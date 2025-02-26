# Analisi Dettagliata dei Colli di Bottiglia - Modulo Tenant

## Panoramica
Il modulo Tenant gestisce il multi-tenancy dell'applicazione. L'analisi ha identificato diverse aree critiche che impattano le performance e la scalabilità.

## 1. Risoluzione Tenant
**Problema**: Risoluzione inefficiente del tenant corrente
- Impatto: Overhead in ogni richiesta
- Causa: Lookup database e cache non ottimizzata

**Soluzione Proposta**:
```php
declare(strict_types=1);

namespace Modules\Tenant\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Tenant\Models\Tenant;
use Spatie\QueueableAction\QueueableAction;

final class TenantResolver
{
    use QueueableAction;

    private const CACHE_TTL = 3600; // 1 ora

    public function resolveTenant(string $identifier): ?Tenant
    {
        return Cache::tags(['tenants'])
            ->remember(
                "tenant_{$identifier}",
                self::CACHE_TTL,
                fn() => $this->findTenant($identifier)
            );
    }

    private function findTenant(string $identifier): ?Tenant
    {
        return Tenant::query()
            ->where('domain', $identifier)
            ->orWhere('subdomain', $identifier)
            ->with(['config', 'settings'])
            ->first();
    }

    public function clearTenantCache(Tenant $tenant): void
    {
        Cache::tags(['tenants'])->forget("tenant_{$tenant->domain}");
        if ($tenant->subdomain) {
            Cache::tags(['tenants'])->forget("tenant_{$tenant->subdomain}");
        }
    }
}
```

## 2. Database Connection
**Problema**: Gestione inefficiente delle connessioni database
- Impatto: Overhead nelle operazioni database
- Causa: Switching connessioni e configurazione non ottimizzata

**Soluzione Proposta**:
```php
declare(strict_types=1);

namespace Modules\Tenant\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Tenant\Models\Tenant;
use Spatie\QueueableAction\QueueableAction;

final class TenantDatabaseManager
{
    use QueueableAction;

    public function configureTenantConnection(Tenant $tenant): void
    {
        $config = $this->getTenantConfig($tenant);
        
        Config::set('database.connections.tenant', $config);
        
        DB::purge('tenant');
        DB::reconnect('tenant');
    }

    private function getTenantConfig(Tenant $tenant): array
    {
        return Cache::tags(['tenant_config'])
            ->remember(
                "tenant_config_{$tenant->id}",
                now()->addHour(),
                fn() => [
                    'driver' => 'mysql',
                    'host' => $tenant->database_host,
                    'database' => $tenant->database_name,
                    'username' => $tenant->database_username,
                    'password' => $tenant->database_password,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'strict' => true,
                    'engine' => null
                ]
            );
    }
}
```

## 3. Asset Management
**Problema**: Gestione inefficiente degli asset per tenant
- Impatto: Latenza nel caricamento asset
- Causa: Mancanza di caching e ottimizzazione

**Soluzione Proposta**:
```php
declare(strict_types=1);

namespace Modules\Tenant\Services;

use Illuminate\Support\Facades\Storage;
use Spatie\QueueableAction\QueueableAction;

final class TenantAssetManager
{
    use QueueableAction;

    public function getAssetUrl(string $path, ?string $disk = null): string
    {
        $tenant = tenant();
        $cacheKey = "asset_url_{$tenant->id}_{$path}";
        
        return Cache::tags(['tenant_assets'])
            ->remember($cacheKey, now()->addDay(), function() use ($tenant, $path, $disk) {
                $disk = $disk ?? "tenant_{$tenant->id}";
                
                if (!Storage::disk($disk)->exists($path)) {
                    return $this->getDefaultAssetUrl($path);
                }
                
                return Storage::disk($disk)->url($path);
            });
    }

    private function getDefaultAssetUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    public function optimizeAssets(): void
    {
        $tenant = tenant();
        $disk = "tenant_{$tenant->id}";
        
        collect(Storage::disk($disk)->allFiles('css'))
            ->each(fn($file) => $this->optimizeCssFile($file, $disk));
            
        collect(Storage::disk($disk)->allFiles('js'))
            ->each(fn($file) => $this->optimizeJsFile($file, $disk));
    }
}
```

## Metriche di Performance

### Obiettivi
- Tempo risoluzione tenant: < 50ms
- Tempo switch database: < 100ms
- Cache hit rate: > 95%
- Memoria per tenant: < 64MB

### Monitoraggio
```php
// In: Providers/TenantServiceProvider.php
private function setupPerformanceMonitoring(): void
{
    // Monitoring tenant
    Event::listen(TenantResolved::class, function ($event) {
        $start = microtime(true);
        
        return function () use ($start) {
            $duration = microtime(true) - $start;
            
            if ($duration > 0.05) { // 50ms
                Log::channel('tenant_performance')
                    ->warning('Risoluzione tenant lenta', [
                        'tenant' => $event->tenant->id,
                        'duration' => $duration
                    ]);
            }
            
            Metrics::timing('tenant.resolution', $duration * 1000);
        };
    });

    // Monitoring database
    DB::listen(function($query) {
        if ($query->time > 100) {
            Log::channel('tenant_performance')
                ->warning('Query tenant lenta', [
                    'sql' => $query->sql,
                    'time' => $query->time,
                    'tenant' => tenant()->id
                ]);
        }
    });
}
```

## Piano di Implementazione

### Fase 1 (Immediata)
- Implementare caching tenant
- Ottimizzare connessioni database
- Migliorare gestione asset

### Fase 2 (Medio Termine)
- Implementare sharding
- Ottimizzare memoria
- Migliorare resilienza

### Fase 3 (Lungo Termine)
- Implementare isolamento completo
- Ottimizzare scalabilità
- Migliorare sicurezza

## Note Tecniche Aggiuntive

### 1. Configurazione Tenant
```php
// In: config/tenant.php
return [
    'database' => [
        'prefix' => env('TENANT_DB_PREFIX', 'tenant_'),
        'suffix' => env('TENANT_DB_SUFFIX', ''),
        'template' => env('TENANT_DB_TEMPLATE', 'template'),
        'auto_create' => env('TENANT_DB_AUTO_CREATE', true),
        'auto_update' => env('TENANT_DB_AUTO_UPDATE', true)
    ],
    'cache' => [
        'ttl' => [
            'config' => env('TENANT_CACHE_CONFIG_TTL', 3600),
            'assets' => env('TENANT_CACHE_ASSETS_TTL', 86400)
        ]
    ],
    'domains' => [
        'subdomain' => env('TENANT_USE_SUBDOMAIN', true),
        'domain' => env('TENANT_USE_DOMAIN', false),
        'path' => env('TENANT_USE_PATH', false)
    ]
];
```

### 2. Ottimizzazione Query
```php
// In: Models/Tenant.php
declare(strict_types=1);

namespace Modules\Tenant\Models;

use Illuminate\Database\Eloquent\Model;

final class Tenant extends Model
{
    protected $casts = [
        'settings' => 'array',
        'features' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('active', function ($query) {
            $query->where('status', 'active');
        });
    }

    public function scopeForDomain($query, string $domain)
    {
        return $query->where('domain', $domain)
                    ->orWhere('domains', 'like', "%{$domain}%")
                    ->orWhere('subdomain', $domain);
    }
}
```

### 3. Gestione Middleware
```php
// In: Middleware/InitializeTenant.php
declare(strict_types=1);

namespace Modules\Tenant\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Tenant\Services\TenantResolver;
use Symfony\Component\HttpFoundation\Response;

final class InitializeTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $identifier = $this->getTenantIdentifier($request);
        
        if (!$identifier) {
            return $next($request);
        }
        
        $tenant = app(TenantResolver::class)
            ->resolveTenant($identifier);
            
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }
        
        tenancy()->initialize($tenant);
        
        return $next($request);
    }

    private function getTenantIdentifier(Request $request): ?string
    {
        return match (true) {
            config('tenant.domains.subdomain') => $request->getHost(),
            config('tenant.domains.path') => $request->segment(1),
            default => null
        };
    }
}
``` 
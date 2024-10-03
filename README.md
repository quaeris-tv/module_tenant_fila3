# module_tenant
Questo modulo può rendere consapevole un tenant dell'app Laravel. La filosofia di questo pacchetto è che dovrebbe fornire solo lo stretto necessario per consentire la multitenancy

## Aggiungere Modulo nella base del progetto
Dentro la cartella laravel/Modules

```bash
git submodule add https://github.com/laraxot/module_tenant_fila3.git Tenant
```

## Verificare che il modulo sia attivo
```bash
php artisan module:list
```
in caso abilitarlo
```bash
php artisan module:enable Tenant
```

## Eseguire le migrazioni
```bash
php artisan module:migrate Tenant
```

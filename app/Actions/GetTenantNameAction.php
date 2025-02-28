<?php

declare(strict_types=1);

namespace Modules\Tenant\Actions;

// use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

class GetTenantNameAction
{
    use QueueableAction;

    public function execute(): string
    {
        // $default = env('APP_URL');
        $default = config('app.url');
        if (! \is_string($default)) {
            $default = 'localhost';
        }

        $default = Str::after($default, '//');

        $server_name = $default;
        if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] !== '127.0.0.1') {
            // $server_name = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
            $server_name = $_SERVER['SERVER_NAME'];
        }
        if (! is_string($server_name)) {
            $server_name = $default;
        }
        $server_name = Str::of($server_name)->replace('www.', '')->toString();

        $tmp = collect(explode('.', $server_name))
            ->map(
                static fn ($item) => Str::slug($item)
            )->reverse()
            ->values();

        $config_file = config_path($tmp->implode(\DIRECTORY_SEPARATOR));

        if (file_exists($config_file)) {
            return $tmp->implode('/');
        }

        $config_file = config_path($tmp->slice(0, -1)->implode(\DIRECTORY_SEPARATOR));
        if (file_exists($config_file) && $tmp->count() > 2) {
            return $tmp->slice(0, -1)->implode('/');
        }

        // default

        $default = str_replace('.', '/', $default);
        if (! file_exists(base_path('config/'.$default))) {
            return 'localhost';
        }

        if ($default === '') {
            return 'localhost';
        }

        return $default;
    }
}

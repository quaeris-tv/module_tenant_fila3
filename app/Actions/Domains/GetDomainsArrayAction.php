<?php

declare(strict_types=1);

namespace Modules\Tenant\Actions\Domains;

// use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

class GetDomainsArrayAction
{
    use QueueableAction;

    public function execute(): array
    {
        $res = $this->recurse(config_path());
        $res1 = $this->collapse($res);
        $res2 = Arr::map($res1, function (string $value) {
            return [
                'id' => $value,
                'name' => $value,
            ];
        });

        return $res2;
    }

    public function recurse(string $path): array
    {
        $filesystem = new Filesystem;
        $directories = $filesystem->directories($path);
        $res = [];
        foreach ($directories as $dir) {
            $name = Str::after($dir, $path.'/');
            if (\in_array($name, ['lang'], true)) {
                continue;
            }
            $res[$name] = $this->recurse($dir);
        }

        return $res;
    }

    public function collapse(array $data, string $k = ''): array
    {
        $res = [];
        foreach ($data as $k0 => $v0) {
            $newkey = ($k === '') ? $k0 : $k0.'.'.$k;
            if ($v0 === []) {
                $res[] = $newkey;
            }

            $res = array_merge($res, $this->collapse($v0, $newkey));
        }

        return $res;
    }
}

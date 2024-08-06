<?php

declare(strict_types=1);

use Illuminate\Support\Str;

$moduleName = 'Tenant';

return [
    'baseUrl' => '',
    'production' => false,
    'siteName' => 'Modulo '.$moduleName,
    'siteDescription' => 'Modulo '.$moduleName,
    'lang' => 'it',

    'collections' => [
        'posts' => [
            'path' => static fn ($page): string =>
                // return $page->lang.'/posts/'.Str::slug($page->getFilename());
                // return 'posts/' . ($page->featured ? 'featured/' : '') . Str::slug($page->getFilename());
                'posts/'.Str::slug($page->getFilename()),
        ],
        'docs' => [
            'path' => static fn ($page): string =>
                // return $page->lang.'/docs/'.Str::slug($page->getFilename());
                'docs/'.Str::slug($page->getFilename()),
        ],
    ],

    // Algolia DocSearch credentials
    'docsearchApiKey' => env('DOCSEARCH_KEY'),
    'docsearchIndexName' => env('DOCSEARCH_INDEX'),

    // navigation menu
    'navigation' => include_once __DIR__.'/navigation.php',

    // helpers
    'isActive' => static fn ($page, $path) => Str::endsWith(trimPath($page->getPath()), trimPath($path)),
    'isItemActive' => static fn ($page, $item) => Str::endsWith(trimPath($page->getPath()), trimPath($item->getPath())),
    'isActiveParent' => static function ($page, $menuItem): void {
        if (! is_object($menuItem)) {
            return;
        }

        if (! $menuItem->children) {
            return;
        }

        return $menuItem->children->contains(static fn ($child): bool => trimPath($page->getPath()) === trimPath($child));
    }, /*
    'url' => function ($page, $path) {
        return Str::startsWith($path, 'http') ? $path : '/' . trimPath($path);
    },
    */
    'url' => static function ($page, $path) {
        if (Str::startsWith($path, 'http')) {
            return $path;
        }

        // return url('/'.$page->lang.'/'.trimPath($path));
        return url('/'.trimPath($path));
    },

    'children' => static fn ($page, $docs) => $docs->where('parent_id', $page->id),
];

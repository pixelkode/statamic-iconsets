<?php

namespace Pixelkode\Iconsets;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $vite = [
        'input' => [
            'resources/js/iconset.js',
        ],
        'publicDirectory' => 'dist',
    ];

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();

        // Register Control Panel routes
        $this->loadRoutesFrom(__DIR__.'/../routes/cp.php');

        // Register translations with custom namespace
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'pixelkode-iconsets');
    }
}

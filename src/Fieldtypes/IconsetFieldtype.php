<?php

namespace Pixelkode\Iconsets\Fieldtypes;

use Statamic\Facades\File;
use Statamic\Facades\Folder;
use Statamic\Facades\Path;
use Statamic\Fields\Fieldtype;

class IconsetFieldtype extends Fieldtype
{
    protected $categories = ['media'];
    protected $icon = 'icon_picker';

    /**
     * Preload data for the Vue component.
     */
    public function preload(): array
    {
        $directory = $this->config('directory', 'resources/svg/icons');
        $folders = $this->config('folders', []);

        return [
            'url' => cp_route('iconset-fieldtype'),
            'directory' => $directory,
            'folders' => $folders,
        ];
    }

    /**
     * Get all icons from configured folders.
     */
    public function icons(): array
    {
        $directory = $this->config('directory', 'resources/svg/icons');
        $folders = $this->config('folders', []);
        $icons = [];

        foreach ($folders as $folder) {
            $path = Path::tidy(base_path($directory.'/'.$folder));

            // Skip if folder doesn't exist
            if (! Folder::exists($path)) {
                continue;
            }

            // Get all SVG files in this folder
            $svgFiles = Folder::getFilesByType($path, 'svg');

            foreach ($svgFiles as $filePath) {
                $filename = pathinfo($filePath, PATHINFO_FILENAME);

                // Store as folder => [filename => svg content]
                $icons[$folder][$filename] = File::get($filePath);
            }
        }

        return $icons;
    }

    /**
     * Configure the fieldtype settings in the blueprint editor.
     */
    protected function configFieldItems(): array
    {
        return [
            [
                'display' => __('pixelkode-iconsets::messages.section.selection'),
                'fields' => [
                    'directory' => [
                        'display' => __('pixelkode-iconsets::messages.fields.directory.display'),
                        'instructions' => __('pixelkode-iconsets::messages.fields.directory.instructions'),
                        'type' => 'text',
                        'default' => 'resources/svg/icons',
                        'placeholder' => 'resources/svg/icons',
                        'width' => 50,
                    ],
                    'folders' => [
                        'display' => __('pixelkode-iconsets::messages.fields.folders.display'),
                        'instructions' => __('pixelkode-iconsets::messages.fields.folders.instructions'),
                        'type' => 'list',
                        'width' => 50,
                    ],
                    'default' => [
                        'display' => __('pixelkode-iconsets::messages.fields.default.display'),
                        'instructions' => __('pixelkode-iconsets::messages.fields.default.instructions'),
                        'type' => 'text',
                    ],
                ],
            ],
        ];
    }

    /**
     * Augment the value for template rendering.
     * Converts "folder/filename" to full SVG content.
     */
    public function augment($value)
    {
        if (! $value || ! str_contains($value, '/')) {
            return null;
        }

        [$folder, $filename] = explode('/', $value, 2);
        $directory = $this->config('directory', 'resources/svg/icons');
        $path = base_path("{$directory}/{$folder}/{$filename}.svg");

        return File::exists($path) ? File::get($path) : null;
    }
}

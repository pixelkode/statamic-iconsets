<?php

namespace Pixelkode\Iconsets\Http\Controllers;

use Facades\Statamic\Fields\FieldtypeRepository as Fieldtype;
use Illuminate\Http\Request;
use Statamic\Fields\Field;
use Statamic\Http\Controllers\CP\CpController;

class IconsetFieldtypeController extends CpController
{
    /**
     * Handle the icon request from the Vue component.
     */
    public function __invoke(Request $request)
    {
        $fieldtype = $this->fieldtype($request);

        return [
            'icons' => $fieldtype->icons(),
        ];
    }

    /**
     * Get the fieldtype instance with config.
     */
    protected function fieldtype($request)
    {
        $config = $this->getConfig($request);

        return Fieldtype::find('iconset')->setField(
            new Field('iconset', $config)
        );
    }

    /**
     * Decode the base64-encoded config from the request.
     */
    private function getConfig($request)
    {
        // The fieldtype base64-encodes the config
        $json = base64_decode($request->config);

        // The json may include unicode characters, so we'll try to convert it to UTF-8
        $utf8 = mb_convert_encoding($json, 'UTF-8', mb_list_encodings());

        // In PHP 8.1 there's a bug where encoding will return null. It's fixed in 8.1.2.
        // In this case, we'll fall back to the original JSON, but without the encoding.
        $json = empty($utf8) ? $json : $utf8;

        return json_decode($json, true);
    }
}

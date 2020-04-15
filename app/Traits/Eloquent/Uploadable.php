<?php

namespace App\Traits\Eloquent;

use Illuminate\Support\Facades\Storage;

trait Uploadable
{
    public function upload($guid, $file, $storage = 'public')
    {
        $filename = $guid . '_' . str_replace(' ', '_', $file["name"]);
        $path = Storage::disk($storage)->put($filename, base64_decode(explode(',', $file["content"])[1]));

        if ($path) {
            return asset('storage/uploads/'.$filename);
        }

        return null;
    }
}
<?php

namespace App\Models;

use App\Traits\Eloquent\Uploadable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use Uploadable;

    protected $fillable = ['name', 'guid', 'path', 'person_id'];

    function __construct(array $attributes = array()) {
        parent::__construct($attributes);
    }

    static function GUID()
    {
        if (function_exists('com_create_guid') === true){
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public static function saveFile($request, $person_id = NULL) {
        $file = new File;
        $file->name = $request['name'];
        $file->guid = File::GUID();
        $file->path = $file->upload($file->guid, $request);
        $file->person_id = $person_id;

        $file->save();

        return response()->json([
            'message' => 'Attachment has been successfully uploaded.',
        ]);
    }

    public static function deleteFile($name) {
        Storage::disk('public')->delete($name);
    }

    public function person() {
        return $this->belongsTo('App\Person');
    }
}

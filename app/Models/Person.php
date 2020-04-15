<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Person extends Model
{
    protected $fillable = ['id', 'record_type', 'car', 'region', 'initials', 'birth_date', 'pasport_data', 'receipt_info', 'phone', 'email', 'card', 'notation', 'status', 'created_at',
     'updated_at', 'NDS'];

    function __construct(array $attributes = array()) {
        parent::__construct($attributes);
    }

    public static function add($request){
        $command = $request->command;
        if($command == "add") {
            return false;
        }

        try{
            $person = new Person($request->person);
            $person->save();

            if(count($request->person['files']) > 0) {
                foreach($request->person['files'] as $file) {
                    File::saveFile($file, $person->id);
                }
            }

            History::add($person, $command);
            return $person->id;
        }catch(QueryException $ex) {
            return response()->json($ex);
        }
    }

    public static function remove($id) {
        try{
            Person::where('id', $id)->delete();
            return strval(true);
        }catch(QueryException $ex){
            return strval(false);
        }
    }

    public function histories() {
        return $this->hasMany('App\Models\History');
    }

    public function calls() {
        return $this->hasMany('App\Models\Call');
    }

    public function verifications() {
        return $this->hasMany('App\Models\Verification');
    }

    public function outgoingCall () {
        return $this->belongsToMany('App\Models\Task')->using('App\Models\PersonTask')
        ->where([['type', '=', 'Исходящий звонок']])->orderBy('started_at')->take(1);
    }

    public function tasks() {
        return $this->belongsToMany('App\Models\Task')->using('App\Models\PersonTask');
    }

    public static function updateDate($updated_at, $id) {
        return Person::where('id', $id)->update(array('updated_at' => $updated_at));
    }

    public function files() {
        return $this->hasMany('App\Models\File');
    }

    public static function updatePerson($request, $id) {
        $dbPerson = Person::find($id);
        dd($dbPerson);
        $person = new Person($request->person);

        if ($dbPerson != null) {
            date_default_timezone_set('Europe/Moscow');
            $person->updated_at = date('Y-m-d H:i:s', time());
            $command = $request->command;

            if(count($request->person['files']) > 0) {
                //foreach($dbPerson)
                foreach($request->person['files'] as $file) {
                    File::saveFile($file, $person->id);
                }
            }

            if($command == "update") {
                return 0;
            }

            History::add($person, $command);

            return Person::where('id', $id)->update($person->attributes);
        }
        return 0;
    }
}

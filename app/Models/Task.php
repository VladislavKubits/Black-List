<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['id', 'title', 'description', 'type', 'author_id', 'author', 'responsible_id', 'responsible', 'started_at', 'finished_at',
     'status', 'notification_status', 'notification_value', 'created_at', 'updated_at'];

    function __construct(array $attributes = array()) {
        parent::__construct($attributes);
    }

    public function author() {
        return $this->belongsTo('App\Models\User', 'author_id');
    }

    public function responsible() {
        return $this->belongsTo('App\Models\User', 'responsible_id');
    }

    public function histories() {
        return $this->hasMany('App\Models\History');
    }

    public function people() {
        return $this->belongsToMany('App\Models\Person')->using('App\Models\PersonTask');
    }

    public static function add($request){
        $command = $request->command;
        try{
            $task = new Task($request->task);
            $task->save();
            $people = $request->task["people"];
            if (count($people) > 0) {
                $peopleId = [];
                foreach($people as $person) {
                    array_push($peopleId, $person['id']);
                }
                $task->people()->sync($peopleId);
            }

            History::addTask($task, $command);
            return $task->id;
        }catch(QueryException $ex) {
            return response()->json($ex);
        }
    }

    public static function remove($id) {
        try{
           Task::where('id', $id)->delete();
            return strval(true);
        }catch(QueryException $ex){
            return strval(false);
        }
    }

    public static function updateTask($request, $id) {
        $dbTask = Task::find($id);
        $task = new Task($request->task);

        if ($dbTask != null) {
            date_default_timezone_set('Europe/Moscow');
            $task->updated_at = date('Y-m-d H:i:s', time());

            $people = $request->task["people"];
            if (count($people) > 0) {
                $peopleId = [];
                foreach($people as $person) {
                    array_push($peopleId, $person['id']);
                }
                $task->people()->sync($peopleId);
            } else {
                $task->people()->sync([]);
            }

            $command = $request->command;
            if($command == "update") {
                return 0;
            }
            History::addTask($task, $command);

            unset($task->attributes['author']);
            unset($task->attributes['responsible']);

            return Task::where('id', $id)->update($task->attributes);
        }
        return 0;
    }
}

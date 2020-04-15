<?php

namespace App\Models;

use App\Services\HistoryService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class History extends Model
{
    protected $table = 'histories';

    protected $fillable = ['id', 'person_id', 'user_id', 'task_id'];

    function __construct(array $attributes = array()) {
        parent::__construct($attributes);
    }

    public static function add($person, $command) {
        try{
            $history = new History();
            $history->person_id = $person->id;
            $history->user_id = Auth::id();
            $history->info = HistoryService::getHistoryDescription($person, $command);
            $history->save();
            
            return $history->id;
        }catch(QueryException $ex) {
            return $ex->errorInfo;
        }
    }

    public static function addTask($task, $command) {
        try{
            $history = new History();
            $history->task_id = $task->id;
            $history->user_id = Auth::id();
            $history->info = HistoryService::getHistoryDescription($task, $command);
            $history->save();
            
            return $history->id;
        }catch(QueryException $ex) {
            return $ex->errorInfo;
        }
    }

    public static function verificationAdd($verification, $command) {
        try{
            $history = new History();
            $history->person_id = $verification->person_id;
            $history->user_id = Auth::id();
            $history->info = HistoryService::getHistoryDescription($verification, $command);
            $history->save();
            return $history->id;
        }catch(QueryException $ex) {
            return $ex->errorInfo;
        }
    }

    public static function callAdd($call, $command) {
        try{
            $history = new History();
            $history->person_id = $call->person_id;
            $history->user_id = Auth::id();
            $history->info = HistoryService::getHistoryDescription($call, $command);
            $history->save();
            return $history->id;
        }catch(QueryException $ex) {
            return $ex->errorInfo;
        }
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function person() {
        return $this->belongsTo('App\Models\Person');
    }

    public function task() {
        return $this->belongsTo('App\Models\Task');
    }
}

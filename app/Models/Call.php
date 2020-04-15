<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $fillable = ['id', 'type', 'notation', 'status', 'person_id', 'user_id', 'created_at'];

    function __construct(array $attributes = array()) {
        parent::__construct($attributes);
    }

    public static function add($request) {
        try{
            $call = new Call($request->all());
            $call->save();

            History::callAdd($call, "add call");
            Person::updateDate($request->created_at, $request->person_id);
            return $call->id;
        }catch(QueryException $ex) {
            return response()->json($ex);
        }
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function person() {
        return $this->belongsTo('App\Models\Person');
    }
}

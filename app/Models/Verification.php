<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    protected $fillable = ['id', 'initials', 'notation', 'status', 'person_id', 'user_id', 'created_at'];

    function __construct(array $attributes = array()) {
        parent::__construct($attributes);
    }

    public static function add($request) {
        try{
            $verification = new Verification($request->all());
            $verification->save();

            History::verificationAdd($verification, "add verification");
            Person::updateDate($request->created_at, $request->person_id);

            return $verification->id;
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

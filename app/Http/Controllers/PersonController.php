<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PersonController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = null;
        if ($_GET["paginate"]) {
            $sortParam = $_GET['sort_param'];
            $course = $sortParam === 'updated_at' ? 'desc' : 'asc';
            $param3 = $_GET['param3'];


            if($_GET['sort_param'] === 'outgoing_call_date') {
                if ($_GET['param1'] != '' || $_GET['param2'] != '') {
                    if ($_GET['param1'] != '' && $_GET['param2'] != '') {
                        $param1 =  $_GET['param1'];
                        $param2 =  $_GET['param2'];
                        
                        $items = Person::select('id', 'record_type', 'car', 'initials', 'birth_date', 'pasport_data', 'receipt_info', 'phone', 'status', 'updated_at')
                            ->with(['outgoingCall'])
                            ->where([['car', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                            ->orWhere([['initials', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                            ->orWhere([['birth_date', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                            ->orWhere([['pasport_data', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                            ->orWhere([['receipt_info', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                            ->orWhere([['phone', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                            ->orWhere([['card', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                            ->orWhere([['notation', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                            ->orWhere([['region', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                            ->paginate(10);
                    } else {
                        $param = $_GET['param1'] ? $_GET['param1'] : $_GET['param2'];
    
                        $items = Person::select('id', 'record_type', 'car', 'initials', 'birth_date', 'pasport_data', 'receipt_info', 'phone', 'status', 'updated_at')
                            ->with(['outgoingCall'])
                            ->where([['car', 'like', "%$param%"], ['record_type', '=', $param3]])
                            ->orWhere([['initials', 'like', "%$param%"], ['record_type', '=', $param3]])
                            ->orWhere([['birth_date', 'like', "%$param%"], ['record_type', '=', $param3]])
                            ->orWhere([['pasport_data', 'like', "%$param%"], ['record_type', '=', $param3]])
                            ->orWhere([['receipt_info', 'like', "%$param%"], ['record_type', '=', $param3]])
                            ->orWhere([['phone', 'like', "%$param%"], ['record_type', '=', $param3]])
                            ->orWhere([['card', 'like', "%$param%"], ['record_type', '=', $param3]])
                            ->orWhere([['notation', 'like', "%$param%"], ['record_type', '=', $param3]])
                            ->orWhere([['region', 'like', "%$param%"], ['record_type', '=', $param3]])
                            ->orWhere([['status', '=', $param], ['record_type', '=', $param3]])
                            ->paginate(10);
                    }
                } else {
                    $items = Person::select('id', 'record_type', 'car', 'initials', 'birth_date', 'pasport_data', 'receipt_info', 'phone', 'status', 'updated_at')
                        ->with(['outgoingCall'])
                        ->where([['record_type', '=', $param3]])->paginate(10);
                }

                return $items;
            }


            if ($_GET['param1'] != '' || $_GET['param2'] != '') {
                if ($_GET['param1'] != '' && $_GET['param2'] != '') {
                    $param1 =  $_GET['param1'];
                    $param2 =  $_GET['param2'];
                    
                    $items = Person::select('id', 'record_type', 'car', 'initials', 'birth_date', 'pasport_data', 'receipt_info', 'phone', 'status', 'updated_at')
                        ->with(['outgoingCall'])
                        ->where([['car', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                        ->orWhere([['initials', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                        ->orWhere([['birth_date', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                        ->orWhere([['pasport_data', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                        ->orWhere([['receipt_info', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                        ->orWhere([['phone', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                        ->orWhere([['card', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                        ->orWhere([['notation', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                        ->orWhere([['region', 'like', "%$param1%"], ['status', '=', $param2], ['record_type', '=', $param3]])
                        ->orderBy($sortParam, $course)
                        ->paginate(10);
                } else {
                    $param = $_GET['param1'] ? $_GET['param1'] : $_GET['param2'];

                    $items = Person::select('id', 'record_type', 'car', 'initials', 'birth_date', 'pasport_data', 'receipt_info', 'phone', 'status', 'updated_at')
                        ->with(['outgoingCall'])
                        ->where([['car', 'like', "%$param%"], ['record_type', '=', $param3]])
                        ->orWhere([['initials', 'like', "%$param%"], ['record_type', '=', $param3]])
                        ->orWhere([['birth_date', 'like', "%$param%"], ['record_type', '=', $param3]])
                        ->orWhere([['pasport_data', 'like', "%$param%"], ['record_type', '=', $param3]])
                        ->orWhere([['receipt_info', 'like', "%$param%"], ['record_type', '=', $param3]])
                        ->orWhere([['phone', 'like', "%$param%"], ['record_type', '=', $param3]])
                        ->orWhere([['card', 'like', "%$param%"], ['record_type', '=', $param3]])
                        ->orWhere([['notation', 'like', "%$param%"], ['record_type', '=', $param3]])
                        ->orWhere([['region', 'like', "%$param%"], ['record_type', '=', $param3]])
                        ->orWhere([['status', '=', $param], ['record_type', '=', $param3]])
                        ->orderBy($sortParam, $course)
                        ->paginate(10);
                }
                return $items;
            } else {
                $items = Person::select('id', 'record_type', 'car', 'initials', 'birth_date', 'pasport_data', 'receipt_info', 'phone', 'status', 'updated_at')
                    ->with(['outgoingCall'])
                    ->where([['record_type', '=', $param3]])->orderBy($sortParam, $course)->paginate(10);

                return $items;
            }
        } else {
            return Person::select('id', 'initials')->where([['initials', '<>', null]])->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd(__METHOD__);
    }

    public function drivers() {
        if(!isset($_GET['name'])) {
            return redirect("/");
        }

        $sqlString = "select id as Id, initials as Name, substring_index(phone, ',', 1) as Phone, case when status = 'новый' then 0 else 1 end as IsTested, NDS, car as Cars,
         region as Area from people where record_type = 'водитель'";

        if(isset($_GET['name']) && $_GET['name'] != null) {
            $name = $_GET['name'];
            $sqlString .= " and initials like '%$name%'";
        }
        if(isset($_GET['cars']) && $_GET['cars'] != null) {
            $cars = explode(',', $_GET['cars']);

            if(count($cars) > 0 && $cars[0] != "") {
                $sqlString .= " and (car like '%$cars[0]%'";
                unset($cars[0]);
                foreach($cars as $car) {
                    $sqlString .= " or car like '%$car%'";
                }
                $sqlString .= ")";
            }
        }
        if(isset($_GET['isVerified']) && $_GET['isVerified'] != null) {
            $isVerified = $_GET['isVerified'];
            $sqlString .= " and case when status = 'новый' then 0 else 1 end = $isVerified";
        }
        if(isset($_GET['NDS']) && $_GET['NDS'] != null) {
            $NDS = $_GET['NDS'];
            $sqlString .= " and NDS = $NDS";
        }
        if(isset($_GET['regions']) && $_GET['regions'] != null) {
            $regions = explode(',', $_GET['regions']);
            if(count($regions) > 0 && $regions[0] != "") {
                $sqlString .= " and (region like '%$regions[0]%'";
                unset($regions[0]);
                foreach($regions as $region) {
                    $sqlString .= " or region like '%$region%'";
                }
                $sqlString .= ")";
            }
        }

        $drivers = DB::select($sqlString);

        foreach($drivers as $driver) {
            $phone = $driver->Phone;
            if(strlen($phone) == 11 && $phone[0] == '8') {
                $phone = substr($phone, 1);
            } elseif(strlen($phone) == 12 && substr($phone, 0, 2) == "+7") {
                $phone = substr($phone, 2);
            }
            $driver->Phone = $phone;

            $driver->Cars = explode(',', $driver->Cars);
            if($driver->Cars[0] == "") {
                unset($driver->Cars[0]);
            }
        }

        return $drivers;
    }

    public function unique_phone() {
        if($_GET['phone'] && $_GET['record_type']) {
            $phoneArray = preg_split("/ |,/", $_GET['phone']);
            $record_type = $_GET['record_type'];
            foreach($phoneArray as $phone) {
                if($phone) {
                    if(strlen($phone) == 11 && $phone[0] == '8') {
                        $phone = substr($phone, 1);
                    } elseif(strlen($phone) == 12 && substr($phone, 0, 2) == "+7") {
                        $phone = substr($phone, 2);
                    }
                    
                    $results = Person::select('id', 'phone')->where([['phone', 'like', "%$phone%"], ['record_type', '=', $record_type]])->get();
                    if(count($results) > 0) {
                        return $results[0]->id;
                    }
                }
            }
        }
        return 0;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Person::add($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Person::with([
            'histories' => function($q){
                $q->orderBy('created_at', 'desc');
            },'histories.user',
            'calls' => function($q){
                $q->orderBy('created_at', 'desc');
            },'calls.user',
            'verifications' => function($q){
                $q->orderBy('created_at', 'desc');
            },'verifications.user',
            'tasks' => function($q){
                $q->orderBy('updated_at', 'desc');
            }, 'files'])->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd(__METHOD__);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return Person::updatePerson($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $person = Person::with(['files'])->find($id);
        if(count($person->files) > 0) {
            foreach($person->files as $file) {
                dd('uploads/'.$file['guid'].'_'.$file['name']);
                Storage::disk('public')->delete('uploads/'.$file['guid'].'_'.$file['name']);
            }
        }
        return Person::remove($id);
    }

    public function statistic(Request $request) {
        $dateList = $request->all();
        $dayContactsCounts = [];

        foreach($dateList as $day) {
            $count = Person::where([['created_at', 'like', "%$day%"]])->count();
            array_push($dayContactsCounts, $count);
        }

        return $dayContactsCounts;
    }
}
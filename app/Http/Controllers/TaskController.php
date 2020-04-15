<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = null;

        $sortParam = $_GET['sort_param'];
        $course = $sortParam === 'updated_at' || 'started_at' || 'finished_at' ? 'desc' : 'asc';

        if ($_GET['param1'] != '' || $_GET['param2'] != '') {
            if ($_GET['param1'] != '' && $_GET['param2'] != '') {
                $param1 = $_GET['param1'];
                $param2 = $_GET['param2'];

                $items = Task::with([
                    'histories' => function($q){
                        $q->orderBy('created_at', 'desc');
                    },'author', 'responsible',
                    'people' => function($q){
                        $q->select('id', 'phone', 'initials');
                        $q->orderBy('created_at', 'desc');
                    }])->select('id', 'title', 'type', 'author_id', 'responsible_id', 'started_at', 'finished_at', 'status', 'notification_status', 'notification_value',
                 'created_at', 'updated_at')
                    ->where([['title', 'like', "%$param1%"], ['status', '=', $param2]])
                    ->orWhere([['type', 'like', "%$param1%"], ['status', '=', $param2]])
                    ->orderBy($sortParam, $course)
                    ->paginate(10);
            } else{
                $param1 = $_GET['param1'] ? $_GET['param1'] : $_GET['param2'];

                $items = Task::with([
                    'histories' => function($q){
                        $q->orderBy('created_at', 'desc');
                    },'author', 'responsible',
                    'people' => function($q){
                        $q->orderBy('created_at', 'desc');
                    }])->select('id', 'title', 'type', 'author_id', 'responsible_id', 'started_at', 'finished_at', 'status', 'notification_status', 'notification_value',
                 'created_at', 'updated_at')
                    ->where([['title', 'like', "%$param1%"]])
                    ->orWhere([['type', 'like', "%$param1%"]])
                    ->orWhere([['status', 'like', "%$param1%"]])
                    ->orderBy($sortParam, $course)
                    ->paginate(10);
            }
        } else {
            $items = Task::with([
                'histories' => function($q){
                    $q->orderBy('created_at', 'desc');
                },'author', 'responsible',
                'people' => function($q){
                    $q->orderBy('created_at', 'desc');
                }])->select('id', 'title', 'type', 'author_id', 'responsible_id', 'started_at', 'finished_at', 'status', 'notification_status', 'notification_value',
             'created_at', 'updated_at')->orderBy($sortParam, $course)->paginate(10);
        }
        return $items;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Task::add($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Task::with([
            'histories' => function($q){
                $q->orderBy('created_at', 'desc');
            },'histories.user', 'author', 'responsible',
            'people' => function($q){
                $q->orderBy('created_at', 'desc');
            }])->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        return Task::updateTask($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Task::remove($id);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MypageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {

        // 取得したデータ
        $datetime = Carbon::now();
        $year = $datetime->year;
        $month = $datetime->month;
        $result = DB::table('csv_attendances')->where([
                    ['user_id', '=', $id],
                    ['year', '=', $year],
                    ['month', '=', $month]
                    ])->get();
        // return view('mypage',['datetime' => $datetime, 'result' => $result]);
        return view('mypage',compact('datetime','result','month','id'));


    }

    public function next($id,$month)
    {
        // データを取得
        $datetime = Carbon::now();
        $year = $datetime->year;
        $month = ($month) +1;    //翌月

        $result = DB::table('csv_attendances')->where([
            ['user_id', '=', $id],
            ['year', '=', $year],
            ['month', '=', $month]
            ])->get();
        if($result->isEmpty()){
            $msg = $year.'年'.$month.'月の勤務表は登録されていません';
        }else{
            $msg = "";
        }
        return view('mypage',compact('datetime','result','month','id','msg'));
    }

    public function before($id,$month)
    {
        // データを取得
        $datetime = Carbon::now();
        $year = $datetime->year;
        $month = ($month) -1;    //先月

        $result = DB::table('csv_attendances')->where([
            ['user_id', '=', $id],
            ['year', '=', $year],
            ['month', '=', $month]
            ])->get();
        if($result->isEmpty()){
            $msg = $year.'年'.$month.'月の勤務表は登録されていません';
        }else{
            $msg = "";
        }
        return view('mypage',compact('datetime','result','month','id','msg'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

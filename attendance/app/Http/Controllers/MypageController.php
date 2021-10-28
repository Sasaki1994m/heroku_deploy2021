<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Timestamp;
use App\CsvAttendance;
use Log;

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
        $users = DB::table('users')->where([
                    ['id', '=', $id],
                    ])->get();
        $pay = $users[0]->pay;
        $total = 0;
        foreach($result as $inside){

            if($inside->punch_out != NULL){
            $punchout = strtotime($inside->punch_out);
            $punchin = strtotime($inside->punch_in);
            $work = ($punchout - $punchin)/60/60;   //１日の勤務時間
            $oneday = round($work, 1);              //小数点第一位を四捨五入
            $oneday = $oneday -1.0;                 //休憩時間を追加
            $total += $pay * $oneday;
            }
        }
        if($result->isEmpty()){
            $msg = '今月の勤務表が登録されていません';
        }else{
            $msg = "";
        }
        return view('mypage',compact('datetime','result','month','id','msg','users','total'));
        // return response()->json(['tasks' => $result]);

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

    public function getAllMessage(Request $request)
    {

        Log::debug($request);
        // 変数を設定する
        $id = Auth::id();
        $datetime = Carbon::now();
        $year = $datetime->year;
        $month = $datetime->month;        

        // データ更新
        \DB::beginTransaction();
        try{
            $kintai = DB::table('csv_attendances')->where([
                ['user_id', '=', $id],
                ['month', '=', $month],
                ['day', '=', $request->days],
                ])
                ->update([
                    'punch_in' => $request->punchin ,
                    'punch_out' => $request->punchout ,
                    'status' => 1 ,
                ]);
                \DB::commit();

            $result = DB::table('csv_attendances')->where([
                        ['user_id', '=', $id],
                        ['year', '=', $year],
                        ['month', '=', $month],
                        ])->get();
            $users = DB::table('users')->where([
                ['id', '=', $id],
                ])->get();
            $pay = $users[0]->pay;
            $total = 0;
            foreach($result as $inside){
    
                if($inside->punch_out != NULL){
                $punchout = strtotime($inside->punch_out);
                $punchin = strtotime($inside->punch_in);
                $work = ($punchout - $punchin)/60/60;   //１日の勤務時間
                $oneday = round($work, 1);              //小数点第一位を四捨五入
                $oneday = $oneday -1.0;                 //休憩時間を追加
                $total += $pay * $oneday;
                }
            }

            // return response()->json(['tasks' => $result])->with('status','出勤打刻が完了しました。');
            return [$datetime, $result, $month, $id,$users,$total];

        }catch(\Exception $e){
            
			\DB::rollBack();
            return back()->with('status','退勤打刻に失敗しました。');
        }
    }
}

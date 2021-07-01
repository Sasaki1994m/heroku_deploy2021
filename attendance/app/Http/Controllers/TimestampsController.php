<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Timestamp;
use App\CsvAttendance;
use Carbon\Carbon;

class TimestampsController extends Controller
{
    public function list(){
        $daytime = Carbon::now();
        $year  = $daytime->year ;
        $month = $daytime->month;
        $day = $daytime->day;

        $today = CsvAttendance::query();
        $today->where('year', $year); 
        $today->where('month', $month); 
        $today->where('day', $day); 
        $timestamp = $today->paginate();
        return view('list',['timestamp' => $timestamp]);
    }

    public function create(){

        return view('create');
    }

    public function store(Request $request, $id)
    {
        $daytime = Carbon::now();
        $year  = $daytime->year ;
        $month = $daytime->month;
        $day = $daytime->day;
        $yesterday = Carbon::yesterday();
        $before =  $yesterday->day; //前日
        //昨日が退勤打刻されているか判定
        $yesterday = DB::table('csv_attendances')->where([
            ['user_id', '=', $id],
            ['year', '=', $year],
            ['month', '=', $month],
            ['day', '=', $before]
            ])->get();

        foreach($yesterday as $value_data){
            $data =  [
                $value_data->work_start,
                $value_data->punch_in,
                $value_data->punch_out,
            ];
        }
        if($yesterday->isEmpty()){
            return back()->with('error','勤務表が確認出来ませんでした。');
        }
        if($data[0] != null && $data[1] != null && $data[2] === null ){
            return back()->with('error','退勤打刻がされていません。');
        }

        //本日が出勤日なのか、打刻済みでないか判定
        $result = DB::table('csv_attendances')->where([
            ['user_id', '=', $id],
            ['year', '=', $year],
            ['month', '=', $month],
            ['day', '=', $day]
            ])->get();

            foreach($result as $value){
                $data =  [
                    $value->work_start,
                    $value->punch_in,
                ];
            }
            if($data[0] === null){
                return back()->with('error','本日は出勤日ではありません。');
            }elseif($data[1] != null){
                return back()->with('error','既に出勤打刻済みです。');
            }
        \DB::beginTransaction();
        try{
            $timestamp = DB::table('csv_attendances')->where([
                            ['user_id', '=', $id],
                            ['year', '=', $year],
                            ['month', '=', $month],
                            ['day', '=', $day]
                            ])
                        ->update(['punch_in' => Carbon::now() ]);
            \DB::commit();
            return redirect(route('mypage.index',[ 'id' => $id]))->with('status','出勤打刻が完了しました。');
		} catch (\Exception $e) {
			// エラー発生時は、DBへの保存処理が無かったことにする（ロールバック）
			\DB::rollBack();
            return back()->with('status','出勤打刻に失敗しました。');
        }
        
    }

    public function update(Request $request, $id)
    {

        $daytime = Carbon::now();
        $year  = $daytime->year ;
        $month = $daytime->month;
        $day = $daytime->day;

        //今日が出勤日か、出勤打刻がされているか、既に退勤打刻されているか判定
        $today = DB::table('csv_attendances')->where([
            ['user_id', '=', $id],
            ['year', '=', $year],
            ['month', '=', $month],
            ['day', '=', $day]
            ])->get();

        foreach($today as $value_data_2){
            $data =  [
                $value_data_2->work_start,
                $value_data_2->punch_in,
                $value_data_2->punch_out,
            ];
        }
        if($today->isEmpty()){
            return back()->with('error','勤務表が確認出来ませんでした。');
        }
        if($data[0] === null){
            return back()->with('error','本日は出勤日ではありません。');
        }elseif($data[0] != null && $data[1] === null ){
            return back()->with('error','出勤打刻がされていません。');
        }elseif($data[2] != null){
            return back()->with('error','既に退勤打刻済みです。');
        }
        // 更新処理
        \DB::beginTransaction();
        try{
            $timestamp2 = DB::table('csv_attendances')->where([
                            ['user_id', '=', $id],
                            ['year', '=', $year],
                            ['month', '=', $month],
                            ['day', '=', $day]
                            ])
                        ->update(['punch_out' => Carbon::now() ]);
            \DB::commit();
            return redirect(route('mypage.index',[ 'id' => $id]))->with('status','出勤打刻が完了しました。');
		} catch (\Exception $e) {
			// エラー発生時は、DBへの保存処理が無かったことにする（ロールバック）
			\DB::rollBack();
            return back()->with('status','退勤打刻に失敗しました。');
        }
        
    }
}

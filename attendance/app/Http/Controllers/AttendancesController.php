<?php

 namespace App\Http\Controllers;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Database\Eloquent\Model;
 use Symfony\Component\HttpFoundation\StreamedResponse;
 use App\Http\Requests;
 use App\CsvAttendance;
 use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yasumi\Yasumi;


 //useしないと 自動的にnamespaceのパスが付与されるのでuse
 use SplFileObject;
  
 class AttendancesController extends Controller
 {
    protected $csvimport = null;
 
     public function __construct(CsvAttendance $csvimport)
    {
        $this->csvimport = $csvimport;
    }
  
     public function index()
     {
         return view('inport');
     }

     /**
      * CSVインポート
      *
      * @param  Request
      * @return \Illuminate\Http\Response
      */
     public function inport(Request $request)
    {
        $auth_id = Auth::id();

        if(!empty($request->file('csv_file'))){
            //全件削除
            //   CsvAttendance::truncate();
        
            // ロケールを設定(日本語に設定)
            setlocale(LC_ALL, 'ja_JP.UTF-8');
        
            // アップロードしたファイルを取得
            // 'csv_file' はビューの inputタグのname属性
            $uploaded_file = $request->file('csv_file');
            // アップロードしたファイルの絶対パスを取得
            $file_path = $request->file('csv_file')->path($uploaded_file);
            //SplFileObjectを生成
            $file = new SplFileObject($file_path);
            //SplFileObject::READ_CSV が最速らしい
            $file->setFlags(SplFileObject::READ_CSV);
            $row_count = 1;
            //取得したオブジェクトを読み込み
            foreach ($file as $row)
            {
                // 最終行の処理(最終行が空っぽの場合の対策
                if ($row === [null]) continue; 

                // 1行目のヘッダーは取り込まない
                if ($row_count > 1)
                {
                    // CSVの文字コードがSJISなのでUTF-8に変更
                    $year = mb_convert_encoding($row[0], 'UTF-8', 'SJIS');
                    $month = mb_convert_encoding($row[1], 'UTF-8', 'SJIS');
                    $day = mb_convert_encoding($row[2], 'UTF-8', 'SJIS');
                    $work_start = mb_convert_encoding($row[3], 'UTF-8', 'SJIS');
                    $work_start = ($work_start === '')  ? NULL : $work_start;
                    $work_end = mb_convert_encoding($row[4], 'UTF-8', 'SJIS');
                    $work_end = ($work_end === '')  ? NULL : $work_end;
                    $break_time = mb_convert_encoding($row[5], 'UTF-8', 'SJIS');
                    $break_time = ($break_time === '')  ? NULL : $break_time;
                    $user_id = mb_convert_encoding($row[6], 'UTF-8', 'SJIS');
                    if($user_id != $auth_id){
                        return back()->with('status','ユーザIDが一致しません');
                    }
                    //1件ずつインポート
                    \DB::beginTransaction();
                    try{
                        $csv_data = new CsvAttendance;
                        $csv_data->year = $year;
                        $csv_data->month = $month;
                        $csv_data->day = $day;
                        $csv_data->work_start = $work_start;
                        $csv_data->work_end = $work_end;
                        $csv_data->break_time = $break_time;
                        $csv_data->user_id = $user_id;
                        $csv_data->save();
                        \DB::commit();
                    }catch(Exception $exception){
                        \DB::rollback();
                        return back()->with('status','登録失敗しました。再度実施してください。');
                    }
                }
                $row_count++;
                
            }
            return redirect(route('mypage.index',[ 'id' => $user_id]))->with('status','勤務表の登録が完了しました。');

        }else{
            return back()->with('status','ファイルが選択されていません。');
        }
                 
    }
    public function acquisition()
    {
        $dt = new Carbon();
        $year = $dt->year;

        $e = 1;
        $year_data = " <select name='year'> ";
        for($i=4; $i >= $e; $i--){
            $before = ($year) - $i ;
            $year_data .="<option value='$before'> $before";
            $year_data .= "</option>";
        }
        $year_data .= "<option value='$year'> $year  ";
        $year_data .= "</option>";
        $year_data .= "</option>";
        $year_data .= "</select>";


        return view('export',['year_data' => $year_data]);
    }

    public function export(Request $request)
    {
        $title = $request->year;
        $sub_title = $request->month;

        $booking_data = DB::table('csv_attendances')->where([
            ['user_id', '=', $request->user_id],
            ['year', '=', $request->year],
            ['month', '=', $request->month]
            ])->get();

        if($booking_data->isEmpty()){
            return back()->with('status','勤務データはありません。');
        }

        $columns = [
            'year',
            'month',
            'day',
            'work_start',
            'work_end',
            'user_id',
            'punch_in',
            'punch_out',
        ];
        $response = new StreamedResponse (function() use ($request, $columns, $booking_data){
            $stream = fopen('php://output', 'w');

            //　文字化け回避
            stream_filter_prepend($stream,'convert.iconv.utf-8/cp932//TRANSLIT');

            // headerから挿入
            fputcsv($stream, $columns);

            // CSVデータ
            foreach($booking_data as $value) {
                $csv = [
                    $value->year,
                    $value->month,
                    $value->day,
                    $value->work_start,
                    $value->work_end,
                    $value->user_id,
                    $value->punch_in,
                    $value->punch_out,
                ];
                fputcsv($stream, $csv);
            }
            fclose($stream);
        });
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$title.'年'.$sub_title.'月.csv"');
 
        return $response;
    }
    public function adminPage()
    {
        $users = DB::table('users')->get();

        foreach($users as $key => $value){

            if($value->role > 0  && $value->role <= 5){
                $roles[] = "管理者";
            }elseif($value->role > 0  && $value->role <= 10){
                $roles[] = "一般ユーザー";
            }
        }
        $attendance = DB::table('csv_attendances')->where([
            ['status', '=', '1']
        ])->get();

        return view('admin',['users' => $users,'roles' => $roles,'attendance' => $attendance]);
    }
    public function template(Request $request, $id)
    {
        $headers = [ //ヘッダー情報
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=csvexport.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        $callback = function() use ($id)
        {
            // global $id;
            $dt = new Carbon();
            $year = $dt->year;
    
            $start_month = Carbon::now()->startOfMonth()->toDateString();
            $end_month = Carbon::now()->endOfMonth()->toDateString();
    
            // CSVデータ
            for($start_month; $start_month <= $end_month; $start_month++){
                $cnt = 0;
                $holidays = \Yasumi\Yasumi::create('Japan', $year, 'ja_JP');
                foreach($holidays as $holiday){
                    $cnt .= ($holiday == $start_month) ? 1 : '';     //祝日か判定
                }
                $day = new Carbon($start_month);
                if($day->isWeekend()) { //土日か判定
                    $cnt++;
                }
                $work_start = ($cnt == 0) ? '09:00:00' : '';    //平日なら代入
                $work_end = ($cnt == 0) ? '18:00:00' : '';      //平日なら代入
                $break_time = '01:00:00';
                $days = explode("-",$start_month);
    
                $csv[] = [
                    $days[0],
                    $days[1],
                    $days[2],
                    $work_start,
                    $work_end,
                    $break_time,
                    $id,
                    NUll,
                    NUll,
                ];
            }
            $stream = fopen('php://output', 'w');//ファイル作成

            $columns = [    //1行目の情報
                'year',
                'month',
                'day',
                'work_start',
                'work_end',
                'break_time',
                'user_id',
                'punch_in',
                'punch_out',
            ];
            mb_convert_variables('SJIS-win', 'UTF-8', $columns); //文字化け対策

            fputcsv($stream, $columns); //1行目の情報を追記

            // CSVデータ
            foreach($csv as $value){
                $data = [
                    $value[0],
                    $value[1],
                    $value[2],
                    $value[3],
                    $value[4],
                    $value[5],
                    $value[6],
                    $value[7],
                ];
                mb_convert_variables('SJIS-win', 'UTF-8', $data); //文字化け対策

                fputcsv($stream, $data); //ファイルに追記する
            }
            fclose($stream); //ファイル閉じる

        };
// dd($callback);
        return response()->stream($callback, 200, $headers); //ここで実行
    }
    public function approval(Request $request)
    {

        \DB::beginTransaction();
        try{
            $timestamp = DB::table('csv_attendances')->where([
                ['user_id', '=', $request->bfs],
                ['punch_in', '=', $request->pasresf],
                ['status', '=', 1],
                ])
            ->update(['status' => 0 ]);
            \DB::commit();
 
            $users = DB::table('users')->get();
    
            foreach($users as $key => $value){
    
                if($value->role > 0  && $value->role <= 5){
                    $roles[] = "管理者";
                }elseif($value->role > 0  && $value->role <= 10){
                    $roles[] = "一般ユーザー";
                }
            }
            $attendance = DB::table('csv_attendances')->where([
                ['status', '=', '1']
            ])->get();
            return redirect(route('admin',['users' => $users,'roles' => $roles,'attendance' => $attendance]))->with('status','承認が完了しました。');
        } catch (\Exception $e) {
			// エラー発生時は、DBへの保存処理が無かったことにする（ロールバック）
			\DB::rollBack();
            return back()->with('error','承認に失敗しました。');
        }

    }
}
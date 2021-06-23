<?php

 namespace App\Http\Controllers;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
 use Symfony\Component\HttpFoundation\StreamedResponse;
 use App\Http\Requests;
 use App\CsvAttendance;
 use Carbon\Carbon;

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
        return view('admin',['users' => $users,'roles' => $roles]);
    }




 }
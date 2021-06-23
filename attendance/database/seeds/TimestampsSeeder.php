<?php

use Illuminate\Database\Seeder;

class TimestampsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // データのクリア
        DB::table('csv_attendances')->truncate();

        //timestampsテーブルにテストデータを登録
        $params = [
            'user_id' => '1',
            'year' => '2021',
            'month' => '4',
            'day' => '1',
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'break_time' => '01:00:00',
            'user_id' => '1',
            'created_at' => '2021-03-31 09:00:00',
        ];
        DB::table('csv_attendances')->insert($params);
        $params = [
            'user_id' => '2',
            'year' => '2021',
            'month' => '4',
            'day' => '1',
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'break_time' => '01:00:00',
            'user_id' => '2',
            'created_at' => '2021-03-31 09:00:00',
        ];
        DB::table('csv_attendances')->insert($params);
        $params = [
            'user_id' => '3',
            'year' => '2021',
            'month' => '4',
            'day' => '1',
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'break_time' => '01:00:00',
            'user_id' => '3',
            'created_at' => '2021-03-31 09:00:00',
        ];
        DB::table('csv_attendances')->insert($params);
        $params = [
            'user_id' => '4',
            'year' => '2021',
            'month' => '4',
            'day' => '1',
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'break_time' => '01:00:00',
            'user_id' => '4',
            'created_at' => '2021-03-31 09:00:00',
        ];
        DB::table('csv_attendances')->insert($params);
        $params = [
            'user_id' => '5',
            'year' => '2021',
            'month' => '4',
            'day' => '1',
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'break_time' => '01:00:00',
            'user_id' => '5',
            'created_at' => '2021-03-31 09:00:00',
        ];
        DB::table('csv_attendances')->insert($params);

    }
    
}

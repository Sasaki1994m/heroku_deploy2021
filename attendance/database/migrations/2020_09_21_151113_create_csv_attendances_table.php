<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsvAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('year');
            $table->integer('month');
            $table->integer('day');
            $table->time('work_start')->nullable()->format('HH:MM:SS');
            $table->time('work_end')->nullable()->format('HH:MM:SS');
            $table->time('break_time')->nullable()->format('HH:MM:SS');
            $table->integer('user_id');
            $table->datetime('punch_in')->nullable();
            $table->datetime('punch_out')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('csv_attendances');
    }
}

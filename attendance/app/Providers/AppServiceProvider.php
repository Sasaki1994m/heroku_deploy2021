<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //ログを出力させる
         // SQL LOG
         DB::listen(function($query) {
            $sql = $query->sql;
            for ($i = 0; $i < count($query->bindings); $i++) {
              $sql = preg_replace("/\?/", $query->bindings[$i], $sql, 1);
            }
  
            Log::debug("SQL", ["time" => sprintf("%.2f ms", $query->time), "sql" => $sql]);
          });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // 本番環境(Heroku)でhttpsを強制する
        if (\App::environment('production')) {
            \URL::forceScheme('https');
        }
    }
}

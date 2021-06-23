<?php
//認証処理のcontroller
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth; /*追加*/
use Illuminate\Http\Request; // 追加

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers{
        logout as performLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    // protected $redirectTo = RouteServiceProvider::HOME;  
       protected $redirectTo = '/list';    /*　↑↑ログイン完了後のリダイレクト先を変更*/

    // protected function redirectTo() {
    //     if(! Auth::user()) {
    //         return '/';
    //     }
    //     return Route::get('/home', ['user' => Auth::id()]);
    // }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login_id()
    {
        return 'login_id';
    }

    // ログアウト時の挙動を記述
    public function logout(Request $request)
    {
        $this->performLogout($request);
        // フラッシュメッセージを表示
        return redirect('/home')->with('flash_message', __('ログアウトしました'));
    }

}

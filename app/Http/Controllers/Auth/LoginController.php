<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

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

    use AuthenticatesUsers {
        logout as traitLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    // Login
    public function showLoginForm(Request $request, $secret = '')
    {
        if (config('app.env') === 'production' && $secret != config('backend.login_secret')) {
            return '';
        }

        $data = [
            'pageConfigs' => ['bodyCustomClass' => 'bg-full-screen-image blank-page', 'navbarType' => 'hidden'],
        ];

        return view('/auth/login')->with($data);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        if (empty($request->post('username'))) {
            return Response::jsonError('请输入帐号！', 401);
        }

        if (empty($request->post('password'))) {
            return Response::jsonError('请输入密码！', 401);
        }

        if ($this->attemptLogin($request)) {
            $admin = Auth::user();

            if ($admin->status != 1) {
                auth()->logout();
                return Response::jsonError('帐号被封禁！', 401);
            }

            activity()->useLog('后台')->causedBy(Auth::user())->log('登录后台!');

            // 更新管理員登入資訊
            $data = [
                'login_at' => Carbon::now(),
                'login_ip' => $request->ip(),
            ];
            $admin->update($data);

            return Response::jsonSuccess('登录成功!');
        }

        return Response::jsonError('帐号或密码错误!', 401);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        activity()->useLog('后台')->causedBy(Auth::user())->log('登出后台!');

        return $this->traitLogout($request);
    }
}

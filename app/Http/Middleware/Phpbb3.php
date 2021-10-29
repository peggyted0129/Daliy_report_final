<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class Phpbb3
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    
        // 本機測試 
        // Session::put('pdepno', 'MSC');
        // Session::put('userno', 'S480');
        // Session::put('username_utf8', '龔麗');
        // Session::put('utype', 'U');

        // *** MSA 主管測試 : 只有主管才能看到報表 ***
        Session::put('userno', 'S102');
        Session::put('pdepno', 'MSA');
        Session::put('username_utf8', '謝玉玲');
        Session::put('utype', 'G');
        
        // Session::put('userno', 'S002');
        // Session::put('pdepno', '001');    

        return $next($request);

        // 本機測試結束
    

    /* 
        $config_value = DB::table('phpbb_config')
                            ->select('config_value')
                            ->where('config_name', 'cookie_name')
                            ->get();
        foreach($config_value as $value) $cookie_name = $value->config_value;

        if (isset($_COOKIE[$cookie_name.'_sid']) && $_COOKIE[$cookie_name.'_u']!='1') {
            $session_user_id = DB::table('phpbb_sessions')
                                    ->select('session_user_id')
                                    ->where('session_id', $_COOKIE[$cookie_name.'_sid'])
                                    ->get();
            foreach($session_user_id as $value) $suid = $value->session_user_id;

            $user_email = DB::table('phpbb_users')
                                ->select('user_email')
                                ->where('user_id', $suid)
                                ->get();
            foreach($user_email as $value) $email = $value->user_email;


            $user = DB::table('secuser')
                        ->select('userno', 'username_utf8', 'pdepno', 'utype')
                        ->where('email', $email)
                        ->get();
            foreach($user as $value) {
                session([
                    'userno' => $value->userno,
                    'username_utf8' => $value->username_utf8,
                    'pdepno' => $value->pdepno,
                    'utype' => $value->utype
                ]);
            }
            

        }else {
            //return Redirect::to('http://test.relmek.com.tw');
            return Redirect::to('https://www.relmek.com.tw');
        }
        return $next($request);

    */

    }
}

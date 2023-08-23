<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CekLogin
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

        $tokenUser = $request->session()->get('LogSessionAppsAuthenticate');
        if(!$tokenUser){
            Session::flush();
            if($request->ajax()){
                $result['results'] = array('code'=>500, 'description'=>'Session Has Expired, Please Login');
                return response()->json($result);
            }else{
                return redirect('auth/in')->with('error1','Session Has Expired, Please Login');
            }
        }
        return $next($request);
    }
}

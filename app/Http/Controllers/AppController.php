<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Yajra\Datatables\Datatables; 
use URL;
use Log;
use Validator;

class AppController extends Controller
{
    function index(Request $request){  
        if($request->session()->get('id')){
            return redirect('apps/todo');
        }
        return redirect('auth/in');
    }

    function auth(Request $request){        
        if($request->session()->get('id')){
            return redirect('apps/todo');
        }
        return view('auth');
    }

    function login(Request $request){
        $validator = Validator::make($request->all(), 
            [
                'nm' => 'required|max:50',
                'pw' => 'required|max:16',
            ],[
                'nm.required'   => 'Email Please Fill',
                'nm.max'        => 'Email Over 50 Characters',
                'pw.required'   => 'Password Please Fill',
                'pw.max'        => 'Password Over 16 Characters',
            ]
        );

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->with('errorform1',"ERROR")->withInput();
        }

        $email    = $request->nm;
        $password = $request->pw; 

        $data = User::where('email',$email)->first();
        if($data){
            if(Hash::check($password, $data->password)){
                $log = array('IPAddress'=>$request->ip(),'InfoUser'=>$request->header('User-Agent'));        
                $update['login_information'] = json_encode($log);                 
                $data->update($update);
                $data->refresh();
                Session::put('id',$data->id); 
                Session::put('name',$data->name); 
                Session::put('LogSessionAppsAuthenticate', 'g5#(!RV15&!@+/'); 
                Log::channel('activity')->info("".$data->name." Logged in");
                return redirect('apps/todo');
            }else{ 
                return redirect('auth/in')->with('error1','Account not found');
            }
        }else{ 
            return redirect('auth/in')->with('error1','Account not found');
        }
    }

    function register(Request $request){
        $validator = Validator::make($request->all(), 
            [
                'name'  => 'required|max:50',
                'email' => 'required|max:50',
                'pw'    => 'required|max:16',
            ],[
                'name.required'   => 'Name Please Fill',
                'name.max'        => 'Name Over 50 Characters',
                'email.required'  => 'Email Please Fill',
                'email.max'       => 'Email Over 50 Characters',
                'pw.required'     => 'Password Please Fill',
                'pw.max'          => 'Password Over 16 Characters',
            ]
        );

        if ($validator->fails()) {
            $result['results'] = array('code'=>400, 'description'=>'Make sure the filling in the form is correct');
        }
        $log = array('IPAddress'=>$request->ip(),'InfoUser'=>$request->header('User-Agent'));        
        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'login_information' => json_encode($log)
        ]);
        Log::channel('activity')->info("".$request->name." Have registered");
        $result['results'] = array('code'=>200, 'description'=>'Registration Successful, Please Login');
        return response()->json($result);

    }

    function logout(Request $request){
        $session = $request->session()->get('id');
        if($session){
            Log::channel('activity')->info($request->session()->get('name')." Exit Application (Logout)");
            Session::flush();
            return redirect('auth/in')->with('success','You have successfully logged out');
        }else{
            Log::channel('activity')->info($request->session()->get('name')." Login Session Has Expired");
            Session::flush();
            return redirect('auth/in')->with('error','Session Has Expired, Please Login');
        }
    }

    function todo(Request $request){
        return view('todo');
    }

    function getdata(Request $request){
        $res = "";
        $id = $request->session()->get('id');
        $data = Todo::where('id_user',$id)->get();
        if(count($data) > 0){
            foreach($data as $d){
                $res .= "<div class='listtask'><span onclick=\"edit(this)\" data-id='".Crypt::encrypt($d->id)."' data-text='".$d->todo."'>".$d->todo."</span> <label class='btn btn-danger float-right mg-b-0' onclick=\"removetask(this)\" data-id='".Crypt::encrypt($d->id)."' data-id=><i class='fa fa-trash'></i></label></div>";
            }
            $res .= "<div class='mg-t-30'>*Click Data For Edit</div>";
        }else{
            $res .= "<div class='text-center' style='padding: 5px;border: 1px solid silver;'>No Todo From List</div>";
        }

        $result['results'] = array('code'=>200, 'data'=>$res);
        return response()->json($result);
    }

    function save(Request $request){
        if($request->key == "" || $request->key == 0){
            Todo::create([
                'id_user'   => $request->session()->get('id'),
                'todo'      => $request->todo
            ]);
            Log::channel('activity')->info("".$request->session()->get('name')." Add Todo ".$request->todo);
            $result['results'] = array('code'=>200, 'description'=>'Todo Has Been Save');
        }else{
            $id = Crypt::decrypt($request->key);
            $data = Todo::where('id',$id)->first();
            $update['todo'] = $request->todo;
            $data->update($update);
            $data->refresh();
            Log::channel('activity')->info("".$request->session()->get('name')." Update Todo ".$request->todo);
            $result['results'] = array('code'=>200, 'description'=>'Todo Has Been Update');
        }
        return response()->json($result);
    }

    function delete(Request $request, $id){
        if($id == "" || $id == 0){
            $result['results'] = array('code'=>400, 'description'=>'No Data Selected');
        }else{
            $id = Crypt::decrypt($id);
            Todo::where('id',$id)->delete();
            Log::channel('activity')->info("".$request->session()->get('name')." Delete Todo");
            $result['results'] = array('code'=>200, 'description'=>'Data Deleted Successfully');
        }
        return response()->json($result);
    }
} 

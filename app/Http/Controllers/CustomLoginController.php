<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomLoginController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(){
        
        if(!empty(\Session::get('user'))){
            return redirect('dashboard');
        }
        return view('login');    
    }

    public function postLogin(Request $request){
        $users = \DB::table('IAPSYS.mst_user_apps')
                    ->select('mst_user_apps.vc_username', 'mst_user_apps.vc_password', 'hd_employee.vc_emp_name', 'MST_DEPARTMENT.vc_dept_name')
                    ->join('hrd.hd_employee', 'hd_employee.vc_emp_code', 'mst_user_apps.vc_emp_code')
                    ->join('hrd.ALL_EMPLOYEE_STAFF_V', 'ALL_EMPLOYEE_STAFF_V.vc_emp_code', 'mst_user_apps.vc_emp_code')
                    ->join('hrd.MST_DEPARTMENT', 'MST_DEPARTMENT.VC_DEPT_CODE', 'ALL_EMPLOYEE_STAFF_V.VC_DEPT_CODE')
                    ->where('ALL_EMPLOYEE_STAFF_V.VC_DEPT_CODE', '=', 'A003')
                    ->orWhere('mst_user_apps.vc_username', '=', 'WLL')
                    ->get();
        foreach ($users as $user) {
            if($user->vc_username == strtoupper($request->get('initial')) && $user->vc_password == strtoupper($request->get('password'))){
                $user->auth = true;
                \Session::put('user', $user);
                return redirect('dashboard');
            }
        }
        return redirect('login');
    }

    public function logout(Request $request){
        \Session::forget('user');
        return redirect('login');    
    }
}

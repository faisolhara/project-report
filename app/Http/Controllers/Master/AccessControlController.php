<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Model\Master\AccessControl;
use App\Service\AuthorizationService;
use Illuminate\Http\Request;

class AccessControlController extends Controller
{
    const URL       = 'master/access-control';
    const RESOURCE  = 'Master\Access Control';
    protected $now;

    public function __construct()
    {
        $this->now = new \DateTime();
    }

    public function index(Request $request){

        if(!AuthorizationService::check(self::RESOURCE, 'view')){
            abort(403);
        }

        if ($request->isMethod('post')) {
            $request->session()->put(self::CLASS, $request->all());
            return redirect(self::URL.'?page=1');
        }

        $filters = $request->session()->get(self::CLASS);
    	$query = \DB::table('IAPSYS.mst_user_apps')
                    ->select(
                        'mst_user_apps.vc_username',
                        'mst_user_apps.vc_password', 
                        'hd_employee.vc_emp_name', 
                        'MST_DEPARTMENT.vc_dept_name'
                        )
                    ->join('hrd.hd_employee', 'hd_employee.vc_emp_code', 'mst_user_apps.vc_emp_code')
                    ->join('hrd.ALL_EMPLOYEE_STAFF_V', 'ALL_EMPLOYEE_STAFF_V.vc_emp_code', 'mst_user_apps.vc_emp_code')
                    ->join('hrd.MST_DEPARTMENT', 'MST_DEPARTMENT.VC_DEPT_CODE', 'ALL_EMPLOYEE_STAFF_V.VC_DEPT_CODE')
                    ->where(function($query){
                        $query->where('ALL_EMPLOYEE_STAFF_V.VC_DEPT_CODE', '=', 'A003')
                                ->orWhere('mst_user_apps.vc_username', '=', 'WLL');
                    })
                    ->orderBy('vc_emp_name', 'asc')
                    ->groupBy(
                        'mst_user_apps.vc_username',
                        'mst_user_apps.vc_password', 
                        'hd_employee.vc_emp_name', 
                        'MST_DEPARTMENT.vc_dept_name'
                        );

        if (!empty($filters['username'])) {
            $query->whereRaw('LOWER(mst_user_apps.vc_username) like \'%'.strtolower($filters['username']).'%\'');
        }

        if (!empty($filters['name'])) {
            $query->whereRaw('LOWER(hd_employee.vc_emp_name) like \'%'.strtolower($filters['name']).'%\'');
        }

        return view('master.access-control.index', [
            'models'      => $query->paginate(10),
            'filters'     => $filters,
            'url'         => self::URL,
            'resource'    => self::RESOURCE,
            'access'      => [
                        'update' => AuthorizationService::check('Master\Access Control', 'update'),
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        if(!AuthorizationService::check(self::RESOURCE, 'update')){
            abort(403);
        }


        $user  = \DB::table('iapsys.mst_user_apps')
                    ->select('mst_user_apps.vc_username', 'hd_employee.vc_emp_name')
                    ->join('hrd.hd_employee', 'hd_employee.vc_emp_code', 'mst_user_apps.vc_emp_code')
                    ->where('vc_username', '=', $id)->first();

        if(empty($user)){
            abort(404);
        }
        $model = AccessControl::where('username', '=', $id)->get();

        return view('master.access-control.update', [
            'url'       => self::URL,
            'user'      => $user,
            'model'     => $model,
            'resources' => config('app.resources'),
        ]);
    }

    public function save(Request $request){
        $now = new \DateTime();

        $model = AccessControl::where('username', '=', $request->get('username'))->get();
        foreach ($model as $model) {
            $model->delete();
        }
        foreach ($request->get('privileges', []) as $resource => $privileges) {
            foreach ($privileges as $privilege => $access) {
                $accessControl = new AccessControl();
                $accessControl->dpr_access_control_id = \DB::getSequence()->nextValue('dpr_access_control_seq');
                $accessControl->username        = $request->get('username');
                $accessControl->resources       = $resource;
                $accessControl->access_control  = $privilege;
                $accessControl->created_by      = \Session::get('user')->vc_username;
                $accessControl->created_date    = $now->format('Y-m-d H:i:s');
                $accessControl->save();
            }
        }

        $request->session()->flash(
            'successMessage',
            'Access control for user '.$request->get('username').' successfully updated!'
        );

        return redirect(self::URL);
    }
}

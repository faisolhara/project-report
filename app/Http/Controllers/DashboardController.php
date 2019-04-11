<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Transaction\ProjectController;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    const URL       = '/';

    public function __construct()
    {
        if(empty(\Session::get('user'))){
            return redirect('login');            
        }
    }

    public function index(Request $request){
    	if ($request->isMethod('post')) {
            $request->session()->put(self::CLASS, $request->all());
            return redirect(self::URL.'?page=1');
        }

        $filters = $request->session()->get(self::CLASS);
    	$query = \DB::table('iapsys.v_project')
    				->select('v_project.*')
	    			->whereRaw('v_project.inventory_item_id in (select project_id from iapsys.project_progress where validated_date is not null)')
	                ->orderBy('creation_date', 'DESC');

        if (!empty($filters['projectCode'])) {
            $query->where(function($query) use ($filters) {
                $query->where('segment1', 'like', '%'.$filters['projectCode'].'%')
                        ->orWhere('segment2', 'like', '%'.$filters['projectCode'].'%')
                        ->orWhere('segment3', 'like', '%'.$filters['projectCode'].'%');
                });
        }

        if (!empty($filters['description'])) {
            $query->whereRaw('LOWER(v_project.description) like \'%'.strtolower($filters['description']).'%\'');
        }

        if (!empty($filters['type'])) {
            $query->whereRaw('LOWER(v_project.description) like \'%'.strtolower($filters['type']).'%\'');
        }
        return view('dashboard', [
            'models'      => $query->paginate(10),
            'filters'     => $filters,
            'typeOption'  => $this->getTypeOpttion(),
            'url'         => self::URL,
        ]);
        return view('dashboard');    
    }

    public function getTypeOpttion(){
        return [
            'Operational'       => ProjectController::OPERATIONAL,
            'Project Internal'  => ProjectController::PROJECT_INTERNAL,
            'Project Eskternal' => ProjectController::PROJECT_EKSTERNAL,
            'RND'               => ProjectController::RND,
            'Pre Sale'          => ProjectController::PRE_SALE,
        ];
    }
}

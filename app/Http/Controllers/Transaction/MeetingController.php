<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Model\Transaction\ProjectProgress;
use App\Model\Transaction\MilestoneProgress;
use App\Model\Transaction\TaskProgress;
use App\Model\Transaction\Milestone;
use App\Model\Transaction\Task;
use App\Model\Transaction\Meeting;
use App\Model\Transaction\MeetingProject;
use App\Service\AuthorizationService;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    const URL       = 'transaction/meeting';
    const RESOURCE  = 'Transaction\Meeting';

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
        $query = \DB::table('iapsys.meeting')
                ->select(
                    'meeting.*'
                    )
                ->orderBy('meeting.meeting_date', 'DESC')
                ->distinct();

        if (!empty($filters['meetingName'])) {
            $query->whereRaw('LOWER(meeting_name) like \'%'.strtolower($filters['meetingName']).'%\'');
        }

        if (!empty($filters['description'])) {
            $query->whereRaw('LOWER(description) like \'%'.strtolower($filters['description']).'%\'');
        }

        if (!empty($filters['startDate'])) {
            $startDate  = new \DateTime($filters['startDate']);
            $query->where('meeting_date', '>=', $startDate->format('Y-m-d H:i:s'));
        }

        if (!empty($filters['endDate'])) {
            $endDate    = new \DateTime($filters['endDate']);
            $query->where('meeting_date', '<=', $endDate->format('Y-m-d H:i:s'));
        }

        return view('transaction.meeting.index', [
            'models'      => $query->paginate(10),
            'filters'     => $filters,
            'url'         => self::URL,
            'resource'    => self::RESOURCE,
        ]);
    }

    public function add(Request $request)
    {
        if(!AuthorizationService::check(self::RESOURCE, 'add')){
            abort(403);
        }
        $model = new Meeting();

        return view('transaction.meeting.add', [
            'url'            => self::URL,
            'models'         => $model,
            'projectOption'  => $this->getProject(),
        ]);
    }

    public function getProject(){
        $query = \DB::connection('ardmore')
                ->table('inv.mtl_system_items_b')
                ->select(
                    'mtl_system_items_b.segment1',
                    'mtl_system_items_b.segment2',
                    'mtl_system_items_b.segment3',
                    'mtl_system_items_b.INVENTORY_ITEM_ID',
                    'mtl_system_items_b.description'
                )
                ->where('segment1', '=', '2016')
                ->where('organization_id', '=', '124')
                ->where('purchasing_item_flag', '=', 'Y')
                ->where('INVENTORY_ITEM_STATUS_CODE', '=', 'Active')
                ->orderBy('creation_date', 'DESC')
                ->distinct();

        return $query->get();
    }

    public function delete(Request $request){

        if(!AuthorizationService::check(self::RESOURCE, 'delete')){
            abort(403);
        }

        $model = Meeting::find($request->get('meetingId'));

        if(!empty($model->validated_date)){
            abort(404);
        }

        foreach ($model->meetingProject as $meetingProject) {
            $meetingProject->delete();
        }
        $model->delete();

        $request->session()->flash(
            'successMessage',
            'Meeting '.$model->meeting_name.' successfully removed'
        );

        return redirect(self::URL);
    }

    public function save(Request $request){
        // dd($request->all());
        $this->validate($request, [
            'meetingName' => 'required',
            'meetingDate' => 'required',
            'description' => 'required',
        ]);


        $now = new \DateTime();

        $meetingDate = !empty($request->get('meetingDate')) ? new \DateTime($request->get('meetingDate')) : null;

        if (empty($request->get('meetingId'))) {
            $model = new Meeting();
            $model->meeting_id      = \DB::getSequence()->nextValue('meeting_seq');
            $model->created_date    = $now->format('Y-m-d H:i:s');
            $model->created_by      = \Session::get('user')->vc_username;
        } else {
            $model = Meeting::find($request->get('meetingId'));
            $model->last_updated_date    = $now->format('Y-m-d H:i:s');
            $model->last_updated_by      = \Session::get('user')->vc_username;
        }

        $model->meeting_name    = $request->get('meetingName');
        $model->description     = $request->get('description');
        $model->meeting_date    = !empty($meetingDate)? $meetingDate->format('Y-m-d H:i:s') : '';

        $model->save();

        $model->meetingProject()->forceDelete();

        if (!empty($request->get('projectId'))) {
            foreach ($request->get('projectId') as $key => $project) {
                $modelProject = new MeetingProject();
                $modelProject->meeting_project_id = \DB::getSequence()->nextValue('meeting_project_seq');
                $modelProject->meeting_id           = $model->meeting_id;
                $modelProject->project_id           = $project;
                $modelProject->description          = $request->get('projectDescription')[$key];
                $modelProject->problem              = $request->get('projectProblem')[$key];
                $modelProject->solution             = $request->get('projectSolution')[$key];
                $modelProject->created_date         = $now->format('Y-m-d H:i:s');
                $modelProject->created_by           = \Session::get('user')->vc_username;
                $modelProject->save();
            }            

        }

        $request->session()->flash(
            'successMessage',
            'Meeting '.$model->meeting_name.' successfully saved'
        );

        return redirect(self::URL);
    }

    public function update(Request $request, $id)
    {
        if(!AuthorizationService::check(self::RESOURCE, 'update')){
            abort(403);
        }
        if(!is_numeric($id)){
            abort(404);
        }

        $model = Meeting::find($id);

        if(empty($model)){
            abort(404);
        }
        
        return view('transaction.meeting.add', [
            'url'            => self::URL,
            'models'         => $model,
            'projectOption'  => $this->getProject(),
        ]);
    }

    public function checkLastUpdated(Request $request){
        $model = Meeting::find($request->get('meetingId'));
        if (empty($model)) {
            return response()->json(['check' => true]);  
        } 

        $lastUpdatedDate = !empty($model->last_updated_date) ? new \DateTime($model->last_updated_date) : null;
        $lastUpdatedDate = !empty($lastUpdatedDate) ? $lastUpdatedDate->format('d-m-Y H:i:s') : '';
        if($model->last_updated_date == $request->get('lastUpdatedDate')){
            $data = [
                'check'             => true,
            ];
        } else{
            $data = [
                'check'             => false,
                'message'           => 'This meeting is updated by '. $model->last_updated_by .' at '. $lastUpdatedDate. '. Please backup your changes and refresh this page!',
            ];
        }

        return response()->json($data);
    }
}

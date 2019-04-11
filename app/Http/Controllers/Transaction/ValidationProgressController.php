<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Transaction\ProjectController;
use App\Model\Transaction\Project;
use App\Model\Transaction\ProjectProgress;
use App\Model\Transaction\MilestoneProgress;
use App\Model\Transaction\TaskProgress;
use App\Model\Transaction\Milestone;
use App\Model\Transaction\Task;
use App\Service\AuthorizationService;
use Illuminate\Http\Request;

class ValidationProgressController extends Controller
{
    const URL       = 'transaction/validation-progress';
    const RESOURCE  = 'Transaction\Validation Progress';
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
    	$query = \DB::table('iapsys.project_progress')
    			->select(
                    'project_progress.project_progress_id',
                    'project_progress.section',
                    'project_progress.created_by',
                    'project_progress.created_date',
                    'project_progress.last_updated_by',
                    'project_progress.last_updated_date',
                    'v_project.inventory_item_id',
                    'v_project.segment1',
                    'v_project.segment2',
                    'v_project.segment3',
                    'v_project.description as project_name'
                    )
                ->join('v_project', 'v_project.inventory_item_id', '=', 'project_progress.project_id')
                ->whereNull('project_progress.validated_date')
                ->orderBy('project_progress.project_progress_id', 'asc')
                ->groupBy(
                    'project_progress.project_progress_id',
                    'project_progress.section',
                    'project_progress.created_by',
                    'project_progress.created_date',
                    'project_progress.last_updated_by',
                    'project_progress.last_updated_date',
                    'v_project.inventory_item_id',
                    'v_project.segment1',
                    'v_project.segment2',
                    'v_project.segment3',
                    'v_project.description'
                    )
                ->distinct();

        if (!empty($filters['projectCode'])) {
            $query->where(function($query) use ($filters) {
                $query->where('v_project.segment1', 'like', '%'.$filters['projectCode'].'%')
                        ->orWhere('v_project.segment2', 'like', '%'.$filters['projectCode'].'%')
                        ->orWhere('v_project.segment3', 'like', '%'.$filters['projectCode'].'%');
                });
        }

        if (!empty($filters['description'])) {
            $query->whereRaw('LOWER(v_project.description) like \'%'.strtolower($filters['description']).'%\'');
        }

        if (!empty($filters['type'])) {
            $query->whereRaw('LOWER(v_project.description) like \'%'.strtolower($filters['type']).'%\'');
        }

        if (!empty($filters['section'])) {
            $query->where('project_progress.section', '=', $filters['section']);
        }

        if (!empty($filters['startDate'])) {
            $startDate  = new \DateTime($filters['startDate']);
            $query->where('project_progress.created_date', '>=', $startDate->format('Y-m-d 00:00:00'));
        }

        if (!empty($filters['endDate'])) {
            $endDate    = new \DateTime($filters['endDate']);
            $query->where('project_progress.created_date', '<=', $endDate->format('Y-m-d 23:59:59'));
        }

        return view('transaction.validation-progress.index', [
            'models'        => $query->paginate(10),
            'filters'       => $filters,
            'sectionOption' => $this->getSectionOpttion(),
            'typeOption'    => $this->getTypeOpttion(),
            'url'           => self::URL,
        ]);
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

    public function getSectionOpttion(){
        return [
            ProjectController::TECHNICAL,
            ProjectController::MARKETING,
        ];
    }

    public function update(Request $request, $id){
        if(!AuthorizationService::check(self::RESOURCE, 'view')){
            abort(403);
        }
        if(!is_numeric($id)){
            abort(404);
        }

        $model = ProjectProgress::find($id);

        if(empty($model) || !empty($model->validated_date)){
            abort(404);
        }

        return view('transaction.validation-progress.update', [
            'url'     => self::URL,
            'model'   => $model,
        ]);

    }

    public function save(Request $request){
        if(!AuthorizationService::check(self::RESOURCE, 'view')){
            abort(403);
        }
        $now = new \DateTime();

        $startDate      = !empty($request->get('startDateProject')) ? new \DateTime($request->get('startDateProject')) : null; 
        $endDate        = !empty($request->get('endDateProject')) ? new \DateTime($request->get('endDateProject')) : null;


        $modelProject = ProjectProgress::find($request->get('progressId'));
        $modelProject->start_date           = !empty($startDate) ? $startDate->format('Y-m-d H:i:s') : '';
        $modelProject->end_date             = !empty($endDate) ? $endDate->format('Y-m-d H:i:s') : '';
        $modelProject->progress_percentage  = $request->get('progressPercentageProject');
        $modelProject->description          = $request->get('descriptionProject');
        $modelProject->problem              = $request->get('problemProject');
        $modelProject->solution             = $request->get('solutionProject');
        $modelProject->validated_by         = \Session::get('user')->vc_username;
        $modelProject->validated_date       = $now->format('Y-m-d H:i:s');

        $modelProject->save();
        foreach ($modelProject->milestoneProgress as $milestoneProgress) {

            $startDateMilestone      = !empty($request->get('startDateMilestone-'.$milestoneProgress->milestone_progress_id)) ? new \DateTime($request->get('startDateMilestone-'.$milestoneProgress->milestone_progress_id)) : null; 
            $endDateMilestone        = !empty($request->get('endDateMilestone-'.$milestoneProgress->milestone_progress_id)) ? new \DateTime($request->get('endDateMilestone-'.$milestoneProgress->milestone_progress_id)) : null;
            $milestoneProgress->start_date             = !empty($startDateMilestone) ? $startDateMilestone->format('Y-m-d H:i:s') : '';
            $milestoneProgress->end_date               = !empty($endDateMilestone) ? $endDateMilestone->format('Y-m-d H:i:s') : '';
            $milestoneProgress->progress_percentage    = $request->get('progressPercentageMilestone-'.$milestoneProgress->milestone_progress_id);
            $milestoneProgress->description            = $request->get('descriptionMilestone-'.$milestoneProgress->milestone_progress_id);
            $milestoneProgress->problem                = $request->get('problemMilestone-'.$milestoneProgress->milestone_progress_id);
            $milestoneProgress->solution               = $request->get('solutionMilestone-'.$milestoneProgress->milestone_progress_id);
            $milestoneProgress->validated_by           = \Session::get('user')->vc_username;
            $milestoneProgress->validated_date         = $now->format('Y-m-d H:i:s');
            $milestoneProgress->save();

            foreach ($milestoneProgress->taskProgress as $taskProgress) {

                $startDateTask      = !empty($request->get('startDateTask-'.$taskProgress->task_progress_id)) ? new \DateTime($request->get('startDateTask-'.$taskProgress->task_progress_id)) : null; 
                $endDateTask        = !empty($request->get('endDateTask-'.$taskProgress->task_progress_id)) ? new \DateTime($request->get('endDateTask-'.$taskProgress->task_progress_id)) : null;

                $taskProgress->start_date           = !empty($startDateTask) ? $startDateTask->format('Y-m-d H:i:s') : '';
                $taskProgress->end_date             = !empty($endDateTask) ? $endDateTask->format('Y-m-d H:i:s') : '';
                $taskProgress->progress_percentage  = $request->get('progressPercentageTask-'.$taskProgress->task_progress_id);
                $taskProgress->description          = $request->get('descriptionTask-'.$taskProgress->task_progress_id);
                $taskProgress->problem              = $request->get('problemTask-'.$taskProgress->task_progress_id);
                $taskProgress->solution             = $request->get('solutionTask-'.$taskProgress->task_progress_id);
                $taskProgress->validated_by         = \Session::get('user')->vc_username;
                $taskProgress->validated_date       = $now->format('Y-m-d H:i:s');

                $taskProgress->save();
            }
        }

        $query   = $this->getProject($modelProject->project_id);
        $project = $query->first();

        $request->session()->flash(
            'successMessage',
            'Progress project '.$project->description.' successfully validated'
        );

        return redirect(self::URL);

    }

    private function getProject($id){
        $query = \DB::table('iapsys.v_project')
                    ->where('inventory_item_id', '=', $id);
        return $query;
    }

    public function checkLastUpdated(Request $request){
        $model = ProjectProgress::find($request->get('progressId'));
        $lastUpdatedDate = !empty($model->last_updated_date) ? new \DateTime($model->last_updated_date) : null;
        $lastUpdatedDate = !empty($lastUpdatedDate) ? $lastUpdatedDate->format('d-m-Y H:i:s') : '';
        $validatedDate = !empty($model->validated_date) ? new \DateTime($model->validated_date) : null;
        if(!empty($validatedDate)){
            $data = [
                'check'             => false,
                'message'           => 'This progress has validated by '. $model->validated_by.' at '. $validatedDate->format('d-m-Y H:i:s'),
            ];
        }    
            
        else if($model->last_updated_date == $request->get('lastUpdatedDate')){
            $data = [
                'check'             => true,
            ];
        } else{
            $data = [
                'check'             => false,
                'message'           => 'This progress is updated by '. $model->last_updated_by .' at '. $lastUpdatedDate. '. Please backup your progress and refresh this page!',
            ];
        }

        return response()->json($data);
    }
}

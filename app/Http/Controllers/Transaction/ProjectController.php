<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Model\Transaction\ProjectProgress;
use App\Model\Transaction\MilestoneProgress;
use App\Model\Transaction\TaskProgress;
use App\Model\Transaction\Milestone;
use App\Model\Transaction\Task;
use App\Service\AuthorizationService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    const URL       = 'transaction/project';
    const RESOURCE  = 'Transaction\Project';

    const MARKETING = 'Marketing';
    const TECHNICAL = 'Technical';

    const OPERATIONAL       = '.01.';
    const PROJECT_INTERNAL  = '.02.';
    const PROJECT_EKSTERNAL = '.03.';
    const RND               = '.04.';
    const PRE_SALE          = '.05.';
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
    	$query = \DB::table('iapsys.v_project')
                ->select(
                    'inventory_item_id',
                    'segment1',
                    'segment2',
                    'segment3',
                    'description',
                    'creation_date'
                    )
                ->orderBy('creation_date', 'DESC')
                ->groupBy(
                    'inventory_item_id',
                    'segment1',
                    'segment2',
                    'segment3',
                    'description',
                    'creation_date'
                    );

        if (!empty($filters['projectCode'])) {
            $query->where(function($query) use ($filters) {
                $query->where('segment1', 'like', '%'.$filters['projectCode'].'%')
                        ->orWhere('segment2', 'like', '%'.$filters['projectCode'].'%')
                        ->orWhere('segment3', 'like', '%'.$filters['projectCode'].'%');
                });
        }

        if (!empty($filters['description'])) {
            $query->whereRaw('LOWER(description) like \'%'.strtolower($filters['description']).'%\'');
        }

        if (!empty($filters['type'])) {
            $query->whereRaw('LOWER(description) like \'%'.strtolower($filters['type']).'%\'');
        }

        return view('transaction.project.index', [
            'models'      => $query->paginate(10),
            'filters'     => $filters,
            'typeOption'  => $this->getTypeOpttion(),
            'url'         => self::URL,
        ]);
    }

    public function getTypeOpttion(){
        return [
            'Operational'       => self::OPERATIONAL,
            'Project Internal'  => self::PROJECT_INTERNAL,
            'Project Eskternal' => self::PROJECT_EKSTERNAL,
            'RND'               => self::RND,
            'Pre Sale'          => self::PRE_SALE,
        ];
    }

    public function detail(Request $request, $id)
    {
        if(!AuthorizationService::check(self::RESOURCE, 'view')){
            abort(403);
        }
        if(!is_numeric($id)){
            abort(404);
        }
        $query = $this->getProject($id);

        if(empty($query->first())){
            abort(404);
        }

        $milestoneTechnical = Milestone::where('project_id', '=', $id)
                                ->where('section', '=', self::TECHNICAL)
                                ->orderBy('milestone_name', 'asc')->get();
        $milestoneMarketing = Milestone::where('project_id', '=', $id)
                                ->where('section', '=', self::MARKETING)
                                ->orderBy('milestone_name', 'asc')->get();

        $projectProgressTechnical = ProjectProgress::where('project_id', '=', $id)
                                        ->where('section', '=', self::TECHNICAL)
                                        ->orderBy('created_date', 'desc')
                                        ->orderBy('project_progress_id', 'desc')
                                        ->first();
        $projectProgressMarketing = ProjectProgress::where('project_id', '=', $id)
                                        ->where('section', '=', self::MARKETING)
                                        ->orderBy('created_date', 'desc')
                                        ->orderBy('project_progress_id', 'desc')
                                        ->first();

        return view('transaction.project.detail', [
            'url'                       => self::URL,
            'resource'                  => self::RESOURCE,
            'model'                     => $query->first(),
            'milestonesTechnical'       => $milestoneTechnical,
            'milestonesMarketing'       => $milestoneMarketing,
            'projectProgressTechnical'  => $projectProgressTechnical,
            'projectProgressMarketing'  => $projectProgressMarketing,
        ]);
    }

    public function addProgress(Request $request, $id, $section){
        if(!AuthorizationService::check(self::RESOURCE, 'add-progress-technical') && $section == self::TECHNICAL){
            abort(403);
        }else if(!AuthorizationService::check(self::RESOURCE, 'add-progress-marketing') && $section == self::MARKETING){
            abort(403);
        }

        if(!is_numeric($id)){
            abort(404);
        }

        if(!in_array($section, [self::TECHNICAL, self::MARKETING])){
            abort(404);
        }

        $query = $this->getProject($id);

        if(empty($query->first())){
            abort(404);
        }

        $milestone = Milestone::where('project_id', '=', $id)
                        ->where('section', '=', $section)
                        ->orderBy('milestone_name', 'asc')->get();

        $projectProgress = ProjectProgress::where('project_id', '=', $id)
                            ->where('section', '=', $section)
                            ->orderBy('created_date', 'desc')
                            ->orderBy('project_progress_id', 'desc')
                            ->first();

        return view('transaction.project.add-progress', [
            'url'               => self::URL,
            'model'             => $query->first(),
            'section'           => $section,
            'milestones'        => $milestone,
            'projectProgress'   => $projectProgress,
        ]);

    }

    public function saveProgress(Request $request){
        $now = new \DateTime();

        $startDate      = !empty($request->get('startDateProject')) ? new \DateTime($request->get('startDateProject')) : null; 
        $endDate        = !empty($request->get('endDateProject')) ? new \DateTime($request->get('endDateProject')) : null;


        $modelProject = new ProjectProgress();
        $modelProject->project_progress_id  = \DB::getSequence()->nextValue('project_progress_seq');
        $modelProject->section              = $request->get('section');
        $modelProject->project_id           = $request->get('projectId');
        $modelProject->start_date           = !empty($startDate) ? $startDate->format('Y-m-d H:i:s') : '';
        $modelProject->end_date             = !empty($endDate) ? $endDate->format('Y-m-d H:i:s') : '';
        $modelProject->progress_percentage  = $request->get('progressPercentageProject');
        $modelProject->description          = $request->get('descriptionProject');
        $modelProject->problem              = $request->get('problemProject');
        $modelProject->solution             = $request->get('solutionProject');
        $modelProject->created_by           = \Session::get('user')->vc_username;
        $modelProject->created_date         = $now->format('Y-m-d H:i:s');

        $modelProject->save();

        $milestones = Milestone::where('project_id', '=', $modelProject->project_id)->where('section', '=', $request->get('section'))->get();
        foreach ($milestones as $milestone) {

            $startDateMilestone      = !empty($request->get('startDateMilestone-'.$milestone->milestone_id)) ? new \DateTime($request->get('startDateMilestone-'.$milestone->milestone_id)) : null; 
            $endDateMilestone        = !empty($request->get('endDateMilestone-'.$milestone->milestone_id)) ? new \DateTime($request->get('endDateMilestone-'.$milestone->milestone_id)) : null;

            $modelMilestone = new MilestoneProgress();
            $modelMilestone->milestone_progress_id  = \DB::getSequence()->nextValue('milestone_progress_seq');
            $modelMilestone->milestone_id           = $milestone->milestone_id;
            $modelMilestone->project_progress_id    = $modelProject->project_progress_id;
            $modelMilestone->start_date             = !empty($startDateMilestone) ? $startDateMilestone->format('Y-m-d H:i:s') : '';
            $modelMilestone->end_date               = !empty($endDateMilestone) ? $endDateMilestone->format('Y-m-d H:i:s') : '';
            $modelMilestone->progress_percentage    = $request->get('progressPercentageMilestone-'.$milestone->milestone_id);
            $modelMilestone->description            = $request->get('descriptionMilestone-'.$milestone->milestone_id);
            $modelMilestone->problem                = $request->get('problemMilestone-'.$milestone->milestone_id);
            $modelMilestone->solution               = $request->get('solutionMilestone-'.$milestone->milestone_id);
            $modelMilestone->created_by             = \Session::get('user')->vc_username;
            $modelMilestone->created_date           = $now->format('Y-m-d H:i:s');

            $modelMilestone->save();

            foreach ($milestone->tasks as $task) {

                $startDateTask      = !empty($request->get('startDateTask-'.$task->task_id)) ? new \DateTime($request->get('startDateTask-'.$task->task_id)) : null; 
                $endDateTask        = !empty($request->get('endDateTask-'.$task->task_id)) ? new \DateTime($request->get('endDateTask-'.$task->task_id)) : null;

                $modelTask = new TaskProgress();
                $modelTask->task_progress_id     = \DB::getSequence()->nextValue('task_progress_seq');
                $modelTask->task_id              = $task->task_id;
                $modelTask->milestone_progress_id= $modelMilestone->milestone_progress_id;
                $modelTask->start_date           = !empty($startDateTask) ? $startDateTask->format('Y-m-d H:i:s') : '';
                $modelTask->end_date             = !empty($endDateTask) ? $endDateTask->format('Y-m-d H:i:s') : '';
                $modelTask->progress_percentage  = $request->get('progressPercentageTask-'.$task->task_id);
                $modelTask->description          = $request->get('descriptionTask-'.$task->task_id);
                $modelTask->problem              = $request->get('problemTask-'.$task->task_id);
                $modelTask->solution             = $request->get('solutionTask-'.$task->task_id);
                $modelTask->created_by           = \Session::get('user')->vc_username;
                $modelTask->created_date         = $now->format('Y-m-d H:i:s');

                $modelTask->save();
            }
        }

        $query   = $this->getProject($modelProject->project_id);
        $project = $query->first();

        $request->session()->flash(
            'successMessage',
            'Progress project '.$project->description.' successfully saved'
        );

        return redirect(self::URL.'/detail/'.$modelProject->project_id);

    }

    public function updateDetail(Request $request, $id, $section){
        if(!AuthorizationService::check(self::RESOURCE, 'add-remove-milestone-technical') && $section == self::TECHNICAL){
            abort(403);
        }else if(!AuthorizationService::check(self::RESOURCE, 'add-remove-milestone-marketing') && $section == self::MARKETING){
            abort(403);
        }
        if(!is_numeric($id)){
            abort(404);
        }

        if(!in_array($section, [self::TECHNICAL, self::MARKETING])){
            abort(404);
        }
        
        $query = $this->getProject($id);

        if(empty($query->first())){
            abort(404);
        }

        $milestone = Milestone::where('project_id', '=', $id)
                        ->where('section', '=', $section)
                        ->orderBy('milestone_name', 'asc')->get();

        $projectProgress = ProjectProgress::where('project_id', '=', $id)
                            ->where('section', '=', $section)
                            ->orderBy('created_date', 'desc')->first();

        return view('transaction.project.update-detail', [
            'url'               => self::URL,
            'model'             => $query->first(),
            'section'           => $section,
            'milestones'        => $milestone,
            'projectProgress'   => $projectProgress,
        ]);
    }

    public function saveMilestone(Request $request){
        $now = new \DateTime();

        if(empty($request->get('milestoneId'))){
            $model = new Milestone();
            $model->milestone_id = \DB::getSequence()->nextValue('milestone_seq');
            $model->section      = $request->get('section');
            $model->project_id   = $request->get('projectId');
            $model->created_by   = \Session::get('user')->vc_username;
            $model->created_date = $now->format('Y-m-d H:i:s');
        }else{
            $model = Milestone::find($request->get('milestoneId'));
            $model->last_updated_by   = \Session::get('user')->vc_username;
            $model->last_updated_date = $now->format('Y-m-d H:i:s');
        }

        $model->milestone_name = $request->get('milestoneName');
        $model->save();

        $request->session()->flash(
            'successMessage',
            'Milestone '.$model->milestone_name.' successfully saved'
        );

        return redirect(self::URL.'/update-detail/'.$model->project_id.'/'.$request->get('section'));
    }

    public function saveTask(Request $request){
        $now = new \DateTime();

        if(empty($request->get('taskId'))){
            $model = new Task();
            $model->task_id      = \DB::getSequence()->nextValue('task_seq');
            $model->milestone_id = $request->get('milestoneId');
            $model->created_by   = \Session::get('user')->vc_username;
            $model->created_date = $now->format('Y-m-d H:i:s');
        }else{
            $model = Task::find($request->get('taskId'));
            $model->last_updated_by   = \Session::get('user')->vc_username;
            $model->last_updated_date = $now->format('Y-m-d H:i:s');
        }

        $model->task_name = $request->get('taskName');
        $model->save();

        $request->session()->flash(
            'successMessage',
            'Task '.$model->task_name.' successfully saved'
        );

        return redirect(self::URL.'/update-detail/'.$model->milestone->project_id.'/'.$request->get('section'));
    }

    public function deleteMilestone(Request $request){
        $model = Milestone::find($request->get('milestoneId'));

        $milestoneProgress = MilestoneProgress::where('milestone_id', '=', $model->milestone_id)->get();

        foreach ($milestoneProgress as $milestoneProgress) {
            foreach ($milestoneProgress->taskProgress as $taskProgress) {
                $taskProgress->delete();
            }
            $milestoneProgress->delete();
        }

        foreach ($model->tasks as $task) {
            $task->delete();
        }
        $model->delete();

        $request->session()->flash(
            'successMessage',
            'Milestone '.$model->milestone_name.' successfully removed'
        );

        return redirect(self::URL.'/update-detail/'.$model->project_id.'/'.$request->get('section'));
    }

    public function deleteTask(Request $request){
        $model          = Task::find($request->get('taskId'));

        $taskProgress = TaskProgress::where('task_id', '=', $model->task_id)->get();

        foreach ($taskProgress as $taskProgress) {
            $taskProgress->delete();
        }

        $model->delete();

        $request->session()->flash(
            'successMessage',
            'Task '.$model->task_name.' successfully removed'
        );

        return redirect(self::URL.'/update-detail/'.$model->milestone->project_id.'/'.$request->get('section'));
    }

    private function getProject($id){
        $query = \DB::table('iapsys.v_project')
                    ->where('inventory_item_id', '=', $id);
        return $query;
    }

    private function getSimpleProject($id){
        $query = \DB::connection('ardmore')
                ->table('inv.mtl_system_items_b')
                ->select(
                    'mtl_system_items_b.segment1',
                    'mtl_system_items_b.segment2',
                    'mtl_system_items_b.segment3',
                    'mtl_system_items_b.INVENTORY_ITEM_ID',
                    'mtl_system_items_b.description'
                    )
                ->where('INVENTORY_ITEM_ID', '=', $id)
                ->where('segment1', '=', '2016')
                ->where('organization_id', '=', '124')
                ->where('purchasing_item_flag', '=', 'Y')
                ->where('INVENTORY_ITEM_STATUS_CODE', '=', 'Active')
                ->distinct()
                ->first();
        return $query;
    }

}

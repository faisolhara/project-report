<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Transaction\ProjectController;
use App\Model\Transaction\ProjectProgress;
use App\Model\Transaction\MilestoneProgress;
use App\Model\Transaction\TaskProgress;
use App\Model\Transaction\Milestone;
use App\Model\Transaction\Task;
use App\Model\Transaction\MeetingProject;
use App\Service\AuthorizationService;
use Illuminate\Http\Request;

class DetailHistoryMilestoneController extends Controller
{
    const URL      = 'report/detail-history-milestone';
    const RESOURCE = 'Report\Detail History Milestone';

    protected $now;

    public function index(Request $request){
        if(!AuthorizationService::check(self::RESOURCE, 'view')){
            abort(403);
        }
        
        if ($request->isMethod('post')) {
            $request->session()->put('filters', $request->all());
            return redirect(self::URL.'?page=1');
        } elseif (empty($request->get('page'))) {
            $request->session()->forget('filters');
        }

        $tasks      = [];
        $columns    = [];
        $data       = [];
        $meeting    = [];
        $filters    = $request->session()->get('filters');

        if(!empty($filters['milestone'])) {

            $milestoneProgress = \DB::table('iapsys.milestone_progress');


            if (!empty($filters['startDate'])) {
                $startDate  = new \DateTime($filters['startDate']);
                $milestoneProgress->where('milestone_progress.created_date', '>=', $startDate->format('Y-m-d H:i:s'));
            }

            if (!empty($filters['endDate'])) {
                $endDate    = new \DateTime($filters['endDate']);
                $milestoneProgress->where('milestone_progress.created_date', '<=', $endDate->format('Y-m-d H:i:s'));
            }

            $milestoneProgress->where('milestone_progress.milestone_id', '=', $filters['milestone'])
                            ->join('iapsys.project_progress', 'project_progress.project_progress_id', '=', 'milestone_progress.project_progress_id')
                            ->whereNotNull('project_progress.validated_date')
                            ->orderBy('milestone_progress.created_date', 'desc');

            $tasks = Task::where('milestone_id', '=', $filters['milestone'])
                                ->orderBy('task_name', 'asc')
                                ->get();

            $columns[] = 'Milestone Progress';
            foreach ($tasks as $value) {
                $columns[] = $value->task_name;
            }

            foreach ($milestoneProgress->get() as $milestone) {
                $date                           = !empty($milestone->created_date) ? new \DateTime($milestone->created_date) : null;
                $startDateMilestoneProgress       = !empty($milestone->start_date) ? new \DateTime($milestone->start_date) : null; 
                $endDateMilestoneProgress         = !empty($milestone->end_date) ? new \DateTime($milestone->end_date) : null; 

                $valueStartDateMilestone      = !empty($startDateMilestoneProgress) ? $startDateMilestoneProgress->format('d-m-Y') : '';
                $valueEndDateMilestone        = !empty($endDateMilestoneProgress) ? $endDateMilestoneProgress->format('d-m-Y') : '';
                $valuePercentMilestone        = !empty($milestone) ? $milestone->progress_percentage : '0';
                $valueDescriptionMilestone    = !empty($milestone) ? $milestone->description : '';
                $valueProblemMilestone        = !empty($milestone) ? $milestone->problem : '';
                $valueSolutionMilestone       = !empty($milestone) ? $milestone->solution : '';
                
                $valueProgressMilestone = '<b>Start Date : </b>'.$valueStartDateMilestone.'<br>'.
                                        '<b>End Date : </b>'.$valueEndDateMilestone.'<br>'.
                                        '<b>Progress Percentage : </b>'.$valuePercentMilestone.' % <br>'.
                                        '<b>Description : </b>'.$valueDescriptionMilestone.'<br>'.
                                        '<b>Problem : </b>'.$valueProblemMilestone.'<br>'.
                                        '<b>Solution : </b>'.$valueSolutionMilestone;
                if(empty($valueDescriptionMilestone) && empty($valueProblemMilestone) && empty($valueSolutionMilestone) && $this->emptyTaskProgress($tasks, $milestone)){
                    // continue;
                }

                $data[$milestone->milestone_progress_id] [] = $date->format('d-m-Y H:i:s');
                $data[$milestone->milestone_progress_id] [] = $valueProgressMilestone;
                foreach ($tasks as $task) {
                    $taskProgress = TaskProgress::where('milestone_progress_id', '=', $milestone->milestone_progress_id)
                                                            ->where('task_id', '=', $task->task_id)->first();

                    $startDateTaskProgress       = !empty($taskProgress->start_date) ? new \DateTime($taskProgress->start_date) : null; 
                    $endDateTaskProgress         = !empty($taskProgress->end_date) ? new \DateTime($taskProgress->end_date) : null; 

                    $valueStartDateTask      = !empty($startDateTaskProgress) ? $startDateTaskProgress->format('d-m-Y') : '';
                    $valueEndDateTask        = !empty($endDateTaskProgress) ? $endDateTaskProgress->format('d-m-Y') : '';
                    $valuePercentTask        = !empty($taskProgress) ? $taskProgress->progress_percentage : '0';
                    $valueDescriptionTask    = !empty($taskProgress) ? $taskProgress->description : '';
                    $valueProblemTask        = !empty($taskProgress) ? $taskProgress->problem : '';
                    $valueSolutionTask       = !empty($taskProgress) ? $taskProgress->solution : '';
                    
                    $valueProgressTask = '<b>Start Date : </b>'.$valueStartDateTask.'<br>'.
                                            '<b>End Date : </b>'.$valueEndDateTask.'<br>'.
                                            '<b>Progress Percentage : </b>'.$valuePercentTask.' % <br>'.
                                            '<b>Description : </b>'.$valueDescriptionTask.'<br>'.
                                            '<b>Problem : </b>'.$valueProblemTask.'<br>'.
                                            '<b>Solution : </b>'.$valueSolutionTask;
                    
                    $data[$milestone->milestone_progress_id][] = $valueProgressTask;
                }
            }

            $meeting = \DB::table('iapsys.meeting_project')
                            ->select('meeting_project.*', 'meeting.meeting_date', 'meeting.meeting_name', 'meeting.description as meeting_description')
                            ->where('meeting_project.project_id', '=', $filters['projectId'])
                            ->leftJoin('iapsys.meeting', 'meeting.meeting_id', '=', 'meeting_project.meeting_id');

            if (!empty($filters['startDate'])) {
                $startDate  = new \DateTime($filters['startDate']);
                $meeting->where('meeting_date', '>=', $startDate->format('Y-m-d H:i:s'));
            }

            if (!empty($filters['endDate'])) {
                $endDate    = new \DateTime($filters['endDate']);
                $meeting->where('meeting_date', '<=', $endDate->format('Y-m-d H:i:s'));
            }
        }

        $milestonesOption = [];
        if (!empty($filters['projectId']) && !empty($filters['sectionDiv'])) {
            $query = \DB::table('iapsys.milestone')
                            ->where('project_id', '=', $filters['projectId'])
                            ->where('section', '=', $filters['sectionDiv'])
                            ->orderBy('milestone_name', 'asc');

            $milestonesOption = $query->get();
        }

        return view('report.detail-history-milestone.index', [
            'projectOption'      => $this->getProject()->get(),
            'sectionOption'      => [ProjectController::TECHNICAL, ProjectController::MARKETING,],
            'milestonesOption'   => $milestonesOption,
            'filters'            => $filters,
            'columns'            => !empty($columns) ? $columns : [],
            'data'               => !empty($data) ? $data : [],
            'meeting'            => !empty($meeting) ? $meeting->get() : [],
            'url'                => self::URL,
        ]);
    }

    public function emptyTaskProgress($tasks, $milestone){
        foreach ($tasks as $task) {
            $taskProgress = TaskProgress::where('milestone_progress_id', '=', $milestone->milestone_progress_id)
                                                        ->where('task_id', '=', $task->task_id)->first();

            $valueDescriptionTask    = !empty($taskProgress) ? $taskProgress->description : null;
            $valueProblemTask        = !empty($taskProgress) ? $taskProgress->problem : null;
            $valueSolutionTask       = !empty($taskProgress) ? $taskProgress->solution : null;
            
            if(empty($valueDescriptionTask) && empty($valueProblemTask) && empty($valueSolutionTask)){
                return true;
            }

        }
        return false;
    }

    public function getProject(){
        $query = \DB::table('iapsys.v_project')
                ->whereRaw('v_project.inventory_item_id in (select project_id from iapsys.project_progress where validated_date is not null)')
                ->distinct();

        return $query;
    }

    public function getMilestone(Request $request)
    {
        $projectId = $request->get('projectId');
        $sectionDiv = $request->get('sectionDiv');
        $milestone = [];

        if (!empty($projectId) && !empty($sectionDiv)) {
            $query = \DB::table('iapsys.milestone')
                            ->where('project_id', '=', $projectId)
                            ->where('section', '=', $sectionDiv)
                            ->orderBy('milestone_name', 'asc');

            $milestone = $query->get();
        }

        return response()->json($milestone);
    }
}

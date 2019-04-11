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

class DetailHistoryProjectController extends Controller
{
    const URL      = 'report/detail-history-project';
    const RESOURCE = 'Report\Detail History Project';
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

        $milestones = [];
        $columns    = [];
        $data       = [];
        $meeting    = [];
        $filters    = $request->session()->get('filters');

        if(!empty($filters['projectId']) && !empty($filters['sectionDiv'])) {

            $projectProgress = \DB::table('iapsys.project_progress');


            if (!empty($filters['startDate'])) {
                $startDate  = new \DateTime($filters['startDate']);
                $projectProgress->where('project_progress.created_date', '>=', $startDate->format('Y-m-d H:i:s'));
            }

            if (!empty($filters['endDate'])) {
                $endDate    = new \DateTime($filters['endDate']);
                $projectProgress->where('project_progress.created_date', '<=', $endDate->format('Y-m-d H:i:s'));
            }

            $projectProgress->where('project_progress.project_id', '=', $filters['projectId'])
                            ->where('project_progress.section', '=', $filters['sectionDiv'])
                            ->whereNotNull('validated_date')
                            ->orderBy('project_progress.created_date', 'desc');

            $milestones = Milestone::where('project_id', '=', $filters['projectId'])
                                ->where('section', '=', $filters['sectionDiv'])
                                ->orderBy('milestone_name', 'asc')
                                ->get();

            $columns[] = 'Progress Project';
            foreach ($milestones as $value) {
                $columns[] = $value->milestone_name;
            }

            foreach ($projectProgress->get() as $project) {
                $date                           = !empty($project->created_date) ? new \DateTime($project->created_date ) : null;
                $startDateProjectProgress       = !empty($project->start_date) ? new \DateTime($project->start_date ) : null; 
                $endDateProjectProgress         = !empty($project->end_date) ? new \DateTime($project->end_date ) : null; 

                $valueStartDateProject      = !empty($startDateProjectProgress) ? $startDateProjectProgress->format('d-m-Y') : '';
                $valueEndDateProject        = !empty($endDateProjectProgress) ? $endDateProjectProgress->format('d-m-Y') : '';
                $valuePercentProject        = !empty($project) ? $project->progress_percentage : '0';
                $valueDescriptionProject    = !empty($project) ? $project->description : '';
                $valueProblemProject        = !empty($project) ? $project->problem : '';
                $valueSolutionProject       = !empty($project) ? $project->solution : '';
                
                $valueProgressProject = '<b>Start Date : </b>'.$valueStartDateProject.'<br>'.
                                        '<b>End Date : </b>'.$valueEndDateProject.'<br>'.
                                        '<b>Progress Percentage : </b>'.$valuePercentProject.' % <br>'.
                                        '<b>Description : </b>'.$valueDescriptionProject.'<br>'.
                                        '<b>Problem : </b>'.$valueProblemProject.'<br>'.
                                        '<b>Solution : </b>'.$valueSolutionProject;
                if(empty($valueDescriptionProject) && empty($valueProblemProject) && empty($valueSolutionProject) && $this->emptyMilestoneProgress($milestones, $project)){
                    // continue;
                }

                $data[$project->project_progress_id] [] = $date->format('d-m-Y H:i:s');
                $data[$project->project_progress_id] [] = $valueProgressProject;
                foreach ($milestones as $milestone) {
                    $milestoneProgress = MilestoneProgress::where('project_progress_id', '=', $project->project_progress_id)
                                                            ->where('milestone_id', '=', $milestone->milestone_id)->first();

                    $startDateMilestoneProgress       = !empty($milestoneProgress->start_date) ? new \DateTime($milestoneProgress->start_date) : null; 
                    $endDateMilestoneProgress         = !empty($milestoneProgress->end_date) ? new \DateTime($milestoneProgress->end_date) : null; 

                    $valueStartDateMilestone      = !empty($startDateMilestoneProgress) ? $startDateMilestoneProgress->format('d-m-Y') : '';
                    $valueEndDateMilestone        = !empty($endDateMilestoneProgress) ? $endDateMilestoneProgress->format('d-m-Y') : '';
                    $valuePercentMilestone        = !empty($milestoneProgress) ? $milestoneProgress->progress_percentage : '0';
                    $valueDescriptionMilestone    = !empty($milestoneProgress) ? $milestoneProgress->description : '';
                    $valueProblemMilestone        = !empty($milestoneProgress) ? $milestoneProgress->problem : '';
                    $valueSolutionMilestone       = !empty($milestoneProgress) ? $milestoneProgress->solution : '';
                    
                    $valueProgressMilestone = '<b>Start Date : </b>'.$valueStartDateMilestone.'<br>'.
                                            '<b>End Date : </b>'.$valueEndDateMilestone.'<br>'.
                                            '<b>Progress Percentage : </b>'.$valuePercentMilestone.' % <br>'.
                                            '<b>Description : </b>'.$valueDescriptionMilestone.'<br>'.
                                            '<b>Problem : </b>'.$valueProblemMilestone.'<br>'.
                                            '<b>Solution : </b>'.$valueSolutionMilestone;
                    
                    $data[$project->project_progress_id][] = $valueProgressMilestone;
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

        return view('report.detail-history-project.index', [
            'projectOption'      => $this->getProject()->get(),
            'sectionOption'      => [ProjectController::TECHNICAL, ProjectController::MARKETING,],
            'filters'            => $filters,
            'columns'            => !empty($columns) ? $columns : [],
            'data'               => !empty($data) ? $data : [],
            'meeting'            => !empty($meeting) ? $meeting->get() : [],
            'url'                => self::URL,
        ]);
    }

    public function emptyMilestoneProgress($milestones, $project){
        foreach ($milestones as $milestone) {
            $milestoneProgress = MilestoneProgress::where('project_progress_id', '=', $project->project_progress_id)
                                                        ->where('milestone_id', '=', $milestone->milestone_id)->first();

            $valueDescriptionMilestone    = !empty($milestoneProgress) ? $milestoneProgress->description : null;
            $valueProblemMilestone        = !empty($milestoneProgress) ? $milestoneProgress->problem : null;
            $valueSolutionMilestone       = !empty($milestoneProgress) ? $milestoneProgress->solution : null;
            
            // dd($valueSolutionMilestone);
            if(empty($valueDescriptionMilestone) && empty($valueProblemMilestone) && empty($valueSolutionMilestone)){
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

    
}

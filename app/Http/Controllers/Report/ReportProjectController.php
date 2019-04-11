<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Transaction\ProjectController;
use App\Http\Controllers\Controller;
use App\Model\Transaction\ProjectProgress;
use App\Model\Transaction\MilestoneProgress;
use App\Model\Transaction\TaskProgress;
use App\Model\Transaction\Milestone;
use App\Model\Transaction\Task;
use App\Service\AuthorizationService;
use Illuminate\Http\Request;

class ReportProjectController extends Controller
{
    const URL       = 'report/project';
    const RESOURCE  = 'Report\Last Update Project';

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
                ->whereRaw('v_project.inventory_item_id in (select project_id from iapsys.project_progress where validated_date is not null)')
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

        return view('report.project.index', [
            'models'      => $query->paginate(10),
            'filters'     => $filters,
            'typeOption'  => $this->getTypeOpttion(),
            'url'         => self::URL,
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

    public function detail(Request $request, $id)
    {
        if(!AuthorizationService::check(self::RESOURCE, 'view')){
            abort(403);
        }

        if(!is_numeric($id)){
            abort(404);
        }

        $query = $this->getProject($id);

        if(empty($query)){
            abort(404);
        }

        $milestoneTechnical = Milestone::where('project_id', '=', $id)
                                ->where('section', '=', ProjectController::TECHNICAL)
                                ->orderBy('milestone_name', 'asc')->get();
        $milestoneMarketing = Milestone::where('project_id', '=', $id)
                                ->where('section', '=', ProjectController::MARKETING)
                                ->orderBy('milestone_name', 'asc')->get();

        $projectProgressTechnical = ProjectProgress::where('project_id', '=', $id)
                                        ->where('section', '=', ProjectController::TECHNICAL)
                                        ->whereNotNull('validated_date')
                                        ->orderBy('created_date', 'desc')
                                        ->orderBy('project_progress_id', 'desc')
                                        ->first();
        $projectProgressMarketing = ProjectProgress::where('project_id', '=', $id)
                                        ->where('section', '=', ProjectController::MARKETING)
                                        ->whereNotNull('validated_date')
                                        ->orderBy('created_date', 'desc')
                                        ->orderBy('project_progress_id', 'desc')
                                        ->first();

        return view('report.project.detail', [
            'url'                       => self::URL,
            'model'                     => $query,
            'milestonesTechnical'       => $milestoneTechnical,
            'milestonesMarketing'       => $milestoneMarketing,
            'projectProgressTechnical'  => $projectProgressTechnical,
            'projectProgressMarketing'  => $projectProgressMarketing,
        ]);
    }

    private function getProject($id){
        $query = \DB::table('iapsys.v_project')
                    ->where('inventory_item_id', '=', $id)
                    ->first();
        return $query;
    }

    public function showHistoryProject(Request $request, $id, $section)
    {
        if(!AuthorizationService::check(self::RESOURCE, 'view')){
            abort(403);
        }

        if(!is_numeric($id)){
            abort(404);
        }

        if(!in_array($section, [ProjectController::TECHNICAL, ProjectController::MARKETING])){
            abort(404);
        }

        $model = $this->getProject($id);

        if(empty($model)){
            abort(404);
        }

        $modelProgress = ProjectProgress::where('project_id', '=', $id)
                            ->whereNotNull('validated_date')
                            ->where('section', '=', $section)
                            ->orderBy('created_date', 'asc')
                            ->orderBy('project_progress_id', 'asc')
                            ->get();
        
        $date = [];
        $data = [];

        foreach ($modelProgress as $progress) {
            $tempDate = new \DateTime($progress->created_date);
            $date [] = $tempDate->format('d-m-Y');
            $data [] = intval($progress->progress_percentage);
        }
        $dataArr  = [
                        'tooltip'   => [
                            'trigger'       => 'axis',
                        ],
                        'legend'    => [
                            'data'          => [$model->description],
                        ],
                        'xAxis'     => [
                            [
                                'boundaryGap'   => false,
                                'data'          => $date,
                            ]
                        ],
                        'yAxis'     => [
                            [
                            'type'          => 'value',
                            ],
                        ],
                        'series'    => [
                            [
                                'name'          => $model->description,
                                'type'          => 'line',
                                'smooth'        => true,
                                'itemStyle'     => [
                                    'normal'        => [
                                        'areaStyle'     => [
                                            'type'          => 'default',
                                        ],
                                    ],
                                ],
                                'data'      => $data,
                            ],
                        ],
                    ];
        $modelProgress = ProjectProgress::where('project_id', '=', $id)
                            ->whereNotNull('validated_date')
                            ->where('section', '=', $section)
                            ->orderBy('created_date', 'desc')
                            ->orderBy('project_progress_id', 'asc')
                            ->get();

        return view('report.project.show-history', [
            'url'               => self::URL,
            'type'              => 'Project',
            'projectId'         => $model->inventory_item_id,
            'projectCode'       => $model->segment1.'.'.$model->segment2.'.'.$model->segment3,
            'projectName'       => $model->description,
            'milestoneName'     => '',
            'taskName'          => '',
            'data'              => $dataArr,
            'section'           => $section,
            'modelProgress'     => $modelProgress,
        ]);
    }

    public function showHistoryMilestone(Request $request, $id, $section)
    {
        if(!AuthorizationService::check(self::RESOURCE, 'view')){
            abort(403);
        }

        if(!is_numeric($id)){
            abort(404);
        }

        if(!in_array($section, [ProjectController::TECHNICAL, ProjectController::MARKETING])){
            abort(404);
        }

        $modelMilestone = Milestone::find($id); 

        if(empty($modelMilestone)){
            abort(404);
        }

        $modelProgress = \DB::table('iapsys.milestone_progress')
                            ->join('iapsys.milestone', 'milestone.milestone_id', 'milestone_progress.milestone_id')
                            ->where('milestone_progress.milestone_id', '=', $id)
                            ->whereNotNull('validated_date')
                            ->where('section', '=', $section)
                            ->orderBy('milestone_progress.created_date', 'asc')->get();
        $model = $this->getProject($modelMilestone->project_id);

        
        $date = [];
        $data = [];

        foreach ($modelProgress as $progress) {
            $tempDate = new \DateTime($progress->created_date);
            $date [] = $tempDate->format('d-m-Y');
            $data [] = intval($progress->progress_percentage);
        }
        $dataArr  = [
                        'tooltip'   => [
                            'trigger'       => 'axis',
                        ],
                        'legend'    => [
                            'data'          => [$modelMilestone->milestone_name],
                        ],
                        'xAxis'     => [
                            [
                                'boundaryGap'   => false,
                                'data'          => $date,
                            ]
                        ],
                        'yAxis'     => [
                            [
                            'type'          => 'value',
                            ],
                        ],
                        'series'    => [
                            [
                                'name'          => $modelMilestone->milestone_name,
                                'type'          => 'line',
                                'smooth'        => true,
                                'itemStyle'     => [
                                    'normal'        => [
                                        'areaStyle'     => [
                                            'type'          => 'default',
                                        ],
                                    ],
                                ],
                                'data'      => $data,
                            ],
                        ],
                    ];

        $modelProgress  = \DB::table('iapsys.milestone_progress')
                            ->join('iapsys.milestone', 'milestone.milestone_id', 'milestone_progress.milestone_id')
                            ->where('milestone_progress.milestone_id', '=', $id)
                            ->whereNotNull('validated_date')
                            ->where('section', '=', $section)
                            ->orderBy('milestone_progress.created_date', 'desc')->get();

        return view('report.project.show-history', [
            'url'               => self::URL,
            'type'              => 'Milestone',
            'projectId'         => $model->inventory_item_id,
            'projectCode'       => $model->segment1.'.'.$model->segment2.'.'.$model->segment3,
            'projectName'       => $model->description,
            'milestoneName'     => $modelMilestone->milestone_name,
            'taskName'          => '',
            'section'           => $section,
            'data'              => $dataArr,
            'modelProgress'     => $modelProgress,
        ]);
    }

    public function showHistoryTask(Request $request, $id, $section)
    {
        if(!AuthorizationService::check(self::RESOURCE, 'view')){
            abort(403);
        }

        if(!is_numeric($id)){
            abort(404);
        }

        if(!in_array($section, [ProjectController::TECHNICAL, ProjectController::MARKETING])){
            abort(404);
        }

        $modelTask = Task::find($id); 

        if(empty($modelTask)){
            abort(404);
        }

        
        $modelProgress = \DB::table('iapsys.task_progress')
                            ->select('task_progress.*')
                            ->join('iapsys.milestone_progress', 'milestone_progress.milestone_progress_id', 'task_progress.milestone_progress_id')
                            ->join('iapsys.project_progress', 'project_progress.project_progress_id', 'milestone_progress.project_progress_id')
                            ->where('milestone_progress.milestone_id', '=', $id)
                            ->whereNotNull('validated_date')
                            ->where('project_progress.section', '=', $section)
                            ->orderBy('task_progress.created_date', 'asc')->get();

        $model = $this->getProject($modelTask->milestone->project_id);
        
        $date = [];
        $data = [];

        foreach ($modelProgress as $progress) {
            $tempDate = new \DateTime($progress->created_date);
            $date [] = $tempDate->format('d-m-Y');
            $data [] = intval($progress->progress_percentage);
        }
        $dataArr  = [
                        'tooltip'   => [
                            'trigger'       => 'axis',
                        ],
                        'legend'    => [
                            'data'          => [$modelTask->task_name],
                        ],
                        'xAxis'     => [
                            [
                                'boundaryGap'   => false,
                                'data'          => $date,
                            ]
                        ],
                        'yAxis'     => [
                            [
                            'type'          => 'value',
                            ],
                        ],
                        'series'    => [
                            [
                                'name'          => $modelTask->task_name,
                                'type'          => 'line',
                                'smooth'        => true,
                                'itemStyle'     => [
                                    'normal'        => [
                                        'areaStyle'     => [
                                            'type'          => 'default',
                                        ],
                                    ],
                                ],
                                'data'      => $data,
                            ],
                        ],
                    ];

        $modelProgress  = \DB::table('iapsys.task_progress')
                            ->select('task_progress.*')
                            ->join('iapsys.milestone_progress', 'milestone_progress.milestone_progress_id', 'task_progress.milestone_progress_id')
                            ->join('iapsys.project_progress', 'project_progress.project_progress_id', 'milestone_progress.project_progress_id')
                            ->where('milestone_progress.milestone_id', '=', $id)
                            ->whereNotNull('validated_date')
                            ->where('project_progress.section', '=', $section)
                            ->orderBy('task_progress.created_date', 'desc')->get();

        return view('report.project.show-history', [
            'url'               => self::URL,
            'type'              => 'Task',
            'projectId'         => $model->inventory_item_id,
            'projectCode'       => $model->segment1.'.'.$model->segment2.'.'.$model->segment3,
            'projectName'       => $model->description,
            'milestoneName'     => $modelTask->milestone->milestone_name,
            'taskName'          => $modelTask->task_name,
            'data'              => $dataArr,
            'section'           => $section,
            'modelProgress'     => $modelProgress,
        ]);
    }
}

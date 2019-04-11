@extends('master')

@section('title', 'Project')

<?php 
use App\Http\Controllers\Transaction\ProjectController;
 ?>

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="widget">
        <header class="widget-header">
            <h4 class="widget-title">Project</h4>
        </header>
        <hr class="widget-separator">
        <div class="widget-body">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Item Code</label>
                                    <label class="col-sm-8 control-label">{{ $model->segment1.'.'.$model->segment2.'.'.$model->segment3 }}</label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Project Name</label>
                                    <label class="col-sm-8 control-label">{{ $model->description }}</label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Customer</label>
                                    <label class="col-sm-8 control-label">{{ $model->customer_name }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">SO Number</label>
                                    <label class="col-sm-8 control-label">{{ $model->so_number }}</label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Project Value</label>
                                    <label class="col-sm-8 control-label">{{ $model->currency }} {{ number_format($model->ordered_quantity * $model->unit_selling_price) }} </label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="m-b-lg nav-tabs-horizontal">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#tab-1" aria-controls="tab-1" role="tab" data-toggle="tab" aria-expanded="false">Technical</a></li>
                            <li role="presentation" class=""><a href="#tab-2" aria-controls="tab-2" role="tab" data-toggle="tab" aria-expanded="true">Marketing</a></li>
                        </ul>
                        <div class="tab-content p-md">
                            <div role="tabpanel" class="tab-pane fade active in" id="tab-1">
                                <form class="form-horizontal">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Start</label>
                                                <?php $startDate = !empty($projectProgressTechnical) && !empty($projectProgressTechnical->start_date) ? new \DateTime($projectProgressTechnical->start_date) : null ?>
                                                <label class="col-sm-8 control-label">{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">End</label>
                                                <?php $startDate = !empty($projectProgressTechnical) && !empty($projectProgressTechnical->end_date) ? new \DateTime($projectProgressTechnical->end_date) : null ?>
                                                <label class="col-sm-8 control-label">{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Progress</label>
                                                <label class="col-sm-8 control-label">{{ !empty($projectProgressTechnical) ? intval($projectProgressTechnical->progress_percentage) : 0 }}%</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Description</label>
                                                <label class="col-sm-8 control-label">{{ !empty($projectProgressTechnical) ? $projectProgressTechnical->description : '' }}</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Problem</label>
                                                <label class="col-sm-8 control-label">{{ !empty($projectProgressTechnical) ? $projectProgressTechnical->problem : '' }}</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Solution</label>
                                                <label class="col-sm-8 control-label">{{ !empty($projectProgressTechnical) ? $projectProgressTechnical->solution : '' }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="{{ url($url.'/update-detail/'.$model->inventory_item_id.'/'.ProjectController::TECHNICAL) }}" class="btn btn-sm btn-primary">Update Detail Project Technical</a>
                                            <a href="{{ url($url.'/update-progress/'.$model->inventory_item_id.'/'.ProjectController::TECHNICAL) }}" class="btn btn-sm btn-primary">Update Progress Technical</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr/>
                                            @foreach($milestonesTechnical as $milestone)
                                            <?php $milestoneProgress = $milestone->getLastMilestoneProgress(); ?>
                                            <div class="panel-group accordion" id="accordion" role="tablist">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab" id="heading-1">
                                                        <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#{{ 'milestone-'.$milestone->milestone_id }}" aria-expanded="false" aria-controls="{{ 'milestone-'.$milestone->milestone_id }}">
                                                            <h4 class="panel-title">{{ $milestone->milestone_name }} ({{ !empty($milestoneProgress) ? $milestoneProgress->progress_percentage : 0 }}%)</h4>
                                                            <i class="fa acc-switch"></i>
                                                        </a>
                                                    </div>
                                                    <div id="{{ 'milestone-'.$milestone->milestone_id }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-1">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">Start</label>
                                                                        <?php $startDate = !empty($milestoneProgress) && !empty($milestoneProgress->start_date) ? new \DateTime($milestoneProgress->start_date) : null ?>
                                                                        <label class="col-sm-8 control-label">{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}</label>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">End</label>
                                                                        <?php $startDate = !empty($milestoneProgress) && !empty($milestoneProgress->end_date) ? new \DateTime($milestoneProgress->end_date) : null ?>
                                                                        <label class="col-sm-8 control-label">{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}</label>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">Progress</label>
                                                                        <label class="col-sm-8 control-label">{{ !empty($milestoneProgress)  ? $milestoneProgress->progress_percentage : 0 }}%</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">Description</label>
                                                                        <label class="col-sm-8 control-label">{{ !empty($milestoneProgress) ? $milestoneProgress->description : '' }}</label>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">Problem</label>
                                                                        <label class="col-sm-8 control-label">{{ !empty($milestoneProgress) ? $milestoneProgress->problem : '' }}</label>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">Solution</label>
                                                                        <label class="col-sm-8 control-label">{{ !empty($milestoneProgress) ? $milestoneProgress->solution : '' }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <hr/>
                                                                    @foreach($milestone->tasks()->orderBy('task_name', 'asc')->get() as $task)
                                                                    <?php $taskProgress = $task->getLastTaskProgress(); ?>
                                                                    <div class="panel-group accordion" id="milestone-{{ $milestone->milestone_id }}" role="tablist">
                                                                        <div class="panel panel-default">
                                                                            <div class="panel-heading" role="tab" id="heading-2">
                                                                                <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#milestone-{{ $milestone->milestone_id }}" href="#{{ 'task-'.$task->task_id }}" aria-expanded="false" aria-controls="{{ 'task-'.$task->task_id }}">
                                                                                    <h4 class="panel-title">{{ $task->task_name }} ({{ !empty($taskProgress) ? $taskProgress->progress_percentage : 0 }}%)</h4>
                                                                                    <i class="fa acc-switch"></i>
                                                                                </a>
                                                                            </div>
                                                                            <div id="{{ 'task-'.$task->task_id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-2">
                                                                                <div class="panel-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-5">
                                                                                            <div class="form-group">
                                                                                                <label class="col-sm-4 control-label">Start</label>
                                                                                                <?php $startDate = !empty($taskProgress) && !empty($taskProgress->start_date) ? new \DateTime($taskProgress->start_date) : null ?>
                                                                                                <label class="col-sm-8 control-label">{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}</label>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label class="col-sm-4 control-label">End</label>
                                                                                                <?php $startDate = !empty($taskProgress) && !empty($taskProgress->end_date) ? new \DateTime($taskProgress->end_date) : null ?>
                                                                                                <label class="col-sm-8 control-label">{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}</label>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label class="col-sm-4 control-label">Progress</label>
                                                                                                <label class="col-sm-8 control-label">{{ !empty($taskProgress) ? $taskProgress->progress_percentage : 0 }}%</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-5">
                                                                                            <div class="form-group">
                                                                                                <label class="col-sm-4 control-label">Description</label>
                                                                                                <label class="col-sm-8 control-label">{{ !empty($taskProgress) ? $taskProgress->description : '' }}</label>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label class="col-sm-4 control-label">Problem</label>
                                                                                                <label class="col-sm-8 control-label">{{ !empty($taskProgress) ? $taskProgress->problem : '' }}</label>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label class="col-sm-4 control-label">Solution</label>
                                                                                                <label class="col-sm-8 control-label">{{ !empty($taskProgress) ? $taskProgress->solution : '' }}</label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </form>
                            </div><!-- .tab-pane  -->
                            <div role="tabpanel" class="tab-pane fade" id="tab-2">
                                <form class="form-horizontal">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Start</label>
                                                <?php $startDate = !empty($projectProgressMarketing) && !empty($projectProgressMarketing->start_date) ? new \DateTime($projectProgressMarketing->start_date) : null ?>
                                                <label class="col-sm-8 control-label">{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">End</label>
                                                <?php $startDate = !empty($projectProgressMarketing) && !empty($projectProgressMarketing->end_date) ? new \DateTime($projectProgressMarketing->end_date) : null ?>
                                                <label class="col-sm-8 control-label">{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Progress</label>
                                                <label class="col-sm-8 control-label">{{ !empty($projectProgressMarketing) ? intval($projectProgressMarketing->progress_percentage) : 0 }}%</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Description</label>
                                                <label class="col-sm-8 control-label">{{ !empty($projectProgressMarketing) ? $projectProgressMarketing->description : '' }}</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Problem</label>
                                                <label class="col-sm-8 control-label">{{ !empty($projectProgressMarketing) ? $projectProgressMarketing->problem : '' }}</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Solution</label>
                                                <label class="col-sm-8 control-label">{{ !empty($projectProgressMarketing) ? $projectProgressMarketing->solution : '' }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="{{ url($url.'/update-detail/'.$model->inventory_item_id.'/'.ProjectController::MARKETING) }}" class="btn btn-sm btn-primary">Update Detail Project Marketing</a>
                                            <a href="{{ url($url.'/update-progress/'.$model->inventory_item_id.'/'.ProjectController::MARKETING) }}" class="btn btn-sm btn-primary">Update Progress Marketing</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr/>
                                            @foreach($milestonesMarketing as $milestone)
                                            <?php $milestoneProgress = $milestone->getLastMilestoneProgress(); ?>
                                            <div class="panel-group accordion" id="accordion" role="tablist">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab" id="heading-1">
                                                        <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#{{ 'milestone-'.$milestone->milestone_id }}" aria-expanded="false" aria-controls="{{ 'milestone-'.$milestone->milestone_id }}">
                                                            <h4 class="panel-title">{{ $milestone->milestone_name }} ({{ !empty($milestoneProgress) ? $milestoneProgress->progress_percentage : 0 }}%)</h4>
                                                            <i class="fa acc-switch"></i>
                                                        </a>
                                                    </div>
                                                    <div id="{{ 'milestone-'.$milestone->milestone_id }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-1">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">Start</label>
                                                                        <?php $startDate = !empty($milestoneProgress) && !empty($milestoneProgress->start_date) ? new \DateTime($milestoneProgress->start_date) : null ?>
                                                                        <label class="col-sm-8 control-label">{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}</label>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">End</label>
                                                                        <?php $startDate = !empty($milestoneProgress) && !empty($milestoneProgress->end_date) ? new \DateTime($milestoneProgress->end_date) : null ?>
                                                                        <label class="col-sm-8 control-label">{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}</label>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">Progress</label>
                                                                        <label class="col-sm-8 control-label">{{ !empty($milestoneProgress)  ? $milestoneProgress->progress_percentage : 0 }}%</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">Description</label>
                                                                        <label class="col-sm-8 control-label">{{ !empty($milestoneProgress) ? $milestoneProgress->description : '' }}</label>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">Problem</label>
                                                                        <label class="col-sm-8 control-label">{{ !empty($milestoneProgress) ? $milestoneProgress->problem : '' }}</label>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">Solution</label>
                                                                        <label class="col-sm-8 control-label">{{ !empty($milestoneProgress) ? $milestoneProgress->solution : '' }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <hr/>
                                                                    @foreach($milestone->tasks()->orderBy('task_name', 'asc')->get() as $task)
                                                                    <?php $taskProgress = $task->getLastTaskProgress(); ?>
                                                                    <div class="panel-group accordion" id="milestone-{{ $milestone->milestone_id }}" role="tablist">
                                                                        <div class="panel panel-default">
                                                                            <div class="panel-heading" role="tab" id="heading-2">
                                                                                <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#milestone-{{ $milestone->milestone_id }}" href="#{{ 'task-'.$task->task_id }}" aria-expanded="false" aria-controls="{{ 'task-'.$task->task_id }}">
                                                                                    <h4 class="panel-title">{{ $task->task_name }} ({{ !empty($taskProgress) ? $taskProgress->progress_percentage : 0 }}%)</h4>
                                                                                    <i class="fa acc-switch"></i>
                                                                                </a>
                                                                            </div>
                                                                            <div id="{{ 'task-'.$task->task_id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-2">
                                                                                <div class="panel-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-5">
                                                                                            <div class="form-group">
                                                                                                <label class="col-sm-4 control-label">Start</label>
                                                                                                <?php $startDate = !empty($taskProgress) && !empty($taskProgress->start_date) ? new \DateTime($taskProgress->start_date) : null ?>
                                                                                                <label class="col-sm-8 control-label">{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}</label>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label class="col-sm-4 control-label">End</label>
                                                                                                <?php $startDate = !empty($taskProgress) && !empty($taskProgress->start_date) ? new \DateTime($taskProgress->end_date) : null ?>
                                                                                                <label class="col-sm-8 control-label">{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}</label>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label class="col-sm-4 control-label">Progress</label>
                                                                                                <label class="col-sm-8 control-label">{{ !empty($taskProgress) ? $taskProgress->progress_percentage : 0 }}%</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-5">
                                                                                            <div class="form-group">
                                                                                                <label class="col-sm-4 control-label">Description</label>
                                                                                                <label class="col-sm-8 control-label">{{ !empty($taskProgress) ? $taskProgress->description : '' }}</label>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label class="col-sm-4 control-label">Problem</label>
                                                                                                <label class="col-sm-8 control-label">{{ !empty($taskProgress) ? $taskProgress->problem : '' }}</label>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label class="col-sm-4 control-label">Solution</label>
                                                                                                <label class="col-sm-8 control-label">{{ !empty($taskProgress) ? $taskProgress->solution : '' }}</label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </form>
                            </div><!-- .tab-pane  -->
                        </div><!-- .tab-content  -->
                    </div>
                </div>
            </div>
            <div class="row">
                <hr>
                <div class="col-sm-12 text-right">
                    <a href="{{ url($url) }}" class="btn btn-sm btn-warning">Back</a>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection

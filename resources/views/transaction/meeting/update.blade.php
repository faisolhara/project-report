@extends('master')

@section('title', 'Project')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="widget">
        <header class="widget-header">
            <h4 class="widget-title">Update Progress Project ({{ $section }})</h4>
        </header>
        <hr class="widget-separator">
        <div class="widget-body">
            <form class="form-horizontal"  method="post" action="{{ url($url.'/save-progress') }}">
                {{ csrf_field() }}
                <input type="hidden" class="form-control" name ="projectId" id="projectId" value="{{ $model->inventory_item_id }}">
                <input type="hidden" class="form-control" name ="section" id="section" value="{{ $section }}">
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
                        <div class="form-group">
                            <label class="col-sm-4 control-label">SO Number</label>
                            <label class="col-sm-8 control-label">{{ $model->so_number }}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Project Value</label>
                            <label class="col-sm-8 control-label">{{ $model->currency }} {{ number_format($model->ordered_quantity * $model->unit_selling_price) }}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Start</label>
                            <?php $startDate = !empty($projectProgress) && !empty($projectProgress->start_date) ? new \DateTime($projectProgress->start_date) : null ?>
                            <div class="col-sm-8">
                                <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ defaultDate: '{{ !empty($startDate) ? $startDate->format('m/d/Y') : '' }}' }" name="startDateProject">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">End</label>
                            <?php $endDate = !empty($projectProgress) && !empty($projectProgress->end_date) ? new \DateTime($projectProgress->end_date) : null ?>
                            <div class="col-sm-8">
                                <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ defaultDate: '{{ !empty($endDate) ? $endDate->format('m/d/Y') : '' }}' }" name="endDateProject">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Progress (%)</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" name ="progressPercentageProject" id="progressPercentageProject" value="{{ !empty($projectProgress) ? $projectProgress->progress_percentage : 0 }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Description</label>
                            <div class="col-sm-8">
                              <textarea class="form-control" name ="descriptionProject" id="descriptionProject" ></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Problem</label>
                            <div class="col-sm-8">
                              <textarea class="form-control" name ="problemProject" id="problemProject" ></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Solution</label>
                            <div class="col-sm-8">
                              <textarea class="form-control" name ="solutionProject" id="solutionProject" ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr/>
                        @foreach($milestones as $milestone)
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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Start</label>
                                                    <?php $startDate = !empty($milestoneProgress) && !empty($milestoneProgress->start_date) ? new \DateTime($milestoneProgress->start_date) : null ?>
                                                    <div class="col-sm-8">
                                                        <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ defaultDate: '{{ !empty($startDate) ? $startDate->format('m/d/Y') : '' }}' }" name ="startDateMilestone-{{ $milestone->milestone_id }}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">End</label>
                                                    <?php $endDate = !empty($milestoneProgress) && !empty($milestoneProgress->end_date) ? new \DateTime($milestoneProgress->end_date) : null ?>
                                                    <div class="col-sm-8">
                                                        <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ defaultDate: '{{ !empty($endDate) ? $endDate->format('m/d/Y') : '' }}' }" name ="endDateMilestone-{{ $milestone->milestone_id }}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Progress (%)</label>
                                                    <div class="col-sm-8">
                                                      <input type="text" class="form-control" name ="progressPercentageMilestone-{{ $milestone->milestone_id }}" id="progressPercentageMilestone" value="{{ !empty($milestoneProgress) ? $milestoneProgress->progress_percentage : 0}}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Description</label>
                                                    <div class="col-sm-8">
                                                        <textarea class="form-control" name ="descriptionMilestone-{{ $milestone->milestone_id }}" id="descriptionMilestone" ></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Problem</label>
                                                    <div class="col-sm-8">
                                                        <textarea class="form-control" name ="problemMilestone-{{ $milestone->milestone_id }}" id="problemMilestone" ></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Solution</label>
                                                    <div class="col-sm-8">
                                                        <textarea class="form-control" name ="solutionMilestone-{{ $milestone->milestone_id }}" id="solutionMilestone" ></textarea>
                                                    </div>
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
                                                                <h4 class="panel-title">{{ $task->task_name }} ({{ !empty($taskProgress) ? $taskProgress->progress_percentage : 0}}%)</h4>
                                                                <i class="fa acc-switch"></i>
                                                            </a>
                                                        </div>
                                                        <div id="{{ 'task-'.$task->task_id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-2">
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="col-sm-4 control-label">Start</label>
                                                                            <?php $startDate = !empty($taskProgress) && !empty($taskProgress->start_date) ? new \DateTime($taskProgress->start_date) : null ?>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ defaultDate: '{{ !empty($startDate) ? $startDate->format('m/d/Y') : '' }}' }" name ="startDateTask-{{ $task->task_id }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-sm-4 control-label">End</label>
                                                                            <?php $endDate = !empty($taskProgress) && !empty($taskProgress->end_date) ? new \DateTime($taskProgress->end_date) : null ?>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ defaultDate: '{{ !empty($endDate) ? $endDate->format('m/d/Y') : '' }}' }" name ="endDateTask-{{ $task->task_id }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-sm-4 control-label">Progress (%)</label>
                                                                            <div class="col-sm-8">
                                                                              <input type="text" class="form-control" name ="progressPercentageTask-{{ $task->task_id }}" id="progressPercentageTask" value="{{ !empty($taskProgress) ? $taskProgress->progress_percentage : 0 }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-sm-4 control-label">Description</label>
                                                                            <div class="col-sm-8">
                                                                                <textarea class="form-control" name ="descriptionTask-{{ $task->task_id }}" id="descriptionTask" ></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="col-sm-4 control-label">Problem</label>
                                                                            <div class="col-sm-8">
                                                                                <textarea class="form-control" name ="problemTask-{{ $task->task_id }}" id="problemTask" ></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-sm-4 control-label">Solution</label>
                                                                            <div class="col-sm-8">
                                                                                <textarea class="form-control" name ="solutionTask-{{ $task->task_id }}" id="solutionTask" ></textarea>
                                                                            </div>
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
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <a href="{{ url($url.'/detail/'.$model->inventory_item_id) }}" class="btn btn-sm btn-warning">Back</a>
                        <a id="btn-save-progress" class="btn btn-sm btn-primary">Save</a>
                    </div>
                </div>

                <!-- Modal Konfirmasi -->
                <div class="modal fade" id="modal-konfirmasi" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Confirmation</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Are you sure all the progress content is correct ?</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">No</button>
                            <button type="submit" type="button" class="btn btn-sm btn-primary">Yes</button>
                        </div>
                    </div>
                  </div>
                </div>

            </form>
        </div>
    </div>
  </div>
</div>

@endsection

@section('script')
@parent
<script type="text/javascript">
    $(document).on('ready', function() {
        $('#btn-save-progress').on('click', function() {
            $('#modal-konfirmasi').modal('show');
        })
    })
</script>
@endsection

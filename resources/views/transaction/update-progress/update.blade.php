@extends('master')

@section('title', 'Progress')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="widget">
        <header class="widget-header">
            <h4 class="widget-title">Update Progress</h4>
        </header>
        <hr class="widget-separator">
        <div class="widget-body">
            <form class="form-horizontal" id="form-update-progress"  method="post" action="{{ url($url.'/save') }}">
                {{ csrf_field() }}
                <input type="hidden" class="form-control" name ="progressId" id="progressId" value="{{ $model->project_progress_id }}">
                <input type="hidden" class="form-control" name ="lastUpdatedDate" id="lastUpdatedDate" value="{{ $model->last_updated_date }}">
                <div class="row">
                    <div class="col-md-6">
                        <?php $createdDate = !empty($model) && !empty($model->created_date) ? new \DateTime($model->created_date) : null ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Created</label>
                            <label class="col-sm-8 control-label">{{ !empty($createdDate) ? $createdDate->format('d-m-Y H:i') : '' }} {{ !empty($model->created_by) ? '('.$model->created_by.')' : '' }}</label>
                        </div>
                        <?php $last_updatedDate = !empty($model) && !empty($model->last_updated_date) ? new \DateTime($model->last_updated_date) : null ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Last Updated</label>
                            <label class="col-sm-8 control-label">{{ !empty($last_updatedDate) ? $last_updatedDate->format('d-m-Y H:i') : '' }} {{ !empty($model->last_updated_by) ? '('.$model->last_updated_by.')' : '' }}</label>
                        </div>
                        <?php $validatedDate = !empty($model) && !empty($model->validated_date) ? new \DateTime($model->validated_date) : null ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Validated</label>
                            <label class="col-sm-8 control-label">{{ !empty($validatedDate) ? $validatedDate->format('d-m-Y H:i') : '' }} {{ !empty($model->validated_by) ? '('.$model->validated_by.')' : '' }}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Item Code</label>
                            <label class="col-sm-8 control-label">{{ $model->project->segment1.'.'.$model->project->segment2.'.'.$model->project->segment3 }}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Project Name</label>
                            <label class="col-sm-8 control-label">{{ $model->project->description }}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Customer</label>
                            <label class="col-sm-8 control-label">{{ $model->project->customer_name }}</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Start</label>
                            <?php $startDate = !empty($model) && !empty($model->start_date) ? new \DateTime($model->start_date) : null ?>
                            <div class="col-sm-8">
                                <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ format: 'DD-MM-YYYY' }" value="{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}" name="startDateProject">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">End</label>
                            <?php $endDate = !empty($model) && !empty($model->end_date) ? new \DateTime($model->end_date) : null ?>
                            <div class="col-sm-8">
                                <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ format: 'DD-MM-YYYY' }" value="{{ !empty($endDate) ? $endDate->format('d-m-Y') : '' }}" name="endDateProject">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Progress (%)</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" name ="progressPercentageProject" id="progressPercentageProject" value="{{ !empty($model) ? $model->progress_percentage : 0 }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                              <textarea class="form-control" name ="descriptionProject" id="descriptionProject" >{{ !empty($model) ? $model->description : '' }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Problem</label>
                            <div class="col-sm-10">
                              <textarea class="form-control" name ="problemProject" id="problemProject" >{{ !empty($model) ? $model->problem : '' }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Solution</label>
                            <div class="col-sm-10">
                              <textarea class="form-control" name ="solutionProject" id="solutionProject" >{{ !empty($model) ? $model->solution : '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Completed Milestones</label>
                            <div class="col-sm-8">
                                <input class="toggle-completed-milestones" type="checkbox" data-switchery checked />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr/>
                        @foreach($model->milestoneProgress()->join('milestone', 'milestone.milestone_id', '=', 'milestone_progress.milestone_id')->orderBy('milestone.milestone_name', 'asc')->get() as $milestoneProgress)
                        <div class="panel-group accordion" id="accordion" role="tablist">
                            <div class="panel panel-default">
                                <div class="panel-heading milestone-heading" role="tab" id="heading-1">
                                    <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#{{ 'milestone-'.$milestoneProgress->milestone_progress_id }}" aria-expanded="false" aria-controls="{{ 'milestone-'.$milestoneProgress->milestone_progress_id }}">
                                        <h4 class="panel-title">{{ $milestoneProgress->milestone->milestone_name }} (<span class="milestone-persentage">{{ !empty($milestoneProgress) ? $milestoneProgress->progress_percentage : 0 }}</span>%)</h4>
                                        <i class="fa acc-switch"></i>
                                    </a>
                                </div>
                                <div id="{{ 'milestone-'.$milestoneProgress->milestone_progress_id }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-1">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Start</label>
                                                    <?php $startDate = !empty($milestoneProgress) && !empty($milestoneProgress->start_date) ? new \DateTime($milestoneProgress->start_date) : null ?>
                                                    <div class="col-sm-8">
                                                        <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ format: 'DD-MM-YYYY' }" value="{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}" name ="startDateMilestone-{{ $milestoneProgress->milestone_progress_id }}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">End</label>
                                                    <?php $endDate = !empty($milestoneProgress) && !empty($milestoneProgress->end_date) ? new \DateTime($milestoneProgress->end_date) : null ?>
                                                    <div class="col-sm-8">
                                                        <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ format: 'DD-MM-YYYY' }" value="{{ !empty($endDate) ? $endDate->format('d-m-Y') : '' }}" name ="endDateMilestone-{{ $milestoneProgress->milestone_progress_id }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Progress (%)</label>
                                                    <div class="col-sm-8">
                                                      <input type="text" class="form-control" name ="progressPercentageMilestone-{{ $milestoneProgress->milestone_progress_id }}" id="progressPercentageMilestone" value="{{ !empty($milestoneProgress) ? $milestoneProgress->progress_percentage : 0}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Description</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" name ="descriptionMilestone-{{ $milestoneProgress->milestone_progress_id }}" id="descriptionMilestone" >{{ !empty($milestoneProgress) ? $milestoneProgress->description : '' }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Problem</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" name ="problemMilestone-{{ $milestoneProgress->milestone_progress_id }}" id="problemMilestone" >{{ !empty($milestoneProgress) ? $milestoneProgress->problem : '' }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Solution</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" name ="solutionMilestone-{{ $milestoneProgress->milestone_progress_id }}" id="solutionMilestone" >{{ !empty($milestoneProgress) ? $milestoneProgress->solution : '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Completed Tasks</label>
                                                    <div class="col-sm-10">
                                                        <input class="toggle-completed-tasks" type="checkbox" data-switchery checked />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <hr/>
                                                @foreach($milestoneProgress->taskProgress()->join('task', 'task.task_id', '=', 'task_progress.task_id')->orderBy('task.task_name', 'asc')->get() as $taskProgress)
                                                <div class="panel-group accordion" id="milestone-{{ $milestoneProgress->milestone_progress_id }}" role="tablist">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading task-heading" role="tab" id="heading-2">
                                                            <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#milestone-{{ $milestoneProgress->milestone_progress_id }}" href="#{{ 'task-'.$taskProgress->task_progress_id }}" aria-expanded="false" aria-controls="{{ 'task-'.$taskProgress->task_progress_id }}">
                                                                <h4 class="panel-title">{{ $taskProgress->task->task_name }} (<span class="task-persentage">{{ !empty($taskProgress) ? $taskProgress->progress_percentage : 0}}</span>%)</h4>
                                                                <i class="fa acc-switch"></i>
                                                            </a>
                                                        </div>
                                                        <div id="{{ 'task-'.$taskProgress->task_progress_id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-2">
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="col-sm-4 control-label">Start</label>
                                                                            <?php $startDate = !empty($taskProgress) && !empty($taskProgress->start_date) ? new \DateTime($taskProgress->start_date) : null ?>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ format: 'DD-MM-YYYY' }" value="{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}" name ="startDateTask-{{ $taskProgress->task_progress_id }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-sm-4 control-label">End</label>
                                                                            <?php $endDate = !empty($taskProgress) && !empty($taskProgress->end_date) ? new \DateTime($taskProgress->end_date) : null ?>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ format: 'DD-MM-YYYY' }" value="{{ !empty($endDate) ? $endDate->format('d-m-Y') : '' }}" name ="endDateTask-{{ $taskProgress->task_progress_id }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="col-sm-4 control-label">Progress (%)</label>
                                                                            <div class="col-sm-8">
                                                                              <input type="text" class="form-control" name ="progressPercentageTask-{{ $taskProgress->task_progress_id }}" id="progressPercentageTask" value="{{ !empty($taskProgress) ? $taskProgress->progress_percentage : 0 }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="col-sm-2 control-label">Description</label>
                                                                            <div class="col-sm-10">
                                                                                <textarea class="form-control" name ="descriptionTask-{{ $taskProgress->task_progress_id }}" id="descriptionTask" >{{ !empty($taskProgress) ? $taskProgress->description : '' }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-sm-2 control-label">Problem</label>
                                                                            <div class="col-sm-10">
                                                                                <textarea class="form-control" name ="problemTask-{{ $taskProgress->task_progress_id }}" id="problemTask" >{{ !empty($taskProgress) ? $taskProgress->problem : '' }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-sm-2 control-label">Solution</label>
                                                                            <div class="col-sm-10">
                                                                                <textarea class="form-control" name ="solutionTask-{{ $taskProgress->task_progress_id }}" id="solutionTask" >{{ !empty($taskProgress) ? $taskProgress->solution : '' }}</textarea>
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
                        <a href="{{ url($url) }}" class="btn btn-sm btn-warning">Back</a>
                        <a id="btn-save-progress" class="btn btn-sm btn-primary">Update</a>
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
                            <button type="submit" type="button" id="btn-submit-konfirmasi" class="btn btn-sm btn-primary">Yes</button>
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
        $('#btn-submit-konfirmasi').on('click', function(event) {
            event.preventDefault();
            var lastUpdatedDate = $('#lastUpdatedDate').val();
            var progressId      = $('#progressId').val();

            $.ajax({
                method: "POST",
                url: "{{ URL($url.'/check-last-updated') }}",
                data: {lastUpdatedDate: lastUpdatedDate, progressId: progressId, _token: "{{ csrf_token() }}"}
            }).done(function(result) {
                if(!result.check){
                    alert(result.message);
                }else{
                    $('#form-update-progress').submit();
                }
            });
        })
    })
</script>
@endsection

@section('script')
@parent
  <script type="text/javascript">
    $(document).on('ready', function() {
        $('.toggle-completed-milestones').on('change', function() {
            var checked = $(this).is(':checked');
            $(this).parent().parent().parent().parent().parent().find('span.milestone-persentage').each(function() {
                if (parseInt($(this).html()) >= 100) {
                    if (checked) {
                        $(this).parent().parent().parent().parent().parent().show();
                    } else {
                        $(this).parent().parent().parent().parent().parent().hide();
                    }
                }
            })
        })

        $('.toggle-completed-tasks').on('change', function() {
            var checked = $(this).is(':checked');
            $(this).parent().parent().parent().parent().parent().find('span.task-persentage').each(function() {
                if (parseInt($(this).html()) >= 100) {
                    if (checked) {
                        $(this).parent().parent().parent().parent().parent().show();
                    } else {
                        $(this).parent().parent().parent().parent().parent().hide();
                    }
                }
            })
        })
    })
  </script>
@endsection
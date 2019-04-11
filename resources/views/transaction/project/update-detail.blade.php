@extends('master')

@section('title', 'Project')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="widget">
        <header class="widget-header">
            <h4 class="widget-title">Add / Remove Milestones ({{ $section }})</h4>
        </header>
        <hr class="widget-separator">
        <div class="widget-body">
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
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a href="#" class="btn btn-sm btn-success btn-add-milestone" data-project-id="{{ $model->inventory_item_id }}">Add Milestone</a>
                        <hr/>
                        <div class="panel-group accordion" id="accordion" role="tablist">
                            @foreach($milestones as $milestone)
                            <div class="panel panel-default template">
                                <div class="panel-heading milestone-heading" role="tab" id="heading-1">
                                    <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#milestone-{{ $milestone->milestone_id }}" aria-expanded="false" aria-controls="milestone-{{ $milestone->milestone_id }}">
                                        <h4 class="panel-title">{{ $milestone->milestone_name }}</h4>
                                        <i class="fa acc-switch"></i>
                                    </a>
                                </div>
                                <div id="milestone-{{ $milestone->milestone_id }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-1">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <a href="#" class="btn btn-sm btn-success btn-add-task" data-milestone-id="{{ $milestone->milestone_id }}" data-milestone-name="{{ $milestone->milestone_name }}">Add Task</a>
                                                <a href="#" class="btn btn-sm btn-warning btn-edit-milestone" data-milestone-id="{{ $milestone->milestone_id }}" data-milestone-name="{{ $milestone->milestone_name }}">Edit Milestone</a>
                                                <a href="#" class="btn btn-sm btn-danger btn-remove-milestone" data-milestone-id="{{ $milestone->milestone_id }}" data-milestone-name="{{ $milestone->milestone_name }}">Remove Milestone</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <hr/>
                                                <div class="panel-group accordion" id="accordion-1" role="tablist">
                                                    @foreach($milestone->tasks()->orderBy('task_name', 'asc')->get() as $task)
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading task-heading" role="tab" id="heading-2">
                                                            <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion-1" href="#task-{{ $task->task_id }}" aria-expanded="false" aria-controls="task-{{ $task->task_id }}">
                                                                <h4 class="panel-title">{{ $task->task_name }}</h4>
                                                                <i class="fa acc-switch"></i>
                                                            </a>
                                                        </div>
                                                        <div id="task-{{ $task->task_id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-2">
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 text-center">
                                                                        <a href="#" class="btn btn-sm btn-warning btn-edit-task" data-milestone-name="{{ $milestone->milestone_name }}" data-task-id="{{ $task->task_id }}" data-task-name="{{ $task->task_name }}">Edit Task</a>
                                                                        <a href="#" class="btn btn-sm btn-danger btn-remove-task" data-task-id="{{ $task->task_id }}" data-task-name="{{ $task->task_name }}">Remove Task</a>
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
                </div>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <a href="{{ url($url.'/detail/'.$model->inventory_item_id) }}" class="btn btn-sm btn-warning">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-add-milestone" tabindex="-1" role="dialog" aria-labelledby="modal-add-milestone-title">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="{{ url($url.'/save-milestone') }}">
        {{ csrf_field() }}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modal-add-milestone-title">Add Milestone</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="addMilestoneName" class="col-sm-3 control-label">Milestone</label>
                <div class="col-sm-9">
                    <input type="hidden" class="section" name="section" value="{{ $section }}">
                    <input type="hidden" class="projectId" name="projectId">
                    <input type="hidden" class="milestoneId" name="milestoneId">
                    <input type="text" class="form-control milestoneName" name="milestoneName">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" type="button" class="btn btn-sm btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-remove-milestone" tabindex="-1" role="dialog" aria-labelledby="modal-remove-milestone-title">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="{{ url($url.'/delete-milestone') }}">
        {{ csrf_field() }}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modal-remove-milestone-title">Remove Milestone</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <input type="hidden" class="section" name="section" value="{{ $section }}">
                <input type="hidden" class="milestoneId" name="milestoneId">
                <label class="col-sm-12 control-label">Are you sure want to remove milestone <span class="milestoneName"></span> and all its tasks and progress?</label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" type="button" class="btn btn-sm btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-add-task" tabindex="-1" role="dialog" aria-labelledby="modal-add-task-title">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="{{ url($url.'/save-task') }}">
        {{ csrf_field() }}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modal-add-task-title">Add Task</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="milestoneName" class="col-sm-3 control-label">Milestone</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control milestoneName" name="milestoneName" disabled>
                </div>
            </div>
            <div class="form-group">
                <label for="taskName" class="col-sm-3 control-label">Task</label>
                <div class="col-sm-9">
                    <input type="hidden" class="milestoneId" name="milestoneId">
                    <input type="hidden" class="section" name="section" value="{{ $section }}">
                    <input type="hidden" class="taskId" name="taskId">
                    <input type="text" class="form-control taskName" name="taskName">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" type="button" class="btn btn-sm btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-remove-task" tabindex="-1" role="dialog" aria-labelledby="modal-remove-task-title">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="{{ url($url.'/delete-task') }}">
        {{ csrf_field() }}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modal-remove-task-title">Remove Task</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <input type="hidden" class="section" name="section" value="{{ $section }}">
                <input type="hidden" class="taskId" name="taskId">
                <label class="col-sm-12 control-label">Are you sure want to remove task <span class="taskName"></span> and all its progress?</label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" type="button" class="btn btn-sm btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('script')
@parent
<script type="text/javascript">
$(document).on('ready', function() {
    $('.btn-add-milestone').on('click', function() {
        var projectId = $(this).data('project-id');
        $('#modal-add-milestone').find('input.projectId').val(projectId);
        $('#modal-add-milestone').find('input.milestoneId').val('');
        $('#modal-add-milestone').find('input.milestoneName').val('');

        $('#modal-add-milestone-title').html('Add Milestone');
        $('#modal-add-milestone').modal('show');
    })

    $('.btn-edit-milestone').on('click', function() {
        var milestoneId = $(this).data('milestone-id');
        var milestoneName = $(this).data('milestone-name');

        $('#modal-add-milestone').find('input.projectId').val('');
        $('#modal-add-milestone').find('input.milestoneId').val(milestoneId);
        $('#modal-add-milestone').find('input.milestoneName').val(milestoneName);

        $('#modal-add-milestone-title').html('Edit Milestone');
        $('#modal-add-milestone').modal('show');
    })

    $('.btn-remove-milestone').on('click', function() {
        var milestoneId = $(this).data('milestone-id');
        var milestoneName = $(this).data('milestone-name');

        $('#modal-remove-milestone').find('input.milestoneId').val(milestoneId);
        $('#modal-remove-milestone').find('span.milestoneName').html(milestoneName);

        $('#modal-remove-milestone').modal('show');
    })

    $('.btn-add-task').on('click', function() {
        var milestoneId = $(this).data('milestone-id');
        var milestoneName = $(this).data('milestone-name');
        $('#modal-add-task').find('input.milestoneId').val(milestoneId);
        $('#modal-add-task').find('input.milestoneName').val(milestoneName);
        $('#modal-add-task').find('input.taskId').val('');
        $('#modal-add-task').find('input.taskName').val('');

        $('#modal-add-task-title').html('Add task');
        $('#modal-add-task').modal('show');
    })

    $('.btn-edit-task').on('click', function() {
        var milestoneName = $(this).data('milestone-name');
        var taskId = $(this).data('task-id');
        var taskName = $(this).data('task-name');

        $('#modal-add-task').find('input.milestoneId').val('');
        $('#modal-add-task').find('input.milestoneName').val(milestoneName);
        $('#modal-add-task').find('input.taskId').val(taskId);
        $('#modal-add-task').find('input.taskName').val(taskName);

        $('#modal-add-task-title').html('Edit task');
        $('#modal-add-task').modal('show');
    })

    $('.btn-remove-task').on('click', function() {
        var taskId = $(this).data('task-id');
        var taskName = $(this).data('task-name');

        $('#modal-remove-task').find('input.taskId').val(taskId);
        $('#modal-remove-task').find('span.taskName').html(taskName);

        $('#modal-remove-task').modal('show');
    })
})
</script>
@endsection
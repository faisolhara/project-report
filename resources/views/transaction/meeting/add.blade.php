@extends('master')

@section('title', 'Meeting')

@section('style')
@parent
<style type="text/css">
    .select2-container{
        width: 100% !important;
    }
</style>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="widget">
        <header class="widget-header">
            <h4 class="widget-title">Meeting</h4>
        </header>
        <hr class="widget-separator">
        <div class="widget-body">
            <form class="form-horizontal" id="form-add-meeting" method="POST" action="{{ url($url.'/save') }}">
              {{ csrf_field() }}
              <input type="hidden" id="meetingId" name="meetingId" value="{{ count($errors) > 0 ? old('meetingId') : $models->meeting_id }}">
              <input type="hidden" id="lastUpdatedDate" name="lastUpdatedDate" value="{{ count($errors) > 0 ? old('lastUpdatedDate') : $models->last_updated_date }}">
                <div class="row">
                    <div class="col-md-12">
                        <?php 
                        $stringDate     = count($errors) > 0 ? old('meetingDate') : $models->meeting_date;
                        $meetingDate    = count($errors) == 0 || !empty($stringDate) ? new \DateTime($stringDate) : null;
                        ?>
                        <div class="form-group">
                            <label for="meetingDate" class="col-sm-2 control-label">Meeting Date *</label>
                            <div class="col-sm-4">
                              <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ format: 'DD-MM-YYYY HH:mm' }" value="{{ !empty($meetingDate) ? $meetingDate->format('d-m-Y H:i') : '' }}" name ="meetingDate">
                              @if($errors->has('meetingDate'))
                              <span class="label label-danger menu-label">{{ $errors->first('meetingDate') }}</span>
                              @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="meetingName" class="col-sm-2 control-label">Meeting Name *</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" name ="meetingName" id="meetingName" value="{{ count($errors) > 0 ? old('meetingName') : $models->meeting_name }}">
                              @if($errors->has('meetingName'))
                              <span class="label label-danger menu-label">{{ $errors->first('meetingName') }}</span>
                              @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">Description *</label>
                            <div class="col-sm-10">
                              <textarea type="text" class="form-control" name ="description" id="description" value="">{{ count($errors) > 0 ? old('description') : $models->description }}</textarea>
                              @if($errors->has('description'))
                              <span class="label label-danger menu-label">{{ $errors->first('description') }}</span>
                              @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr/>
                        <div class="form-group">
                            <label for="addProject" class="col-sm-2 control-label">Project</label>
                            <div class="col-sm-8">
                                <select id="projectId" class="form-control" data-plugin="select2" name="projectId" id="projectId">
                                    <option value="" >Please Select Project</option>
                                    @foreach($projectOption as $project)
                                    <option value="{{ $project->inventory_item_id }}">{{ $project->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                              <a href="#" id="btn-add-project" class="btn btn-sm btn-success">Add Project</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="table-project" style="margin: 10px 0" class="table table-striped table-bordered table-responsive table-hover">
                          <thead>
                            <tr>
                              <th width="300px">Project Name</th>
                              <th>Description</th>
                              <th>Problem</th>
                              <th>Solution</th>
                              <th width="50px">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(count($errors) > 0)
                            @for($i = 0; $i < count(old('projectId', [])); $i++)
                            <tr>
                              <td>
                                <input type="hidden" name="projectId[]" value="{{ old('projectId')[$i] }}" />
                                <input type="hidden" name="projectName[]" value="{{ old('projectName')[$i] }}" />
                                {{ old('projectName')[$i] }}
                              </td>
                              <td>
                                <textarea type="text" class="form-control" name ="projectDescription[]"> {{ old('projectDescription')[$i] }} </textarea>
                              </td>
                              <td>
                                <textarea type="text" class="form-control" name ="projectProblem[]">{{ old('projectProblem')[$i] }}</textarea>
                              </td>
                              <td>
                                <textarea type="text" class="form-control" name ="projectSolution[]">{{ old('projectSolution')[$i] }}</textarea>
                              </td>
                              <td class="text-center">
                                  <a href="#" class="icon icon-circle icon-sm m-b-0 btn-remove-project" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete Project"><i class="fa fa-remove"></i></a>
                              </td>
                            </tr>
                            @endfor
                            @else
                            @foreach($models->meetingProject()->select('meeting_project.*', 'project_view.description as project_name')->join('iapsys.project_view', 'project_view.inventory_item_id', 'meeting_project.project_id')->orderBy('project_view.description', 'asc')->distinct()->get() as $model)
                            <tr>
                              <td>
                                <input type="hidden" name="projectId[]" value="{{ $model->project_id }}" />
                                <input type="hidden" name="projectName[]" value="{{ $model->project_name }}" />
                                {{ $model->project_name }}
                              </td>
                              <td>
                                <textarea type="text" class="form-control" name ="projectDescription[]"> {{ $model->description }} </textarea>
                              </td>
                              <td>
                                <textarea type="text" class="form-control" name ="projectProblem[]">{{ $model->problem }}</textarea>
                              </td>
                              <td>
                                <textarea type="text" class="form-control" name ="projectSolution[]">{{ $model->solution }}</textarea>
                              </td>
                              <td class="text-center">
                                  <a href="#" class="icon icon-circle icon-sm m-b-0 btn-remove-project" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete Project"><i class="fa fa-remove"></i></a>
                              </td>
                            </tr>
                            @endforeach
                            @endif
                          </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <a href="{{ url($url) }}" class="btn btn-sm btn-warning">Back</a>
                        <a id="btn-save-meeting" class="btn btn-sm btn-primary">Save</a>
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
                                <label class="col-sm-12 control-label">Are you sure all the meeting content is correct ?</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">No</button>
                            <button type="submit" id="btn-submit-konfirmasi" type="button" class="btn btn-sm btn-primary">Yes</button>
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
    $('#btn-add-project').on('click', function(){
        var projectId = $('#projectId').val();
        var projectName = $('#projectId option:selected').text();

        var exist = false;
        $('#table-project > tbody  > tr').each(function() {
            var trProjectId = $(this).find('input[name="projectId[]"]').val();
            if (projectId == trProjectId) {
                exist = true;
            }
        });

        if (exist || projectId == '') {
            return;
        }

        $('#table-project tbody').append('<tr>\
          <td>\
            <input type="hidden" name="projectId[]" value="'+projectId+'" />\
            <input type="hidden" name="projectName[]" value="'+projectName+'" />\
            '+projectName+'\
          </td>\
          <td>\
            <textarea type="text" class="form-control" name ="projectDescription[]"></textarea>\
          </td>\
          <td>\
            <textarea type="text" class="form-control" name ="projectProblem[]"></textarea>\
          </td>\
          <td>\
            <textarea type="text" class="form-control" name ="projectSolution[]"></textarea>\
          </td>\
          <td class="text-center">\
              <a href="#" class="icon icon-circle icon-sm m-b-0 btn-remove-project" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete Project"><i class="fa fa-remove"></i></a>\
          </td>\
        </tr>');

        $('.btn-remove-project').on('click', deleteProject)
    })

    $('#btn-save-meeting').on('click', function() {
        $('#modal-konfirmasi').modal('show');
    })
    $('#btn-submit-konfirmasi').on('click', function(event) {
        event.preventDefault();
        var lastUpdatedDate = $('#lastUpdatedDate').val();
        var meetingId      = $('#meetingId').val();

        $.ajax({
            method: "POST",
            url: "{{ URL($url.'/check-last-updated') }}",
            data: {lastUpdatedDate: lastUpdatedDate, meetingId: meetingId, _token: "{{ csrf_token() }}"}
        }).done(function(result) {
            if(!result.check){
                alert(result.message);
            }else{
                $('#form-add-meeting').submit();
            }
        });
    })

    $('.btn-remove-project').on('click', deleteProject)
})

var deleteProject = function() {
    $(this).parent().parent().remove();    
}
</script>
@endsection
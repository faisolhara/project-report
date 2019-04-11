@extends('master')

@section('title', 'Access Control')
<?php 
use App\Service\AuthorizationService;
?>
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
            <h4 class="widget-title">Access Control</h4>
        </header>
        <hr class="widget-separator">
        <div class="widget-body">
            <form class="form-horizontal" method="POST" action="{{ url($url.'/save') }}">
              {{ csrf_field() }}
              <input type="hidden" name="username" value="{{ count($errors) > 0 ? old('username') : $user->vc_username }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Username</label>
                            <label class="col-sm-10 control-label">{{ $user->vc_username }}</label>
                            <input type="hidden" name="username" value="{{ $user->vc_username }}">
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Name</label>
                            <label class="col-sm-10 control-label">{{ $user->vc_emp_name }}</label>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        @foreach($resources as $module => $moduleResources)
                        <div class="panel-group accordion" id="accordion" role="tablist">
                          <div class="panel panel-default">
                              <div class="panel-heading header-heading" role="tab" id="heading-1">
                                  <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#{{ $module }}" aria-expanded="false" aria-controls="{{ $module }}">
                                      <h4 class="panel-title">{{ $module }}</h4>
                                      <i class="fa acc-switch"></i>
                                  </a>
                              </div>
                              <div id="{{ $module }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-1">
                                  <div class="panel-body">
                                      <div class="row">
                                          <div class="col-md-12">
                                              @foreach($moduleResources as $resource => $privileges)
                                              <div class="panel-group accordion" id="{{ $module }}" role="tablist">
                                                  <div class="panel panel-default">
                                                      <div class="panel-heading child-heading" role="tab" id="heading-2">
                                                          <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#{{ $module }}" href="#{{ str_replace(' ', '', str_replace('\\', '', $resource)) }}" aria-expanded="false" aria-controls="{{ str_replace(' ', '', str_replace('\\', '', $resource)) }}">
                                                              <h4 class="panel-title">{{ $resource }}</h4>
                                                              <i class="fa acc-switch"></i>
                                                          </a>
                                                      </div>
                                                      <div id="{{ str_replace(' ', '', str_replace('\\', '', $resource)) }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-2">
                                                          <div class="panel-body">
                                                              <div class="row">
                                                                <div class="col-md-12">
                                                                    @foreach($privileges as $privilege)
                                                                    <?php
                                                                        $access = !empty(old('privileges')) ? !empty(old('privileges')[$resource][$privilege]) : AuthorizationService::canAccess($user->vc_username, $resource, $privilege);
                                                                    ?>
                                                                    <div class="form-group">
                                                                      <div class="col-sm-12">
                                                                          <input type="checkbox" id="{{ str_replace(' ', '', str_replace('\\', '', $resource)).'-'.$privilege }}" name="privileges[{{ $resource }}][{{ $privilege }}]" value="1" {{ $access ? 'checked' : '' }}>
                                                                          <label for="{{ str_replace(' ', '', str_replace('\\', '', $resource)).'-'.$privilege }}">{{ ucfirst($privilege) }}</label>
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
                              </div>
                          </div>
                      </div>
                      @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <a href="{{ url($url) }}" class="btn btn-sm btn-warning">Back</a>
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
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

    $('.btn-remove-project').on('click', deleteProject)
})

var deleteProject = function() {
    $(this).parent().parent().remove();    
}
</script>
@endsection
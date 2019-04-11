@extends('master')

@section('title', 'Meeting')
<?php 
use App\Service\AuthorizationService;
?>

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="widget p-lg">
      <form class="form-horizontal" role="form" id="add-form" method="post" action="">
      {{ csrf_field() }}
        <div class="col-md-6 portlets">
          <div class="form-group">
            <label for="meetingName" class="col-sm-4 control-label">Meeting Name</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name ="meetingName" id="meetingName" value="{{ !empty($filters['meetingName']) ? $filters['meetingName'] : '' }}">
            </div>
          </div>
          <div class="form-group">
            <label for="description" class="col-sm-4 control-label">Description</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name ="description" id="description" value="{{ !empty($filters['description']) ? $filters['description'] : '' }}">
            </div>
          </div>
        </div>
        <div class="col-md-6 portlets">
          <div class="form-group">
            <label class="col-sm-4 control-label">Start Date</label>
              <div class="col-sm-6">
                <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ defaultDate: '{{ !empty($filters['startDate']) ? $filters['startDate'] : '' }}', format: 'DD-MM-YYYY' }" name ="startDate" >
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">End Date</label>
              <div class="col-sm-6">
                <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ format: 'DD-MM-YYYY' }" name ="endDate" value = "{{ !empty($filters['endDate']) ? $filters['endDate'] : '' }}">
              </div>
            </div>
        </div>
        <div class="col-sm-12 data-table-toolbar text-right">
          <div class="form-group">
            <button type="submit" class="btn btn-sm btn-success">Search</button>
            @if(AuthorizationService::check($resource, 'add'))
            <a href="{{ url($url.'/add') }}" class="btn btn-sm btn-primary">Add Meeting</a>
            @endif
          </div>
        </div>
        <table class="table table-striped table-bordered table-responsive table-hover">
          <tbody>
            <tr>
              <th width="200px">Meeting Name</th>
              <th width="100px">Meeting Date</th>
              <th>Description</th>
              <th width="75px">Action</th>
            </tr>
            @foreach($models as $model)
            <?php 
            $meetingDate = !empty($model->meeting_date) ? new \DateTime($model->meeting_date) : null;
            $date        = !empty($meetingDate) ? $meetingDate->format('d-m-Y H:i') : '';
            ?>
            <tr>
              <td>{{ $model->meeting_name }}</td>
              <td>{{ $date }}</td>
              <td>{!! nl2br($model->description) !!}</td>
              <td class="text-center">
                @if(AuthorizationService::check($resource, 'update'))
                <a href="{{ url($url.'/update/'.$model->meeting_id) }}" class="icon icon-circle icon-sm m-b-0" data-toggle="tooltip" title="" data-placement="top" data-original-title="Update Meeting"><i class="fa fa-edit"></i></a>
                @endif
                @if(AuthorizationService::check($resource, 'delete'))
                <a data-id="{{ $model->meeting_id }}" data-label="{{ $model->meeting_name }} ({{ $date }})" href="#" class="icon icon-circle icon-sm m-b-0 btn-delete" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete Meeting"><i class="fa fa-remove"></i></a>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="data-table-toolbar">
            {!! $models->render() !!}
        </div>

      </form>
    </div><!-- .widget -->
  </div><!-- END column -->
</div><!-- .row -->

<!-- Modal Konfirmasi -->
<div class="modal fade" id="modal-konfirmasi" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Confirmation</h4>
        </div>
        <form class="form-horizontal"  method="post" action="{{ url($url.'/delete') }}">
          {{ csrf_field() }}
          <div class="modal-body">
              <div class="form-group">
                  <label class="col-sm-12 control-label">Are you sure want to delete <span id="label-meeting"></span> ?</label>
              </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" name="meetingId" id="meeting-id">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">No</button>
              <button type="submit" type="button" class="btn btn-sm btn-primary">Yes</button>
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
        $('.btn-delete').on('click', function() {
            $("#meeting-id").val($(this).data('id'));
            $("#label-meeting").html($(this).data('label'));
            $('#modal-konfirmasi').modal('show');
        })
    })
</script>
@endsection

@extends('master')

@section('title', 'Validation Progress')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="widget p-lg">
      <form class="form-horizontal" role="form" id="add-form" method="post" action="">
      {{ csrf_field() }}
        <div class="col-md-6 portlets">
          <div class="form-group">
            <label for="projectCode" class="col-sm-4 control-label">Item Code</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name ="projectCode" id="projectCode" value="{{ !empty($filters['projectCode']) ? $filters['projectCode'] : '' }}">
            </div>
          </div>
          <div class="form-group">
            <label for="description" class="col-sm-4 control-label">Project Name</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name ="description" id="description" value="{{ !empty($filters['description']) ? $filters['description'] : '' }}">
            </div>
          </div>
          <div class="form-group">
            <label for="type" class="col-sm-4 control-label">Type</label>
            <div class="col-sm-8">
              <select id="type" name="type" class="form-control">
                  <option value="">ALL</option>
                  @foreach($typeOption as $type => $value)
                      <option value="{{ $value }}" {{ !empty($filters['type']) && $filters['type'] == $value ? 'selected' : '' }}>{{ $type }}</option>
                  @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="col-md-6 portlets">
          <div class="form-group">
            <label for="type" class="col-sm-4 control-label">Section</label>
            <div class="col-sm-8">
              <select id="type" name="section" class="form-control">
                  <option value="">ALL</option>
                  @foreach($sectionOption as $section)
                      <option value="{{ $section }}" {{ !empty($filters['section']) && $filters['section'] == $section ? 'selected' : '' }}>{{ $section }}</option>
                  @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
              <label class="col-sm-4 control-label">Start Date Progress</label>
              <div class="col-sm-8">
                  <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ format: 'DD-MM-YYYY' }" name ="startDate" value="{{ !empty($filters['startDate']) ? $filters['startDate'] : '' }}">
              </div>
          </div>
          <div class="form-group">
              <label class="col-sm-4 control-label">End Date Progress</label>
              <div class="col-sm-8">
                  <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ format: 'DD-MM-YYYY' }" name ="endDate" value = "{{ !empty($filters['endDate']) ? $filters['endDate'] : '' }}">
              </div>
          </div>
        </div>
        <div class="col-sm-12 data-table-toolbar text-right">
          <div class="form-group">
            <button type="submit" class="btn btn-sm btn-success">Search</button>
          </div>
        </div>
        <table class="table table-striped table-bordered table-responsive table-hover">
          <tbody>
            <tr>
              <th>Item Code</th>
              <th>Project Name</th>
              <th>Section</th>
              <th>Created Date<hr>Created By</th>
              <th>Last Updated Date<hr>Last Updated By</th>
              <th width="50px">Action</th>
            </tr>
            @foreach($models as $model)
            <?php 
            $createdDate      = !empty($model->created_date) ? new \DateTime($model->created_date) : null;
            $lastUpdatedDate  = !empty($model->last_updated_date) ? new \DateTime($model->last_updated_date) : null;
            ?>
            <tr>
              <td>{{ $model->segment1.'.'.$model->segment2.'.'.$model->segment3 }}</td>
              <td>{{ $model->project_name }}</td>
              <td>{{ $model->section }}</td>
              <td>{{ !empty($createdDate) ? $createdDate->format('d-m-Y H:i') : '' }} <hr>{{ $model->created_by }}</td>
              <td>{{ !empty($lastUpdatedDate) ? $lastUpdatedDate->format('d-m-Y H:i') : '' }} <hr>{{ $model->last_updated_by }}</td>
              <td class="text-center">
                <a href="{{ url($url.'/update/'.$model->project_progress_id) }}" class="icon icon-circle icon-sm m-b-0" data-toggle="tooltip" title="" data-placement="top" data-original-title="Validate Progress"><i class="fa fa-check"></i></a>
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

@endsection

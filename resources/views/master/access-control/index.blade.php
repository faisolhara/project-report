@extends('master')

@section('title', 'Access Control')
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
            <label for="username" class="col-sm-4 control-label">Username</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name ="username" id="username" value="{{ !empty($filters['username']) ? $filters['username'] : '' }}">
            </div>
          </div>
          <div class="form-group">
            <label for="name" class="col-sm-4 control-label">Name</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name ="name" id="name" value="{{ !empty($filters['name']) ? $filters['name'] : '' }}">
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
              <th>Username</th>
              <th>Name</th>
              <th>Action</th>
            </tr>
            @foreach($models as $model)
            <tr>
              <td>{{ $model->vc_username }}</td>
              <td>{{ $model->vc_emp_name }}</td>
              <td class="text-center">
                @if(AuthorizationService::check($resource, 'update'))
                <a href="{{ url($url.'/update/'.$model->vc_username) }}" class="icon icon-circle icon-sm m-b-0" data-toggle="tooltip" title="" data-placement="top" data-original-title="Update Access Control"><i class="fa fa-edit"></i></a>
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

@endsection

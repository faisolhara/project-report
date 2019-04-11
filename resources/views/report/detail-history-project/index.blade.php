@extends('master')

@section('title', 'Detail History Project')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="widget">
        <header class="widget-header">
            <h4 class="widget-title">Detail History Project</h4>
        </header>
        <hr class="widget-separator">
        <div class="widget-body">
            <form class="form-horizontal" method="POST" action="">
            {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="select2-demo-1" class="col-sm-4 control-label">Project Name</label>
                            <div class="col-sm-8">
                                <select id="select2-demo-1" class="form-control" data-plugin="select2" name ="projectId" id="projectId">
                                    <option value="">Please Select</option>
                                    @foreach($projectOption as $project)
                                    <option value="{{ $project->inventory_item_id }}" {{ !empty($filters['projectId']) && $filters['projectId'] == $project->inventory_item_id ? 'selected' : '' }}>{{ $project->description }}</option>
                                    @endforeach
                                </select>
                            </div><!-- END column -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label for="select2-demo-1" class="col-sm-4 control-label">Section</label>
                            <div class="col-sm-8">
                                <select id="select2-demo-1" class="form-control" data-plugin="select2" name ="sectionDiv" id="sectionDiv">
                                    <option value="">Please Select</option>
                                    @foreach($sectionOption as $section)
                                    <option value="{{ $section }}" {{ !empty($filters['sectionDiv']) && $filters['sectionDiv'] == $section ? 'selected' : '' }}>{{ $section }}</option>
                                    @endforeach
                                </select>
                            </div><!-- END column -->
                        </div><!-- .form-group -->
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Start Date</label>
                            <div class="col-sm-6">
                                <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ format: 'DD-MM-YYYY' }" name ="startDate" value="{{ !empty($filters['startDate']) ? $filters['startDate'] : '' }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">End Date</label>
                            <div class="col-sm-6">
                                <input type="text" id="datetimepicker5" class="form-control" data-plugin="datetimepicker" data-options="{ format: 'DD-MM-YYYY' }" name ="endDate" value = "{{ !empty($filters['endDate']) ? $filters['endDate'] : '' }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">Search</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="widget-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="m-b-lg nav-tabs-horizontal">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#tab-1" aria-controls="tab-1" role="tab" data-toggle="tab" aria-expanded="false">Detail Progress</a></li>
                            <li role="presentation" class=""><a href="#tab-2" aria-controls="tab-2" role="tab" data-toggle="tab" aria-expanded="true">Meeting</a></li>
                        </ul>
                        <div class="tab-content p-md">
                            <div role="tabpanel" class="tab-pane fade active in" id="tab-1">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-responsive table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                @foreach($columns as $column)
                                                <th>{{ $column }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data as $id => $progress)
                                            <?php $count = 1; ?>
                                            <tr>
                                                @foreach($progress as $index => $value)
                                                <?php  
                                                $color     = "text-color";
                                                if($count == 1 || $count == 2){
                                                    $color = "success text-color";
                                                }
                                                $count++;
                                                ?>
                                                <td class="{{ $color }}" style="vertical-align: top; min-width: {{ $index === 0 ? 100 : 300 }}px;">{!! nl2br($value) !!}</td>
                                                @endforeach                            
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <thead>
                                            <tr>
                                                @if(!empty($data))
                                                <th>Date</th>
                                                @foreach($columns as $column)
                                                <th>{{ $column }}</th>
                                                @endforeach
                                                @endif
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab-2">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-responsive table-hover">
                                        <thead>
                                            <tr>
                                                <th width="120px">Meeting Date <hr> Meeting Name</th>
                                                <th>Meeting Description</th>
                                                <th>Description</th>
                                                <th>Problem</th>
                                                <th>Solution</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($meeting as $meetingProject)
                                            <?php 
                                            $date = !empty($meetingProject->meeting_date) ? new \DateTime($meetingProject->meeting_date) : null;
                                            ?>
                                            <tr>
                                                <td>{{ !empty($date) ? $date->format('d-m-Y H:i') : '' }}<hr> {{ $meetingProject->meeting_name }}</td>
                                                <td>{!! nl2br($meetingProject->meeting_description) !!}</td>
                                                <td>{!! nl2br($meetingProject->description) !!}</td>
                                                <td>{!! nl2br($meetingProject->problem) !!}</td>
                                                <td>{!! nl2br($meetingProject->solution) !!}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection
@extends('master')

@section('title', 'Project')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="widget">
        <header class="widget-header">
            <h4 class="widget-title">Project Progress History ({{ $section }})</h4>
        </header>
        <hr class="widget-separator">
        <div class="widget-body">
            <form class="form-horizontal">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Item Code</label>
                            <label class="col-sm-8 control-label">{{ $projectCode }}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Project Name</label>
                            <label class="col-sm-8 control-label">{{ $projectName }}</label>
                        </div>
                    </div>
                    @if($type != 'Project')
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Milestone</label>
                            <label class="col-sm-8 control-label">{{ $milestoneName }}</label>
                        </div>
                        @if($type == 'Task')
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Task</label>
                            <label class="col-sm-8 control-label">{{ $taskName }}</label>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr/>
                        <div data-plugin="chart" data-options='{{ json_encode($data) }}' style="height: 300px;">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr/>
                        <table class="table table-striped table-bordered table-responsive table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Progress Date<hr/>Progress</th>
                                    <th>Start Date<hr/>End Date</th>
                                    <th>Description</th>
                                    <th>Problem</th>
                                    <th>Solution</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modelProgress as $key => $progress)
                                <?php  
                                $createdDate = !empty($progress->created_date) ? new \DateTime($progress->created_date) : null;
                                $startDate   = !empty($progress->start_date) ? new \DateTime($progress->start_date) : null;
                                $endDate     = !empty($progress->end_date) ? new \DateTime($progress->end_date) : null;
                                ?>
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ !empty($createdDate) ? $createdDate->format('d-m-Y H:i') : '' }}<hr/>{{ $progress->progress_percentage }}%</td>
                                    <td>{{ !empty($startDate) ? $startDate->format('d-m-Y') : '' }}<hr/>{{ !empty($endDate) ? $endDate->format('d-m-Y') : '' }}</td>
                                    <td>{{ $progress->description }}</td>
                                    <td>{{ $progress->problem }}</td>
                                    <td>{{ $progress->solution }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <a href="{{ url($url.'/detail/'.$projectId) }}" class="btn btn-sm btn-warning">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
  </div>
</div>
@endsection
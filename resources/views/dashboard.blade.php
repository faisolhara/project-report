@extends('master')

@section('title', 'Dashboard')

<?php 
use App\Http\Controllers\Transaction\ProjectController;
?>

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
        </div>
        <div class="col-md-6 portlets">
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
        <div class="col-sm-12 data-table-toolbar text-right">
          <div class="form-group">
            <button type="submit" class="btn btn-sm btn-success">Search</button>
          </div>
        </div>
        <table class="table table-striped table-bordered table-responsive table-hover">
          <thead>
            <tr>
              <th rowspan="2">Item Code</th>
              <th rowspan="2">Project Name</th>
              <th colspan="2">Technical</th>
              <th colspan="2">Marketing</th>
            </tr>
            <tr>
              <th>Start Date<hr>End Date</th>
              <th>Progress Date<hr>Percentage</th>
              <th>Start Date<hr>End Date</th>
              <th>Progress Date<hr>Percentage</th>
            </tr>
          </thead>
          <tbody>
            @foreach($models as $model)
            <?php 
            $now = new \DateTime();
            $progressTechnical = \DB::table('iapsys.project_progress')->where('project_id', '=', $model->inventory_item_id)->where('section', '=', ProjectController::TECHNICAL)->whereNotNull('validated_date')->orderBy('created_date', 'desc')->first();
            $startDateTechnical     = !empty($progressTechnical->start_date) ? new \DateTime($progressTechnical->start_date) : null;
            $endDateTechnical       = !empty($progressTechnical->end_date) ? new \DateTime($progressTechnical->end_date) : null;
            $createdDateTechnical   = !empty($progressTechnical->created_date) ? new \DateTime($progressTechnical->created_date) : null;
            $percentageTechnical    = !empty($progressTechnical) ? intval($progressTechnical->progress_percentage) : 0;

            if ($createdDateTechnical !== null) {
              $createdDateTechnical->setTime(0, 0, 0);
            }

            $progressMarketing = \DB::table('iapsys.project_progress')->where('project_id', '=', $model->inventory_item_id)->where('section', '=', ProjectController::MARKETING)->whereNotNull('validated_date')->orderBy('created_date', 'desc')->first();
            $startDateMarketing     = !empty($progressMarketing->start_date) ? new \DateTime($progressMarketing->start_date) : null;
            $endDateMarketing       = !empty($progressMarketing->end_date) ? new \DateTime($progressMarketing->end_date) : null;
            $createdDateMarketing   = !empty($progressMarketing->created_date) ? new \DateTime($progressMarketing->created_date) : null;
            $percentageMarketing    = !empty($progressMarketing) ? intval($progressMarketing->progress_percentage) : 0;

            if ($createdDateMarketing !== null) {
              $createdDateMarketing->setTime(0, 0, 0);
            }

            $classTechnical = 'success text-color';
            $classMarketing = 'success text-color';

            if(empty($createdDateTechnical)) {
                $classTechnical = 'text-color';
            }elseif(empty($endDateTechnical)) {
                $classTechnical = 'danger text-color';
            }elseif($percentageTechnical >= 100 && $createdDateTechnical > $endDateTechnical) {
                $classTechnical = 'danger text-color';
            }elseif($percentageTechnical < 100 && $now > $endDateTechnical){
                $classTechnical = 'danger text-color';
            }

            if(empty($createdDateMarketing)) {
                $classMarketing = 'text-color';
            }elseif(empty($endDateMarketing)) {
                $classMarketing = 'danger text-color';
            }elseif($percentageMarketing >= 100 && $createdDateMarketing > $endDateMarketing) {
                $classMarketing = 'danger text-color';
            }elseif($percentageMarketing < 100 && $now > $endDateMarketing){
                $classMarketing = 'danger text-color';
            }
            ?>
            <tr>
              <td>{{ $model->segment1.'.'.$model->segment2.'.'.$model->segment3 }}</td>
              <td>{{ $model->description }}</td>
              <td class="{{ $classTechnical }}">{{ !empty($startDateTechnical) ? $startDateTechnical->format('d-m-Y') : ''  }}<hr>
                  {{ !empty($endDateTechnical) ? $endDateTechnical->format('d-m-Y') : ''  }}</td>
              <td class="{{ $classTechnical }}">{{ !empty($createdDateTechnical) ? $createdDateTechnical->format('d-m-Y') : ''  }}<hr>
                  {{ $percentageTechnical }} %</td>
              <td class="{{ $classMarketing }}">{{ !empty($startDateMarketing) ? $startDateMarketing->format('d-m-Y') : ''  }}<hr>
                  {{ !empty($endDateMarketing) ? $endDateMarketing->format('d-m-Y') : ''  }}</td>
              <td class="{{ $classMarketing }}">{{ !empty($createdDateMarketing) ? $createdDateMarketing->format('d-m-Y') : ''  }}<hr>
                  {{ $percentageMarketing }} %</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="data-table-toolbar">
            {!! $models->render() !!}
        </div>
        <h4 class="m-b-lg">Project Rule</h4>
        <table class="table table-striped table-bordered table-responsive table-hover">
          <tbody>
            <tr style="font-weight: bold;">
              <td widtd="33%" class="text-color">Iddle</td>
              <td widtd="33%" class="success text-color">Success</td>
              <td widtd="33%" class="danger text-color">Danger</td>
            </tr>
          </tbody>
          <tbody>
            <tr>
              <td class="text-color">Empty Progress</td>
              <td class="success text-color">Progress = 100% and end date >= progress date</td>
              <td class="danger text-color">Progress = 100% and end date < progress date</td>
            </tr>
            <tr>
              <td class="text-color"></td>
              <td class="success text-color">Progress < 100% and end date >= now </td>
              <td class="danger text-color">Progress < 100% and end date < now</td>
            </tr>
            <tr>
              <td class="text-color"></td>
              <td class="success text-color"></td>
              <td class="danger text-color"> There is progress but empty end date project</td>
            </tr>
          </tbody>
        </table>
      </form>
      
    </div><!-- .widget -->
  </div><!-- END column -->
</div><!-- .row -->

@endsection

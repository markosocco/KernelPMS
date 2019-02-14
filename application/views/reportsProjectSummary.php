<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Report - Project Summary</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>dist/css/AdminLTE.min.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- Own Style -->
  <!-- <link rel="stylesheet" href="<?php echo base_url("/assets/css/reportsProjectPerDeptStyle.css")?>"> -->
  <!-- Report Style -->
  <link rel="stylesheet" href="<?php echo base_url("/assets/css/reportStyle.css")?>">
</head>
<body id="printArea" style="font-size: 11px">
<div class="wrapper">
  <!-- Main content -->
  <section>
    <!-- title row -->
    <div class="reportHeader viewCenter">
      <h3 class="viewCenter"><img class="" id = "logo" src = "<?php echo base_url("/assets/media/tei.png")?>"> Project Summary Report</h3>
    </div>
    <div class="reportBody">
      <!-- LOOP START HERE -->
        <div class="box box-danger">
          <table class="table-condensed" style="width:100%">
            <tr>
              <td><b>Title: </b><?php echo $project['PROJECTTITLE']; ?></td>
              <?php // to fix date format
                $projectStart = date_create($project['PROJECTSTARTDATE']);
                $projectEnd = date_create($project['PROJECTENDDATE']);
                if($project['PROJECTSTATUS'] == 'Complete')
                  $projectActualEnd = date_format(date_create($project['PROJECTACTUALENDDATE']), "F d, Y");
                else
                  $projectActualEnd = "Present";
              ?>
              <td align="right"><b>Initial Duration: </b><?php echo date_format($projectStart, "F d, Y") . " - " . date_format($projectEnd, "F d, Y");?></td>
            </tr>
            <tr>
              <td><b>Owner: </b>

                <?php foreach ($users as $user): ?>
                  <?php if ($user['USERID'] == $project['users_USERID']): ?>
                    <?php echo $user['FIRSTNAME'] . " " . $user['LASTNAME']; ?>
                  <?php endif; ?>
                <?php endforeach; ?>

              </td>
              <td align="right"><b>Actual Duration: </b><?php echo date_format($projectStart, "F d, Y") . " - " . $projectActualEnd;?></td>
            </tr>
            <tr>
              <td><b>Description: </b><?php echo $project['PROJECTDESCRIPTION']; ?></td>
              <td></td>
            </tr>
          </table>

          <div class="viewCenter">
						<p style="display: inline-block">Legend:</p>
						<div style="width: 20px; height: 10px; background-color:#d2d6de; display:inline-block; margin-left:10px;"></div> Completeness
						<div style="width: 20px; height: 10px; background-color:#18A55D; display:inline-block; margin-left:10px;"></div> Timeliness
					</div>

          <!-- BAR CHART -->
          <div class="box box-default">
            <div class="box-body">
              <div class="chart">
                <canvas id="barChart" style="height:180px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- TEAM MEMBERS -->
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Team Members</h5>
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body">
  								<table class="table table-bordered table-condensed" id="">
  									<thead>
  										<tr>
  											<th>Name</th>
                        <th>Position</th>
  											<th>Department</th>
  											<th class='text-center' width="10%">Total Tasks</th>
                        <th class='text-center' width="10%">Delayed Tasks</th>
  											<th class='text-center' width="10%">Timeliness</th>
  										</tr>
  									</thead>
  									<tbody>
                      <?php foreach($team as $member):?>

  											<?php $numTasks = 0;?>
  											<?php $timeliness = 0;?>
                        <?php $delayedTaskCounter = 0;?>

  											<?php foreach($taskCount as $count)
  												if($count['USERID'] == $member['USERID'])
  													$numTasks = $count['taskCount'];
  											?>

  											<?php foreach($employeeTimeliness as $empTimeliness)
  												if($empTimeliness['USERID'] == $member['USERID'])
  													$timeliness = $empTimeliness['timeliness'];
  											?>
  											<?php if($timeliness == 100.00)
  											 	$timeliness = 100;
  											?>

                        <?php foreach($delayedTaskCount as $delayedCount)
  												if($delayedCount['USERID'] == $member['USERID'])
  													$delayedTaskCounter = $delayedCount['DELAYEDCOUNT'];
  											?>

  											<tr>
  												<td><?php echo $member['FIRSTNAME'];?> <?php echo $member['LASTNAME'];?></td>
                          <td><?php echo $member['POSITION'];?></td>
  												<td><?php echo $member['DEPARTMENTNAME'];?></td>
  												<td class='text-center'><?php echo $numTasks;?></td>
                          <td class='text-center'><?php echo $delayedTaskCounter;?></td>
  												<td class='text-center'><?php echo $timeliness;?>%</td>
  											</tr>
  										<?php endforeach;?>
  									</tbody>
  								</table>
  							</div>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>

  				<!-- DELAYED TASKS -->
  				<?php if($delayedTasks != null):?>
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Delayed Tasks</h5>
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body" id="delayedBox">
  								<table class="table table-bordered table-condensed" id="delayedTable">
  									<thead>
  										<tr>
  											<th width="20%">Task</th>
  											<th width="10%" class='text-center'>Target<br>End Date</th>
  											<th width="10%" class='text-center'>Actual<br>End Date</th>
  											<th width="5%" class='text-center'>Days Delayed</th>
                        <th width="15%">Responsible</th>
                        <th width="15%">Department</th>
  											<th width="25">Reason</th>
  										</tr>
  									</thead>
  									<tbody id="delayedData">
  										<?php foreach ($delayedTasks as $task):?>
  											<?php
  											if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
  											{
  												$endDate = $task['TASKENDDATE'];
  												$delay = $task['actualInitial'];
  											}
  											else
  											{
  												$endDate = $task['TASKADJUSTEDENDDATE'];
  												$delay = $task['actualAdjusted'];
  											}?>

  											<?php if($task['TASKACTUALENDDATE'] > $endDate):?>
  											<tr>
  												<td><?php echo $task['TASKTITLE'];?></td>
  												<td class='text-center'><?php echo date_format(date_create($endDate), "M d, Y");?></td>
  												<td class='text-center'><?php echo date_format(date_create($task['TASKACTUALENDDATE']), "M d, Y");?></td>
  												<td align="center"><?php echo $delay;?></td>
                          <td><?php echo $task['FIRSTNAME'];?> <?php echo $task['LASTNAME'];?></td>
                          <td><?php echo $task['DEPARTMENTNAME'];?></td>
  												<td><?php echo $task['TASKREMARKS'];?></td>
  											</tr>
  										<?php endif;?>
  										<?php endforeach;?>
  									</tbody>
  								</table>
  							</div>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>
  			<?php else:?>
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Delayed Tasks</h5>
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body">
  								<h6 align="center">There were no delayed tasks</h6>
  							</div>
  						</div>
  					</div>
  					<!-- /.col -->
  				</div>
  			<?php endif;?>

  				<!-- EARLY TASKS -->
  				<?php if($earlyTasks != null):?>
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Early Tasks</h5>
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body">
  								<table class="table table-bordered table-condensed" id="">
  									<thead>
  										<tr>
  											<th width="20%">Task</th>
  											<th width="10%" class='text-center'>Target<br>End Date</th>
  											<th width="10%" class='text-center'>Actual<br>End Date</th>
  											<th width="5%" class='text-center'>Days Early</th>
                        <th width="15%">Responsible</th>
                        <th width="15%">Department</th>
  											<th width="25">Reason</th>
  										</tr>
  									</thead>
  									<tbody>
  										<?php foreach ($earlyTasks as $task):?>
  											<?php
  											if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
  											{
  												$endDate = $task['TASKENDDATE'];
  												$early = $task['actualInitial'];
  											}
  											else
  											{
  												$endDate = $task['TASKADJUSTEDENDDATE'];
  												$early = $task['actualAdjusted'];
  											}
  											?>

  											<?php if($task['TASKACTUALENDDATE'] < $endDate):?>
  												<tr>
  													<td><?php echo $task['TASKTITLE'];?></td>
  													<td class='text-center'><?php echo date_format(date_create($endDate), "M d, Y");?></td>
  													<td class='text-center'><?php echo date_format(date_create($task['TASKACTUALENDDATE']), "M d, Y");?></td>
  													<td align="center"><?php echo $early;?></td>
                            <td><?php echo $task['FIRSTNAME'];?> <?php echo $task['LASTNAME'];?></td>
                            <td><?php echo $task['DEPARTMENTNAME'];?></td>
  													<td><?php echo $task['TASKREMARKS'];?></td>
  												</tr>
  											<?php endif;?>
  										<?php endforeach;?>
  									</tbody>
  								</table>
  							</div>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>
  				<?php else:?>
  					<div class="row">
  						<div class="col-md-12 col-sm-12 col-xs-12">
  							<div class="box box-default">
  								<div class="box-header with-border">
  									<h5 class="box-title">Early Tasks</h5>
  								</div>
  								<!-- /.box-header -->
  								<div class="box-body">
  									<h6 align="center">There were no early tasks</h6>
  								</div>
  							</div>
  						</div>
  						<!-- /.col -->
  					</div>
  				<?php endif;?>

  				<!-- Requests -->
  				<?php if($changeRequests != null):?>
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Change Requests</h5>
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body">
  								<table class="table table-bordered table-condensed" id="">
  									<thead>
  										<tr>
  											<th width="0%">Task</th>
                        <th width="0%">Type</th>
                        <th width="0%" class='text-center'>Date Requested</th>
                        <th width="0">Reason</th>
  											<th width="0%">Requested By</th>
  											<th width="0%">Department</th>
  											<th width="0%" class='text-center'>Status</th>
  											<th width="0%">Reviewed By</th>
  											<th width="0%" class='text-center'>Date Reviewed</th>
  											<th width="0">Remarks</th>
  										</tr>
  									</thead>
  									<tbody>
  										<?php foreach($changeRequests as $request):?>
  											<tr>
  												<?php if($request['REQUESTTYPE'] == '1')
  														$type = "Change Performer";
  													else
  														$type = "Change Date";
  													?>

  												<?php foreach($users as $user)
  													if($user['USERID'] == $request['users_REQUESTEDBY'])
  													{
  														$requester = $user['FIRSTNAME'] . " " . $user['LASTNAME'];
  														foreach($allDepartments as $dept)
  														{
  															if($user['departments_DEPARTMENTID'] == $dept['DEPARTMENTID'])
  															$deptName = $dept['DEPARTMENTNAME'];
  														}
  													}
  													else if($user['USERID'] == $request['users_APPROVEDBY'])
  													{
  														$approver = $user['FIRSTNAME'] . " " . $user['LASTNAME'];
  													}
  												?>

  												<?php
  												$requestdate = date_create($request['REQUESTEDDATE']);
  												$approveddate = date_create($request['APPROVEDDATE']);
  												?>

  												<td><?php echo $request['TASKTITLE'];?></td>
                          <td><?php echo $type;?></td>
                          <td class='text-center'><?php echo date_format($requestdate, "M d, Y");?></td>
                          <td><?php echo $request['REASON'];?></td>
                        	<td><?php echo $requester;?></td>
  												<td><?php echo $deptName;?></td>
  												<td class='text-center'><?php echo $request['REQUESTSTATUS'];?></td>
  												<?php if($request['REQUESTSTATUS'] == 'Pending'):?>
  													<td align='center'>-</td>
  												<?php else:?>
  													<td><?php echo $approver;?></td>
  												<?php endif;?>
  												<td class='text-center'><?php echo date_format($approveddate, "M d, Y");?></td>
  												<?php if($request['REMARKS'] == ""):?>
  												<td align="center">-</td>
  												<?php else:?>
  												<td><?php echo $request['REMARKS'];?></td>
  												<?php endif;?>
  											</tr>
  										<?php endforeach;?>
  									</tbody>
  								</table>
  							</div>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>
  			<?php else:?>
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Change Requests</h5>
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body">
  								<h6 align="center">There were no change requests</h6>
  							</div>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>
  			<?php endif;?>
        </div>

    <div class="endReport viewCenter">
      <p>***END OF REPORT***</p>
    </div>

    <footer class="reportFooter">
      <!-- To the right -->
      <div class="pull-right hidden-xs">
        <!-- <medium>Page 1 of 1M</medium> -->
      </div>
      <!-- Default to the left -->
      <medium>Prepared By: <?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']?></medium>
      <br>
      <medium>Prepared On: <?php echo date('F d, Y'); ?></medium>
    </footer>
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/chart.js/Chart.js"></script>
<script>
  $(function ()
  {
    var barChartData = {
      // LOOP THROUGH DEPTS
      labels  : [

        <?php $index = 0;?>
        <?php foreach($allDepartments as $dept):?>
          '<?php echo $dept['DEPT'];?>'
          <?php $index++;?>
          <?php if(count($allDepartments) > $index):?>
            ,
          <?php else:?>
            ],
          <?php endif;?>
        <?php endforeach;?>

      datasets: [
        {
          label               : 'Completeness',
          fillColor           : 'rgba(210, 214, 222, 1)',
          strokeColor         : 'rgba(210, 214, 222, 1)',
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [
            <?php $index = 0;?>
            <?php foreach($departmentCompleteness as $deptCompleteness):?>
              <?php echo $deptCompleteness['completeness'];?>
              <?php $index++;?>
              <?php if(count($departmentCompleteness) > $index):?>
                ,
              <?php else:?>
                ]
              <?php endif;?>
            <?php endforeach;?>

        },
        {
          label               : 'Timeliness',
          fillColor           : 'rgba(60,141,188,0.9)',
          strokeColor         : 'rgba(60,141,188,0.8)',
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [
            <?php $index = 0;?>
            <?php foreach($departmentTimeliness as $deptTimeliness):?>
              <?php echo $deptTimeliness['timeliness'];?>
              <?php $index++;?>
              <?php if(count($departmentTimeliness) > $index):?>
                ,
              <?php else:?>
                ]
              <?php endif;?>
            <?php endforeach;?>
        }
      ]
    }

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
    var barChart                         = new Chart(barChartCanvas)
    var barChartData                     = barChartData
    barChartData.datasets[1].fillColor   = '#00a65a'
    barChartData.datasets[1].strokeColor = '#00a65a'
    barChartData.datasets[1].pointColor  = '#00a65a'
    var barChartOptions                  = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero        : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : true,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - If there is a stroke on each bar
      barShowStroke           : true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth          : 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing         : 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing       : 1,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to make the chart responsive
      responsive              : true,
      maintainAspectRatio     : true,
      animation               : false,

      legend:
      {
        display: true,
        position: 'bottom',
        fullWidth: true
      },

      onAnimationComplete: function ()
      {
        var ctx = this.chart.ctx;
        ctx.font = this.scale.font;
        ctx.fillStyle = this.scale.textColor
        ctx.textAlign = "center";
        ctx.textBaseline = "bottom";

        this.datasets.forEach(function (dataset) {
            dataset.bars.forEach(function (bar) {
                ctx.fillText(bar.value, bar.x, bar.y - 0);
            });
        })
      }
    }

    barChartOptions.datasetFill = false
    barChart.Bar(barChartData, barChartOptions)
  })

  $.ajax({
    success:function()
    {

    },
    complete:function()
    {
      window.print();
    }
  });
</script>
</body>
</html>

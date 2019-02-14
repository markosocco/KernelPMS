<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Report - Project Performance</title>
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
  <!-- Frame Style -->
  <link rel="stylesheet" href="<?php echo base_url("/assets/css/frameStyle.css")?>">
  <!-- Report Style -->
  <link rel="stylesheet" href="<?php echo base_url("/assets/css/reportStyle.css")?>">
</head>
<body id="printArea" style="font-size: 11px">
<div class="wrapper">
  <!-- Main content -->
  <section>
    <!-- title row -->
    <div class="reportHeader viewCenter">
      <h3 class="viewCenter"><img class="" id = "logo" src = "<?php echo base_url("/assets/media/tei.png")?>"> Project Performance Report</h3>
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
              <td align="right"><b>Duration: </b><?php echo date_format($projectStart, "F d, Y") . " - " . date_format($projectEnd, "F d, Y");?></td>
            </tr>
            <tr>
              <td><b>Description: </b><?php echo $project['PROJECTDESCRIPTION']; ?></td>
              <td align="right"><b>Owner: </b>

                <?php foreach ($users as $user): ?>
                  <?php if ($user['USERID'] == $project['users_USERID']): ?>
                    <?php echo $user['FIRSTNAME'] . " " . $user['LASTNAME']; ?>
                  <?php endif; ?>
                <?php endforeach; ?>

              </td>
            </tr>
          </table>

          <!-- BAR CHART -->
          <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-8">
              <div class="box box-default">
                <div class="box-body">
                  <div class="viewCenter">
        						<p style="display: inline-block">Legend:</p>
        						<div style="width: 20px; height: 10px; background-color:#d2d6de; display:inline-block; margin-left:10px;"></div> Completeness
        						<div style="width: 20px; height: 10px; background-color:#18A55D; display:inline-block; margin-left:10px;"></div> Timeliness
        					</div>
                  <div class="chart">
                    <canvas id="barChart" style="height:210px"></canvas>
                  </div>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
							<div class="box box-default" style="height:230px; vertical-align:middle">
								<!-- <div class="box-header with-border">
									<h3 class="box-title">Project Performance</h3>
								</div> -->
								<!-- /.box-header -->
								<div class="box-body" style="height:100%">
	                <div style="display:inline-block; text-align:center; width:49%; height: 100%;">
	                  <div>
                      <br>
                      <br>
                      <h3><?php echo $projectCompleteness['completeness']; ?>%</h3>
                      <h5>Completeness</h5>
	                  </div>
	                </div>
	                <div style="display:inline-block; text-align:center; width:49%; height: 100%; border-left: solid lightgray 1px;">
	                  <div>
                      <br>
                      <br>
                      <h3><?php echo $projectTimeliness['timeliness']; ?>%</h3>
                      <h5>Timeliness</h5>
	                 </div>
	               </div>
	              </div>
							</div>
		        </div>
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
    											<th width='20%'>Task</th>
    											<th class='text-center'>End Date</th>
    											<th class='text-center'>Actual<br>End Date</th>
    											<th class='text-center'>Days<br>Delayed</th>
                          <th class="text-center">R</th>
                          <th class="text-center">A</th>
                          <th class="text-center">C</th>
                          <th class="text-center">I</th>
                          <th class='text-center'>Department</th>
    											<th width="10%">Reason</th>
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
    											}
                          foreach($users as $user)
                          {

                          }
                          ?>
  											<tr>
                          <td><?php echo $task['TASKTITLE'];?></td>
                          <td class='text-center'><?php echo date_format(date_create($endDate), "M d, Y");?></td>
  												<td class='text-center'><?php echo date_format(date_create($task['TASKACTUALENDDATE']), "M d, Y");?></td>
                          <td class='text-center'><?php echo $delay?></td>
                          <td>
      											<?php foreach ($raci as $raciRow): ?>
      												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
      													<?php if ($raciRow['ROLE'] == '1'): ?>
      														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
                                  <?php foreach($users as $user):?>
                                    <?php if ($raciRow['users_USERID'] == $user['USERID']): ?>
                                      <?php $deptID = $user['departments_DEPARTMENTID'];?>
                                    <?php endif;?>
                                  <?php endforeach;?>
      													<?php endif; ?>
      												<?php endif; ?>
      											<?php endforeach; ?>
      										</td>
      										<td>
      											<?php foreach ($raci as $raciRow): ?>
      												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
      													<?php if ($raciRow['ROLE'] == '2'): ?>
      														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
      													<?php endif; ?>
      												<?php endif; ?>
      											<?php endforeach; ?>
      										</td>
      										<td>
      											<?php foreach ($raci as $raciRow): ?>
      												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
      													<?php if ($raciRow['ROLE'] == '3'): ?>
      														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
      													<?php endif; ?>
      												<?php endif; ?>
      											<?php endforeach; ?>
      										</td>
      										<td>
      											<?php foreach ($raci as $raciRow): ?>
      												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
      													<?php if ($raciRow['ROLE'] == '4'): ?>
      														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
      													<?php endif; ?>
      												<?php endif; ?>
      											<?php endforeach; ?>
      										</td>
                          <td class='text-center'>
                            <?php foreach ($departments as $department): ?>
      												<?php if ($deptID == $department['DEPARTMENTID']): ?>
                                <?php echo $department['DEPARTMENTNAME'];?>
      												<?php endif; ?>
      											<?php endforeach; ?>
                          </td>
                          <td><?php echo $task['TASKREMARKS'];?></td>
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
<!-- Progress Circle -->
<script src="<?php echo base_url()."assets/"; ?>progress-circle/progresscircle.js" type="text/javascript"></script>
<script>

  $('.circlechart').circlechart(); // Initialization
  $(function ()
  {
    var barChartData = {
      // LOOP THROUGH DEPTS
      labels  : [
        <?php $depts = array() ?>
        <?php foreach ($departments as $deptKey => $dept): ?>
          <?php if ($dept['DEPARTMENTID'] != 1): ?>
            '<?php echo $dept['DEPT']; ?>'
            <?php array_push($depts, $dept['DEPT']); ?>
            <?php if (end($departments) == $dept): ?>
              ],
            <?php else: ?>
              ,
            <?php endif; ?>
          <?php endif; ?>
        <?php endforeach; ?>
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
            <?php foreach ($departments as $dKey => $d): ?>
              <?php foreach ($departmentCompleteness as $comp): ?>
                <?php if ($d['DEPT'] == $comp['DEPT']): ?>
                  <?php echo $comp['completeness']; ?>

                  <?php if (count($departmentCompleteness) == ($dKey + 1)): ?>
                      ]
                  <?php else: ?>
                      ,
                  <?php endif; ?>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endforeach; ?>
                                // GRAY DATA
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
            <?php foreach ($departments as $dKey => $d): ?>
              <?php foreach ($departmentTimeliness as $time): ?>
                <?php if ($d['DEPT'] == $time['DEPT']): ?>
                  <?php echo $time['timeliness']; ?>

                  <?php if (count($departmentTimeliness) == ($dKey + 1)): ?>
                      ]
                  <?php else: ?>
                      ,
                  <?php endif; ?>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endforeach; ?>
                                // GREEN DATA
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
        position: 'top',
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

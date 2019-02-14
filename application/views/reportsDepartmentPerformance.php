<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Report - Department Performance</title>
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
    <!-- <link rel="stylesheet" href="<?php echo base_url("/assets/css/reportsDepartmentPerformanceStyle.css")?>"> -->
    <!-- Report Style -->
    <link rel="stylesheet" href="<?php echo base_url("/assets/css/reportStyle.css")?>">
  </head>
  <body id="printArea" style="font-size: 11px">
  <div class="wrapper">
    <!-- Main content -->
    <section>
      <!-- title row -->
      <div class="reportHeader viewCenter">
        <h3 class="viewCenter"><img class="" id = "logo" src = "<?php echo base_url("/assets/media/tei.png")?>"> Department Performance Report</h3>
        <h4 class="viewCenter"><?php echo date('Y');?></h4>
      </div>
      <div class="reportBody">

        <!-- BAR CHART -->
        <div class="box box-danger">
          <!-- <div class="box-header with-border">
            <h3 class="box-title">Bar Chart</h3>
          </div> -->
          <div class="box-body">
            <div class="viewCenter">
  						<p style="display: inline-block">Legend:</p>
  						<div style="width: 20px; height: 10px; background-color:#d2d6de; display:inline-block; margin-left:10px;"></div> Completeness
  						<div style="width: 20px; height: 10px; background-color:#18A55D; display:inline-block; margin-left:10px;"></div> Timeliness
  					</div>
            <div class="chart">
              <canvas id="barChart" style="height:180px"></canvas>
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->

          <div class="box box-default">
            <!-- TEAM MEMBERS -->
    				<div class="row">
    					<div class="col-md-12 col-sm-12 col-xs-12">
    						<div class="box box-default">
    							<div class="box-header with-border">
    								<!-- <h5 class="box-title">Team Members</h5> -->
    							</div>
    							<!-- /.box-header -->
    							<div class="box-body">
                    <?php foreach($departments as $key => $department):?>
                      <?php if($department['DEPARTMENTID'] != 1):?>
      								<table class="table table-bordered table-condensed" id="">

      										<tr>
                            <th class='text-center' colspan="2"><?php echo $department['DEPARTMENTNAME'];?></th>
      											<th width = "35%">Project</th>
      											<th width = "20%" class='text-center'>End Date</th>
                            <th width = "15%" class='text-center'>Completeness</th>
                            <th width = "15%" class='text-center'>Timeliness</th>
      										</tr>
      									<tbody>
                          <?php $counter = 0;?>
                          <?php foreach(${"departmentPerf" . $department['DEPARTMENTID']} as $performanceData):?>
                            <?php if($counter == 0):?>
                              <tr>
                                <th rowspan="<?php echo count(${"departmentPerf" . $department['DEPARTMENTID']});?>" class="text-center" style="vertical-align: middle; font-size:14px;"><?php echo $departmentPerformance[$key]['COMPLETENESSAVERAGE'];?>%<br><span style="font-size:8px">Completeness</span></th>
                                <th rowspan="<?php echo count(${"departmentPerf" . $department['DEPARTMENTID']});?>" class="text-center" style="vertical-align: middle; font-size:14px;"><?php echo $departmentPerformance[$key]['TIMELINESSAVERAGE'];?>%<br><span style="font-size:8px">Timeliness</span></th>
                                <td style="vertical-align: middle"><?php echo $performanceData['PROJECTTITLE'];?></td>
                                <td style="vertical-align: middle" align = 'center'><?php echo date_format(date_create($performanceData['PROJECTENDDATE']), "F d, Y");?></td>
                                <td style="vertical-align: middle" align = 'center'><?php echo $performanceData['completeness'];?>%</td>
                                <td style="vertical-align: middle" align = 'center'><?php echo $performanceData['timeliness'];?>%</td>
                              </tr>
                            <?php else:?>
                              <tr>
                                <td style="vertical-align: middle"><?php echo $performanceData['PROJECTTITLE'];?></td>
                                <td style="vertical-align: middle" align = 'center'><?php echo date_format(date_create($performanceData['PROJECTENDDATE']), "F d, Y");?></td>
                                <td style="vertical-align: middle" align = 'center'><?php echo $performanceData['completeness'];?>%</td>
                                <td style="vertical-align: middle" align = 'center'><?php echo $performanceData['timeliness'];?>%</td>
                              </tr>
                              <?php endif;?>
                          <?php $counter++;?>
                          <?php endforeach;?>
                      	</tbody>
      								</table><br><br>
                    <?php endif;?>
                    <?php endforeach;?>
    							</div>
    						</div>
    	        </div>
    	        <!-- /.col -->
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
  <script> //AJAX DATA

    $.ajax({
     type:"POST",
     url: "<?php echo base_url("index.php/controller/getDelayEffect"); ?>",
     data: {task_ID: $taskID},
     dataType: 'json',
     success:function(affectedTasks)
     {

     },
     error:function()
     {
       alert("There was a problem in retrieving the department performance");
     }
    });

  </script>

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
          <?php foreach($departments as $department):?>
            '<?php echo $department['DEPT'];?>'
            <?php $index++;?>
            <?php if(count($departments) > $index):?>
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
              <?php foreach($departmentPerformance as $deptCompleteness):?>
                <?php echo $deptCompleteness['COMPLETENESSAVERAGE'];?>
                <?php $index++;?>
                <?php if(count($departmentPerformance) > $index):?>
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
              <?php foreach($departmentPerformance as $deptTimeliness):?>
                <?php echo $deptTimeliness['TIMELINESSAVERAGE'];?>
                <?php $index++;?>
                <?php if(count($departmentPerformance) > $index):?>
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

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Report - Team Performance</title>
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
      <h3 class="viewCenter"><img class="" id = "logo" src = "<?php echo base_url("/assets/media/tei.png")?>"> Team Performance Report</h3>
    </div>
    <div class="reportBody">
      <!-- LOOP START HERE -->
        <div class="box box-danger">
          <table class="table-condensed" style="width:100%">
            <tr>
              <td><b>Department: </b><?php echo $deptName;?></td>
            </tr>
            <tr>
              <td><b>Head: </b><?php echo $deptHead['FIRSTNAME'] . " " . $deptHead['LASTNAME'];?></td>
            </tr>
          </table>

          <!-- BAR CHART -->
          <div class="box box-default">
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
                      <th class='text-center'>Projects</th>
                      <th class='text-center'>Ongoing<br>Tasks</th>
                      <th class='text-center'>Delayed<br>Tasks</th>
                      <th class='text-center'>Average<br>Completeness</th>
                      <th class='text-center'>Average<br>Timeliness</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($userTeam as $user):?>
                      <tr>
                        <td><?php echo $user['FIRSTNAME'] . " " . $user['LASTNAME'];?></td>
                        <td><?php echo $user['POSITION']; ?></td>

                        <?php
                          $countProject = 0;
                          $countTask = 0;
                          $countDelayed = 0;
                          $completeness = 0;
                          $timeliness = 100;
                        ?>

                        <?php foreach ($projectCount as $pCount): ?>
                          <?php if ($user['USERID'] == $pCount['USERID']): ?>
                            <?php $countProject = $pCount['PROJECTCOUNT']; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>


                        <?php foreach ($taskCount as $tCount): ?>
                          <?php if ($user['USERID'] == $tCount['users_USERID']): ?>
                            <?php $countTask = $tCount['TASKCOUNT']; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>

                        <?php foreach ($delayedCount as $dCount): ?>
                          <?php if ($user['USERID'] == $dCount['userid']): ?>
                            <?php $countDelayed = $dCount['delayedTaskCount']; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>

                        <?php foreach ($employeePerformance as $perf): ?>
                          <?php if ($perf['users_USERID'] == $user['USERID']): ?>
                            <?php $completeness =  $perf['completeness']; ?>
                            <?php $timeliness =  $perf['timeliness'];?>

                          <?php endif; ?>
                        <?php endforeach; ?>

                        <td align='center'><?php echo $countProject; ?></td>
                        <td align='center'><?php echo $countTask; ?></td>
                        <td align='center'><?php echo $countDelayed; ?></td>
                        <td align='center'><?php echo $completeness; ?>%</td>
                        <td align='center'><?php echo $timeliness; ?>%</td>

                      </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
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

<!-- jQuery 3 -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/chart.js/Chart.js"></script>
<script>
  console.log("hello");
  $(function ()
  {
    var barChartData = {
      labels:[
        <?php $index = 0;?>
        <?php foreach($userTeam as $user):?>
        '<?php echo $user['FIRSTNAME'] . " " . $user['LASTNAME'];?>'
        <?php $index++;?>
        <?php if(count($userTeam) > $index):?>
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
            <?php $idx = 0;?>

            <?php foreach($userTeam as $user):?>

              <?php $completeness = 0;?>

              <?php foreach($employeePerformance as $employee): ?>

                <?php if($employee['users_USERID'] == $user['USERID']): ?>
                  <?php $completeness = $employee['completeness'];?>
                <?php endif; ?>
              <?php endforeach; ?>

              '<?php echo $completeness ?>'
              <?php $idx++;?>
              <?php if(count($userTeam) > $idx):?>
                ,
              <?php else:?>
                ],
              <?php endif;?>
            <?php endforeach;?>
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
            <?php $index = 0;?>
            <?php $timeliness = 0;?>

            <?php foreach($userTeam as $key => $user):?>
            <?php $timeliness = 0;?>

            <?php foreach($employeePerformance as $employee): ?>

              <?php if($employee['users_USERID'] == $user['USERID']): ?>
                <?php $timeliness = $employee['timeliness'];?>
              <?php endif; ?>
            <?php endforeach; ?>

            '<?php echo $timeliness ?>'

            <?php $index++;?>
              <?php if(count($userTeam) > $index):?>
                ,
              <?php else:?>
                ],
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

  $.ajax(
  {
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

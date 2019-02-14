<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Report - Employee Performance Report</title>
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
<body onload="window.print();" id="printArea" style="font-size: 11px">
<div class="wrapper">
  <!-- Main content -->
  <section>
    <!-- title row -->
    <div class="reportHeader viewCenter">
      <h3 class="viewCenter"><img class="" id = "logo" src = "<?php echo base_url("/assets/media/tei.png")?>"> Employee Performance Report</h3>
    </div>
    <div class="reportBody">
      <!-- LOOP START HERE -->
        <div class="box box-danger">
          <table class="table-condensed" style="width:100%; width:auto; display:inline-block">
            <tr>
              <td><b>Name: </b><?php echo $userInfo['FIRSTNAME'] . " " . $userInfo['LASTNAME'];?></td>
            </tr>
            <tr>
              <td><b>Position: </b><?php echo $userInfo['POSITION'];?></td>
            </tr>
            <tr>
              <td><b>Department: </b>
                <?php foreach($departments as $department)
                  if($department['DEPARTMENTID'] == $userInfo['departments_DEPARTMENTID'])
                    echo $department['DEPARTMENTNAME'];
                ?>
              </td>
            </tr>
          </table>

           <div class="pull-right" style="display:inline-block; margin-right: 20px">
             <div style="display:inline-block; text-align:center; width:49%; padding-right: 20px">
               <h3><?php echo $completeness['completeness']; ?>%</h3>
               <h6>Completeness</h6>
             </div>
             <div style="display:inline-block; text-align:center; width:49%; padding-left: 20px; border-left: solid lightgray 1px;">
               <h3><?php echo $timeliness['timeliness']; ?>%</h3>
               <h6>Timeliness</h6>
             </div>
           </div>

          <?php foreach($projectCount as $key => $project):?>
          <table class="table table-bordered table-condensed" id="">
            <thead>
                <tr>
                  <th colspan="3"><?php echo $project['PROJECTTITLE'];?> (<?php echo date_format(date_create($project['PROJECTSTARTDATE']), "M d, Y");?> - <?php echo date_format(date_create($project['PROJECTENDDATE']), "M d, Y");?>)</th>
                  <?php
                    $completeness = 0;
                    $timeliness = 100;
                  ?>
                  <?php foreach($projectPerformance as $pp): ?>
                    <?php if($project['PROJECTID'] == $pp['PROJECTID']):?>
                      <?php $completeness = $pp['completeness']; ?>
                      <?php $timeliness = $pp['timeliness']; ?>
                    <?php endif;?>
                  <?php endforeach;?>

                  <th colspan="3">Completess: <?php echo $completeness . "%"; ?></th>
                  <th colspan="3">Timeliness: <?php echo $timeliness . "%"; ?></th>
                </tr>
                <tr>
                  <th width="20%">Task</th>
                  <th class='text-center' width="10%">Start Date</th>
                  <th class='text-center' width="10%">End Date</th>
                  <th class='text-center' width="10%">Actual<br>End Date</th>
                  <th class='text-center' width="5%">Days<br>Delayed</th>
                  <th class='text-center' width="15%">A</th>
                  <th class='text-center' width="15%">C</th>
                  <th class='text-center' width="15%">I</th>
                  <th class='text-center' width="10%">Status</th>
                </tr>
              </thead>
                <tbody>
                <?php foreach($taskCount as $task):?>
                  <?php if($task['projects_PROJECTID'] == $project['PROJECTID']):?>
                    <?php
                      if($task['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
                        $startDate = $task['TASKSTARTDATE'];
                      else
                        $startDate = $task['TASKADJUSTEDSTARTDATE'];

                      if($task['TASKADJUSTEDENDDATE'] == "")
                      {
                        $endDate = $task['TASKENDDATE'];
                        $daysDelayed = $task['originalDelay'];
                      }
                      else
                      {
                        $endDate = $task['TASKADJUSTEDENDDATE'];
                        $daysDelayed = $task['adjustedDelay'];
                      }

                      if($task['TASKACTUALENDDATE'] == "")
                        $actualEnd = "-";
                      else
                        $actualEnd = date_format(date_create($task['TASKACTUALENDDATE']), "M d, Y");
                    ?>
                    <tr>
                      <td><?php echo $task['TASKTITLE'];?></td>
                      <td align='center'><?php echo date_format(date_create($startDate), "M d, Y"); ?></td>
                      <td align='center'><?php echo date_format(date_create($endDate), "M d, Y"); ?></td>
                      <td align='center'><?php echo $actualEnd; ?></td>
                      <td align='center'>
                        <?php if($task['TASKSTATUS'] == 'Complete'):?>
                          <?php echo $daysDelayed; ?>
                        <?php else:?>
                          -
                        <?php endif;?>
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
                      <td align='center'><?php echo $task['TASKSTATUS'];?></td>
                    </tr>
                  <?php endif;?>
                <?php endforeach;?>
                </tbody>
              </table>
            <?php endforeach;?>

  				<!-- Change Requests -->
          <?php if($changeRequests != NULL):?>
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
                          <th class="text-center" width="10%">End Date</th>
                          <th width="0%" class='text-center'>Type</th>
                          <th width="0" class="text-center" width="10%">Date Requested</th>
    											<th width="0%">Reason</th>
                          <th width="0%" class="text-center">Status</th>
                          <th width="0%">Reviewed By</th>
                          <th width="0%" class="text-center" width="10%">Reviewed Date</th>
    										</tr>
    									</thead>
    									<tbody>
                        <?php foreach($changeRequests as $changeRequest):?>

                          <?php
                          if($changeRequest['TASKADJUSTEDENDDATE'] == "") // check if start date has been previously adjusted
                            $endDate = $changeRequest['TASKENDDATE'];
                          else
                            $endDate = $changeRequest['TASKADJUSTEDENDDATE'];

                          if($changeRequest['REQUESTSTATUS'] == 'Pending')
                          {
                            $reviewedBy = '<td align="center">-</td>';
                            $reviewedDate = '<td align="center">-</td>';
                          }
                          else
                          {
                            $reviewedBy = "<td>" . $changeRequest['FIRSTNAME'] . " " . $changeRequest['LASTNAME'] . "</td>";
                            $reviewedDate = "<td>" . date_format(date_create($changeRequest['APPROVEDDATE']), "M d, Y") . "</td>";
                          }
                          ?>
                          <tr>
                            <td><?php echo $changeRequest['TASKTITLE'];?></td>
                            <td align='center'><?php echo date_format(date_create($endDate), "M d, Y"); ?></td>
                            <td align='center'>
                              <?php if($changeRequest['REQUESTTYPE'] == '1')
    														echo "Change Performer";
    													else
    														echo "Change Date";
    													?>
                            </td>
                            <td align='center'><?php echo date_format(date_create($changeRequest['REQUESTEDDATE']), "M d, Y"); ?></td>
                            <td><?php echo $changeRequest['REASON'];?></td>
                            <td align='center'><?php echo $changeRequest['REQUESTSTATUS'];?></td>
                            <?php echo $reviewedBy;?>
                            <?php echo $reviewedDate;?>
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
</body>
</html>

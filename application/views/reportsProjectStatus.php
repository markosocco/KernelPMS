<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Report - Project Status Report</title>
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
      <h3 class="viewCenter"><img class="" id = "logo" src = "<?php echo base_url("/assets/media/tei.png")?>"> Project Status Report</h3>
      <p>from
        <?php
          $time = strtotime(date('Y-m-d') . ' -' . $interval . ' days');
          echo $date = date("F d, Y", $time);?>
          to
        <?php echo date('F d, Y');?>
      </p>
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

          <!-- PROBLEMS ENCOUNTERED -->
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="box box-default">
                <div class="box-header with-border">
                  <h5 class="box-title">Delayed Tasks</h5>
                </div>
                <!-- /.box-header -->
                <?php if($problemTasks != NULL):?>

                  <div class="box-body">
                    <table class="table table-bordered table-condensed" id="">
                      <thead>
                        <tr>
                          <th width="30%">Task</th>
                          <th class='text-center' width="10%">End Date</th>
                          <th class='text-center' width="10%">Actual End Date</th>
                          <th class='text-center' width="7%">Days Delayed</th>
                          <th>Responsible</th>
                          <th width="15%">Remarks</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($problemTasks as $problemTask):?>
                          <?php // to fix date format
                            if($problemTask['TASKSTATUS'] == 'Ongoing')
                            {
                              $taskActualEnd = "-";
                              $remarks = "-";
                            }
                            else
                            {
                              $taskActualEnd = date_format(date_create($problemTask['TASKACTUALENDDATE']), "M d, Y");
                              $remarks = $problemTask['TASKREMARKS'];
                            }

                            if($problemTask['TASKADJUSTEDENDDATE'] == "")
                            {
                              $taskEnd = date_create($problemTask['TASKENDDATE']);
                              if($problemTask['TASKSTATUS'] == 'Ongoing')
                                $daysDelayed = $problemTask['ongoingOrigDelay'];
                              else
                                $daysDelayed = $problemTask['completeOrigDelay'];
                            }
                            else
                            {
                              $taskEnd = date_create($problemTask['TASKADJUSTEDENDDATE']);
                              if($problemTask['TASKSTATUS'] == 'Ongoing')
                                $daysDelayed = $problemTask['ongoingAdjustedDelay'];
                              else
                                $daysDelayed = $problemTask['completeAdjustedDelay'];
                            }
                          ?>
                          <tr>
                            <td><?php echo $problemTask['TASKTITLE'];?></td>
                            <td align="center"><?php echo date_format($taskEnd, "M d, Y");?></td>
                            <td align="center"><?php echo $taskActualEnd;?></td>
                            <td align="center"><?php echo $daysDelayed;?></td>
                            <td><?php echo $problemTask['FIRSTNAME'];?> <?php echo $problemTask['LASTNAME'];?></td>
                            <td align="center"><?php echo $remarks;?></td>
                          </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                  </div>
                <?php else:?>
                  <h5 align="center">There are no delayed tasks</h5>
                <?php endif;?>
              </div>
            </div>
            <!-- /.col -->
          </div>

          <!-- RISKS -->
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Risks</h5>
  							</div>
  							<!-- /.box-header -->
                <?php if($pendingRFC != NULL || $pendingRACI != NULL):?>
    							<div class="box-body">
                    <?php if($pendingRACI != NULL):?>
                      <h5>Pending Task Delegation</h5>
                        <table class="table table-bordered table-condensed" id="">
                          <thead>
        										<tr>
        											<th width="40%">Task</th>
                              <th class='text-center'>Start Date</th>
                              <th>Delegator</th>
        										</tr>
        									</thead>
        									<tbody>
                            <?php foreach($pendingRACI as $taskRACI):?>
                              <tr>
                                <td><?php echo $taskRACI['TASKTITLE'];?></td>
                                <td align="center"><?php echo date_format(date_create($taskRACI['TASKSTARTDATE']), "M d, Y");?></td>
                                <td><?php echo $taskRACI['FIRSTNAME'];?> <?php echo $taskRACI['LASTNAME'];?></td>
                              </tr>
                            <?php endforeach;?>
        									</tbody>
        								</table>
                      <?php endif;?>

                    <?php if($pendingRFC != NULL):?>
                      <h5>Pending Change Requests</h5>
      								<table class="table table-bordered table-condensed" id="">
                        <thead>
      										<tr>
      											<th width = "40%">Task</th>
                            <th class='text-center'>End Date</th>
                            <th>Type</th>
                            <th class='text-center'>Date Requested</th>
                            <th>Requester</th>
                            <th>Reason</th>
      										</tr>
      									</thead>
      									<tbody>
                          <?php foreach($pendingRFC as $request):?>
                            <?php // to fix date format
                              $taskStart = date_create($request['TASKSTARTDATE']);
                              if($request['TASKADJUSTEDENDDATE'] == "")
                                $taskEnd = date_create($request['TASKENDDATE']);
                              else
                                $taskEnd = date_create($request['TASKADJUSTEDENDDATE']);

                              if($request['REQUESTTYPE'] == '1')
                                $type = "Change Date";
                              else
                                $type = "Change Performer";

                              $taskRequest = date_create($request['REQUESTEDDATE']);
                            ?>
                            <tr>
                              <td><?php echo $request['TASKTITLE'];?></td>
                              <td align="center"><?php echo date_format($taskEnd, "M d, Y");?></td>
                              <td><?php echo $type;?></td>
                              <td align="center"><?php echo date_format($taskRequest, "M d, Y");?></td>
                              <td><?php echo $request['FIRSTNAME'];?> <?php echo $request['LASTNAME'];?></td>
                              <td><?php echo $request['REASON'];?></td>
                            </tr>
                          <?php endforeach;?>
      									</tbody>
      								</table>
                    <?php endif;?>
    							</div>
                <?php elseif($pendingRFC == NULL && $pendingRACI == NULL):?>
                  <h5 align="center">There are no risks</h5>
                <?php endif;?>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>

          <!-- PLANNED LAST WEEK -->
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Ongoing Tasks</h5>
  							</div>
  							<!-- /.box-header -->
                <?php if($ongoingTasks != NULL):?>
    							<div class="box-body">
    								<table class="table table-bordered table-condensed" id="">
    									<thead>
    										<tr>
    											<th width="40%">Task</th>
    											<th class='text-center'>Start Date</th>
                          <th class='text-center'>End Date</th>
                          <th>Responsible</th>
    										</tr>
    									</thead>
    									<tbody>
                        <?php foreach($ongoingTasks as $ongoingTask):?>
                          <?php // to fix date format
                            $taskStart = date_create($ongoingTask['TASKSTARTDATE']);
                            if($ongoingTask['TASKADJUSTEDENDDATE'] == "")
                              $taskEnd = date_create($ongoingTask['TASKENDDATE']);
                            else
                              $taskEnd = date_create($ongoingTask['TASKADJUSTEDENDDATE']);
                          ?>
                          <tr>
                            <td><?php echo $ongoingTask['TASKTITLE'];?></td>
                            <td align="center"><?php echo date_format($taskStart, "M d, Y");?></td>
                            <td align="center"><?php echo date_format($taskEnd, "M d, Y");?></td>
                            <td><?php echo $ongoingTask['FIRSTNAME'];?> <?php echo $ongoingTask['LASTNAME'];?></td>
                          </tr>
                        <?php endforeach;?>
    									</tbody>
    								</table>
    							</div>
                <?php else:?>
                  <h5 align="center">There were no tasks planned last <?php echo strtolower($intervalWord);?></h5>
                <?php endif;?>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>

  				<!-- ACCOMPLISHED TASKS LAST WEEK -->
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Accomplished Tasks Last <?php echo $intervalWord;?></h5>
  							</div>
  							<!-- /.box-header -->
                <?php if($accomplishedLast != NULL):?>
    							<div class="box-body" id="delayedBox">
    								<table class="table table-bordered table-condensed" id="delayedTable">
                      <thead>
    										<tr>
    											<th width="40%">Task</th>
    											<th class='text-center'>End Date</th>
                          <th class='text-center'>Actual End Date</th>
                          <th>Responsible</th>
    										</tr>
    									</thead>
    									<tbody>
                        <?php foreach($accomplishedLast as $accomplishedLastTask):?>
                          <?php // to fix date format
                            $taskActualEnd = date_create($accomplishedLastTask['TASKACTUALENDDATE']);
                            if($accomplishedLastTask['TASKADJUSTEDENDDATE'] == "")
                              $taskEnd = date_create($accomplishedLastTask['TASKENDDATE']);
                            else
                              $taskEnd = date_create($accomplishedLastTask['TASKADJUSTEDENDDATE']);
                          ?>
                          <tr>
                            <td><?php echo $accomplishedLastTask['TASKTITLE'];?></td>
                            <td align="center"><?php echo date_format($taskEnd, "M d, Y");?></td>
                            <td align="center"><?php echo date_format($taskActualEnd, "M d, Y");?></td>
                            <td><?php echo $accomplishedLastTask['FIRSTNAME'];?> <?php echo $accomplishedLastTask['LASTNAME'];?></td>
                          </tr>
                        <?php endforeach;?>
    									</tbody>
    								</table>
    							</div>
                <?php else:?>
                  <h5 align="center">There were no tasks accomplished last <?php echo strtolower($intervalWord);?></h5>
                <?php endif;?>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>

  				<!-- PLANNED NEXT WEEK -->
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Planned Next <?php echo $intervalWord;?></h5>
  							</div>
  							<!-- /.box-header -->
                <?php if ($plannedNext != NULL):?>
    							<div class="box-body">
    								<table class="table table-bordered table-condensed" id="">
                      <thead>
    										<tr>
    											<th width="40%">Task</th>
                          <th class='text-center'>Start Date</th>
    											<th class='text-center'>End Date</th>
                          <th>Responsible</th>
    										</tr>
    									</thead>
    									<tbody>
                        <?php foreach($plannedNext as $plannedNextTask):?>
                          <?php // to fix date format
                            $taskStart = date_create($plannedNextTask['TASKSTARTDATE']);
                            if($plannedNextTask['TASKADJUSTEDENDDATE'] == "")
                              $taskEnd = date_create($plannedNextTask['TASKENDDATE']);
                            else
                              $taskEnd = date_create($plannedNextTask['TASKADJUSTEDENDDATE']);
                          ?>
                          <tr>
                            <td><?php echo $plannedNextTask['TASKTITLE'];?></td>
                            <td align="center"><?php echo date_format($taskStart, "M d, Y");?></td>
                            <td align="center"><?php echo date_format($taskEnd, "M d, Y");?></td>
                            <td><?php echo $plannedNextTask['FIRSTNAME'];?> <?php echo $plannedNextTask['LASTNAME'];?></td>
                          </tr>
                        <?php endforeach;?>
    									</tbody>
    								</table>
    							</div>
                <?php else:?>
                  <h5 align="center">There are no tasks planned next <?php echo strtolower($intervalWord);?></h5>
                <?php endif;?>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>

        </div>

    <div class="endReport viewCenter">
      <p>***END OF REPORT***</p>
    </div>

    <div class="reportFooter">
      <!-- To the right -->
      <div id="pageCounter">
        <!-- <medium>Page 1 of 1M</medium> -->
      </div>
      <!-- Default to the left -->
      <medium>Prepared By: <?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']?></medium>
      <br>
      <medium>Prepared On: <?php echo date('F d, Y'); ?></medium>

    </div>
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
<script>

</script>
</body>
</html>

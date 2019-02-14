<html>
	<head>
		<title>Kernel - Change Requests</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/rfcStyle.css")?>"> -->
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Change Requests
					<small>What do I think needs changing?</small>
				</h1>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<?php if($changeRequests != null):?>
					<div class="box box-danger">
						<div class="box-header">
							<h3 class="box-title">Requests I Need To Approve</h3>
						</div>
						<!-- /.box-header -->

						<div class="box-body">
							<table id="rfcList" class="table no-margin table-hover">
								<thead>
								<tr>
									<th width="10%" class="text-center">Date Requested</th>
									<th class="text-center" width="1%">Type</th>
									<th width="15%">Requested By</th>
									<th>Task</th>
									<th width="10%" class="text-center">Start Date</th>
									<th width="11%" class="text-center">Target End Date</th>
									<th>Project</th>
								</tr>
								</thead>
								<tbody>
									<?php foreach($changeRequests as $changeRequest):
										$dateRequested = date_create($changeRequest['REQUESTEDDATE']);
										if($changeRequest['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
											$startDate = date_create($changeRequest['TASKSTARTDATE']);
										else
											$startDate = date_create($changeRequest['TASKADJUSTEDSTARTDATE']);

										if($changeRequest['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
											$endDate = date_create($changeRequest['TASKENDDATE']);
										else
											$endDate = date_create($changeRequest['TASKADJUSTEDENDDATE']);

										if($changeRequest['REQUESTTYPE'] == 1)
											$type = "Change Performer";
										else
											$type = "Change Date/s";
									?>
										<tr class="request clickable" data-project = "<?php echo $changeRequest['PROJECTID']; ?>" data-request = "<?php echo $changeRequest['REQUESTID']; ?>">

											<form action = 'projectGantt' method="POST">
												<input type ='hidden' name='rfc' value='0'>
											</form>

											<td><?php echo date_format($dateRequested, "M d, Y"); ?></td>
											<td align="center">
												<?php if($changeRequest['REQUESTTYPE'] == 1):?>
													<i class="fa fa-user-times"></i>
												<?php else:?>
													<i class="fa fa-calendar"></i>
												<?php endif;?>
												<!-- <?php echo $type;?> -->
											</td>
											<td><?php echo $changeRequest['FIRSTNAME'] . " " .  $changeRequest['LASTNAME'] ;?></td>
											<td><?php echo $changeRequest['TASKTITLE'];?></td>
											<td class="text-center"><?php echo date_format($startDate, "M d, Y"); ?></td>
											<td class="text-center"><?php echo date_format($endDate, "M d, Y"); ?></td>
											<td><?php echo $changeRequest['PROJECTTITLE'];?></td>
										</tr>
									<?php endforeach;?>

								</tbody>
							</table>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				<?php endif;?>


					<?php if($userRequests != null):?>
						<div class="box box-danger">
							<div class="box-header">
								<h3 class="box-title">Requests I Sent</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-body">
								<table id="userrfcList" class="table no-margin table-hover">
									<thead>
									<tr>
										<th width="10%" class="text-center">Date Requested</th>
										<th class="text-center">Type</th>
										<th>Task</th>
										<th width="10%" class="text-center">Start Date</th>
										<th width="11%" class="text-center">Target End Date</th>
										<th>Project</th>
										<th class="text-center">Status</th>
										<th>Reviewed By</th>
										<th class="text-center">Date Reviewed</th>
									</tr>
									</thead>
									<tbody>
										<?php foreach($userRequests as $userRequest):
											$dateRequested = date_create($userRequest['REQUESTEDDATE']);
											$approvedDate = date_create($userRequest['APPROVEDDATE']);

											if($userRequest['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
												$startDate = date_create($userRequest['TASKSTARTDATE']);
											else
												$startDate = date_create($userRequest['TASKADJUSTEDSTARTDATE']);

											if($userRequest['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
												$endDate = date_create($userRequest['TASKENDDATE']);
											else
												$endDate = date_create($userRequest['TASKADJUSTEDENDDATE']);

											if($userRequest['REQUESTTYPE'] == 1)
												$type = "Change Performer";
											else
												$type = "Change Date/s";
										?>
											<tr class="userRequest clickable" data-project = "<?php echo $userRequest['PROJECTID']; ?>" data-request = "<?php echo $userRequest['REQUESTID']; ?>">

												<form id = "viewProject" action = 'projectGantt' method="POST">
													<input type ='hidden' name='userRequest' value='0'>
												</form>

												<td><?php echo date_format($dateRequested, "M d, Y"); ?></td>
												<td align="center">
													<?php if($userRequest['REQUESTTYPE'] == 1):?>
														<i class="fa fa-user-times"></i>
													<?php else:?>
														<i class="fa fa-calendar"></i>
													<?php endif;?>
													<!-- <?php echo $type;?> -->
												</td>
												<td><?php echo $userRequest['TASKTITLE'];?></td>
												<td align="center"><?php echo date_format($startDate, "M d, Y"); ?></td>
												<td align="center"><?php echo date_format($endDate, "M d, Y"); ?></td>
												<td><?php echo $userRequest['PROJECTTITLE'];?></td>
												<td align="center"><?php echo $userRequest['REQUESTSTATUS'];?></td>
												<?php if($userRequest['REQUESTSTATUS'] == 'Pending'):?>
													<td align="center">-</td>
													<td align="center">-</td>
												<?php else:?>
													<td><?php echo $userRequest['FIRSTNAME'] . " " .  $userRequest['LASTNAME'] ;?></td>
													<td align="center"><?php echo date_format($approvedDate, "M d, Y");;?></td>
												<?php endif;?>
											</tr>
										<?php endforeach;?>

									</tbody>
								</table>
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					<?php endif;?>

				<?php if($changeRequests == null && $userRequests == null):?>
					<h3 class="box-title" style="text-align:center" >You have no change requests</h3>
				<?php endif;?>

			</section>

		</div>


		<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->

		<script>
		$("#rfc").addClass("active");

		$(document).on("click", ".request", function() {
			var $project = $(this).attr('data-project');
			var $request = $(this).attr('data-request');
			$("form").attr("name", "formSubmit");
			$("form").append("<input type='hidden' name='project_ID' value= " + $project + ">");
			$("form").append("<input type='hidden' name='request_ID' value= " + $request + ">");
			$("form").submit();
		});

		$(document).on("click", ".userRequest", function() {
			var $project = $(this).attr('data-project');
			var $request = $(this).attr('data-request');
			$("#viewProject").attr("name", "formSubmit");
			$("#viewProject").append("<input type='hidden' name='project_ID' value= " + $project + ">");
			$("#viewProject").append("<input type='hidden' name='request_ID' value= " + $request + ">");
			$("#viewProject").submit();
		});

		$(function () {
			$('#rfcList').DataTable({
				'paging'      : false,
				'lengthChange': false,
				'searching'   : true,
				'ordering'    : true,
				'info'        : false,
				'autoWidth'   : false,
				'order'				: [[ 0, "asc" ]],
				'columnDefs'	: [
				{
					'targets'		: [ 1 ],
					'orderable'	: false
				} ]
			});

			$('#userrfcList').DataTable({
				'paging'      : false,
				'lengthChange': false,
				'searching'   : true,
				'ordering'    : true,
				'info'        : false,
				'autoWidth'   : false,
				'order'				: [[ 0, "asc" ]],
				'columnDefs'	: [
				{
					'targets'		: [ 1 ],
					'orderable'	: false
				} ]
			});
		});
		</script>

	</body>
</html>

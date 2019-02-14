<html>
	<head>
		<title>Kernel - Project Archives</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/archivesStyle.css")?>">
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Project Archives
					<small>What are the projects we have done?</small>
				</h1>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<?php if($archives == null):?>
					<h3 class="box-title" style="text-align:center">There are no project archives</h3>
				<?php else:?>
				<div class="box box-danger">
					<div class="box-header">
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="archiveList" class="table no-margin table-hover">
							<thead>
							<tr>
								<th width="35%">Project</th>
								<th>Project Type</th>
								<th>Owner</th>
								<th width = "10%" class='text-center'>Start Date</th>
								<th width = "10%" class='text-center'>Target<br>End Date</th>
								<th width = "10%" class='text-center'>Actual<br>End Date</th>
							</tr>
							</thead>
							<tbody>

								<?php foreach ($archives as $a): ?>
									<tr class="project clickable" data-id = "<?php echo $a['PROJECTID']; ?>">

										<form action = 'projectGantt' method="POST">
												<input type ='hidden' name='archives' value='0'>
										</form>

										<?php
											$start = date_create($a['PROJECTSTARTDATE']);
											$end = date_create($a['PROJECTENDDATE']);
											$actualEnd = date_create($a['PROJECTACTUALENDDATE']);
										;?>

										<td><?php echo $a['PROJECTTITLE']; ?></td>
										<td>
											<?php
											switch($a['PROJECTTYPE'])
											{
												case 1: echo 'Store Opening'; break;
												case 2: echo 'Product Launch'; break;
												case 3: echo 'Marketing Promotion'; break;
												case 4: echo 'System Development'; break;
												case 5: echo 'Onboarding'; break;
												case 6: echo 'Offboarding'; break;
												case 7: echo 'Miscellaneous'; break;
												default: echo ''; break;
											}
											 ;?>
										</td>
										<td><?php echo $a['FIRSTNAME'] . " " . $a['LASTNAME']; ?></td>
										<td class='text-center'><?php echo date_format($start, "M d, Y"); ?></td>
										<td class='text-center'><?php echo date_format($end, "M d, Y"); ?></td>
										<td class='text-center'><?php echo date_format($actualEnd, "M d, Y"); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			<?php endif;?>

			</section>

		</div>


		<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->

		<script>

		// IF USING POST METHOD FOR PROJECT ID
		$(document).on("click", ".project", function() {
			var $id = $(this).attr('data-id');
			$("form").attr("name", "formSubmit");
			$("form").append("<input type='hidden' name='project_ID' value= " + $id + ">");
			$("form").submit();

			// console.log("hello " + $id);
			});

		$("#projectArchives").addClass("active");
		$(function () {
			$('#archiveList').DataTable({
				'paging'      : false,
				'lengthChange': false,
				'searching'   : true,
				'ordering'    : true,
				'info'        : false,
				'autoWidth'   : false
			});
		});
		</script>

	</body>
</html>

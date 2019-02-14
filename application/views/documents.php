<html>
	<head>
		<title>Kernel - Documents</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/documentsStyle.css")?>">
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Documents
					<small>What documents do I have?</small>
				</h1>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<div class="box box-danger">
					<div class="box-header">
						<!-- <h3 class="box-title">All documents of all projects</h3> -->
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="documentList" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Document Name</th>
									<th>Project</th>
									<th>Uploaded By</th>
									<th>Department</th>
									<th>Upload Date</th>
									<th align="center"></th>
								</tr>
							</thead>
							<tbody>

								<?php
									foreach ($documents as $row) {

										$dateUploaded = $row['UPLOADEDDATE'];
										$MonthNum = substr($dateUploaded, 5, 2);
										$Day = substr($dateUploaded, 8, 2);
										$Year = substr($dateUploaded, 0, 4);
										$Month = "";

										switch (intval($MonthNum))
										{
											case 1: $Month = 'Jan'; break;
											case 2: $Month = 'Feb'; break;
											case 3: $Month = 'Mar'; break;
											case 4: $Month = 'Apr'; break;
											case 5: $Month = 'May'; break;
											case 6: $Month = 'Jun'; break;
											case 7: $Month = 'Jul'; break;
											case 8: $Month = 'Aug'; break;
											case 9: $Month = 'Sep'; break;
											case 10: $Month = 'Oct'; break;
											case 11: $Month = 'Nov'; break;
											case 12: $Month = 'Dec'; break;
										}

										$formattedDate = $Month . " " . $Day . ", " . $Year;

										echo "<tr>";
											echo "<td>" . $row['DOCUMENTNAME'] . "</td>";
											echo "<td>" . $row['PROJECTTITLE'] . "</td>";
											echo "<td>" . $row['FIRSTNAME'] . " " . $row['LASTNAME'] . "</td>";
											echo "<td>" . $row['DEPARTMENTNAME'] . "</td>";
											echo "<td>" . $formattedDate . "</td>";
											echo "<td align='center'><button type='button' class='btn btn-success'>
											<i class='fa fa-download'></i></button></td>";

										echo "</tr>";
									}
								?>

							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

			</section>

		</div>


		<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->

		<script>
		$("#documents").addClass("active");

		$(document).ready(function() {
			$(".downloadBtn").click(function(){
				alert("Place download function here!");
			});
		});

		$(function () {
	    $('#documentList').DataTable({
	      'paging'      : false,
	      'lengthChange': false,
	      'searching'   : true,
	      'ordering'    : true,
	      'info'        : false,
	      'autoWidth'   : false
	    });
	  })
		</script>

	</body>
</html>

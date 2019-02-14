<html>
	<head>
		<title>Kernel - Calendar</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/templatesStyle.css")?>"> -->
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Calendar
					<!-- <small>When do I have to finish</small> -->
					<small>When are my dues?</small>
				</h1>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<div class="row">
					<div style="padding:20px">
						<div class="box box-danger">
							<div class="box-body no-padding">
								<!-- THE CALENDAR -->
								<div id="calendar"></div>
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /. box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</div>

		<?php require("footer.php"); ?>

		<script>
		$("#myCalendar").addClass("active");

		$(function () {

			/* initialize the calendar
			 -----------------------------------------------------------------*/
			//Date for the calendar events (dummy data)
			var date = new Date()
			var d    = date.getDate(),
					m    = date.getMonth(),
					y    = date.getFullYear()
			$('#calendar').fullCalendar({
				header    : {
					left  : 'prev,next today',
					center: 'title',
				},
				buttonText: {
					today: 'today',
					month: 'month',
					week : 'week',
					day  : 'day'
				},
				//Random default events
				events    : [
					{
						title          : 'All Day Event',
						start          : new Date(y, m, 1),
						backgroundColor: '#f56954', //red
						borderColor    : '#f56954' //red
					},
					{
						title          : 'Long Event',
						start          : new Date(y, m, d - 5),
						end            : new Date(y, m, d - 2),
						backgroundColor: '#f39c12', //yellow
						borderColor    : '#f39c12' //yellow
					},
					{
						title          : 'Meeting',
						start          : new Date(y, m, d, 10, 30),
						allDay         : false,
						backgroundColor: '#0073b7', //Blue
						borderColor    : '#0073b7' //Blue
					},
					{
						title          : 'Lunch',
						start          : new Date(y, m, d, 12, 0),
						end            : new Date(y, m, d, 14, 0),
						allDay         : false,
						backgroundColor: '#00c0ef', //Info (aqua)
						borderColor    : '#00c0ef' //Info (aqua)
					},
					{
						title          : 'Birthday Party',
						start          : new Date(y, m, d + 1, 19, 0),
						end            : new Date(y, m, d + 1, 22, 30),
						allDay         : false,
						backgroundColor: '#00a65a', //Success (green)
						borderColor    : '#00a65a' //Success (green)
					},
					{
						title          : 'Click for Google',
						start          : new Date(y, m, 28),
						end            : new Date(y, m, 29),
						url            : 'http://google.com/',
						backgroundColor: '#3c8dbc', //Primary (light-blue)
						borderColor    : '#3c8dbc' //Primary (light-blue)
					}
				],
			})
			})
		</script>

	</body>
</html>

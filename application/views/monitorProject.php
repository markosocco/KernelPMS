<html>
	<head>
		<title>Kernel - Monitor Project</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/monitorTeamStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini fixed">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Monitor Project
						<small>What's happening to projects I'm spearheading?</small>
					</h1>

					<ol class="breadcrumb">
	          <?php $dateToday = date('F d, Y | l');?>
	          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
	        </ol>
				</section>
				<!-- Main content -->
				<section class="content container-fluid" style="padding-top:20px">
					<!-- TOGGLE MY PROJECT -->
	        <div id = "divGridListMyProjects" class="pull-right">
	          <a href="#" id = "buttonListProjects" class="btn btn-default btn" data-toggle="tooltip" data-placement="left" title="List View"><i class="fa fa-th-list"></i></a>
	          <a href="#" id = "buttonGridProjects" class="btn btn-default btn" data-toggle="tooltip" data-placement="left" title="Grid View"><i class="fa fa-th-large"></i></a>
	        </div>

					<!-- SORT/LEGEND -->
					<div>
	          <button type="button" id = "filterAll" class="btn btn-default filter">All</button>
	          <button type="button" id = "filterCompleted" class="btn bg-teal filter">Completed</button>
	          <button type="button" id = "filterOngoing" class="btn btn-success filter">Ongoing</button>
	          <button type="button" id = "filterDelayed" class="btn btn-danger filter">Delayed</button>
	          <button type="button" id = "filterPlanned" class="btn btn-warning filter">Planned</button>
	          <!-- <button type="button" id = "filterParked" class="btn btn-info filter">Parked</button> -->
	          <!-- <button type="button" id = "filterDrafted" class="btn bg-maroon filter">Draft</button> -->
	        </div>

	        <br>

	        <!-- PROJECT VIEW -->
	        <div id="projectView">

	          <div id="myProjectsGridView">

	            <div class="row">

	              <?php if($completedProjects == null && $delayedProjects == null &&
	                      $ongoingProjects == null && $plannedProjects == null):?>
	                <h3 class = "projects" align="center">You do not own any project</h3>
	              <?php endif;?>

	              <div class = "projectsGrid" id = "completedProjGrid">
	                <?php foreach ($completedProjects as $key=> $value):?>

	                  <div class="col-lg-3 col-xs-6">
	                    <!-- small box -->
	                    <a class = "project clickable" data-id = "<?php echo $value['PROJECTID']; ?>">
	                    <div class="small-box bg-teal">
	                      <div class="inner">
	                        <h2>100%</h2>

	                        <form action = 'monitorDepartment'  method="POST">
	                        </form>

	                        <p class="title"><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>Archiving in
	                          <?php echo $value['datediff'] +1;?>
	                          <?php if(($value['datediff'] +1) > 1):?>
	                            days
	                          <?php else:?>
	                            day
	                          <?php endif;?>
	                        </i></p>
	                      </div>
	                      <div class="icon" style="margin-top:25px;">
	                        <i class="ion ion-checkmark"></i>
	                      </div>
	                    </div>
	                    </a>
	                  </div>
	                <!-- ./col -->
	                <?php endforeach;?>
	              </div>

	              <div class = "projectsGrid" id = "delayedProjGrid">

	                <?php foreach ($delayedProjects as $key=> $value):?>

	                  <div class="col-lg-3 col-xs-6">
	                    <!-- small box -->
	                    <a class = "project clickable" data-id = "<?php echo $value['PROJECTID']; ?>">
	                    <div class="small-box bg-red">
	                      <div class="inner">

	                        <h2>
	                          <?php
	                            foreach ($delayedProjectProgress as $row)
	                            {
	                              if ($value['PROJECTID'] == $row['projects_PROJECTID'])
	                              {
	                                echo $row['projectProgress'];
	                              }
	                            } ?>%</h2>

	                        <form class="dept" action = 'monitorDepartment'  method="POST">
	                        </form>

	                        <p class="title"><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>
	                          <?php echo $value['datediff'];?>
	                          <?php if(($value['datediff'] +1) > 1):?>
	                            days delayed
	                          <?php else:?>
	                            day delayed
	                          <?php endif;?>
	                        </i></p>
	                      </div>

	                      <div class="icon" style="margin-top:25px;">
	                        <i class="ion ion-alert-circled"></i>
	                      </div>
	                    </div>
	                    </a>
	                  </div>
	                  <!-- ./col -->
	                <?php endforeach;?>
	              </div>

	              <div class = "projectsGrid" id = "ongoingProjGrid">
	                <?php foreach ($ongoingProjects as $key=> $value):?>

	                  <div class="col-lg-3 col-xs-6">
	                    <!-- small box -->
	                    <a class = "project clickable" data-id = "<?php echo $value['PROJECTID']; ?>">
	                    <div class="small-box bg-green">
	                      <div class="inner">

	                        <h2>
	                          <?php
	                            foreach ($ongoingProjectProgress as $row)
	                            {
	                              if ($value['PROJECTID'] == $row['projects_PROJECTID'])
	                              {
	                                echo $row['projectProgress'];
	                              }
	                            } ?>%</h2>

                          <form class="dept" action = 'monitorDepartment'  method="POST">
	                        </form>

	                        <p class="title"><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>
	                          <?php echo $value['datediff'] +1;?>
	                          <?php if(($value['datediff'] +1) > 1):?>
	                            days remaining
	                          <?php else:?>
	                            day remaining
	                          <?php endif;?>
	                        </i></p>
	                      </div>
	                      <div class="icon" style="margin-top:25px;">
	                        <i class="ion ion-clipboard"></i>
	                      </div>
	                    </div>
	                    </a>
	                  </div>
	                  <!-- ./col -->
	                <?php endforeach;?>
	              </div>

	              <div class = "projectsGrid" id = "plannedProjGrid">
	                <?php foreach ($plannedProjects as $row):?>
	                  <div class="col-lg-3 col-xs-6">
	                    <!-- small box -->
	                    <a class = "project clickable" data-id = "<?php echo $row['PROJECTID']; ?>">
	                    <div class="small-box bg-yellow">
	                      <div class="inner">
	                        <h2 class='title'><?php echo $row['PROJECTTITLE']; ?></h2>

	                        <form class="dept" action = 'monitorDepartment'  method="POST">
	                        </form>

	                        <?php //Compute for days remaining
	          							if($row['PROJECTADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
	          								$startdate = date_create($row['PROJECTSTARTDATE']);
	          							else
	          								$startdate = date_create($row['PROJECTADJUSTEDSTARTDATE']);
	                        // $startdate = date_create($row['PROJECTSTARTDATE']);
	                        ?>
	                        <p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Launch in
	                          <?php echo $row['datediff'] +1;?>
	                          <?php if(($row['datediff'] +1) > 1):?>
	                            days
	                          <?php else:?>
	                            day
	                          <?php endif;?>
	                        </i></p>
	                      </div>
	                      <div class="icon" style="margin-top:25px;">
	                        <i class="ion ion-lightbulb"></i>
	                      </div>
	                    </div>
	                    </a>
	                  </div>
	                  <!-- ./col -->
	                <?php endforeach;?>
	              </div>
	            </div>
	          </div>
	          <!-- ./myProjectsGridView -->

						<!-- LIST VIEW -->
						<div id="myProjectsListView">
							<div class="box">
								<!-- /.box-header -->
								<div class="box-body">
									<table id="projectList" class="table table-hover no-margin">
										<thead>
											<tr>
												<th width="1%"></th>
												<th>Project Title</th>
												<th class='text-center'>Start Date</th>
												<th class='text-center'>Target End Date</th>
												<th class='text-center'>Progress</th>
											</tr>
										</thead>

										<tbody>

											<tr class="project completedProjList">
												<?php foreach ($completedProjects as $key=> $value):?>

													<?php // to fix date format
														$completedStart = date_create($value['PROJECTSTARTDATE']);
														$completedEnd = date_create($value['PROJECTENDDATE']);
													?>

													<tr class="project completedProjList" data-id = "<?php echo $value['PROJECTID']; ?>">

														<form class="gantt" action = 'projectGantt'  method="POST">
														</form>

														<td class="bg-teal"></td>
														<td><?php echo $value['PROJECTTITLE']; ?></td>
														<td align='center'><?php echo date_format($completedStart, "M d, Y");?></td>
														<td align='center'><?php echo date_format($completedEnd, "M d, Y");?></td>
														<td align='center'>100%</td>
													</tr>
												<?php endforeach;?>
											</tr>

											<tr class="project delayedProjList">

												<?php foreach ($delayedProjects as $key=> $value):?>

													<?php // to fix date format
														$delayedStart = date_create($value['PROJECTSTARTDATE']);
														$delayedEnd = date_create($value['PROJECTENDDATE']);
													?>

														<tr class="project delayedProjList" data-id = "<?php echo $value['PROJECTID']; ?>">

															<form class="gantt" action = 'projectGantt'  method="POST">
															</form>

															<td class="bg-red"></td>
															<td><?php echo $value['PROJECTTITLE']; ?></td>
															<td align='center'><?php echo date_format($delayedStart, "M d, Y");?></td>
															<td align='center'><?php echo date_format($delayedEnd, "M d, Y");?></td>
															<td align='center'>
															<?php
																foreach ($delayedProjectProgress as $row)
																{
																	if ($value['PROJECTID'] == $row['projects_PROJECTID'])
																	{
																		echo $row['projectProgress'];
																	}
																} ?>%</td>
													</tr>
												<?php endforeach;?>
											</tr>

											<tr class="project ongoingProjList">

											<?php foreach ($ongoingProjects as $key=> $value):?>

												<?php // to fix date format
													$ongoingStart = date_create($value['PROJECTSTARTDATE']);
													$ongoingEnd = date_create($value['PROJECTENDDATE']);
												?>

													<tr class="project ongoingProjList" data-id = "<?php echo $value['PROJECTID']; ?>">

														<form class="gantt" action = 'projectGantt'  method="POST">
														</form>

														<td class="bg-green"></td>
														<td><?php echo $value['PROJECTTITLE']; ?></td>
														<td align='center'><?php echo date_format($ongoingStart, "M d, Y");?></td>
														<td align='center'><?php echo date_format($ongoingEnd, "M d, Y");?></td>
														<td align='center'>
														<?php
															foreach ($ongoingProjectProgress as $row)
															{
																if ($value['PROJECTID'] == $row['projects_PROJECTID'])
																{
																	echo $row['projectProgress'];
																}
															} ?>%</td>
													</tr>
												<?php endforeach;?>
											</tr>

											<tr class="project plannedProjList">
												<?php foreach ($plannedProjects as $row):?>

													<tr class="project plannedProjList" data-id = "<?php echo $row['PROJECTID']; ?>">

													<?php // to fix date format
														$plannedStart = date_create($row['PROJECTSTARTDATE']);
														$plannedEnd = date_create($row['PROJECTENDDATE']);
													?>


														<form class="gantt" action = 'projectGantt'  method="POST">
														</form>

														<td class="bg-yellow"></td>
														<td><?php echo $row['PROJECTTITLE']; ?></td>
														<td align='center'><?php echo date_format($plannedStart, "M d, Y");?></td>
														<td align='center'><?php echo date_format($plannedEnd, "M d, Y");?></td>
														<td align='center'>0.00%</td>
													</tr>
												<?php endforeach;?>
											</tr>


											<!-- <?php foreach ($parkedProjects as $key=> $value):?>

												<?php // to fix date format
													$parkedStart = date_create($value['PROJECTSTARTDATE']);
													$parkedEnd = date_create($value['PROJECTENDDATE']);
												?>

												<tr class="project parkedProjList" data-id = "<?php echo $value['PROJECTID']; ?>">

													<form class="gantt" action = 'projectGantt'  method="POST">
													</form>

													<td class="btn-info"></td>
													<td><?php echo $value['PROJECTTITLE']; ?></td>
													<td><?php echo date_format($parkedStart, "M d, Y");?></td>
													<td><?php echo date_format($parkedEnd, "M d, Y");?></td>
													<td>
														<?php
															foreach ($parkedProjectProgress as $row)
															{
																if ($value['PROJECTID'] == $row['projects_PROJECTID'])
																{
																	echo $row['projectProgress'];
																}
															} ?>%</td>
												</tr>
											<?php endforeach;?>

											<?php foreach ($draftedProjects as $key=> $value):?>

												<?php // to fix date format
													$draftedStart = date_create($value['PROJECTSTARTDATE']);
													$draftedEnd = date_create($value['PROJECTENDDATE']);
												?>

												<tr class="project draftedProjList" data-id = "<?php echo $value['PROJECTID']; ?>">

													<form class="gantt" action = 'projectGantt'  method="POST">
													</form>

													<td class="bg-maroon"></td>
													<td><?php echo $value['PROJECTTITLE']; ?></td>
													<td><?php echo date_format($draftedStart, "M d, Y");?></td>
													<td><?php echo date_format($draftedEnd, "M d, Y");?></td>
													<td>0.00%</td>
												</tr>
											<?php endforeach;?> -->
										</tbody>
									</table>
								</div>
								<!-- /.box-body -->
							</div>
							<!-- /.box -->
						</div>
						<!-- /.myProjectListView -->
					<!-- END OF LIST VIEW -->
				</section>
				<!-- /.content -->
			</div>
			<?php require("footer.php"); ?>
		</div>
		<!-- ./wrapper -->
		<script>
			$("#monitor").addClass("active");
			$("#monitorProject").addClass("active");
			$("#buttonGridProjects").hide();
			$("#myProjectsListView").hide();


			$(document).on("click", ".project", function() {
	      var $id = $(this).attr('data-id');
	      $(".dept").attr("name", "formSubmit");
	      $(".dept").append("<input type='hidden' name='project_ID' value= " + $id + ">");
	      $(".dept").submit();
	    });

			$("#filterAll").addClass('active');

			// FILTER
			$(document).on("click", ".filter", function(e) {
				$(".filter").removeClass('active');
				$(this).addClass('active');

				filterProjects(e.target.id);

			});

			function filterProjects(selectedFilter)
			{

				if(selectedFilter == "filterAll")
					$(".emptyProjects").hide();
				else
					$(".emptyProjects").show();

				switch(selectedFilter)
				{
					case "filterAll": var filter = 'all'; break;
					case "filterCompleted": var filter = 'completed'; break;
					case "filterDelayed": var filter = 'delayed'; break;
					case "filterOngoing": var filter = 'ongoing'; break;
					case "filterPlanned": var filter = 'planned'; break;
					case "filterParked": var filter = 'parked'; break;
					case "filterDrafted": var filter = 'drafted'; break;
				}

				if($("#myProjectsListView").css("display") == 'none')
	      {
	        // alert("My Projects in Grid View");
	        $(".projectsGrid").hide();

	        if(filter == 'all')
	          $(".projectsGrid").show();
	        else
	        {
	          if($.trim( $('#' + filter + 'ProjGrid').text() ).length == 0) // check if empty
	            $('#' + filter + 'ProjGrid').html("<h3 class = 'emptyProjects' align = 'center'>There are no " + filter + " projects</h3>");
	          $('#' + filter + 'ProjGrid').show();
	        }
	      }
				else
	      {
	        // alert("My Projects in List View");
	        $(".project").hide();

	        if(filter == 'all')
	          $(".project").show();
	        else
					{
						if($.trim( $('.' + filter + 'ProjList').text() ).length == 0) // check if empty
						{
							$("." + filter + "ProjList").html("<td class = 'emptyProjects' colspan='5' align='center'> There are no " + filter + " projects</td>");
						}
						$("." + filter + "ProjList").show();
					}
	      }
			}

			// TOGGLE GRID AND LIST
			$("#buttonListProjects").on("click", function(){
	      $("#myProjectsListView").show();
	      $("#myProjectsGridView").hide();

				$("#buttonGridProjects").show();
				$("#buttonListProjects").hide();
			});

			$("#buttonGridProjects").on("click", function(){
				$("#myProjectsGridView").show();
				$("#myProjectsListView").hide();

				$("#buttonListProjects").show();
				$("#buttonGridProjects").hide();
			});



		</script>
	</body>
</html>

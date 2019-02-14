<html>

  <head>
    <title>Kernel - My Projects</title>

    <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myProjectsStyle.css")?>">
  </head>

  <body class="hold-transition skin-red sidebar-mini fixed">
    <?php require("frame.php"); ?>

    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1 id="myProjectsHeader">
          My Projects
          <small>What are my projects?</small>
        </h1>

        <h1 id="myTeamHeader">
          My Team Projects
          <small>What is my team working on?</small>
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
          <a href="#" id = "buttonListProjects" class="btn btn-default btn" data-toggle="tooltip" data-placement="top" title="List View"><i class="fa fa-th-list"></i>
          <a href="#" id = "buttonGridProjects" class="btn btn-default btn" data-toggle="tooltip" data-placement="top" title="Grid View"><i class="fa fa-th-large"></i></a>

          <!-- <a href="#" id = "buttonListProjects" class="btn btn-default btn"><i class="fa fa-bars"></i>
          <a href="#" id = "buttonGridProjects" class="btn btn-default btn"><i class="fa fa-clone"></i></a> -->
        </div>

        <div id="divShowMyTeam" class="pull-right">
          <a href="#" id = "showMyTeam" class="btn btn-default btn" data-toggle="tooltip" data-placement="top" title="My Team"><i class="fa fa-users" ></i></a>
        </div>

        <!-- TOGGLE MY TEAM -->
        <div id = "divGridListMyTeam" class="pull-right">
          <a href="#" id = "buttonListTeam" class="btn btn-default btn" data-toggle="tooltip" data-placement="top" title="List View"><i class="fa fa-th-list"></i>
          <a href="#" id = "buttonGridTeam" class="btn btn-default btn" data-toggle="tooltip" data-placement="top" title="Grid View"><i class="fa fa-th-large"></i></a>
        </div>

        <div id="divShowMyProjects" class="pull-right">
          <a href="#" id = "showMyProjects" class="btn btn-default btn" data-toggle="tooltip" data-placement="top" title="My Projects"><i class="fa fa-briefcase"></i></a>
        </div>

        <div> <!-- SORT/LEGEND -->
          <button type="button" id = "filterAll" class="btn btn-default filter">All</button>
          <button type="button" id = "filterCompleted" class="btn bg-teal filter">Completed</button>
          <button type="button" id = "filterOngoing" class="btn btn-success filter">Ongoing</button>
          <button type="button" id = "filterDelayed" class="btn btn-danger filter">Delayed</button>
          <button type="button" id = "filterPlanned" class="btn btn-warning filter">Planned</button>
          <!-- <button type="button" id = "filterParked" class="btn btn-info filter">Parked</button> -->
          <!-- <button type="button" id = "filterDrafted" class="btn bg-maroon filter">Draft</button> -->
        </div>

        <br><br>

        <?php if($_SESSION['usertype_USERTYPEID'] != 5):?>
          <div class="row" id="createProject">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <a href="<?php echo base_url("index.php/controller/addProjectDetails"); ?>">
              <div class="small-box bg-blue">
                <div class="inner">
                  <h2>Create</h2>
                  <p>New<br>Project</p>
                </div>
                <div class="icon" style="margin-top:25px;">
                  <i class="ion ion-plus"></i>
                </div>

                <!-- <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div> -->
              </div>
              </a>
            </div>
            <!-- ./col -->

            <?php if($templates != null):?>
              <?php foreach($templates as $template):?>
              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <a class = "templateProj clickable" data-id = "<?php echo $template['projects_PROJECTID']; ?>">
                <div class="small-box bg-purple">
                  <div class="inner">
                    <form id = 'template' action = 'projectGantt'  method="POST">
                      <input type='hidden' name='myProjects' value= "0">
                    </form>
                    <h2 class="title"><?php echo $template['PROJECTTITLE'];?></h2>
                    <?php
                      $enddate = date_create($template['PROJECTACTUALENDDATE']);
                    ;?>
                    <p>Completed on<b></b><br><i><?php echo date_format($enddate, "F d, Y");?></i></p>
                  </div>
                  <div class="icon" style="margin-top:25px;">
                    <i class="ion ion-folder"></i>
                  </div>
                </div>
                </a>
              </div>
            <?php endforeach;?>
          <?php endif;?>

          </div>

          <hr id="hrCreateProject" style="height:1px; background-color:black">
        <?php endif;?>

        <!-- PROJECT VIEW -->
        <div id="projectView">

          <div id="myProjectsGridView">

            <div class="row">

              <!-- <?php if($completedProjects == null && $delayedProjects == null &&
                      $ongoingProjects == null && $plannedProjects == null &&
                      $parkedProjects == null && $draftedProjects == null):?>
                <h3 class = "projects" align="center">There are no projects</h3>
              <?php endif;?> -->

              <div class = "projectsGrid" id = "completedProjGrid">
                <?php foreach ($completedProjects as $key=> $value):?>

                  <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <a class = "project clickable" data-id = "<?php echo $value['PROJECTID']; ?>">
                    <div class="small-box bg-teal">
                      <div class="inner">
                        <h2>100%</h2>

                        <form action = 'projectGantt'  method="POST">
                        </form>

                        <p class="title"><b><?php echo $value['PROJECTTITLE']; ?></b>
                          <br><i>Archiving in
                          <?php echo $value['datediff'] +1;?>
                          <?php if(($value['datediff'] +1) > 1):?>
                            days
                          <?php else:?>
                            day
                          <?php endif;?>
                        </i>
                      </p>
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

                        <form class="gantt" action = 'projectGantt'  method="POST">
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
                  <?php if ($value['datediff'] >= 0): ?>

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

                            <form class="gantt" action = 'projectGantt'  method="POST">
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
                <?php endif; ?>
                <?php endforeach;?>
              </div>

              <div class = "projectsGrid" id = "plannedProjGrid">
                <?php foreach ($plannedProjects as $row):?>
                  <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <a class = "project clickable" data-id = "<?php echo $row['PROJECTID']; ?>">
                    <div class="small-box bg-yellow">
                      <div class="inner">
                        <h2 class="title"><?php echo $row['PROJECTTITLE']; ?></h2>

                        <form class="gantt" action = 'projectGantt'  method="POST">
                        </form>

                        <?php //Compute for days remaining
          							if($row['PROJECTADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
          								$startdate = date_create($row['PROJECTSTARTDATE']);
          							else
          								$startdate = date_create($row['PROJECTADJUSTEDSTARTDATE']);
                        // $startdate = date_create($row['PROJECTSTARTDATE']);
                        ?>
                        <p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Launch in
                          <?php echo $row['datediff'];?>
                          <?php if(($row['datediff']) > 1):?>
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

              <!-- <div class = "projectsGrid" id = "parkedProjGrid">
                <?php foreach ($parkedProjects as $key=> $value):?>

                  <div class="col-lg-3 col-xs-6"> -->
                    <!-- small box -->
                    <!-- <a class = "project clickable" data-id = "<?php echo $value['PROJECTID']; ?>">
                    <div class="small-box btn-info">
                      <div class="inner">

                        <h2>
                          <?php
                            foreach ($parkedProjectProgress as $row)
                            {
                              if ($value['PROJECTID'] == $row['projects_PROJECTID'])
                              {
                                echo $row['projectProgress'];
                              }
                            } ?>%</h2>

                        <form class="gantt" action = 'projectGantt'  method="POST">
                        </form>

                        <p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>Parked</i></p>
                      </div>
                      <div class="icon" style="margin-top:25px;">
                        <i class="ion ion-clock"></i>
                      </div>
                    </div>
                    </a>
                  </div> -->
                  <!-- ./col -->
                <!-- <?php endforeach;?>
              </div> -->

              <!-- <div class = "projectsGrid" id = "draftedProjGrid">
                <?php foreach ($draftedProjects as $row):?>
                  <div class="col-lg-3 col-xs-6"> -->
                    <!-- small box -->
                    <!-- <a class = "project clickable" data-id = "<?php echo $row['PROJECTID']; ?>">
                      <div id="draftBox" class="small-box bg-maroon">
                        <div class="inner">
                          <h2 class="title"><?php echo $row['PROJECTTITLE']; ?></h2>

                          <form class="gantt" action = 'projectGantt'  method="POST">
                          </form>

                          <?php //Compute for days remaining
                          $startdate = date_create($row['PROJECTSTARTDATE']);
                          ?>
                          <p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Draft</i></p>
                        </div>
                        <div class="icon" style="margin-top:25px;">
                          <i class="ion ion-clock"></i>
                        </div>
                      </div>
                    </a>
                  </div> -->
                  <!-- ./col -->
                <!-- <?php endforeach;?>
              </div> -->

            </div>
          </div>
          <!-- ./myProjectsGridView -->

          <div id="myProjectsListView">
            <div class="box">
              <div class="box-header" style="display:inline-block">
                <h3 class="box-title">
                  <a href="<?php echo base_url("index.php/controller/addProjectDetails"); ?>">
                    <button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Create Project</button>
                  </a>
                </h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <table id="projectList" class="table no-margin table-hover">
                  <thead>
                    <tr>
                      <th width="1%"></th>
                      <th>Project Title</th>
                      <th class=text-center>Start Date</th>
                      <th class=text-center>Target End Date</th>
                      <th class=text-center>Progress</th>
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
                          <td class=text-center><?php echo date_format($completedStart, "M d, Y");?></td>
                          <td class=text-center><?php echo date_format($completedEnd, "M d, Y");?></td>
                          <td class=text-center>100%</td>
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
                            <td class=text-center><?php echo date_format($delayedStart, "M d, Y");?></td>
                            <td class=text-center><?php echo date_format($delayedEnd, "M d, Y");?></td>
                            <td class=text-center>
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
                        <?php if ($value['datediff'] >= 0): ?>

                        <?php // to fix date format
                          $ongoingStart = date_create($value['PROJECTSTARTDATE']);
                          $ongoingEnd = date_create($value['PROJECTENDDATE']);
                        ?>

                        <tr class="project ongoingProjList" data-id = "<?php echo $value['PROJECTID']; ?>">

                          <form class="gantt" action = 'projectGantt'  method="POST">
                          </form>

                          <td class="bg-green"></td>
                          <td><?php echo $value['PROJECTTITLE']; ?></td>
                          <td class=text-center><?php echo date_format($ongoingStart, "M d, Y");?></td>
                          <td class=text-center><?php echo date_format($ongoingEnd, "M d, Y");?></td>
                          <td class=text-center>
                          <?php
                            foreach ($ongoingProjectProgress as $row)
                            {
                              if ($value['PROJECTID'] == $row['projects_PROJECTID'])
                              {
                                echo $row['projectProgress'];
                              }
                            } ?>%</td>
                        </tr>
                      <?php endif; ?>
                      <?php endforeach;?>
                    </tr>

                    <tr class="project plannedProjList">

                      <?php foreach ($plannedProjects as $row):?>

                        <?php // to fix date format
                          $plannedStart = date_create($row['PROJECTSTARTDATE']);
                          $plannedEnd = date_create($row['PROJECTENDDATE']);
                        ?>

                        <tr class="project plannedProjList" data-id = "<?php echo $row['PROJECTID']; ?>">

                          <form class="gantt" action = 'projectGantt'  method="POST">
                          </form>

                          <td class="bg-yellow"></td>
                          <td><?php echo $row['PROJECTTITLE']; ?></td>
                          <td class=text-center><?php echo date_format($plannedStart, "M d, Y");?></td>
                          <td class=text-center><?php echo date_format($plannedEnd, "M d, Y");?></td>
                          <td class=text-center>0.00%</td>
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
        </div>
        <!-- END OF PROJECT VIEW -->


        <!-- TEAM VIEW -->
        <div id="teamView">

          <div id="myTeamGridView">
            <div class="row">

              <div class = "teamGrid" id = "completedTeamGrid">
                <?php foreach ($completedProjects as $key=> $value):?>

  								<div class="col-lg-3 col-xs-6">
  									<!-- small box -->
  									<a class = "myTeam clickable" data-id = "<?php echo $value['PROJECTID']; ?>">
  									<div class="small-box bg-teal">
  										<div class="inner">

  											<h2>100%</h2>

  											<form class="teamgantt" action = 'teamGantt'  method="POST">
  											</form>

  											<p class="title"><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>Archiving in <?php echo $value['datediff'] +1;?>
                          <?php if($value['datediff'] +1 > 1) :?>
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

              <div class = "teamGrid" id = "delayedTeamGrid">
  							<?php foreach ($delayedProjects as $key=> $value):?>

  								<div class="col-lg-3 col-xs-6">
  									<!-- small box -->
  									<a class = "myTeam clickable" data-id = "<?php echo $value['PROJECTID']; ?>">
  									<div class="small-box bg-red">
  										<div class="inner">

  											<h2>
  												<?php
  													foreach ($delayedTeamProjectProgress as $row)
  													{
  														if ($value['PROJECTID'] == $row['projects_PROJECTID'])
  														{
  															echo $row['projectProgress'];
  														}
  													}
  												?>%</h2>

                        <form class="teamgantt" action = 'teamGantt'  method="POST">
  											</form>

  											<p><b class="title"><?php echo $value['PROJECTTITLE']; ?></b><br><i><?php echo $value['datediff'];?>
                          <?php if($value['datediff'] > 1) :?>
                            days
                          <?php else:?>
                            day
                          <?php endif;?>
                          delayed
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

              <div class = "teamGrid" id = "ongoingTeamGrid">
  							<?php foreach ($ongoingProjects as $key=> $value):?>
                  <?php if ($value['datediff'] >= 0): ?>

  								<div class="col-lg-3 col-xs-6">
  									<!-- small box -->
  									<a class = "myTeam clickable" data-id = "<?php echo $value['PROJECTID']; ?>">
  									<div class="small-box bg-green">
  										<div class="inner">

  											<h2>
  												<?php
  													foreach ($ongoingTeamProjectProgress as $row)
  													{
  														if ($value['PROJECTID'] == $row['projects_PROJECTID'])
  														{
  															echo $row['projectProgress'];
  														}
  													}
  												?>%</h2>

                        <form class="teamgantt" action = 'teamGantt'  method="POST">
  											</form>

  											<p><b class="title"><?php echo $value['PROJECTTITLE']; ?></b><br><i><?php echo $value['datediff'] +1;?>
                          <?php if($value['datediff'] > 1) :?>
                            days
                          <?php else:?>
                            day
                          <?php endif;?>
                        remaining</i></p>
  										</div>
  										<div class="icon" style="margin-top:25px;">
  											<i class="ion ion-clipboard"></i>
  										</div>
  									</div>
  								</a>
  								</div>
  								<!-- ./col -->
                <?php endif; ?>
  							<?php endforeach;?>
              </div>

              <div class = "teamGrid" id = "plannedTeamGrid">
  							<?php foreach ($plannedProjects as $row):?>
  								<div class="col-lg-3 col-xs-6">
  									<!-- small box -->
  									<a class = "myTeam clickable" data-id = "<?php echo $row['PROJECTID']; ?>">
  									<div class="small-box bg-yellow">
  										<div class="inner">
  											<h2 class="title"><?php echo $row['PROJECTTITLE']; ?></h2>

                        <form class="teamgantt" action = 'teamGantt'  method="POST">
  											</form>

  											<?php //Compute for days remaining
  											$startdate = date_create($row['PROJECTSTARTDATE']);
  											?>
  											<p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Launch in <?php echo $row['datediff'] +1;?>
                          <?php if($value['datediff']+1 > 1) :?>
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

              <!-- <div class = "teamGrid" id = "parkedTeamGrid">
  							<?php foreach ($parkedProjects as $key=> $value):?>

  								<div class="col-lg-3 col-xs-6"> -->
  									<!-- small box -->
  									<!-- <a class = "myTeam clickable" data-id = "<?php echo $value['PROJECTID']; ?>">
  									<div class="small-box btn-info">
  										<div class="inner">

  											<h2>
  												<?php
  													foreach ($parkedTeamProjectProgress as $row)
  													{
  														if ($value['PROJECTID'] == $row['projects_PROJECTID'])
  														{
  															echo $row['projectProgress'];
  														}
  													}
  												?>%</h2>

                        <form class="teamgantt" action = 'teamGantt'  method="POST">
  											</form>

  											<p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>Parked</i></p>
  										</div>
  										<div class="icon" style="margin-top:25px;">
  											<i class="ion ion-clock"></i>
  										</div>
  									</div>
  								</a>
  								</div> -->
  								<!-- ./col -->
  							<!-- <?php endforeach;?>
              </div>

              <div class = "teamGrid" id = "draftedTeamGrid">
  							<?php foreach ($draftedProjects as $row):?>
  								<div class="col-lg-3 col-xs-6"> -->
  									<!-- small box -->
  									<!-- <a class = "myTeam clickable" data-id = "<?php echo $row['PROJECTID']; ?>">
  									<div id="draftBox" class="small-box bg-maroon">
  										<div class="inner">
  											<h2 class="title"><?php echo $row['PROJECTTITLE']; ?></h2>

                        <form class="teamgantt" action = 'teamGantt'  method="POST">
  											</form>

  											<?php //Compute for days remaining
  											$startdate = date_create($row['PROJECTSTARTDATE']);
  											?>
  											<p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Draft</i></p>
  										</div>
  										<div class="icon" style="margin-top:25px;">
  											<i class="ion ion-clock"></i>
  										</div>
  									</div>
  								</a>
  								</div> -->
  								<!-- ./col -->
  							<!-- <?php endforeach;?>
              </div> -->

						</div>
          </div>

          <div id="myTeamListView">
            <div class="box">
              <div class="box-header" style="display:inline-block">
                <h3 class="box-title">
                  <a href="<?php echo base_url("index.php/controller/addProjectDetails"); ?>">
                    <button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Create Project</button>
                  </a>
                </h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <table id="projectList" class="table no-margin table-hover">
                  <thead>
                  <tr>
                    <th width="1%"></th>
                    <th>Project Title</th>
                    <th class=text-center>Start Date</th>
                    <th class=text-center>Target End Date</th>
                    <th class=text-center>Progress</th>
                  </tr>
                  </thead>

                  <tbody>

                    <tr class="myTeam completedTeamList clickable">

                      <?php foreach ($completedProjects as $key=> $value):?>

                          <?php // to fix date format
                            $completedStart = date_create($value['PROJECTSTARTDATE']);
                            $completedEnd = date_create($value['PROJECTENDDATE']);
                          ?>

                        <tr class="myTeam completedTeamList clickable" data-id = "<?php echo $value['PROJECTID']; ?>">

                          <form class="teamgantt" action = 'teamGantt'  method="POST">
                          </form>

                          <td class="bg-teal"></td>
                          <td><?php echo $value['PROJECTTITLE']; ?></td>
                          <td class=text-center><?php echo date_format($completedStart, "M d, Y");?></td>
                          <td class=text-center><?php echo date_format($completedEnd, "M d, Y");?></td>
                          <td class=text-center>100%</td>
                        </tr>
                      <?php endforeach;?>
                    </tr>

                    <tr class="myTeam delayedTeamList clickable">

                      <?php foreach ($delayedProjects as $key=> $value):?>

                        <?php // to fix date format
                          $delayedStart = date_create($value['PROJECTSTARTDATE']);
                          $delayedEnd = date_create($value['PROJECTENDDATE']);
                        ?>

                      <tr class="myTeam delayedTeamList clickable" data-id = "<?php echo $value['PROJECTID']; ?>">

                        <form class="teamgantt" action = 'teamGantt'  method="POST">
                        </form>

                        <td class="bg-red"></td>
                        <td><?php echo $value['PROJECTTITLE']; ?></td>
                        <td class=text-center><?php echo date_format($delayedStart, "M d, Y");?></td>
                        <td class=text-center><?php echo date_format($delayedEnd, "M d, Y");?></td>
                        <td class=text-center>
                          <?php
                            foreach ($delayedTeamProjectProgress as $row)
                            {
                              if ($value['PROJECTID'] == $row['projects_PROJECTID'])
                              {
                                echo $row['projectProgress'];
                              }
                            } ?>%</td>
                      </tr>
                    <?php endforeach;?>
                  </tr>


                  <tr class="myTeam ongoingTeamList clickable">

                    <?php foreach ($ongoingProjects as $key=> $value):?>
                      <?php if ($value['datediff'] >= 0): ?>

                      <?php // to fix date format
                        $ongoingStart = date_create($value['PROJECTSTARTDATE']);
                        $ongoingEnd = date_create($value['PROJECTENDDATE']);
                      ?>

                    <tr class="myTeam ongoingTeamList clickable" data-id = "<?php echo $value['PROJECTID']; ?>">

                      <form class="teamgantt" action = 'teamGantt'  method="POST">
                      </form>

                      <td class="bg-green"></td>
                      <td><?php echo $value['PROJECTTITLE']; ?></td>
                      <td class=text-center><?php echo date_format($ongoingStart, "M d, Y");?></td>
                      <td class=text-center><?php echo date_format($ongoingEnd, "M d, Y");?></td>
                      <td class=text-center>
                        <?php
                          foreach ($ongoingTeamProjectProgress as $row)
                          {
                            if ($value['PROJECTID'] == $row['projects_PROJECTID'])
                            {
                              echo $row['projectProgress'];
                            }
                          } ?>%</td>
                    </tr>
                  <?php endif; ?>
                  <?php endforeach; ?>
                </tr>

                <tr class="myTeam plannedTeamList clickable">

                  <?php foreach ($plannedProjects as $row):?>

                    <?php // to fix date format
                      $plannedStart = date_create($row['PROJECTSTARTDATE']);
                      $plannedEnd = date_create($row['PROJECTENDDATE']);
                    ?>

                    <tr class="myTeam plannedTeamList clickable" data-id = "<?php echo $row['PROJECTID']; ?>">

                      <form class="teamgantt" action = 'teamGantt'  method="POST">
                      </form>

                      <td class="bg-yellow"></td>
                      <td><?php echo $row['PROJECTTITLE']; ?></td>
                      <td class=text-center><?php echo date_format($plannedStart, "M d, Y");?></td>
                      <td class=text-center><?php echo date_format($plannedEnd, "M d, Y");?></td>
                      <td class=text-center>0.00%</td>
                    </tr>
                  <?php endforeach;?>
                </tr>

                <!-- <?php foreach ($parkedProjects as $key=> $value):?>

                  <?php // to fix date format
                    $parkedStart = date_create($value['PROJECTSTARTDATE']);
                    $parkedEnd = date_create($value['PROJECTENDDATE']);
                  ?>

                  <tr class="myTeam parkedTeamList clickable" data-id = "<?php echo $value['PROJECTID']; ?>">

                    <form class="teamgantt" action = 'teamGantt'  method="POST">
                    </form>

                    <td class="btn-info"></td>
                    <td><?php echo $value['PROJECTTITLE']; ?></td>
                    <td><?php echo date_format($parkedStart, "M d, Y");?></td>
                    <td><?php echo date_format($parkedEnd, "M d, Y");?></td>
                    <td>
                      <?php
                        foreach ($parkedTeamProjectProgress as $row)
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

                  <tr class="myTeam draftedTeamList clickable" data-id = "<?php echo $value['PROJECTID']; ?>">

                    <form class="teamgantt" action = 'teamGantt'  method="POST">
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
          </div>
        </div>
        <!-- END OF TEAM VIEW -->
      </section>
    </div>

      <?php require("footer.php"); ?>

    </div> <!--.wrapper closing div-->
    <!-- ./wrapper -->

    <script>

    $("#myProjects").addClass("active");
    $("#projects").addClass("active");
    $("#buttonGridProjects").hide();
    $("#teamView").hide();
    $("#myProjectsListView").hide();
    $("#divGridListMyTeam").hide();
    $("#divShowMyProjects").hide();
    $("#myTeamHeader").hide();

    // show my projects (default: grid view)
    $("#showMyProjects").on("click", function(){

      $("#projectView").show();
      $("#teamView").hide();

      $("#myProjectsGridView").show();
      $("#myProjectsListView").hide();

      $("#divGridListMyProjects").show();
      $("#divGridListMyTeam").hide();

      $("#buttonListProjects").show();
      $("#buttonGridProjects").hide();

      $("#divShowMyTeam").show();
      $("#divShowMyProjects").hide();

      $("#createProject").show();
      $("#hrCreateProject").show();

      $("#myProjectsHeader").show();
      $("#myTeamHeader").hide();

      filterProjects($(".filter.active").attr('id'));
    });

    // show my team (default: grid view)
    $("#showMyTeam").on("click", function(){
      $("#teamView").show();
      $("#projectView").hide();

      $("#myTeamGridView").show();
      $("#myTeamListView").hide();

      $("#divGridListMyTeam").show();
      $("#divGridListMyProjects").hide();

      $("#buttonListTeam").show();
      $("#buttonGridTeam").hide();

      $("#divShowMyProjects").show();
      $("#divShowMyTeam").hide();

      $("#createProject").show();
      $("#hrCreateProject").show();

      $("#myTeamHeader").show();
      $("#myProjectsHeader").hide();

      filterProjects($(".filter.active").attr('id'));
    });

    // show my projects in list view
    $("#buttonListProjects").on("click", function(){

      $("#projectView").show();
      $("#teamView").hide();

      $("#myProjectsListView").show();
      $("#myProjectsGridView").hide();

      $("#divGridListMyProjects").show();
      $("#divGridListMyTeam").hide();

      $("#buttonGridProjects").show();
      $("#buttonListProjects").hide();

      $("#divShowMyTeam").show();
      $("#divShowMyProjects").hide();

      $("#createProject").hide();
      $("#hrCreateProject").hide();

      $("#myProjectsHeader").show();
      $("#myTeamHeader").hide();

      filterProjects($(".filter.active").attr('id'));
    });

    // show my team in list view
    $("#buttonListTeam").on("click", function(){
      $("#teamView").show();
      $("#projectView").hide();

      $("#myTeamListView").show();
      $("#myTeamGridView").hide();

      $("#divGridListMyTeam").show();
      $("#divGridListMyProjects").hide();

      $("#buttonGridTeam").show();
      $("#buttonListTeam").hide();

      $("#divShowMyProjects").show();
      $("#divShowMyTeam").hide();

      $("#createProject").hide();
      $("#hrCreateProject").hide();

      $("#myTeamHeader").show();
      $("#myProjectsHeader").hide();

      filterProjects($(".filter.active").attr('id'));
    });

    // show my projects in grid view
    $("#buttonGridProjects").on("click", function(){
      $("#projectView").show();
      $("#teamView").hide();

      $("#myProjectsGridView").show();
      $("#myProjectsListView").hide();

      $("#divGridListMyProjects").show();
      $("#divGridListMyTeam").hide();

      $("#buttonListProjects").show();
      $("#buttonGridProjects").hide();

      $("#divShowMyTeam").show();
      $("#divShowMyProjects").hide();

      $("#createProject").show();
      $("#hrCreateProject").show();

      $("#myProjectsHeader").show();
      $("#myTeamHeader").hide();

      filterProjects($(".filter.active").attr('id'));
    });

    // show my team in grid view
    $("#buttonGridTeam").on("click", function(){
      $("#teamView").show();
      $("#projectView").hide();

      $("#myTeamGridView").show();
      $("#myTeamListView").hide();

      $("#divGridListMyTeam").show();
      $("#divGridListMyProjects").hide();

      $("#buttonListTeam").show();
      $("#buttonGridTeam").hide();

      $("#divShowMyProjects").show();
      $("#divShowMyTeam").hide();

      $("#createProject").show();
      $("#hrCreateProject").show();

      $("#myTeamHeader").show();
      $("#myProjectsHeader").hide();

      filterProjects($(".filter.active").attr('id'));
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

      if($("#myProjectsGridView").css("display") != 'none' && $("#myTeamListView").css("display") == 'none' && $("#myProjectsListView").css("display") == 'none')
      {
        // Team Projects in Grid View
        $(".teamGrid").hide();

        if(filter == 'all')
          $(".teamGrid").show();
        else
        {
          if($.trim( $('#' + filter + 'TeamGrid').text() ).length == 0) // check if empty
            $('#' + filter + 'TeamGrid').html("<h3 class = 'emptyProjects' align = 'center'>There are no " + filter + " projects</h3>");
          $('#' + filter + 'TeamGrid').show();
        }
      }
      else if($("#myProjectsListView").css("display") != 'none' && $("#myTeamGridView").css("display") == 'none' && $("#myProjectsGridView").css("display") == 'none')
      {
        // Team Projects in List View
        $(".myTeam").hide();

        if(filter == 'all')
          $(".myTeam").show();
        else
        {
          if($.trim( $('.' + filter + 'TeamList').text() ).length == 0) // check if empty
          {
            $("." + filter + "TeamList").html("<td class = 'emptyProjects' colspan='5' align='center'> There are no " + filter + " projects</td>");
          }
          $("." + filter + "TeamList").show();
        }
      }
      else if($("#myTeamGridView").css("display") != 'none' && $("#myProjectsListView").css("display") == 'none')
      {
        // My Projects in Grid View
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
      else if($("#myTeamListView").css("display") != 'none' && $("#myProjectsGridView").css("display") == 'none')
      {
        // My Projects in List View
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

    // IF USING POST METHOD FOR PROJECT ID
    $(document).on("click", ".project", function() {
      var $id = $(this).attr('data-id');
      $(".gantt").attr("name", "formSubmit");
      $(".gantt").append("<input type='hidden' name='project_ID' value= " + $id + ">");
      $(".gantt").submit();
    });

    $(document).on("click", ".myTeam", function() {
      var $id = $(this).attr('data-id');
      $(".teamgantt").attr("name", "formSubmit");
      $(".teamgantt").append("<input type='hidden' name='project_ID' value= " + $id + ">");
      $(".teamgantt").submit();
    });

    $(document).on("click", ".templateProj", function() {
      var $id = $(this).attr('data-id');
      $("#template").attr("name", "formSubmit");
      $("#template").append("<input type='hidden' name='project_ID' value= " + $id + ">");
      $("#template").append("<input type='hidden' name='templateProjectGantt' value='0'>");
      $("#template").submit();
    });

    </script>
  </body>
</html>

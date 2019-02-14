<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>dist/css/AdminLTE.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/select2/dist/css/select2.min.css">
  <!-- Gantt Chart style -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>css/jsgantt.css" type="text/css"/>
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- fullCalendar -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/fullcalendar/dist/fullcalendar.min.css">
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
  <!-- Animate -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>animate.css/animate.min.css"/>
  <!-- Any Gantt -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>anyChart/css/anychart-ui.min.css" type="text/css"/>
  <!-- Frame Style -->
  <link rel="stylesheet" href="<?php echo base_url("/assets/css/frameStyle.css")?>">

<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>dist/css/skins/skin-red.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="<?php echo base_url()."assets/"; ?>https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="<?php echo base_url()."assets/"; ?>https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-red sidebar-mini fixed">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <?php if($_SESSION['usertype_USERTYPEID'] == 1):?>
      <a href="<?php echo base_url("index.php/controller/dashboardAdmin"); ?>" class="logo">
    <?php else:?>
      <a href="<?php echo base_url("index.php/controller/dashboard"); ?>" class="logo">
    <?php endif;?>
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>TEI</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Taters </b>Enterprises Inc.</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="<?php echo base_url()."assets/"; ?>" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Tasks: style can be found in dropdown.less -->
          <?php if($_SESSION['usertype_USERTYPEID'] != 2 && $_SESSION['usertype_USERTYPEID'] != 1):?>

            <li class="dropdown tasks-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-flag-o"></i>
                <span id="taskCount" class="label label-success">
                  <?php echo $_SESSION['taskCount'];?>
                </span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">Your Tasks</li>
                <li>
                  <!-- inner menu: contains the actual data -->
                  <ul class="menu menuTask">

                    <?php if($_SESSION['tasks'] != NULL):?>
                      <?php foreach($_SESSION['tasks'] as $task) :?>

                        <li><!-- Task item -->
                          <a href="<?php echo base_url("index.php/controller/taskTodo"); ?>">
                            <h3>
                              <?php echo $task['TASKTITLE']; ?>
                              <small class="pull-right">
                                <?php
                                  if($task['TASKADJUSTEDENDDATE'] != ''){
                                    $endDate = date_create($task['TASKADJUSTEDENDDATE']);
                                  } else {
                                    $endDate = date_create($task['TASKENDDATE']);
                                  }
                                  echo date_format($endDate, "F d, Y");
                                ?>
                              </small>
                            </h3>
                          </a>
                        </li>

                      <?php endforeach; ?>
                    <?php endif; ?>
                  </ul>
                </li>
                <li class="footer">
                  <a href="<?php echo base_url("index.php/controller/taskTodo"); ?>">View all tasks</a>
                </li>
              </ul>
            </li>
          <?php endif;?>

          <!-- Notifications Menu -->
          <li class="dropdown notifications-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span id="notifCount" class="label label-warning">
                <?php

                  $counter = 0;

                  foreach ($_SESSION['notifications'] as $notifications) {

                    if($notifications['status'] == 'Unread'){
                      $counter++;
                    }
                  }

                  echo $counter;
                ?>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Your Notifications</li>
              <li>
                <!-- Inner Menu: contains the notifications -->
                <!-- MARKO CHANGE COLOR HERE -->
                <ul class="menu menuNotifs" style="background-color:#d6d2d2;">
                  <?php foreach ($_SESSION['notifications'] as $row): ?>

                    <form method='POST' class ='notificationForm' action='notifRedirect' style="height:0;weight:0;padding:0;margin:0;"> </form>

                    <!-- start notification -->
                    <?php if($row['STATUS'] = 'Read'): ?>
                    <!-- MARKO CHANGE COLOR HERE -->
                      <li class="notification" style="background-color:#ffffff;"
                          data-notifID="<?php echo $row['NOTIFICATIONID']; ?>"
                          data-projectID="<?php echo $row['projects_PROJECTID']; ?>"
                          data-type="<?php echo $row['TYPE']; ?>"
                          data-taskID="<?php echo $row['tasks_TASKID']; ?>">
                        <a href="#">
                          <i class="fa fa-users text-aqua"></i> <?php echo $row['DETAILS']; ?>
                        </a>
                      </li>

                    <?php else:?>

                      <li class="notification"
                          data-notifID="<?php echo $row['NOTIFICATIONID']; ?>"
                          data-projectID="<?php echo $row['projects_PROJECTID']; ?>"
                          data-type="<?php echo $row['TYPE']; ?>"
                          data-taskID="<?php echo $row['tasks_TASKID']; ?>">
                        <a href="#">
                          <i class="fa fa-users text-aqua"></i> <?php echo $row['DETAILS']; ?>
                        </a>
                      </li>

                    <?php endif;?>

                  <?php endforeach; ?>
                  <!-- end notification -->
                </ul>
              </li>
              <li class="footer"><a href="<?php echo base_url("index.php/controller/notifications"); ?>">View all</a></li>
            </ul>
          </li>
          <!-- Tasks Menu -->

          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="<?php echo base_url()."assets/"; ?>#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="<?php echo $_SESSION['IDPIC']; ?>" class="user-image" alt="User Image">

              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="<?php echo $_SESSION['IDPIC']; ?>" class="img-circle" alt="User Image">


                <p>
                  <?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']; ?>
                  <small>
                    <?php echo $_SESSION['POSITION']; ?></small>
                </p>
              </li>
              <!-- Menu Body -->

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a class="btn btn-default btn-flat" data-toggle='modal' data-target='#modal-changePassword'>Change Password</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url()."index.php/controller/logout"; ?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->

        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $_SESSION['IDPIC']; ?>" class="img-circle" alt="User Image">
        </div>

        <div class="pull-left info">
          <p><?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']; ?></p>
          <!-- Status -->
          <!-- <a href="<?php echo base_url()."assets/"; ?>#"><i class="fa fa-circle text-success"></i> Online</a> -->
          <p class="pos" style="color:gray"><?php echo $_SESSION['POSITION'] ;?></p>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Project Management</li>

        <!--IF STATEMENTS DEPENDING ON USER TYPE  -->
        <?php if($_SESSION['usertype_USERTYPEID'] == 1):?>
          <li id = 'dashboard'><a href="<?php echo base_url("index.php/controller/dashboardAdmin"); ?>"><i class="fa fa-bar-chart"></i> <span> Dashboard</span></a></li>
          <li id = 'manageUsers'><a href="<?php echo base_url("index.php/controller/manageUsers"); ?>"><i class="fa fa-users"></i> <span> Users</span></a></li>
          <li id = 'manageDepartments'><a href="<?php echo base_url("index.php/controller/manageDepartments"); ?>"><i class="fa fa-chain"></i> <span> Departments</span></a></li>
          <li id = 'manageUserTypes'><a href="<?php echo base_url("index.php/controller/manageUsertypes"); ?>"><i class="fa fa-street-view"></i> <span> User Types</span></a></li>
        <?php else:?>
         <li id = 'dashboard'><a href="<?php echo base_url("index.php/controller/dashboard"); ?>"><i class="fa fa-bar-chart"></i> <span> Dashboard</span></a></li>
        <?php if($_SESSION['usertype_USERTYPEID'] != 2 ):?> <!-- NOT TO SHOW FOR EXECUTIVE LEVEL -->
          <li id = 'myProjects'><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-briefcase"></i> <span> My Projects</span></a></li>
        <?php else:?> <!-- FOR EXECUTIVE LEVEL -->
          <li id = 'projects'><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-briefcase"></i> <span> Projects</span></a></li>

          <!-- <li id = 'projects' class="treeview">
            <a href="allprojects">
              <i class="fa fa-briefcase"></i> <span>Projects</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li id="all" class="deptProjects" data-id="0"><a><i class="fa fa-circle-o"></i> All</a></li>
              <?php foreach($departments as $department):?>
                <?php if($department['DEPARTMENTID'] != 1):?>
                  <li id="<?php $department['DEPARTMENTID'];?>" class="deptProjects" data-id="<?php echo $department['DEPARTMENTID'];?>"><a><i class="fa fa-circle-o"></i> <?php echo $department['DEPT'];?></a></li>
                <?php endif;?>
              <?php endforeach;?>
              <form id = 'deptProjects' action = 'myProjects'  method="POST">
                <input type='hidden' name='deptProjects' value= "1">
              </form>
            </ul>
          </li> -->
        <?php endif;?>

      <?php if($_SESSION['usertype_USERTYPEID'] != 5):?>
        <?php if($_SESSION['usertype_USERTYPEID'] != 2):?>
          <li id = 'tasks' class="treeview">
            <a href=" ">
              <i class="fa fa-check-square-o"></i><span> Tasks</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
              <ul class="treeview-menu">
                <li id = 'taskDelegate'><a class="menu"  href="<?php echo base_url("index.php/controller/taskDelegate"); ?>"><i class="fa fa-circle-o"></i> Delegate</a></li>
                <li id = 'taskTodo'><a class="menu" href="<?php echo base_url("index.php/controller/taskTodo"); ?>"><i class="fa fa-circle-o"></i> To Do</a></li>
              </ul>
          </li>
        <?php endif;?>

          <li id = 'monitor' class="treeview">
            <a href=" ">
              <i class="fa fa-desktop"></i><span> Monitor</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php if($_SESSION['usertype_USERTYPEID'] != 2):?>
                <li id = 'monitorProject'><a class="menu"  href="<?php echo base_url("index.php/controller/monitorProject"); ?>"><i class="fa fa-circle-o"></i> Project</a></li>
                <li id = 'monitorTeam'><a class="menu"  href="<?php echo base_url("index.php/controller/monitorTeam"); ?>"><i class="fa fa-circle-o"></i> Team</a></li>
              <?php else:?>
                <li id = 'monitorDepartments'><a class="menu"  href="<?php echo base_url("index.php/controller/monitorDepartments"); ?>"><i class="fa fa-circle-o"></i> Department</a></li>
              <?php endif;?>
              <li id = 'monitorTasks'><a class="menu"  href="<?php echo base_url("index.php/controller/taskMonitor"); ?>"><i class="fa fa-circle-o"></i> Tasks</a></li>
            </ul>
          </li>
        <?php else:?>
          <li id = 'taskTodo'><a href="<?php echo base_url("index.php/controller/taskTodo"); ?>"><i class="fa fa-check-square-o"></i> <span>To Do</span></a></li>
        <?php endif;?>

        <?php if($_SESSION['usertype_USERTYPEID'] != 2):?>
          <li id = 'rfc'><a href="<?php echo base_url("index.php/controller/rfc"); ?>"><i class="fa fa-flag"></i> <span> Change Requests</span></a></li>
        <?php endif;?>
        <!-- <li id = 'myCalendar'><a href="<?php echo base_url("index.php/controller/myCalendar"); ?>"><i class="fa fa-calendar"></i><span> My Calendar</span></a></li> -->
        <li id = 'reports'><a href="<?php echo base_url("index.php/controller/reports"); ?>"><i class="fa fa-tachometer"></i><span> Reports</span></a></li>
        <?php if($_SESSION['usertype_USERTYPEID'] != 5 && $_SESSION['usertype_USERTYPEID'] != 2):?>
          <li id = 'templates'><a href="<?php echo base_url("index.php/controller/templates"); ?>"><i class="fa fa-window-maximize"></i><span> Templates</span></a></li>
        <?php endif;?>
        <li id = 'projectArchives'><a href="<?php echo base_url("index.php/controller/archives"); ?>"><i class="fa fa-archive"></i><span> Archives</span></a></li>
        <!-- <li id = 'documents'><a href="<?php echo base_url("index.php/controller/documents"); ?>"><i class="fa fa-folder"></i><span> Documents</span></a></li> -->
        <?php endif;?>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
<!-- </div> -->
<!-- ./wrapper -->

<!-- CHANGE PASSWORD MODAL -->
<div class="modal fade" id="modal-changePassword" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id = "taskTitle">Change Password</h2>
      </div>
      <div class="modal-body" style="text-align:center">
        <div style="text-align:left; display:inline-block">
          <!-- <p style="color:red"><b>Your new password is new</b></p> -->
          <form name="changePassword" class="form-horizontal" action="changePassword" method="POST">
          <div class="form-group">
            <label for="oldPass" class="col-sm-6 control-label"><span style="color:red">*</span>Old Password</label>
            <div class="col-sm-6">
              <input type="password" class="form-control" name="oldPass" id="oldPass" placeholder="Enter Old Password" required>
            </div>
          </div>
          <div class="form-group">
            <label for="newPass" class="col-sm-6 control-label"><span style="color:red">*</span>New Password</label>
            <div class="col-sm-6">
              <input type="password" class="form-control" name="newPass" id="newPass" placeholder="Enter New Password" required>
            </div>
          </div>
          <div class="form-group">
            <label for="confirmPass" class="col-sm-6 control-label"><span style="color:red">*</span>Confirm New Password</label>
            <div class="col-sm-6">
              <input type="password" class="form-control" name="confirmPass" id="confirmPass" placeholder="Confirm New Password" required>
            </div>
          </div>
          <p><span style="color:red">*</span><small>Required</small></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
        <button type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
      </form>
      </div>
    </div>
  </div>
</div>
<!-- CHANGE PASSWORD MODAL -->

<!-- REQUIRED JS SCRIPTS -->


<!-- jQuery 3 -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()."assets/"; ?>dist/js/adminlte.min.js"></script>
<!-- date-range-picker -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url()."assets/"; ?>bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()."assets/"; ?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- fullCalendar -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/moment/moment.js"></script>
<script src="<?php echo base_url()."assets/"; ?>bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
<!-- Bootstrap Notify -->
<script src="<?php echo base_url()."assets/"; ?>bootstrap-notify-3.1.3/dist/bootstrap-notify.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/fastclick/lib/fastclick.js"></script>
<!-- jQuery Knob -->
<script src="<?php echo base_url()."assets/"; ?>bower_components/jquery-knob/js/jquery.knob.js"></script>
<!-- Any Chart -->
<script src="<?php echo base_url()."assets/"; ?>anyChart/js/anychart-bundle.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()."assets/"; ?>anyChart/js/anychart-base.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()."assets/"; ?>anyChart/js/anychart-core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()."assets/"; ?>anyChart/js/anychart-gantt.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()."assets/"; ?>anyChart/js/anychart-treemap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()."assets/"; ?>anyChart/js/anychart-table.min.js" type="text/javascript"></script>
<!-- Progress Circle -->
<script src="<?php echo base_url()."assets/"; ?>progress-circle/progresscircle.js" type="text/javascript"></script>

<!-- Bootstrap 4.1.1 -->
<!-- <script src="<?php echo base_url()."assets/"; ?>bower_components/bootstrap-4.1.1/dist/js/bootstrap.min.js"></script> -->

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
  <script>

  // ALERTS
  <?php if (isset($_SESSION['alertMessage'])): ?>
      $(document).ready(function()
      {
        <?php if (isset($_SESSION['danger'])): ?>
          $(document).ready(function()
          {
            dangerAlert();
          });

        <?php elseif (isset($_SESSION['success'])): ?>

          $(document).ready(function()
          {
            successAlert();
          });

        <?php elseif (isset($_SESSION['info'])): ?>

          $(document).ready(function()
          {
            infoAlert();
          });

        <?php elseif (isset($_SESSION['warning'])): ?>

          $(document).ready(function()
          {
            warningAlert();
          });

        <?php endif; ?>
      });
  <?php endif; ?>

    function successAlert ()
    {
      $.notify({
        // options
        icon: 'fa fa-check',
        message: ' <?php if (isset($_SESSION['alertMessage'])) echo $_SESSION['alertMessage']; ?>'
        },{
        // settings
        type: 'success',
        offset: 60,
        delay: 5000,
        placement: {
          from: "top",
          align: "center"
        },
        animate: {
          enter: 'animated fadeInDownBig',
          exit: 'animated fadeOutUpBig'
        },
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
          '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
          '<span data-notify="icon"></span>' +
          '<span data-notify="title">{1}</span>' +
          '<span data-notify="message">{2}</span>' +
        '</div>'
        });
    };

    function dangerAlert ()
    {
      $.notify({
        // options
        icon: 'fa fa-ban',
        message: ' <?php if (isset($_SESSION['alertMessage'])) echo $_SESSION['alertMessage']; ?>'
        },{
        // settings
        type: 'danger',
        offset: 60,
        delay: 5000,
        placement: {
          from: "top",
          align: "center"
        },
        animate: {
          enter: 'animated fadeInDownBig',
          exit: 'animated fadeOutUpBig'
        },
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
          '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
          '<span data-notify="icon"></span>' +
          '<span data-notify="title">{1}</span>' +
          '<span data-notify="message">{2}</span>' +
        '</div>'
        });
    };

    function warningAlert ()
    {
      $.notify({
        // options
        icon: 'fa fa-warning',
        message: ' <?php if (isset($_SESSION['alertMessage'])) echo $_SESSION['alertMessage']; ?>'
        },{
        // settings
        type: 'warning',
        offset: 60,
        delay: 5000,
        placement: {
          from: "top",
          align: "center"
        },
        animate: {
          enter: 'animated fadeInDownBig',
          exit: 'animated fadeOutUpBig'
        },
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
          '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
          '<span data-notify="icon"></span>' +
          '<span data-notify="title">{1}</span>' +
          '<span data-notify="message">{2}</span>' +
        '</div>'
        });
    };

    function infoAlert ()
    {
      $.notify({
        // options
        icon: 'fa fa-info',
        message: ' <?php if (isset($_SESSION['alertMessage'])) echo $_SESSION['alertMessage']; ?>'
        },{
        // settings
        type: 'info',
        offset: 60,
        delay: 5000,
        placement: {
          from: "top",
          align: "center"
        },
        animate: {
          enter: 'animated fadeInDownBig',
          exit: 'animated fadeOutUpBig'
        },
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
          '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
          '<span data-notify="icon"></span>' +
          '<span data-notify="title">{1}</span>' +
          '<span data-notify="message">{2}</span>' +
        '</div>'
        });
      };

    // function checkNotif() {
    //   $.ajax({
    //     url: "<?php echo base_url("index.php/controller/getAllNotificationsByUser"); ?>",
    //     dataType: "json",
    //     success: function(data) {
    //
    //       if(data['notifications'].length > 0)
    //       {
    //         var $counter = 0;
    //         // MARKO CHANGE COLOR HERE
    //         var $color = "";
    //
    //         $('#notifCount').html("");
    //         $('.menuNotifs').html("");
    //
    //         for(i = 0; i < data['notifications'].length; i ++){
    //           if(data['notifications'][i].status == "Unread"){
    //             $counter++;
    //             // MARKO CHANGE COLOR HERE
    //             $color = "#F5FFFA";
    //           } else {
    //             $color = "#ffffff";
    //           }
    //
    //           var $notifID = data['notifications'][i].NOTIFICATIONID;
    //           var $projectID = data['notifications'][i].projects_PROJECTID;
    //           var $taskID = data['notifications'][i].tasks_TASKID;
    //           var $notifType = data['notifications'][i].TYPE;
    //
    //           $('.menuNotifs').append("<li class='notification' style='background-color:" + $color + ";'" +
    //             "data-notifID='" + $notifID + "' " +
    //             "data-projectID='" + $projectID + "' " +
    //             "data-taskID='" + $taskID + "' " +
    //             "data-type='" + $notifType + "' " + "'><a ='#'><i class='fa fa-users text-aqua'></i>" + data['notifications'][i].DETAILS + "</a></li>");
    //         }
    //
    //         $('#notifCount').html($counter);
    //       }
    //     }
    //   });
    // } setInterval(checkNotif, 150000);

    $("body").on('click', '.notification', function() {

      var $projectID = $(this).attr('data-projectID');
      var $taskID = $(this).attr('data-taskID');
      var $notifType = $(this).attr('data-type');
      var $notifID = $(this).attr('data-notifID');

      $(".notificationForm").attr("name", "formSubmit");
      $(".notificationForm").append("<input type='hidden' name='projectID' value='" + $projectID + "'>");
      $(".notificationForm").append("<input type='hidden' name='taskID' value='" + $taskID + "'>");
      $(".notificationForm").append("<input type='hidden' name='type' value='" + $notifType + "'>");
      $(".notificationForm").append("<input type='hidden' name='notifID' value='" + $notifID + "'>");
      $(".notificationForm").submit();

    });

    function checkTasks() {
      $.ajax({
        url: "<?php echo base_url("index.php/controller/getAllTasksByUser"); ?>",
        dataType: "json",
        success: function(data) {

          if(data['allTasks'].length > 0)
          {
            var $counter = data['allTasks'].length;

            $('#taskCount').html("");
            $('.menuTask').html("");

            $link = "<?php echo base_url("index.php/controller/taskTodo"); ?>"

            for(i = 0; i < $counter; i++){

              var date = new Date(data['allTasks'][i].TASKENDDATE);
      				var month = date.toLocaleDateString("en-US", {month: "short"});
      				var day = date.getDate();
      				if(day < 10){
      					day = "0"+day;
      				}
      				var year = date.getFullYear()
      				var formattedDate =  month + " " + day + ", " + year;

              $('.menuTask').append("<li><a href='" + $link + "'><h3>" +
                    data['allTasks'][i].TASKTITLE +
                  "<small class='pull-right'>" + formattedDate +
                  "</small></h3></a></li>");
            }

            $('#taskCount').html($counter);
          }
        }
      });
    } setInterval(checkTasks, 150000);

    $(function () {
      $('[data-toggle="tooltip"]').tooltip({container: 'body'});
    });

    $("body").on('click', '.deptProjects', function() {
      var $id = $(this).attr('data-id');
      $("#deptProjects").attr("name", "formSubmit");
      $("#deptProjects").append("<input type='hidden' name='dept_ID' value= " + $id + ">");
      $("#deptProjects").submit();
    });

  </script>

</body>
</html>

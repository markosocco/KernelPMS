<html>
	<head>
		<title>Admin - Manage Departments</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/archivesStyle.css")?>"> -->
	</head>
	<body>
		<?php require("frame.php"); ?>
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Manage Departments
					<small>Where do Kernel users belong?</small>
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
						<h3 class="box-title">
							<span data-toggle='modal' data-target='#modal-addDept'>
								<button id="addDept" type="button" class="btn btn-primary"
								data-toggle="tooltip" data-placement="right" title="Add Department">
									<i class="fa fa-plus-square"></i></button></span>
						</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="deptList" class="table table-hover">
							<thead>
							<tr>
								<th width="40%">Department Name</th>
								<th width="15%" class='text-center'>Shortcut</th>
								<th width="35%">Department Head</th>
								<th width="10%" class='text-center'>Action</th>
							</tr>
							</thead>
							<tbody>
								<?php foreach ($departments as $department): ?>
									<tr>
										<td><?php echo $department['DEPARTMENTNAME']; ?></td>
										<td class="text-center"><?php echo $department['DEPT']; ?></td>
										<td><?php echo $department['FIRSTNAME']; ?> <?php echo $department['LASTNAME']; ?></td>
										<td class='text-center'>
											<span data-toggle="modal"><button type="button"
												class="btn btn-primary btn-sm editBtn" data-id="<?php echo $department['DEPARTMENTID'];?>"
												data-toggle="tooltip" data-placement="top" title="Edit">
											<i class="fa fa-edit"></i></button></span>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

				<!-- ADD/EDIT DEPT MODAL -->
				<div class="modal fade" id="modal-addDept" tabindex="-1">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h2 class="modal-title" id = "modalTitle"></h2>
				      </div>
				      <div class="modal-body" style="text-align:center">
				        <div style="text-align:left; display:inline-block">
				          <form id="deptForm" name="" class="form-horizontal" action="" method="POST">
				          <div class="form-group">
				            <label for="deptName" class="col-sm-4 control-label"><span style="color:red">*</span>Department Name</label>
				            <div class="col-sm-8">
				              <input type="text" class="form-control" name="deptName" id="deptName" placeholder="Enter Department Name" value="" required>
				            </div>
				          </div>
				          <div class="form-group">
				            <label for="dept" class="col-sm-4 control-label"><span style="color:red">*</span>Department Shortcut</label>
				            <div class="col-sm-8">
				              <input type="text" class="form-control" name="dept" id="dept" maxlength="4" placeholder="Enter Department Shortcut" required>
				            </div>
				          </div>
									<div class="form-group">
				            <label for="deptHead" class="col-sm-4 control-label"><span style="color:red">*</span>Department Head</label>
										<div class="col-sm-8">
 										 <select name="deptHead" id="deptHead" class="form-control select2" required>
 											 <option disabled selected value = "0">-- Select a Department Head -- </option>
 											 <?php
 												 foreach ($deptHeads as $user) {
 													 echo "<option value=" . $user['USERID'] . ">" . $user['FIRSTNAME'] . " " . $user['LASTNAME'] . " (" . $user['DEPT'] . ")</option>";
 												 }
 											 ?>
 										 </select>
 									 </div>
				          </div>
									<div class="form-group">
				            <label for="active" class="col-sm-4 control-label"><span style="color:red">*</span>Active</label>
										<div class="col-sm-8">
 										 <select name="active" id="active" class="form-control select2" required>
 											 <option disabled selected value = "2">-- Yes or No -- </option>
											 <option value = "1">Yes</option>
											 <option value = "0">No</option>
										 </select>
 									 </div>
				          </div>
				          <p><span style="color:red">*</span><small>Required</small></p>
				        </div>
				      </div>
				      <div class="modal-footer">
				        <button id="closeAddDept" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
				        <button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
				      </form>
				      </div>
				    </div>
				  </div>
				</div>
				<!-- ADD/EDIT DEPT MODAL -->

			</section>
		</div>
		<?php require("footer.php"); ?>
		</div> <!--.wrapper closing div-->
		<script>
		$("#manageDepartments").addClass("active");

		$(function () {
			$('#deptList').DataTable({
				'paging'      : false,
				'lengthChange': false,
				'searching'   : true,
				'ordering'    : true,
				'info'        : false,
				'autoWidth'   : false
			});
		});

		$("#closeAddDept").click(function()
		{
			$("#deptHead").val("0");
			$("#active").val("2");
		});

		$("#addDept").click(function()
		{
			$("#deptForm").attr("name", "addNewDepartment");
			$("#deptForm").attr("action", "addNewDepartment");

			$("#modalTitle").html("Add New Department");
			$("#deptName").attr("value", "");
			$("#dept").attr("value", "");
			$("#deptHead").val("0");
			$("#active").val("2");
		});

		$(".editBtn").click(function()
		{
			$("#modalTitle").html("Edit Department");
			$("#deptForm").attr("name", "editDepartment");
			$("#deptForm").attr("action", "editDepartment");
			var $deptID = $(this).attr('data-id');
			$("#deptForm").append("<input type='hidden' name='dept_ID' value= " + $deptID + ">");

			$.ajax({
			 type:"POST",
			 url: "<?php echo base_url("index.php/controller/getDeptDetails"); ?>",
			 data: {dept_ID: $deptID},
			 dataType: 'json',
			 success:function($data)
			 {
				 $("#deptName").attr("value", $data['deptEdit'].DEPARTMENTNAME);
				 $("#dept").attr("value", $data['deptEdit'].DEPT);
				 $("#deptHead").val($data['deptEdit'].users_DEPARTMENTHEAD);
				 $("#active").val($data['deptEdit'].isAct);
			 },
			 error:function()
			 {
				 alert("There was a problem in retrieving the department details");
			 }
			 });

			 $("#modal-addDept").modal('show');
		});
		</script>
	</body>
</html>

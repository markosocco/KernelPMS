<html>
	<head>
		<title>Admin - Manage User Types</title>
	</head>
	<body>
		<?php require("frame.php"); ?>
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Manage User Types
					<small>What are the different users?</small>
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
							<span data-toggle='modal' data-target='#modal-addUserType'>
								<button id="addType" type="button" class="btn btn-primary"
								data-toggle="tooltip" data-placement="right" title="Add User Type">
									<i class="fa fa-plus-square"></i></button></span>
						</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="userTypeList" class="table table-hover">
							<thead>
							<tr>
								<th width="90%">User Type</th>
								<th width="10%" class='text-center'>Action</th>
							</tr>
							</thead>
							<tbody>
								<?php foreach ($userTypes as $userType): ?>
									<tr>
										<td><?php echo $userType['USERTYPE']; ?></td>
										<td class='text-center'>
											<span data-toggle="modal"><button type="button"
												class="btn btn-primary btn-sm editBtn" data-id="<?php echo $userType['USERTYPEID'];?>"
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
				<div class="modal fade" id="modal-addUserType" tabindex="-1">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h2 class="modal-title" id = "modalTitle"></h2>
				      </div>
				      <div class="modal-body" style="text-align:center">
				        <div style="text-align:left; display:inline-block">
				          <form id="userTypeForm" name="" class="form-horizontal" action="" method="POST">
				          <div class="form-group">
				            <label for="userType" class="col-sm-4 control-label"><span style="color:red">*</span>User Type</label>
				            <div class="col-sm-8">
				              <input type="text" class="form-control" name="userType" id="userType" placeholder="Enter User Type" value="" required>
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
				        <button id="closeAddType" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
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
		$("#manageUserTypes").addClass("active");

		$(function () {
			$('#userTypeList').DataTable({
				'paging'      : false,
				'lengthChange': false,
				'searching'   : true,
				'ordering'    : true,
				'info'        : false,
				'autoWidth'   : false
			});
		});

		$("#closeAddType").click(function()
		{
			$("#active").val("2");
		});

		$("#addType").click(function()
		{
			$("#userTypeForm").attr("name", "addUserType");
			$("#userTypeForm").attr("action", "addUserType");

			$("#modalTitle").html("Add New User Type");
			$("#userType").attr("value", "");
			$("#active").val("2");
		});

		$(".editBtn").click(function()
		{
			$("#modalTitle").html("Edit User Type");
			$("#userTypeForm").attr("name", "editUserType");
			$("#userTypeForm").attr("action", "editUserType");
			var $usertypeID = $(this).attr('data-id');
			$("#userTypeForm").append("<input type='hidden' name='usertype_ID' value= " + $usertypeID + ">");

			$.ajax({
			 type:"POST",
			 url: "<?php echo base_url("index.php/controller/getUserTypeDetails"); ?>",
			 data: {usertype_ID: $usertypeID},
			 dataType: 'json',
			 success:function($data)
			 {
				 $("#userType").attr("value", $data['usertypeEdit'].USERTYPE);
				 $("#active").val($data['usertypeEdit'].isAct);
			 },
			 error:function()
			 {
				 alert("There was a problem in retrieving the user type details");
			 }
			 });

			 $("#modal-addUserType").modal('show');
		});
		</script>
	</body>
</html>

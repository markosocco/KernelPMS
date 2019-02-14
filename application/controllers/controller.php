<?php

date_default_timezone_set('Asia/Manila');
defined('BASEPATH') OR exit('No direct script access allowed');

class controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->model("model");
		$this->load->library('session');
		$this->load->helper(array('form', 'url'));
		$this->load->library('email');

	}

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function send_mail() {

		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		$email = $this->input->post('emailadd');

		//Email content
		$htmlContent = '<h1>Sending email via SMTP server</h1>';
		$htmlContent .= '<p>This email has sent via SMTP server from CodeIgniter application.</p><br><br>';

		$this->email->to($email);
		$this->email->from('KernelPMS@gmail.com','Kernel Notification');
		$this->email->subject('Hello');
		$this->email->message($htmlContent);

		$this->email->send();

		$this->session->set_flashdata('success', 'alert');
		$this->session->set_flashdata('alertMessage', ' Email sent successfully');
		redirect("controller/login");
  }

	public function login()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('login');
		}

		else
		{
			$this->load->view('restrictedAccess');
		}
	}

//LOGS USER OUT AND DESTROYS SESSION
	public function logout()
	{
		unset($_SESSION);
	  session_destroy();
	  session_write_close();

		$this->load->view("login");
	}

// CHECKS IF EMAIL AND PASSWORD MATCH DB
	public function validateLogin()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'email', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			 $this->load->view('login', $this->data);
		}

		if ($this->form_validation->run() == TRUE)
		{
			$data = array(
					'email' => $this->input->post('email'),
					// 'password' => password_verify($this->input->post('password'), hashedpasswordfromDB)
					//'password' => md5($this->input->post('password'))
					'password' => $this->input->post('password')
			);

			$result = $this->model->checkDatabase($data);

			if($result == true)
			{
				$sessionData = $this->model->getUserData($data);
				$this->session->set_userdata($sessionData);

				$notifications = $this->model->getAllNotificationsByUser();
				$this->session->set_userdata('notifications', $notifications);

				$tasks = $this->model->getAllTasksByUser($_SESSION['USERID']);

				$count = 0;
				foreach ($tasks as $taskCount){ $count++; }

				$this->session->set_userdata('taskCount', $count);

				$currentDate = date('Y-m-d');
				$this->model->updateTaskStatus($currentDate);
				$this->model->updateProjectStatus($currentDate);

				$allTasks = $this->model->getAllTasksByUser($_SESSION['USERID']);

				$this->session->set_userdata('tasks', $allTasks);

				$taskDeadlines = $this->model->getTasks2DaysBeforeDeadline();

				if($taskDeadlines != NULL){

					foreach ($taskDeadlines as $taskWithDeadline) {

						$projectDetails = $this->model->getProjectByID($taskWithDeadline['projects_PROJECTID']);
						$projectTitle = $projectDetails['PROJECTTITLE'];

						if($taskWithDeadline['DATEDIFF'] == 2)
							$details = "Deadline for " . $taskWithDeadline['TASKTITLE'] . " in " . $projectTitle . " is in 2 days.";
						else if($taskWithDeadline['DATEDIFF'] == 1)
							$details = "Deadline for " . $taskWithDeadline['TASKTITLE'] . " in " . $projectTitle . " is tomorrow";
						else if($taskWithDeadline['DATEDIFF'] == 0)
							$details = "Deadline for " . $taskWithDeadline['TASKTITLE']. " in " . $projectTitle . " is today.";
						else
							$details = $taskWithDeadline['TASKTITLE'] .  " in " . $projectTitle . " is already delayed. Please accomplish immediately.";

						// for task owner
						$isFound = $this->model->checkNotification($currentDate, $details, $_SESSION['USERID']);
						if(!$isFound){
							// START: Notifications
							$notificationData = array(
								'users_USERID' => $taskWithDeadline['TASKOWNER'],
								'DETAILS' => $details,
								'TIMESTAMP' => date('Y-m-d H:i:s'),
								'status' => 'Unread',
								'tasks_TASKID' => $taskWithDeadline['TASKID'],
								'projects_PROJECTID' => $taskWithDeadline['projects_PROJECTID'],
								'TYPE' => '3'
							);

							$this->model->addNotification($notificationData);
							// END: Notification

							// // START: Email Notification
							//
							// $email = $this->model->getEmail($taskWithDeadline['TASKOWNER']);
							//
							// $this->email->set_mailtype("html");
							// $this->email->set_newline("\r\n");
							//
							// //Email content
							// $htmlContent = '<h1>Sending email via SMTP server</h1>';
							// $htmlContent .= '<p>This email has sent via SMTP server from CodeIgniter application.</p><br><br>';
							//
							// $this->email->to($email);
							// $this->email->from('KernelPMS@gmail.com','Kernel Notification');
							// $this->email->subject('woOoOoOoOoOow');
							// $this->email->message($htmlContent);
							//
							// $this->email->send();
							// // END: Email Notification
						}


						if($taskWithDeadline['DATEDIFF'] < 0){

							$details = $taskWithDeadline['TASKTITLE'] . " in " . $projectTitle .  " is delayed.";

							// for project owner
							$isFound = $this->model->checkNotification($currentDate, $details, $taskWithDeadline['PROJECTOWNER']);
							if(!$isFound){
								// START: Notifications
								$notificationData = array(
									'users_USERID' => $taskWithDeadline['PROJECTOWNER'],
									'DETAILS' => $details,
									'TIMESTAMP' => date('Y-m-d H:i:s'),
									'status' => 'Unread',
									'tasks_TASKID' => $taskWithDeadline['TASKID'],
									'projects_PROJECTID' => $taskWithDeadline['projects_PROJECTID'],
									'TYPE' => '1'
								);

								$this->model->addNotification($notificationData);
								// END: Notification
							}

							// for ACI
							$data['ACI'] = $this->model->getACIbyTask($taskWithDeadline['TASKID']);
							if($data['ACI'] != NULL) {
								foreach($data['ACI'] as $ACIusers){
									$isFound = $this->model->checkNotification($currentDate, $details, $ACIusers['users_USERID']);

									if(!$isFound){
										// START: Notifications
										$notificationData = array(
											'users_USERID' => $ACIusers['users_USERID'],
											'DETAILS' => $details,
											'TIMESTAMP' => date('Y-m-d H:i:s'),
											'status' => 'Unread',
											'tasks_TASKID' => $taskWithDeadline['TASKID'],
											'projects_PROJECTID' => $taskWithDeadline['projects_PROJECTID'],
											'TYPE' => '4'
										);
										$this->model->addNotification($notificationData);
									}
								}
							}

							// for executives(task escalation)
							if($taskWithDeadline['DATEDIFF'] <= -7){
								$filter = "users.usertype_USERTYPEID = '2'";
								$details = $taskWithDeadline['TASKTITLE'] . " of " . $projectTitle . " is being escalated to your attention due to it being delayed for more than a week.";
								$data['executives'] = $this->model->getAllUsersByUserType($filter);
								if($data['executives'] != NULL) {
									foreach($data['executives'] as $executiveUsers){
										$isFound = $this->model->checkNotification($currentDate, $details, $executiveUsers['USERID']);

										if(!$isFound){
											// START: Notifications
											$notificationData = array(
												'users_USERID' => $executiveUsers['USERID'],
												'DETAILS' => $details,
												'TIMESTAMP' => date('Y-m-d H:i:s'),
												'status' => 'Unread',
												'tasks_TASKID' => $taskWithDeadline['TASKID'],
												'projects_PROJECTID' => $taskWithDeadline['projects_PROJECTID'],
												'TYPE' => '1'
											);
											$this->model->addNotification($notificationData);
										}
									}
								}
							}


							// for next task person
							$postTasksData['nextTaskID'] = $this->model->getPostDependenciesByTaskID($taskWithDeadline['TASKID']);
							if($postTasksData['nextTaskID'] != NULL){

								foreach($postTasksData['nextTaskID'] as $nextTaskDetails) {

									$nextTaskID = $nextTaskDetails['tasks_POSTTASKID'];
									$postTasksData['users'] = $this->model->getRACIbyTask($nextTaskID);
									$nextTaskTitle = $nextTaskDetails['TASKTITLE'];

									foreach($postTasksData['users'] as $postTasksDataUsers){
										$details = "Pre-requisite task of " . $nextTaskTitle . " in " . $projectTitle . " is delayed.";

										$isFound = $this->model->checkNotification($currentDate, $details, $postTasksDataUsers['users_USERID']);

										if(!$isFound){
											// START: Notifications
											$notificationData = array(
												'users_USERID' => $postTasksDataUsers['users_USERID'],
												'DETAILS' => $details,
												'TIMESTAMP' => date('Y-m-d H:i:s'),
												'status' => 'Unread',
												'tasks_TASKID' => $taskWithDeadline['TASKID'],
												'projects_PROJECTID' => $taskWithDeadline['projects_PROJECTID'],
												'TYPE' => '4'
											);
											$this->model->addNotification($notificationData);
										}
									}
								}
							}
						}
					}
				}

				// Project Performance Assessment
				$projectAssessmentFound = $this->model->checkProjectAssessment();
				if($projectAssessmentFound == NULL){

					$projectAssessment = $this->model->compute_daily_projectPerformance();

					foreach ($projectAssessment as $value){

						$projectAssessmentData = array (
							'COMPLETENESS' => $value['completeness'],
							'TIMELINESS' => $value['timeliness'],
							'projects_PROJECTID' => $value['projects_PROJECTID'],
							'DATE' => date('Y-m-d'),
							'TYPE' => 1
						);

						$this->model->addProjectAssessment($projectAssessmentData);
					}
				}

				// Department Performance Assessment
				$departmentAssessmentFound = $this->model->checkDepartmentAssessment();
				if($departmentAssessmentFound == NULL){

					$departmentAssessment = $this->model->compute_daily_departmentPerformance();

					foreach ($departmentAssessment as $value){

						$departmentAssessmentData = array (
							'COMPLETENESS' => $value['completeness'],
							'TIMELINESS' => $value['timeliness'],
							'departments_DEPARTMENTID' => $value['departments_DEPARTMENTID'],
							'DATE' => date('Y-m-d')
						);

						$this->model->addDepartmentAssessment($departmentAssessmentData);
					}
				}

				// Employee Performance Assessment
				$employeeAssessmentFound = $this->model->checkEmployeeAssessment();
				if($employeeAssessmentFound == NULL){

					$employeeAssessment = $this->model->compute_daily_employeePerformance();

					foreach ($employeeAssessment as $value){

						$employeeAssessmentData = array (
							'COMPLETENESS' => $value['completeness'],
							'TIMELINESS' => $value['timeliness'],
							'users_USERID' => $value['users_USERID'],
							'DATE' => date('Y-m-d')
						);

						$this->model->addEmployeeAssessment($employeeAssessmentData);
					}
				}

				if ($_SESSION['USERID'] == 1)
				{
					redirect('controller/dashboardAdmin');
				}

				else
				{
					redirect('controller/dashboard');
				}
			}

			else
			{
				$email = $this->input->post('email');
				$this->session->set_flashdata('stickyemail', $email);

				// ALERTS
				$this->session->set_flashdata('danger', 'alert');
				$this->session->set_flashdata('alertMessage', 'Email and password do not match');

				redirect('controller/login');
			}
		}
	}

	public function restrictedAccess()
	{
		$this->load->view('restrictedAccess');
	}

	public function contact()
	{
		$this->load->view('contact');
	}

	public function dashboard()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			if ($_SESSION['departments_DEPARTMENTID'] == '1') //ONLY EXECUTIVES CAN VIEW ALL PROJECTS
			{
				$data['ongoingProjects'] = $this->model->getAllOngoingProjects();
				$data['plannedProjects'] = $this->model->getAllPlannedProjects();
				$data['delayedProjects'] = $this->model->getAllDelayedProjects();
				$data['parkedProjects'] = $this->model->getAllParkedProjects();
				$data['draftedProjects'] = $this->model->getAllDraftedProjects();
				$data['completedProjects'] = $this->model->getAllCompletedProjects();
			}
			else
			{
				$data['ongoingProjects'] = $this->model->getAllOngoingProjectsByUser($_SESSION['USERID']);
				$data['plannedProjects'] = $this->model->getAllPlannedProjectsByUser($_SESSION['USERID']);
				$data['delayedProjects'] = $this->model->getAllDelayedProjectsByUser($_SESSION['USERID']);
				$data['parkedProjects'] = $this->model->getAllParkedProjectsByUser($_SESSION['USERID']);
				$data['draftedProjects'] = $this->model->getAllDraftedProjectsByUser($_SESSION['USERID']);
				$data['completedProjects'] = $this->model->getAllCompletedProjectsByUser($_SESSION['USERID']);
			}

			// $data['ongoingProjectProgress'] = $this->model->getOngoingProjectProgress();
			// $data['delayedProjectProgress'] = $this->model->getDelayedProjectProgress();
			// $data['parkedProjectProgress'] = $this->model->getParkedProjectProgress();
			$data['currentProgress'] = $this->model->checkProjectAssessment();

			$data['ongoingTeamProjectProgress'] = $this->model->getOngoingProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);
			$data['delayedTeamProjectProgress'] = $this->model->getDelayedProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);
			$data['parkedTeamProjectProgress'] = $this->model->getParkedProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);
			$data['tasks2DaysBeforeDeadline'] = $this->model->getTasks2DaysBeforeDeadline();
			$data['toAcknowledgeDocuments'] = $this->model->getAllDocumentAcknowledgementByUser($_SESSION['USERID']);

			// RFC Approval Data
			$userID = $_SESSION['USERID'];
			$deptID = $_SESSION['departments_DEPARTMENTID'];
			switch($_SESSION['usertype_USERTYPEID'])
			{
				case '4': // if supervisor is logged in
					$filter = "(usertype_USERTYPEID = '5' && users_SUPERVISORS = '$userID' && REQUESTSTATUS = 'Pending')
						|| (projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending')"; break;
				case '3': // if head is logged in
					$filter = "((usertype_USERTYPEID = '4' || usertype_USERTYPEID = '5')&& users.departments_DEPARTMENTID = '$deptID' && REQUESTSTATUS = 'Pending')
					|| (projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending')"; break;
				case '5': // if PO is logged in
					$filter = "projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending'"; break;
				default:
					$filter = "usertype_USERTYPEID = '3' && REQUESTSTATUS = 'Pending'"; break;
			}


			$data['changeRequests'] = $this->model->getChangeRequestsForApproval($filter, $_SESSION['USERID']);
			$data['userRequests'] = $this->model->getChangeRequestsByUser($_SESSION['USERID']);
			$data['delegateTasks'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID']);
			$data['lastWeekProgress'] = $this->model->getLatestWeeklyProgress();
			$data['employeeCompleteness'] = $this->model->compute_completeness_employee($_SESSION['USERID']);
			$data['departmentCompleteness'] = $this->model->compute_completeness_department($_SESSION['departments_DEPARTMENTID']);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employee($_SESSION['USERID']);
			$data['departmentTimeliness'] = $this->model->compute_timeliness_department($_SESSION['departments_DEPARTMENTID']);

			$this->load->view("dashboard", $data);
		}
	}

	public function myProjects()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			if ($_SESSION['departments_DEPARTMENTID'] == '1') //ONLY EXECUTIVES CAN VIEW ALL PROJECTS
			{
				$data['ongoingProjects'] = $this->model->getAllOngoingProjects();
				$data['plannedProjects'] = $this->model->getAllPlannedProjects();
				$data['delayedProjects'] = $this->model->getAllDelayedProjects();
				$data['parkedProjects'] = $this->model->getAllParkedProjects();
				$data['draftedProjects'] = $this->model->getAllDraftedProjects();
				$data['completedProjects'] = $this->model->getAllCompletedProjects();
			}
			else
			{
				$data['ongoingProjects'] = $this->model->getAllOngoingProjectsByUser($_SESSION['USERID']);
				$data['plannedProjects'] = $this->model->getAllPlannedProjectsByUser($_SESSION['USERID']);
				$data['delayedProjects'] = $this->model->getAllDelayedProjectsByUser($_SESSION['USERID']);
				$data['parkedProjects'] = $this->model->getAllParkedProjectsByUser($_SESSION['USERID']);
				$data['draftedProjects'] = $this->model->getAllDraftedProjectsByUser($_SESSION['USERID']);
				$compProjects = $this->model->getAllCompletedProjectsByUser($_SESSION['USERID']);

				foreach ($compProjects as $completeProjects)
				{
					$datePlusSeven = date_add(date_create($completeProjects['PROJECTACTUALENDDATE']), date_interval_create_from_date_string("7 days"));

					if ($datePlusSeven->format('Y-m-d') <= date('Y-m-d'))
					{
						$archiveStatus = array(
							'PROJECTSTATUS' => 'Archived'
						);

						$changeProjectStatus = $this->model->changeProjectStatus($completeProjects['PROJECTID'], $archiveStatus);
					}
				}
			}

			$data['completedProjects'] = $this->model->getAllCompletedProjectsByUser($_SESSION['USERID']);

			$data['ongoingProjectProgress'] = $this->model->getOngoingProjectProgress();
			$data['delayedProjectProgress'] = $this->model->getDelayedProjectProgress();
			$data['parkedProjectProgress'] = $this->model->getParkedProjectProgress();

			$data['ongoingTeamProjectProgress'] = $this->model->getOngoingProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);
			$data['delayedTeamProjectProgress'] = $this->model->getDelayedProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);
			$data['parkedTeamProjectProgress'] = $this->model->getParkedProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);

			$data['templates'] = $this->model->getAllTemplates();
			$this->load->view("myProjects", $data);
		}
	}

	public function monitorTeam()
	{
		if (!isset($_SESSION['EMAIL'])|| $_SESSION['usertype_USERTYPEID'] == 5)
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			if ($this->input->post('dept_ID') != null)
			{
				$deptID = $this->input->post('dept_ID');
				$_SESSION['DEPARTMENTID'] = $deptID;
			}
			else
			{
				$deptID = $_SESSION['DEPARTMENTID'];
			}

			$data['performance'] = array();
			$data['tCountStaff'] = array();
			$data['pCountStaff'] = array();
			$taskCondition = "";
			$projectCondition = "";

			if ($_SESSION['usertype_USERTYPEID'] == 3 || $_SESSION['usertype_USERTYPEID'] == 2)
			{
				$data['staff'] = $this->model->getAllUsersByDepartment($deptID);
				$taskCondition = "raci.STATUS = 'Current' && raci.ROLE = '1' && departments_DEPARTMENTID = " . $deptID . " && tasks.CATEGORY = 3";
			}

			elseif ($_SESSION['usertype_USERTYPEID'] == 4)
			{
				$data['staff'] = $this->model->getAllUsersBySupervisor($_SESSION['USERID']);
				$taskCondition = "raci.STATUS = 'Current' && raci.ROLE = '1' && departments_DEPARTMENTID = " . $deptID . " && tasks.CATEGORY = 3 && users_SUPERVISORS = " . $_SESSION['USERID'];
			}

			$data['projects'] = $this->model->getAllProjects();
			$data['taskCount'] = $this->model->getTaskCountPerDepartment($deptID, $taskCondition);
			$data['projectCount'] = $this->model->getProjectCountPerDepartment($deptID);


			foreach ($data['staff'] as $key=> $row)
			{
				$data['timeliness'][] = $this->model->compute_timeliness_employee($row['USERID']);
				$data['completeness'][] = $this->model->compute_completeness_employee($row['USERID']);
			}


			// SAVES USER IDS WITH TASKS INTO ARRAY
			foreach ($data['taskCount'] as $row2)
			{
				$data['tCountStaff'][] = $row2['users_USERID'];
			}

			// CHECKS IF STAFF HAS TASK, SAVES INTO ARRAY
			foreach ($data['staff'] as $s)
			{
				if (in_array($s['USERID'], $data['tCountStaff']))
				{
					$data['tCountStaff'][] = $s['USERID'];
 				}
			}

			// SAVES USER IDS WITH PROJECTS INTO ARRAY
			foreach ($data['projectCount'] as $row2)
			{
				$data['pCountStaff'][] = $row2['USERID'];
			}

			// CHECKS IF STAFF HAS PROJECTS, SAVES INTO ARRAY
			foreach ($data['staff'] as $s)
			{
				if (in_array($s['USERID'], $data['pCountStaff']))
				{
					$data['pCountStaff'][] = $s['USERID'];
 				}
			}

			$this->load->view("monitorTeam", $data);
		}
	}

	public function monitorMembers()
	{
		if (!isset($_SESSION['EMAIL']) || $_SESSION['usertype_USERTYPEID'] == 5)
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			if (isset($_SESSION['employee_ID']))
			{
				$id = $_SESSION['employee_ID'];
			}
			else
			{
				$id = $this->input->post('employee_ID');
			}
			$deptID = $_SESSION['DEPARTMENTID'];
			$taskCondition = "raci.STATUS = 'Current' && raci.ROLE = '1' && departments_DEPARTMENTID = " . $deptID . " && tasks.CATEGORY = 3";

			$data['pCount'] = array();
			$data['tCount'] = array();

			$projectCount = $this->model->getProjectCountPerDepartment($deptID);
			$taskCount = $this->model->getTaskCountPerDepartment($deptID, $taskCondition);

			// SET PROJECT COUNT FOR EMPLOYEE
			foreach ($projectCount as $p)
			{
				if ($p['USERID'] == $id)
				{
					$data['pCount'][] = $p;
				}
			}

			// SET TASK COUNT FOR EMPLOYEE
			foreach ($taskCount as $t)
			{
				if ($t['users_USERID'] == $id)
				{
					$data['tCount'][] = $t;
				}
			}

			switch($_SESSION['usertype_USERTYPEID'])
			{
				case '2':
					$filter = "users.usertype_USERTYPEID = '3'";
					break;

				case '3':
					$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
					break;

				case '4':
					$filter = "(users.usertype_USERTYPEID = '3' &&  users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."')
					|| users.users_SUPERVISORS = '" . $_SESSION['USERID'] ."' && users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
					break;

				default:
					$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
					break;
			}

			$data['departments'] = $this->model->getAllDepartments();
			$data['users'] = $this->model->getAllUsers();
			$data['wholeDept'] = $this->model->getAllUsersByUserType($filter);
			$data['projectCount'] = $this->model->getProjectCount();
			$data['taskCounts'] = $this->model->getTaskCount();

			$data['user'] = $this->model->getUserByID($id);
			$data['projects'] = $this->model->getAllProjectsByUser($id);
			$data['tasks'] = $this->model->getAllTasksForAllOngoingProjects($id);
			$data['timeliness'] = $this->model->compute_timeliness_employee($id);
			$data['completeness'] = $this->model->compute_completeness_employee($id);
			$data['raci'] = $this->model->getAllACI();

			$this->load->view("monitorMembers", $data);
		}
	}

	public function monitorDepartment()
	{
		if (!isset($_SESSION['EMAIL']) || $_SESSION['usertype_USERTYPEID'] == 5)
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$projectID = $this->input->post('project_ID');
			$data['projectProfile'] = $this->model->getProjectByID($projectID);
			$data['projectCompleteness'] = $this->model->compute_completeness_project($projectID);
			$data['projectTimeliness'] = $this->model->compute_timeliness_project($projectID);
			$data['allDepartments'] = $this->model->getAllDepartmentsByProjectByRole($projectID);
			$data['departments'] = $this->model->compute_timeliness_departmentByProject($projectID);
			$data['tasks'] = $this->model->getAllTasksByProject($projectID);
			$data['delayedTasks'] = $this->model->getAllOngoingDelayedTasksByIDRole1($projectID);

			$this->load->view("monitorDepartment", $data);
		}
	}

	public function monitorDepartmentDetails()
	{
		if (!isset($_SESSION['EMAIL'])|| $_SESSION['usertype_USERTYPEID'] == 5)
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$projectID = $this->input->post('project_ID');
			$deptID = $this->input->post('dept_ID');

			$data['projectProfile'] = $this->model->getProjectByID($projectID);
			$data['tasks'] = $this->model->getAllDepartmentTasksByProject($projectID, $deptID);
			$data['raci'] = $this->model->getAllACI();
			$_SESSION['byDepartment'] = 'true';

			$this->load->view("monitorDepartmentDetails", $data);
		}
	}

	public function monitorProjectDetails()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess' || $_SESSION['usertype_USERTYPEID'] == 5);
		}

		else
		{
			$projectID = $this->input->post('project_ID');

			$data['projectProfile'] = $this->model->getProjectByID($projectID);
			$data['tasks'] = $this->model->getAllTasksByProject($projectID);
			$data['raci'] = $this->model->getAllACI();

			$this->load->view("monitorDepartmentDetails", $data);
		}
	}

	public function monitorProject()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess' || $_SESSION['usertype_USERTYPEID'] == 5);
		}

		else
		{
			$data['ongoingProjects'] = $this->model->getAllOngoingOwnedProjectsByUser($_SESSION['USERID']);
			$data['plannedProjects'] = $this->model->getAllPlannedOwnedProjectsByUser($_SESSION['USERID']);
			$data['delayedProjects'] = $this->model->getAllDelayedOwnedProjectsByUser($_SESSION['USERID']);
			$data['completedProjects'] = $this->model->getAllCompletedOwnedProjectsByUser($_SESSION['USERID']);

			$data['ongoingProjectProgress'] = $this->model->getOngoingProjectProgress();
			$data['delayedProjectProgress'] = $this->model->getDelayedProjectProgress();

			$this->load->view("monitorProject", $data);
		}
	}

	public function monitorDepartments()
	{
		if (!isset($_SESSION['EMAIL']) || $_SESSION['usertype_USERTYPEID'] != 2)
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['departments'] = $this->model->getAllDepartments();

			$this->load->view("monitorDepartments", $data);
		}
	}

	public function myTasks()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['departments'] = $this->model->getAllDepartments();

			switch($_SESSION['usertype_USERTYPEID'])
			{
				case '2':
					$filter = "users.usertype_USERTYPEID = '3'";
					break;

				case '3':
					$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
					break;

				case '4':
					$filter = "users.users_SUPERVISORS = '" . $_SESSION['USERID'] ."'";
					break;

				default:
					$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
					break;
			}

			$data['departments'] = $this->model->getAllDepartments();
			$data['deptEmployees'] = $this->model->getAllUsersByUserType($filter);
			$data['wholeDept'] = $this->model->getAllUsersByDepartment($_SESSION['departments_DEPARTMENTID']);
			$data['projectCountR'] = $this->model->getProjectCount($_SESSION['departments_DEPARTMENTID']);
			$data['taskCountR'] = $this->model->getTaskCount($_SESSION['departments_DEPARTMENTID']);
			$data['projectCount'] = $this->model->getProjectCount();
			$data['taskCount'] = $this->model->getTaskCount();

			$data['users'] = $this->model->getAllUsers();
			$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);
			$data['ACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Ongoing");
			$data['mainActivity'] = $this->model->getAllMainActivitiesByUser($_SESSION['USERID']);
			$data['subActivity'] = $this->model->getAllSubActivitiesByUser($_SESSION['USERID']);

			$this->load->view("myTasks", $data);
		}
	}

	public function updateTask()
	{
		if ($this->input->post("remarksUpdate") == NULL)
		{
			$this->session->set_flashdata('danger', 'alert');
			$this->session->set_flashdata('alertMessage', ' Please provide an update for this task');

		}
		else
		{
			$data = array(
				'tasks_TASKID' => $this->input->post("task_ID"),
				'COMMENT' => $this->input->post("remarksUpdate"),
				'users_COMMENTEDBY' => $_SESSION['USERID'],
				'COMMENTDATE' =>date('Y-m-d')
			);
			$this->model->addTaskUpdate($data);

			$this->session->set_flashdata('success', 'alert');
			$this->session->set_flashdata('alertMessage', ' Task update submitted');

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$taskID = $this->input->post("task_ID");

			$taskDetails = $this->model->getTaskByID($taskID);
			$taskTitle = $taskDetails['TASKTITLE'];

			$projectID = $taskDetails['projects_PROJECTID'];
			$projectDetails = $this->model->getProjectByID($projectID);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			// START: LOG DETAILS

			$details = $userName . " has an update with " . $taskTitle . ".";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $projectID
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			$projectOwnerID = $projectDetails['users_USERID'];

			// START: Notifications

			// notify project owner
			$details = $userName . " has an update on " . $taskTitle . " in " . $projectTitle . ".";

			$notificationData = array(
				'users_USERID' => $projectOwnerID,
				'DETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'status' => 'Unread',
				'projects_PROJECTID' => $projectID,
				'tasks_TASKID' => $taskID,
				'TYPE' => '4'
			);

			$notificationID = $this->model->addNotification($notificationData);

			// notify ACI
			$ACIdata['ACI'] = $this->model->getACIbyTask($taskID);
			if($ACIdata['ACI'] != NULL) {

				foreach($ACIdata['ACI'] as $ACIusers){

					$details = $userName . " has an update on " . $taskTitle . " in " . $projectTitle . ".";

					$notificationData = array(
						'users_USERID' => $ACIusers['users_USERID'],
						'DETAILS' => $details,
						'TIMESTAMP' => date('Y-m-d H:i:s'),
						'status' => 'Unread',
						'projects_PROJECTID' => $projectID,
						'tasks_TASKID' => $taskID,
						'TYPE' => '4'
					);
					$this->model->addNotification($notificationData);
				}
			}
			// END: Notification

		}
		redirect('controller/' . $this->input->post("page"));
	}

	public function getTaskUpdates()
	{
		$taskID = $this->input->post("task_ID");
		$taskUpdates = $this->model->getTaskUpdatesByID($taskID);

		echo json_encode($taskUpdates);
	}

	public function doneTask()
	{
		if ($this->input->post('task_ID'))
		{
			if ($this->input->post('remarks') == NULL)
			{
				$this->session->set_flashdata('danger', 'alert');
				$this->session->set_flashdata('alertMessage', ' Remarks are required for delayed tasks');
				$this->taskTodo();
			}

			else
			{
		    $id = $this->input->post("task_ID");
		    $remarks = $this->input->post('remarks');

		    $data = array(
		          'TASKSTATUS' => 'Complete',
		          'TASKREMARKS' => $remarks,
		          'TASKACTUALENDDATE' => date('Y-m-d')
		    );

		    $updateTasks = $this->model->updateTaskDone($id, $data);

		    // START OF LOGS/NOTIFS
		    $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

		    $taskDetails = $this->model->getTaskByID($id);
		    $taskTitle = $taskDetails['TASKTITLE'];

		    $projectID = $taskDetails['projects_PROJECTID'];
		    $projectDetails = $this->model->getProjectByID($projectID);
		    $projectTitle = $projectDetails['PROJECTTITLE'];

		    // START: LOG DETAILS

		    $details = $userName . " has completed " . $taskTitle . ".";

		    $logData = array (
		      'LOGDETAILS' => $details,
		      'TIMESTAMP' => date('Y-m-d H:i:s'),
		      'projects_PROJECTID' => $projectID
		    );

		    $this->model->addToProjectLogs($logData);
		    // END: LOG DETAILS

		    $projectOwnerID = $projectDetails['users_USERID'];

		    // START: Notifications

		    // notify project owner
		    $details = $userName . " has completed " . $taskTitle . " in " . $projectTitle . ".";

		    $notificationData = array(
		      'users_USERID' => $projectOwnerID,
		      'DETAILS' => $details,
		      'TIMESTAMP' => date('Y-m-d H:i:s'),
		      'status' => 'Unread',
		      'projects_PROJECTID' => $projectID,
		      'tasks_TASKID' => $id,
		      'TYPE' => '1'
		    );

		    $notificationID = $this->model->addNotification($notificationData);

		    // // START: Email notification
		    // $projectOwnerEmail = $this->model->getEmail($projectOwnerID);
		    //
		    // $this->email->set_mailtype("html");
		    // $this->email->set_newline("\r\n");
		    //
		    // //Email content
		    // $htmlContent = '<h1>Hi!</h1>';
		    // $htmlContent .= '<p>' . $details . '</p>';
		    //
		    // $this->email->to($projectOwnerEmail);
		    // $this->email->from('KernelPMS@gmail.com','Kernel Notification');
		    // $this->email->subject('#' . $notificationID . " - " . $projectTitle . " update");
		    // $this->email->message($htmlContent);
		    //
		    // $this->email->send();
		    // // End: Email notification

		    // notify next task person
		    $postTasksData['nextTaskID'] = $this->model->getPostDependenciesByTaskID($id);
		    if($postTasksData['nextTaskID'] != NULL){

		      foreach($postTasksData['nextTaskID'] as $nextTaskDetails) {

		        $nextTaskID = $nextTaskDetails['tasks_POSTTASKID'];
		        $postTasksData['users'] = $this->model->getRACIbyTask($nextTaskID);
		        $nextTaskTitle = $nextTaskDetails['TASKTITLE'];

		        foreach($postTasksData['users'] as $postTasksDataUsers){

		          $details = "Pre-requisite task of " . $nextTaskTitle . " in " . $projectTitle . " has been completed.";

		          $notificationData = array(
		            'users_USERID' => $postTasksDataUsers['users_USERID'],
		            'DETAILS' => $details,
		            'TIMESTAMP' => date('Y-m-d H:i:s'),
		            'status' => 'Unread',
		            'tasks_TASKID' => $id,
		            'projects_PROJECTID' => $projectID,
		            'TYPE' => '3'
		          );

		          $this->model->addNotification($notificationData);
		        }
		      }
		    }

		    // notify ACI
		    $ACIdata['ACI'] = $this->model->getACIbyTask($id);
		    if($ACIdata['ACI'] != NULL) {

		      foreach($ACIdata['ACI'] as $ACIusers){

		        $details = $userName . " has completed " . $taskTitle . " in " . $projectTitle . ".";

		        $notificationData = array(
		          'users_USERID' => $ACIusers['users_USERID'],
		          'DETAILS' => $details,
		          'TIMESTAMP' => date('Y-m-d H:i:s'),
		          'status' => 'Unread',
		          'projects_PROJECTID' => $projectID,
		          'tasks_TASKID' => $id,
		          'TYPE' => '4'
		        );
		        $this->model->addNotification($notificationData);
		      }
		    }
		    // END: Notification

		    // Check and Complete Main and Sub Activities
		    $parentID = $this->model->getParentTask($id);
		    $completeTasks = $this->model->checkTasksStatus($parentID['tasks_TASKPARENT']);
		    if($completeTasks == 0)
		    {
		      $subData = array(
		            'TASKSTATUS' => 'Complete',
		            'TASKACTUALENDDATE' => date('Y-m-d')
		      );
		      $this->model->updateTaskDone($parentID['tasks_TASKPARENT'], $subData); // Complete Sub Activity

		      // START OF LOGS/NOTIFS
		      $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

		      $taskDetails = $this->model->getTaskByID($id);
		      $taskTitle = $taskDetails['TASKTITLE'];

		      $projectID = $taskDetails['projects_PROJECTID'];
		      $projectDetails = $this->model->getProjectByID($projectID);
		      $projectTitle = $projectDetails['PROJECTTITLE'];

		      // START: LOG DETAILS
		      $details = $userName . " has completed Sub Activity - " . $taskTitle . ".";

		      $logData = array (
		        'LOGDETAILS' => $details,
		        'TIMESTAMP' => date('Y-m-d H:i:s'),
		        'projects_PROJECTID' => $projectID
		      );

		      $this->model->addToProjectLogs($logData);
		      // END: LOG DETAILS

		      $projectOwnerID = $projectDetails['users_USERID'];

		      // START: Notifications
		      $details = "Sub Activty - " . $taskTitle . " has been completed by " . $userName . " in " . $projectTitle . ".";

		      $notificationData = array(
		        'users_USERID' => $projectOwnerID,
		        'DETAILS' => $details,
		        'TIMESTAMP' => date('Y-m-d H:i:s'),
		        'status' => 'Unread',
		        'projects_PROJECTID' => $projectID,
		        'tasks_TASKID' => $id,
		        'TYPE' => '1'
		      );

		      $this->model->addNotification($notificationData);

		      // notify ACI
		      $ACIdata['ACI'] = $this->model->getACIbyTask($id);
		      if($ACIdata['ACI'] != NULL) {

		        foreach($ACIdata['ACI'] as $ACIusers){

		          $details = "Sub Activity - " . $taskTitle . " has been completed in " . $projectTitle . ".";

		          $notificationData = array(
		            'users_USERID' => $ACIusers['users_USERID'],
		            'DETAILS' => $details,
		            'TIMESTAMP' => date('Y-m-d H:i:s'),
		            'status' => 'Unread',
		            'projects_PROJECTID' => $projectID,
		            'tasks_TASKID' => $id,
		            'TYPE' => '4'
		          );
		          $this->model->addNotification($notificationData);
		        }
		      }
		      // END: Notification

		      $mainID = $this->model->getParentTask($parentID['tasks_TASKPARENT']);
		      $completeSubs = $this->model->checkTasksStatus($mainID['tasks_TASKPARENT']);
		      if($completeSubs == 0)
		      {
		        $mainData = array(
		              'TASKSTATUS' => 'Complete',
		              'TASKACTUALENDDATE' => date('Y-m-d')
		        );
		        $this->model->updateTaskDone($mainID['tasks_TASKPARENT'], $mainData); // Complete Main Activity

		        // START OF LOGS/NOTIFS
		        $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

		        $taskDetails = $this->model->getTaskByID($id);
		        $taskTitle = $taskDetails['TASKTITLE'];

		        $projectID = $taskDetails['projects_PROJECTID'];
		        $projectDetails = $this->model->getProjectByID($projectID);
		        $projectTitle = $projectDetails['PROJECTTITLE'];

		        // START: LOG DETAILS
		        $details = $userName . " has completed Main Activity - " . $taskTitle . ".";

		        $logData = array (
		          'LOGDETAILS' => $details,
		          'TIMESTAMP' => date('Y-m-d H:i:s'),
		          'projects_PROJECTID' => $projectID
		        );

		        $this->model->addToProjectLogs($logData);
		        // END: LOG DETAILS

		        $projectOwnerID = $projectDetails['users_USERID'];

		        // START: Notifications
		        $details = "Main Activity - " . $taskTitle . " has been completed in " . $projectTitle . ".";

		        $notificationData = array(
		          'users_USERID' => $projectOwnerID,
		          'DETAILS' => $details,
		          'TIMESTAMP' => date('Y-m-d H:i:s'),
		          'status' => 'Unread',
		          'projects_PROJECTID' => $projectID,
		          'tasks_TASKID' => $id,
		          'TYPE' => '1'
		        );

		        $this->model->addNotification($notificationData);

		        // notify next ACI
		        $ACIdata['ACI'] = $this->model->getACIbyTask($id);
		        if($ACIdata['ACI'] != NULL) {

		          foreach($ACIdata['ACI'] as $ACIusers){

		            $details = "Main Activity - " . $taskTitle . " has been completed in " . $projectTitle . ".";

		            $notificationData = array(
		              'users_USERID' => $ACIusers['users_USERID'],
		              'DETAILS' => $details,
		              'TIMESTAMP' => date('Y-m-d H:i:s'),
		              'status' => 'Unread',
		              'projects_PROJECTID' => $projectID,
		              'tasks_TASKID' => $id,
		              'TYPE' => '4'
		            );
		            $this->model->addNotification($notificationData);
		          }
		        }
		        // END: Notification

		        // Check and Complete a Project
		        $completeProject = $this->model->checkProjectStatus($mainID['projects_PROJECTID']);
		        if($completeProject == 0)
		        {
		          $projectData = array(
		                'PROJECTSTATUS' => 'Complete',
		                'PROJECTACTUALENDDATE' => date('Y-m-d')
		          );
		          $this->model->completeProject($mainID['projects_PROJECTID'], $projectData); // Complete Project

		          // START OF LOGS/NOTIFS
		          $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

		          $taskDetails = $this->model->getTaskByID($id);
		          $taskTitle = $taskDetails['TASKTITLE'];

		          $projectID = $taskDetails['projects_PROJECTID'];
		          $projectDetails = $this->model->getProjectByID($projectID);
		          $projectTitle = $projectDetails['PROJECTTITLE'];

		          // START: LOG DETAILS
		          $details = "Project completed!";

		          $logData = array (
		            'LOGDETAILS' => $details,
		            'TIMESTAMP' => date('Y-m-d H:i:s'),
		            'projects_PROJECTID' => $projectID
		          );

		          $this->model->addToProjectLogs($logData);
		          // END: LOG DETAILS

		          $projectOwnerID = $projectDetails['users_USERID'];

		          $details = $projectTitle . " has been completed. Project Summary is now viewable.";

		          // notify PO
		          $notificationData = array(
		            'users_USERID' => $projectDetails['users_USERID'],
		            'DETAILS' => $details,
		            'TIMESTAMP' => date('Y-m-d H:i:s'),
		            'status' => 'Unread',
		            'projects_PROJECTID' => $projectID,
		            'TYPE' => '7'
		          );

		          $this->model->addNotification($notificationData);

		          // notify all people involved in that project
		          $data['projectUsers'] = $this->model->getAllUsersByProject($projectID);

		          if($data['projectUsers'] != NULL){
		            foreach($data['projectUsers'] as $projectUsers ) {
		              // START: Notifications
		              $details = $projectTitle . " has been completed and will be archived in 7 days.";

		              $notificationData = array(
		                'users_USERID' => $projectUsers['users_USERID'],
		                'DETAILS' => $details,
		                'TIMESTAMP' => date('Y-m-d H:i:s'),
		                'status' => 'Unread',
		                'projects_PROJECTID' => $projectID,
		                'TYPE' => '7'
		              );

		              $this->model->addNotification($notificationData);
		            }
		          }
		          // END: Notification
		        }
		      }
		    }

				$this->session->set_flashdata('success', 'alert');
				$this->session->set_flashdata('alertMessage', ' Task has been marked complete');
				$this->taskTodo();
			}
		}
	}

	public function loadTaskHistory()
	{
		$taskID = $this->input->post("task_ID");
		$data['task'] = $this->model->getTaskByID($taskID);
		$data['raciHistory'] = $this->model->getAllRACIbyTask($taskID);
		$data['changeRequests'] = $this->model->getChangeRequestsByTask($taskID);
		$data['users'] = $this->model->getAllUsers();

		echo json_encode($data);
	}

	public function loadTasks()
	{
		$data['users'] = $this->model->getAllUsers();
		$data['departments'] = $this->model->getAllDepartments();
		$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);
		$data['ACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Ongoing");
		$data['mainActivity'] = $this->model->getAllMainActivitiesByUser($_SESSION['USERID']);
		$data['subActivity'] = $this->model->getAllSubActivitiesByUser($_SESSION['USERID']);

		echo json_encode($data);
	}

	public function getDependenciesByTaskID()
	{
		$taskID = $this->input->post("task_ID");
		$data['dependencies'] = $this->model->getDependenciesByTaskID($taskID);
		$data['taskID'] = $this->model->getTaskByID($taskID);

		echo json_encode($data);
	}

	public function getPostDependenciesByTaskID()
	{
		$taskID = $this->input->post("task_ID");
		$data['dependencies'] = $this->model->getPostDependenciesByTaskID($taskID);
		$data['taskID'] = $this->model->getTaskByID($taskID);

		echo json_encode($data);
	}

	public function acceptTask()
	{
		$taskID = $this->input->post("task_ID");

		$updateD = $this->model->updateRACI($taskID, '0'); // change status to 'changed'

		$delegateData = array(
			'ROLE' => '5',
			'users_USERID' => $_SESSION['USERID'],
			'tasks_TASKID' => $taskID,
			'STATUS' => 'Changed'
		);
		$result = $this->model->addToRaci($delegateData);

		// START OF LOGS/NOTIFS
		$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

		$taskDetails = $this->model->getTaskByID($taskID);
		$taskTitle = $taskDetails['TASKTITLE'];

		$projectID = $taskDetails['projects_PROJECTID'];
		$projectDetails = $this->model->getProjectByID($projectID);
		$projectTitle = $projectDetails['PROJECTTITLE'];

		// START: LOG DETAILS
		$details = $userName . " has accepted the responsibility for " . $taskTitle . ".";

		$logData = array (
			'LOGDETAILS' => $details,
			'TIMESTAMP' => date('Y-m-d H:i:s'),
			'projects_PROJECTID' => $projectID
		);

		$this->model->addToProjectLogs($logData);
		// END: LOG DETAILS

		$taskRACI = $this->model->getRACIbyTask($taskID);
		foreach($taskRACI as $raci){

			if($raci['ROLE'] != 5){
				// START: Notifications
				$details = $userName . " has accepted the responsibility for " . $taskTitle . " in " . $projectTitle . ".";
				$notificationData = array(
					'users_USERID' => $raci['users_USERID'],
					'DETAILS' => $details,
					'TIMESTAMP' => date('Y-m-d H:i:s'),
					'status' => 'Unread'
				);

				$this->model->addNotification($notificationData);
				// END: Notification
			}
		}
		$this->session->set_flashdata('success', 'alert');
		$this->session->set_flashdata('alertMessage', ' Task has been accepted');
		$this->taskDelegate();
	}

	public function delegateTask()
	{
		$responsibleEmp = $this->input->post('responsibleEmp');
		$accountableEmp = $this->input->post("accountableEmp[]");
		$consultedEmp = $this->input->post("consultedEmp[]");
		$informedEmp = $this->input->post("informedEmp[]");

		if ($responsibleEmp == NULL)
		{
			$this->session->set_flashdata('danger', 'alert');
			$this->session->set_flashdata('alertMessage', ' Select an employee to be responsible for the task');
			$this->taskDelegate();
		}

		elseif ($accountableEmp == NULL)
		{
			$this->session->set_flashdata('danger', 'alert');
			$this->session->set_flashdata('alertMessage', ' Select an employee to be accountable for the task');
			$this->taskDelegate();
		}

		elseif ($consultedEmp == NULL)
		{
			$this->session->set_flashdata('danger', 'alert');
			$this->session->set_flashdata('alertMessage', ' Select an employee to be consulted for the task');
			$this->taskDelegate();
		}

		elseif ($informedEmp == NULL)
		{
			$this->session->set_flashdata('danger', 'alert');
			$this->session->set_flashdata('alertMessage', ' Select an employee to be informed for the task');
			$this->taskDelegate();
		}

		else
		{
		  $taskID = $this->input->post("task_ID");

		  $updateR = $this->model->updateRACI($taskID, '1'); // change status to 'changed'
		  $updateA = $this->model->updateRACI($taskID, '2'); // change status to 'changed'
		  $updateC = $this->model->updateRACI($taskID, '3'); // change status to 'changed'
		  $updateI = $this->model->updateRACI($taskID, '4'); // change status to 'changed'

		  // SAVE RESPONSIBLE
		  if($this->input->post("responsibleEmp"))
		  {
		    $responsibleEmp = $this->input->post('responsibleEmp');
		    $responsibleType = $this->model->getUserType($responsibleEmp);

		    $delegate = $this->model->checkForDelegation($taskID);

		    if($delegate == '1')
		    {
		      $updateD = $this->model->updateRACI($taskID, '0'); // change status to 'changed'

		      if($responsibleType == '5') //if to staff, remove delegation
		      {
		        $delegateDataNew = array(
		          'ROLE' => '0',
		          'users_USERID' => $responsibleEmp,
		          'tasks_TASKID' => $taskID,
		          'STATUS' => 'Changed'
		        );
		      }
		      else
		      {
		        $delegateDataNew = array(
		          'ROLE' => '0',
		          'users_USERID' => $responsibleEmp,
		          'tasks_TASKID' => $taskID,
		          'STATUS' => 'Current'
		        );
		      }

		      $result = $this->model->addToRaci($delegateDataNew);

		      $delegateData = array(
		        'ROLE' => '5',
		        'users_USERID' => $_SESSION['USERID'],
		        'tasks_TASKID' => $taskID,
		        'STATUS' => 'Changed'
		      );
		      $result = $this->model->addToRaci($delegateData);
		    }

		    $responsibleData = array(
		      'ROLE' => '1',
		      'users_USERID' => $responsibleEmp,
		      'tasks_TASKID' => $taskID,
		      'STATUS' => 'Current'
		    );
		    $result = $this->model->addToRaci($responsibleData);

		    // START OF LOGS/NOTIFS
		    $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

		    $taskDetails = $this->model->getTaskByID($taskID);
		    $taskTitle = $taskDetails['TASKTITLE'];

		    $projectID = $taskDetails['projects_PROJECTID'];
		    $projectDetails = $this->model->getProjectByID($projectID);
		    $projectTitle = $projectDetails['PROJECTTITLE'];

		    $userDetails = $this->model->getUserByID($this->input->post('responsibleEmp'));
		    $taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

		    // START: LOG DETAILS
		    $details = $userName . " has tagged " . $taggedUserName . " as responsible for " . $taskTitle . ".";

		    $logData = array (
		      'LOGDETAILS' => $details,
		      'TIMESTAMP' => date('Y-m-d H:i:s'),
		      'projects_PROJECTID' => $projectID
		    );

		    $this->model->addToProjectLogs($logData);
		    // END: LOG DETAILS


		    // START: Notifications
		    $details =  $userName . " has tagged you responsible for " . $taskTitle . " in " . $projectTitle . ".";

		    $notificationData = array(
		      'users_USERID' => $this->input->post('responsibleEmp'),
		      'DETAILS' => $details,
		      'TIMESTAMP' => date('Y-m-d H:i:s'),
		      'status' => 'Unread',
		      'projects_PROJECTID' => $projectID,
		      'tasks_TASKID' => $taskID,
		      'TYPE' => '3'
		    );

		    $this->model->addNotification($notificationData);
		    // END: Notification
		  }

		  if ($this->input->post("accountableEmp[]"))
		  {
		    foreach($this->input->post("accountableEmp[]") as $empID)
		    {
		      $accountableData = array(
		        'ROLE' => '2',
		        'users_USERID' => $empID,
		        'tasks_TASKID' =>	$taskID,
		        'STATUS' => 'Current'
		      );
		      $this->model->addToRaci($accountableData);

		      // START OF LOGS/NOTIFS
		      $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

		      $taskDetails = $this->model->getTaskByID($taskID);
		      $taskTitle = $taskDetails['TASKTITLE'];

		      $projectID = $taskDetails['projects_PROJECTID'];
		      $projectDetails = $this->model->getProjectByID($projectID);
		      $projectTitle = $projectDetails['PROJECTTITLE'];

		      $userDetails = $this->model->getUserByID($empID);
		      $taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

		      // START: LOG DETAILS
		      $details = $userName . " has tagged " . $taggedUserName . " as accountable for " . $taskTitle . ".";

		      $logData = array (
		        'LOGDETAILS' => $details,
		        'TIMESTAMP' => date('Y-m-d H:i:s'),
		        'projects_PROJECTID' => $projectID
		      );

		      $this->model->addToProjectLogs($logData);
		      // END: LOG DETAILS

		      // START: Notifications
		      $details =  $userName . " has tagged you accountable for " . $taskTitle . " in " . $projectTitle . ".";
		      $notificationData = array(
		        'users_USERID' => $empID,
		        'DETAILS' => $details,
		        'TIMESTAMP' => date('Y-m-d H:i:s'),
		        'status' => 'Unread',
		        'projects_PROJECTID' => $projectID,
		        'tasks_TASKID' => $taskID,
		        'TYPE' => '4'
		      );

		      $this->model->addNotification($notificationData);
		      // END: Notification
		    }
		  }

		  if($this->input->post("consultedEmp[]"))
		  {
		    foreach($this->input->post("consultedEmp[]") as $empID)
		    {
		      $consultedData = array(
		        'ROLE' => '3',
		        'users_USERID' => $empID,
		        'tasks_TASKID' =>	$taskID,
		        'STATUS' => 'Current'
		      );
		      $this->model->addToRaci($consultedData);

		      // START: LOG DETAILS
		      $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
		      $taskDetails = $this->model->getTaskByID($taskID);
		      $taskTitle = $taskDetails['TASKTITLE'];
		      $projectID = $taskDetails['projects_PROJECTID'];
		      $userDetails = $this->model->getUserByID($empID);
		      $taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];
		      $details = $userName . " has tagged " . $taggedUserName . " as consulted for " . $taskTitle . ".";

		      $logData = array (
		        'LOGDETAILS' => $details,
		        'TIMESTAMP' => date('Y-m-d H:i:s'),
		        'projects_PROJECTID' => $projectID
		      );

		      $this->model->addToProjectLogs($logData);
		      // END: LOG DETAILS

		      // START: Notifications
		      $projectDetails = $this->model->getProjectByID($projectID);
		      $projectTitle = $projectDetails['PROJECTTITLE'];

		      $details =  $userName . " has tagged you consulted for " . $taskTitle . " in " . $projectTitle . ".";
		      $notificationData = array(
		        'users_USERID' => $empID,
		        'DETAILS' => $details,
		        'TIMESTAMP' => date('Y-m-d H:i:s'),
		        'status' => 'Unread',
		        'projects_PROJECTID' => $projectID,
		        'tasks_TASKID' => $taskID,
		        'TYPE' => '4'
		      );

		      $this->model->addNotification($notificationData);
		      // END: Notification
		    }
		  }

		  if($this->input->post("informedEmp[]"))
		  {
		    foreach($this->input->post("informedEmp[]") as $empID)
		    {
		      $informedData = array(
		        'ROLE' => '4',
		        'users_USERID' => $empID,
		        'tasks_TASKID' =>	$taskID,
		        'STATUS' => 'Current'
		      );
		      $this->model->addToRaci($informedData);

		      // START: LOG DETAILS
		      $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
		      $taskDetails = $this->model->getTaskByID($taskID);
		      $taskTitle = $taskDetails['TASKTITLE'];
		      $projectID = $taskDetails['projects_PROJECTID'];
		      $userDetails = $this->model->getUserByID($empID);
		      $taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];
		      $details = $userName . " has tagged " . $taggedUserName . " as informed for " . $taskTitle . ".";

		      $logData = array (
		        'LOGDETAILS' => $details,
		        'TIMESTAMP' => date('Y-m-d H:i:s'),
		        'projects_PROJECTID' => $projectID
		      );

		      $this->model->addToProjectLogs($logData);
		      // END: LOG DETAILS

		      // START: Notifications
		      $projectDetails = $this->model->getProjectByID($projectID);
		      $projectTitle = $projectDetails['PROJECTTITLE'];

		      $details =  $userName . " has tagged you informed for " . $taskTitle . " in " . $projectTitle . ".";

		      $notificationData = array(
		        'users_USERID' => $empID,
		        'DETAILS' => $details,
		        'TIMESTAMP' => date('Y-m-d H:i:s'),
		        'status' => 'Unread',
		        'projects_PROJECTID' => $projectID,
		        'tasks_TASKID' => $taskID,
		        'TYPE' => '4'
		      );

		      $this->model->addNotification($notificationData);
		      // END: Notification

		    }
		  }
		  $this->session->set_flashdata('success', 'alert');
		  $this->session->set_flashdata('alertMessage', ' Task has been delegated');
			if ($this->input->post("reassigned"))
			{
				$empID = $this->input->post('employee_ID');
				$this->session->set_flashdata('employee_ID', $empID);
				redirect('controller/monitorMembers');
			}
			else
				$this->taskDelegate();
		}
	}


	public function submitRFC()
	{
		if ($this->input->post("rfcType") == NULL)
		{
			$this->session->set_flashdata('danger', 'alert');
		  $this->session->set_flashdata('alertMessage', ' Please select a request type');
		  $this->taskTodo();
		}

		else
		{
			if ($this->input->post("reason") == NULL)
			{
				$this->session->set_flashdata('danger', 'alert');
				$this->session->set_flashdata('alertMessage', ' Please provide a reason for this request');
				$this->taskTodo();
			}

			else
			{
			  if($this->input->post("rfcType") == '1')
			  {
			    $data = array(
			      'REQUESTTYPE' => $this->input->post("rfcType"),
			      'tasks_REQUESTEDTASK' => $this->input->post("task_ID"),
			      'REASON' => $this->input->post("reason"),
			      'REQUESTSTATUS' => "Pending",
			      'users_REQUESTEDBY' => $_SESSION['USERID'],
			      'REQUESTEDDATE' => date('Y-m-d'),
			      'users_APPROVEDBY' => '1'
			    );

			    // START OF LOGS/NOTIFS
			    $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			    $taskID = $this->input->post("task_ID");
			    $taskDetails = $this->model->getTaskByID($taskID);
			    $taskTitle = $taskDetails['TASKTITLE'];

			    $projectID = $taskDetails['projects_PROJECTID'];
			    $projectDetails = $this->model->getProjectByID($projectID);
			    $projectTitle = $projectDetails['PROJECTTITLE'];

			    // START: LOG DETAILS
			    $details = $userName . " requested a change in performer for " . $taskTitle . ".";

			    $logData = array (
			      'LOGDETAILS' => $details,
			      'TIMESTAMP' => date('Y-m-d H:i:s'),
			      'projects_PROJECTID' => $taskDetails['projects_PROJECTID']
			    );

			    $this->model->addToProjectLogs($logData);
			    // END: LOG DETAILS

			    // START: Notifications
			    $details =  "A change in performer was requested by " . $userName . " for " . $taskTitle . " in " . $projectTitle . ".";

			    // if($_SESSION['usertype_USERTYPEID'] == 5 || 4) {
			    // 	$taggedUserID = $_SESSION['users_SUPERVISORS'];
			    // } else {
			    // 	$taggedUserID = $projectDetails['users_USERID'];
			    // }

			    // notify PO
			    $notificationData = array(
			      'users_USERID' => $projectDetails['users_USERID'],
			      'DETAILS' => $details,
			      'TIMESTAMP' => date('Y-m-d H:i:s'),
			      'status' => 'Unread',
			      'projects_PROJECTID' => $projectID,
			      'tasks_TASKID' => $taskID,
			      'TYPE' => '6'
			    );

			    $this->model->addNotification($notificationData);

			    // notify immediate head
			    $notificationData = array(
			      'users_USERID' => $_SESSION['users_SUPERVISORS'],
			      'DETAILS' => $details,
			      'TIMESTAMP' => date('Y-m-d H:i:s'),
			      'status' => 'Unread',
			      'projects_PROJECTID' => $projectID,
			      'tasks_TASKID' => $taskID,
			      'TYPE' => '6'
			    );

			    $this->model->addNotification($notificationData);

			    // notify department head
			    $departmentHeadID = $this->model->getUserHead($_SESSION['users_SUPERVISORS']);

			    $notificationData = array(
			      'users_USERID' => $departmentHeadID,
			      'DETAILS' => $details,
			      'TIMESTAMP' => date('Y-m-d H:i:s'),
			      'status' => 'Unread',
			      'projects_PROJECTID' => $projectID,
			      'tasks_TASKID' => $taskID,
			      'TYPE' => '6'
			    );

			    $this->model->addNotification($notificationData);
			    // END: Notification

			  }

			  else
			  {
					if ($this->input->post("endDate") == NULL)
					{
						$this->session->set_flashdata('danger', 'alert');
					  $this->session->set_flashdata('alertMessage', ' Please select a new end date');

					  $this->taskTodo();
					}

					else
					{
					  $data = array(
					    'REQUESTTYPE' => $this->input->post("rfcType"),
					    'tasks_REQUESTEDTASK' => $this->input->post("task_ID"),
					    'REASON' => $this->input->post("reason"),
					    'REQUESTSTATUS' => "Pending",
					    'users_REQUESTEDBY' => $_SESSION['USERID'],
					    'REQUESTEDDATE' => date('Y-m-d'),
					    'NEWSTARTDATE' => $this->input->post("startDate"),
					    'NEWENDDATE' => $this->input->post("endDate"),
					  );

					  // START OF LOGS/NOTIFS
					  $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

					  $taskID = $this->input->post("task_ID");
					  $taskDetails = $this->model->getTaskByID($taskID);
					  $taskTitle = $taskDetails['TASKTITLE'];

					  $projectID = $taskDetails['projects_PROJECTID'];
					  $projectDetails = $this->model->getProjectByID($projectID);
					  $projectTitle = $projectDetails['PROJECTTITLE'];

					  // START: LOG DETAILS
					  $details = $userName . " requested a change in dates for " . $taskTitle . ".";

					  $logData = array (
					    'LOGDETAILS' => $details,
					    'TIMESTAMP' => date('Y-m-d H:i:s'),
					    'projects_PROJECTID' => $projectID
					  );

					  $this->model->addToProjectLogs($logData);
					  // END: LOG DETAILS

					  // START: Notifications
					  $details =  "A change in dates was requested by " . $userName . " for " . $taskTitle . " in " . $projectTitle . ".";
					  $taggedUserID = "";

					  if($_SESSION['usertype_USERTYPEID'] == 5 || 4) {
					    $taggedUserID = $_SESSION['users_SUPERVISORS'];
					  } else {
					    $taggedUserID = $projectDetails['users_USERID'];
					  }

					  $notificationData = array(
					    'users_USERID' => $taggedUserID,
					    'DETAILS' => $details,
					    'TIMESTAMP' => date('Y-m-d H:i:s'),
					    'status' => 'Unread',
					    'projects_PROJECTID' => $projectID,
					    'tasks_TASKID' => $taskID,
					    'TYPE' => '6'
					  );

					  $this->model->addNotification($notificationData);
					  // END: Notification
					}
			  }

			  $this->model->addRFC($data);
			  $this->session->set_flashdata('success', 'alert');
			  $this->session->set_flashdata('alertMessage', ' Request for change submitted');
			  $this->taskTodo();
			}
		}
	}

	public function approveDenyRFC()
	{
		$requestID = $this->input->post('request_ID');
		$requestType = $this->input->post('request_type');
		$projectID = $this->input->post('project_ID');
		$remarks = $this->input->post('remarks');
		$status = $this->input->post('status');
		$taskID = $this->input->post('task_ID');
		$requestorID = $this->input->post('requestor_ID');
		$responsibleEmp = $this->input->post('responsibleEmp');

		if ($remarks == NULL)
		{
			$this->session->set_flashdata('rfcNoRemarks', $requestID);
			$this->session->set_flashdata('projectID', $projectID);

			$this->session->set_flashdata('danger', 'alert');
			$this->session->set_flashdata('alertMessage', ' Remarks is a required field');

			redirect("controller/projectGantt");
		}

		elseif ($responsibleEmp == NULL && $requestType == 1)
		{
			$this->session->set_flashdata('rfcNoRemarks', $requestID);
			$this->session->set_flashdata('projectID', $projectID);

				$this->session->set_flashdata('remarks', $remarks);

			$this->session->set_flashdata('danger', 'alert');
			$this->session->set_flashdata('alertMessage', ' Please pick an employee to be responsible for the task');

			redirect("controller/projectGantt");
		}

		else
		{
			$data = array(
				'REQUESTSTATUS' => $status,
				'REMARKS' => $remarks,
				'users_APPROVEDBY' => $_SESSION['USERID'],
				'APPROVEDDATE' => date('Y-m-d')
			);

			$this->model->updateRFC($requestID, $data);

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$taskDetails = $this->model->getTaskByID($taskID);
			$taskTitle = $taskDetails['TASKTITLE'];

			$projectID = $taskDetails['projects_PROJECTID'];
			$projectDetails = $this->model->getProjectByID($projectID);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			$details = $userName . " has " . $status . " change request for " . $taskTitle . ".";

			// it doesn't work sa deny
			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $projectID
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			// START: Notifications
			$details = $userName . " has " . $status . " your change request for " . $taskTitle . ".";

			$notificationData = array(
				'users_USERID' => $requestorID,
				'DETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'status' => 'Unread',
				'projects_PROJECTID' => $projectID,
				'tasks_TASKID' => $taskID,
				'TYPE' => '6'
			);

			$this->model->addNotification($notificationData);
			// END: Notification

			if($status == 'Approved' && $requestType == '1') // if approved change performer
			{
				$taskID = $this->input->post("task_ID");

				$updateR = $this->model->updateRACI($taskID, '1'); // change status to 'changed'
				$updateA = $this->model->updateRACI($taskID, '2'); // change status to 'changed'
				$updateC = $this->model->updateRACI($taskID, '3'); // change status to 'changed'
				$updateI = $this->model->updateRACI($taskID, '4'); // change status to 'changed'

					// SAVE/UPDATE RESPONSIBLE
					if($this->input->post("responsibleEmp"))
					{
						$responsibleEmp = $this->input->post('responsibleEmp');
						$responsibleData = array(
							'ROLE' => '1',
							'users_USERID' => $responsibleEmp,
							'tasks_TASKID' => $taskID,
							'STATUS' => 'Current'
						);
						$result = $this->model->addToRaci($responsibleData);

						// START OF LOGS/NOTIFS
						$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

						$taskDetails = $this->model->getTaskByID($taskID);
						$taskTitle = $taskDetails['TASKTITLE'];

						$projectID = $taskDetails['projects_PROJECTID'];
						$projectDetails = $this->model->getProjectByID($projectID);
						$projectTitle = $projectDetails['PROJECTTITLE'];

						$userDetails = $this->model->getUserByID($responsibleEmp);
						$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

						// START: LOG DETAILS
						$details = $userName . " has tagged " . $taggedUserName . " as responsible for " . $taskTitle . ".";

						$logData = array (
							'LOGDETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'projects_PROJECTID' => $projectID
						);

						$this->model->addToProjectLogs($logData);
						// END: LOG DETAILS

						// START: Notifications
						$details = $userName . " has tagged " . $taggedUserName . " as responsible for " . $taskTitle . ".";
						$details =  $userName . " has tagged you responsible for " . $taskTitle . " in " . $projectTitle . ".";

						$notificationData = array(
							'users_USERID' => $responsibleEmp,
							'DETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'status' => 'Unread',
							'projects_PROJECTID' => $projectID,
							'tasks_TASKID' => $taskID,
							'TYPE' => '3'
						);

						$this->model->addNotification($notificationData);
						// END: Notification
					}

					// SAVE ACCOUNTABLE
					if ($this->input->post("accountableEmp[]"))
					{
						foreach($this->input->post("accountableEmp[]") as $empID)
						{
							$accountableData = array(
								'ROLE' => '2',
								'users_USERID' => $empID,
								'tasks_TASKID' =>	$taskID,
								'STATUS' => 'Current'
							);
							$this->model->addToRaci($accountableData);

							// START OF LOGS/NOTIFS
							$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

							$taskDetails = $this->model->getTaskByID($taskID);
							$taskTitle = $taskDetails['TASKTITLE'];

							$projectID = $taskDetails['projects_PROJECTID'];
							$projectDetails = $this->model->getProjectByID($projectID);
							$projectTitle = $projectDetails['PROJECTTITLE'];

							$userDetails = $this->model->getUserByID($empID);
							$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

							// START: LOG DETAILS
							$details = $userName . " has tagged " . $taggedUserName . " as accountable for " . $taskTitle . ".";
							$details =  $userName . " has tagged you responsible for " . $taskTitle . " in " . $projectTitle . ".";

							$logData = array (
								'LOGDETAILS' => $details,
								'TIMESTAMP' => date('Y-m-d H:i:s'),
								'projects_PROJECTID' => $projectID
							);

							$this->model->addToProjectLogs($logData);
							// END: LOG DETAILS

							// START: Notifications
							$details =  $userName . " has tagged you accountable for " . $taskTitle . " in " . $projectTitle . ".";

							$notificationData = array(
								'users_USERID' => $empID,
								'DETAILS' => $details,
								'TIMESTAMP' => date('Y-m-d H:i:s'),
								'status' => 'Unread',
								'projects_PROJECTID' => $projectID,
								'tasks_TASKID' => $taskID,
								'TYPE' => '4'
							);

							$this->model->addNotification($notificationData);
							// END: Notification

						}
					}

					// SAVE CONSULTED
					if($this->input->post("consultedEmp[]"))
					{
						foreach($this->input->post("consultedEmp[]") as $empID)
						{
							$consultedData = array(
								'ROLE' => '3',
								'users_USERID' => $empID,
								'tasks_TASKID' =>	$taskID,
								'STATUS' => 'Current'
							);
							$this->model->addToRaci($consultedData);

							// START OF LOGS/NOTIFS
							$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

							$taskDetails = $this->model->getTaskByID($taskID);
							$taskTitle = $taskDetails['TASKTITLE'];

							$projectID = $taskDetails['projects_PROJECTID'];
							$projectDetails = $this->model->getProjectByID($projectID);
							$projectTitle = $projectDetails['PROJECTTITLE'];

							$userDetails = $this->model->getUserByID($empID);
							$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

							// START: LOG DETAILS
							$details = $userName . " has tagged " . $taggedUserName . " as consulted for " . $taskTitle . ".";

							$logData = array (
								'LOGDETAILS' => $details,
								'TIMESTAMP' => date('Y-m-d H:i:s'),
								'projects_PROJECTID' => $projectID
							);

							$this->model->addToProjectLogs($logData);
							// END: LOG DETAILS

							// START: Notifications
							$details =  $userName . " has tagged you consulted for " . $taskTitle . " in " . $projectTitle . ".";

							$notificationData = array(
								'users_USERID' => $empID,
								'DETAILS' => $details,
								'TIMESTAMP' => date('Y-m-d H:i:s'),
								'status' => 'Unread',
								'projects_PROJECTID' => $projectID,
								'tasks_TASKID' => $taskID,
								'TYPE' => '4'
							);

							$this->model->addNotification($notificationData);
							// END: Notification
						}
					}

					// SAVE INFORMED
					if($this->input->post("informedEmp[]"))
					{
						foreach($this->input->post("informedEmp[]") as $empID)
						{
							$informedData = array(
								'ROLE' => '4',
								'users_USERID' => $empID,
								'tasks_TASKID' =>	$taskID,
								'STATUS' => 'Current'
							);
							$this->model->addToRaci($informedData);

							// START OF LOGS/NOTIFS
							$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

							$taskDetails = $this->model->getTaskByID($taskID);
							$taskTitle = $taskDetails['TASKTITLE'];

							$projectID = $taskDetails['projects_PROJECTID'];
							$projectDetails = $this->model->getProjectByID($projectID);
							$projectTitle = $projectDetails['PROJECTTITLE'];

							// START: LOG DETAILS
							$details = $userName . " has tagged " . $taggedUserName . " as informed for " . $taskTitle . ".";

							$logData = array (
								'LOGDETAILS' => $details,
								'TIMESTAMP' => date('Y-m-d H:i:s'),
								'projects_PROJECTID' => $projectID
							);

							$this->model->addToProjectLogs($logData);
							// END: LOG DETAILS

							// START: Notifications
							$details =  $userName . " has tagged you informed for " . $taskTitle . " in " . $projectTitle . ".";
							$notificationData = array(
								'users_USERID' => $empID,
								'DETAILS' => $details,
								'TIMESTAMP' => date('Y-m-d H:i:s'),
								'status' => 'Unread',
								'projects_PROJECTID' => $projectID,
								'tasks_TASKID' => $taskID,
								'TYPE' => '4'
							);

							$this->model->addNotification($notificationData);
							// END: Notification
						}
					}
					$this->session->set_flashdata('success', 'alert');

					$this->session->set_flashdata('alertMessage', ' Request for change approved');
			} // end if appoved change Performer
			else // if approved change date
			{
				$taskID = $this->input->post("task_ID");
				$changeRequest = $this->model->getChangeRequestbyID($requestID);

				$taskData = array(
					'TASKADJUSTEDENDDATE' => $changeRequest['NEWENDDATE']
				);

				$this->model->updateTaskDates($taskID, $taskData); //save adjusted dates of requested task

				// START OF LOGS/NOTIFS
				$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

				$taskDetails = $this->model->getTaskByID($taskID);
				$taskTitle = $taskDetails['TASKTITLE'];

				$projectID = $taskDetails['projects_PROJECTID'];
				$projectDetails = $this->model->getProjectByID($projectID);
				$projectTitle = $projectDetails['PROJECTTITLE'];

				// START: LOG DETAILS
				$details = $userName . " has adjusted the end date for " . $taskTitle . ".";

				$logData = array (
					'LOGDETAILS' => $details,
					'TIMESTAMP' => date('Y-m-d H:i:s'),
					'projects_PROJECTID' => $projectID
				);

				$this->model->addToProjectLogs($logData);
				// END: LOG DETAILS

				// START: Notifications
				$details = "End Date for " . $taskTitle . " in " . $projectTitle . " has been adjusted.";

				// notify project owner
				$notificationData = array(
					'users_USERID' => $projectDetails['users_USERID'],
					'DETAILS' => $details,
					'TIMESTAMP' => date('Y-m-d H:i:s'),
					'status' => 'Unread',
					'projects_PROJECTID' => $projectID,
					'tasks_TASKID' => $taskID,
					'TYPE' => '1'
				);

				$this->model->addNotification($notificationData);

				// notify next task person
				$postTasksData['nextTaskID'] = $this->model->getPostDependenciesByTaskID($taskID);
				if($postTasksData['nextTaskID'] != NULL){

					foreach($postTasksData['nextTaskID'] as $nextTaskDetails) {

						$nextTaskID = $nextTaskDetails['tasks_POSTTASKID'];
						$postTasksData['users'] = $this->model->getRACIbyTask($nextTaskID);

						foreach($postTasksData['users'] as $postTasksDataUsers){
							$details = "End Date for " . $taskTitle . " in " . $projectTitle . " has been adjusted.";

							$notificationData = array(
								'users_USERID' => $postTasksDataUsers['users_USERID'],
								'DETAILS' => $details,
								'TIMESTAMP' => date('Y-m-d H:i:s'),
								'status' => 'Unread',
								'projects_PROJECTID' => $projectID,
								'tasks_TASKID' => $taskID,
								'TYPE' => '1'
							);

							$this->model->addNotification($notificationData);
						}
					}
				}

				// notify ACI
				$ACIdata['ACI'] = $this->model->getACIbyTask($taskID);
				if($ACIdata['ACI'] != NULL) {

					foreach($ACIdata['ACI'] as $ACIusers){

						$details = "End Date for " . $taskTitle . " in " . $projectTitle . " has been adjusted.";

						$notificationData = array(
							'users_USERID' => $ACIusers['users_USERID'],
							'DETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'status' => 'Unread',
							'projects_PROJECTID' => $projectID,
							'tasks_TASKID' => $taskID,
							'TYPE' => '4'
						);
						$this->model->addNotification($notificationData);
					}
				}
				// END: Notification
				$this->session->set_flashdata('success', 'alert');

				$this->session->set_flashdata('alertMessage', ' Request for change approved');
			} // end if approved change dates

			$data['projectProfile'] = $this->model->getProjectByID($projectID);
			$data['ganttData'] = $this->model->getAllProjectTasksGroupByTaskID($projectID);
			$data['dependencies'] = $this->model->getDependenciesByProject($projectID);
			$data['users'] = $this->model->getAllUsers();
			$data['responsible'] = $this->model->getAllResponsibleByProject($projectID);
			$data['accountable'] = $this->model->getAllAccountableByProject($projectID);
			$data['consulted'] = $this->model->getAllConsultedByProject($projectID);
			$data['informed'] = $this->model->getAllInformedByProject($projectID);
			// $data['subActivityProgress'] = $this->model->getSubActivityProgress($projectID);

			$data['employeeCompleteness'] = $this->model->compute_completeness_employeeByProject($_SESSION['USERID'], $projectID);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employeeByProject($_SESSION['USERID'], $projectID);
			$data['projectCompleteness'] = $this->model->compute_completeness_project($projectID);
			$data['projectTimeliness'] = $this->model->compute_timeliness_project($projectID);

			unset($_SESSION['rfc']);
			$this->session->set_flashdata('projectID', $projectID);
			$this->session->set_flashdata('changeRequest', 0);

			redirect("controller/projectGantt");
		}

		// $this->load->view("projectGantt", $data);
	}

	public function getUserWorkloadProjects()
	{
		$userID = $this->input->post('userID');
		$data['workloadProjects'] = $this->model->getWorkloadProjects($userID);

		echo json_encode($data);
	}

	public function getUserWorkloadTasksUnique()
	{
		$userID = $this->input->post('userID');
		$projectID = $this->input->post('projectID');
		$data['userData'] = $this->model->getUserByID($userID);
		$data['workloadTasks'] = $this->model->getWorkloadTasksUnique($userID, $projectID);

		echo json_encode($data);
	}


	public function templates()
	{
		if (!isset($_SESSION['EMAIL']) || $_SESSION['usertype_USERTYPEID'] == 5)
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['templates'] = $this->model->getAllTemplates();

			$this->load->view("templates", $data);
		}
	}

	public function archives()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['archives'] = $this->model->getAllProjectArchives();
			// $data['templates'] = $this->model->getAllTemplates();

			$this->load->view("archives", $data);
		}
	}

	public function addProjectDetails()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$id = $this->input->post("project_ID");
			$edit = $this->input->post("edit");
			$templates = $this->input->post("templates");

			// TEMPLATES
			if (isset($id))
			{
				if (isset($edit))
				{
					$this->session->set_flashdata('edit', $edit);
				}

				elseif (isset($templates))
				{
					$this->session->set_flashdata('templates', $id);
				}

				$data['project'] = $this->model->getProjectByID($id);
				$data['allTasks'] = $this->model->getAllProjectTasks($id);
				$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);
				$data['mainActivity'] = $this->model->getAllMainActivitiesByID($id);
				$data['subActivity'] = $this->model->getAllSubActivitiesByID($id);
				$data['tasks'] = $this->model->getAllTasksByIDRole1($id);

				$this->load->view("addProjectDetails", $data);
			}

			else
			{
				$this->load->view("addProjectDetails");
			}
		}
	}

	public function editProject()
	{
		$projectID = $this->input->post('project_ID');

		$data['project'] = $this->model->getProjectByID($projectID);
		$data['editAllTasks'] = $this->model->getAllProjectTasks($projectID);
		$data['editGroupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($projectID);
		$data['editMainActivity'] = $this->model->getAllMainActivitiesByID($projectID);
		$data['editSubActivity'] = $this->model->getAllSubActivitiesByID($projectID);
		$data['editTasks'] = $this->model->getAllTasksByIDRole1($projectID);
		$data['editRaci'] = $this->model->getRaci($projectID);
		$data['editUsers'] = $this->model->getAllUsers();

		$this->session->set_flashdata('edit', $projectID);

		$this->load->view('addProjectDetails', $data);
	}

	public function myCalendar()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("myCalendar");
		}
	}

	public function documents()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{

			$data['documents'] = $this->model->getAllDocuments();
			$this->load->view("documents", $data);
		}
	}

	public function reports()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			if($_SESSION['departments_DEPARTMENTID'] == 1)
			{
				$data['allProjects'] = $this->model->getAllProjects();
				$data['allOngoingProjects'] = $this->model->getAllOngoingAndDelayedProjects();
			}
			else
			{
				$data['allProjects'] = $this->model->getAllProjectsOwnedByUser($_SESSION['USERID']);
				$data['allOngoingProjects'] = $this->model->getAllOngoingPOProjects($_SESSION['USERID']);
			}

			$this->load->view("reports", $data);
		}
	}

	// REPORTS STARTS

	public function reportsDepartmentPerformance()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{

			$data['allProjects'] = $this->model->getAllProjects();
			$data['departments'] = $this->model->getAllDepartmentsWithoutExecutive();
			$data['projectCompleteness'] = $this->model->compute_completeness_allProjects();
			$data['projectTimeliness'] = $this->model->compute_timeliness_allProjects();
			$data['departmentPerformance'] = $this->model->compute_departmentPerformance();

			foreach($data['departments'] as $department)
			{
				$data['departmentPerf' . $department['DEPARTMENTID']] = $this->model->getDeptPerformance($department['DEPARTMENTID']);
				// $data['departmentTimeliness' . $department['DEPARTMENTID']] = $this->model->getDeptTimeliness($department['DEPARTMENTID']);
			}

			$this->load->view("reportsDepartmentPerformance", $data);
		}
	}

	public function reportsProjectSummary()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$projectID = $this->input->post('project');

			if ($projectID == NULL)
			{
				$this->session->set_flashdata('danger', 'alert');
				$this->session->set_flashdata('alertMessage', ' You did not select a project');

				redirect('controller/reports');
			}

			else
			{
				$data['project'] = $this->model->getProjectByID($projectID);
				$data['mainActivity'] = $this->model->getAllMainActivitiesByID($projectID);
				$data['subActivity'] = $this->model->getAllSubActivitiesByID($projectID);
				$data['tasks'] = $this->model->getAllTasksByIDRole1($projectID);
				$data['earlyTasks'] = $this->model->getAllEarlyTasksByIDRole1($projectID);
				$data['delayedTasks'] = $this->model->getAllDelayedTasksByIDRole1($projectID);
				$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($projectID);
				$data['changeRequests'] = $this->model->getChangeRequestsByProject($projectID);
				$data['documents'] = $this->model->getAllDocumentsByProject($projectID);
				$data['projectCompleteness'] = $this->model->compute_completeness_project($projectID);
				$data['projectTimeliness'] = $this->model->compute_timeliness_project($projectID);
				$data['departmentTimeliness'] = $this->model->compute_timeliness_departmentByProject($projectID);
				$data['departmentCompleteness'] = $this->model->compute_completeness_departmentByProject($projectID);
				$data['team'] = $this->model->getTeamByProject($projectID);
				$data['users'] = $this->model->getAllUsers();
				$data['allDepartments'] = $this->model->getAllDepartmentsByProjectByRole($projectID);
				$data['taskCount'] = $this->model->getTaskCountByProjectByRole($projectID);
				$data['employeeTimeliness'] = $this->model->compute_timeliness_employeesByProject($projectID);
				$data['delayedTaskCount'] = $this->model->getDelayedTaskCount($projectID);

				// foreach ($data['employeeTimeliness'] as $key => $value) {
				// 	echo $value['USERID'] . "<br>";
				// 	echo $value['timeliness1'] . "<br>";
				// 	echo $value['timeliness2'] . "<br>";
				// 	echo $value['timeliness'] . "<br><br>";
				//
				// }


				$this->load->view("reportsProjectSummary", $data);
			}
		}
	}

	public function reportsEmployeePerformance()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['userInfo'] = $this->model->getUserByID($_SESSION['USERID']);
			$data['departments'] = $this->model->getAllDepartments();
			$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);
			$data['raci'] = $this->model->getAllACI();
			$data['projectCount'] = $this->model->getProjectCountRole1($_SESSION['USERID']);
			$data['taskCount'] = $this->model->getTaskCountRole1($_SESSION['USERID']);
			$data['changeRequests'] = $this->model->getChangeRequestsByUser($_SESSION['USERID']);
			$data['completeness'] = $this->model->compute_completeness_employee($_SESSION['USERID']);
			$data['timeliness'] = $this->model->compute_timeliness_employee($_SESSION['USERID']);
			$data['projectPerformance'] = $this->model->compute_employeePerformance_perProject($_SESSION['USERID']);

			$this->load->view("reportsEmployeePerformance", $data);
		}
	}

	public function reportsTeamPerformance()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}
		else
		{
			$deptID = $_SESSION['departments_DEPARTMENTID'];
			$data['deptName'] = $_SESSION['DEPARTMENTNAME'];

			$data['deptHead'] = $this->model->getDepartmentHeadByDepartmentID($_SESSION['departments_DEPARTMENTID']);
			if($_SESSION['usertype_USERTYPEID'] == '3') //managers
				$data['userTeam'] = $this->model->getAllUsersByDepartment($_SESSION['departments_DEPARTMENTID']);
			else if($_SESSION['usertype_USERTYPEID'] == '4') //supervisors
				$data['userTeam'] = $this->model->getUserTeam($_SESSION['USERID']);

			$taskCondition = "raci.STATUS = 'Current' && raci.ROLE = '1' && departments_DEPARTMENTID = " . $deptID . " && tasks.CATEGORY = 3";

			$data['employeePerformance'] = $this->model->compute_employeePerformance_byDepartments($deptID);
			$data['taskCount'] = $this->model->getTaskCountPerDepartment($deptID, $taskCondition);
			$data['projectCount'] = $this->model->getProjectCountPerDepartment($deptID);
			$data['delayedCount'] = $this->model->getDelayedTaskCountPerDepartment($deptID);

			$this->load->view("reportsTeamPerformance", $data);
		}
	}

	public function reportsProjectPerformance()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}
		else
		{
			$projectID = $this->input->post('project');

			if ($projectID == NULL)
			{
				$this->session->set_flashdata('danger', 'alert');
				$this->session->set_flashdata('alertMessage', ' You did not select a project');

				redirect('controller/reports');
			}

			else
			{
				$data['project'] = $this->model->getProjectByID($projectID);
				$data['users'] = $this->model->getAllUsers();
				$data['delayedTasks'] = $this->model->getAllDelayedTasksByIDRole1($projectID);
				$data['raci'] = $this->model->getAllACI();
				$data['departments'] = $this->model->getAllDepartmentsByProjectByRole($projectID);
				$data['departmentCompleteness'] = $this->model->compute_completeness_departmentByProject($projectID);
				$data['departmentTimeliness'] = $this->model->compute_timeliness_departmentByProject($projectID);
				$data['projectCompleteness'] = $this->model->compute_completeness_project($projectID);
				$data['projectTimeliness'] = $this->model->compute_timeliness_project($projectID);

				$this->load->view("reportsProjectPerformance", $data);
			}
		}
	}

	public function reportsProjectProgress()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$interval = $this->input->post('interval');

			if ($interval == NULL)
			{
				$this->session->set_flashdata('danger', 'alert');
				$this->session->set_flashdata('alertMessage', ' You did not select an interval');

				redirect('controller/reports');
			}

			else
			{
				$projectID = $this->input->post("project");

				if ($projectID == NULL)
				{
					$this->session->set_flashdata('danger', 'alert');
					$this->session->set_flashdata('alertMessage', ' You did not select a project');

					redirect('controller/reports');
				}

				else
				{
					$data['interval'] = $this->input->post("interval");
					if($data['interval'] == '7')
						$data['intervalWord'] = "Week";
					else
						$data['intervalWord'] = "Month";

					$data['project'] = $this->model->getProjectByID($projectID);
					$data['users'] = $this->model->getAllUsers();
					$data['mainActivities'] = $this->model->getMainActivitiesByProject($projectID);
					$data['subActivities'] = $this->model->getSubActivitiesByProject($projectID);

					foreach($data['mainActivities'] as $mainActivity)
					{
						foreach($data['subActivities'] as $subActivity)
						{
							$data['accomplishedTasks' . $subActivity['TASKID']] = $this->model->getAccomplishedTasks($projectID, $subActivity['TASKID'], $data['interval']);
						}
					}

					// Main Completeness
					$mainCompletenessFound = $this->model->checkMainCompleteness($projectID);
					if($mainCompletenessFound == NULL)
					{

						$data['mainActivities'] = $this->model->getMainActivitiesByProject($projectID);
						$data['subActivities'] = $this->model->getSubActivitiesByProject($projectID);
						$data['allTasks'] = $this->model->getAllProjectTasksGroupByTaskID($projectID);
						$data['weight'] = $this->model->compute_completeness_project($projectID);

						$weight = $data['weight']['weight'];

						$mainActCompleteness = array();
						$subActCompleteness = array();

						// SET COMPLETENESS
						foreach ($data['mainActivities'] as $mainCompKey => $mainCompValue)
						{
							$mainCompleteness = 0;

							foreach ($data['subActivities'] as $subCompKey => $subCompValue)
							{
								if ($subCompValue['tasks_TASKPARENT'] == $mainCompValue['TASKID'])
								{
									$subCompleteness = 0;

									foreach ($data['allTasks'] as $taskCompKey => $taskCompValue)
									{
										if ($taskCompValue['CATEGORY'] == 3  && $taskCompValue['TASKSTATUS'] == 'Complete')
										{
											if ($taskCompValue['tasks_TASKPARENT'] == $subCompValue['TASKID'])
											{
												$subCompleteness += $weight;
											}
										}
									}

									$mainCompleteness += $subCompleteness;

									$subCompleteness = array(
										'TASKID' => $subCompValue['TASKID'],
										'subCompleteness' => round($subCompleteness, 2),
										'tasks_TASKPARENT' => $mainCompValue['TASKID']
									);

									array_push($subActCompleteness, $subCompleteness);
								}
							}

							$mainCompleteness = array(
								'tasks_MAINID' => $mainCompValue['TASKID'],
								'COMPLETENESS' => round($mainCompleteness, 2),
								'projects_PROJECTID' => $projectID,
								'TYPE' => 2,
								'DATE' => date('Y-m-d')
							);

							array_push($mainActCompleteness, $mainCompleteness);

							$addAssessment = $this->model->addProjectAssessment($mainCompleteness);
						}
					}

					$data['pastProgress'] = $this->model->getAssessmentByMain($data['interval'], $projectID);
					$data['currentProgress'] = $this->model->getCurrentAssessmentByMain($projectID);

					$data['taskWeight'] = $this->model->getTaskWeightByProject($projectID);
					$data['taskCountMain'] = array();
					$data['allTasks'] = $this->model->getAllProjectTasksGroupByTaskID($projectID);

					$mainActCompleteness = array();
					$subActCompleteness = array();
					$totalWeight = 0;

					// GET TASK COUNT PER MAIN
					foreach ($data['mainActivities'] as $mainCompKey => $mainCompValue)
					{
						$mainCompleteness = 0;

						foreach ($data['subActivities'] as $subCompKey => $subCompValue)
						{
							if ($subCompValue['tasks_TASKPARENT'] == $mainCompValue['TASKID'])
							{
								$subCompleteness = 0;

								foreach ($data['allTasks'] as $taskCompKey => $taskCompValue)
								{
									if ($taskCompValue['CATEGORY'] == 3  && $taskCompValue['TASKSTATUS'] == 'Complete')
									{
										if ($taskCompValue['tasks_TASKPARENT'] == $subCompValue['TASKID'])
										{
											$subCompleteness += $data['taskWeight'];
										}
									}
								}

								$mainCompleteness += $subCompleteness;

								array_push($subActCompleteness, $subCompleteness);
							}
						}

						$mainData = array(
							'TASKID' =>  $mainCompValue['TASKID'],
							'weight' => $mainCompleteness
						);

						array_push($mainActCompleteness, $mainData);
					}

					$data['mainWeight'] = $mainActCompleteness;

					$this->load->view("reportsProjectProgress", $data);
				}
			}
		}
	}

	public function reportsProjectStatus()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$interval = $this->input->post('interval');

			if ($interval == NULL)
			{
				$this->session->set_flashdata('danger', 'alert');
				$this->session->set_flashdata('alertMessage', ' You did not select an interval');

				redirect('controller/reports');
			}

			else
			{
				$projectID = $this->input->post("project");

				if ($projectID == NULL)
				{
					$this->session->set_flashdata('danger', 'alert');
					$this->session->set_flashdata('alertMessage', ' You did not select a project');

					redirect('controller/reports');
				}

				else
				{
					$data['interval'] = $this->input->post("interval");
					if($data['interval'] == '7')
						$data['intervalWord'] = "Week";
					else
						$data['intervalWord'] = "Month";

					$data['project'] = $this->model->getProjectByID($projectID);
					$data['users'] = $this->model->getAllUsers();
					$data['ongoingTasks'] = $this->model->getAllOngoingDelayedTasksByIDRole1($projectID);
					$data['accomplishedLast'] = $this->model->getAccomplishedLast($projectID, $data['interval']);
					$data['problemTasks'] = $this->model->getProblemTasks($projectID, $data['interval']);
					$data['plannedNext'] = $this->model->getPlannedNext($projectID, $data['interval']);
					$data['pendingRFC'] = $this->model->getPendingRFCNext($projectID, $data['interval']);
					$data['pendingRACI'] = $this->model->getPendingRaci($projectID);

					$this->load->view("reportsProjectStatus", $data);
				}
			}
		}
	}
	// REPORTS END

	public function dashboardAdmin()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['departments'] = $this->model->getAllDepartmentsForAdmin();
			$data['users'] = $this->model->getAllUsersForAdmin();

			$this->load->view("dashboardAdmin", $data);
		}
	}

	public function manageDepartments()
	{
		if (!isset($_SESSION['EMAIL']) || $_SESSION['usertype_USERTYPEID'] == 5)
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['departments'] = $this->model->getAllDepartmentsForAdmin();
			$data['deptHeads'] = $this->model->getAllDepartmentHeadsForAdmin();

			$this->load->view("manageDepartments", $data);
		}
	}

	public function manageUsers()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['users'] = $this->model->getAllUsersForAdmin();
			$data['departments'] = $this->model->getAllDepartments();
			$data['userTypes'] = $this->model->getAllUserTypesForAdmin();

			$this->load->view("manageUsers", $data);
		}
	}

	public function manageUserTypes()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['userTypes'] = $this->model->getAllUserTypesForAdmin();

			$this->load->view("manageUserTypes", $data);
		}
	}

	public function addNewUser()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$newUser = array(
				'FIRSTNAME' => $this->input->post("firstName"),
				'LASTNAME' => $this->input->post("lastName"),
				'EMAIL' => $this->input->post("email"),
				'PASSWORD' => $this->input->post("password"),
				'POSITION' => $this->input->post("position"),
				'departments_DEPARTMENTID' => $this->input->post("department"),
				'usertype_USERTYPEID' => $this->input->post("userType"),
				'users_SUPERVISORS' => $this->input->post("supervisor"),
				'isAct' => $this->input->post("active"),
				'JOBDESCRIPTION' => $this->input->post("jobDesc")
			);

			if($this->model->addNewUser($newUser))
			{
				$this->session->set_flashdata('success', 'alert');
				$this->session->set_flashdata('alertMessage', ' New user added successfully');
				redirect('controller/manageUsers');
			}
		}
	}

	public function editUser()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$oldUser = array(
				'FIRSTNAME' => $this->input->post("firstName"),
				'LASTNAME' => $this->input->post("lastName"),
				'EMAIL' => $this->input->post("email"),
				'PASSWORD' => $this->input->post("password"),
				'POSITION' => $this->input->post("position"),
				'departments_DEPARTMENTID' => $this->input->post("department"),
				'usertype_USERTYPEID' => $this->input->post("userType"),
				'users_SUPERVISORS' => $this->input->post("supervisor"),
				'isAct' => $this->input->post("active"),
				'JOBDESCRIPTION' => $this->input->post("jobDesc")
			);

			if($this->model->updateUser($oldUser, $this->input->post("user_ID")))
			{
				$this->session->set_flashdata('success', 'alert');
				$this->session->set_flashdata('alertMessage', ' User details updated successfully');
				redirect('controller/manageUsers');
			}
		}
	}

	public function getUserDetails()
	{
		$userID = $this->input->post("user_ID");
		$data['userEdit'] = $this->model->getUserByID($userID);

		echo json_encode($data);
	}

	public function addNewDepartment()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$newDept = array(
				'DEPARTMENTNAME' => $this->input->post("deptName"),
				'DEPT' => strtoupper($this->input->post("dept")),
				'users_DEPARTMENTHEAD' => $this->input->post("deptHead"),
				'isAct' => $this->input->post("active")
			);

			if($this->model->addNewDepartment($newDept))
			{
				$this->session->set_flashdata('success', 'alert');
				$this->session->set_flashdata('alertMessage', ' New department added successfully');
				redirect('controller/manageDepartments');
			}
		}
	}

	public function editDepartment()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$oldDept = array(
				'DEPARTMENTNAME' => $this->input->post("deptName"),
				'DEPT' => strtoupper($this->input->post("dept")),
				'users_DEPARTMENTHEAD' => $this->input->post("deptHead"),
				'isAct' => $this->input->post("active")
			);

			if($this->model->updateDepartment($oldDept, $this->input->post("dept_ID")))
			{
				$this->session->set_flashdata('success', 'alert');
				$this->session->set_flashdata('alertMessage', ' Department details updated successfully');
				redirect('controller/manageDepartments');
			}
		}
	}

	public function getDeptDetails()
	{
		$userID = $this->input->post("dept_ID");
		$data['deptEdit'] = $this->model->getDepartmentByID($userID);

		echo json_encode($data);
	}

	public function projectLogs()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$id = $this->input->post("projectID_logs");
			$this->session->set_flashdata('projectIDlogs', $id);
			$data['projectLog'] = $this->model->getProjectLogs($id);
			$data['projectID'] = $id;
			$data['projectProfile'] = $this->model->getProjectByID($id);
			$this->load->view("projectLogs", $data);
		}
	}

	public function addUserType()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$newUserType = array(
				'USERTYPE' => $this->input->post("userType"),
				'isAct' => $this->input->post("active")
			);

			if($this->model->addNewUserType($newUserType))
			{
				$this->session->set_flashdata('success', 'alert');
				$this->session->set_flashdata('alertMessage', ' New user type added successfully');
				redirect('controller/manageUserTypes');
			}
		}
	}

	public function getUserTypeDetails()
	{
		$usertypeID = $this->input->post("usertype_ID");
		$data['usertypeEdit'] = $this->model->getUserTypeByID($usertypeID);

		echo json_encode($data);
	}

	public function editUserType()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$usertypeID = $this->input->post("usertype_ID");

			$oldUserType = array(
				'USERTYPE' => $this->input->post("userType"),
				'isAct' => $this->input->post("active")
			);

			if($this->model->updateUserType($oldUserType, $usertypeID))
			{
				$this->session->set_flashdata('success', 'alert');
				$this->session->set_flashdata('alertMessage', ' User type updated successfully');
				redirect('controller/manageUserTypes');
			}
		}
	}

	public function teamGantt()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$id = $this->input->post("project_ID");
			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['ganttData'] = $this->model->getAllProjectTasks($id);
			$data['dependencies'] = $this->model->getDependenciesByProject($id);
			$data['users'] = $this->model->getAllUsers();

			$departmentID = $_SESSION['departments_DEPARTMENTID'];

			$data['ganttData'] = $this->model->getAllProjectTasksByDepartment($id, $departmentID);

			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['ganttData'] = $this->model->getAllProjectTasksByDepartment($id, $departmentID);
			$data['dependencies'] = $this->model->getDependenciesByProject($id);
			$data['users'] = $this->model->getAllUsers();

			$data['responsible'] = $this->model->getAllResponsibleByProject($id);
			$data['accountable'] = $this->model->getAllAccountableByProject($id);
			$data['consulted'] = $this->model->getAllConsultedByProject($id);
			$data['informed'] = $this->model->getAllInformedByProject($id);

			$data['employeeCompleteness'] = $this->model->compute_completeness_employeeByProject($_SESSION['USERID'], $id);
			$deptC = $this->model->compute_completeness_departmentByProject($id);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employeeByProject($_SESSION['USERID'], $id);
			$deptT = $this->model->compute_timeliness_departmentByProject($id);

			foreach($deptC as $dc){
				if($dc['DEPARTMENTID'] == $departmentID){
					$data['departmentCompleteness'] = $dc;
				}
			}

			foreach($deptT as $dt){
				if($dt['DEPARTMENTID'] == $_SESSION['departments_DEPARTMENTID']){
					$data['departmentTimeliness'] = $dt;
				}
			}

			$this->load->view("teamGantt", $data);
		}
	}

	public function taskTodo()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);
			$data['projects'] = $this->model->getAllTasksByUserByProject($_SESSION['USERID']);
			$data['projectsToDo'] = $this->model->getAllTasksByUserByProjectToDo($_SESSION['USERID']);

			$this->load->view("taskTodo", $data);
		}
	}

	public function taskDelegate()
	{
		if (!isset($_SESSION['EMAIL']) || $_SESSION['usertype_USERTYPEID'] == 5)
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$dashboard = $this->input->post("dashboard");
			if (isset($dashboard))
			{
				$this->session->set_flashdata('dashboard', $dashboard);
			}
			switch($_SESSION['usertype_USERTYPEID'])
			{
				case '2':
					$filter = "users.usertype_USERTYPEID = '3'";
					break;

				case '3':
					$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
					break;

				case '4':
					$filter = "(users.usertype_USERTYPEID = '3' &&  users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."')
					|| users.users_SUPERVISORS = '" . $_SESSION['USERID'] ."' && users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'
					|| users.USERID = '" . $_SESSION['USERID'] . "'";
					break;

				default:
					$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
					break;
			}

			$data['delegateTasksByProject'] = $this->model->getAllProjectsToEditByUser($_SESSION['USERID'], "projects.PROJECTID");
			$data['delegateTasks'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID']);
			$data['departments'] = $this->model->getAllDepartments();
			$data['users'] = $this->model->getAllUsers();
			$data['wholeDept'] = $this->model->getAllUsersByUserType($filter);
			$data['projectCount'] = $this->model->getProjectCount();
			$data['taskCount'] = $this->model->getTaskCount();

			$this->load->view("taskDelegate", $data);
		}
	}

	public function taskMonitor()
	{
		if (!isset($_SESSION['EMAIL']) || $_SESSION['usertype_USERTYPEID'] == 5)
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['allTasks'] = $this->model->getAllTasksToMonitor();

			$data['allPlannedACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Planning");
			$data['uniquePlannedACItasks'] = $this->model->getUniqueACITasksByUser($_SESSION['USERID'], "Planning");

			$data['allOngoingACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Ongoing");
			$data['uniqueOngoingACItasks'] = $this->model->getUniqueACITasksByUser($_SESSION['USERID'], "Ongoing");

			$data['allCompletedACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Complete");
			$data['uniqueCompletedACItasks'] = $this->model->getUniqueACITasksByUser($_SESSION['USERID'], "Complete");

			$data['allOngoingACIprojects'] = $this->model->getUniqueACIOngoingProjectsByUserByProject($_SESSION['USERID'], "Ongoing");
			$data['allACIprojects'] = $this->model->getUniqueACIProjectsByUserByProject($_SESSION['USERID']);

			$this->load->view("taskMonitor", $data);
		}
	}

	public function getRACIByTaskID()
	{
		$taskID = $this->input->post('taskID');
		$data['raci'] = $this->model->getRACIbyTask($taskID);

		$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";

		$data['departments'] = $this->model->getAllDepartments();
		$data['users'] = $this->model->getAllUsers();
		$data['wholeDept'] = $this->model->getAllUsersByDepartment($_SESSION['departments_DEPARTMENTID']);
		$data['projectCount'] = $this->model->getProjectCount();
		$data['taskCount'] = $this->model->getTaskCount();

		echo json_encode($data);
	}

	public function setDelegationRestriction()
	{
		$data['delegateTasksByProject'] = $this->model->getAllProjectsToEditByUser($_SESSION['USERID'], "projects.PROJECTID");
		$data['delegateTasks'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID']);

		echo json_encode($data);
	}

	public function notifications()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['notification'] = $this->model->getAllNotificationsByUser();

			$this->load->view("notifications", $data);
		}
	}

	public function projectSummary()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$projectID = $this->input->post('project_ID');
			$templateProjSummary = $this->input->post('templateProjSummary');

			$data['project'] = $this->model->getProjectByID($projectID);
			$data['mainActivity'] = $this->model->getAllMainActivitiesByID($projectID);
			$data['subActivity'] = $this->model->getAllSubActivitiesByID($projectID);
			$data['tasks'] = $this->model->getAllTasksByIDRole1($projectID);
			$data['earlyTasks'] = $this->model->getAllEarlyTasksByIDRole1($projectID);
			$data['delayedTasks'] = $this->model->getAllDelayedTasksByIDRole1($projectID);
			$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($projectID);
			$data['changeRequests'] = $this->model->getChangeRequestsByProject($projectID);
			$data['documents'] = $this->model->getAllDocumentsByProject($projectID);
			$data['projectCompleteness'] = $this->model->compute_completeness_project($projectID);
			$data['projectTimeliness'] = $this->model->compute_timeliness_project($projectID);
			$data['departments'] = $this->model->compute_timeliness_departmentByProject($projectID);
			$data['team'] = $this->model->getTeamByProject($projectID);
			$data['users'] = $this->model->getAllUsers();
			$data['allDepartments'] = $this->model->getAllDepartments();
			$data['taskCount'] = $this->model->getTaskCountByProjectByRole($projectID);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employeesByProject($projectID);

			$this->load->view("projectSummary", $data);
		}
	}

// DELETE THIS MAYBE?
	public function newProjectTask()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("newProjectTask");
		}
	}

	public function addMainActivities()
	{
	  if (isset($_SESSION['USERID']))
	  {
	      // IMPORT PROJECT
	    if ($this->input->post('isImport') == 1)
	    {
	      $config['upload_path'] = './assets/uploads/templates';
	      $config['allowed_types'] = 'xlsx|xls';
	      $config['max_size'] = '10000000';
	      $this->load->library('upload', $config);
	      $this->upload->initialize($config);

	      if (!$this->upload->do_upload('uploadFile'))
	      {
	        $error = array('error' => $this->upload->display_errors());

	         $this->session->set_flashdata('danger', 'alert');
	         $this->session->set_flashdata('alertMessage', $error['error']);

	         redirect('controller/addProjectDetails');
	      }

	       else
	       {
	         $data = array('upload_data' => $this->upload->data());
	         $path = './assets/uploads/templates/';

	         // PROJECT DETAILS

	         $import_xls_file = $data['upload_data']['file_name'];
	         $inputFileName = $path . $import_xls_file;

	         try
	         {
						 $sheetname = 'Project Details';
	          $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
	          $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
						$reader->setReadDataOnly(true);
	          $reader->setLoadSheetsOnly($sheetname);
	          $spreadsheet = $reader->load($inputFileName);
	          $worksheet = $spreadsheet->getActiveSheet()->toArray(NULL, 'true', 'true', 'true');

	          // CHECK IF DATA IN PROJECT DETAILS SHEET IS VALID
	          $title = $spreadsheet->getActiveSheet()->getCell('B1')->getValue();
	          $description = $spreadsheet->getActiveSheet()->getCell('B2')->getValue();
	          $startDate = $spreadsheet->getActiveSheet()->getCell('B3')->getFormattedValue();
	          $endDate = $spreadsheet->getActiveSheet()->getCell('B4')->getFormattedValue();
	          $actualEndDate = $spreadsheet->getActiveSheet()->getCell('B5')->getFormattedValue();
	          $status = $spreadsheet->getActiveSheet()->getCell('B6')->getValue();
						$type = $spreadsheet->getActiveSheet()->getCell('B7')->getValue();

	          // CHECK IF SPREADSHEET IS NULL/BLANK
	          if ($title == NULL || $description == NULL || $startDate == NULL || $endDate == NULL || $status == NULL || $type == NULL)
	          {
	            $this->session->set_flashdata('danger', 'alert');
	            $this->session->set_flashdata('alertMessage', ' Please make sure all required fields in Project Details sheet are filled');

	            unlink($inputFileName);

	            redirect('controller/addProjectDetails');
	          }

						if (DateTime::createFromFormat('Y-m-d', $startDate) == FALSE)
						{
							$this->session->set_flashdata('danger', 'alert');
							$this->session->set_flashdata('alertMessage', ' Project Start Date in Project Details is not a valid date');

							unlink($inputFileName);

							redirect('controller/addProjectDetails');
						}

						if (DateTime::createFromFormat('Y-m-d', $endDate) == FALSE)
						{
							$this->session->set_flashdata('danger', 'alert');
							$this->session->set_flashdata('alertMessage', ' Project End Date in Project Details is not a valid date');

							unlink($inputFileName);

							redirect('controller/addProjectDetails');
						}

            // PROJECT ASSESSMENT
            // CHECK IF SPREADSHEET IS NULL/BLANK
            $sheetname = 'Project Assessment';

            //DATA VALIDATION FOR IMPORT
						$filterSubset = new assessmentFilter();
						$reader->setReadFilter($filterSubset);
            $reader->setLoadSheetsOnly($sheetname);
            $spreadsheet = $reader->load($inputFileName);
            $worksheet = $spreadsheet->getActiveSheet()->toArray(NULL, 'true', 'true', 'true');

            foreach ($worksheet as $assessmentKey => $checkAssessment)
            {
              if ($assessmentKey != 1)
              {
                if ($assessmentKey == 2)
                {
                  if ($checkAssessment['A'] == NULL || $checkAssessment['B'] == NULL || $checkAssessment['C'] == NULL)
                  {
                    $this->session->set_flashdata('danger', 'alert');
                    $this->session->set_flashdata('alertMessage', ' All fields in the first row of Project Assessment are required');

                    unlink($inputFileName);

                    redirect('controller/addProjectDetails');
                  }

									if ($checkAssessment['A'] != $startDate)
									{
										$this->session->set_flashdata('danger', 'alert');
                    $this->session->set_flashdata('alertMessage', ' Date in the first row should be the same as the Project Start Date');

                    unlink($inputFileName);

                    redirect('controller/addProjectDetails');
									}
                }

                else
                {
                  if (($checkAssessment['A'] != NULL && $checkAssessment['B'] == NULL) || ($checkAssessment['A'] != NULL && $checkAssessment['C'] == NULL))
                  {
                    $this->session->set_flashdata('danger', 'alert');
                    $this->session->set_flashdata('alertMessage', ' Please make sure that all fields in row ' . $assessmentKey . ' in Project Assessment are filled');

                    unlink($inputFileName);

                    redirect('controller/addProjectDetails');
                  }

                  else
                  {
                    // CHECK IF DATE IS VALID
                    if (DateTime::createFromFormat('Y-m-d', $checkAssessment['A']) !== FALSE)
                    {
											if ($assessmentKey == 3)
											{
												$prevRow = array();
												$prevRow['A'] = $startDate;
											}

											else
											{
												$prevIndex = $assessmentKey - 1;
												$prevRow = $worksheet[$prevIndex];
											}

											// CHECK IF DATES ARE SEQUENTIAL
											$date_plusOne = date_add(date_create($prevRow['A']), date_interval_create_from_date_string("1 days"));

											if ($checkAssessment['A'] != $date_plusOne->format('Y-m-d'))
											{
												$this->session->set_flashdata('danger', 'alert');
	                      $this->session->set_flashdata('alertMessage', ' Date in row ' . $assessmentKey . ' in Project Assessment is not sequential');

	                      unlink($inputFileName);

	                      redirect('controller/addProjectDetails');
											}
                    }

                    else
                    {
                      $this->session->set_flashdata('danger', 'alert');
                      $this->session->set_flashdata('alertMessage', ' Date in row ' . $assessmentKey . ' in Project Assessment is not valid');

                      unlink($inputFileName);

                      redirect('controller/addProjectDetails');
                    }
                  }
                }

								// CHECK IF IT IS A NUMBER
								if (!is_Numeric($checkAssessment['B']))
								{
									$this->session->set_flashdata('danger', 'alert');
									$this->session->set_flashdata('alertMessage', ' Completeness in row ' . $assessmentKey . ' in Project Assessment is a number');

									unlink($inputFileName);

									redirect('controller/addProjectDetails');
								}

								// CHECK IF NUMBER HAS 2 DECIMAL PLACES
								if (!preg_match('/\.\d{2,}/', $checkAssessment['B']))
								{
									$this->session->set_flashdata('danger', 'alert');
									$this->session->set_flashdata('alertMessage', ' Completeness in row ' . $assessmentKey . ' in Project Assessment does not have 2 decimal places');

									unlink($inputFileName);

									redirect('controller/addProjectDetails');
								}

								// CHECK IF IT IS A NUMBER
								if (!is_Numeric($checkAssessment['C']))
								{
									$this->session->set_flashdata('danger', 'alert');
									$this->session->set_flashdata('alertMessage', ' Timeliness in row ' . $assessmentKey . ' in Project Assessment is not a number');

									unlink($inputFileName);

									redirect('controller/addProjectDetails');
								}

								// CHECK IF NUMBER HAS 2 DECIMAL PLACES
								if (!preg_match('/\.\d{2,}/', $checkAssessment['C']))
								{
									$this->session->set_flashdata('danger', 'alert');
									$this->session->set_flashdata('alertMessage', ' Timeliness in row ' . $assessmentKey . ' in Project Assessment does not have 2 decimal places');

									unlink($inputFileName);

									redirect('controller/addProjectDetails');
								}
              }
            }

					  $sheetname = 'Tasks';

					  //DATA VALIDATION FOR IMPORT
						$filterSubset = new tasksFilter();
						$reader->setReadFilter($filterSubset);
					  $reader->setLoadSheetsOnly($sheetname);
					  $spreadsheet_tasks = $reader->load($inputFileName);
					  $worksheet_tasks = $spreadsheet_tasks->getActiveSheet()->toArray(NULL, 'true', 'true', 'true');

						$mainActivityTitles = array();

					  foreach ($worksheet_tasks as $checkRow => $checkCell)
					  {
					    // CHECK IF BLANK
					    if ($checkCell['A'] == NULL || $checkCell['B'] == NULL || $checkCell['C'] == NULL || $checkCell['E'] == NULL || $checkCell['G'] == NULL)
					    {
					      $this->session->set_flashdata('danger', 'alert');
					      $this->session->set_flashdata('alertMessage', ' Please make sure that all fields in row ' . $checkRow . ' in Tasks are filled');

					      unlink($inputFileName);

					      redirect('controller/addProjectDetails');
					    }

					    else
					    {
					      if ($checkRow != 1)
					      {
					        // CEHCK IF START DATE IS VALIDE
					        if (DateTime::createFromFormat('Y-m-d', $checkCell['B']) !== FALSE)
					        {
					          // CHECK IF END DATE IS VALID
					          if (DateTime::createFromFormat('Y-m-d', $checkCell['C']) !== FALSE)
					          {
					            // CHECK IF DATE IS IN RANGE OF PROJECT START AND END DATE
					            $projStart_ts = strtotime($startDate);
					            $projEnd_ts = strtotime($endDate);
					            $startCell_ts = strtotime($checkCell['B']);
					            $endCell_ts = strtotime($checkCell['C']);

					            if ($startCell_ts >= $projStart_ts)
					            {
					              if ($endCell_ts <= $projEnd_ts)
					              {
					                // CHECK IF TASK IS COMPLETE AND ACTUAL END DATE IS FILLED
					                if ($checkCell['D'] == NULL && $checkCell['E'] == 'Complete')
					                {
					                  $this->session->set_flashdata('danger', 'alert');
					                  $this->session->set_flashdata('alertMessage', ' Actual End Date in row ' . $checkRow . ' is required');

					                  unlink($inputFileName);

					                  redirect('controller/addProjectDetails');
					                }

					                else
					                {
					                  // CHECK IF COMPLETE TASK HAS ACTUAL END DATE
					                  if ($checkCell['D'] != NULL && $checkCell['E'] != 'Complete')
					                  {
					                    $this->session->set_flashdata('danger', 'alert');
					                    $this->session->set_flashdata('alertMessage', ' Status in row ' . $checkRow . ' should be <i>Complete</i>');

					                    unlink($inputFileName);

					                    redirect('controller/addProjectDetails');
					                  }

					                  else
					                  {
					                    // CHECK IF DELAYED TASK HAS REMARKS
					                    if ($checkCell['F'] == NULL && ($checkCell['E'] == 'Complete' && $checkCell['C'] < $checkCell['D'] && $checkCell['G'] == 3))
					                    {
					                      $this->session->set_flashdata('danger', 'alert');
					                      $this->session->set_flashdata('alertMessage', ' Remarks in row ' . $checkRow . ' is required for delayed tasks');

					                      unlink($inputFileName);

					                      redirect('controller/addProjectDetails');
					                    }

					                    else
					                    {
					                      // CHECK IF MAIN ACT HAS NO TASK PARENT
					                      if ($checkCell['G'] == 1 && $checkCell['H'] != NULL)
					                      {
					                        $this->session->set_flashdata('danger', 'alert');
					                        $this->session->set_flashdata('alertMessage', ' Task in row ' . $checkRow . ' should not have a Task Parent');

					                        unlink($inputFileName);

					                        redirect('controller/addProjectDetails');
					                      }

					                      else
					                      {
					                        // CHECK IF TASK PARENT IS NULL FOR SUB/TASKS
					                        if ($checkCell['G'] != 1 && $checkCell['H'] == NULL)
					                        {
					                          $this->session->set_flashdata('danger', 'alert');
					                          $this->session->set_flashdata('alertMessage', ' Task Parent in row ' . $checkRow . ' is required');

					                          unlink($inputFileName);

					                          redirect('controller/addProjectDetails');
					                        }

					                        else
					                        {
					                          // CHECK IF TASK PARENT IS VALID TASK
					                          if ($checkCell['G'] != 1)
					                          {
					                            $checkTaskParents = array_column($worksheet_tasks, 'A');

					                            if (in_array($checkCell['H'], $checkTaskParents))
					                            {
					                              // CHECK IF TASK PARENT IS OF HIGHER CATEGORY
					                              foreach ($worksheet_tasks as $checkParent)
					                              {
					                                if ($checkCell['H'] == $checkParent['A'])
					                                {
					                                  if ($checkCell['G'] > $checkParent['G'])
					                                  {
					                                    if ($checkCell['G'] - $checkParent['G'] != 1)
					                                    {
					                                      $this->session->set_flashdata('danger', 'alert');
					                                      $this->session->set_flashdata('alertMessage', ' Task Parent in row ' . $checkRow . ' should be one category higher');

					                                      unlink($inputFileName);

					                                      redirect('controller/addProjectDetails');
					                                    }
					                                  }

					                                  else
					                                  {
					                                    $this->session->set_flashdata('danger', 'alert');
					                                    $this->session->set_flashdata('alertMessage', ' Task Parent in row ' . $checkRow . ' should be a higher category');

					                                    unlink($inputFileName);

					                                    redirect('controller/addProjectDetails');
					                                  }
					                                }
					                              }
					                            }

					                            else
					                            {
					                              $this->session->set_flashdata('danger', 'alert');
					                              $this->session->set_flashdata('alertMessage', ' Task Parent in row ' . $checkRow . ' is not a valid task');

					                              unlink($inputFileName);

					                              redirect('controller/addProjectDetails');
					                            }
					                          }

																		if ($checkCell['G'] == 1 || $checkCell['G'] == 2)
																		{
																			if ($checkCell['I'] != NULL || $checkCell['J'] != NULL || $checkCell['K'] != NULL || $checkCell['L'] != NULL)
																			{
																				$this->session->set_flashdata('danger', 'alert');
					                              $this->session->set_flashdata('alertMessage', ' Mains and Subs in row ' . $checkRow . ' should not have RACI');

					                              unlink($inputFileName);

					                              redirect('controller/addProjectDetails');
																			}
																		}

					                          if ($checkCell['G'] == 3)
					                          {
																			// CHECK IF RACI IS FILLED
																			if ($checkCell['I'] == NULL || $checkCell['J'] == NULL || $checkCell['K'] == NULL || $checkCell['L'] == NULL)
					                            {
					                              $this->session->set_flashdata('danger', 'alert');
					                              $this->session->set_flashdata('alertMessage', ' Please make sure RACI in row ' . $checkRow . ' is filled');

					                              unlink($inputFileName);

					                              redirect('controller/addProjectDetails');
					                            }

																			// CHECK IF R IS VALID USER
						                          $checkResponsible = explode(", ", $checkCell['I']);

						                          foreach ($checkResponsible as $c)
						                          {
						                            $checkUserR = $this->model->checkUserByName($c);

						                            if (!$checkUserR)
						                            {
						                              $this->session->set_flashdata('danger', 'alert');
						                              $this->session->set_flashdata('alertMessage', ' Responsible in row ' . $checkRow . ' is not a valid user');

						                              unlink($inputFileName);

						                              redirect('controller/addProjectDetails');
						                            }
						                          }

					                            // CHECK IF A IS VALID USER
					                            $checkAccountable = explode(", ", $checkCell['J']);

					                            foreach ($checkAccountable as $c)
					                            {
					                              $checkUserA = $this->model->checkUserByName($c);

					                              if (!$checkUserA)
					                              {
					                                $this->session->set_flashdata('danger', 'alert');
					                                $this->session->set_flashdata('alertMessage', ' Accountable in row ' . $checkRow . ' is not a valid user');

					                                unlink($inputFileName);

					                                redirect('controller/addProjectDetails');
					                              }
					                            }

					                            // CHECK IF C IS VALID USER
					                            $checkConsulted = explode(", ", $checkCell['K']);

					                            foreach ($checkConsulted as $c)
					                            {
					                              $checkUserC = $this->model->checkUserByName($c);

					                              if (!$checkUserC)
					                              {
					                                $this->session->set_flashdata('danger', 'alert');
					                                $this->session->set_flashdata('alertMessage', ' Consulted in row ' . $checkRow . ' is not a valid user');

					                                unlink($inputFileName);

					                                redirect('controller/addProjectDetails');
					                              }
					                            }

					                            // CHECK IF I IS VALID USER
					                            $checkInformed = explode(", ", $checkCell['L']);

					                            foreach ($checkInformed as $c)
					                            {
					                              $checkUserI = $this->model->checkUserByName($c);

					                              if (!$checkUserI)
					                              {
					                                $this->session->set_flashdata('danger', 'alert');
					                                $this->session->set_flashdata('alertMessage', ' Informed in row ' . $checkRow . ' is not a valid user');

					                                unlink($inputFileName);

					                                redirect('controller/addProjectDetails');
					                              }
					                            }
					                          }
					                        }
					                      }
					                    }
					                  }
					                }
					              }

					              else
					              {
					                $this->session->set_flashdata('danger', 'alert');
					                $this->session->set_flashdata('alertMessage', ' End Date in row ' . $checkRow . ' is not in the Project Date range');

					                unlink($inputFileName);

					                redirect('controller/addProjectDetails');
					              }
					            }

					            else
					            {
					              $this->session->set_flashdata('danger', 'alert');
					              $this->session->set_flashdata('alertMessage', ' Start Date in row ' . $checkRow . ' is not in the Project Date range');

					              unlink($inputFileName);

					              redirect('controller/addProjectDetails');
					            }
					          }

					          else
					          {
					            $this->session->set_flashdata('danger', 'alert');
					            $this->session->set_flashdata('alertMessage', ' End Date in row ' . $checkRow . ' is not valid');

					            unlink($inputFileName);

					            redirect('controller/addProjectDetails');
					          }
					        }

					        else
					        {
					          $this->session->set_flashdata('danger', 'alert');
					          $this->session->set_flashdata('alertMessage', ' Start Date in row ' . $checkRow . ' is not valid');

					          unlink($inputFileName);

					          redirect('controller/addProjectDetails');
					        }
					      }
					    }

							if ($checkCell['G'] == 1)
							{
								array_push($mainActivityTitles, $checkCell['A']);
							}
					  }

						// MAIN ACTIVITY PROGRESS
            // CHECK IF SPREADSHEET IS NULL/BLANK
            $sheetname = 'Main Activity Progress';

            //DATA VALIDATION FOR IMPORT
						$filterSubset = new assessmentFilter();
						$reader->setReadFilter($filterSubset);
            $reader->setLoadSheetsOnly($sheetname);
            $spreadsheet = $reader->load($inputFileName);
            $worksheet = $spreadsheet->getActiveSheet()->toArray(NULL, 'true', 'true', 'true');

            foreach ($worksheet as $mainAssessmentKey => $checkMainAssessment)
            {
              if ($mainAssessmentKey != 1)
              {
                if ($mainAssessmentKey == 2)
                {
                  if ($checkMainAssessment['A'] == NULL || $checkMainAssessment['B'] == NULL || $checkMainAssessment['C'] == NULL)
                  {
                    $this->session->set_flashdata('danger', 'alert');
                    $this->session->set_flashdata('alertMessage', ' All fields in the first row of Main Activity Progress are required');

                    unlink($inputFileName);

                    redirect('controller/addProjectDetails');
                  }
                }

                else
                {
                  if (($checkMainAssessment['A'] != NULL && $checkMainAssessment['B'] == NULL) || ($checkMainAssessment['A'] != NULL && $checkMainAssessment['C'] == NULL) || ($checkMainAssessment['B'] != NULL && $checkMainAssessment['C'] == NULL))
                  {
                    $this->session->set_flashdata('danger', 'alert');
                    $this->session->set_flashdata('alertMessage', ' Please make sure that all fields in row ' . $mainAssessmentKey . ' in Main Activity Progress are filled');

                    unlink($inputFileName);

                    redirect('controller/addProjectDetails');
                  }

                  else
                  {
                    // CHECK IF DATE IS VALID
                    if (DateTime::createFromFormat('Y-m-d', $checkMainAssessment['A']) !== FALSE)
                    {
											// CHECK IF IT IS A NUMBER
											if (!is_Numeric($checkMainAssessment['B']))
											{
												$this->session->set_flashdata('danger', 'alert');
												$this->session->set_flashdata('alertMessage', ' Completeness in row ' . $assessmentKey . ' in Main Activity Progress is not a number');

												unlink($inputFileName);

												redirect('controller/addProjectDetails');
											}

											// CHECK IF NUMBER HAS 2 DECIMAL PLACES
											if (!preg_match('/\.\d{2,}/', $checkMainAssessment['B']))
											{
												$this->session->set_flashdata('danger', 'alert');
												$this->session->set_flashdata('alertMessage', ' Completeness in row ' . $assessmentKey . ' in Main Activity Progress does not have 2 decimal places');

												unlink($inputFileName);

												redirect('controller/addProjectDetails');
											}
                    }

                    else
                    {
                      $this->session->set_flashdata('danger', 'alert');
                      $this->session->set_flashdata('alertMessage', ' Date in row ' . $mainAssessmentKey . ' in Main Activity Progress is not valid');

                      unlink($inputFileName);

                      redirect('controller/addProjectDetails');
                    }
                  }
                }

								// CHECK IF TASK IS VALID TASK
								if (!in_array($checkMainAssessment['C'], $mainActivityTitles))
								{
									$this->session->set_flashdata('danger', 'alert');
									$this->session->set_flashdata('alertMessage', ' Main Activity in row ' . $mainAssessmentKey . ' in Main Activity Progess is not a valid task');

									unlink($inputFileName);

									redirect('controller/addProjectDetails');
								}
              }
            }

						// ACTUAL IMPORT
						$sheetname = 'Project Details';

						$reader->setLoadSheetsOnly($sheetname);
						$spreadsheet = $reader->load($inputFileName);
						$worksheet = $spreadsheet->getActiveSheet()->toArray(NULL, 'true', 'true', 'true');

						$title = $spreadsheet->getActiveSheet()->getCell('B1')->getValue();
						$description = $spreadsheet->getActiveSheet()->getCell('B2')->getValue();
						$startDate = $spreadsheet->getActiveSheet()->getCell('B3')->getFormattedValue();
						$endDate = $spreadsheet->getActiveSheet()->getCell('B4')->getFormattedValue();
						$actualEndDate = $spreadsheet->getActiveSheet()->getCell('B5')->getFormattedValue();
						$status = $spreadsheet->getActiveSheet()->getCell('B6')->getValue();
						$type = $spreadsheet->getActiveSheet()->getCell('B7')->getValue();

						$currDate = date("Y-m-d");

						if ($status == 'Ongoing')
						{
						  $data = array(
						      'PROJECTTITLE' => $title,
						      'PROJECTSTARTDATE' => $startDate,
						      'PROJECTENDDATE' => $endDate,
						      'PROJECTDESCRIPTION' => $description,
						      'PROJECTSTATUS' => $status,
						      'users_USERID' => $_SESSION['USERID'],
						      'PROJECTACTUALSTARTDATE' => $startDate,
						      'DATECREATED' => $currDate,
									'PROJECTTYPE' => $type
						  );
						}

						elseif ($status == 'Complete' || $status == 'Archived')
						{
						  $data = array(
						      'PROJECTTITLE' => $title,
						      'PROJECTSTARTDATE' => $startDate,
						      'PROJECTENDDATE' => $endDate,
						      'PROJECTDESCRIPTION' => $description,
						      'PROJECTSTATUS' => $status,
						      'users_USERID' => $_SESSION['USERID'],
						      'PROJECTACTUALSTARTDATE' => $startDate,
						      'PROJECTACTUALENDDATE' => $actualEndDate,
						      'DATECREATED' => $currDate,
									'PROJECTTYPE' => $type
						  );
						}

						elseif ($status == 'Planning')
						{
						  $data = array(
						      'PROJECTTITLE' => $title,
						      'PROJECTSTARTDATE' => $startDate,
						      'PROJECTENDDATE' => $endDate,
						      'PROJECTDESCRIPTION' => $description,
						      'PROJECTSTATUS' => $status,
						      'users_USERID' => $_SESSION['USERID'],
						      'DATECREATED' => $currDate,
									'PROJECTTYPE' => $type
						  );
						}

						$sDate = date_create($startDate);
						$eDate = date_create($endDate);
						$diff = date_diff($eDate, $sDate, true);
						$dateDiff = $diff->format('%R%a');

						$data['project'] = $this->model->addProject($data);
						$data['dateDiff'] =$dateDiff;
						$data['departments'] = $this->model->getAllDepartments();

						$projectID = $data['project']['PROJECTID'];

						if ($data)
						{
						  // START OF LOGS/NOTIFS
						  $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

						  $projectID = $data['project']['PROJECTID'];

						  // START: LOG DETAILS
						  $details = $userName . " created this project.";

						  $logData = array (
						    'LOGDETAILS' => $details,
						    'TIMESTAMP' => date('Y-m-d H:i:s'),
						    'projects_PROJECTID' => $projectID
						  );

						  $this->model->addToProjectLogs($logData);
						  // END: LOG DETAILS

						  $sheetname = 'Project Assessment';

							$filterSubset = new assessmentFilter();
							$reader->setReadFilter($filterSubset);
						  $reader->setLoadSheetsOnly($sheetname);
						  $spreadsheet = $reader->load($inputFileName);
						  $worksheet = $spreadsheet->getActiveSheet()->toArray(NULL, 'true', 'true', 'true');

						  if ($status == 'Planning')
						  {
						    $progressData = array(
						      'projects_PROJECTID' => $projectID,
						      'DATE' => date('Y-m-d'),
						      'COMPLETENESS' => 0,
						      'TIMELINESS' => 100
						    );

						    $this->model->addAssessmentProject($progressData);
						  }

						  elseif ($status == 'Complete' || $status == 'Archived' || $status == 'Ongoing')
						  {
						    foreach ($worksheet as $projAssessKey => $projAssessment)
						    {
						      if ($projAssessKey != 1)
						      {
						        if ($projAssessKey == 2)
						        {
						          $progressData = array(
						            'projects_PROJECTID' => $projectID,
						            'DATE' => $startDate,
						            'COMPLETENESS' => $projAssessment['B'],
						            'TIMELINESS' => $projAssessment['C'],
												'TYPE' => 1
						          );

						          $this->model->addAssessmentProject($progressData);
						        }

						        else
						        {
						          $progressData = array(
						            'projects_PROJECTID' => $projectID,
						            'DATE' => $projAssessment['A'],
						            'COMPLETENESS' => $projAssessment['B'],
						            'TIMELINESS' => $projAssessment['C'],
												'TYPE' => 1
						          );

						          $this->model->addAssessmentProject($progressData);
						        }
						      }
						    }
						  }

							elseif ($status = 'Planning')
							{
								$progressData = array(
									'projects_PROJECTID' => $projectID,
									'DATE' => $startDate,
									'COMPLETENESS' => 0,
									'TIMELINESS' => 100,
									'TYPE' => 1
								);

								$this->model->addAssessmentProject($progressData);
							}

						  $sheetname = 'Tasks';

							$filterSubset = new tasksFilter();
							$reader->setReadFilter($filterSubset);
						  $reader->setLoadSheetsOnly($sheetname);
						  $spreadsheet = $reader->load($inputFileName);
						  $worksheet = $spreadsheet->getActiveSheet()->toArray(NULL, 'true', 'true', 'true');

						  // GET MAIN ACTIVITIES FROM WORKSHEET
						  $flag = true;
						  $i=0;

						  foreach ($worksheet as $value)
						  {
						    if($flag)
						    {
						      $flag =false;
						      continue;
						    }

						    if ($value['G'] == 1)
						    {
									if ($value['E'] == 'Complete' || $value['E'] == 'Ongoing')
									{
										$insertMain['TASKACTUALSTARTDATE'] = $value['B'];
									}

						      $insertMain['TASKTITLE'] = $value['A'];
						      $insertMain['TASKSTARTDATE'] = $value['B'];
						      $insertMain['TASKENDDATE'] = $value['C'];
						      $insertMain['TASKACTUALENDDATE'] = $value['D'];
						      $insertMain['TASKSTATUS'] = $value['E'];
						      $insertMain['TASKREMARKS'] = $value['F'];
						      $insertMain['CATEGORY'] = $value['G'];
						      $insertMain['projects_PROJECTID'] = $projectID;

						      // ENTER TASK TO DB
						      $mainAct = $this->model->importTaskToProject($insertMain);

									// TODO LOG
									// START OF LOGS/NOTIFS
									$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

									$taskDetails = $this->model->getTaskByID($mainAct['TASKID']);
									$taskTitle = $mainAct['TASKTITLE'];

									$projectID = $mainAct['projects_PROJECTID'];
									$projectDetails = $this->model->getProjectByID($projectID);
									$projectTitle = $projectDetails['PROJECTTITLE'];

									// $userDetails = $this->model->getUserByID($dept['users_DEPARTMENTHEAD']);
									// $taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

									// START: LOG DETAILS
									$details = $userName . " has created Main Activity - " . $taskTitle . ".";

									$logData = array (
										'LOGDETAILS' => $details,
										'TIMESTAMP' => date('Y-m-d H:i:s'),
										'projects_PROJECTID' => $projectID
									);

									$this->model->addToProjectLogs($logData);
									// END: LOG DETAILS

						      // GET ALL SUB ACTS UNDER CURRENT MAIN
						      foreach ($worksheet as $cell)
						      {
						        if ($cell['H'] == $mainAct['TASKTITLE'])
						        {
											if ($cell['E'] == 'Complete' || $cell['E'] == 'Ongoing')
											{
												$insertSub['TASKACTUALSTARTDATE'] = $cell['B'];
											}

						          $insertSub['TASKTITLE'] = $cell['A'];
						          $insertSub['TASKSTARTDATE'] = $cell['B'];
						          $insertSub['TASKENDDATE'] = $cell['C'];
						          $insertSub['TASKACTUALENDDATE'] = $cell['D'];
						          $insertSub['TASKSTATUS'] = $cell['E'];
						          $insertSub['TASKREMARKS'] = $cell['F'];
						          $insertSub['CATEGORY'] = $cell['G'];
						          $insertSub['tasks_TASKPARENT'] = $mainAct['TASKID'];
						          $insertSub['projects_PROJECTID'] = $projectID;

						          $subAct = $this->model->importTaskToProject($insertSub);

											// START OF LOGS/NOTIFS
											$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

											$taskDetails = $this->model->getTaskByID($subAct['TASKID']);
											$taskTitle = $subAct['TASKTITLE'];

											$projectID = $subAct['projects_PROJECTID'];
											$projectDetails = $this->model->getProjectByID($projectID);
											$projectTitle = $projectDetails['PROJECTTITLE'];

											// START: LOG DETAILS
											$details = $userName . " has created Sub Activity - " . $taskTitle . ".";

											$logData = array (
												'LOGDETAILS' => $details,
												'TIMESTAMP' => date('Y-m-d H:i:s'),
												'projects_PROJECTID' => $projectID
											);

											$this->model->addToProjectLogs($logData);
											// END: LOG DETAILS

						          // GET ALL TASKS UNDER CURRENT SUB
						          foreach ($worksheet as $cell_2)
						          {
						            if ($cell_2['H'] == $subAct['TASKTITLE'])
						            {
													if ($cell_2['E'] == 'Complete' || $cell_2['E'] == 'Ongoing')
													{
														$insertTask['TASKACTUALSTARTDATE'] = $cell_2['B'];
													}

						              $insertTask['TASKTITLE'] = $cell_2['A'];
						              $insertTask['TASKSTARTDATE'] = $cell_2['B'];
						              $insertTask['TASKENDDATE'] = $cell_2['C'];
						              $insertTask['TASKACTUALENDDATE'] = $cell_2['D'];
						              $insertTask['TASKSTATUS'] = $cell_2['E'];
						              $insertTask['TASKREMARKS'] = $cell_2['F'];
						              $insertTask['CATEGORY'] = $cell_2['G'];
						              $insertTask['tasks_TASKPARENT'] = $subAct['TASKID'];
						              $insertTask['projects_PROJECTID'] = $projectID;

						              $task = $this->model->importTaskToProject($insertTask);

						              // RESPONSIBLE
						              $taskUsersR = explode(", ", $cell_2['I']);

						              foreach ($taskUsersR as $r)
						              {
						                $taskUserIDR = $this->model->getUserByName($r);

						                $taskR['ROLE'] = 1;
						                $taskR['users_USERID'] = $taskUserIDR['USERID'];
						                $taskR['tasks_TASKID'] = $task['TASKID'];
						                $taskR['STATUS'] = 'Current';

						                $result = $this->model->addToRaci($taskR);

														// START OF LOGS/NOTIFS
														$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

														$taskDetails = $this->model->getTaskByID($task['TASKID']);
														$taskTitle = $task['TASKTITLE'];

														$projectID = $task['projects_PROJECTID'];
														$projectDetails = $this->model->getProjectByID($projectID);
														$projectTitle = $projectDetails['PROJECTTITLE'];

														$userDetails = $this->model->getUserByID($taskUserIDR['USERID']);
														$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

														// START: LOG DETAILS
														$details = $userName . " has tagged " . $taggedUserName . " as responsible for  " . $taskTitle . " in " . $projectDetails['PROJECTTITLE'];

														$logData = array (
															'LOGDETAILS' => $details,
															'TIMESTAMP' => date('Y-m-d H:i:s'),
															'projects_PROJECTID' => $projectID
														);

														$this->model->addToProjectLogs($logData);
														// END: LOG DETAILS

														//START: Notifications
														$details = $userName . " has tagged you as responsbile for " . $taskTitle . " in " . $projectDetails['PROJECTTITLE'] . ".";
														$notificationData = array(
															'users_USERID' => $taskUserIDR['USERID'],
															'DETAILS' => $details,
															'TIMESTAMP' => date('Y-m-d H:i:s'),
															'status' => 'Unread',
															'projects_PROJECTID' => $projectID,
															'tasks_TASKID' => $task['TASKID'],
															'TYPE' => '2'
														);

														$this->model->addNotification($notificationData);
														// END: Notification

														$mainRaci['ROLE'] = 5;
										        $mainRaci['users_USERID'] = $taskUserIDR['users_DEPARTMENTHEAD'];
										        $mainRaci['tasks_TASKID'] = $mainAct['TASKID'];
										        $mainRaci['STATUS'] = 'Current';

										        $result = $this->model->addToRaci($mainRaci);

														$subRaci['ROLE'] = 5;
								            $subRaci['users_USERID'] = $taskUserIDR['users_DEPARTMENTHEAD'];
								            $subRaci['tasks_TASKID'] = $subAct['TASKID'];
								            $subRaci['STATUS'] = 'Current';

								            $result = $this->model->addToRaci($subRaci);
						              }

						              // ACCOUNTABLE
						              $taskUsersA = explode(", ", $cell_2['J']);

						              foreach ($taskUsersA as $a)
						              {
						                $taskUserIDA = $this->model->getUserByName($a);

						                $taskA['ROLE'] = 2;
						                $taskA['users_USERID'] = $taskUserIDA['USERID'];
						                $taskA['tasks_TASKID'] = $task['TASKID'];
						                $taskA['STATUS'] = 'Current';

						                $result = $this->model->addToRaci($taskA);

														// START OF LOGS/NOTIFS
														$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

														$taskDetails = $this->model->getTaskByID($task['TASKID']);
														$taskTitle = $task['TASKTITLE'];

														$projectID = $task['projects_PROJECTID'];
														$projectDetails = $this->model->getProjectByID($projectID);
														$projectTitle = $projectDetails['PROJECTTITLE'];

														$userDetails = $this->model->getUserByID($taskUserIDA['USERID']);
														$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

														// START: LOG DETAILS
														$details = $userName . " has tagged " . $taggedUserName . " as accountable for  " . $taskTitle . " in " . $projectDetails['PROJECTTITLE'];

														$logData = array (
															'LOGDETAILS' => $details,
															'TIMESTAMP' => date('Y-m-d H:i:s'),
															'projects_PROJECTID' => $projectID
														);

														$this->model->addToProjectLogs($logData);
														// END: LOG DETAILS

														//START: Notifications
														$details = $userName . " has tagged you as accountable for " . $taskTitle . " in " . $projectDetails['PROJECTTITLE'] . ".";
														$notificationData = array(
															'users_USERID' => $taskUserIDA['USERID'],
															'DETAILS' => $details,
															'TIMESTAMP' => date('Y-m-d H:i:s'),
															'status' => 'Unread',
															'projects_PROJECTID' => $projectID,
															'tasks_TASKID' => $task['TASKID'],
															'TYPE' => '2'
														);

														$this->model->addNotification($notificationData);
														// END: Notification
						              }

						              // CONSULTED
						              $taskUsersC = explode(", ", $cell_2['K']);

						              foreach ($taskUsersC as $c)
						              {
						                $taskUserIDC = $this->model->getUserByName($c);

						                $taskC['ROLE'] = 3;
						                $taskC['users_USERID'] = $taskUserIDC['USERID'];
						                $taskC['tasks_TASKID'] = $task['TASKID'];
						                $taskC['STATUS'] = 'Current';

						                $result = $this->model->addToRaci($taskC);

														// START OF LOGS/NOTIFS
														$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

														$taskDetails = $this->model->getTaskByID($task['TASKID']);
														$taskTitle = $task['TASKTITLE'];

														$projectID = $task['projects_PROJECTID'];
														$projectDetails = $this->model->getProjectByID($projectID);
														$projectTitle = $projectDetails['PROJECTTITLE'];

														$userDetails = $this->model->getUserByID($taskUserIDC['USERID']);
														$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

														// START: LOG DETAILS
														$details = $userName . " has tagged " . $taggedUserName . " as consulted for  " . $taskTitle . " in " . $projectDetails['PROJECTTITLE'];

														$logData = array (
															'LOGDETAILS' => $details,
															'TIMESTAMP' => date('Y-m-d H:i:s'),
															'projects_PROJECTID' => $projectID
														);

														$this->model->addToProjectLogs($logData);
														// END: LOG DETAILS

														//START: Notifications
														$details = $userName . " has tagged you as consulted for " . $taskTitle . " in " . $projectDetails['PROJECTTITLE'] . ".";
														$notificationData = array(
															'users_USERID' => $taskUserIDC['USERID'],
															'DETAILS' => $details,
															'TIMESTAMP' => date('Y-m-d H:i:s'),
															'status' => 'Unread',
															'projects_PROJECTID' => $projectID,
															'tasks_TASKID' => $task['TASKID'],
															'TYPE' => '2'
														);

														$this->model->addNotification($notificationData);
														// END: Notification
						              }

						              // INFORMED
						              $taskUsersI = explode(", ", $cell_2['L']);

						              foreach ($taskUsersI as $i)
						              {
						                $taskUserIDI = $this->model->getUserByName($i);

						                $taskI['ROLE'] = 4;
						                $taskI['users_USERID'] = $taskUserIDI['USERID'];
						                $taskI['tasks_TASKID'] = $task['TASKID'];
						                $taskI['STATUS'] = 'Current';

						                $result = $this->model->addToRaci($taskI);

														// START OF LOGS/NOTIFS
														$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

														$taskDetails = $this->model->getTaskByID($task['TASKID']);
														$taskTitle = $task['TASKTITLE'];

														$projectID = $task['projects_PROJECTID'];
														$projectDetails = $this->model->getProjectByID($projectID);
														$projectTitle = $projectDetails['PROJECTTITLE'];

														$userDetails = $this->model->getUserByID($taskUserIDI['USERID']);
														$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

														// START: LOG DETAILS
														$details = $userName . " has tagged " . $taggedUserName . " as informed for  " . $taskTitle . " in " . $projectDetails['PROJECTTITLE'];

														$logData = array (
															'LOGDETAILS' => $details,
															'TIMESTAMP' => date('Y-m-d H:i:s'),
															'projects_PROJECTID' => $projectID
														);

														$this->model->addToProjectLogs($logData);
														// END: LOG DETAILS

														//START: Notifications
														$details = $userName . " has tagged you as informed for " . $taskTitle . " in " . $projectDetails['PROJECTTITLE'] . ".";
														$notificationData = array(
															'users_USERID' => $taskUserIDI['USERID'],
															'DETAILS' => $details,
															'TIMESTAMP' => date('Y-m-d H:i:s'),
															'status' => 'Unread',
															'projects_PROJECTID' => $projectID,
															'tasks_TASKID' => $task['TASKID'],
															'TYPE' => '2'
														);

														$this->model->addNotification($notificationData);
														// END: Notification
						              }
						            }
						          }
						        }
						      }
						    }
						  }

						  $data['mainActivity'] = $this->model->getAllMainActivitiesByID($projectID);

							$sheetname = 'Main Activity Progress';

							$filterSubset = new assessmentFilter();
							$reader->setReadFilter($filterSubset);
						  $reader->setLoadSheetsOnly($sheetname);
						  $spreadsheet = $reader->load($inputFileName);
						  $worksheet = $spreadsheet->getActiveSheet()->toArray(NULL, 'true', 'true', 'true');

							foreach ($worksheet as $mainActProg)
							{
								foreach ($data['mainActivity'] as $m)
								{
									if ($mainActProg['C'] == $m['TASKTITLE'])
									{
										$progressData = array(
											'projects_PROJECTID' => $projectID,
											'DATE' => $mainActProg['A'],
											'COMPLETENESS' => $mainActProg['B'],
											'TYPE' => 2,
											'tasks_MAINID' => $m['TASKID']
										);

										$this->model->addAssessmentProject($progressData);
									}
								}
							}

						  // REDIRECT TO DEPENDENCIES
						  $data['project'] = $this->model->getProjectByID($projectID);
						  $data['allTasks'] = $this->model->getAllTasksForImportDependency($projectID);
						  $data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($projectID);
						  $data['subActivity'] = $this->model->getAllSubActivitiesByID($projectID);
						  $data['tasks'] = $this->model->getAllTasksByIDRole1($projectID);
						  $data['users'] = $this->model->getAllUsers();
						  $data['departments'] = $this->model->getAllDepartments();

						  $sDate = date_create($data['project']['PROJECTSTARTDATE']);
						  $eDate = date_create($data['project']['PROJECTENDDATE']);
						  $diff = date_diff($eDate, $sDate, true);
						  $dateDiff = $diff->format('%R%a');

						  $data['dateDiff'] = $dateDiff;

						  $this->session->set_flashdata('import', 'import');

						  $this->load->view("addDependencies", $data);
						}

						else
						{
						  $this->session->set_flashdata('danger', 'alert');
						  $this->session->set_flashdata('alertMessage', ' There was an error in inserting your data');

						  unlink($inputFileName);

						  redirect('controller/addProjectDetails');
						}
	        }

	        catch (Exception $e)
	        {
	           die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
	                    . '": ' .$e->getMessage());
	        }
	       }
	     }

	    // NEW PROJECT FROM SCRATCH
	    else
	    {
	      $startDate = $this->input->post('startDate');
	      date_default_timezone_set("Singapore");
	      $currDate = date("Y-m-d");

	      if ($currDate >= $startDate)
	      {
	        $status = 'Ongoing';
	      }

	      else
	      {
	        $status = 'Drafted';
	      }

	      $data = array(
	          'PROJECTTITLE' => $this->input->post('projectTitle'),
	          'PROJECTSTARTDATE' => $startDate,
	          'PROJECTENDDATE' => $this->input->post('endDate'),
	          'PROJECTDESCRIPTION' => $this->input->post('projectDetails'),
	          'PROJECTSTATUS' => $status,
	          'users_USERID' => $_SESSION['USERID'],
	          'DATECREATED' => $currDate,
						'PROJECTTYPE' => $this->input->post('type')
	      );

				echo $this->input->post('type');

	      $sDate = date_create($startDate);
	      $eDate = date_create($this->input->post('endDate'));
	      $diff = date_diff($eDate, $sDate, true);
	      $dateDiff = $diff->format('%R%a');

	      // PLUGS DATA INTO DB AND RETURNS ARRAY OF THE PROJECT
	      $data['project'] = $this->model->addProject($data);
	      $data['dateDiff'] =$dateDiff;
	      $data['departments'] = $this->model->getAllDepartments();

	      if ($data)
	      {
	        // TODO PUT ALERT

	        // START OF LOGS/NOTIFS
	        $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

	        $projectID = $data['project']['PROJECTID'];

	        // START: LOG DETAILS
	        $details = $userName . " created this project.";

	        $logData = array (
	          'LOGDETAILS' => $details,
	          'TIMESTAMP' => date('Y-m-d H:i:s'),
	          'projects_PROJECTID' => $projectID
	        );

	        $this->model->addToProjectLogs($logData);
	        // END: LOG DETAILS

	        $progressData = array(
	          'projects_PROJECTID' => $projectID = $data['project']['PROJECTID'],
	          'DATE' => date('Y-m-d'),
	          'COMPLETENESS' => 0,
	          'TIMELINESS' => 100,
						'TYPE' => 1
	        );

	        $this->model->addProjectAssessment($progressData);

	        $templates = $this->input->post('templates');

	        if (isset($templates))
	        {
	          $this->session->set_flashdata('templates', $templates);

	          $data['templateProject'] = $this->model->getProjectByID($templates);
	          $data['templateAllTasks'] = $this->model->getAllProjectTasks($templates);
	          $data['templateGroupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($templates);
	          $data['templateMainActivity'] = $this->model->getAllMainActivitiesByID($templates);
	          $data['templateSubActivity'] = $this->model->getAllSubActivitiesByID($templates);
	          $data['templateTasks'] = $this->model->getAllTasksByIDRole1($templates);
	          $data['templateRaci'] = $this->model->getRaci($templates);
	          $data['templateUsers'] = $this->model->getAllUsers();
	        }

	        $this->load->view('addMainActivities', $data);
	      }
	    }
	  }

	  else
	  {
	    // TODO PUT ALERT
	    redirect('controller/restrictedAccess');
	  }
	}

	public function addTasks()
	{
		$id = $this->input->post('project_ID');

		$parent = $this->input->post('subActivity_ID');
		$title = $this->input->post('title');
		$startDates = $this->input->post('taskStartDate');
		$endDates = $this->input->post('taskEndDate');
		$department = $this->input->post("department");
		$rowNum = $this->input->post('row');

		$addedTask = array();

		$departments = $this->model->getAllDepartments();

		foreach($departments as $row)
		{
			switch ($row['DEPARTMENTNAME'])
			{
				case 'Executive':
					$execHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Marketing':
					$mktHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Finance':
					$finHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Procurement':
					$proHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Human Resource':
					$hrHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Management Information System':
					$misHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Store Operations':
					$opsHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Facilities Administration':
					$fadHead = $row['users_DEPARTMENTHEAD'];
					break;
			}
		}

		date_default_timezone_set("Singapore");
		$currDate = date("Y-m-d");

		foreach ($title as $key=> $row)
		{
			if ($currDate >= $startDates[$key])
			{
				$tStatus = 'Ongoing';
			}

			else
			{
				$tStatus = 'Planning';
			}

			$data = array(
					'TASKTITLE' => $row,
					'TASKSTARTDATE' => $startDates[$key],
					'TASKENDDATE' => $endDates[$key],
					'TASKSTATUS' => $tStatus,
					'CATEGORY' => '3',
					'projects_PROJECTID' => $id,
					'tasks_TASKPARENT' => $parent[$key]
			);

			// SAVES ALL ADDED TASKS INTO AN ARRAY
			$addedTask[] = $this->model->addTasksToProject($data);
		 }

		// GETS DEPARTMENT ARRAY FOR RACI
		foreach ($addedTask as $aKey=> $a)
		{
			// echo " -- " . $a . " -- " . "<br>";
			// rowNum SAVES THE ORDER OF HOW THE DEPARTMENT ARRAY MUST LOOK LIKE
			foreach ($rowNum as $rKey => $row)
			{
				// echo $row . "<br>";
				// echo $aKey . " == " . $rKey . "<br>";
				if ($aKey == $rKey)
				{
					// echo $aKey . " == " . $rKey . "<br>";
					foreach ($department as $dKey => $d)
					{
						// echo $row . " == " . $dKey . "<br>";
						if ($row == $dKey)
						{
							// echo $row . " == " . $dKey . "<br>";
							foreach ($d as $value)
							{
								switch ($value)
								{
									case 'Executive':
										$deptHead = $execHead;
										break;
									case 'Marketing':
										$deptHead = $mktHead;
										break;
									case 'Finance':
										$deptHead = $finHead;
										break;
									case 'Procurement':
										$deptHead = $proHead;
										break;
									case 'Human Resource':
										$deptHead = $hrHead;
										break;
									case 'Management Information System':
										$deptHead = $misHead;
										break;
									case 'Store Operations':
										$deptHead = $opsHead;
										break;
									case 'Facilities Administration':
										$deptHead = $fadHead;
										break;
								}

								// echo $value . ", ";

								$data = array(
										'ROLE' => '0',
										'users_USERID' => $deptHead,
										'tasks_TASKID' => $a,
										'STATUS' => 'Current'
								);

								// ENTER INTO RACI
								$result = $this->model->addToRaci($data);

								$data2 = array(
										'ROLE' => '1',
										'users_USERID' => $deptHead,
										'tasks_TASKID' => $a,
										'STATUS' => 'Current'
								);

								// ENTER INTO RACI
								$result2 = $this->model->addToRaci($data2);

								// START OF LOGS/NOTIFS
								$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

								$taskDetails = $this->model->getTaskByID($a);
								$taskTitle = $taskDetails['TASKTITLE'];

								$projectID = $taskDetails['projects_PROJECTID'];
								$projectDetails = $this->model->getProjectByID($projectID);
								$projectTitle = $projectDetails['PROJECTTITLE'];

								$userDetails = $this->model->getUserByID($deptHead);
								$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

								// START: LOG DETAILS
								$details = $userName . " has tagged " . $taggedUserName . " to delegate " . $taskTitle . ".";

								$logData = array (
									'LOGDETAILS' => $details,
									'TIMESTAMP' => date('Y-m-d H:i:s'),
									'projects_PROJECTID' => $projectID
								);

								$this->model->addToProjectLogs($logData);
								// END: LOG DETAILS

								//START: Notifications
								$details = "A new project has been created. " .  $userName . " has tagged you to delegate " . $taskTitle . " in " . $projectTitle . ".";
								$notificationData = array(
									'users_USERID' => $deptHead,
									'DETAILS' => $details,
									'TIMESTAMP' => date('Y-m-d H:i:s'),
									'status' => 'Unread',
									'projects_PROJECTID' => $projectID,
									'tasks_TASKID' => $a,
									'TYPE' => '2'
								);

								$this->model->addNotification($notificationData);
								// END: Notification

							}
							// echo "<br>";
						}
					}
				}
			}
		}

		// $this->output->enable_profiler(TRUE);

		// GANTT CODE
		// $data['projectProfile'] = $this->model->getProjectByID($id);
		// $data['ganttData'] = $this->model->getAllProjectTasks($id);
		// // $data['preReq'] = $this->model->getPreReqID();
		// $data['dependencies'] = $this->model->getDependencies();
		// $data['users'] = $this->model->getAllUsers();

		$data['project'] = $this->model->getProjectByID($id);
		$data['allTasks'] = $this->model->getAllProjectTasks($id);
		$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);
		$data['mainActivity'] = $this->model->getAllMainActivitiesByID($id);
		$data['subActivity'] = $this->model->getAllSubActivitiesByID($id);
		$data['tasks'] = $this->model->getAllTasksByIDRole0($id);
		$data['users'] = $this->model->getAllUsers();
		$data['departments'] = $this->model->getAllDepartments();

		$sDate = date_create($data['project']['PROJECTSTARTDATE']);
		$eDate = date_create($data['project']['PROJECTENDDATE']);
		$diff = date_diff($eDate, $sDate, true);
		$dateDiff = $diff->format('%R%a');

		$data['dateDiff'] = $dateDiff;

		// $this->load->view("dashboard", $data);
		// redirect('controller/projectGantt');
		$this->load->view("addDependencies", $data);
	}

	public function projectGantt()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			if (isset($_SESSION['projectID']))
			{
				$id = $_SESSION['projectID'];
			}

			else
			{
				$id = $this->input->post("project_ID");
			}

			$archives =$this->input->post("archives");
			$rfc =$this->input->post("rfc");
			$userRequest =$this->input->post("userRequest");
			$myTasks =$this->input->post("myTasks");
			$templates =$this->input->post("templates");
			$dashboard =$this->input->post("dashboard");
			$templateProjectGantt = $this->input->post("templateProjectGantt");
			$monitorTasks =$this->input->post("monitorTasks");

			$data['isTemplate'] = $this->model->checkIfTemplate($id);

			// DASHBOARD
			if (isset($dashboard))
			{
				$dashboard =$this->input->post("dashboard");
				$this->session->set_flashdata('dashboard', $dashboard);

				if (isset($rfc))
				{
					$rfc =$this->input->post("rfc");
					$this->session->set_flashdata('rfc', $rfc);
					$requestID = $this->input->post("request_ID");
					$data['changeRequest'] = $this->model->getChangeRequestbyID($requestID);
					switch($_SESSION['usertype_USERTYPEID'])
					{
						case '2':
							$filter = "users.usertype_USERTYPEID = '3'";
							break;

						case '3':
							$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
							break;

						case '4':
							$filter = "(users.usertype_USERTYPEID = '3' &&  users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."')
							|| users.users_SUPERVISORS = '" . $_SESSION['USERID'] ."' && users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
							break;

						default:
							$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
							break;
					}
					$data['departments'] = $this->model->getAllDepartments();
					$data['deptEmployees'] = $this->model->getAllUsersByUserType($filter);
					$data['wholeDept'] = $this->model->getAllUsersByUserType($filter);
					$data['projectCountR'] = $this->model->getProjectCount();
					$data['taskCountR'] = $this->model->getTaskCount();
					$data['projectCount'] = $this->model->getProjectCount($data['changeRequest']['departments_DEPARTMENTID']);
					$data['taskCount'] = $this->model->getTaskCount($data['changeRequest']['departments_DEPARTMENTID']);
				}
			}

			// ARCHIVES
			elseif (isset($archives))
			{
				$archives = $this->input->post("archives");
				$this->session->set_flashdata('archives', $archives);
			}

			// RFC
			elseif (isset($rfc) || isset($_SESSION['rfcNoRemarks']))
			{
				if (isset($rfc))
				{
					$rfc = $this->input->post("rfc");
					$requestID = $this->input->post("request_ID");
				}

				elseif (isset($_SESSION['rfcNoRemarks']))
				{
					$requestID = $_SESSION['rfcNoRemarks'];
					$rfc = $requestID;
				}

				$this->session->set_flashdata('rfc', $rfc);

				$data['changeRequest'] = $this->model->getChangeRequestbyID($requestID);
				switch($_SESSION['usertype_USERTYPEID'])
				{
					case '2':
						$filter = "users.usertype_USERTYPEID = '3'";
						break;

					case '3':
						$filter = "users.departments_DEPARTMENTID = '". $data['changeRequest']['departments_DEPARTMENTID'] ."'";
						break;

					case '4':
						$filter = "users.users_SUPERVISORS = '" . $_SESSION['USERID'] ."'";
						break;

					default:
						$filter = "users.departments_DEPARTMENTID = '". $data['changeRequest']['departments_DEPARTMENTID'] ."'";
						break;
				}
				$data['departments'] = $this->model->getAllDepartments();
				$data['deptEmployees'] = $this->model->getAllUsersByUserType($filter);
				$data['wholeDept'] = $this->model->getAllUsersByDepartment($data['changeRequest']['departments_DEPARTMENTID']);
				$data['projectCount'] = $this->model->getProjectCount();
				$data['taskCount'] = $this->model->getTaskCount();
			}
			elseif (isset($myTasks))
			{
				$mytasks = $this->input->post("myTasks");
				$this->session->set_flashdata('myTasks', $mytasks);
			}
			elseif (isset($templates))
			{
				$templates = $this->input->post("templates");
				$this->session->set_flashdata('templates', $templates);
			}
			elseif (isset($userRequest))
			{
				$userRequest = $this->input->post("userRequest");
				$requestID = $this->input->post("request_ID");
				$data['changeRequest'] = $this->model->getChangeRequestbyID($requestID);
				$this->session->set_flashdata('userRequest', $userRequest);
			}
			elseif (isset($templateProjectGantt))
			{
				$templateProjectGantt = $this->input->post("templateProjectGantt");
				$this->session->set_flashdata('templateProjectGantt', $templateProjectGantt);
			}
			elseif (isset($monitorTasks))
			{
				$templateProjectGantt = $this->input->post("monitorTasks");
				$this->session->set_flashdata('monitorTasks', $monitorTasks);
			}

			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['ganttData'] = $this->model->getAllProjectTasksGroupByTaskID($id);
			$data['dependencies'] = $this->model->getDependenciesByProject($id);
			$data['users'] = $this->model->getAllUsers();

			$data['responsible'] = $this->model->getAllResponsibleByProject($id);
			$data['accountable'] = $this->model->getAllAccountableByProject($id);
			$data['consulted'] = $this->model->getAllConsultedByProject($id);
			$data['informed'] = $this->model->getAllInformedByProject($id);

			$data['employeeCompleteness'] = $this->model->compute_completeness_employeeByProject($_SESSION['USERID'], $id);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employeeByProject($_SESSION['USERID'], $id);
			$data['projectCompleteness'] = $this->model->compute_completeness_project($id);
			$data['projectTimeliness'] = $this->model->compute_timeliness_project($id);

			$this->load->view("projectGantt", $data);

			// redirect("controller/projectGantt");
		}
	}

	public function projectDocuments()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			if (isset($_SESSION['projectID']))
			{
				$id = $_SESSION['projectID'];
			}

			else
			{
				$id = $this->input->post("project_ID");
				$this->session->set_flashdata('projectID', $id);
			}

			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['departments'] = $this->model->getAllDepartmentsByProject($id);
			$data['documentsByProject'] = $this->model->getAllDocumentsByProject($id);
			$data['documentAcknowledgement'] = $this->model->getDocumentsForAcknowledgement($id, $_SESSION['USERID']);
			$data['users'] = $this->model->getAllUsersByProject($id);

			$this->load->view("projectDocuments", $data);
		}
	}

	public function addDependencies()
	{
		$id = $this->input->post('project_ID');
		$taskID = $this->input->post('taskID');
		$dependencies = $this->input->post('dependencies');

		$allTasks = $this->model->getAllProjectTasksGroupByTaskID($id);

		foreach ($allTasks as $key => $value)
		{
			if ($value['CATEGORY'] == '3')
			{
				// echo " -- " . $value['TASKID'] . " -- <br>";
				foreach ($taskID as $tKey => $tValue)
				{
					if ($value['TASKID'] == $tValue)
					{
						// echo $value['TASKID'] . " == " . $tValue . "<br>";

						if ($dependencies != NULL)
						{
							foreach ($dependencies as $dKey => $dValue)
							{
								if ($tKey == $dKey)
								{
									// echo $tKey . " == " . $dKey . "<br>";
									foreach ($dValue as $d)
									{
										$data = array(
											'PRETASKID' => $d,
											'tasks_POSTTASKID' => $value['TASKID']
										);

										// echo $d . ", ";

										// ENTER DEPENDENCIES TO DB
										$result = $this->model->addToDependencies($data);
									}
								}
							}
						}
						// echo "<br>";
					}
				}
			}
		}

		$data['projectProfile'] = $this->model->getProjectByID($id);
		$data['ganttData'] = $this->model->getAllProjectTasksGroupByTaskID($id);
		$data['dependencies'] = $this->model->getDependenciesByProject($id);
		$data['users'] = $this->model->getAllUsers();
		$data['responsible'] = $this->model->getAllResponsibleByProject($id);
		$data['accountable'] = $this->model->getAllAccountableByProject($id);
		$data['consulted'] = $this->model->getAllConsultedByProject($id);
		$data['informed'] = $this->model->getAllInformedByProject($id);

		$data['employeeCompleteness'] = $this->model->compute_completeness_employeeByProject($_SESSION['USERID'], $id);
		$data['employeeTimeliness'] = $this->model->compute_timeliness_employeeByProject($_SESSION['USERID'], $id);
		$data['projectCompleteness'] = $this->model->compute_completeness_project($id);
		$data['projectTimeliness'] = $this->model->compute_timeliness_project($id);

		// foreach ($data['ganttData'] as $key => $value) {
		// 	echo $value['tasks_TASKID'] . " parent is ";
		// 	echo $value['tasks_TASKPARENT'] . "<br>";
		// }

		$this->load->view("projectGantt", $data);
		// $this->load->view("gantt2", $data);
	}

	public function rfc()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$userID = $_SESSION['USERID'];
			$deptID = $_SESSION['departments_DEPARTMENTID'];
			switch($_SESSION['usertype_USERTYPEID'])
			{
				case '4': // if supervisor is logged in
					$filter = "(usertype_USERTYPEID = '5' && users_SUPERVISORS = '$userID' && REQUESTSTATUS = 'Pending')
						|| (projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending')"; break;
				case '3': // if head is logged in
					$filter = "((usertype_USERTYPEID = '4' || usertype_USERTYPEID = '5') && users.departments_DEPARTMENTID = '$deptID' && REQUESTSTATUS = 'Pending')
					|| (projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending')"; break;
				case '5': // if PO is logged in
					$filter = "projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending'"; break;
				default:
					$filter = "usertype_USERTYPEID = '3' && REQUESTSTATUS = 'Pending'"; break;
			}

			$data['changeRequests'] = $this->model->getChangeRequestsForApproval($filter, $_SESSION['USERID']);
			$data['userRequests'] = $this->model->getChangeRequestsByUser($_SESSION['USERID']);
			$this->load->view("rfc", $data);
		}
	}

	public function notifRedirect(){

		$projectID = $this->input->post("projectID");
		$taskID = $this->input->post("taskID");
		$type = $this->input->post("type");
		$notifID = $this->input->post("notifID");

		$statusArray = array(
				"status" => "Read"
		);

		$this->model->updateNotification($notifID, $statusArray);

		$data['notifications'] = $this->model->getAllNotificationsByUser();
		$this->session->set_userdata('notifications', $data['notifications']);

		if ($type == 2){ // taskDelegate

			$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";

			$data['delegateTasksByProject'] = $this->model->getAllProjectsToEditByUser($_SESSION['USERID'], "projects_PROJECTID");
			$data['delegateTasksByMainActivity'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID'], "1");
			$data['delegateTasksBySubActivity'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID'], "2");
			$data['delegateTasks'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID']);
			$data['departments'] = $this->model->getAllDepartments();
			$data['users'] = $this->model->getAllUsers();
			$data['wholeDept'] = $this->model->getAllUsersByDepartment($_SESSION['departments_DEPARTMENTID']);
			$data['projectCount'] = $this->model->getProjectCount();
			$data['taskCount'] = $this->model->getTaskCount();

			$this->load->view("taskDelegate", $data);

		} else if ($type == 3){ //taskTodo

			$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);
			$data['projects'] = $this->model->getAllTasksByUserByProject($_SESSION['USERID']);
			$data['projectsToDo'] = $this->model->getAllTasksByUserByProjectToDo($_SESSION['USERID']);

			$this->load->view("taskTodo", $data);

		} else if ($type == 4){ // taskMonitor

			$data['allTasks'] = $this->model->getAllTasksToMonitor();

			$data['allPlannedACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Planning");
			$data['uniquePlannedACItasks'] = $this->model->getUniqueACITasksByUser($_SESSION['USERID'], "Planning");

			$data['allOngoingACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Ongoing");
			$data['uniqueOngoingACItasks'] = $this->model->getUniqueACITasksByUser($_SESSION['USERID'], "Ongoing");

			$data['allCompletedACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Complete");
			$data['uniqueCompletedACItasks'] = $this->model->getUniqueACITasksByUser($_SESSION['USERID'], "Complete");

			$data['allOngoingACIprojects'] = $this->model->getUniqueACIOngoingProjectsByUserByProject($_SESSION['USERID'], "Ongoing");
			$data['allACIprojects'] = $this->model->getUniqueACIProjectsByUserByProject($_SESSION['USERID']);

			$this->load->view("taskMonitor", $data);

		} else if ($type == 5){ // projectDocuments

			$data['projectProfile'] = $this->model->getProjectByID($projectID);
			$data['departments'] = $this->model->getAllDepartmentsByProject($projectID);
			$data['documentsByProject'] = $this->model->getAllDocumentsByProject($projectID);
			$data['documentAcknowledgement'] = $this->model->getDocumentsForAcknowledgement($projectID, $_SESSION['USERID']);
			$data['users'] = $this->model->getAllUsersByProject($projectID);

			$this->load->view("projectDocuments", $data);

		} else if ($type == 6){ // rfc

			$userID = $_SESSION['USERID'];
			$deptID = $_SESSION['departments_DEPARTMENTID'];
			switch($_SESSION['usertype_USERTYPEID'])
			{
				case '4': // if supervisor is logged in
					$filter = "(usertype_USERTYPEID = '5' && users_SUPERVISORS = '$userID' && REQUESTSTATUS = 'Pending')
						|| (projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending')"; break;
				case '3': // if head is logged in
					$filter = "(usertype_USERTYPEID = '4' && users.departments_DEPARTMENTID = '$deptID' && REQUESTSTATUS = 'Pending')
					|| (projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending')"; break;
				case '5': // if PO is logged in
					$filter = "projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending'"; break;
				default:
					$filter = "usertype_USERTYPEID = '3' && REQUESTSTATUS = 'Pending'"; break;
			}

			$data['changeRequests'] = $this->model->getChangeRequestsForApproval($filter, $_SESSION['USERID']);
			$data['userRequests'] = $this->model->getChangeRequestsByUser($_SESSION['USERID']);
			$this->load->view("rfc", $data);

		} else if ($type == 7) { // project summary

			$templateProjSummary = $this->input->post('templateProjSummary');

			// if (isset($templateProjSummary))
			// {
			// 	echo $templateProjSummary;
			// }
			// else {
			// 	echo "hello";
			// }

			$data['project'] = $this->model->getProjectByID($projectID);
			$data['mainActivity'] = $this->model->getAllMainActivitiesByID($projectID);
			$data['subActivity'] = $this->model->getAllSubActivitiesByID($projectID);
			$data['tasks'] = $this->model->getAllTasksByIDRole1($projectID);
			$data['earlyTasks'] = $this->model->getAllEarlyTasksByIDRole1($projectID);
			$data['delayedTasks'] = $this->model->getAllDelayedTasksByIDRole1($projectID);
			$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($projectID);
			$data['changeRequests'] = $this->model->getChangeRequestsByProject($projectID);
			$data['documents'] = $this->model->getAllDocumentsByProject($projectID);
			$data['projectCompleteness'] = $this->model->compute_completeness_project($projectID);
			$data['projectTimeliness'] = $this->model->compute_timeliness_project($projectID);
			$data['departments'] = $this->model->compute_timeliness_departmentByProject($projectID);
			$data['team'] = $this->model->getTeamByProject($projectID);
			$data['users'] = $this->model->getAllUsers();
			$data['allDepartments'] = $this->model->getAllDepartments();
			$data['taskCount'] = $this->model->getTaskCountByProjectByRole($projectID);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employeesByProject($projectID);

			$this->load->view("projectSummary", $data);

		} else { // projectGantt

			$data['projectProfile'] = $this->model->getProjectByID($projectID);
			$data['ganttData'] = $this->model->getAllProjectTasksGroupByTaskID($projectID);
			$data['dependencies'] = $this->model->getDependenciesByProject($projectID);
			$data['users'] = $this->model->getAllUsers();

			$data['responsible'] = $this->model->getAllResponsibleByProject($projectID);
			$data['accountable'] = $this->model->getAllAccountableByProject($projectID);
			$data['consulted'] = $this->model->getAllConsultedByProject($projectID);
			$data['informed'] = $this->model->getAllInformedByProject($projectID);

			$data['employeeCompleteness'] = $this->model->compute_completeness_employeeByProject($_SESSION['USERID'], $projectID);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employeeByProject($_SESSION['USERID'], $projectID);
			$data['projectCompleteness'] = $this->model->compute_completeness_project($projectID);
			$data['projectTimeliness'] = $this->model->compute_timeliness_project($projectID);

			$data['isTemplate'] = $this->model->checkIfTemplate($projectID);


			$this->load->view("projectGantt", $data);

		}
	}

	/******************** MY PROJECTS START ********************/

	// ADDS MAIN ACTIVITIES TO PROJECT
	public function addTasksToProject()
	{
		// GET PROJECT ID
		$id = $this->input->post("project_ID");

		// GET ARRAY OF INPUTS FROM VIEW
		$title = $this->input->post('title');
		$startDates = $this->input->post('taskStartDate');
		$endDates = $this->input->post('taskEndDate');
		$department = $this->input->post("department");
		$rowNum = $this->input->post('row');
		$templateTaskID = $this->input->post('templateTaskID');

		$addedTask = array();

		// GET ALL DEPTS TO ASSIGN DEPT HEAD TO TASK
		$departments = $this->model->getAllDepartments();

		foreach($departments as $row)
		{
			switch ($row['DEPARTMENTNAME'])
			{
				case 'Executive':
					$execHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Marketing':
					$mktHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Finance':
					$finHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Procurement':
					$proHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Human Resource':
					$hrHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Management Information System':
					$misHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Store Operations':
					$opsHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Facilities Administration':
					$fadHead = $row['users_DEPARTMENTHEAD'];
					break;
			}
		}

		date_default_timezone_set("Singapore");
		$currDate = date("Y-m-d");

		foreach ($title as $key=> $row)
		{
			if ($currDate >= $startDates[$key])
			{
				$tStatus = 'Ongoing';
			}

			else
			{
				$tStatus = 'Planning';
			}

			if (isset($templateTaskID[$key]))
			{
				$data = array(
	          'TASKTITLE' => $row,
	          'TASKSTARTDATE' => $startDates[$key],
	          'TASKENDDATE' => $endDates[$key],
	          'TASKSTATUS' => $tStatus,
	          'CATEGORY' => '1',
	          'projects_PROJECTID' => $id,
						'TEMPLATETASKID' => $templateTaskID[$key]
	      );
			}

			else
			{
				$data = array(
	          'TASKTITLE' => $row,
	          'TASKSTARTDATE' => $startDates[$key],
	          'TASKENDDATE' => $endDates[$key],
	          'TASKSTATUS' => $tStatus,
	          'CATEGORY' => '1',
	          'projects_PROJECTID' => $id
	      );
			}

      $addedTask[] = $this->model->addTasksToProject($data);
		}

		// TESTING
		foreach ($addedTask as $aKey=> $a)
		{
				// echo " -- " . $a . " -- <br>";
			foreach ($rowNum as $rKey => $row)
			{
				// echo $aKey . " == " . $rKey . "<br>";
				// echo $row . "<br>";

				if ($aKey == $rKey)
				{
					foreach ($department as $dKey => $d)
					{
						if ($row == $dKey)
						{
							foreach ($d as $value)
							{
								switch ($value)
								{
									case 'Marketing':
										$deptHead = $mktHead;
										break;
									case 'Finance':
										$deptHead = $finHead;
										break;
									case 'Procurement':
										$deptHead = $proHead;
										break;
									case 'Human Resource':
										$deptHead = $hrHead;
										break;
									case 'Management Information System':
										$deptHead = $misHead;
										break;
									case 'Store Operations':
										$deptHead = $opsHead;
										break;
									case 'Facilities Administration':
										$deptHead = $fadHead;
										break;
								}

								// echo $value . ", ";

								if ($value == 'All')
								{
									foreach ($departments as $deptKey => $dept)
									{
										if ($dept['DEPARTMENTNAME'] != 'Executive')
										{
											$data = array(
													'ROLE' => '0',
													'users_USERID' => $dept['users_DEPARTMENTHEAD'],
													'tasks_TASKID' => $a,
													'STATUS' => 'Current'
											);

											// ENTER INTO RACI
											$result = $this->model->addToRaci($data);

											// START OF LOGS/NOTIFS
											$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

											$taskDetails = $this->model->getTaskByID($a);
											$taskTitle = $taskDetails['TASKTITLE'];

											$projectID = $taskDetails['projects_PROJECTID'];
											$projectDetails = $this->model->getProjectByID($projectID);
											$projectTitle = $projectDetails['PROJECTTITLE'];

											$userDetails = $this->model->getUserByID($dept['users_DEPARTMENTHEAD']);
											$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

											// START: LOG DETAILS
											$details = $userName . " has created Main Activity - " . $taskTitle . ".";

											$logData = array (
												'LOGDETAILS' => $details,
												'TIMESTAMP' => date('Y-m-d H:i:s'),
												'projects_PROJECTID' => $projectID
											);

											$this->model->addToProjectLogs($logData);
											// END: LOG DETAILS

											//START: Notifications
											$details = "A new project has been created. " . $userName . " has tagged you to delegate Main Activity - " . $taskTitle . " in " . $projectTitle . ".";
											$notificationData = array(
												'users_USERID' => $dept['users_DEPARTMENTHEAD'],
												'DETAILS' => $details,
												'TIMESTAMP' => date('Y-m-d H:i:s'),
												'status' => 'Unread',
												'projects_PROJECTID' => $projectID,
												'tasks_TASKID' => $a,
												'TYPE' => '2'
											);

											$this->model->addNotification($notificationData);
											// END: Notification
										}
									}
								}

								else
								{
									$data = array(
											'ROLE' => '0',
											'users_USERID' => $deptHead,
											'tasks_TASKID' => $a,
											'STATUS' => 'Current'
									);

									// ENTER INTO RACI
									$result = $this->model->addToRaci($data);

									// // START OF LOGS/NOTIFS
									// $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
									//
									// $taskDetails = $this->model->getTaskByID($a);
									// $taskTitle = $taskDetails['TASKTITLE'];
									//
									// $projectID = $taskDetails['projects_PROJECTID'];
									// $projectDetails = $this->model->getProjectByID($projectID);
									// $projectTitle = $projectDetails['PROJECTTITLE'];
									//
									// $userDetails = $this->model->getUserByID($deptHead);
									// $taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];
									//
									// // START: LOG DETAILS
									// $details = $userName . " has created Main Activity - " . $taskTitle . ".";
									//
									// $logData = array (
									// 	'LOGDETAILS' => $details,
									// 	'TIMESTAMP' => date('Y-m-d H:i:s'),
									// 	'projects_PROJECTID' => $projectID
									// );
									//
									// $this->model->addToProjectLogs($logData);
									// // END: LOG DETAILS
									//
									// //START: Notifications
									// $details = "A new project has been created. " . $userName . " has tagged you to delegate Main Activity - " . $taskTitle . " in " . $projectTitle . ".";
									// $notificationData = array(
									// 	'users_USERID' => $deptHead,
									// 	'DETAILS' => $details,
									// 	'TIMESTAMP' => date('Y-m-d H:i:s'),
									// 	'status' => 'Unread',
									// 	'projects_PROJECTID' => $projectID,
									// 	'tasks_TASKID' => $a,
									// 	'TYPE' => '2'
									// );
									//
									// $this->model->addNotification($notificationData);
									// // END: Notification
								}
							}
							// echo "<br>";
						}
					}
				}
			}
		}

		$startDate = $this->model->getProjectByID($id);
		date_default_timezone_set("Singapore");
		$currDate = date("Y-m-d");

		if ($currDate >= $startDate['PROJECTSTARTDATE'])
		{
			$status = array(
				"PROJECTSTATUS" => "Ongoing");
		}

		else
		{
			$status = array(
				"PROJECTSTATUS" => "Planning");
		}

		$changeStatues = $this->model->updateProjectStatusPlanning($id, $status);

			$data['project'] = $this->model->getProjectByID($id);
			$data['tasks'] = $this->model->getAllProjectTasks($id);
			$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);
			$data['users'] = $this->model->getAllUsers();
			$data['departments'] = $this->model->getAllDepartments();

			$sDate = date_create($data['project']['PROJECTSTARTDATE']);
			$eDate = date_create($data['project']['PROJECTENDDATE']);
			$diff = date_diff($eDate, $sDate, true);
			$dateDiff = $diff->format('%R%a');

			$data['dateDiff'] = $dateDiff;

			$templates = $this->input->post('templates');

			if (isset($templates))
			{
				$this->session->set_flashdata('templates', $templates);

				$data['templateProject'] = $this->model->getProjectByID($templates);
				$data['templateAllTasks'] = $this->model->getAllProjectTasks($templates);
				$data['templateGroupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($templates);
				$data['templateMainActivity'] = $this->model->getAllMainActivitiesByID($templates);
				$data['templateSubActivity'] = $this->model->getAllSubActivitiesByID($templates);
				$data['templateTasks'] = $this->model->getAllTasksByIDRole1($templates);
				$data['templateRaci'] = $this->model->getRaci($templates);
				$data['templateUsers'] = $this->model->getAllUsers();
			}

			// $this->output->enable_profile(TRUE);
			$this->load->view('addSubActivities', $data);
		}

		public function editMainActivity()
		{
			$projectID = $this->input->post('edit');

      $data = array(
          'PROJECTTITLE' => $this->input->post('projectTitle'),
          'PROJECTSTARTDATE' => $this->input->post('startDate'),
          'PROJECTENDDATE' => $this->input->post('endDate'),
          'PROJECTDESCRIPTION' => $this->input->post('projectDetails'),
					'PROJECTTYPE' => $this->input->post('type')
      );

      $sDate = date_create($this->input->post('startDate'));
      $eDate = date_create($this->input->post('endDate'));
      $diff = date_diff($eDate, $sDate, true);
      $dateDiff = $diff->format('%R%a');

      // PLUGS DATA INTO DB AND RETURNS ARRAY OF THE PROJECT
      $data['project'] = $this->model->editProjectDetails($projectID, $data);
      $data['dateDiff'] =$dateDiff;
      $data['departments'] = $this->model->getAllDepartments();

      if ($data)
      {
        // TODO PUT ALERT

        // START OF LOGS/NOTIFS
        $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

        // START: LOG DETAILS
        $details = $userName . " edited this project.";

        $logData = array (
          'LOGDETAILS' => $details,
          'TIMESTAMP' => date('Y-m-d H:i:s'),
          'projects_PROJECTID' => $projectID
        );

        $this->model->addToProjectLogs($logData);
        // END: LOG DETAILS

        if (isset($_SESSION['edit']) || isset($_SESSION['templates']))
        {
          $this->session->set_flashdata('edit', $projectID);

          $data['templateProject'] = $this->model->getProjectByID($projectID);
          $data['templateAllTasks'] = $this->model->getAllProjectTasks($projectID);
          $data['templateGroupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($projectID);
          $data['templateMainActivity'] = $this->model->getAllMainActivitiesByID($projectID);
          $data['templateSubActivity'] = $this->model->getAllSubActivitiesByID($projectID);
          $data['templateTasks'] = $this->model->getAllTasksByIDRole1($projectID);
          $data['templateRaci'] = $this->model->getRaci($projectID);
          $data['templateUsers'] = $this->model->getAllUsers();
        }

        $this->load->view('addMainActivities', $data);
      }
		}

		public function editSubActivity()
		{
			// GET PROJECT ID
		  $id = $this->input->post("project_ID");

		  // GET ARRAY OF INPUTS FROM VIEW
		  $title = $this->input->post('title');
		  $startDates = $this->input->post('taskStartDate');
		  $endDates = $this->input->post('taskEndDate');
		  $department = $this->input->post("department");
		  $rowNum = $this->input->post('row');
		  $taskID = $this->input->post('taskID');

		  $addedTask = array();

			// CHANGE RACI STATUS
		  $allMainRaci = $this->model->getRaciMain($id);

		  foreach ($allMainRaci as $allR)
		  {
		  	$data = array(
		  		'ROLE' => '0',
		  		'STATUS' => 'Changed'
		  	);

		  	$changeRaci = $this->model->changeRACIStatus($allR['tasks_TASKID'], $data);
		  }

			// GET ALL DEPTS TO ASSIGN DEPT HEAD TO TASK
		  $departments = $this->model->getAllDepartments();

		  foreach($departments as $row)
		  {
		    switch ($row['DEPARTMENTNAME'])
		    {
		      case 'Executive':
		        $execHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Marketing':
		        $mktHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Finance':
		        $finHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Procurement':
		        $proHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Human Resource':
		        $hrHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Management Information System':
		        $misHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Store Operations':
		        $opsHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Facilities Administration':
		        $fadHead = $row['users_DEPARTMENTHEAD'];
		        break;
		    }
		  }

		  date_default_timezone_set("Singapore");
		  $currDate = date("Y-m-d");

		  foreach ($title as $key=> $row)
		  {
		    if ($currDate >= $startDates[$key])
		    {
		      $tStatus = 'Ongoing';
		    }

		    else
		    {
		      $tStatus = 'Planning';
		    }

		    if (isset($taskID[$key]))
		    {
		      $data = array(
		          'TASKTITLE' => $row,
		          'TASKSTARTDATE' => $startDates[$key],
		          'TASKENDDATE' => $endDates[$key],
		          'TASKSTATUS' => $tStatus,
		          'CATEGORY' => '1',
		          'projects_PROJECTID' => $id
		      );
		    }

		    else
		    {
		      $data = array(
		          'TASKTITLE' => $row,
		          'TASKSTARTDATE' => $startDates[$key],
		          'TASKENDDATE' => $endDates[$key],
		          'TASKSTATUS' => $tStatus,
		          'CATEGORY' => '1',
		          'projects_PROJECTID' => $id
		      );
		    }

		    $addedTask[] = $this->model->editTask($taskID[$key], $data);
		  }

			// TESTING
		  foreach ($addedTask as $aKey=> $a)
		  {
		      // echo " -- " . $a . " -- <br>";
		    foreach ($rowNum as $rKey => $row)
		    {
		      // echo $aKey . " == " . $rKey . "<br>";
		      // echo $row . "<br>";

		      if ($aKey == $rKey)
		      {
		        foreach ($department as $dKey => $d)
		        {
		          if ($row == $dKey)
		          {
		            foreach ($d as $value)
		            {
		              switch ($value)
		              {
		                case 'Marketing':
		                  $deptHead = $mktHead;
		                  break;
		                case 'Finance':
		                  $deptHead = $finHead;
		                  break;
		                case 'Procurement':
		                  $deptHead = $proHead;
		                  break;
		                case 'Human Resource':
		                  $deptHead = $hrHead;
		                  break;
		                case 'Management Information System':
		                  $deptHead = $misHead;
		                  break;
		                case 'Store Operations':
		                  $deptHead = $opsHead;
		                  break;
		                case 'Facilities Administration':
		                  $deptHead = $fadHead;
		                  break;
		              }

		              // echo $value . ", ";

		              if ($value == 'All')
		              {
		                foreach ($departments as $deptKey => $dept)
		                {
		                  if ($dept['DEPARTMENTNAME'] != 'Executive')
		                  {
		                    $data = array(
		                        'ROLE' => '0',
		                        'users_USERID' => $dept['users_DEPARTMENTHEAD'],
		                        'tasks_TASKID' => $a,
		                        'STATUS' => 'Current'
		                    );

		                    // ENTER INTO RACI
		                    $result = $this->model->addToRaci($data);

		                    // START OF LOGS/NOTIFS
		                    $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

		                    $taskDetails = $this->model->getTaskByID($a);
		                    $taskTitle = $taskDetails['TASKTITLE'];

		                    $projectID = $taskDetails['projects_PROJECTID'];
		                    $projectDetails = $this->model->getProjectByID($projectID);
		                    $projectTitle = $projectDetails['PROJECTTITLE'];

		                    $userDetails = $this->model->getUserByID($dept['users_DEPARTMENTHEAD']);
		                    $taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

		                    // START: LOG DETAILS
		                    $details = $userName . " has edited Main Activity - " . $taskTitle . ".";

		                    $logData = array (
		                      'LOGDETAILS' => $details,
		                      'TIMESTAMP' => date('Y-m-d H:i:s'),
		                      'projects_PROJECTID' => $projectID
		                    );

		                    $this->model->addToProjectLogs($logData);
		                    // END: LOG DETAILS

		                    //START: Notifications
		                    $details = "A project has been edited. " . $userName . " has tagged you to delegate Main Activity - " . $taskTitle . " in " . $projectTitle . ".";
		                    $notificationData = array(
		                      'users_USERID' => $dept['users_DEPARTMENTHEAD'],
		                      'DETAILS' => $details,
		                      'TIMESTAMP' => date('Y-m-d H:i:s'),
		                      'status' => 'Unread',
		                      'projects_PROJECTID' => $projectID,
		                      'tasks_TASKID' => $a,
		                      'TYPE' => '2'
		                    );

		                    $this->model->addNotification($notificationData);
		                    // END: Notification
		                  }
		                }
		              }

		              else
		              {
		                $data = array(
		                    'ROLE' => '0',
		                    'users_USERID' => $deptHead,
		                    'tasks_TASKID' => $a,
		                    'STATUS' => 'Current'
		                );

		                // ENTER INTO RACI
		                $result = $this->model->addToRaci($data);

		              }
		            }
		            // echo "<br>";
		          }
		        }
		      }
		    }
		  }

			$startDate = $this->model->getProjectByID($id);
		  date_default_timezone_set("Singapore");
		  $currDate = date("Y-m-d");

		  if ($currDate >= $startDate['PROJECTSTARTDATE'])
		  {
		    $status = array(
		      "PROJECTSTATUS" => "Ongoing");
		  }

		  else
		  {
		    $status = array(
		      "PROJECTSTATUS" => "Planning");
		  }

		  $changeStatues = $this->model->updateProjectStatusPlanning($id, $status);

		    $data['project'] = $this->model->getProjectByID($id);
		    $data['tasks'] = $this->model->getAllProjectTasks($id);
		    $data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskIDMain($id);
		    $data['users'] = $this->model->getAllUsers();
		    $data['departments'] = $this->model->getAllDepartments();

		    $sDate = date_create($data['project']['PROJECTSTARTDATE']);
		    $eDate = date_create($data['project']['PROJECTENDDATE']);
		    $diff = date_diff($eDate, $sDate, true);
		    $dateDiff = $diff->format('%R%a');

		    $data['dateDiff'] = $dateDiff;

		    if (isset($_SESSION['edit']))
		    {
		      $data['templateProject'] = $this->model->getProjectByID($id);
		      $data['templateAllTasks'] = $this->model->getAllProjectMainSub($id);
		      $data['templateGroupedTasks'] = $this->model->getAllProjectTasksGroupByTaskIDMain($id);
		      $data['templateMainActivity'] = $this->model->getAllMainActivitiesByID($id);
		      $data['templateSubActivity'] = $this->model->getAllSubActivitiesByID($id);
		      $data['templateTasks'] = $this->model->getAllTasksByIDRole1($id);
		      $data['templateRaci'] = $this->model->getRaci($id);
		      $data['templateUsers'] = $this->model->getAllUsers();
		    }

		    // $this->output->enable_profile(TRUE);
		    $this->load->view('addSubActivities', $data);
		}

		// ADDS SUB ACTIVITIES TO MAIN ACTIVITIES OF PROJECT
		public function addSubActivities()
		{
		  $id = $this->input->post('project_ID');

		  $parent = $this->input->post('mainActivity_ID');
		  $title = $this->input->post('title');
		  $startDates = $this->input->post('taskStartDate');
		  $endDates = $this->input->post('taskEndDate');
			$department = $this->input->post("department");
			$rowNum = $this->input->post('row');
			$templateTaskID = $this->input->post('templateTaskID');

			$addedTask = array();

		  $departments = $this->model->getAllDepartments();

		  foreach($departments as $row)
		  {
		    switch ($row['DEPARTMENTNAME'])
		    {
		      case 'Executive':
		        $execHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Marketing':
		        $mktHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Finance':
		        $finHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Procurement':
		        $proHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Human Resource':
		        $hrHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Management Information System':
		        $misHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Store Operations':
		        $opsHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Facilities Administration':
		        $fadHead = $row['users_DEPARTMENTHEAD'];
		        break;
		    }
		  }

			date_default_timezone_set("Singapore");
			$currDate = date("Y-m-d");

		  foreach ($title as $key=> $row)
		  {
				if ($currDate >= $startDates[$key])
				{
					$tStatus = 'Ongoing';
				}

				else
				{
					$tStatus = 'Planning';
				}

				if (isset($templateTaskID[$key]))
				{
					$data = array(
		          'TASKTITLE' => $row,
		          'TASKSTARTDATE' => $startDates[$key],
		          'TASKENDDATE' => $endDates[$key],
		          'TASKSTATUS' => $tStatus,
		          'CATEGORY' => '2',
		          'projects_PROJECTID' => $id,
		          'tasks_TASKPARENT' => $parent[$key],
							'TEMPLATETASKID' => $templateTaskID[$key]
		      );
				}

				else
				{
					$data = array(
		          'TASKTITLE' => $row,
		          'TASKSTARTDATE' => $startDates[$key],
		          'TASKENDDATE' => $endDates[$key],
		          'TASKSTATUS' => $tStatus,
		          'CATEGORY' => '2',
		          'projects_PROJECTID' => $id,
		          'tasks_TASKPARENT' => $parent[$key]
		      );
				}

				// SAVES ALL ADDED TASKS INTO AN ARRAY
	      $addedTask[] = $this->model->addTasksToProject($data);
		   }

			// GETS DEPARTMENT ARRAY FOR RACI
			foreach ($addedTask as $aKey=> $a)
	 		{
	 			// echo " -- " . $a . " -- " . $parent[$aKey] . "<br>";
				// rowNum SAVES THE ORDER OF HOW THE DEPARTMENT ARRAY MUST LOOK LIKE
	 			foreach ($rowNum as $rKey => $row)
	 			{
					// echo $row . "<br>";
	 				// echo $aKey . " == " . $rKey . "<br>";
	 				if ($aKey == $rKey)
	 				{
						// echo $aKey . " == " . $rKey . "<br>";
	 					foreach ($department as $dKey => $d)
	 					{
							// echo $row . " == " . $dKey . "<br>";
	 						if ($row == $dKey)
	 						{
								// echo $row . " == " . $dKey . "<br>";
	 							foreach ($d as $value)
	 							{
	 								switch ($value)
	 								{
	 									case 'Executive':
	 										$deptHead = $execHead;
	 										break;
	 									case 'Marketing':
	 										$deptHead = $mktHead;
	 										break;
	 									case 'Finance':
	 										$deptHead = $finHead;
	 										break;
	 									case 'Procurement':
	 										$deptHead = $proHead;
	 										break;
	 									case 'Human Resource':
	 										$deptHead = $hrHead;
	 										break;
	 									case 'Management Information System':
	 										$deptHead = $misHead;
	 										break;
	 									case 'Store Operations':
	 										$deptHead = $opsHead;
	 										break;
	 									case 'Facilities Administration':
	 										$deptHead = $fadHead;
	 										break;
	 								}

	 								// echo $value . ", ";

	 								$data = array(
	 										'ROLE' => '0',
	 										'users_USERID' => $deptHead,
	 										'tasks_TASKID' => $a,
											'STATUS' => 'Current'
	 								);

	 								// ENTER INTO RACI
	 								$result = $this->model->addToRaci($data);

									// START OF LOGS/NOTIFS
									// $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
									//
									// $taskDetails = $this->model->getTaskByID($a);
									// $taskTitle = $taskDetails['TASKTITLE'];
									//
									// $projectID = $taskDetails['projects_PROJECTID'];
									// $projectDetails = $this->model->getProjectByID($projectID);
									// $projectTitle = $projectDetails['PROJECTTITLE'];
									//
									// $userDetails = $this->model->getUserByID($deptHead);
									// $taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];
									//
									// START: LOG DETAILS
									// $details = $userName . " has created Sub Activity - " . $taskTitle . ".";
									//
									// $logData = array (
									// 	'LOGDETAILS' => $details,
									// 	'TIMESTAMP' => date('Y-m-d H:i:s'),
									// 	'projects_PROJECTID' => $projectID
									// );
									//
									// $this->model->addToProjectLogs($logData);
									// END: LOG DETAILS
									//
									// //START: Notifications
									// $details = "A new project has been created. " . $userName . " has tagged you to delegate Sub Activity - " . $taskTitle . " in " . $projectTitle . ".";
									// $notificationData = array(
									// 	'users_USERID' => $deptHead,
									// 	'DETAILS' => $details,
									// 	'TIMESTAMP' => date('Y-m-d H:i:s'),
									// 	'status' => 'Unread',
									// 	'projects_PROJECTID' => $projectID,
									// 	'tasks_TASKID' => $a,
									// 	'TYPE' => '3'
									// );
									//
									// $this->model->addNotification($notificationData);
									// // END: Notification

	 							}
	 							// echo "<br>";
	 						}
	 					}
	 				}
	 			}
	 		}

		  // $this->output->enable_profiler(TRUE);

		  // GANTT CODE
		  // $data['projectProfile'] = $this->model->getProjectByID($id);
		  // $data['ganttData'] = $this->model->getAllProjectTasks($id);
		  // // $data['preReq'] = $this->model->getPreReqID();
		  // $data['dependencies'] = $this->model->getDependencies();
		  // $data['users'] = $this->model->getAllUsers();

		  $data['project'] = $this->model->getProjectByID($id);
		  $data['tasks'] = $this->model->getAllProjectTasks($id);
		  $data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);
			$data['mainActivity'] = $this->model->getAllMainActivitiesByID($id);
			$data['subActivity'] = $this->model->getAllSubActivitiesByID($id);
		  $data['users'] = $this->model->getAllUsers();
		  $data['departments'] = $this->model->getAllDepartments();

		  $sDate = date_create($data['project']['PROJECTSTARTDATE']);
		  $eDate = date_create($data['project']['PROJECTENDDATE']);
			$diff = date_diff($eDate, $sDate, true);
			$dateDiff = $diff->format('%R%a');

		  $data['dateDiff'] = $dateDiff;

			$templates = $this->input->post('templates');

			if (isset($templates))
			{
				$this->session->set_flashdata('templates', $templates);

				$data['templateProject'] = $this->model->getProjectByID($templates);
				$data['templateAllTasks'] = $this->model->getAllProjectTasks($templates);
				$data['templateGroupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($templates);
				$data['templateMainActivity'] = $this->model->getAllMainActivitiesByID($templates);
				$data['templateSubActivity'] = $this->model->getAllSubActivitiesByID($templates);
				$data['templateTasks'] = $this->model->getAllTasksByIDRole1($templates);
				$data['templateRaci'] = $this->model->getRaci($templates);
				$data['templateUsers'] = $this->model->getAllUsers();
				$data['templateSubActTaskID'] = $this->model->getSubActivityTaskID($templates);
			}

			// foreach ($data['templateSubActTaskID'] as $row)
			// {
			// 	echo $row['TASKID'] . "<br>";
			// }

		  // $this->load->view("dashboard", $data);
		  // redirect('controller/projectGantt');
		  $this->load->view("addTasks", $data);
		}

		public function archiveProject()
		{
			$id = $this->input->post("project_ID");

			$data = array(
				'PROJECTSTATUS' => 'Archived'
			);

			$result = $this->model->archiveProject($id, $data);

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$projectDetails = $this->model->getProjectByID($id);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			// START: LOG DETAILS
			$details = $userName . " has archived this project.";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $id
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			if ($result)
			{
				$data['archives'] = $this->model->getAllProjectArchives();

				$this->session->set_flashdata('success', 'alert');
				$this->session->set_flashdata('alertMessage', ' Project has been archived');

				redirect("controller/archives");
			}
		}

		public function parkProject()
		{
			$id = $this->input->post("project_ID");

			$data = array(
				'PROJECTSTATUS' => 'Parked'
			);

			$result = $this->model->parkProject($id, $data);

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$projectDetails = $this->model->getProjectByID($id);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			// START: LOG DETAILS
			$details = $userName . " has parked this project.";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $id
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			if ($result)
			{
				$this->myProjects();
			}
		}

		public function templateProject()
		{
			$id = $this->input->post("project_ID");

			$project = $this->model->getProjectByID($id);

			// templates.PROJECTID == TEMPLATEID
			// templates.PROJECTSTATUS == projects.PROJECTID

			$data = array(
				'PROJECTTITLE' => $project['PROJECTTITLE'] . " Template",
				'PROJECTSTARTDATE' => $project['PROJECTSTARTDATE'],
				'PROJECTENDDATE' => $project['PROJECTACTUALENDDATE'],
				'PROJECTDESCRIPTION' => $project['PROJECTDESCRIPTION'],
				'projects_PROJECTID' => $id,
				'users_USERID' => $_SESSION['USERID']
			);

			$result = $this->model->templateProject($data);

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$projectDetails = $this->model->getProjectByID($id);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			// START: LOG DETAILS
			$details = $userName . " has made this project a template.";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $id
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			//
			if ($result)
			{
				$data['templates'] = $this->model->getAllTemplates();

				$this->session->set_flashdata('success', 'alert');
				$this->session->set_flashdata('alertMessage', ' Project has been saved as a template');
				redirect("controller/templates");
			}
		}

	public function uploadDocument()
	{
		  $config['upload_path']          = './assets/uploads';
		  $config['allowed_types']        = '*';
		  $config['max_size']							= '10000000';

		  $this->load->library('upload', $config);
		  $this->upload->initialize($config);

		  //GET PROJECT ID
		  $id = $this->input->post("project_ID");
		  $projectID = $this->model->getProjectByID($id);

		  // PLACEHOLDER
		  $documentID = "";
		  $deptID = "";

		  // UPLOAD: FAILED
		  if(!$this->upload->do_upload('document'))
		  {
				$error = array('error' => $this->upload->display_errors());

				 $this->session->set_flashdata('danger', 'alert');
				 $this->session->set_flashdata('alertMessage', "did not work " . $error['error']);
		  }

		  else
		  { // START: UPLOAD - SUCCESSFUL

		    $user = $_SESSION['USERID'];
		    $fileName = $this->upload->data('file_name');
		    $src = "http://localhost/Kernel/assets/uploads/" . $fileName;

		    $departments = $this->input->post('departments');
		    $users = $this->input->post('users');

		    if ($departments == NULL && $users == NULL)
		    {
		      $uploadData = array(
		        'DOCUMENTSTATUS' => 'Uploaded',
		        'DOCUMENTNAME' => $fileName,
		        'DOCUMENTLINK' => $src,
		        'users_UPLOADEDBY' => $user,
		        'UPLOADEDDATE' => date('Y-m-d'),
		        'projects_PROJECTID' => $id,
		        'REMARKS' => $this->input->post('remarks')
		      );

		      $this->model->uploadDocument($uploadData);
		      $allUsers = $this->model->getAllUsersByProject($id);

		      // START: Notification
		      $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
		      $details = $userName . " has uploaded " . $fileName . ".";

		      foreach($allUsers as $user)
		      {
		        if($user['users_USERID'] != $_SESSION['USERID'])
		        {

		          $notificationData = array(
		            'users_USERID' => $user['users_USERID'],
		            'DETAILS' => $details,
		            'TIMESTAMP' => date('Y-m-d H:i:s'),
		            'status' => 'Unread',
		            'projects_PROJECTID' => $id,
		            'TYPE' => '5'
		          );

		          $this->model->addNotification($notificationData);
		          // END: Notification

		        }
		      }
		    }

		    else
		    {
		      $uploadData = array(
		        'DOCUMENTSTATUS' => 'For Acknowledgement',
		        'DOCUMENTNAME' => $fileName,
		        'DOCUMENTLINK' => $src,
		        'users_UPLOADEDBY' => $user,
		        'UPLOADEDDATE' => date('Y-m-d'),
		        'projects_PROJECTID' => $id,
		        'REMARKS' => $this->input->post('remarks')
		      );

		      // INSERT IN DOCUMENTS TABLE, RETURNS DOCUMENTID OF INSERTED DATA
		      $documentID = $this->model->uploadDocument($uploadData);

		      if ($departments != NULL)
		      {
		        foreach ($departments as $departmentRow)
		        {
		          $departmentUsers = $this->model->getAllUsersByProjectByDepartment($id, $departmentRow);

		          foreach ($departmentUsers as $userIDByDepartment)
		          {
		            $acknowledgementData = array (
		              'documents_DOCUMENTID' => $documentID,
		              'users_ACKNOWLEDGEDBY' => $userIDByDepartment['users_USERID']
		            );

		            // INSERT IN DOCUMENT ACKNOWLEDGMENT TABLE
		            $this->model->addToDocumentAcknowledgement($acknowledgementData);

		            // START: Notification
		            $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
		            $projectTitle = $projectID['PROJECTTITLE'];
		            $details = $userName . " has uploaded " . $fileName . " to the project " .  $projectTitle . " and needs your acknowledgement.";

		            $notificationData = array(
		              'users_USERID' => $userIDByDepartment['users_USERID'],
		              'DETAILS' => $details,
		              'TIMESTAMP' => date('Y-m-d H:i:s'),
		              'status' => 'Unread',
		              'projects_PROJECTID' => $id,
		              'TYPE' => '5'
		            );

		            $this->model->addNotification($notificationData);
		            // END: Notification
		          }
		        }
		      }

		      if ($users != NULL)
		      {
		        foreach ($users as $userID)
		        {
		          // CHECKS DOCUMENT ACKNOWLEDGMENT TABLE FOR DUPLICATION
		          $documentAcknowledgement = $this->model->getDocumentAcknowledgementID($userID, $documentID);

		          if (!$documentAcknowledgement['DOCUMENTACKNOWLEDGEMENTID'])
		          {
		            $acknowledgementData = array (
		              'documents_DOCUMENTID' => $documentID,
		              'users_ACKNOWLEDGEDBY' => $userID
		            );

		            // START: Notification
		            $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
		            $projectTitle = $projectID['PROJECTTITLE'];
		            $details = $userName . " has uploaded " . $fileName . " to the project " .  $projectTitle . " and needs your acknowledgement.";

		            $notificationData = array(
		              'users_USERID' => $userID,
		              'DETAILS' => $details,
		              'TIMESTAMP' => date('Y-m-d H:i:s'),
		              'status' => 'Unread',
		              'projects_PROJECTID' => $id,
		              'TYPE' => '5'
		            );

		            $this->model->addNotification($notificationData);
		            // END: Notification

		            // INSERT IN DOCUMENT ACKNOWLEDGMENT TABLE
		            $this->model->addToDocumentAcknowledgement($acknowledgementData);
		          }
		        }
		      }
		    }

		    // START: LOG DETAILS
		    $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
		    $details = $userName . " uploaded " . $fileName;

		    $logData = array (
		      'LOGDETAILS' => $details,
		      'TIMESTAMP' => date('Y-m-d H:i:s'),
		      'projects_PROJECTID' => $id
		    );

		    $this->model->addToProjectLogs($logData);
		    // END: LOG DETAILS

				$this->session->set_flashdata('success', 'alert');
			  $this->session->set_flashdata('alertMessage', ' Document uploaded successfully');
		  }

		  $this->session->set_flashdata('projectID', $id);
		  $data['projectProfile'] = $this->model->getProjectByID($id);
		  $data['departments'] = $this->model->getAllDepartmentsByProject($id);
		  $data['documentsByProject'] = $this->model->getAllDocumentsByProject($id);
		  $data['documentAcknowledgement'] = $this->model->getDocumentsForAcknowledgement($id, $_SESSION['USERID']);
		  $data['users'] = $this->model->getAllUsersByProject($id);

			redirect("controller/projectDocuments");
			// $this->load->view("projectDocuments", $data);
	}

	public function acknowledgeDocument()
	{
		//GET DOCUMENT ID
		$documentID = $this->input->post("documentID");
		$projectID = $this->input->post("projectID");
		$dashboard = $this->input->post("fromWhere");
		$fileName = $this->input->post("fileName");

		$currentDate = date('Y-m-d');

		$result = $this->model->updateDocumentAcknowledgement($documentID, $_SESSION['USERID'], $currentDate);

		// START: LOG DETAILS
		$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
		$details = $userName . " has acknowledged " . $fileName;

		$logData = array (
			'LOGDETAILS' => $details,
			'TIMESTAMP' => date('Y-m-d H:i:s'),
			'projects_PROJECTID' => $projectID
		);

		$this->model->addToProjectLogs($logData);
		// END: LOG DETAILS

		if ($result)
		{
			if(isset($dashboard))
			{
				$this->session->set_flashdata('success', 'alert');
				$this->session->set_flashdata('alertMessage', ' Document acknowledged');
				redirect('controller/dashboard');
			}

			else
			{
				$data['projectProfile'] = $this->model->getProjectByID($projectID);
				$data['departments'] = $this->model->getAllDepartmentsByProject($projectID);
				$data['documentsByProject'] = $this->model->getAllDocumentsByProject($projectID);
				$data['documentAcknowledgement'] = $this->model->getDocumentsForAcknowledgement($projectID, $_SESSION['USERID']);
				$data['users'] = $this->model->getAllUsersByProject($projectID);

				$this->session->set_flashdata('success', 'alert');
				$this->session->set_flashdata('alertMessage', ' Document acknowledged');
				$this->session->set_flashdata('projectID', $projectID);

				redirect('controller/projectDocuments');
			}
		}
	}

	public function getAllNotificationsByUser()
	{
		$data['notification'] = $this->model->getAllNotificationsByUser();
		$data['notifications'] = $this->model->getAllNotificationsByUser();

		// $notifications = $this->model->getAllNotificationsByUser($sessionData['USERID']);
		$this->session->set_userdata('notifications', $data['notifications']);

		echo json_encode($data);
	}

	public function getAllTasksByUser()
	{
		$data['allTasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);

		$this->session->set_userdata('allTasks', $data['allTasks']);

		echo json_encode($data);
	}

	/******************** MY PROJECTS END ********************/

	public function changePassword()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('oldPass', 'Old Password', 'required'); // check if match with current password
		$this->form_validation->set_rules('newPass', 'New Password', 'required');

		$oldPass = $this->input->post('oldPass');
		$newPass = $this->input->post('newPass');
		$checker = $this->model->samePassword($oldPass);

		if(!$checker)
		{
			$this->session->set_flashdata('danger', 'alert');
			$this->session->set_flashdata('alertMessage', ' Current password is incorrect');
			redirect('controller/dashboard');
		}

		else
		{
			$this->form_validation->set_rules('confirmPass', 'Confirm Password', 'required|matches[newPass]'); //check na dapat same with new pass

			if ($this->form_validation->run() == FALSE)
			{
				$this->session->set_flashdata('danger', 'alert');
				$this->session->set_flashdata('alertMessage', ' Passwords do not match');
				redirect('controller/dashboard');
			}

			else
			{
				$this->form_validation->set_rules('newPass', 'New Password', 'required|min_length[6]');

				if ($this->form_validation->run() == FALSE)
				{
					$this->session->set_flashdata('danger', 'alert');
					$this->session->set_flashdata('alertMessage', ' Password too short');
					redirect('controller/dashboard');
				}

				else
				{
					$isSamePassword = $this->model->checkSamePassword($newPass);

					if ($isSamePassword)
					{
						$this->session->set_flashdata('danger', 'alert');
						$this->session->set_flashdata('alertMessage', ' New password must be different');
						redirect('controller/dashboard');
					}

					else
					{
						$data = array(
							'PASSWORD' => password_hash($newPass, PASSWORD_DEFAULT)
						);

						// echo password_hash($newPass, PASSWORD_DEFAULT);

						// echo "<br>$2y$10$weMZc/mMJqs0HLU58WGfDeHr.SzSEBtDlEBxt3WOk/T3zM/zc25.S";

						$result = $this->model->updatePassword($data);

						if ($result)
						{
							$this->session->set_flashdata('success', 'alert');
							$this->session->set_flashdata('alertMessage', ' Password changed');
							redirect('controller/dashboard');
						}

						else
						{
							$this->session->set_flashdata('danger', 'alert');
							$this->session->set_flashdata('alertMessage', ' Error in changing password');
							redirect('controller/dashboard');
						}
					}
				}
			}
		}
	}

	public function getDelayEffect()
	{
		$taskID = $this->input->post("task_ID");
		$task = $this->model->getTaskByID($taskID);
		$taskPostReqs = $this->model->getPostDependenciesByTaskID($taskID);

		$affectedTasks = array();

		if(COUNT($taskPostReqs) > 0) // if there are post requisite tasks
		{
			$postReqsToAdjust = array();
			$postReqsToAdjust[] = $taskID; // add requested task to array
			$i = 0; // set counter
			while(COUNT($postReqsToAdjust) > 0) // loop while array is not empty/there are postreqs to check
			{
				$postReqs = $this->model->getPostDependenciesByTaskID($postReqsToAdjust[$i]); // get post reqs of current task

				if(COUNT($postReqs) > 0) // if there are post reqs found
				{
					foreach($postReqs as $postReq)
					{
						$startDate = $postReq['TASKSTARTDATE'];
						$endDate = $postReq['currDate'];

						if($endDate >= $startDate) //check if currTasks's end date will exceed the postreq's start date
						{
							if($postReq['TASKADJUSTEDSTARTDATE'] != null && $postReq['TASKADJUSTEDENDDATE'] != null)
								$taskDuration = $postReq['adjustedTaskDuration2'];
							elseif($postReq['TASKSTARTDATE'] != null && $postReq['TASKADJUSTEDENDDATE'] != null)
								$taskDuration = $postReq['adjustedTaskDuration1'];
							else
								$taskDuration = $postReq['initialTaskDuration'];

							if($postReq['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
								$currTaskEnd = $postReq['TASKENDDATE'];
							else
								$currTaskEnd = $postReq['TASKADJUSTEDENDDATE'];

							$new_start = date('Y-m-d', strtotime($endDate . ' +1 day')); // set start date to one day after enddate
							$new_end = date('Y-m-d', strtotime($new_start . ' +' . ($taskDuration-1) . ' day')); // set end date according to duration

							foreach($affectedTasks as $affectedTask)
							{
								if($affectedTask['id'] == $postReqsToAdjust[$i])
								{
									$new_start = date('Y-m-d', strtotime($affectedTask['newEndDate'] . ' +1 day'));
									$new_end = date('Y-m-d', strtotime($new_start . ' +' . ($taskDuration-1) . ' day'));
								}
							}

							$affectedTasks[] = array("projEndDate" => $task['PROJECTENDDATE'],
																		"id" => $postReq['TASKID'],
																		"taskTitle" => $postReq['TASKTITLE'],
																		"taskStatus" => $postReq['TASKSTATUS'],
								                    "startDate" => $postReq['TASKSTARTDATE'],
								                    "newStartDate" => $new_start,
								                    "endDate" => $currTaskEnd,
																		"newEndDate" => $new_end,
																		"responsible" => $postReq['FIRSTNAME'] . " " . $postReq['LASTNAME']);

						}
						array_push($postReqsToAdjust, $postReq['TASKID']); // save task to array for checking
					}
				}
				unset($postReqsToAdjust[$i]); // remove current task from array
				$i++; // increase counter
			}
		}

		// ARRAY CLEAN UP
		$index = 0;
		foreach($affectedTasks as $affectedTask1)
		{
			$doubleCount = 0;
			foreach($affectedTasks as $affectedTask2)
			{
				if($affectedTask1['id'] == $affectedTask2['id'])
				{
					$doubleCount++;
					if($doubleCount >= 2)
					{
						$affectedTasks[$index] = array("id" => null);
					}
				}
			}
			$index++;
		}

		echo json_encode($affectedTasks);
	}

	public function getRFCDateEffect()
	{
		$rfcID = $this->input->post("rfc_ID");
		$rfc = $this->model->getChangeRequestbyID($rfcID);
		$taskPostReqs = $this->model->getPostDependenciesByTaskID($rfc['tasks_REQUESTEDTASK']);

		$affectedTasks = array();

		if(COUNT($taskPostReqs) > 0) // if there are post requisite tasks
		{
			$postReqsToAdjust = array();
			$postReqsToAdjust[] = $rfc['tasks_REQUESTEDTASK']; // add requested task to array
			$i = 0; // set counter
			while(COUNT($postReqsToAdjust) > 0) // loop while array is not empty/there are postreqs to check
			{
				$postReqs = $this->model->getPostDependenciesByTaskID($postReqsToAdjust[$i]); // get post reqs of current task

				if(COUNT($postReqs) > 0) // if there are post reqs found
				{
					foreach($postReqs as $postReq)
					{
						$startDate = $postReq['TASKSTARTDATE'];
						$endDate = $rfc['NEWENDDATE'];

						if($endDate >= $startDate) //check if currTasks's end date will exceed the postreq's start date
						{
							if($postReq['TASKADJUSTEDSTARTDATE'] != null && $postReq['TASKADJUSTEDENDDATE'] != null)
								$taskDuration = $postReq['adjustedTaskDuration2'];
							elseif($postReq['TASKSTARTDATE'] != null && $postReq['TASKADJUSTEDENDDATE'] != null)
								$taskDuration = $postReq['adjustedTaskDuration1'];
							else
								$taskDuration = $postReq['initialTaskDuration'];

							if($postReq['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
								$currTaskEnd = $postReq['TASKENDDATE'];
							else
								$currTaskEnd = $postReq['TASKADJUSTEDENDDATE'];

							$new_start = date('Y-m-d', strtotime($endDate . ' +1 day')); // set start date to one day after enddate
							$new_end = date('Y-m-d', strtotime($new_start . ' +' . ($taskDuration-1) . ' day')); // set end date according to duration

							foreach($affectedTasks as $affectedTask)
							{
								if($affectedTask['id'] == $postReqsToAdjust[$i])
								{
									$new_start = date('Y-m-d', strtotime($affectedTask['newEndDate'] . ' +1 day'));
									$new_end = date('Y-m-d', strtotime($new_start . ' +' . ($taskDuration-1) . ' day'));
								}
							}

							$affectedTasks[] = array("id" => $postReq['TASKID'],
																		"taskTitle" => $postReq['TASKTITLE'],
																		"taskStatus" => $postReq['TASKSTATUS'],
								                    "startDate" => $postReq['TASKSTARTDATE'],
								                    "newStartDate" => $new_start,
								                    "endDate" => $currTaskEnd,
																		"newEndDate" => $new_end,
																		"responsible" => $postReq['FIRSTNAME'] . " " . $postReq['LASTNAME']);

						}
						array_push($postReqsToAdjust, $postReq['TASKID']); // save task to array for checking
					}
				}
				unset($postReqsToAdjust[$i]); // remove current task from array
				$i++; // increase counter
			}
		}

		// ARRAY CLEAN UP
		$index = 0;
		foreach($affectedTasks as $affectedTask1)
		{
			$doubleCount = 0;
			foreach($affectedTasks as $affectedTask2)
			{
				if($affectedTask1['id'] == $affectedTask2['id'])
				{
					$doubleCount++;
					if($doubleCount >= 2)
					{
						$affectedTasks[$index] = array("id" => null);
					}
				}
			}
			$index++;
		}

		echo json_encode($affectedTasks);
	}
}

class assessmentFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter
{
  public function readCell($column, $row, $worksheetName = '')
	{
    //  Read columns A to C only
    if (in_array($column,range('A','C')))
		{
        return true;
    }

    return false;
  }
}

class tasksFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter
{
  public function readCell($column, $row, $worksheetName = '')
	{
    //  Read columns A to L only
    if (in_array($column,range('A','L')))
		{
        return true;
    }

    return false;
  }
}

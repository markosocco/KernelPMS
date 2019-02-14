<?php
class model extends CI_Model
{
  public function __construct()
  {
    $this->load->database();
  }

// CHECK IF EMAIL AND PASSWORD EXIST AND MATCH IN DB
  public function checkDatabase($data)
  {
    // WITH ENCRYPTION
    $q = "SELECT * FROM USERS WHERE BINARY EMAIL = '" . $data['email'] . "' LIMIT 1";
    $query = $this->db->query($q);

    // ENCRYPTION START
    $hash = $query->row('PASSWORD');

    if ($query->num_rows() == 1)
    {
      if (password_verify($data['password'], $hash))
      {
        return true;
      }

      else
      {
        return false;
      }
    }
    // ENCRYPTION END

    else
    {
      return false;
    }
  }

// GET DATA OF USER, GIVEN THE EMAIL
  public function getUserData($data)
  {
    $condition = "users.EMAIL =" . "'" . $data['email'] . "'";
    $this->db->select('*');
    $this->db->from('users');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->row_array();
  }

  public function getEmail($id)
  {
    $condition = "USERID = " . $id;
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->row("EMAIL");
  }

// GET PROJECTID GIVEN TITLE AND DATES
  public function getProjectID($data)
  {
    $condition = "PROJECTTITLE =" . "'" . $data['PROJECTTITLE'] ."' AND PROJECTSTARTDATE = '" . $data['PROJECTSTARTDATE'] ."' AND '". $data['PROJECTENDDATE'] ."'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->row("PROJECTID");
  }

// SAVE NEW PROJECT TO DB; RETURNS PROJECT
  public function addProject($data)
  {
    $result = $this->db->insert('projects', $data);

    if ($result)
    {
      $this->db->select('*');
      $this->db->from('projects');
      $this->db->order_by('PROJECTID', 'DESC');
      $this->db->limit(1);

      $query = $this->db->get();
      return $query->row_array();
    }

    else
    {
      return false;
    }
  }

  // SAVE INDIVIDUAL TASK TO PROJECT
  public function addTasksToProject($data)
  {
    $result = $this->db->insert('tasks', $data);

    if ($result)
    {
      $this->db->select('*');
      $this->db->from('tasks');
      $this->db->order_by('TASKID', 'DESC');
      $this->db->limit(1);
      $query = $this->db->get();

      return $query->row('TASKID');
    }

    else
    {
      return false;
    }
  }

  // MARK TASK AS COMPLETE
  public function updateTaskDone($id, $data)
  {
    $this->db->where('TASKID', $id);
    $result = $this->db->update('tasks', $data);
  }

  // MARK PROJECT AS COMPLETE
  public function completeProject($id, $data)
  {
    $this->db->where('PROJECTID', $id);
    $result = $this->db->update('projects', $data);
  }

  // GETS PROJECT BY ID; RETURNS PROJECT
  public function getProjectByID($data)
  {
    $condition = "PROJECTID =" . $data;
    $this->db->select('*, DATEDIFF(PROJECTENDDATE, PROJECTSTARTDATE) +1 as "duration",
    DATEDIFF(PROJECTENDDATE, CURDATE())+1 as "remaining",
    DATEDIFF(PROJECTSTARTDATE, CURDATE())+1 as "launching",
    DATEDIFF(PROJECTACTUALENDDATE, PROJECTSTARTDATE)+1 as "actualDuration",
    DATEDIFF(CURDATE(), PROJECTENDDATE) as "delayed"');
    $this->db->from('projects');
    $this->db->join('users', 'users.USERID = projects.users_USERID');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->row_array();
  }

  // GETS ALL TASKS OF A PROJECT AND DEPARTMENT
  // public function getAllProjectTasks($data)
  // {
  //   // $condition = "projects_PROJECTID =" . $data;
  //   // $this->db->select('*');
  //   // $this->db->from('tasks');
  //   // $this->db->where($condition);
  //   // $query = $this->db->get();
  //   //
  //   // return $query->result_array();
  //
  //   $sql = "SELECT t.*, d.DEPARTMENTNAME as dName FROM tasks as t JOIN users as u on t.users_USERID = u.USERID JOIN departments as d on u.departments_DEPARTMENTID = d.DEPARTMENTID WHERE t.projects_PROJECTID = " . $data;
  //
	// 	$data = $this->db->query($sql);
  //   return $data->result_array();
  // }

  public function getChangeRequestsByProject($id)
  {
    $this->db->select('*');
    $this->db->from('changerequests');
    $this->db->join('tasks', 'changerequests.tasks_REQUESTEDTASK = tasks.TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'users.USERID = changerequests.users_REQUESTEDBY');
    $this->db->where("PROJECTID = '$id'");
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getChangeRequestsForApproval($filter, $id)
  {
    $this->db->select('*');
    $this->db->from('changerequests');
    $this->db->join('tasks', 'changerequests.tasks_REQUESTEDTASK = tasks.TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'users.USERID = changerequests.users_REQUESTEDBY');
    $this->db->where($filter . " && changerequests.users_REQUESTEDBY != '$id' && changeRequests.REQUESTSTATUS = 'Pending'");
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getChangeRequestsByUser($id)
  {
    $this->db->select('*');
    $this->db->from('changerequests');
    $this->db->join('tasks', 'changerequests.tasks_REQUESTEDTASK = tasks.TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'users.USERID = changerequests.users_APPROVEDBY');
    $this->db->where("changerequests.users_REQUESTEDBY = '$id' && (projects.PROJECTSTATUS = 'Ongoing' || projects.PROJECTSTATUS = 'Planning')");
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getChangeRequestbyID($requestID)
  {
    $condition = "REQUESTID = '$requestID'";
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('changerequests');
    $this->db->join('tasks', 'changerequests.tasks_REQUESTEDTASK = tasks.TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'users.USERID = changerequests.users_REQUESTEDBY');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->row_array();
  }

  public function getChangeRequestsByTask($id)
  {
    $this->db->select('*');
    $this->db->from('changerequests');
    $this->db->join('tasks', 'changerequests.tasks_REQUESTEDTASK = tasks.TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'users.USERID = changerequests.users_REQUESTEDBY');
    $this->db->where("tasks_REQUESTEDTASK = '$id'");
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllUsers()
  {
    $this->db->select('*, ' . $_SESSION['usertype_USERTYPEID'] . ' as "userType"');
    $this->db->from('users');
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllUsersByUserType($filter)
  {
    $condition = $filter;
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllUsersByDepartment($deptID)
  {
    $condition = "users.departments_DEPARTMENTID = '$deptID' && USERID != '1'";
    $this->db->select('*');
    $this->db->from('users');

    $this->db->where($condition);
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getProjectCount()
  {
    $condition = "projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && raci.ROLE != '0' && raci.ROLE != '5' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'";
    $this->db->select('users.*, count(distinct projects.PROJECTID) AS "projectCount"');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->group_by('users.USERID');
    $this->db->where($condition);
    return $this->db->get()->result_array();
  }

  public function getTaskCount()
  {
    $condition = "projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && raci.ROLE != '0' && raci.ROLE != '5' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'";
    $this->db->select('users.*,tasks.TASKSTATUS, (tasks.TASKENDDATE - tasks.TASKSTARTDATE) as "delay", count(distinct tasks.TASKID) AS "taskCount"');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);
    $this->db->group_by('users.USERID');

    return $this->db->get()->result_array();
  }

  public function getTaskCountByProjectByRole($id)
  {
    $condition = "projects.PROJECTID = '$id' && raci.ROLE = 1 && raci.STATUS = 'Current' && tasks.CATEGORY = '3'";
    $this->db->select('users.*, count(distinct tasks.TASKID) AS "taskCount"');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('users.USERID');
    $this->db->order_by('departments.DEPARTMENTNAME');

    return $this->db->get()->result_array();
  }

  public function getWorkloadProjects($userID)
  {
    $condition = "projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && raci.users_USERID = '$userID' && raci.ROLE != '0' && raci.ROLE != '5' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'";
    $this->db->select('projects.*');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->group_by('projects.PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getWorkloadTasks($userID, $projectID)
  {
    $condition = "projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && raci.users_USERID = '$userID' && projects.PROJECTID = '$projectID' && raci.ROLE != '0' && raci.ROLE != '5' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->order_by('tasks.TASKSTARTDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getWorkloadTasksUnique($userID, $projectID)
  {
    $condition = "projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && raci.users_USERID = '$userID' && projects.PROJECTID = '$projectID' && raci.ROLE != '0' && raci.ROLE != '5' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->order_by('tasks.TASKSTARTDATE');
    $this->db->group_by('tasks.TASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllOngoingAndDelayedProjects()
  {
    $condition = "(PROJECTSTARTDATE <= CURDATE() && PROJECTENDDATE >= CURDATE() || PROJECTENDDATE < CURDATE()) && PROJECTSTATUS = 'Ongoing'";
    $this->db->select('*, DATEDIFF(projects.PROJECTENDDATE, CURDATE()) as "datediff"');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->order_by('PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL ONGOING PROJECTS BASED ON PROJECTSTARTDATE AND PROJECTENDDATE
  public function getAllOngoingProjects()
  {
    $condition = "PROJECTSTARTDATE <= CURDATE() && PROJECTENDDATE >= CURDATE() && PROJECTSTATUS = 'Ongoing'";
    $this->db->select('*, DATEDIFF(projects.PROJECTENDDATE, CURDATE()) as "datediff"');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->order_by('PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL PLANNED PROJECTS BASED ON PROJECTSTARTDATE
  public function getAllPlannedProjects()
  {
    $condition = "PROJECTSTARTDATE > CURDATE() && PROJECTSTATUS = 'Planning'";
    $this->db->select('*, DATEDIFF(PROJECTSTARTDATE, CURDATE()) as "datediff"');
    $this->db->from('PROJECTS');
    $this->db->where($condition);
    $this->db->order_by('PROJECTSTARTDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL DELAYED PROJECTS BASED ON PROJECTENDDATE
  public function getAllDelayedProjects()
  {
    $condition = "PROJECTENDDATE < CURDATE() && PROJECTSTATUS = 'Ongoing'";
    $this->db->select('*, ABS(DATEDIFF(PROJECTENDDATE, CURDATE())) AS "datediff"');
    $this->db->from('PROJECTS');
    $this->db->where($condition);
    $this->db->order_by('PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL PARKED PROJECTS BASED ON PROJECTSTATUS
  public function getAllParkedProjects()
  {
    $condition = "PROJECTSTATUS = 'Parked'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->order_by('PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL DRAFTED PROJECTS BASED ON PROJECTSTATUS
  public function getAllDraftedProjects()
  {
    $condition = "PROJECTSTATUS = 'Drafted'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->order_by('PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL PLANNED PROJECTS BASED ON PROJECTSTARTDATE
  public function getAllCompletedProjects()
  {
    $condition = "PROJECTSTATUS = 'Complete'";
    $this->db->select('*, ((7-datediff(PROJECTACTUALENDDATE, curdate()))-1) as "datediff"');
    $this->db->from('PROJECTS');
    $this->db->where($condition);
    $this->db->order_by('PROJECTSTARTDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL ONGOING PROJECTS BASED ON PROJECTSTARTDATE AND PROJECTENDDATE OF LOGGED USER
  public function getAllOngoingProjectsByUser($userID)
  {
    // $condition = "((raci.users_USERID = '$userID' && raci.STATUS = 'Current') || projects.users_USERID = '$userID') && projects.PROJECTSTARTDATE <= CURDATE() && projects.PROJECTENDDATE > CURDATE() && projects.PROJECTSTATUS = 'Ongoing'";
    $condition = "((raci.users_USERID = '$userID' && raci.STATUS = 'Current') || projects.users_USERID = '$userID') && projects.PROJECTSTATUS = 'Ongoing'";
    $this->db->select('projects.*, DATEDIFF(projects.PROJECTENDDATE, CURDATE()) as "datediff"');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');

    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL ONGOING PROJECTS BASED ON PROJECTSTARTDATE AND PROJECTENDDATE OF LOGGED USER
  public function getAllPlannedProjectsByUser($userID)
  {
    $condition = "((raci.users_USERID = '$userID' && raci.STATUS = 'Current') || projects.users_USERID = '$userID') && projects.PROJECTSTATUS = 'Planning'";
    $this->db->select('projects.*, DATEDIFF(projects.PROJECTSTARTDATE, CURDATE()) as "datediff"');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTSTARTDATE');

    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL DELAYED PROJECTS BASED ON PROJECTENDDATE OF LOGGED END USER
  public function getAllDelayedProjectsByUser($userID)
  {
    $condition = "((raci.users_USERID = '$userID' && raci.STATUS = 'Current') || projects.users_USERID = '$userID') && PROJECTENDDATE < CURDATE() && PROJECTSTATUS = 'Ongoing'";
    $this->db->select('*, ABS(DATEDIFF(PROJECTENDDATE, CURDATE())) AS "datediff"');
    $this->db->from('PROJECTS');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL PARKED PROJECTS BASED ON PROJECTSTATUS
  public function getAllParkedProjectsByUser($userID)
  {
    $condition = "((raci.users_USERID = '$userID' && raci.STATUS = 'Current') || projects.users_USERID = '$userID') && PROJECTSTATUS = 'Parked'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL DRAFTED PROJECTS BASED ON PROJECTSTATUS
  public function getAllDraftedProjectsByUser($userID)
  {
    $condition = "PROJECTSTATUS = 'Drafted' && users_USERID = " . $userID;
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL PLANNED PROJECTS BASED ON PROJECTSTARTDATE
  public function getAllCompletedProjectsByUser($userID)
  {
    $condition = "PROJECTSTATUS = 'Complete' && users_USERID = " . $userID;
    $this->db->select('*, ((7-datediff(PROJECTACTUALENDDATE, curdate()))-1) as "datediff"');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

  // GET ALL PROJECT ARCHIVES
  public function getAllProjectArchives()
  {
    $condition = "PROJECTSTATUS = 'Archived'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('users', 'projects.users_USERID = users.USERID');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->result_array();
  }

  // GET ALL TEMPLATES
  public function getAllTemplates()
  {
    $this->db->select('templates.*, projects.PROJECTACTUALSTARTDATE, projects.PROJECTACTUALENDDATE, users.FIRSTNAME, users.LASTNAME');
    $this->db->from('templates');
    $this->db->join('projects', 'templates.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'projects.users_USERID = users.USERID');

    return $this->db->get()->result_array();
  }

  public function getOngoingProjectProgress()
	{
		$this->db->select('projects.*, COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
		ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
		$this->db->from('tasks');
		$this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
		$this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Ongoing" AND !(projectenddate < CURDATE())');
		$this->db->group_by('projects_PROJECTID');
		$this->db->order_by('PROJECTENDDATE');
		$this->db->limit('');

		return $this->db->get()->result_array();
	}

	public function getDelayedProjectProgress()
	{
		$this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
		ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
		$this->db->from('tasks');
		$this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
		$this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Ongoing" AND projectenddate < CURDATE()');
		$this->db->group_by('tasks.projects_PROJECTID');
		$this->db->order_by('projects.PROJECTENDDATE');

		return $this->db->get()->result_array();
	}

	public function getParkedProjectProgress()
	{
		$this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
		ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
		$this->db->from('tasks');
		$this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
		$this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Parked" AND !(projectenddate < CURDATE())');
		$this->db->group_by('tasks.projects_PROJECTID');
		$this->db->order_by('projects.PROJECTENDDATE');

		return $this->db->get()->result_array();
	}

  public function getOngoingProjectProgressByTeam($departmentID)
	{
		$this->db->select('raci.STATUS = "Current" && COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
		ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
		$this->db->from('tasks');
		$this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
		$this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
		$this->db->join('users', 'raci.users_USERID = users.USERID');
		$this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Ongoing"
		AND !(projectenddate < CURDATE()) AND raci.status = "Current" AND role = 1 AND users.departments_DEPARTMENTID = ' . $departmentID);
		$this->db->group_by('projects_PROJECTID');
		$this->db->order_by('PROJECTENDDATE');
		$this->db->limit('');

		return $this->db->get()->result_array();
	}

	public function getDelayedProjectProgressByTeam($departmentID)
	{
		$this->db->select('raci.STATUS = "Current" && COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
		ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
		$this->db->from('tasks');
		$this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
		$this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
		$this->db->join('users', 'raci.users_USERID = users.USERID');
		$this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Ongoing"
		AND projectenddate < CURDATE() AND raci.status = "Current" AND role = 1 AND users.departments_DEPARTMENTID = ' . $departmentID);
		$this->db->group_by('tasks.projects_PROJECTID');
		$this->db->order_by('projects.PROJECTENDDATE');

		return $this->db->get()->result_array();
	}

	public function getParkedProjectProgressByTeam($departmentID)
	{
		$this->db->select('raci.STATUS = "Current" && COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
		ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
		$this->db->from('tasks');
		$this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
		$this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
		$this->db->join('users', 'raci.users_USERID = users.USERID');
		$this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Parked"
		AND !(projectenddate < CURDATE()) AND raci.status = "Current" AND role = 1 AND users.departments_DEPARTMENTID = ' . $departmentID);
		$this->db->group_by('tasks.projects_PROJECTID');
		$this->db->order_by('projects.PROJECTENDDATE');

		return $this->db->get()->result_array();
	}

  public function getAllTasksByUser($id)
  {
    $condition = "raci.users_USERID = '" . $id . "' && raci.STATUS = 'Current' && projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '3' && raci.ROLE = '1'";
    $this->db->select('*, IF(projects.users_USERID = ' . $_SESSION['USERID'] . ', 1, 0) as "isProjectOwner", DATE_ADD(CURDATE(), INTERVAL +2 day) as "threshold" , DATEDIFF(CURDATE(), tasks.TASKSTARTDATE) as "delay",
    CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllTasksByUserByProject($USERID)
  {
    $condition = "raci.users_USERID = '" . $USERID . "' && raci.STATUS = 'Current' &&
    projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' &&
    tasks.CATEGORY = '3' && raci.ROLE = '1'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->group_by('projects.PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllTasksByUserByProjectToDo($USERID)
  {
    $condition = "raci.users_USERID = '" . $USERID . "' && raci.STATUS = 'Current' &&
    projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' &&
    tasks.CATEGORY = '3' && raci.ROLE = '1' && DATE_ADD(CURDATE(), INTERVAL +2 day) >= tasks.TASKENDDATE";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->group_by('projects.PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllACITasksByUser($id, $status)
  {
    $condition = "raci.users_USERID = '" . $id . "' && raci.STATUS = 'Current' && projects.PROJECTSTATUS != 'Complete' && projects.PROJECTSTATUS != 'Archived' && tasks.TASKSTATUS = '$status' && tasks.CATEGORY = '3' && raci.ROLE != '0' && raci.ROLE != '5'";
    $this->db->select('*, CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getUniqueACITasksByUser($id, $status)
  {
    $condition = "raci.users_USERID = '" . $id . "' && raci.STATUS = 'Current' && projects.PROJECTSTATUS != 'Complete' && projects.PROJECTSTATUS != 'Archived' && tasks.TASKSTATUS = '$status' && tasks.CATEGORY = '3' && raci.ROLE != '0' && raci.ROLE != '5'";
    $this->db->select('*, CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->group_by('tasks.TASKID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getUniqueACIOngoingProjectsByUserByProject($USERID, $status)
  {
    $condition = "raci.users_USERID = '" . $USERID . "' && raci.STATUS = 'Current' && projects.PROJECTSTATUS != 'Complete' && projects.PROJECTSTATUS != 'Archived' && tasks.TASKSTATUS = '$status' && tasks.CATEGORY = '3' && raci.ROLE != '0' && raci.ROLE != '5'";
    $this->db->select('*, CURDATE() as "currentDate"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getUniqueACIProjectsByUserByProject($USERID)
  {
    $condition = "raci.users_USERID = '" . $USERID . "' && raci.STATUS = 'Current' && projects.PROJECTSTATUS != 'Complete' && projects.PROJECTSTATUS != 'Archived' && tasks.CATEGORY = '3' && raci.ROLE != '0' && raci.ROLE != '5'";
    $this->db->select('*, CURDATE() as "currentDate"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllMainActivitiesByUser($USERID)
  {
    $condition = "raci.users_USERID = '" . $USERID . "' && raci.STATUS = 'Current' && projects.PROJECTSTATUS = 'Planning' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '1'";
    $this->db->select('*, CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllSubActivitiesByUser($USERID)
  {
    $condition = "raci.ROLE = '0' && raci.users_USERID = '" . $USERID . "' && raci.STATUS = 'Current' && projects.PROJECTSTATUS = 'Planning' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '2'";
    $this->db->select('*, CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllProjectsToEditByUser($USERID)
  {
    $condition = "raci.ROLE = '0' &&  raci.users_USERID = '" . $USERID . "' && raci.STATUS = 'Current' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '3'";
    $this->db->select('*, DATE_ADD(CURDATE(), INTERVAL +2 day) as "threshold", CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration",
    DATEDIFF(PROJECTSTARTDATE, CURDATE())+1 as "launching"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('tasks.TASKSTARTDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllActivitiesToEditByUser($id)
  {
    $condition = "raci.ROLE = '0' && raci.users_USERID = '" . $id . "' && raci.STATUS = 'Current' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '3'";
    $this->db->select('*, DATE_ADD(CURDATE(), INTERVAL +2 day) as "threshold", CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration",
    DATEDIFF(PROJECTSTARTDATE, CURDATE())+1 as "launching"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKSTARTDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function checkForDelegation($taskID)
  {
    $condition = "tasks_TASKID = " . $taskID . " && ROLE = '0' && STATUS = 'Current'";
    $this->db->select('*');
    $this->db->from('raci');
    $this->db->where($condition);

    return $this->db->get()->num_rows();
  }

// GET PRE-REQUISITE ID
  public function getDependenciesByProject($projectID)
  {
    $condition = "projects.PROJECTID = " . $projectID;
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('dependencies', 'tasks.TASKID = dependencies.tasks_POSTTASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getDependenciesByTaskID($taskID)
  {
    // $condition = "raci.STATUS = 'Current' && dependencies.tasks_POSTTASKID = '$taskID' && raci.ROLE = '1'";
    $condition = "raci.STATUS = 'Current' && (raci.ROLE = '1' || raci.ROLE = '0') && dependencies.tasks_POSTTASKID = '$taskID'";
    $this->db->select('*, CURDATE() as "currDate"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('dependencies', 'raci.tasks_TASKID = dependencies.PRETASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getPostDependenciesByTaskID($taskID)
  {
    // $condition = "raci.STATUS = 'Current' && dependencies.PRETASKID = '$taskID' && raci.ROLE = '1'";
    $condition = "raci.STATUS = 'Current' && (raci.ROLE = '1' || raci.ROLE = '0') && dependencies.PRETASKID = '$taskID'";
    $this->db->select('*, CURDATE() as "currDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('dependencies', 'raci.tasks_TASKID = dependencies.tasks_POSTTASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  // Returns 0 if all tasks under a parent task are complete
  public function checkTasksStatus($parentID)
  {
    $condition = "tasks_TASKPARENT = '$parentID' && tasks.TASKSTATUS != 'Complete'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->where($condition);

    return $this->db->get()->num_rows();
  }

  // Returns 0 if all tasks in a project are complete
  public function checkProjectStatus($projectID)
  {
    $condition = "projects_PROJECTID = '$projectID' && tasks.TASKSTATUS != 'Complete'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->where($condition);

    return $this->db->get()->num_rows();
  }

  public function getParentTask($taskID)
  {
    $condition = "TASKID = '$taskID'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

// RETURNS ARRAY OF DEPARTMENTS
  public function getAllDepartments()
  {
    $this->db->select('*');
    $this->db->from('departments');
    $this->db->order_by('DEPARTMENTNAME');

    $query = $this->db->get();

    return $query->result_array();
  }

// GETS ALL DEPARTMENTS INVOLVED IN A PROJECT
  public function getAllDepartmentsByProject($projectID)
  {
    $condition = "raci.STATUS = 'Current' && tasks.projects_PROJECTID = " . $projectID;
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('DEPARTMENTNAME');
    $this->db->order_by('DEPARTMENTNAME');

    return $this->db->get()->result_array();
  }

  public function getAllDepartmentsByProjectByRole($projectID)
  {
    $condition = "raci.STATUS = 'Current' && raci.ROLE = 1 && tasks.projects_PROJECTID = " . $projectID;
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('DEPARTMENTNAME');
    $this->db->order_by('DEPARTMENTNAME');

    return $this->db->get()->result_array();
  }

// GETS ALL THE USERS OF A DEPARTMENT THAT ARE INVOLVED IN A PROJECT EXCEPT THE SESSION USER
  public function getAllUsersByProjectByDepartment($projectID, $departmentID)
  {
    $condition = "raci.STATUS = 'Current' && projects_PROJECTID = " . $projectID . " AND departments_DEPARTMENTID = " . $departmentID;
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('users_USERID');

    return $this->db->get()->result_array();
  }

// GETS ALL USERS INVOLVED IN A PROJECT
  public function getAllUsersByProject($projectID)
  {
    $condition = "raci.STATUS = 'Current' && tasks.projects_PROJECTID = " . $projectID;
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('users_USERID');

    return $this->db->get()->result_array();
  }

  public function arrangeTasks($data, $id)
  {
    $condition = "TASKID = " . $id;
    $this->db->where($condition);
    $this->db->update('tasks', $data);
  }

  public function getTaskByID($id)
  {
    $condition = "TASKID = " . $id;
    $this->db->select('*, CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function getUserByID($id)
  {
    $condition = "USERID = " . $id;
    $this->db->select('*, CURDATE() as "currentDate"');
    $this->db->from('users');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function getDepartmentByID($id)
  {
    $condition = "DEPARTMENTID = " . $id;
    $this->db->select('*');
    $this->db->from('departments');
    $this->db->join('users', 'departments.users_DEPARTMENTHEAD = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  // GET DATA FOR THE GANTT CHART
  public function getAllProjectTasks($id)
  {
    $condition = "raci.STATUS = 'Current' && projects.PROJECTID = " . $id;
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllProjectTasksGroupByTaskID($id)
  {
    // initialTaskDuration
    $condition = "raci.STATUS = 'Current' && projects.PROJECTID = " . $id;
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('tasks.TASKID');
    $this->db->group_by('tasks.TASKSTARTDATE');

    return $this->db->get()->result_array();
  }

  public function getAllMainActivitiesByID($id)
  {
    $condition = "raci.STATUS = 'Current' && projects.PROJECTID = " . $id . " AND tasks.CATEGORY = '1'";
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('tasks.TASKID');

    return $this->db->get()->result_array();
  }

  public function getAllSubActivitiesByID($id)
  {
    $condition = "raci.STATUS = 'Current' && projects.PROJECTID = " . $id . " AND tasks.CATEGORY = 2";
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('tasks.TASKID');

    return $this->db->get()->result_array();
  }

  public function getAllTasksByIDRole0($id)
  {
    $condition = "raci.STATUS = 'Current' && raci.ROLE = '0' && projects.PROJECTID = " . $id . " AND tasks.CATEGORY = 3";
    $this->db->select('*, CURDATE() as "currDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKADJUSTEDENDDATE)) as "actualAdjusted",
    ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKENDDATE)) as "actualInitial",
    ABS(DATEDIFF(CURDATE(), TASKADJUSTEDENDDATE)) as "adjustedDelay",
    ABS(DATEDIFF(CURDATE(), TASKENDDATE)) as "initialDelay"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('tasks.TASKID');

    return $this->db->get()->result_array();
  }

// FOR TEAM GANTT CHART
  public function getAllProjectTasksByDepartment($projectID, $departmentID)
  {
    $condition = "raci.STATUS = 'Current' && projects_PROJECTID = " . $projectID . " AND departments_DEPARTMENTID = " . $departmentID;
    $this->db->select('*, CURDATE() as "currDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('TASKID');
    $this->db->order_by('TASKENDDATE');

    return $this->db->get()->result_array();
  }

  public function uploadDocument($data)
  {
    $this->db->insert('documents', $data);
    $id = $this->db->insert_id();

    return $id;
  }

  public function addToDocumentAcknowledgement($data)
  {
    $this->db->insert('documentAcknowledgement', $data);

    return true;
  }

  public function updateDocumentAcknowledgement($documentID, $userID, $currentDate)
  {
    $condition = "documents_DOCUMENTID = " . $documentID . " AND users_ACKNOWLEDGEDBY = " . $userID;
    $this->db->set('ACKNOWLEDGEDDATE', $currentDate);
    $this->db->where($condition);
    $this->db->update('documentAcknowledgement');

    return true;
  }

  // FOR PRESIDENT
  public function getAllDocuments()
  {
    $this->db->select('*');
    $this->db->from('documents');
    $this->db->join('projects', 'documents.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'documents.users_UPLOADEDBY = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');

    return $this->db->get()->result_array();
  }

  // DOCUMENTS IN SPECIFIC PROJECT
  public function getAllDocumentsByProject($projectID)
  {
    $condition = "documents.projects_PROJECTID = " . $projectID;
    $this->db->select('*');
    $this->db->from('documents');
    $this->db->join('projects', 'documents.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'documents.users_UPLOADEDBY = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  // DOCUMENTS IN SPECIFIC PROJECT THAT NEEDS ACKNOWLEDGEMENT
  public function getDocumentsForAcknowledgement($projectID, $userID)
  {
    $condition = "documents.projects_PROJECTID = " . $projectID . " AND users_ACKNOWLEDGEDBY = " . $userID . " AND users_UPLOADEDBY != " . $userID;
    $this->db->select('*');
    $this->db->from('documents');
    $this->db->join('projects', 'documents.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('documentAcknowledgement', 'documents.DOCUMENTID = documentAcknowledgement.documents_DOCUMENTID');
    $this->db->join('users', 'documents.users_UPLOADEDBY = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('documents.DOCUMENTID');

    return $this->db->get()->result_array();
  }

  // CHECKS FOR DUPLICATE WHEN UPLOADING
  public function getDocumentAcknowledgementID($userID, $documentID)
  {
    $condition = "users_ACKNOWLEDGEDBY = " . $userID . " AND documents_DOCUMENTID = " . $documentID;
    $this->db->select('*');
    $this->db->from('documentAcknowledgement');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  // DASHBOARD
  public function getAllDocumentAcknowledgementByUser($userID)
  {
    $condition = "ACKNOWLEDGEDDATE = '' && users_ACKNOWLEDGEDBY = " . $userID . " && users_UPLOADEDBY != '$userID'";
    $this->db->select('*');
    $this->db->from('documentAcknowledgement');
    $this->db->join('documents', 'documents_DOCUMENTID = DOCUMENTID');
    $this->db->join('users', 'users_UPLOADEDBY = USERID');
    $this->db->join('departments', 'departments_DEPARTMENTID = DEPARTMENTID');
    $this->db->join('projects', 'projectID = projects_PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function insertParentTask($data, $id)
  {
    $this->db->where('TASKID', $id);
    $this->db->update('tasks', $data);
  }

  public function addToRaci($data)
  {
    $this->db->insert('raci', $data);
    return true;
  }

  public function updateRACI($taskID, $role)
  {
    $condition = "tasks_TASKID = '$taskID' && ROLE = '$role'";
    $this->db->set('STATUS', 'Changed');
    $this->db->where($condition);
    $this->db->update('raci');
  }

  public function getAllResponsibleByProject($projectID)
  {
    $condition = "raci.STATUS = 'Current' && tasks.projects_PROJECTID = '$projectID' && ROLE = '1'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAlLAccountableByProject($projectID)
  {
    $condition = "raci.STATUS = 'Current' && tasks.projects_PROJECTID = '$projectID' && ROLE = '2'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllConsultedByProject($projectID)
  {
    $condition = "raci.STATUS = 'Current' && tasks.projects_PROJECTID = '$projectID' && ROLE = '3'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllInformedByProject($projectID)
  {
    $condition = "raci.STATUS = 'Current' && tasks.projects_PROJECTID = '$projectID' && ROLE = '4'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllDepartmentsWithoutExecutive()
  {
    $condition = "DEPARTMENTID != 1";
    $this->db->select('*');
    $this->db->from('departments');
    $this->db->where($condition);
    $this->db->order_by('DEPARTMENTNAME');

    return $this->db->get()->result_array();
  }

  public function addRFC($data)
  {
    $this->db->insert('changerequests', $data);
    return true;
  }

  public function updateRFC($requestID, $data)
  {
    $this->db->where('REQUESTID', $requestID);
    $result = $this->db->update('changerequests', $data);

    return true;
  }

  public function updateTaskDates($taskID, $data)
  {
    $this->db->where('TASKID', $taskID);
    $result = $this->db->update('tasks', $data);

    return true;
  }

  public function getProjectLogs($id)
  {
    $condition = "projects_PROJECTID = " . $id;
    $this->db->select('*');
    $this->db->from('logs');
    $this->db->where($condition);
    $this->db->order_by('TIMESTAMP','DESC');

    return $this->db->get()->result_array();
  }

  public function addToProjectLogs($data)
  {
    $this->db->insert('logs', $data);
    return true;
  }

  public function updateTaskStatus($currentDate)
  {
    $condition = "TASKSTARTDATE <= CURDATE() AND TASKSTATUS = 'Planning';";
    $this->db->set('TASKSTATUS', 'Ongoing');
    $this->db->set('TASKACTUALSTARTDATE', $currentDate);
    $this->db->where($condition);
    $this->db->update('tasks');
  }

  public function updateProjectStatus($currentDate)
  {
    $condition = "PROJECTSTARTDATE <= CURDATE() AND PROJECTSTATUS = 'Planning';";
    $this->db->set('PROJECTSTATUS', 'Ongoing');
    $this->db->set('PROJECTACTUALSTARTDATE', $currentDate);
    $this->db->where($condition);
    $this->db->update('projects');
  }

  public function parkProjectByID($projectID)
  {
    $condition = "PROJECTID = '$projectID';";
    $this->db->set('PROJECTSTATUS', 'Parked');
    $this->db->where($condition);
    $this->db->update('projects');
  }

  public function getTasks2DaysBeforeDeadline()
  {
    $condition = "raci.STATUS = 'Current' AND TASKSTATUS != 'Complete' AND DATEDIFF(TASKENDDATE, CURDATE()) <= 2
     AND CATEGORY = 3 AND ROLE = 1 AND raci.users_USERID = " . $_SESSION['USERID'];
    $this->db->select('*, DATEDIFF(TASKENDDATE, CURDATE()) AS "DATEDIFF", raci.users_USERID AS "TASKOWNER", projects.users_USERID AS "PROJECTOWNER"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);
    $this->db->group_by('tasks.TASKID');
    $this->db->order_by('tasks.TASKENDDATE','ASC');

    return $this->db->get()->result_array();
  }

  public function getAllNotificationsByUser()
  {
    $condition = "users_USERID = " . $_SESSION['USERID'];
    $this->db->select('notifications.*, datediff(curdate(), timestamp) as "DATEDIFF"');
    $this->db->from('notifications');
    $this->db->where($condition);
    $this->db->order_by('TIMESTAMP','DESC');

    return $this->db->get()->result_array();
  }

  public function checkNotification($currentDate, $details, $userID)
  {
    $condition = "datediff(TIMESTAMP, CURDATE()) = 0 AND DETAILS = '" . $details . "' AND users_USERID =" . $userID;
    $this->db->select('*, datediff(curdate(), timestamp) as "DATEDIFF"');
    $this->db->from('notifications');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function addNotification($data)
  {
    $this->db->insert('notifications', $data);
    $id = $this->db->insert_id();

    return $id;
  }

  public function updateNotification($notificationID, $notificationStatus)
  {
    $this->db->where('NOTIFICATIONID', $notificationID);
    $this->db->update('notifications', $notificationStatus);

    return true;
  }

  public function addToDependencies($data)
  {
    $this->db->insert('dependencies', $data);
    return true;
  }

  public function archiveProject($id, $data)
  {
    $this->db->where('PROJECTID', $id);
    $result = $this->db->update('projects', $data);

    return true;
  }

  public function parkProject($id, $data)
  {
    $this->db->where('PROJECTID', $id);
    $result = $this->db->update('projects', $data);

    return true;
  }

  public function templateProject($data)
  {
    $this->db->insert('templates', $data);
    return true;
  }

  public function getRaci($id)
  {
    $condition = "projects.PROJECTID = '" . $id . "' AND raci.STATUS = 'Current'";
    $this->db->select('raci.*, users.departments_DEPARTMENTID as uDept, tasks.CATEGORY as tCat');
    $this->db->from('raci');
    $this->db->join('tasks', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', ' raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getRACIbyTask($taskID){
    $condition = "tasks_TASKID = '" . $taskID . "' AND raci.STATUS = 'Current'";
    $this->db->select('*, CURDATE() as "currentDate"');
    $this->db->from('raci');
    $this->db->join('users', ' raci.users_USERID = users.USERID');
    $this->db->join('tasks', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllRACIbyTask($taskID){
    $condition = "tasks_TASKID = '" . $taskID . "'";
    $this->db->from('raci');
    $this->db->join('users', ' raci.users_USERID = users.USERID');
    $this->db->where($condition);
    $this->db->order_by("RACIID");

    return $this->db->get()->result_array();
  }

  public function getACIbyTask($taskID){
    $condition = "tasks_TASKID = " . $taskID . " AND raci.STATUS = 'Current' AND role != 1";
    $this->db->select('*');
    $this->db->from('raci');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getLatestWeeklyProgress(){
    $condition = "datediff(curdate(), DATE) = 7 && TYPE = 1";
    $this->db->select('*,  datediff(curdate(), DATE) as "datediff"');
    $this->db->from('assessmentProject');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getLatestAssessmentDepartment(){
    $condition = "datediff(curdate(), DATE) <= 7 && datediff(curdate(), DATE) > 0 ";
    $this->db->select('*,  datediff(curdate(), DATE) as "datediff"');
    $this->db->from('assessmentDepartment');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getLatestAssessmentEmployee(){
    $condition = "datediff(curdate(), DATE) <= 7 && datediff(curdate(), DATE) > 0 ";
    $this->db->select('*,  datediff(curdate(), DATE) as "datediff"');
    $this->db->from('assessmentEmployee');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function editProject($id, $data)
  {
    $this->db->where('PROJECTID', $id);
    $result = $this->db->update('projects', $data);
  }

  public function updateProjectStatusPlanning($id, $status)
  {
    $this->db->where('PROJECTID', $id);
    $result = $this->db->update('projects', $status);
  }

  public function editProjectTask($id, $data)
  {
    $this->db->where('TASKID', $id);
    $result = $this->db->update('tasks', $data);

    return $id;
  }

  public function updateRaciStatus($id, $data)
  {
    $this->db->where('tasks_TASKID', $id);
    $result = $this->db->update('raci', $data);
  }

  public function compute_completeness_employee($userID){
    $condition = "PROJECTSTATUS = 'Ongoing' && CATEGORY = 3 && raci.status = 'Current' && role = 1 && raci.users_USERID = " . $userID;
		$this->db->select('raci.USERS_USERID as USERID, COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
		ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness"');
		$this->db->from('tasks');
		$this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('projects', 'projects_PROJECTID = PROJECTID');
		$this->db->where($condition);

		return $this->db->get()->row_array();
  }

  public function compute_timeliness_employee($userID){
    $condition = "CATEGORY = 3 && TASKACTUALSTARTDATE != '' && (TASKSTATUS = 'Ongoing' || TASKSTATUS = 'Complete') && raci.status = 'Current' && role = 1 &&
      PROJECTSTATUS = 'Ongoing' && raci.users_USERID = " . $userID;
    $this->db->select('raci.USERS_USERID as USERID, COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
      ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE && TASKSTATUS = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) +
      ROUND((COUNT(IF(TASKENDDATE >= CURDATE() && TASKSTATUS = "Ongoing", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('projects', 'projects_PROJECTID = PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_completeness_employeeByProject($userID, $projectID)
  {
    $condition = "CATEGORY = 3 && raci.status = 'Current' && role = 1 && users_USERID = " . $userID . " && projects_PROJECTID = " . $projectID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_timeliness_employeeByProject($userID, $projectID)
  {
    $condition = "CATEGORY = 3 && TASKACTUALSTARTDATE != '' && (TASKSTATUS = 'Ongoing' || TASKSTATUS = 'Complete') && raci.status = 'Current' && role = 1 && users_USERID = " . $userID . " && projects_PROJECTID = " . $projectID;
    $this->db->select('projects_PROJECTID, (100 / COUNT(taskstatus)),
      ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE && TASKSTATUS = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) +
      ROUND((COUNT(IF(TASKENDDATE <= CURDATE() && TASKSTATUS = "Ongoing", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_completeness_department($deptID)
  {
    $condition = "CATEGORY = 3 && raci.status = 'Current' && role = 1 && departments_DEPARTMENTID = " . $deptID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
      ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('users', 'users_USERID = USERID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_timeliness_department($deptID)
  {
    $condition = "CATEGORY = 3 && TASKACTUALSTARTDATE != '' && (TASKSTATUS = 'Ongoing' || TASKSTATUS = 'Complete') && projects.PROJECTSTATUS = 'Ongoing' && raci.status = 'Current' && role = 1 && departments_DEPARTMENTID = " . $deptID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
      ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE && TASKSTATUS = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) +
      ROUND((COUNT(IF(TASKENDDATE >= CURDATE() && TASKSTATUS = "Ongoing", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('users', 'users_USERID = USERID');
    $this->db->join('projects', 'projects_PROJECTID = PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_timeliness_employeesByProject($projectID)
  {
    $condition = "CATEGORY = 3 && TASKACTUALSTARTDATE != '' && (TASKSTATUS = 'Ongoing' || TASKSTATUS = 'Complete') && raci.status = 'Current' && role = 1 && projects_PROJECTID = " . $projectID;
    $this->db->select('users.USERID, COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
      ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE && TASKSTATUS = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) +
      ROUND((COUNT(IF(TASKENDDATE >= CURDATE() && TASKSTATUS = "Ongoing", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('users', 'users_USERID = USERID');
    $this->db->group_by("USERID");
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function compute_completeness_departmentByProject($projectID)
  {
    $condition = "CATEGORY = 3 && raci.status = 'Current' && role = 1 && projects_PROJECTID = " . $projectID;
    $this->db->select('departments.*, COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
      ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('users', 'users_USERID = USERID');
    $this->db->join('departments', 'users.departments_departmentid = departments.departmentid');
    $this->db->group_by('departments_DEPARTMENTID');
    $this->db->where($condition);
    $this->db->order_by('departments.DEPARTMENTNAME');

    return $this->db->get()->result_array();
  }

  public function compute_timeliness_departmentByProject($projectID)
  {
    $condition = "CATEGORY = 3 && TASKACTUALSTARTDATE != '' && (TASKSTATUS = 'Ongoing' || TASKSTATUS = 'Complete') && raci.status = 'Current' && role = 1 && projects_PROJECTID = " . $projectID;
    $this->db->select('departments.*, COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
      ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE && TASKSTATUS = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) +
      ROUND((COUNT(IF(TASKENDDATE >= CURDATE() && TASKSTATUS = "Ongoing", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness",
      ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('users', 'users_USERID = USERID');
    $this->db->join('departments', 'users.departments_departmentid = departments.departmentid');
    $this->db->group_by('departments_DEPARTMENTID');
    $this->db->where($condition);
    $this->db->order_by('departments.DEPARTMENTNAME');

    return $this->db->get()->result_array();
  }

  public function compute_completeness_project($projectID)
  {
    $condition = "CATEGORY = 3 AND tasks.projects_PROJECTID = " . $projectID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)) AS "weight",
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_timeliness_project($projectID)
  {
    $condition = "CATEGORY = 3 AND (TASKSTATUS = 'Ongoing' || TASKSTATUS = 'Complete') AND tasks.projects_PROJECTID = " . $projectID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
      ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE && TASKSTATUS = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) +
      ROUND((COUNT(IF(TASKENDDATE >= CURDATE() && TASKSTATUS = "Ongoing", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_completeness_allProjects()
  {
    $condition = "CATEGORY = 3";
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function compute_timeliness_allProjects()
  {
    $condition = "CATEGORY = 3 AND (TASKSTATUS = 'Ongoing' || TASKSTATUS = 'Complete') AND TASKACTUALSTARTDATE != ''";
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
      ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE && TASKSTATUS = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) +
      ROUND((COUNT(IF(TASKENDDATE >= CURDATE() && TASKSTATUS = "Ongoing", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function compute_employeePerformance_perProject($userID){
    $condition = "YEAR(CURDATE()) && CATEGORY = 3 && TASKACTUALSTARTDATE != ''  && raci.status = 'Current' && role = 1 && users_USERID = " . $userID;
    $this->db->select('projects_PROJECTID as "PROJECTID", COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness",
    ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE && TASKSTATUS = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) +
    ROUND((COUNT(IF(TASKENDDATE >= CURDATE() && TASKSTATUS = "Ongoing", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->where($condition);
    $this->db->group_by('projects_projectid');

    return $this->db->get()->result_array();
  }

  public function compute_employeePerformance_byDepartments($deptID){

    $condition = "CATEGORY = 3 && TASKACTUALSTARTDATE != ''  && projects.PROJECTSTATUS = 'Ongoing' && raci.status = 'Current' && role = 1 && departments_departmentid = " . $deptID;
    $this->db->select('raci.users_USERID,
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness",
    ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE && TASKSTATUS = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) +
    ROUND((COUNT(IF(TASKENDDATE >= CURDATE() && TASKSTATUS = "Ongoing", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('users', 'users_USERID = USERID');
    $this->db->join('projects', 'projects_PROJECTID = PROJECTID');
    $this->db->where($condition);
    $this->db->group_by('raci.users_USERID');

    return $this->db->get()->result_array();
  }

  public function compute_daily_projectPerformance(){

    $condition = "CATEGORY = 3 AND TASKACTUALSTARTDATE != ''
        AND projects.PROJECTSTATUS = 'Ongoing'";
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness",
    ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE && TASKSTATUS = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) +
    ROUND((COUNT(IF(TASKENDDATE >= CURDATE() && TASKSTATUS = "Ongoing", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);
    $this->db->group_by('projects_PROJECTID');

    return $this->db->get()->result_array();
  }

  public function compute_daily_departmentPerformance(){

    $condition = "CATEGORY = 3 AND TASKACTUALSTARTDATE != '' AND raci.status = 'Current' AND role = 1 AND projects.PROJECTSTATUS = 'Ongoing'";
    $this->db->select('COUNT(TASKID), departments_DEPARTMENTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness",
    ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE && TASKSTATUS = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) +
    ROUND((COUNT(IF(TASKENDDATE >= CURDATE() && TASKSTATUS = "Ongoing", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('users', 'users_USERID = USERID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);
    $this->db->group_by('departments_DEPARTMENTID');

    return $this->db->get()->result_array();
  }

  public function compute_daily_employeePerformance(){

    $condition = "CATEGORY = 3 AND TASKACTUALSTARTDATE != '' AND raci.status = 'Current' AND role = 1 AND projects.PROJECTSTATUS = 'Ongoing'";
    $this->db->select('COUNT(TASKID), raci.users_USERID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness",
    ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE && TASKSTATUS = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) +
    ROUND((COUNT(IF(TASKENDDATE >= CURDATE() && TASKSTATUS = "Ongoing", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);
    $this->db->group_by('raci.users_USERID');

    return $this->db->get()->result_array();
  }

  public function compute_departmentPerformance(){

    $condition = "YEAR(CURDATE()) AND departments_DEPARTMENTID != 1";
    $this->db->select('departments_DEPARTMENTID, DEPARTMENTNAME,
    ROUND(AVG(timeliness), 2) as "TIMELINESSAVERAGE",
    ROUND(AVG(completeness), 2) as "COMPLETENESSAVERAGE",
    ROUND((AVG(timeliness) + AVG(completeness))/2 ,2) as "AVERAGE"');
    $this->db->from('assessmentdepartment');
    $this->db->join('departments', 'departments_DEPARTMENTID = DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('departments_DEPARTMENTID');
    $this->db->order_by('DEPARTMENTNAME');

    return $this->db->get()->result_array();
  }

  // public function compute_completeness_projectByUser()
  // {
  //   $condition = "CATEGORY = 3 AND projects.PROJECTSTATUS = 'Ongoing' AND projects.users_USERID = " . $_SESSION['USERID'];
  //   $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
  //   ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness"');
  //   $this->db->from('tasks');
  //   $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
  //   $this->db->group_by('projects_PROJECTID');
  //   $this->db->where($condition);
  //
  //   return $this->db->get()->result_array();
  // }
  //
  // public function compute_timeliness_projectByUser()
  // {
  //   $condition = "CATEGORY = 3 AND TASKACTUALSTARTDATE != '' AND projects.PROJECTSTATUS = 'Ongoing' AND projects.users_USERID = " . $_SESSION['USERID'];
  //   $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
  //   ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE, 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
  //   $this->db->from('tasks');
  //   $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
  //   $this->db->group_by('projects_PROJECTID');
  //   $this->db->where($condition);
  //
  //   return $this->db->get()->result_array();
  // }

  public function checkProjectAssessment()
  {
    $condition = "datediff(DATE, CURDATE()) = 0 and TYPE = 1";
    $this->db->select('*');
    $this->db->from('assessmentProject');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function checkDepartmentAssessment()
  {
    $condition = "datediff(DATE, CURDATE()) = 0";
    $this->db->select('*');
    $this->db->from('assessmentDepartment');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function checkEmployeeAssessment()
  {
    $condition = "datediff(DATE, CURDATE()) = 0";
    $this->db->select('*');
    $this->db->from('assessmentEmployee');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function checkMainCompleteness($projectID){

    $condition = "datediff(DATE, CURDATE()) = 0 and TYPE = 2 AND projects_PROJECTID = " . $projectID;
    $this->db->select('*');
    $this->db->from('assessmentProject');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function addProjectAssessment($data){

    $this->db->insert('assessmentProject', $data);

    return true;
  }

  public function addDepartmentAssessment($data){

    $this->db->insert('assessmentDepartment', $data);

    return true;
  }

  public function addEmployeeAssessment($data){

    $this->db->insert('assessmentEmployee', $data);

    return true;
  }

  public function getAllProjects()
  {
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.taskid = raci.tasks_taskid');
    $this->db->join('users', 'raci.users_userid = users.userid');
    $this->db->join('departments', 'users.departments_departmentid = departments.departmentid');
    $this->db->group_by("PROJECTID");

    return $this->db->get()->result_array();
  }

  public function getTeamByProject($id)
  {
    $condition = "raci.STATUS = 'Current' && role = 1 && tasks.projects_PROJECTID = '$id'";
    $this->db->select('users.*, tasks.*, departments.DEPARTMENTNAME');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.taskid = raci.tasks_taskid');
    $this->db->join('users', 'raci.users_userid = users.userid');
    $this->db->join('departments', 'users.departments_departmentid = departments.departmentid');
    $this->db->where($condition);
    $this->db->group_by("USERID");
    $this->db->order_by("departments.DEPARTMENTNAME");

    return $this->db->get()->result_array();
  }

  public function getAllProjectsByUser($id)
  {
    $condition = "users.USERID = " . $id . " && raci.STATUS = 'Current' && raci.role = 1 && tasks.CATEGORY = '3' && projects.PROJECTSTATUS != 'Complete' && projects.PROJECTSTATUS != 'Archived' && projects.PROJECTSTATUS != 'Parked' && projects.PROJECTSTATUS != 'Drafted'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.taskid = raci.tasks_taskid');
    $this->db->join('users', 'raci.users_userid = users.userid');
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTSTARTDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllTasksForAllOngoingProjects($id)
  {
    $condition = "users.USERID = " . $id . " && raci.STATUS = 'Current' && raci.ROLE = 1 && tasks.CATEGORY = '3' && projects.PROJECTSTATUS != 'Complete' && projects.PROJECTSTATUS != 'Archived'";
    $this->db->select('*, CURDATE() as "currDate"');
    $this->db->from('projects');
    $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.taskid = raci.tasks_taskid');
    $this->db->join('users', 'raci.users_userid = users.userid');
    $this->db->order_by('projects.PROJECTID, tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllTasksToMonitor()
  {
    $condition = "raci.STATUS = 'Current' && raci.ROLE = 1 && tasks.CATEGORY = '3' && projects.PROJECTSTATUS != 'Complete' && projects.PROJECTSTATUS != 'Archived'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.taskid = raci.tasks_taskid');
    $this->db->join('users', 'raci.users_userid = users.userid');
    $this->db->order_by('projects.PROJECTID, tasks.TASKENDDATE');
    $this->db->group_by('tasks.TASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllProjectsByDepartment($departmentID)
  {
    $condition = "YEAR(CURDATE()) && departments_DEPARTMENTID = " . $departmentID;
    $this->db->select('tasks.*, COUNT(DISTINCT(projects.PROJECTID)) as "PROJECTCOUNT"');
    $this->db->from('projects');
    $this->db->join('users', 'projects.users_userid = users.userid');
    $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.taskid = raci.tasks_taskid');
    $this->db->join('departments', 'users.departments_departmentid = departments.departmentid');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

// MONITOR PROJECTS

  public function getAllProjectsOwnedByUser($userID){
    $condition = "PROJECTSTATUS != 'Planning' && users_USERID = " . $userID;
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->order_by('projects.PROJECTTITLE');
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllCompletedOwnedProjectsByUser($userID)
  {
    $condition = "PROJECTSTATUS = 'Complete' && users_USERID = " . $userID;
    $this->db->select('*, ((7-datediff(PROJECTACTUALENDDATE, curdate()))-1) as "datediff"');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllPlannedOwnedProjectsByUser($userID)
  {
    $condition = "projects.users_USERID = '$userID' && projects.PROJECTSTATUS = 'Planning'";
    $this->db->select('projects.*, DATEDIFF(projects.PROJECTSTARTDATE, CURDATE()) as "datediff"');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTSTARTDATE');

    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllOngoingOwnedProjectsByUser($userID)
  {
    $condition = "projects.users_USERID = '$userID' && projects.PROJECTSTARTDATE <= CURDATE() && projects.PROJECTENDDATE > CURDATE() && projects.PROJECTSTATUS = 'Ongoing'";
    $this->db->select('projects.*, DATEDIFF(projects.PROJECTENDDATE, CURDATE()) as "datediff"');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');

    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllDelayedOwnedProjectsByUser($userID)
  {
    $condition = "projects.users_USERID = '$userID' && PROJECTENDDATE < CURDATE() && PROJECTSTATUS = 'Ongoing'";
    $this->db->select('*, ABS(DATEDIFF(PROJECTENDDATE, CURDATE())) AS "datediff"');
    $this->db->from('PROJECTS');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllACI()
  {
    $condition = "raci.STATUS = 'Current' && tasks.CATEGORY = '3'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllTasksByProject($projectID)
  {
    $condition = "raci.STATUS = 'Current' && projects_PROJECTID = " . $projectID . " && tasks.CATEGORY = '3'";
    $this->db->select('*, CURDATE() as "currDate",
    ABS(DATEDIFF(CURDATE(), TASKADJUSTEDENDDATE)) as "adjustedDelay",
    ABS(DATEDIFF(CURDATE(), TASKENDDATE)) as "initialDelay"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('tasks.TASKID');
    $this->db->order_by('TASKENDDATE');

    return $this->db->get()->result_array();
  }

  public function getAllUsersBySupervisor($id)
  {
    $condition = "users_SUPERVISORS = " . $id;
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllDepartmentTasksByProject($projectID, $departmentID)
  {
    $condition = "raci.STATUS = 'Current' && projects_PROJECTID = " . $projectID . " && departments_DEPARTMENTID = " . $departmentID . " && tasks.CATEGORY = '3'";
    $this->db->select('*, CURDATE() as "currDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('TASKID');
    $this->db->order_by('TASKENDDATE');

    return $this->db->get()->result_array();
  }

  public function getAllTasksByIDRole1($id)
  {
    $condition = "raci.STATUS = 'Current' && raci.ROLE = '1' && projects.PROJECTID = " . $id . " AND tasks.CATEGORY = 3";
    $this->db->select('*, CURDATE() as "currDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKADJUSTEDENDDATE)) as "actualAdjusted",
    ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKENDDATE)) as "actualInitial",
    ABS(DATEDIFF(CURDATE(), TASKADJUSTEDENDDATE)) as "adjustedDelay",
    ABS(DATEDIFF(CURDATE(), TASKENDDATE)) as "initialDelay"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('tasks.TASKID');

    return $this->db->get()->result_array();
  }

  public function checkIfTemplate($id)
  {
    $condition = "projects_PROJECTID = " . $id;
    $this->db->select('*');
    $this->db->from('templates');
    $this->db->where($condition);
    $this->db->limit(1);
    $query = $this->db->get();

    if ($query->num_rows() == 1)
    {
      return true;
    }

    else
    {
      return false;
    }
  }

  public function samePassword($oldPass)
  {
    $condition = "USERID = " . $_SESSION['USERID'];
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition);
    $query = $this->db->get();
    $currPassword = $query->row("PASSWORD");

    if (password_verify($oldPass, $currPassword))
    {
      return true;
    }

    else
    {
      return false;
    }
  }

  public function updatePassword($data)
  {
    $this->db->where('USERID', $_SESSION['USERID']);
    $this->db->update('users', $data);

    return true;
  }

  public function getSubActivityTaskID($id)
  {
    $condition = "raci.STATUS = 'Current' && projects.PROJECTID = " . $id . " AND tasks.CATEGORY = 2";
    $this->db->select('TASKID');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function checkSamePassword($pass)
  {
    $condition = "USERID = " . $_SESSION['USERID'];
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition);
    $query = $this->db->get();
    $currPassword = $query->row("PASSWORD");

    if (password_verify($pass, $currPassword))
    {
      return true;
    }

    else
    {
      return false;
    }
  }

  public function importTaskToProject($data)
  {
    $result = $this->db->insert('tasks', $data);

    if ($result)
    {
      $this->db->select('*');
      $this->db->from('tasks');
      $this->db->order_by('TASKID', 'DESC');
      $this->db->limit(1);
      $query = $this->db->get();

      return $query->row_array();
    }

    else
    {
      return false;
    }
  }

  public function getUserByName($data)
  {
    $condition = "CONCAT(FIRSTNAME, ' ', LASTNAME) = '" . $data . "'";
    $this->db->select("*");
    $this->db->from('users');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->row_array();
  }

  public function getAllTasksForImportDependency($id)
  {
    $condition = "raci.STATUS = 'Current' && raci.ROLE = '1' && projects.PROJECTID = " . $id;
    $this->db->select('*, CURDATE() as "currDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKADJUSTEDENDDATE)) as "actualAdjusted",
    ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKENDDATE)) as "actualInitial",
    ABS(DATEDIFF(CURDATE(), TASKADJUSTEDENDDATE)) as "adjustedDelay",
    ABS(DATEDIFF(CURDATE(), TASKENDDATE)) as "initialDelay"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

    // REPORTS

    public function getAllOngoingPOProjects($userID)
    {
      $condition = "projects.users_USERID = '$userID' && projects.PROJECTSTATUS = 'Ongoing'";
      $this->db->select('projects.*, DATEDIFF(projects.PROJECTENDDATE, CURDATE()) as "datediff"');
      $this->db->from('projects');
      $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
      $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
      $this->db->where($condition);
      $this->db->group_by('projects.PROJECTID');
      $this->db->order_by('projects.PROJECTENDDATE');

      $query = $this->db->get();

      return $query->result_array();
    }

   public function getOngoingTasks($projectID, $interval)
   {
     $condition = "PROJECTID = '$projectID' && raci.role = '1' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'
                   && (tasks.TASKENDDATE BETWEEN DATE_SUB(CURDATE(), INTERVAL $interval DAY) AND CURDATE()
                   || tasks.TASKADJUSTEDENDDATE BETWEEN DATE_SUB(CURDATE(), INTERVAL $interval DAY) AND CURDATE()
                   || tasks.TASKSTARTDATE BETWEEN DATE_SUB(CURDATE(), INTERVAL $interval DAY) AND CURDATE()
                   || (tasks.TASKENDDATE <= CURDATE() && tasks.TASKSTATUS = 'Ongoing')
                   || (tasks.TASKADJUSTEDENDDATE <= CURDATE() && tasks.TASKSTATUS = 'Ongoing'))";
     $this->db->select('*');
     $this->db->from('projects');
     $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
     $this->db->join('raci', 'tasks.taskid = raci.tasks_taskid');
     $this->db->join('users', 'raci.users_userid = users.userid');
     $this->db->order_by('tasks.TASKENDDATE');
     $this->db->where($condition);

     return $this->db->get()->result_array();
   }

   public function getAccomplishedLast($projectID, $interval)
   {
     $condition = "PROJECTID = '$projectID' && raci.role = '1' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'
                   && (tasks.TASKENDDATE BETWEEN DATE_SUB(CURDATE(), INTERVAL $interval DAY) AND CURDATE()
                   || tasks.TASKADJUSTEDENDDATE BETWEEN DATE_SUB(CURDATE(), INTERVAL $interval DAY) AND CURDATE()
                   || tasks.TASKSTARTDATE BETWEEN DATE_SUB(CURDATE(), INTERVAL $interval DAY) AND CURDATE()
                   || (tasks.TASKENDDATE <= CURDATE() && tasks.TASKSTATUS = 'Ongoing')
                   || (tasks.TASKADJUSTEDENDDATE <= CURDATE() && tasks.TASKSTATUS = 'Ongoing'))
                   && tasks.TASKSTATUS = 'Complete'";
     $this->db->select('*');
     $this->db->from('projects');
     $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
     $this->db->join('raci', 'tasks.taskid = raci.tasks_taskid');
     $this->db->join('users', 'raci.users_userid = users.userid');
     $this->db->order_by('tasks.TASKENDDATE');
     $this->db->where($condition);

     return $this->db->get()->result_array();
   }

   public function getProblemTasks($projectID, $interval)
   {
     $condition = "PROJECTID = '$projectID' && raci.role = '1' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'
                   && (tasks.TASKACTUALENDDATE > tasks.TASKADJUSTEDENDDATE || tasks.TASKACTUALENDDATE > tasks.TASKENDDATE
                   || tasks.TASKENDDATE < CURDATE() || tasks.TASKADJUSTEDENDDATE < CURDATE())
                   && tasks.TASKSTATUS = 'Ongoing'
                   && (tasks.TASKENDDATE BETWEEN DATE_SUB(CURDATE(), INTERVAL $interval DAY) AND CURDATE()
                   || tasks.TASKADJUSTEDENDDATE BETWEEN DATE_SUB(CURDATE(), INTERVAL $interval DAY) AND CURDATE()
                   || tasks.TASKSTARTDATE BETWEEN DATE_SUB(CURDATE(), INTERVAL $interval DAY) AND CURDATE()
                   || (tasks.TASKENDDATE <= CURDATE() && tasks.TASKSTATUS = 'Ongoing')
                   || (tasks.TASKADJUSTEDENDDATE <= CURDATE()))";
     $this->db->select('*, ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKENDDATE)) as "completeOrigDelay",
                       ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKADJUSTEDENDDATE)) as "completeAdjustedDelay",
                       ABS(DATEDIFF(tasks.TASKADJUSTEDENDDATE, CURDATE())) as "ongoingAdjustedDelay",
                       ABS(DATEDIFF(tasks.TASKENDDATE, CURDATE())) as "ongoingOrigDelay"');
     $this->db->from('projects');
     $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
     $this->db->join('raci', 'tasks.taskid = raci.tasks_taskid');
     $this->db->join('users', 'raci.users_userid = users.userid');
     $this->db->order_by('tasks.TASKENDDATE');
     $this->db->where($condition);

     return $this->db->get()->result_array();
   }

   public function getPlannedNext($projectID, $interval)
   {
     $condition = "PROJECTID = '$projectID' && raci.role = '1' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'
                   && tasks.TASKSTARTDATE BETWEEN DATE_ADD(CURDATE(), INTERVAL $interval DAY) AND CURDATE()";
     $this->db->select('*');
     $this->db->from('projects');
     $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
     $this->db->join('raci', 'tasks.taskid = raci.tasks_taskid');
     $this->db->join('users', 'raci.users_userid = users.userid');
     $this->db->order_by('tasks.TASKENDDATE');
     $this->db->where($condition);

     return $this->db->get()->result_array();
   }

   public function getPendingRFCNext($projectID, $interval)
   {
     $condition = "PROJECTID = '$projectID' && changeRequests.REQUESTSTATUS = 'Pending'
                  && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '3'";
     $this->db->select('*');
     $this->db->from('changerequests');
     $this->db->join('tasks', 'changerequests.tasks_REQUESTEDTASK = tasks.TASKID');
     $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
     $this->db->join('users', 'users.USERID = changerequests.users_REQUESTEDBY');
     $this->db->where($condition);
     $query = $this->db->get();

     return $query->result_array();
   }

   public function getPendingRaci($projectID)
   {
     $condition = "projects.PROJECTID = '" . $projectID . "' AND raci.STATUS = 'Current'
                   && raci.ROLE = '0' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '3'";
     $this->db->select('*');
     $this->db->from('raci');
     $this->db->join('tasks', 'raci.tasks_TASKID = tasks.TASKID');
     $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
     $this->db->join('users', ' raci.users_USERID = users.USERID');
     $this->db->where($condition);

     return $this->db->get()->result_array();
   }

   public function getAccomplishedTasks($projectID, $subActivityID, $interval)
   {
     $condition = "PROJECTID = '$projectID' && raci.role = '1' && raci.STATUS = 'Current' && tasks.tasks_TASKPARENT = '$subActivityID'
                   && tasks.TASKACTUALENDDATE BETWEEN DATE_SUB(CURDATE(), INTERVAL $interval DAY) AND CURDATE()
                   && tasks.TASKSTATUS = 'Complete'";
     $this->db->select('*, ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKENDDATE)) as "completeOrig",
                       ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKADJUSTEDENDDATE)) as "completeAdjusted"');
     $this->db->from('projects');
     $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
     $this->db->join('raci', 'tasks.taskid = raci.tasks_taskid');
     $this->db->join('users', 'raci.users_userid = users.userid');
     $this->db->where($condition);
     $this->db->order_by('tasks.TASKACTUALENDDATE');

     return $this->db->get()->result_array();
   }

   public function getAllEarlyTasksByIDRole1($id)
   {
     $condition = "raci.STATUS = 'Current' && raci.ROLE = '1' && projects.PROJECTID = " . $id . " && tasks.CATEGORY = 3
                   && tasks.TASKSTATUS = 'Complete' && (tasks.TASKACTUALENDDATE < tasks.TASKENDDATE || tasks.TASKACTUALENDDATE < tasks.TASKADJUSTEDENDDATE)";
     $this->db->select('*, CURDATE() as "currDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
     DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
     DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
     ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKADJUSTEDENDDATE)) as "actualAdjusted",
     ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKENDDATE)) as "actualInitial",
     ABS(DATEDIFF(CURDATE(), TASKADJUSTEDENDDATE)) as "adjustedDelay",
     ABS(DATEDIFF(CURDATE(), TASKENDDATE)) as "initialDelay"');
     $this->db->from('tasks');
     $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
     $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
     $this->db->join('users', 'raci.users_USERID = users.USERID');
     $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
     $this->db->where($condition);
     $this->db->group_by('tasks.TASKID');

     return $this->db->get()->result_array();
   }

   public function getAllDelayedTasksByIDRole1($id)
   {
     $condition = "raci.STATUS = 'Current' && raci.ROLE = '1' && projects.PROJECTID = " . $id . " && tasks.CATEGORY = 3
                   && tasks.TASKSTATUS = 'Complete' && (tasks.TASKACTUALENDDATE > tasks.TASKENDDATE || tasks.TASKACTUALENDDATE > tasks.TASKADJUSTEDENDDATE)";
     $this->db->select('*, CURDATE() as "currDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
     DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
     DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
     ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKADJUSTEDENDDATE)) as "actualAdjusted",
     ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKENDDATE)) as "actualInitial",
     ABS(DATEDIFF(CURDATE(), TASKADJUSTEDENDDATE)) as "adjustedDelay",
     ABS(DATEDIFF(CURDATE(), TASKENDDATE)) as "initialDelay"');
     $this->db->from('tasks');
     $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
     $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
     $this->db->join('users', 'raci.users_USERID = users.USERID');
     $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
     $this->db->where($condition);
     $this->db->group_by('tasks.TASKID');

     return $this->db->get()->result_array();
   }

   public function getDeptPerformance($deptID)
   {
     $condition = "CATEGORY = 3 && raci.status = 'Current' && role = 1 && departments_DEPARTMENTID = " . $deptID;
     $this->db->select('COUNT(TASKID), projects_PROJECTID, projects.PROJECTTITLE, projects.PROJECTENDDATE, (100 / COUNT(taskstatus)),
     ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness",
     ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE, 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
     $this->db->from('tasks');
     $this->db->join('raci', 'tasks_TASKID = TASKID');
     $this->db->join('users', 'users_USERID = USERID');
     $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
     $this->db->where($condition);
     $this->db->group_by('projects.PROJECTID');
     $this->db->order_by('projects.PROJECTENDDATE');

     return $this->db->get()->result_array();
   }

   public function getMainActivitiesByProject($projectID)
   {
     $condition = "CATEGORY = '1' && tasks.projects_PROJECTID = '$projectID'";
     $this->db->select('*');
     $this->db->from('tasks');
     $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
     $this->db->where($condition);
     $this->db->order_by('tasks.TASKSTARTDATE');
     $this->db->group_by('tasks.TASKID');

     return $this->db->get()->result_array();
   }

   public function getSubActivitiesByProject($projectID)
   {
     $condition = "CATEGORY = '2' && tasks.projects_PROJECTID = '$projectID'";
     $this->db->select('*');
     $this->db->from('tasks');
     $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
     $this->db->where($condition);
     $this->db->order_by('tasks.TASKSTARTDATE');
     $this->db->group_by('tasks.TASKID');

     return $this->db->get()->result_array();
   }

   public function checkUserByName($data)
   {
     $condition = "CONCAT(FIRSTNAME, ' ', LASTNAME) = '" . $data . "'";
     $this->db->select("CONCAT(FIRSTNAME, ' ', LASTNAME) as FULLNAME");
     $this->db->from('users');
     $this->db->where($condition);
     $this->db->limit(1);
     $query = $this->db->get();

     if ($query->num_rows() == 1)
     {
       return true;
     }

     else
     {
       return false;
     }
   }

   public function getUserType($userID)
   {
     $condition = "USERID = '$userID'";
     $this->db->select('*');
     $this->db->from('users');
     $this->db->where($condition);
     $this->db->limit(1);
     $query = $this->db->get();

     return $query->row('usertype_USERTYPEID');
   }

   public function getUserTeam($userID)
   {
     $condition = "users_SUPERVISORS = '$userID'";
     $this->db->select('*');
     $this->db->from('users');
     $this->db->where($condition);

     return $this->db->get()->result_array();
   }

   public function addAssessmentProject($data){

     $this->db->insert('assessmentProject', $data);

     return true;
   }

   public function getProjectCountRole1($userID)
   {
    $condition = "raci.ROLE = '1' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'
                 && raci.users_USERID = '$userID'";
    $this->db->select('projects.*');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->where($condition);
    $this->db->group_by('PROJECTID');
    return $this->db->get()->result_array();
  }

  public function getTaskCountRole1($userID)
  {
    $condition = "raci.ROLE = '1' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'
                 && raci.users_USERID = '$userID'";
    $this->db->select('tasks.*, ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKENDDATE)) as "originalDelay",
                      ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKADJUSTEDENDDATE)) as "adjustedDelay"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getDepartmentHeadByDepartmentID($deptID)
  {
    $condition = "departments_DEPARTMENTID = '$deptID' && usertype_USERTYPEID = '3'";
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function getAllOngoingDelayedTasksByIDRole1($id)
  {
    $condition = "raci.STATUS = 'Current' && raci.ROLE = '1' && projects.PROJECTID = " . $id . " && tasks.CATEGORY = 3
                  && tasks.TASKSTATUS = 'Ongoing' && (CURDATE() > tasks.TASKENDDATE || CURDATE() > tasks.TASKADJUSTEDENDDATE)";
    $this->db->select('*, CURDATE() as "currDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKADJUSTEDENDDATE)) as "actualAdjusted",
    ABS(DATEDIFF(tasks.TASKACTUALENDDATE, tasks.TASKENDDATE)) as "actualInitial",
    ABS(DATEDIFF(CURDATE(), TASKADJUSTEDENDDATE)) as "adjustedDelay",
    ABS(DATEDIFF(CURDATE(), TASKENDDATE)) as "initialDelay"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('tasks.TASKID');

    return $this->db->get()->result_array();
  }

  public function getAllUsersForAdmin()
  {
    $condition = "users.isAct = '1'";
    $this->db->select('*');
    $this->db->from('users');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('users.LASTNAME');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllDepartmentsForAdmin()
  {
    $condition = "departments.isAct = '1'";
    $this->db->select('*');
    $this->db->from('users');
    $this->db->join('departments', 'users.USERID = departments.users_DEPARTMENTHEAD');
    $this->db->order_by('users.LASTNAME');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllUserTypesForAdmin()
  {
    $condition = "usertype.isAct = '1'";
    $this->db->select('*');
    $this->db->from('usertype');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllDepartmentHeadsForAdmin()
  {
    $condition = "usertype_USERTYPEID = 2 OR usertype_USERTYPEID = 3";
    $this->db->select('*');
    $this->db->from('users');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getTaskCountPerDepartment($deptID, $condition){

    $this->db->select('users_USERID, COUNT(IF(taskstatus = "Ongoing", 1, NULL)) + COUNT(IF(taskstatus = "Planning", 1, NULL)) AS "TASKCOUNT"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);
    $this->db->group_by('users_USERID');

    return $this->db->get()->result_array();
  }

  public function getProjectCountPerDepartment($deptID){

    $condition = "raci.ROLE = 1 AND raci.STATUS = 'Current'AND tasks.CATEGORY = 3
      AND (projects.PROJECTSTATUS = 'Ongoing' OR projects.PROJECTSTATUS = 'Planning')
      AND users.departments_DEPARTMENTID = " . $deptID;
    $this->db->select('users.*, COUNT(DISTINCT(projects.PROJECTID)) as "PROJECTCOUNT"');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->group_by('users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAssessmentByMain($key, $projectID)
  {
    $condition = "projects_PROJECTID = " . $projectID . " AND TYPE = 2 AND DATE = date_sub(curdate(), INTERVAL " . $key . " DAY)";
    $this->db->select('*');
    $this->db->from('assessmentProject');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getCurrentAssessmentByMain($projectID)
  {
    $condition = "projects_PROJECTID = " . $projectID . " && DATE = '" . date('Y-m-d') . "' && TYPE = 2";
    $this->db->select('*');
    $this->db->from('assessmentProject');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getDelayedTaskCount($projectID){

    $condition = "projects.PROJECTID = '$projectID' && raci.ROLE = 1 && raci.STATUS = 'Current' && tasks.CATEGORY = '3'";
    $this->db->select('users.*, tasks.*, COUNT(IF(TASKENDDATE < TASKACTUALENDDATE, 1, NULL)) + COUNT(IF(TASKENDDATE <= CURDATE() && TASKSTATUS = "Ongoing", 1, NULL)) AS "DELAYEDCOUNT"');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('users.USERID');
    $this->db->order_by('departments.DEPARTMENTNAME');

    return $this->db->get()->result_array();
  }

  public function changeProjectStatus($id, $data)
  {
    $this->db->where('PROJECTID', $id);
    $result = $this->db->update('projects', $data);
  }

  public function getDelayedTaskCountPerDepartment($deptID)
  {
    $condition = "YEAR(CURDATE()) && CATEGORY = 3 && TASKACTUALSTARTDATE != ''  && raci.status = 'Current' && role = 1 && departments_departmentid = " . $deptID;
    $this->db->select('users.userid, COUNT(IF(TASKACTUALENDDATE < TASKENDDATE, 1, NULL)) AS "delayedTaskCount"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.taskid = raci.tasks_taskid');
    $this->db->join('users', 'users.userid = raci.users_userid');
    $this->db->where($condition);
    $this->db->group_by('users_userid');

    return $this->db->get()->result_array();
  }

  public function getUserHead($userid){

    $condition = "USERID = " . $userid;
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition);

    return $this->db->get()->row('users_SUPERVISORS');
  }

  public function getTaskWeightByProject($id)
  {
    $condtion = "tasks.CATEGORY = 3 AND projects.PROJECTID = " . $id;
    $this->db->select("COUNT(taskid), projects_projectid, (100 / COUNT(taskid)) AS 'weight'");
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condtion);

    return $this->db->get()->row('weight');
  }

  public function addTaskUpdate($data)
  {
    $this->db->insert('taskupdates', $data);
    return true;
  }

  public function getTaskUpdatesByID($taskID)
  {
    $condition = "taskupdates.tasks_TASKID = '$taskID'";
    $this->db->select('*');
    $this->db->from('taskUpdates');
    $this->db->join('tasks', 'tasks.taskid = taskupdates.tasks_TASKID');
    $this->db->join('users', 'users.userid = taskupdates.users_COMMENTEDBY');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function editProjectDetails($id, $data)
  {
    $this->db->where('PROJECTID', $id);
    $result = $this->db->update('projects', $data);
  }

  public function editTask($id, $data)
  {
    $this->db->where('TASKID', $id);
    $result = $this->db->update('tasks', $data);

    if ($result)
    {
      $this->db->select('*');
      $this->db->from('tasks');
      $this->db->where('TASKID', $id);
      $query = $this->db->get();

      return $query->row('TASKID');
    }

    else
    {
      return false;
    }
  }

  public function editRaci($id, $data)
  {
    $this->db->where('tasks_TASKID', $id);
    $result = $this->db->update('raci', $data);
  }

  public function changeRACIStatus($id, $data)
  {
    $condition = "tasks_TASKID = " . $id . " AND CATEGORY = 1";
    $this->db->where('tasks_TASKID', $id);
    $result = $this->db->update('raci', $data);
  }

  public function getRaciMain($id)
  {
    $condition = "projects.PROJECTID = '" . $id . "' AND raci.STATUS = 'Current' AND tasks.CATEGORY = 1";
    $this->db->select('raci.*, users.departments_DEPARTMENTID as uDept, tasks.CATEGORY as tCat');
    $this->db->from('raci');
    $this->db->join('tasks', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', ' raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllProjectMainSub($id)
  {
    $condition = "raci.STATUS = 'Current' && tasks.CATEGORY != 3 && projects.PROJECTID = " . $id;
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllProjectTasksGroupByTaskIDMain($id)
  {
    // initialTaskDuration
    $condition = "raci.STATUS = 'Current' && tasks.CATEGORY = 1 && projects.PROJECTID = " . $id;
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('tasks.TASKID');
    $this->db->group_by('tasks.TASKSTARTDATE');

    return $this->db->get()->result_array();
  }

  public function addNewUserType($data)
  {
    $this->db->insert('usertype', $data);

    return true;
  }

  public function updateUserType($data, $usertypeID)
  {
    $this->db->where('USERTYPEID', $usertypeID);
    $this->db->update('usertype', $data);

    return true;
  }

  public function addNewUser($data)
  {
    $this->db->insert('users', $data);

    return true;
  }

  public function updateUser($data, $userID)
  {
    $this->db->where('USERID', $userID);
    $this->db->update('users', $data);

    return true;
  }

  public function addNewDepartment($data)
  {
    $this->db->insert('departments', $data);

    return true;
  }

  public function updateDepartment($data, $deptID)
  {
    $this->db->where('DEPARTMENTID', $deptID);
    $this->db->update('departments', $data);

    return true;
  }

  public function getUserTypeByID($id)
  {
    $condition = "USERTYPEID = " . $id;
    $this->db->select('*');
    $this->db->from('usertype');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }
}
?>

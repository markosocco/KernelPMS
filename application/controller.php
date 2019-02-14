/******************** GANTT CHART DELETE THIS AFTER ********************/
public function gantt()
{
  if (!isset($_SESSION['EMAIL']))
  {
    $this->load->view('contact');
  }

  else
  {
    $data['ganttData'] = $this->model->getGanttData();
    $data['dependencies'] = $this->model->getDependecies();
    $this->load->view("gantt", $data);
  }
}

<?php
require_once '../Config/Database.php';
require_once '../Controller/Controller.php';
require_once '../Model/Employee.php';

class Router {
  private $request;
  private $method;
  private $controller;
  private $db;


  public function __construct($request, $method) {
    $this->request = $request;
    $this->method = $method;
    $this->db = new Database();
    $this->controller = new EmployeeController($this->db);
  }

  public function route() {
    switch ($this->method) {
      case 'GET':
        $this->get();
        break;
      case 'POST':
        $this->post();
        break;
      case 'PUT':
        $this->put();
        break;
      case 'DELETE':
        $this->delete();
        break;
      default:
        http_response_code(404);
        echo 'Invalid request';
    }
  }

  private function get() {
    switch ($this->request) {
      case '/employees':
        echo $this->controller->getAll();
        break;
      default:
        preg_match('/\/employees\/(\d+)/', $this->request, $matches);
        if (count($matches) != 2) {
          http_response_code(404);
          echo 'Invalid request';
          return;
        }
        $id = $matches[1];
        $response = $this->controller->getById($id);
        http_response_code($response['status']);
        echo json_encode($response);
    }
  }




  private function post() {
    switch ($this->request) {
      case '/employees':
        $this->addEmployee();
        break;
      default:
        http_response_code(404);
        echo 'Invalid request';
    }
  }


  private function addEmployee() {
    $data = json_decode(file_get_contents('php://input'), true);
  
    if (!isset($data['name']) || !isset($data['email']) || !isset($data['phone'])) {
      http_response_code(400);
      echo 'Invalid request body';
      return;
    }
  
    $employee = new Employee(0,$data['name'], $data['email'], $data['phone']);
  
    $response = $this->controller->create($employee);
    echo json_encode($response);
  
  }
  

  private function delete() {
    preg_match('/\/employees\/(\d+)/', $this->request, $matches);
    if (count($matches) != 2) {
      http_response_code(404);
      echo 'Invalid request';
      return;
    }

    $id = $matches[1];
 
    $response = $this->controller->delete($id);
    echo json_encode($response);


  }



  private function put() {
    preg_match('/\/employees\/(\d+)/', $this->request, $matches);
    if (count($matches) != 2) {
        http_response_code(404);
        echo 'Invalid request';
        return;
    }
  
    $id = $matches[1];
  
    $data = json_decode(file_get_contents('php://input'), true);
  
    if (!isset($data['name']) || !isset($data['email']) || !isset($data['phone'])) {
        http_response_code(400);
        echo 'Missing required fields';
        return;
    }
  
    $result = $this->controller->update(new Employee($id, $data['name'], $data['email'], $data['phone']));
  
    http_response_code(200);
    echo json_encode($result);
    
}



}

  

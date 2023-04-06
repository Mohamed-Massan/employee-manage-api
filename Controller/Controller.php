<?php
class EmployeeController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    

    }



    public function getAll() {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM employees");
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $employees = array();
    
        foreach ($result as $row) {
            array_push($employees, $row);
        }
    
        if (empty($employees)) {
            return json_encode(array("message" => "No employees found."));
        } else {
            return json_encode($employees);
        }
    }
    
    

    

    public function getById($id) {
    
        try {
            
            $stmt = $this->db->getConnection()->prepare("SELECT * FROM employees WHERE id = :id");
            $stmt->execute(['id' => $id]);
    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($row !== false) {
                return ['status' => 200, 'data' => $row];
            } else {
                
                return ['status' => 404, 'message' => 'Employee not found'];
            }
        } catch (PDOException $e) {
            return ['status' => 500, 'message' => 'Internal server error'];
        }
    }
    
    

    public function create($employee) {
        
        $name = filter_var($employee->getName(), FILTER_SANITIZE_STRING);
        $email = filter_var($employee->getEmail(), FILTER_SANITIZE_EMAIL);
        $phone = filter_var($employee->getPhone(), FILTER_SANITIZE_STRING);
    
        try {
            $stmt = $this->db->getConnection()->prepare("INSERT INTO employees (name, email, phone) VALUES (:col1, :col2, :col3)");
            $stmt->execute(array(':col1' => $name, ':col2' => $email, ':col3' => $phone));
    
            $response = ["success" => true];
        } catch (Exception $e) {
            $response = ["success" => false, "error" => $e->getMessage()];
        }
    
        return $response;
    }
    
    



    public function delete($id) {
        try {
            $stmt = $this->db->getConnection()->prepare("DELETE FROM employees WHERE id = :col1");
            $stmt->execute(array(':col1' => $id));
    
            $rowCount = $stmt->rowCount();
            $response = ["success" => $rowCount > 0]; // Return true if any rows were affected
        } catch (Exception $e) {
            $response = ["success" => false, "error" => $e->getMessage()];
        }
    
        return $response;
    }


    public function update($employee) {
        $stmt = $this->db->getConnection()->prepare("UPDATE employees SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->execute([$employee->getName(), $employee->getEmail(), $employee->getPhone(), $employee->getId()]);
        
        if ($stmt->rowCount() == 0) {
            http_response_code(404);
            return array("message" => "Employee not found.");
        } else {
            return array("message"=> "Employee updated successfully");
        }
    }
    

}

    


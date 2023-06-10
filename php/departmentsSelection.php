<?php

function departmentSelection($userID) {
    require_once('connect.php');
    $db = getDBConnection();


    $stmt = $db->prepare('SELECT department.id AS department_id, department.name_ AS department_name, department.description_ AS department_description FROM department, agentWorksDepartment
    WHERE department.id = agentWorksDepartment.id_department
        AND  agentWorksDepartment.id_agent = ?');

    $stmt->execute();

    $stmt->execute(array($userID));
    $result = $stmt->fetch();

    if($result){
        $department = new department();
        $department->id = $result["department_id"];
        $department->name = $result["department_name"];
        $department->description = $result["department_description"];
        return $department;
    }

    return null;
}

?>
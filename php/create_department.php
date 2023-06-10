<?php

require_once('connect.php');
require_once('classes.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    if (empty($name)) {
        $error = 'Department name is required.';
    } elseif (strlen($description) > 255) {
        $error = 'Description must not exceed 255 characters.';
    } else {
        $department = new Department();
        $department->name = $name;
        $department->description = $description;

        $db = getDBConnection();
        $stmt = $db->prepare("INSERT INTO department (name_, description_) VALUES (?, ?)");
        $result = $stmt->execute([$department->name, $department->description]);

        if ($result) {
            echo 'Department created successfully!';
        } else {
            echo 'Error creating department.';
        }
    }
}
?>

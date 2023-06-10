<?php
require_once('connect.php');

$db = getDBConnection();

session_start();

$newdepart = intval($_POST['department']);

$stmt = $db->prepare("UPDATE agentWorksDepartment SET id_department = ? WHERE id_agent = ?");
$stmt->execute([$newdepart, $_POST["user_id"]]);


$previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'my_tickets.php';
$_SESSION['department_id'] = $newdepart; 
header('Location: ' . $previous_page);
?>
<?php
require_once('connect.php');

$db = getDBConnection();

$ticketId = $_POST['ticket'];
$departmentID = $_POST['departmentID'];

$stmt = $db->prepare("DELETE FROM agentAssignedTicket WHERE id_ticket = ?");
$stmt->execute([$ticketId]);

$stmt = $db->prepare("UPDATE ticket SET department_id = ? WHERE id = ?");
$stmt->execute([$departmentID, $ticketId]);

$previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'my_tickets.php';
header('Location: ' . $previous_page);
?>
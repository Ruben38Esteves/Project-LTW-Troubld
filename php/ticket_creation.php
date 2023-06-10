<?php
require_once('connect.php');
require_once('classes.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $department = $_POST['department'];
    $priority = $_POST['priority'];
    $email_message = $_POST['email-message'];
    $user_id = intval($_SESSION['userId']);
    if (empty($subject) || empty($department) || empty($priority) || empty($email_message)) {
        echo "Please fill in all the required fields.";
        exit;
    }
    $db = getDBConnection();
    $stmt = $db->prepare("INSERT INTO ticket (subtitle_, description_, priority_, department_id, id_client) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(array($subject, $email_message, $priority, $department, $user_id));

    echo "Submission successful!";

    header('Location: my_tickets.php');
    exit;
}
?>
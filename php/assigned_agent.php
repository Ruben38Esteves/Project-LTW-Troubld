<?php
    require_once('connect.php');
    session_start();

    $db = getDBConnection();

    $stmt = $db->prepare("DELETE FROM agentAssignedTicket WHERE id_ticket = ?");
    $stmt->execute(array($_POST["ticket_id"]));
    
    $stmt = $db->prepare("INSERT INTO agentAssignedTicket (id_agent,id_ticket) VALUES (?, ?)");
    $stmt->execute(array($_POST["agent_id"], $_POST["ticket_id"]));
    
    
    $previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'my_tickets.php';
    header('Location: ' . $previous_page);
?>
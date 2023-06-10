<?php
    require_once('connect.php');
    session_start();

    $db = getDBConnection();
    $stmt = $db->prepare("INSERT INTO agentAssignedTicket (id_agent,id_ticket) VALUES (?, ?)");
    $stmt->execute(array($_SESSION["userId"], $_POST["ticket_id"]));

    header('Location: ../my_tickets.php');
?>
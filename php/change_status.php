<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ticketId = $_POST["ticket_id"];
    $newStatus = $_POST["new_status"];

    require_once("connect.php");
    $db = getDBConnection();

    $stmt = $db->prepare("UPDATE ticket SET status_ = ? WHERE id = ?");
    $stmt->execute(array($newStatus, $ticketId));

    http_response_code(200);
} else {
    http_response_code(400);
}

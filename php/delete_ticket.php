<?php

require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketId = $_POST['ticket_id'];


    $db = getDBConnection();
    $stmt = $db->prepare("DELETE FROM ticket WHERE id = ?");
    $stmt->execute([$ticketId]);

    http_response_code(200);
} else {
    http_response_code(405);
}

?>

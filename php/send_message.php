<?php
    require_once('connect.php');
    session_start();

    $db = getDBConnection();
    $content = $_POST["message_info"];
    $chat_id = intval($_POST["chat_id"]);

    $stmt = $db->prepare("INSERT INTO message_inf (content, id_client, id_chat) VALUES (?, ?, ?)");
    $stmt->execute([$content, $_SESSION["userId"], $chat_id]);

    $previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'my_tickets.php';
    header('Location: ' . $previous_page);  

?>
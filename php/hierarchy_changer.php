<?php
session_start();

require_once('connect.php');

$db = getDBConnection();

$newHierarchy = $_POST["user_type"];
$target_id = intval($_POST["user_id"]);

$stmt = $db->prepare("DELETE FROM administrator WHERE id = ?");
$stmt->execute([$target_id]);

$stmt = $db->prepare("DELETE FROM agent WHERE id = ?");
$stmt->execute([$target_id]);

$stmt = $db->prepare("DELETE FROM client WHERE id = ?");
$stmt->execute([$target_id]);

if ($newHierarchy == "client") {
    $stmt = $db->prepare("INSERT INTO client (id) VALUES (?)");
    $stmt->execute([$target_id]);
} elseif ($newHierarchy == "agent") {
    $stmt = $db->prepare("INSERT INTO client (id) VALUES (?)");
    $stmt->execute([$target_id]);

    $stmt = $db->prepare("INSERT INTO agent (id) VALUES (?)");
    $stmt->execute([$target_id]);
} elseif ($newHierarchy == "admin") {
    $stmt = $db->prepare("INSERT INTO client (id) VALUES (?)");
    $stmt->execute([$target_id]);

    $stmt = $db->prepare("INSERT INTO agent (id) VALUES (?)");
    $stmt->execute([$target_id]);

    $stmt = $db->prepare("INSERT INTO administrator (id) VALUES (?)");
    $stmt->execute([$target_id]);
}

header('Location: profile.php?username=' . $_SESSION["username"]);
?>
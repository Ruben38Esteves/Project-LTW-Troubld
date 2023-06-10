<?php
session_start();

require_once('connect.php');

$db = getDBConnection();

$old = sha1($_POST["old"]);
$new1 = sha1($_POST["new1"]);
$new2 = sha1($_POST["new2"]);

if ($new1 === $new2) {
    $stmt = $db->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$_SESSION["userId"]]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($result["password_hash"] == $old) {
        $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$new1, $_SESSION["userId"]]);
    }
}
header('Location: profile.php?username=' . $_SESSION["username"]);
?>
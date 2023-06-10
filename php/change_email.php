<?php
require_once('connect.php');

$db = getDBConnection();

session_start();

$newEmail = $_POST['email'];

if ($newEmail !== $_SESSION["email"]) {

    $stmt = $db->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->execute([$newEmail, $_SESSION["userId"]]);
    $_SESSION["email"] = $newEmail;

}

header('Location: profile.php?username=' . $_SESSION["username"]);
    ?>
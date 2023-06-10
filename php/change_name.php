<?php
    require_once('connect.php');

    $db = getDBConnection();

    session_start();

    $newName = $_POST['name'];

    if($newName != $_SESSION["name"]){
        $stmt = $db->prepare("UPDATE users SET name_ = ? WHERE id = ?");
        $stmt->execute([$newName, $_SESSION["userId"]]);
        $_SESSION["name"] = $newName;
    }

    header('Location: profile.php?username=' . $_SESSION["username"])
?>
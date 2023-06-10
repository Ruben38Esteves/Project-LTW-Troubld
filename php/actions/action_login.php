<?php 
    declare (strict_types = 1);
    require_once('../authenticate.php');
    $email = $_POST['email'];
    $password = $_POST['password'];
    authenticate($email, $password);
?>
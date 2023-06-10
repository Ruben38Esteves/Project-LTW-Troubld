<?php
// register.php
require_once('connect.php');
session_start();    

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the registration form submission
    $email = $_POST['email'];
    $username = $_POST['username'];
    $name_ = $_POST['name'];
    $password = sha1($_POST['password']);
    $password_confirm = sha1($_POST['password_confirm']);
    $birthday = $_POST['birthday'];
    if (empty($name_) || empty($email) || empty($password) || empty($password_confirm) || empty($username) || empty($birthday)) {
        echo "Please fill in all the required fields.";
        exit;
    }

    if($password != $password_confirm){
        echo "Password and Password_confirm must be the same";
        exit;
    }
    $db = getDBConnection();
    $stmt = $db->prepare("INSERT INTO users (name_, username, password_hash, birthday, email) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(array($name_, $username, $password, $birthday, $email));

    echo "Registration successful!";

    header('Location: ../index.php');
    exit;
}
?>
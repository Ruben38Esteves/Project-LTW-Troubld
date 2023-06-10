<?php
//php -S localhost:9000
//localhost:9000
function authenticate($email, $password){
    require_once('connect.php');
    require_once('departmentsSelection.php');
    require_once("classes.php");

    $db = getDBConnection();

    $stmt = $db->prepare("SELECT users.id, users.name_, users.username, users.password_hash, users.birthday, users.email, agent.id AS agent_id, administrator.id AS administrator_id
    FROM users
    LEFT JOIN agent ON users.id = agent.id
    LEFT JOIN administrator ON agent.id = administrator_id
    WHERE users.email = ? AND users.password_hash = ?");
    $stmt->execute(array($email, $password));
    $user = $stmt->fetch();


    if($user){
        $_SESSION['userId'] = $user['id'];
        $_SESSION['name'] = $user['name_'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['loggedin']   = true;
        if ($user['administrator_id'] != null) {
            $_SESSION['hierarchy'] = 'admin';
        } else if ($user['agent_id'] != null) {
            $_SESSION['hierarchy'] = 'agent';
        } else {
            $_SESSION['hierarchy'] = 'client';
        }
        $_SESSION['ticket_query_status'] = "";
        $_SESSION['ticket_query_priority'] = "";
        $_SESSION['ticket_query_depertment_id'] = "";

        $department = new department();
        $department = departmentSelection($user['id']);
        $_SESSION['department_id'] = $department->id; 
        $_SESSION['department_name'] = $department->name;

        echo "logged in";
        header('Location: my_tickets.php');
        exit;
    }else{
        echo '<script>';
        echo 'alert("Invalid username or password.");';
        echo 'window.location.href = "../index.php";';
        echo '</script>';
    }
}

session_start();

$email = $_POST['email'];
$password = sha1($_POST['password']);

if($email && $password) authenticate($email, $password);
else header('Location: ../index.php'); //se nao conseguir dar authenticate, entao ele volta para o index.php 
?>
<?php
    session_start();
    if ($_SESSION['loggedin'] == true) {
        header('Location: php/my_tickets.php');
    }else{
        require 'php/index_display.php';
        show_index();
    }
?>
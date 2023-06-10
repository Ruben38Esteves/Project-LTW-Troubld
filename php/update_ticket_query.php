<?php

    session_start();

    $_SESSION['ticket_query_status'] = $_POST['status'];
    $_SESSION['ticket_query_priority'] = $_POST['priority'];
    $_SESSION['ticket_query_depertment_id'] = $_POST['department'];

    header('Location: my_tickets.php');

?>
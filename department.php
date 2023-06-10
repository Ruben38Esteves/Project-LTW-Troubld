<link rel="stylesheet" href="../html_css/department.css">
<?php
session_start();
?>
<link rel="stylesheet" href="../html_css/department.css">
<?php
require_once('php/classes.php');
require_once('php/department_info.php');
require 'php/common/common_html.php';
$department_id = $_GET['id'];
$department = get_department_info(intval($department_id));
$department->tickets = get_department_tickets(intval($department_id));
show_common();
?>

<div class="content">
            
<?php
    view_department_info($department); 
    view_department_tickets($department->tickets, $department->tickets, $department->tickets);
?>

<?php
close_common();
?>
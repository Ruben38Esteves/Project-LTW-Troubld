<?php

function get_department_info(int $department_id)
{
    require_once('connect.php');
    require_once('classes.php');

    $db = getDBConnection();

    $stmt = $db->prepare("SELECT id, name_, description_ FROM department WHERE id = ?");
    $stmt->execute(array($department_id));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $department = new department();
    $department->id = $result['id'];
    $department->name = $result['name_'];
    $department->description = $result['description_'];

    return $department;
}

function get_all_departs()
{
    require_once('connect.php');
    require_once('classes.php');

    $db = getDBConnection();

    $stmt = $db->prepare("SELECT * FROM department");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $departments = array();


    if ($result) {
        foreach ($result as $row) {
            $department = new department();
            $department->id = $row['id'];
            $department->name = $row['name_'];
            $department->description = $row['description_'];
            $departments[] = $department;
        }
    }

    return $departments;
}

function get_department_tickets(int $department_id)
{
    require_once('connect.php');
    require_once('classes.php');
    require_once('ticket_info.php');

    $db = getDBConnection();

    $stmt = $db->prepare("SELECT id
    FROM ticket t
    WHERE t.department_id = ?
    ");
    $stmt->execute(array($department_id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tickets = array();


    if ($result) {
        foreach ($result as $row) {
            $tickets[] = get_ticket(intval($row['id']));
        }
    }

    return $tickets;
}

function get_tickets(string $status, string $priority, int $department_id, string $startDate, string $endDate)
{
    require_once('connect.php');
    require_once('classes.php');

    $db = getDBConnection();

    $sql = "SELECT t.*, c.username AS client_username, COALESCE(a.username, 'Not assigned') AS agent_username
    FROM ticket t
    INNER JOIN users c ON t.id_client = c.id
    LEFT JOIN agentAssignedTicket aa ON t.id = aa.id_ticket
    LEFT JOIN users a ON aa.id_agent = a.id
    WHERE t.department_id = ? 
    ";


    // Add additional filters to the SQL statement if the corresponding inputs are provided
    if ($priority !== null) {
        $sql .= "AND priority_ = ?";
    }
    if ($status !== null) {
        $sql .= "AND status_ = ?";
    }

    if ($startDate !== null && $endDate !== null) {
        $sql .= " AND creation_date BETWEEN ? AND ? ";
    }

    $stmt = $db->prepare($sql);

    // Bind the parameter values based on the provided inputs
    $params = array();
    $params[] = $department_id;

    if ($priority !== null) {
        $params[] = $priority;
    }
    if ($status !== null) {
        $params[] = $status;
    }

    if ($startDate !== null && $endDate !== null) {
        $params[] = $startDate;
        $params[] = $endDate;
    }

    $sql .= " ORDER BY creation_date DESC";



    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tickets = array();

    if ($result) {
        foreach ($result as $row) {
            $ticket = new ticket();
            $ticket->id = $row['id'];
            $ticket->subtitle_ = $row['subtitle_'];
            $ticket->description_ = $row['description_'];
            $ticket->status_ = $row['status_'];
            $ticket->priority_ = $row['priority_'];
            $ticket->department_id = $row['department_id'];
            $ticket->client_username = $row['client_username'];
            $ticket->agent_username = $row['agent_username'];

            $tickets[] = $ticket;
        }
    }


    // Process and display the fetched tickets
    return $tickets;
}
function view_department_info($department)
{
    ?>
    <div class="department_preview">

        <div id="department_name">
            <h1 title="Department Name">
                <?php echo $department->name ?>
            </h1>
        </div>

        <div id="department_description">
            <h2>
                <?php echo $department->description ?>
            </h2>
        </div>
    </div>
    <?php
}

function view_department_tickets(array $recieved_tickets, array $assigned_tickets, array $last_finished_tickets)
{
    require_once 'ticket_info.php';
    ?>
    <div class="department_tickets">
        <div class="received_tickets">
            <p title="Received Tickets">High Priority Tickets</p>

            <?php
            foreach ($recieved_tickets as $ticket) {
                display_ticket($ticket);
            } ?>
        </div>

        <div class="assigned_tickets">
            <p title="Assigned Tickets">Closed Tickets</p>


            <?php foreach ($assigned_tickets as $ticket) {
                display_ticket($ticket);
            } ?>
        </div>

        <div class="last_finished_tickets">
            <p title="Last Finished Tickets">Last Finished Tickets</p>

            <?php foreach ($last_finished_tickets as $ticket) {
                display_ticket($ticket);
            } ?>
        </div>

    </div>
    <?php
}

function display_tickets(array $tickets)
{
    foreach ($tickets as $ticket) {
        display_ticket($ticket);
    }
}


function get_all_users_from_depart(int $department_id)
{
    require_once('connect.php');
    require_once('classes.php');


    $db = getDBConnection();

    $stmt = $db->prepare("
    SELECT users.id, users.username
    FROM users
    INNER JOIN agentWorksDepartment ON users.id = agentWorksDepartment.id_agent
    WHERE agentWorksDepartment.id_department = ?
    ");
    $stmt->execute(array($department_id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $users = array();


    if ($result) {
        foreach ($result as $row) {
            $user = new user();
            $user->id = $row['id'];
            $user->username = $row['username'];
            $users[] = $user;
        }
    }

    return $users;
}

?>
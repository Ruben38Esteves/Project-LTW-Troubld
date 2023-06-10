<?php
require('ticket_info.php');
function fetch_user_tickets(int $user_id, string $status, int $priority, int $department_id)
{
    require_once('connect.php');
    require_once('departmentsSelection.php');
    require_once('classes.php');


    $db = getDBConnection();

    $query = "SELECT id FROM ticket t WHERE 1 = 1";

    $params = array();

    if ($user_id !== intval("")) {
        $query .= " AND t.id_client = ?";
        $params[] = $user_id;
    }

    if ($status !== "") {
        $query .= " AND t.status_ = ?";
        $params[] = $status;
    }

    if ($priority !== intval("")) {
        $query .= " AND t.priority_ = ?";
        $params[] = $priority;
    }

    if ($department_id !== intval("")) {
        $query .= " AND t.department_id = ?";
        $params[] = $department_id;
    }

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tickets = array();

    if ($result) {
        foreach ($result as $row) {
            $tickets[] = get_ticket($row['id']);
        }
        return $tickets;
    }

    return null;
}


function fetch_user_assigned_tickets(int $user_id)
{
    require_once('connect.php');
    require_once('departmentsSelection.php');
    require_once('classes.php');

    $db = getDBConnection();

    $stmt = $db->prepare("SELECT t.id
        FROM ticket t
        JOIN agentAssignedTicket aat ON t.id = aat.id_ticket
        WHERE aat.id_agent = ?");
    $stmt->execute([$user_id]);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tickets = array();

    if ($result) {
        foreach ($result as $row) {
            $tickets[] = get_ticket($row['id']);
        }
    }

    return $tickets;

}
?>
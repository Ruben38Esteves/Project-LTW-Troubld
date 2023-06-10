<?php
function aaaaaafetch_user_assigned_tickets(int $user_id)
{
    require_once('connect.php');
    require_once('departmentsSelection.php');
    require_once('classes.php');
    require('ticket_info.php');

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
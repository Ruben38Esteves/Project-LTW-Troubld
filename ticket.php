
<?php
session_start();
?>
<link rel="stylesheet" href="html_css/ticket.css">
<?php
require_once('php/classes.php');
require_once('php/ticket_info.php');
require 'php/common/common_html.php';
$ticket_id = $_GET['id'];
$ticket = get_ticket(intval($ticket_id));
show_common();
?>

<div class="content">
<?php
display_ticket($ticket);

display_ticket_buttons($ticket);

if($ticket->client_username == $_SESSION['username'] || $ticket->agent_username == $_SESSION['username'] || $_SESSION['hierarchy'] === 'admin')
    display_chat($ticket);

?>
</div>

<script>
        function deleteTicket(ticketId) {
            <?php if ($_SESSION['hierarchy'] === 'admin' || $_SESSION['username'] == $ticket->agent_username || $_SESSION['username'] == $ticket->client_username) { ?>
                if (confirm("Are you sure you want to delete this ticket?")) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "../php/delete_ticket.php");
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                window.location.href = "../../../../../php/my_tickets.php";
                            } else {
                                alert("Failed to delete the ticket.");
                            }
                        }
                    };
                    xhr.send("ticket_id=" + ticketId);
                }
            <?php } ?>
        }

        function changeTicketStatus(ticketId) {
            var newStatus = prompt("Enter the new status for the ticket:");

            if (newStatus === "Answer in FAQ") {
                if (confirm("Are you sure you want to mark this ticket as 'Answer in FAQ'?")) {
                    updateTicketStatus(ticketId, newStatus);
                }
            } else {
                updateTicketStatus(ticketId, newStatus);
            }
        }

        function updateTicketStatus(ticketId, newStatus) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../php/change_status.php");
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        if (newStatus === "Answer in FAQ") {
                            alert("The answer to this ticket is in the FAQ.");
                        }
                        location.reload();
                    } else {
                        alert("Failed to change ticket status.");
                    }
                }
            };
            xhr.send("ticket_id=" + ticketId + "&new_status=" + newStatus);
        }
    </script>
<?php
close_common();

?>
<?php

function get_chatId(int $ticket_id)
{
    require_once('connect.php');
    $db = getDBConnection();

    $stmt = $db->prepare("SELECT id FROM chat
    WHERE id_ticket = ?");

    $stmt->execute([$ticket_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result["id"];
}

function get_ticket(int $ticket_id)
{
    require_once('connect.php');
    require_once('classes.php');

    $db = getDBConnection();

    $stmt = $db->prepare("
        SELECT t.*, c.username AS client_username, COALESCE(a.username, 'Not assigned') AS agent_username,  ddd.name_ AS department_name
        FROM ticket t
        INNER JOIN users c ON t.id_client = c.id
        LEFT JOIN agentAssignedTicket aa ON t.id = aa.id_ticket
        LEFT JOIN users a ON aa.id_agent = a.id
        LEFT JOIN department ddd ON ddd.id = t.department_id
        WHERE t.id = ?
    ");
    $stmt->execute(array($ticket_id));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $ticket = new ticket();
    $ticket->id = $row['id'];
    $ticket->subtitle_ = $row['subtitle_'];
    $ticket->department_name_ = $row['department_name'];
    $ticket->description_ = $row['description_'];
    $ticket->status_ = $row['status_'];
    $ticket->priority_ = $row['priority_'];
    $ticket->department_id = $row['department_id'];
    $ticket->client_username = $row['client_username'];
    $ticket->agent_username = $row['agent_username'];

    return $ticket;
}

function get_messages(int $ticket_id)
{
    require_once('connect.php');
    require_once('classes.php');

    $db = getDBConnection();

    $stmt = $db->prepare("SELECT message_inf.* FROM message_inf
    INNER JOIN chat ON message_inf.id_chat = chat.id
    WHERE chat.id_ticket = ?
    ORDER BY time_sent ASC");

    $stmt->execute([$ticket_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $messages = array();
    if ($result) {
        foreach ($result as $row) {
            $message = new message();
            $message->id = $row['id'];
            $message->content = $row['content'];
            $message->time_sent = $row['time_sent'];
            $message->id_client = $row['id_client'];

            $messages[] = $message;
        }

    }


    return $messages;

}

function display_ticket(ticket $ticket)
{
    ?>
    <div class="ticket_preview">
        <div class="ticket_part">
            <div class="ticket_header">
                <div id="ticket_name">
                    <?php echo $ticket->subtitle_; ?>
                </div>
                <div id="ticket_status">
                    <i>Status:</i>
                    <?php echo $ticket->status_; ?>
                </div>
                <div id="ticket_priority">
                    <i>Priority:</i>
                    <?php echo $ticket->priority_; ?>
                </div>
            </div>
            <div class="ticket_info">
                <div id="ticket_id">
                    <a href="/ticket.php?id=<?php echo $ticket->id ?>">
                        #<?php echo $ticket->id; ?>
                    </a>
                </div>

                <div id="ticket_department">
                    <i>Department:</i> <a href="/department.php?id=<?php echo $ticket->department_id ?>"><?php echo $ticket->department_name_; ?></a>
                </div>
            </div>
            <div id="ticket_details">
                <?php echo $ticket->description_; ?>
            </div>
        </div>
        <div id="users_associateds">
            <div id="user_ticket_creator">
                <i>Created By:</i>
                <a href="profile.php?username=<?php echo $ticket->client_username; ?>"><?php echo $ticket->client_username; ?></a>
            </div>
            <div id="user_assigned_to">
                <i>Assigned To:</i>
                <a href="profile.php?username=<?php echo $ticket->agent_username; ?>"><?php echo $ticket->agent_username; ?></a>
            </div>
        </div>
    </div>

    <?php
}

function display_ticket_buttons($ticket)
{
    session_start();
    require_once('department_info.php');
    ?>
    <div class="edit_ticket_buttons">

        <?php if($_SESSION['department_id'] === $ticket->id || $_SESSION['hierarchy'] > 1){?>
        <button type="button" class="button button1" onclick="show_department_changer()">
            Change Department
        </button>
        <div id="change_department" style="display: none;">
            <form action="../../php/change_department.php" method="POST">
                <select aria-label="new_department" name="departmentID">
                    <option value="">None</option>
                    <?php
                    $departments = get_all_departs();
                    foreach ($departments as $department) {
                        ?>
                        <option value="<?php echo $department->id; ?>"> <?php echo $department->name; ?> </option>
                        <?php
                    }
                    ?>
                </select>
                <input type="hidden" name="ticket" value="<?php echo $ticket->id; ?>">
                <button class="email_buttom" type="submit">
                    change
                </button>
            </form>
        </div>
        <?php }
        
        if($_SESSION['hierarchy'] >= 1){?>
        <button type="button" class="button button1" onclick="show_assigned_ticket_changer()">
        Assign agent!
        </button>
        <div id="assigned_agent" style="display: none;">
            <form action="../../php/assigned_agent.php" method="POST">
                <select aria-label="new_agent" name="agent_id">
                    <option value="">None</option>
                    <?php
                    $users = array();
                    $users = get_all_users_from_depart($ticket->department_id);
                    foreach ($users as $user) {
                        ?>
                        <option value="<?php echo $user->id; ?>"> <?php echo $user->username; ?> </option>
                        <?php
                    }
                    ?>
                </select>
                <input type="hidden" name="ticket_id" value="<?php echo $ticket->id; ?>">
                <button class="email_buttom" type="submit">
                    change
                </button>
            </form>
        </div>

        <?php } ?>
        <button type="button" class="button button1" onclick="changeTicketStatus(<?php echo $ticket->id; ?>)">
            Change Status
        </button>
        <button type="button" class="button button1" onclick="openModal('Edit hashtags')">
            Edit hashtags
        </button>
        <button type="button" class="button button1" onclick="openModal('List ticket history')">
            List ticket history
        </button>
        <a href="php/faq_page.php?department_id=<?php echo $ticket->department_id; ?>" class="button button1">
            FAQ
        </a>
        <button type="button" class="button button1" onclick="deleteTicket(<?php echo $ticket->id; ?>)">
            Delete ticket
        </button>
    </div>

    <script>
    function show_department_changer() {
        document.getElementById("change_department").style.display = "block";
    }
    
    function show_assigned_ticket_changer() {
        document.getElementById("assigned_agent").style.display = "block";
    }
    </script>

    <?php
}
function display_chat($ticket)
{
    session_start();
    require_once('classes.php');
    require_once('fetch_user_info.php');
    ?>
    <link rel="stylesheet" href="../html_css/ticket_chat.css">
    <div class="ticket_chat">
        <div class="messages">
            <?php
            $messages = get_messages($ticket->id);

            foreach ($messages as $message) {
                $content = $message->content;
                $timeSent = $message->time_sent;
                $username = get_username_by_id($message->id_client);
                // Determine the CSS class based on the sender of the message
                if ($_SESSION['username'] == $username) {
                    ?>
                    <div class="message_user">
                        <?php
                } else {
                    ?>
                        <div class="message_other">
                            <?php
                }
                ?>
                        <div class="message_header">
                            <?php echo $username ?>
                            <p>
                                <?php echo $content; ?>
                            </p>
                            <span class="time_sent">
                                <?php echo $timeSent; ?>
                            </span>
                        </div>
                    </div>
                    <?php
            }
            ?>
            </div>
            <form class="message_form" method="POST" action="php/send_message.php">
                <input type="text" class="message_header" name="message_info" id="client" placeholder="Write a message">
                <input type="hidden" name="chat_id" value="<?php echo get_chatId(intval($ticket->id)); ?>">
                <button type="submit" class="button_send_text button_send_text1">
                    Send
                </button>

            </form>
        </div>
        <?php
}
?>
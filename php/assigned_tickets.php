<?php
session_start();
require_once('fetch_user_tickets.php');
require 'common/common_html.php';
require_once 'department_info.php';
$tickets = fetch_user_assigned_tickets(intval($_SESSION['userId']));
show_common();
?>
<div class="content">
    <form class="search_form" method="POST" action="update_ticket_query.php">
        <div class="client_box">
            <label for="client">Client:</label>
            <input type="text" name="client" id="client" placeholder="Client name">
        </div>
        <div class="status_box">
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="">Any</option>
                <option value="Recived">Received</option>
                <option value="Open">Open</option>
                <option value="Assigned">Assigned</option>
                <option value="Closed">Closed</option>
            </select>
        </div>
        <div class="priority_box">
            <label for="priority">Priority:</label>
            <select name="priority" id="priority">
                <optgroup label="Low">
                    <option value="1" selected>1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </optgroup>

                <optgroup label="Medium">
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </optgroup>

                <optgroup label="High">
                    <option value="1">7</option>
                    <option value="2">8</option>
                    <option value="3">9</option>
                </optgroup>
            </select>
        </div>
        <div class="department_box">
            <label for="department">Department:</label>
            <select name="department" id="department">
                <option value="">Any</option>
                <?php
                $departments = get_all_departs();
                foreach ($departments as $department) {
                    ?>
                    <option value="<?php echo $department->id; ?>"> <?php echo $department->name; ?> </option>
                    <?php
                }
                ?>
            </select>
        </div>


        <div class="button-container" style="flex-basis: 100%; display: flex">
            <button type="submit">Search</button>
        </div>
    </form>
    <button type="button" class="new_ticket_button" onclick="location.href='new_ticket.php'">
        +
    </button>
    <div id="tickets">
        <?php foreach ($tickets as $ticket) {
            display_ticket($ticket);
        } ?>
    </div>
</div>
<?php
close_common(); ?>
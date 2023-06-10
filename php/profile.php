<?php
require 'common/common_html.php';

require_once('classes.php');
require_once('department_info.php');
require_once('fetch_user_tickets.php');
require_once('fetch_user_info.php');

session_start();
$visited_name = $_GET['username'];
if ($_SESSION['username'] === $visited_name) {
    $mypage = true;
} else {
    $mypage = false;
}
$user = fetch_user_by_username($visited_name);
$tickets = fetch_user_tickets(intval($user->id), "", intval(""), intval(""));

if ($user->hierarchy >= 1 && $user->department != null) {
    $assigned_tickets = fetch_user_assigned_tickets(intval($user->id));
    $user_department = get_department_info($user->department);
}


show_common();
?>

<link rel="stylesheet" href="../html_css/profile.css">
<div class="content">
    <div class="profile_preview">
        <div class="profile_header">
            <div class="profile_name">
                <form class="name_form" action="change_name.php" method="POST">
                    <textarea name="name" rows="1" cols="32"><?php echo $user->name; ?></textarea>
                    <?php if ($mypage || $_SESSION['hierarchy'] === 'admin') { ?>
                        <button type="submit">
                            Edit
                        </button>
                    <?php } ?>
                </form>
            </div>
            <?php if (!$mypage && $_SESSION['hierarchy'] === 'admin') { ?>
                <div class="profile_type">
                    <form action="hierarchy_changer.php" method="POST">
                        <input type="radio" name="user_type" value="client" checked="checked">Client
                        <input type="radio" name="user_type" value="agent">Agent
                        <input type="radio" name="user_type" value="admin"> Admin
                        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                        <button type="submit">
                            change
                        </button>
                    </form>
                </div>
            <?php } ?>
            <div class="profile_email">
                <?php if ($mypage || $_SESSION['hierarchy'] === 'admin') { ?>
                    <form action="change_email.php" method="POST">
                        <textarea class="email" name="email"><?php echo $user->email; ?></textarea>
                        <button class="email_buttom" type="submit">
                            change
                        </button>
                    </form>
                <?php } else { ?>
                    <div class="email">
                        <?php echo $user->email; ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php if ($user->hierarchy >= 1) { ?>
            <div class="profile_department">
                Assigned to the departments:
                <?php if ($_SESSION['hierarchy'] === 'admin') { ?>
                    <a href="../department.html">
                        <?php echo ($user_department->name); ?>
                    </a>
                    <button class="password_button" onclick="show_department_changer()">
                        Change department
                    </button>
                    <div id="change_department" style="display: none;">
                        <form action="department_changer.php" method="POST">
                            <select aria-label="new_department" name="department">
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
                            <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                            <button class="email_buttom" type="submit">
                                change
                            </button>
                        </form>
                    </div>
                <?php } else { ?>
                    <a href="../department.html">
                        <?php echo ($user_department->name); ?>
                    </a>
                <?php }; ?>
            </div>
            <?php }; ?>
        
        <?php if ($mypage || $_SESSION['hierarchy'] === 'admin') { ?>
            <button class="password_button" onclick="show_password_changer()">
                Change password button
            </button>
            <div id="change_password" style="display: none;">
                <form action="password_changer.php" method="POST">
                    <label>
                        <input type="text" name="old" placeholder="Old Password">
                    </label>
                    <label>
                        <input type="text" name="new1" placeholder="New Password">
                    </label>
                    <label>
                        <input type="text" name="new2" placeholder="Repeat New Password">
                    </label>
                    <button class="email_buttom" type="submit">
                        change
                    </button>
                </form>
            </div>
        <?php }; ?>
    </div>
    <div class="user_tickets">
        <div class="assigned_tickets">
            <div class="owned_tickets_head">
                <p title="Owned Tickets">Owned Tickets</p>
                <div>
                    <select id="tickets_status_selector" name="status_ticket">
                        <option value="">--Status--</option>
                        <option value="Working">Open</option>
                        <option value="Done">Closed</option>
                    </select>
                </div>
            </div>
            <?php if (!empty($tickets)) {
                foreach ($tickets as $ticket) {
                    display_ticket($ticket);
                }
            }
            ?>
        </div>
        <?php if ($user->hierarchy >= 1) { ?>
            <div class="assigned_tickets">
                <div class="assigned_tickets_head">
                    <p title="Assigned Tickets">Assigned Tickets</p>
                    <div>

                        <select id="tickets_status_selector" name="status_ticket">
                            <option value="">--Status--</option>
                            <option value="Working">Open</option>
                            <option value="Done">Closed</option>
                        </select>
                    </div>
                </div>
                <?php if (!empty($assigned_tickets)) {
                    foreach ($assigned_tickets as $ticket) {
                        display_ticket($ticket);
                    }
                }
                ?>
            </div>
        <?php } ?>
    </div>
</div>
<script>
    function show_password_changer() {
        document.getElementById("change_password").style.display = "block";
    }
    function show_department_changer() {
        document.getElementById("change_department").style.display = "block";
    }
</script>

<?php
close_common();
?>
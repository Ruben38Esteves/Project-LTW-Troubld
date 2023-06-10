<?php
session_start();
function show_common()
{ ?>
    <!DOCTYPE html>
    <html lang="en-US">

    <head>
        <link rel="stylesheet" href="../../html_css/main_page.css">
        <link rel="stylesheet" href="../../html_css/ticket_page.css">
        
        <title>Troubld</title>
    </head>

    <body>
        <div class="wrapper">
            <div class="header">
                Troubld
            </div>
            <div class="nav">
                <div id="profile_photo">
                    <img style="width: 150px; height: 150px;border-radius: 50%;" src="../../../../profile.jpeg" alt="User"
                    onclick="location.href='../../../../php/profile.php?username=<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES); ?>'"
>
                </div>

                <?php if ($_SESSION['hierarchy'] == 'admin'): ?>
                <div>
                    <button type="button" class="button button1" onclick="location.href='../../../../../php/my_tickets.php'">
                        My Tickets
                    </button>

                    <button type="button" class="button button1" onclick="location.href='../../../../../php/admin_page.php'">
                        Management
                    </button>

                    <button type="button" class="button button1" onclick="location.href='../../../../../department.php?id=<?php echo $_SESSION['department_id']; ?>'">
                        My Department
                    </button>

                    <button type="button" class="button button1" onclick="location.href='../../../../php/assigned_tickets.php'">
                        Assigned Tickets
                    </button>

                    <button type="button" class="button button1" onclick="location.href='../../../../../../php/unassigned_tickets.php'">
                        Unassigned tickets
                    </button>
                </div>
                <?php elseif ($_SESSION['hierarchy'] == 'agent'): ?>
                <div>
                    <button type="button" class="button button1" onclick="location.href='../../../../../php/my_tickets.php'">
                        My Tickets
                    </button>

                    <button type="button" class="button button1" onclick="location.href='../../../../../../department.php?id=<?php echo $_SESSION['department_id']; ?>'">
                        My Department
                    </button>

                    <button type="button" class="button button1" onclick="location.href='../../../../../php/assigned_tickets.php'">
                        Assigned Tickets
                    </button>

                    <button type="button" class="button button1" onclick="location.href='../../../../../../php/unassigned_tickets.php'">
                        Unassigned tickets
                    </button>

                </div>
                <?php else: ?>
                <div>
                    <button type="button" class="button button1" onclick="location.href='../../../../../php/my_tickets.php'">
                        My Tickets
                    </button>
                </div>
                <?php endif; ?>

                <button type="button" class="button button2" onclick="location.href='../../../../../../logout.php'">
                    Logout
                </button>
            </div>
<?php } 

function close_common() {
    ?>
    </div>
    </body>
    </html>
<?php
    }
?>
<?php
session_start();
require 'common/common_html.php';
require_once 'department_info.php';
show_common();
?>

<div class="content">
    <div class="header_form">
        <form method="POST" action="ticket_creation.php">
            <input type="text" class="ticket_subject" placeholder="Subject..." name="subject">
            <div>
                <label for="new_department">Department:</label>

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

            </div>
            <div>
                <label for="new_priority">Priority:</label>
                <select aria-label="new_priority" name="priority">
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
            <textarea id="email-message" name="email-message" rows="5" placeholder="Description..."></textarea>
            <button type="submit" class="create_ticket_button button3"
                onclick="location.href='../main_page.html'">+</button>
        </form>
    </div>
</div>

<?php
close_common();
?>
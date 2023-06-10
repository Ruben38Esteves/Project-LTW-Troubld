<?php
    session_start();
    require 'common/common_html.php';
    show_common();
?>

<link rel="stylesheet" href="../../html_css/admin_page.css">
<div class="content">
    <div class="title_box">
        <h2>Admin Page</h2>
    </div>
    <div class="function_box">
        <h3>New Department</h3>
        <form class="department_form" action="" method="post">
            <label for="department_name">Department Name:</label>
            <input type="text" id="department_name" name="department_name" required>
            <label for="description">Description:</label>
            <textarea name="description" required></textarea>
            <button type="button" class="button button4" onclick="createDepartment()">Create Department</button>
        </form>
        <div id="result"></div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function createDepartment() {
        var name = document.getElementById('department_name').value;
        var description = document.getElementsByName('description')[0].value;

        $.ajax({
            url: 'create_department.php',
            type: 'POST',
            data: { name: name, description: description },
            success: function(response) {
                $('#result').html('<p class="success-message">Department created successfully!</p>');
            }
        });
    }
</script>

<?php
    close_common();
?>

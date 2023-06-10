<?php
session_start();
require_once 'common/common_html.php';
show_common();

require_once 'connect.php';
require_once 'classes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketId = $_POST['ticketId'];
    $faqId = $_POST['faqId'];

    if (empty($ticketId) || empty($faqId)) {
        $error = 'Ticket ID and FAQ ID are required.';
    } else {
        associateTicketWithFAQ($ticketId, $faqId);
    }
}

function associateTicketWithFAQ($ticketId, $faqId) {
    $db = getDBConnection();

    $stmt = $db->prepare("INSERT INTO faq_on_ticket (id_faq, id_ticket) VALUES (?, ?)");
    $result = $stmt->execute([$faqId, $ticketId]);

    if ($result) {
        $stmt = $db->prepare("UPDATE ticket SET status_ = 'Closed' WHERE id = ?");
        $result = $stmt->execute([$ticketId]);

        if ($result) {
            echo 'Ticket associated with FAQ question successfully! Ticket status set to "Closed".';
        } else {
            echo 'Error updating ticket status.';
        }
    } else {
        echo 'Error associating ticket with FAQ question.';
    }
}

function generateFAQLink($faqId) {
    return '<a href="faq.php#faq' . $faqId . '">Question ' . $faqId . '</a>';
}

$department_id = $_GET['department_id'];

$db = getDBConnection();

$stmt = $db->prepare("
    SELECT faq.*
    FROM faq
    JOIN faq_on_ticket ON faq.id = faq_on_ticket.id_faq
    JOIN ticket ON ticket.id = faq_on_ticket.id_ticket
    WHERE ticket.department_id = ?
");
$stmt->execute(array($department_id));
$faqList = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<link rel="stylesheet" href="../../html_css/faq_page.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.faq_item').click(function() {
            $(this).find('p').slideToggle();
        });
        $('.faq_item p').hide();
    });
</script>

<div class="content">
    <div class="title_box">
        <div class="faq_title">FAQ</div>
    </div>
    <?php foreach ($faqList as $faq) { ?>
        <div class="faq_box">
            <div class="faq_item">
                <h3 id="faq<?php echo $faq['id']; ?>">Question <?php echo $faq['id']; ?></h3>
                <p><?php echo $faq['answer']; ?></p>
            </div>
        </div>
    <?php } ?>
</div>

<?php
close_common();
?>
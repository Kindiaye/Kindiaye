<?php
//session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config.php'; 

function getSentEmails($user_id) {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT destinataires, sujet, contenu, date_envoi FROM emails_envoyes WHERE utilisateur_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $emails = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $emails;
}

$sent_emails = getSentEmails($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Messages envoyés</title>
    <style>
        h1{
            color: #c6a49a;
        }
        th{
            color: #c6a49a;
        }
    </style>
</head>
<body>
    <h1>Messages envoyés</h1>
    <?php if (count($sent_emails) > 0): ?>
        <table border="1">
            <tr>
                <th>Destinataires</th>
                <th>Sujet</th>
                <th>Contenu</th>
                <th>Date d'envoi</th>
            </tr>
            <?php foreach ($sent_emails as $email): ?>
                <tr>
                    <td><?php echo htmlspecialchars($email['destinataires']); ?></td>
                    <td><?php echo htmlspecialchars($email['sujet']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($email['contenu'])); ?></td>
                    <td><?php echo htmlspecialchars($email['date_envoi']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucun message envoyé.</p>
    <?php endif; ?>
</body>
</html>

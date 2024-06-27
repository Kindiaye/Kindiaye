<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';
require 'vendor/autoload.php'; // Inclure l'autoloader de Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$messageSent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to = $_POST['to'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];
    $from = $_SESSION['user_email']; // This is the email of the logged-in user
    $attachment = isset($_FILES['attachment']) ? $_FILES['attachment'] : null;

    // Split the recipient emails by comma and trim whitespace
    $recipients = array_map('trim', explode(',', $to));

    // Send email to each recipient
    $allEmailsSent = true;
    foreach ($recipients as $recipient) {
        if (!sendEmail($recipient, $subject, $body, $from, 'MKM Maily', 'YOUR_APP_PASSWORD', $attachment)) {
            $allEmailsSent = false;
            $error = 'Erreur lors de l\'envoi du message à ' . $recipient;
        } else {
            // Stocker l'email dans la base de données
            $conn = db_connect();
            $stmt = $conn->prepare("INSERT INTO emails_envoyes (utilisateur_id, destinataires, sujet, contenu, date_envoi) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("isss", $_SESSION['user_id'], $recipient, $subject, $body);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
    }

    if ($allEmailsSent) {
        // Rediriger après envoi du message pour éviter l'envoi multiple sur actualisation
        header('Location: dashboard.php?page=write&success=1');
        exit();
    }
}

function sendEmail($to, $subject, $body, $from, $fromName, $appPassword, $attachment = null) {
    $mail = new PHPMailer(true); // Créer une instance de PHPMailer avec gestion des exceptions
    try {
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->isHTML(true);
        $mail->Username = "mkm.maily@gmail.com"; // Utiliser l'adresse e-mail de l'expéditeur
        $mail->Password = "nfrnaiqzpnqqshhc"; // Utiliser le mot de passe de l'application
        $mail->setFrom($from, $fromName);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->addAddress($to);

        // Ajouter la pièce jointe
        if ($attachment && $attachment['error'] == UPLOAD_ERR_OK) {
            $mail->addAttachment($attachment['tmp_name'], $attachment['name']);
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Afficher les détails de l'exception
        echo 'Erreur lors de l\'envoi du message : ' . $mail->ErrorInfo;
        echo 'Détails de l\'exception : ' . $e->getMessage();
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Envoyer un nouveau message</title>
    <link rel="stylesheet" href="style.css">
    <style>
        h1{
            color: #c6a49a;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Envoyer un nouveau message</h1>
    <?php if (isset($_GET['success'])): ?>
        <p>Message envoyé avec succès.</p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" action="send_message.php" enctype="multipart/form-data">
        <label for="from">De :</label><br>
        <input type="email" id="from" name="from" value="<?php echo $_SESSION['user_email']; ?>" readonly><br>

        <label for="to">À :</label><br>
        <input type="text" id="to" name="to" placeholder="Séparez les adresses par des virgules" required><br>

        <label for="subject">Sujet :</label><br>
        <input type="text" id="subject" name="subject" required><br>

        <label for="body">Message :</label><br>
        <textarea id="body" name="body" required></textarea><br><br>

        <label for="attachment">Pièce jointe :</label><br>
        <input type="file" id="attachment" name="attachment"><br>

        <button type="submit">Envoyer</button>
    </form>
</body>
</html>

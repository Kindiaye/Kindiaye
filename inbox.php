<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';
require 'vendor/autoload.php'; 

use PhpImap\Mailbox;
use PhpImap\Exceptions\ConnectionException;

function getInboxEmails($email, $password) {
    $mailbox = new Mailbox(
        '{imap.gmail.com:993/imap/ssl}INBOX', // IMAP server and mailbox folder
        $email, // Username for the before configured mailbox
        $password, // Password for the before configured username
        __DIR__, // Directory where attachments will be saved (optional)
        'UTF-8' // Server encoding (optional)
    );

    try {
        // Get all emails (messages)
        $mailsIds = $mailbox->searchMailbox('ALL');
        if(!$mailsIds) {
            die('Mailbox is empty');
        }

        // Get the first message and save its attachment(s)
        $emails = [];
        foreach ($mailsIds as $mailId) {
            $email = $mailbox->getMail($mailId);
            $emails[] = $email;
        }

        return $emails;
    } catch (ConnectionException $ex) {
        die('IMAP connection failed: '.$ex->getMessage());
    } catch (Exception $ex) {
        die('An error occurred: '.$ex->getMessage());
    }
}

$userEmail = 'mkm.maily@gmail.com'; // Remplacez par l'email de l'utilisateur
$userPassword = 'nfrnaiqzpnqqshhc'; // Remplacez par le mot de passe de l'application

$emails = getInboxEmails($userEmail, $userPassword);

$conn = db_connect();
$stmt = $conn->prepare("SELECT id, destinataires, sujet, contenu, date_envoi FROM emails_envoyes WHERE utilisateur_id = ? AND status = 'active' ORDER BY date_envoi DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$activeEmails = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Boîte de réception</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a; /* Dark background */
            color: #e0e0e0; /* Light gray text */
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #c6a49a;  /* #ffb6c1 Rose champagne */
            text-align: center;
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #2c2c2c; /* Slightly lighter dark background */
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        li strong {
            color: #c6a49a; /* Rose champagne */
        }

        li p {
            margin: 10px 0 0 0;
        }

        button[type="submit"] {
            background-color: #c6a49a; /* Rose champagne */
            color: #1a1a1a; /* Dark text */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
        }

        button[type="submit"]:hover {
            background-color: #e0e0e0; /* Light gray */
        }
    </style>
</head>
<body>
    <h1>Boîte de réception</h1>
    <form method="post" action="delete.php">
        <?php if (empty($emails) && empty($activeEmails)): ?>
            <p>Aucun message dans la boîte de réception.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($emails as $email): ?>
                    <li>
                        <input type="checkbox" name="selected_emails[]" value="<?php echo $email->id; ?>">
                        <strong>De :</strong> <?php echo htmlspecialchars($email->fromAddress); ?><br>
                        <strong>Sujet :</strong> <?php echo htmlspecialchars($email->subject); ?><br>
                        <strong>Date :</strong> <?php echo htmlspecialchars($email->date); ?><br>
                        <p><?php echo nl2br(htmlspecialchars($email->textPlain)); ?></p>
                    </li>
                <?php endforeach; ?>
                <?php foreach ($activeEmails as $email): ?>
                    <li>
                        <input type="checkbox" name="selected_emails[]" value="<?php echo $email['id']; ?>">
                        <strong>À :</strong> <?php echo htmlspecialchars($email['destinataires']); ?><br>
                        <strong>Sujet :</strong> <?php echo htmlspecialchars($email['sujet']); ?><br>
                        <strong>Date :</strong> <?php echo htmlspecialchars($email['date_envoi']); ?><br>
                        <p><?php echo nl2br(htmlspecialchars($email['contenu'])); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button type="submit">Supprimer les messages sélectionnés</button>
        <?php endif; ?>
    </form>
</body>
</html>

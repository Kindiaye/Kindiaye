<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_emails'])) {
    $selectedEmails = $_POST['selected_emails'];

    $conn = db_connect();
    foreach ($selectedEmails as $emailId) {
        $stmt = $conn->prepare("UPDATE emails_envoyes SET status = 'deleted' WHERE id = ? AND utilisateur_id = ?");
        $stmt->bind_param("ii", $emailId, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    }
    $conn->close();

    header('Location: dashboard.php?page=inbox');
    exit();
}


$conn = db_connect();
$stmt = $conn->prepare("SELECT id, destinataires, sujet, contenu, date_envoi FROM emails_envoyes WHERE utilisateur_id = ? AND status = 'deleted' ORDER BY date_envoi DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$deletedEmails = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messages supprimés</title>
    <link rel="stylesheet" href="style.css">
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
    <h1>Messages supprimés</h1>
    <?php if (empty($deletedEmails)): ?>
        <p>Aucun message supprimé.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($deletedEmails as $email): ?>
                <li>
                    <strong>À :</strong> <?php echo htmlspecialchars($email['destinataires']); ?><br>
                    <strong>Sujet :</strong> <?php echo htmlspecialchars($email['sujet']); ?><br>
                    <strong>Date :</strong> <?php echo htmlspecialchars($email['date_envoi']); ?><br>
                    <p><?php echo nl2br(htmlspecialchars($email['contenu'])); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>


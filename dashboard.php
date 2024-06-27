<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifiez si l'utilisateur est connecté, sinon redirigez vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="styles.css"> 
    <style>
        
body {
    font-family: Arial, sans-serif;
    background-color: #1a1a1a; /* Dark background */
    color: #e0e0e0; /* Light gray text */
    margin: 0;
    padding: 0;
}

        .sidebar {
            width: 250px;
            background-color: #c6a49a;
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            color: #fff;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
        .content iframe {
            width: 100%;
            height: calc(100vh - 40px);
            border: none;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><Menu>Tableau de bord</Menu></h2>
        <a href="dashboard.php?page=send">◘Écrire un nouveau message</a>
        <a href="dashboard.php?page=inbox">◘Messages reçus</a>
        <a href="dashboard.php?page=sent">◘Messages envoyés</a>
        <a href="dashboard.php?page=deleted">◘Messages supprimés</a>
    </div>
    <div class="content">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'write';
        $pagePath = '';

        switch ($page) {
            case 'write':
                $pagePath = 'send_message.php';
                break;
            case 'inbox':
                $pagePath = 'inbox.php';
                break;
            case 'sent':
                $pagePath = 'sent.php';
                break;
            case 'deleted':
                $pagePath = 'delete.php';
                break;
            default:
                $pagePath = 'send_message.php';
        }

        if (file_exists($pagePath)) {
            include $pagePath;
        } else {
            echo '<p>Page non trouvée.</p>';
        }
        ?>
    </div>
</body>
</html>

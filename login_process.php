<?php
session_start();
include 'config.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifiez les informations d'identification de l'utilisateur
    $conn = db_connect();
    $stmt = $conn->prepare('SELECT * FROM utilisateurs WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_password'] = $password; // Assurez-vous de stocker le mot de passe haché
     header('Location: dashboard.php');
    } else {
        // Connexion échouée
        echo 'Invalid email or password';
    }
}
?>

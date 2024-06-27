<?php
session_start();
require_once 'config.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]); 
    
    $link = db_connect();
    
    $sql = "SELECT id, nom, password FROM utilisateurs WHERE email = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $email);
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_bind_result($stmt, $id, $nom, $hashed_password);
                if(mysqli_stmt_fetch($stmt)){
                    if(password_verify($password, $hashed_password)){
                        session_start();
                        $_SESSION['id'] = $id;
                        $_SESSION['nom'] = $nom;
                        header('Location: dashboard.php');
                    } else {
                        echo "Le mot de passe que vous avez entré est incorrect.";
                    }
                }
            } else {
                echo "Aucun compte trouvé avec cet email.";
            }
        } else {
            echo "Erreur lors de la vérification des identifiants.";
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
 <style>
    
body {
    font-family: Arial, sans-serif;
    background-color: #1a1a1a; /* Dark background */
    color: #e0e0e0; /* Light gray text */
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    margin: auto;
    overflow: hidden;
    background-color: #2c2c2c; /* Slightly lighter dark background */
    padding: 20px;
    border: 1px solid #444;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Dark shadow */
}

h1 {
    color:  #c6a49a; /* Rose champagne */
    text-align: center;
}
 </style>
</head>
<body>
    <div class="container">
    <h1>BIENVENUE SUR MKM Mail☻</h1>
        <h1 class="conn">Connectez-vous</h1>
    <form action="login_process.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <button class="sub" type="submit" >Se connecter</button>
    </form>
    </div>
</body>
</html>


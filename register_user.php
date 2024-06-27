<?php
require_once 'config.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nom = trim($_POST["nom"]);
    $email = trim($_POST["email"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT); // Hachage du mot de passe
    $app_password = trim($_POST["app_password"]); // Mot de passe d'application non haché car il est utilisé directement pour l'authentification SMTP

    $link = db_connect();
    
    $sql = "INSERT INTO utilisateurs (nom, email, password, app_password) VALUES (?, ?, ?, ?)";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "ssss", $nom, $email, $password, $app_password);
        if(mysqli_stmt_execute($stmt)){
            header("Location: login.php");
        } else {
            echo "Quelque chose a mal tourné. Veuillez réessayer plus tard.";
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #1a1a1a; 
    color:  #e0e0e0;
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
        <h1>Inscription</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            Nom: <input type="text" name="nom" required><br>
            Email: <input type="email" name="email" required><br>
            Mot de passe: <input type="password" name="password" required><br><br>
            Mot de passe d'application (nfrnaiqzpnqqshhc): <input type="password" name="app_password" required><br><br>
            <input type="submit" value="S'inscrire">
        </form>
    </div>
</body>
</html>

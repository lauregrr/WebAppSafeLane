<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/connexion_style.css">
    <link rel="icon" type="image/png" href="/assets/images/safelane.png" sizes="32x32 64x64">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <div id='image'>
        <img src="/assets/images/safelane.png" height=200px>
    </div>
    <div class="container">
        <h1>Connexion</h1>
        <form action="process_login.php" method="POST">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="Se connecter">
        </form>
    </div>

    <script src="assets/connexion.js"></script>
</body>
</html>

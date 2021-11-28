<?php
    session_start();
    @$nbrInscrit = "";
    if($_SESSION["droits"]!="administrateur"){
        header("location:profil.php");
        exit();
    } 

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Page de connexion PPE">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>

            <div class="form-container">
                <h1>Panneau d'administration</h1>
                <p>Insérer utilisateurs</p>
            </div>
               
    </body>
</html>
<?php
   session_start();
   @$email=$_POST["email"];
   //md5 correspond au hash du mot de passe, on vient comparer les hash
   @$pass=md5($_POST["pass"]);
   @$valider=$_POST["valider"];
   $erreur="";
   if(isset($valider)){
      include("connexion.php");
      $sel=$pdo->prepare("select * from utilisateurs where mail=? and pass=? limit 1");
      $sel->execute(array($email,$pass));
      //fetchAll() nous permet de récupérer la réponse de la requête dans un tableau car elle n'est pas lisible autrement
      $tab=$sel->fetchAll();
      //Si la requête nous renvoie du contenu, c'est que l'utilisateur et le mot de passe sont correct
      if(count($tab)>0){
          //On récupère nom,prénom et on donne l'autorisation puis on redirige sur profil.php
         $_SESSION["prenom"]=ucfirst(strtolower($tab[0]["Prenom"]));
         $_SESSION["nom"]=ucfirst(strtolower($tab[0]["Nom"]));
         $_SESSION["pseudo"]=ucfirst(strtolower($tab[0]["Pseudo"]));
         $_SESSION["birthDate"]=ucfirst(strtolower($tab[0]["DateNaissance"]));
         $_SESSION["inscriptionDate"]=ucfirst(strtolower($tab[0]["DateInscription"]));
         $_SESSION["email"]=$tab[0]["Mail"];     
         $_SESSION["autoriser"]="oui";    
         include("connexion.php");
         //On récupère les droit d'admin une fois qu'on a connaissance de l'existence du compte.
         $sel=$pdo->prepare("select libelle from utilisateurs inner join droits on utilisateurs.droitId = droits.id where utilisateurs.Mail=? and utilisateurs.Pass=?");
         $sel->execute(array($email,$pass));
         $tab=$sel->fetchAll();
         $_SESSION["droits"]=$tab[0]["libelle"]; 
         header("location:profil.php");

      }
      else{

          $erreur="Mauvais mail ou mot de passe!";
      }


   }
?> 

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <meta name="description" content="Page de connexion PPE">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="form-container">
            <h1>Identification</h1>
            <form name="login" method="post" action="">
                <input type="text" name="email" placeholder="Adresse mail"/>
                <input type="password" name="pass" placeholder="Mot de passe"/>
                <input type="submit" name="valider" value="LOGIN"/>
            </form>    
            <p>Vous n'avez pas de compte ? <a href="register.php">Créez-en un !</a></p>
        </div>
    </body>
</html>
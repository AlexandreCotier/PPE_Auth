<?php
   session_start();
   //On stock l'ensemble des champs de saisies dans des variables respectives
   @$nom=$_POST["nom"];
   @$prenom=$_POST["prenom"];
   @$email=$_POST["email"];
   @$pseudo=$_POST["pseudo"];
   @$pass=$_POST["pass"];  
   @$birthDate=strtotime($_POST["birthDate"]);  //On converti la date de naissance dans un format que MySQL reconnait
   @$birthDate=date('Y-m-d H:i:s', $birthDate);
   @$repass=$_POST["repass"];
   @$valider=$_POST["valider"];
   $erreur="";
   if(isset($valider)){
      if(empty($nom)) $erreur="Nom laissé vide!";
      elseif(empty($prenom)) $erreur="Prénom laissé vide!";
      elseif(empty($nom)) $erreur="Nom laissé vide!";
      elseif(empty($email)) $erreur="Email laissé vide!";
      elseif(empty($pseudo)) $erreur="Pseudo laissé vide!";
      elseif(empty($birthDate)) $erreur="Date de naissance laissé vide!";
      elseif(empty($pass)) $erreur="Mot de passe laissé vide!";
      elseif($pass!=$repass) $erreur="Mots de passe non identiques!";
      else{         
         include("connexion.php"); //On appel notre objet de connexion si tous nos champs sont correctements remplies
         $sel=$pdo->prepare("select Nom from utilisateurs where Mail=? OR Pseudo=?");
         $sel->execute(array($email));
         $tab=$sel->fetchAll();
         if(count($tab)>0)
            $erreur="Cet email ou cet identifiant existe déjà!";
         else{
            $ins=$pdo->prepare("insert into utilisateurs(prenom,nom,mail,pseudo,DateNaissance,pass) values(?,?,?,?,?,?)");
            if($ins->execute(array($prenom,$nom,$email,$pseudo,$birthDate,md5($pass)))) //On balance toutes les données et on hach notre mot de passe
               header("location:index.php"); //On redirige sur la page de connexion
         }   
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
            <h1>S'inscrire</h1>
            <form name="login" method="post" action="">
                <input type="text" name="prenom" placeholder="Prénom"/>
                <input type="text" name="nom" placeholder="Nom"/>
                <input type="text" name="email" placeholder="Adresse mail"/>
                <input type="text" name="pseudo" placeholder="Pseudo">
                <input type="date" name="birthDate" placeholder="Date de naissance">
                <input type="password" name="pass" placeholder="Mot de passe"/>
                <input type="password" name="repass" placeholder="Confirmer mot de passe"/>
                <input type="submit" name="valider" value="S'enregister"/>
            </form>    
            <div class="erreur">
                <?php echo $erreur //Si erreur, on l'affiche ?> 
            </div>
            <p>Vous avez déjà un compte ? <a href="index.php">Connectez-vous !</a></p>
        </div>
    </body>
</html>
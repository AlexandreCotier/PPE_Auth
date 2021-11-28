<?php
    session_start();
    @$nbrInscrit = "";
    if($_SESSION["autoriser"]!="oui"){
        header("location:index.php");
        exit();
    } 
    include("connexion.php");
    $sel=$pdo->prepare("select count(*) as nombre from utilisateurs");
    $sel->execute();
    $tab=$sel->fetchAll();
    if(count($tab)>0){
        //On récupère nom,prénom et on donne l'autorisation puis on redirige sur profil.php
        $nbrInscrit=$tab[0]["nombre"];

    }
    @$pass=$_POST["pass"];
    @$newPass=$_POST["newPass"];
    @$newRePass=$_POST["newRePass"];
    @$email=$_SESSION["email"];
    @$valider=$_POST['valider'];
    $erreur="";
    $validation="";
    if(isset($valider)){
        if(($newPass == $newRePass) && (!empty($pass))){
            include("connexion.php");
            $sel=$pdo->prepare("select Prenom from utilisateurs where Mail=? and Pass=? limit 1");
            $sel->execute(array($email,md5($pass)));
            $tab=$sel->fetchAll();
            if(count($tab)>0){
                $upd=$pdo->prepare("UPDATE utilisateurs SET Pass=? WHERE Mail=? and Pass=?");
                $upd->execute(array(md5($newPass),$email,md5($pass)));
                $validation="mot de passe changé!"; 
            }
            else{
                $erreur="Votre ancien mot de passe n'est pas correct";
            }
        }else{
            $erreur="Vos mot de passes ne correspondent pas";
        }
    }
    @$supprimer=$_POST["supprimer"];
    if(isset($supprimer)){
        include("connexion.php");
        $del=$pdo->prepare("delete from utilisateurs where Mail=?");
        $del->execute(array($_SESSION["email"]));        
        session_destroy();
        header("location:index.php");
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
                <h1>Profil</h1>
                <div class="infos">
                    <ul>
                        <li>Nom : <strong><?php echo $_SESSION["nom"]?></strong></li>
                        <li>Prénom : <strong><?php echo $_SESSION["prenom"]?></strong></li>
                        <li>Pseudo : <strong><?php echo $_SESSION["pseudo"]?></strong></li>
                        <li>Email : <strong><?php echo $_SESSION["email"]?></strong></li>
                        <li>Data de naissance : <strong><?php echo $_SESSION["birthDate"]?></strong></li>
                        <li>Inscrit depuis : <strong><?php echo $_SESSION["inscriptionDate"]?></strong></li>
                        <li>Status : <strong><?php echo $_SESSION["droits"]?></strong></li>
                    </ul>
                </div>
                <button><a href="deconnexion.php">Se déconnecter</a></button>
            </div>
                <div class="form-container">
                    <p>Utilisateurs enregistrés :</p>
                    <?php
                        $sel=$pdo->prepare("select Pseudo from utilisateurs order by Pseudo desc");
                        $sel->execute();
                        while($pseudo = $sel->fetch()){
                            ?>
                            <a><?php echo $pseudo["Pseudo"]?>,</a>
                            <?php
                        }
                        ?>

                    <p>Utilisateurs inscrits : <strong><?php echo $nbrInscrit ?></strong></p>
                </div>

                <div class="form-container">
                    <form name="modifyPassword" method="post" action="">
                        <input type="password" name="pass" placeholder="Ancien mot de passe"/>
                        <input type="password" name="newPass" placeholder="Nouveau mot de passe"/>
                        <input type="password" name="newRePass" placeholder="Confirmer mot de passe"/>
                        <input type="submit" name="valider" value="Modifier mot de passe"/>
                    </form>
                    <div class="erreur"><?php echo $erreur ?></div>
                    <div class="valid"><?php echo $validation ?></div>
                    <form name="supprCompte" method="post" action="">
                        <input type="submit" name="supprimer" value="Supprimer mon compte"/>
                    </form>
                    <?php 
                    if($_SESSION["droits"] == "administrateur"){
                        ?> <button ><a href="panneauAdmin.php">Accéder au panneau d'administration</a></button> <?php
                    }

                    ?>
                </div>

    </body>
</html>

<?php
   //Ici on initialise l'objet permettant la connexion à la base de données.
   try{
      $pdo=new PDO("mysql:host=localhost;dbname=ppe","utilisateur","XjuC7xiTt8o8FipG");
   }
   catch(PDOException $e){
      echo $e->getMessage();
   }
?> 
<?php 
//==================================OBJECTIFS==================================//
$objectif_user_req = $bdd->prepare('SELECT * FROM `objectifs` WHERE `idmbrs` = ?');
$objectif_user_req->execute(array($userid));
$objectif_user = $objectif_user_req->fetch();

//POUR AFFICHAGE PLUS BAS//
$objectif_user_req2 = $bdd->prepare('SELECT * FROM `objectifs` WHERE `idmbrs` = ? LIMIT 5');
$objectif_user_req2->execute(array($userid));
if(isset($_POST['objectifpersonnel-submit'])){
   $nomobjectif = htmlspecialchars($_POST['nomobjectif']);
   $descriptionobjectif = htmlspecialchars($_POST['descriptionobjectif']);
   
      if(!empty($nomobjectif) AND !empty($descriptionobjectif)){
         $objectifreq = $bdd->prepare('INSERT INTO `objectifs`(`idmbrs`, `nom`, `info`) SELECT :idmbrs, :nom, :info FROM DUAL  
         WHERE NOT EXISTS (SELECT * FROM `objectifs` WHERE `idmbrs` = :idmbrs AND `nom` = :nom AND `info` = :info)');
         $objectifreq->execute(array('idmbrs' => $userid,'nom' => $nomobjectif,'info' => $descriptionobjectif));
         header('Location: index.php?id='.$_SESSION['idmbrs']);
      }
      else{
         $erreurobjectif = "Tous les champs doivent être complétés !";
      }
   }
   
   if(isset($_POST['delete-objectif'])){
      $id_delete_objectif = htmlspecialchars($_POST['delete-objectif']);
      $reqsuppressionobjectif = $bdd->prepare('DELETE FROM `objectifs` WHERE `id` = ?');
      $reqsuppressionobjectif->execute(array($id_delete_objectif));
      header('Location: index.php?id='.$_SESSION['idmbrs']);

   }
//===============================FIN OBJECTIFS==============================//
?>
<?php 
//==================================GROUPE==================================//

//REQUETE SQL POUR GROUPE & ADMIN DU GROUPE //
$infogroupe_user = $bdd->prepare('SELECT * FROM `groupe` WHERE `groupe_id` = ?');
$infogroupe_user->execute(array($groupe_user));
$groupe_info_user = $infogroupe_user->fetch();
@$groupe_id_user = $groupe_info_user['groupe_id'];

// VERIF USER DANS UN GROUPE OU PAS //

if($groupe_user != 0){  

   //RECUPERATION DONNEES BDD DE TOUT LES UTILISATEURS D'UN GROUPE//
   $groupe_user = $_SESSION['groupe'];
   $reqg = $bdd->prepare('SELECT * FROM `membres` WHERE `groupe`= ?');
   $reqg->execute(array($groupe_user));
   $grpinfo = $reqg->fetchall();
   @$name_groupe_user1 = $grpinfo[0]['name'];
   @$name_groupe_user2 = $grpinfo[1]['name'];
   @$name_groupe_user3 = $grpinfo[2]['name'];
   @$name_groupe_user4 = $grpinfo[3]['name'];
   @$nombre_seance_semaine_user1 = $grpinfo[0]['nombre_seance_semaine'];
   @$nombre_seance_semaine_user2 = $grpinfo[1]['nombre_seance_semaine'];
   @$nombre_seance_semaine_user3 = $grpinfo[2]['nombre_seance_semaine'];
   @$nombre_seance_semaine_user4 = $grpinfo[3]['nombre_seance_semaine'];
   

  //VERIF SI USER EST ADMIN //
  if($groupe_info_user['groupe_id_user_admin'] == $userid){  
  $groupe_display = "Gérer votre groupe";
  $groupe_display_under = "Paramétrer votre groupre";
  $groupe_href = "groupe.php?id=$userid";
  }
  else{
    $groupe_display = "Voir votre groupe";
    $groupe_display_under = "Voir les informations du groupe";
    $groupe_href = "groupe.php?id=$userid";
  }
}
else{
  $groupe_display = "Créer votre groupe";
  $groupe_display_under = "Créer un groupe avec vos amis / famille";
  $groupe_href = "creationgroupe.php?id=$userid";

}

//BOUTTON POUR QUITTER SON GROUPE //
if(isset($_POST['quite_groupe'])){
  if($_POST['quite_groupe'] == 1){
    if(@$groupe_info_user['groupe_id_user_admin'] == $userid){  
      $erreur = "En quittant le groupe vous supprimerez celui-ci !";
      $erreur_info ="Button de vérif a faire ";
      }
      else{
        //REQUETE SQL POUR GROUPE_QUITE/
        $infogroupe3 = $bdd->prepare('SELECT * FROM `groupe` WHERE `groupe_id` = ?');
        $infogroupe3->execute(array($groupe_id_user));
        $groupe_info3 = $infogroupe3->fetch();

        //ON RETIRE 1 USER AU TOTAL DU GROUPE //
        @$groupe_total_user_moins_1 = $groupe_info3['groupe_total_user']-1;

        //SUPPRIMER ID GROUPE DANS BDD MEMBRE //
        $id_groupe_user = $bdd->prepare('UPDATE `membres` SET `groupe` = NULL WHERE `idmbrs` = ? ');
        $id_groupe_user->execute(array($userid));

        //VERIFICATION SI LE GROUPE N'EST PAS INFERIEUR OU EGALE A 1 //
        if($groupe_total_user_moins_1 <= 1){
          $deletegroupe = $bdd->prepare("DELETE FROM `groupe` WHERE `groupe_id` = ?");
          $deletegroupe->execute(array($groupe_id_user));
        }
        else{
        // ON ENLEVE 1 USER DANS LA BDD //
        $modifgroupe = $bdd->prepare("UPDATE `groupe` SET `groupe_total_user` = ? WHERE `groupe_id` = ?");
        $modifgroupe->execute(array($groupe_total_user_moins_1, $groupe_id_user));
        }
      }
  }
}

// REQUETE SQL POUR INVITATIONS DE GROUPE //
$groupe_request = $bdd->prepare('SELECT * FROM `groupe_user_pending` WHERE `id_user` = ?');
$groupe_request->execute(array($userid));
$nbre_dmd_groupe = $groupe_request->rowCount();      

// SI BOUTTON ACCEPT APPUYE //
if(isset($_POST['groupe_accept'])){
  $id_groupe_via_button = htmlspecialchars($_POST['groupe_accept']);

  if($groupe_user != 0){ //USER EST DANS UN GROUPE //
    $erreur = "Vous faite déja partis d'un groupe !";
    $erreur_info = "Merci de quitter votre groupe avant de vouloir en rejoindre un autre !";
  }
  else{ //USER PAS DANS UN GROUPE
    $user_add_to_groupe = $bdd->prepare('UPDATE `membres` SET `groupe`= ? WHERE `idmbrs` = ?');
    $user_add_to_groupe->execute(array($id_groupe_via_button, $userid));
  }
  $user_delete_pending = $bdd->prepare('DELETE FROM `groupe_user_pending` WHERE `id_user` = ? AND `id_groupe` = ?');
  $user_delete_pending->execute(array($userid, $id_groupe_via_button));


}
elseif(isset($_POST['groupe_deny'])){// SI BOUTTON DENY APPUYE //
  $id_groupe_via_button = htmlspecialchars( $_POST['groupe_deny']);//SECURITE ANTI CODE HTML //

  //REQUETE SQL POUR GROUPE_DENY /
  $infogroupe2 = $bdd->prepare('SELECT * FROM `groupe` WHERE `groupe_id` = ?');
  $infogroupe2->execute(array($id_groupe_via_button));
  $groupe_info2 = $infogroupe2->fetch();

  //ON RETIRE 1 USER AU TOTAL DU GROUPE //
  $groupe_total_user_moins_1 = $groupe_info2['groupe_total_user']-1;

  //VERIFICATION SI LE GROUPE N'EST PAS INFERIEUR OU EGALE A 1 //
  if($groupe_total_user_moins_1 <= 1){
    $deletegroupe = $bdd->prepare("DELETE FROM `groupe` WHERE `groupe_id`=");
    $deletegroupe->execute(array($id_groupe_via_button));
  }
  else{
  // ON ENLEVE 1 USER DANS LA BDD //
  $modifgroupe = $bdd->prepare("UPDATE `groupe` SET `groupe_total_user`= ? WHERE `groupe_id` = ?");
  $modifgroupe->execute(array($groupe_total_user_moins_1, $id_groupe_via_button));
  }
}
//================================FIN GROUPE================================//

?>
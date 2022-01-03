<!-- 
tps = temps par semaines de sports => temps_semaines_sports
tpms = temps par mois de sports => temps_mois_sports
nseps = nombre de seance par semaine => nombre_seance_semaine
nsepms = nombre de seance par mois => nombre_seance_mois
nset = nombre de seance totale => nombre_seance_total
-->
<?php
//DEBUT DE SESSION //
session_start();
//VERIFICATION SESSION OK //
if($_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { // EXPIRATION ? //
  //REDIRECTION A LA PAGE DE CONNEXIO //
  $_SESSION['deco'] = "Votre session a expiré !";
  header('Location: deconnexion.php');
} else{ // SI PAS EXPIRER //
  $_SESSION['last_activity'] = time(); // ACTUALISATION DE LA DERNIERE ACTIVITE //
}
if(empty($_SESSION['connection']) && $_SESSION['connection'] != true ){
  $_SESSION['deco'] = "Votre session a expiré !";
  header('Location: deconnexion.php');
}
if($_SESSION['idmbrs'] != $_GET['id']){ // ID URL CORRESPOND ID SESSION //
  header('Location: index.php?id='.$_SESSION['idmbrs']);
}
?>
<!-- INCLUDE POUR TITLE + CONNEXION BDD -->
<?php
    include('include.php');    
?>
<?php 
// RECUPERATION ID DANS URL + VERIFICATION BDD + RECUPERATION INFORMATION //
if(isset($_GET['id']) AND $_GET['id'] > 0) { 

  //ON PASSE L'ID DANS UNE VARIABLE //
  $userid = $_GET['id']; 
  
  //REQUETE SQL //
  $requser = $bdd->prepare('SELECT * FROM membres WHERE idmbrs = ?');
  $requser->execute(array($userid));
  $userinfo = $requser->fetch();

  // VARIABLES UTILISATEURS //
  $name = $userinfo['name']; //PASSAGE DU NOM DE L'UTILISATEUR DANS VARIABLE $name //
  $tps = $userinfo['temps_semaines_sports']; //PASSAGE DU TEMPS PAR SEMAINE DANS VARIABLE $tps //
  $tpms = $userinfo['temps_mois_sports']; //PASSAGE DU TEMPS PAR MOIS DE SPORT DANS VARIABLE $tpms //
  $nseps = $userinfo['nombre_seance_semaine']; //PASSAGE DU NOMBRE DE SEANCES PAR SEMAINE DANS VARIABLE $nseps //
  $nsepms = $userinfo['nombre_seance_mois']; //PASSAGE DU NOMBRE DE SEANCES PAR MOIS DANS VARIABLE $nsepms //
  $nset = $userinfo['nombre_seance_total']; //PASSAGE DU NOMBRE DE SEANCE TOTALE DANS VARIABLE $nset //
  $groupe_user = $userinfo['groupe']; //PASSAGE DE L'ID DU GROUPE DU USER DANS VARIABLE $groupe_user //
  //$userid = ID DE L'UTILISATEUR CONNECTER //

  //REQUETE SQL AMIS USER //
  $requser2 = $bdd->prepare('SELECT * FROM friends WHERE (id_receveur = ? OR id_demandeur = ?)');
  $requser2->execute(array($userid,$userid));
  $userinfo2 = $requser2->fetch();

  //REQUETE DEMANDE D'AMIS //
  $amis_user_pending = $bdd->prepare('SELECT * FROM `friends` WHERE `statut` = 1 AND `id_receveur` = ?');
  $amis_user_pending->execute(array($userid));

  $nbre_dmd_amis = $amis_user_pending->rowCount();      

  }
  else{
    header("Location: ../connexion.php"); //REDIRECTION VERS connexion.php //
  }
?>
<?php 
//RECUPERATION ID & SC & EX DEPUIS L'URL // 
   if(isset($_GET['sc']) AND $_GET['sc'] > 0) {
      if(isset($_GET['ex']) AND $_GET['ex'] > 0) {

         $sc_id = $_GET['sc']; 
         $exercice_id = $_GET['ex']; 

         //VERIFICATION PAS DE MODIFICATION DES VARIABLES DANS L'URL //
         if($_SESSION['seance_en_cours'] != $sc_id OR $_SESSION['exercice_en_cours'] != $exercice_id){
            header('Location: index.php?id='.$userid.'&sc='.$_SESSION['seance_en_cours'].'&ex='.$_SESSION['exercice_en_cours'].'');
         }
      }
      else{
         //REDIRECTION VERS index.php //   
      }
   }      
   else{
      //REDIRECTION VERS index.php //   
   }
?>
<?php
// CHOIX DE LA SEANCE
if(isset($_POST['seance-nom-check'])){
   $recuperation_nomseance_difficulte = explode(";", $_POST['seance-nom-check']);
   $_SESSION['seance_choisis'] = $recuperation_nomseance_difficulte[0];// 0 = NOM DE LA SEANCE //
   //REQUETE POUR ID SEANCE //
   $reqs = $bdd->prepare('SELECT * FROM seances WHERE nom = ?');
   $reqs->execute(array($_SESSION['seance_choisis']));
   $scinfo = $reqs->fetch();
   $_SESSION['id_seance'] = $scinfo['ids'];

}
// EASY MEDIUM EXPERT //
if(@$recuperation_nomseance_difficulte[1] == 'easy'){ // 1 = NIVEAU DE DIFFICULTE //
   $_SESSION['difficulty'] = 'easy';
   $_SESSION['active_class_easy'] = 'active';
   $_SESSION['active_class_medium'] = '';
   $_SESSION['active_class_expert'] = '';
   }
   elseif(@$recuperation_nomseance_difficulte[1] == 'medium'){
   $_SESSION['difficulty'] = 'medium';
   $_SESSION['active_class_easy'] = '';
   $_SESSION['active_class_medium'] = 'active'; 
   $_SESSION['active_class_expert'] = '';  
   }
   elseif(@$recuperation_nomseance_difficulte[1] == 'expert'){
   $_SESSION['difficulty'] = 'expert';
   $_SESSION['active_class_easy'] = '';
   $_SESSION['active_class_medium'] = ''; 
   $_SESSION['active_class_expert'] = 'active';
   }
//==================================//////==================================//


//==================================EASY==================================//
    // RECUPERATION INFORMATION SEANCE PERSONNALISER EASY //
    if(isset($_POST['seance_info_send_easy'])) { //RECUPERATION DES INFORMATIONS DU FORMULAIRES SUR INFO SEANCE LORS DE L'APPUIE DU BOUTON "seance_info_send_easy" //
      $minutes = $_POST['minutes'];
      $secondes = $_POST['secondes'];  
      $repetition = $_POST['repetition']; 
      $minutes_pause = $_POST['minutes_pause'];
      $secondes_pause = $_POST['secondes_pause']; 
      //VERIFICATION QUE TOUTES LES CONDITIONS SONT RESPECTER //  
      if(!empty($minutes) AND !empty($secondes) AND !empty($repetition)){ 
        if($minutes <= 60){ //VERIFICATION QUE LES MINUTES DE PAUSE NE DEPASSE PAS 20 MIN //
          if($minutes > 0){ //VERIFICATION QUE LE TEMPS DE PAUSE N'EST PAS NEGATIF //
            if($secondes <= 59){ // VERIFICATION QUE LES SECONDES DE PAUSE NE DEPASSE PAS 59 SEC //
              if($repetition <= 50 AND $repetition != 0){
                $_SESSION['minutes'] = $minutes;
                $_SESSION['secondes'] = $secondes;
                $_SESSION['repetition'] = $repetition;
                $_SESSION['minutes_pause'] = $minutes_pause;
                $_SESSION['secondes_pause'] = $secondes_pause;
                $id_seance = $_SESSION['id_seance'];
                header("Location: sport.php?id=$userid&sc=$id_seance&ex=1"); 
              }
              else{
                $erreur = "Vous ne pouvez pas selectionner 0 répétition ! ";
              }
            }
            else{
              $erreur = "Vous ne pouvez pas dépasser 59 secondes de pause !";
            }
          }
          else{
            $erreur = "Vous ne pouvez pas avoir un temps de pause négatif !";
          } 
        }
        else{
          $erreur = "Vous ne pouvez pas dépasser 60 minutes de pause !";
        }
      }
      else{
        $erreur = "Vous devez remplir tout les champs !";
      }
    }
//==================================//////==================================//


//==================================MEDIUM==================================//

  // RECUPERATION INFORMATION SEANCE PERSONNALISER MEDIUM DANS ARRAY //
  if(isset($_POST['repetition_exercice'])) 
  {
    $nombre_repetition = $_POST['repetition_exercice'];
    $total_repetition = count($nombre_repetition);
    for($i=0; $i<$total_repetition; $i++)
    {
      $int_nombre_repetiton = (int)$nombre_repetition[$i]; //CONVERSION EN INT //
      if($int_nombre_repetiton != 0){ //VERIFICATION VALEUR DE REPETITION NON NUL //
        if($int_nombre_repetiton > 0){ // VERIFICATION VALEUR SUPERIEUR A 0 //
          $repetition[] = (int)$nombre_repetition[$i]; //AJOUT DANS LE TABLEAU //
        }
        else{
          $erreur = "Vous ne pouvez pas avoir un nombre de répétition négatif !";
          //ATTENTION EN CAS D'ERREUR UNE VALEUR PAR DEFAULT EST AJOUTE (10) //
          $erreur_info = "La valeur par defaut a été enregistré ! (10)";
          $repetition[] = (int)10;
        }
      }
      else{
        $erreur = "Le nombre de répétition ne peux pas étre égale à 0 !";
        //ATTENTION EN CAS D'ERREUR UNE VALEUR PAR DEFAULT EST AJOUTE (10) //
        $erreur_info = "La valeur par defaut a été enregistré ! (10)";
        $repetition[] = (int)10;
      }            
    }
  }

  //RECUPERATION INFORMATION MINUTES POUR CHAQUE EXERCICE DANS ARRAY //
  if(isset($_POST['minutes_exercice']))
  {
    $nombre_minutes = $_POST['minutes_exercice'];
    $totalminutes = count($nombre_minutes);
    for($i=0; $i<$totalminutes; $i++)
    {
      $int_nombre_minutes = (int)$nombre_minutes[$i]; //CONVERSION EN INT //
      if($int_nombre_minutes <= 60){ //VERIFICATION QUE LES MINUTES NE DEPASSE PAS 60 MIN //
        if($int_nombre_minutes >= 0){ //VERIFICATION QUE LES MINUTES NE SONT PAS NEGATIVE //
          $minutes[] = (int)$nombre_minutes[$i];
        }
        else{
          $erreur = "Vous ne pouvez pas avoir une durée d'exercice négatif !";
          //ATTENTION EN CAS D'ERREUR UNE VALEUR PAR DEFAULT EST AJOUTE (1) //
          $erreur_info = "La valeur par defaut a été enregistré ! (1)";
          $minutes[] = (int)1;
        } 
      }
      else{
        $erreur = "La durée des exercices ne peux pas dépassé 60 minutes !";
        //ATTENTION EN CAS D'ERREUR UNE VALEUR PAR DEFAULT EST AJOUTE (1) //
        $erreur_info = "La valeur par defaut a été enregistré ! (1)";
        $minutes[] = (int)1;
      }
    }
  }

  //RECUPERATION INFORMATION SECONDES POUR CHAQUE EXERCICE DANS ARRAY //
  if(isset($_POST['secondes_exercice']))
  {
    $nombre_secondes = $_POST['secondes_exercice'];
    $totalsecondes = count($nombre_secondes);
    for($i=0; $i<$totalsecondes; $i++)
    {
      $int_nombre_secondes = (int)$nombre_secondes[$i];//CONVERSION EN INT //
      if($int_nombre_secondes <= 59){ // VERIFICATION QUE LES SECONDES NE DEPASSE PAS 59 SEC //
        if($int_nombre_secondes >= 0){
        $secondes[] = (int)$nombre_secondes[$i];
        }
        else{
          $erreur = "Vous ne pouvez pas avoir un nombre de secondes négatif !";
          //ATTENTION EN CAS D'ERREUR UNE VALEUR PAR DEFAULT EST AJOUTE (30) //
          $erreur_info = "La valeur par defaut a été enregistré ! (30)";
          $secondes[] = (int)30;
        }
      }
      else{
        $erreur = "Vous ne pouvez pas dépasser 59 secondes de pause !";
        //ATTENTION EN CAS D'ERREUR UNE VALEUR PAR DEFAULT EST AJOUTE (30) //
        $erreur_info = "La valeur par defaut a été enregistré ! (30)";
        $secondes[] = (int)30;
      }
    }
  }
  //PASSAGE DES ARRAY DE DONNEES CHOISI DANS SESSION
  if(isset($_POST['seance_info_send_medium'])){
    $_SESSION['minutes_pause'] = $_POST['minutes_pause'];
    $_SESSION['secondes_pause'] = $_POST['secondes_pause']; 
    $_SESSION['repetition_user_array'] = @$repetition;
    $_SESSION['minutes_user_array'] = @$minutes;
    $_SESSION['secondes_user_array'] = @$secondes;
    $_SESSION['numero_exercice_dispo_array'] = @$numero_exercice_dispo;
    $id_seance = $_SESSION['id_seance'];
    if(!isset($erreur)){
      header("Location: sport.php?id=$userid&sc=$id_seance&ex=1");
    }
  }
  

//==================================EXPERT==================================//



//==================================//////==================================//

//==================================OBJECTIFS==================================//
$objectif_user_req = $bdd->prepare('SELECT * FROM `objectifs` WHERE `idmbrs` = ?');
$objectif_user_req->execute(array($userid));
$objectif_user = $objectif_user_req->fetch();

//POUR AFFICHAGE PLUS BAS//
$objectif_user_req2 = $bdd->prepare('SELECT * FROM `objectifs` WHERE `idmbrs` = ?');
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
  $groupe_href = "../groupe.php";
  }
  else{
    $groupe_display = "Voir votre groupe";
    $groupe_display_under = "Voir les informations du groupe";
    $groupe_href = "../groupe.php";
  }
}
else{
  $groupe_display = "Créer votre groupe";
  $groupe_display_under = "Créer un groupe avec vos amis / famille";
  $groupe_href = "../creationgroupe.php?id=$userid";

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
//==================================//////==================================//

//CALCUL NOMBRE DE NOTIFICATION (DEMANDE D'AMIS + DEMANDE GROUPE) //
$nombre_notif = $nbre_dmd_amis + $nbre_dmd_groupe;

//====================================COLOR=================================//
function fromRGB($R, $G, $B){
   $R = dechex($R);
   if(strlen($R)<2)
   $R = 'O'.$R;

   $G = dechex($G);
   if (strlen($G)<2)
   $G = 'O'.$G;

   $B = dechex($B);
   if (strlen($B)<2)
   $B = 'O'.$B;
   
   return '#' . $R . $G . $B;
}
$colorhex = fromRGB(78,55,178);
//==================================END COLOR===============================//
?>
<!doctype html>
<html lang="en" style="<?= $userinfo['color']?>">


<head>
   <!-- Required meta tags -->
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title><?php echo title; ?></title>
   <!-- Favicon -->
   <link rel="shortcut icon" href="images/logo.png" />
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <!-- Typography CSS -->
   <link rel="stylesheet" href="css/typography.css">
   <!-- Style CSS -->
   <link rel="stylesheet" href="css/style.css">
   <!-- Responsive CSS -->
   <link rel="stylesheet" href="css/responsive.css">
   
   <script href="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
   <script href="getMessage.js" type="text/javascript"></script>
   <script src="oXHR.js" type="text/javascript" ></script>
   <script type="text/javascript" src="script.js"></script>
   <script src="script_ancMsg.js" type="text/javascript" ></script>
</head>
<body>
   <!-- loader Start -->
   <div id="loading">
      <div id="loading-center">
      </div>
   </div>
   <!-- loader END -->
   <!-- Wrapper Start -->
   <div class="wrapper">
      <!-- Sidebar  -->
      <div class="iq-sidebar">
         <div class="iq-sidebar-logo d-flex justify-content-between">
            <a href="index.php?id=$userid" class="header-logo">
               <img src="images/logo.png" class="img-fluid rounded-normal" alt="">
               <div class="logo-title">
                  <span class="text-primary text-uppercase">Sport +</span> 
               </div>
            </a>
            <div class="iq-menu-bt-sidebar">
               <div class="iq-menu-bt align-self-center">
                  <div class="wrapper-menu open">
                     <div class="main-circle"><i class="las la-bars"></i></div>
                  </div>
               </div>
            </div>
         </div>
         <form method="post">   
         <div id="sidebar-scrollbar">
            <nav class="iq-sidebar-menu">
               <ul id="iq-sidebar-toggle" class="iq-menu">
                  <li>
                     <a href="#menu-seances" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-list-check"></i><span>Séances</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="menu-seances" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                           <?php    
                              // AFFICHAGE DE TOUTE LES SEANCES DE LA BDD //        
                              $reponse = $bdd->query("SELECT * FROM seances WHERE id_createur = 1");// id_createur == 1 => séances de base // 
                              while ($donnees = $reponse->fetch())
                              {
                                 echo '
                                 <li class="menu-level">
                                    <a href="#menu_seances'. $donnees['ids'] .'" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="las la-thumbtack"></i><span>'. $donnees['nom']. '</span></a>
                                    <ul id="menu_seances'. $donnees['ids'] .'" class="iq-submenu iq-submenu-data collapse">
                                       <li><a href="#menu_seances2'. $donnees['ids'] .'" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="las la-thumbtack"></i>Classique</a></li>
                                          <li class="menu-level">
                                             <ul id="menu_seances2'. $donnees['ids'] .'" class="iq-submenu iq-submenu-data collapse">
                                                <li><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $donnees['nom'] .';Custom"><a><i class="ri-record-circle-line"></i>Easy</button></a></li>
                                                <li><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $donnees['nom'] .';Custom"><a><i class="ri-record-circle-line"></i>Advanced</button></a></li>
                                             </ul>
                                          </li>
                                       <li><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $donnees['nom'] .';Custom"><a><i class="las la-thumbtack"></i>Custom</button></a></li>
                                    </ul>
                                 </li>
                                 ';
                              }
                           ?>
                        </form>
                        </ul>

                  <li>
                     <a href="#menu-vos-seances" class="iq-waves-effect" data-toggle="collapse" aria-expanded="false"><span class="ripple rippleEffect"></span><i class="las la-user-tie iq-arrow-left"></i><span>Vos Séances</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="menu-vos-seances" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" style="">
                        <?php    
                              // AFFICHAGE DES SEANCES DE L'UTILISATEUR //        
                              $reponse2 = $bdd->query("SELECT * FROM seances WHERE id_createur = $userid");// id_createur == 1 => séances de base // 
                              if(!empty($reponse2)){
                                 while ($donnees2 = $reponse2->fetch())
                                 {
                                    echo '                                    
                                    <li class="menu-level">
                                       <a href="#menu_seance_user'. $donnees2['ids'] .'" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="las la-thumbtack"></i><span>'. $donnees2['nom']. '</span></a>
                                          <ul id="menu_seance_user'. $donnees2['ids'] .'" class="iq-submenu iq-submenu-data collapse">
                                          <li><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $donnees2['nom'] .';easy"><a><i class="ri-record-circle-line"></i>easy</button></a></li>
                                          <li><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $donnees2['nom'] .';medium"><a><i class="ri-record-circle-line"></i>Medium</button></a></li>
                                          </ul>
                                    </li>';
                                 }
                              }
                              echo '<li><a href=""><i class="las la-plus"></i>Créér une séance</a></li>';
                           ?>
                        </ul>
                  </li>
               </ul>
            </nav>
         </div>
      </div>
      <!-- TOP Nav Bar -->
      <div class="iq-top-navbar">
         <div class="iq-navbar-custom">
            <nav class="navbar navbar-expand-lg navbar-light p-0">
               <div class="iq-menu-bt d-flex align-items-center">
                  <div class="wrapper-menu">
                     <div class="main-circle"><i class="las la-bars"></i></div>
                  </div>
                  <div class="iq-navbar-logo d-flex justify-content-between">
                     <a href="index.php" class="header-logo">
                        <img src="images/logo.png" class="img-fluid rounded-normal" alt="">
                        <div class="pt-2 pl-2 logo-title">
                           <span class="text-primary text-uppercase">Sport +</span>
                        </div>
                     </a>
                  </div>
               </div>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"  aria-label="Toggle navigation">
                  <i class="ri-menu-3-line"></i>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="list-unstyled iq-menu-top d-flex justify-content-between mb-0 p-0">
                     <li class="active"><a href="index.php?id=<?=$userid?>">Home</a></li>
                     <?php
                     //VERIFICATION SI UNE SEANCE EST EN COURS + AFFICHAGE BOUTON REPRISE//
                     if(isset($_SESSION['seance_en_cours']) AND isset($_SESSION['exercice_en_cours'])){
                        echo '<li><a href="sport.php?id='.$userid.'&sc='.$_SESSION['seance_en_cours'].'&ex='.$_SESSION['exercice_en_cours'].'">Séances en cours</a></li>';
                     }
                     else{
                        //PAS DE SEANCE EN COURS //
                     }
                     ?>
                  </ul>
                  <ul class="navbar-nav ml-auto navbar-list">  
                     <li class="nav-item nav-icon">
                        <div class="iq-search-bar">
                           <form action="#" class="searchbox">
                              <input type="text" class="text search-input" placeholder="Search Here..">
                              <a class="search-link" href="#"><i class="ri-search-line text-black"></i></a>
                              <a class="search-audio" href="#"><i class="las la-microphone text-black"></i></a>
                           </form>
                        </div>
                     </li>
                     <li class="nav-item nav-icon search-content">
                        <a href="#" class="search-toggle iq-waves-effect text-gray rounded"><span class="ripple rippleEffect " ></span>
                           <i class="ri-search-line text-black"></i>
                        </a>
                        <form action="#" class="search-box p-0">
                           <input type="text" class="text search-input" placeholder="Type here to search...">
                           <a class="search-link" href="#"><i class="ri-search-line text-black"></i></a>
                           <a class="search-audio" href="#"><i class="las la-microphone text-black"></i></a>
                        </form>
                     </li>
                     <li class="nav-item nav-icon">
                        <a href="profil-edit.php?id=<?=$userid?>" class="search-toggle iq-waves-effect text-black rounded">
                           <i class="las la-cog"></i>
                           <span class=" dots"></span>
                        </a>
                     </li>
                     <?php 
                     require ('./script/functions.php');
                        $bdd = bdd_connect();

                        delete_msg();

                        if ($_SESSION['pseudo'] == NULL) {
                           header('Location: index.php');
                        }
                        else {
                        ?>
                     <li class="nav-item nav-icon">
                              <a href="#" class="search-toggle iq-waves-effect text-black rounded">
                                 <i class="lar la-comment"></i>
                              </a>
                              <div class="iq-sub-dropdown">
                                 <div class="iq-card shadow-none m-0">
                                    <div class="iq-card-body p-0 ">
                                       <div class="bg-primary p-3">
                                          <h5 class="mb-0 text-white">Chat Général</h5>
                                       </div>
                                       <a class="iq-sub-card" >
                                          <div class="media align-items-center">
                                             <div class="media-body ml-3">
                                                <div id="cadre_chat"></div>
                                             </div>
                                          </div>
                                       </a>
                              </div>
                           </div>
                        </div>
                     </li>
                     <?php 
                     if($groupe_user != 0){  
                        echo'
                           <li class="nav-item nav-icon">
                              <a href="#" class="search-toggle iq-waves-effect text-black rounded">
                                 <i class="lar la-envelope"></i>
                                 <span class="massage-icon dots badge badge-primary">0</span>
                              </a>
                              <div class="iq-sub-dropdown">
                                 <div class="iq-card shadow-none m-0">
                                    <div class="iq-card-body p-0 ">
                                       <div class="bg-primary p-3">
                                          <h5 class="mb-0 text-white">Chat de Groupe<small class="badge  badge-light float-right pt-1">0</small></h5>
                                       </div>
                                       <a class="iq-sub-card" >
                                          <div class="media align-items-center">
                                             <div class="media-body ml-3">
                                                <h6 class="mb-0 ">Fonctionnalitée indisponible  !</h6>
                                             </div>
                                          </div>
                                       </a>
                                       <!-- CHAT DE GROUPE 
                                       <a href="#" class="iq-sub-card">
                                          <div class="media align-items-center">
                                             <div class="">
                                                <img class="avatar-40 rounded" src="images/user/groupe.png" alt="">
                                             </div>
                                             <div class="media-body ml-3">
                                                <h6 class="mb-0 "></h6>
                                                <small class="float-left font-size-12">13 Jun</small>
                                             </div>
                                          </div>
                                       </a>
                                       -->
                                    </div>
                                 </div>
                              </div>
                           </li>';
                     }
                     ?>
                     <li class="nav-item nav-icon">
                        <a href="#" class="search-toggle iq-waves-effect text-black rounded">
                           <i class="ri-notification-line block"></i>
                           <span class="notice-icon dots badge badge-primary"><?= $nombre_notif?></span>
                        </a>
                        <div class="iq-sub-dropdown">
                           <div class="iq-card shadow-none m-0">
                              <div class="iq-card-body p-0">
                                 <div class="bg-primary p-3">
                                    <h5 class="mb-0 text-white">Notifications<small class="badge  badge-light float-right pt-1"><?= $nombre_notif?></small></h5>
                                 </div>
                                 <form action="" method="POST">
                                 <?php 
                                 if($nombre_notif != 0){                                 
                                    //AFFICHAGE DEMANDE D'AMIS //                                                                     
                                   while ($donnees = $amis_user_pending->fetch()){
                                     $amis_user_pending_check_name = $bdd->prepare('SELECT `idmbrs`, `name` FROM `membres` WHERE `idmbrs`= ?');
                                     $amis_user_pending_check_name->execute(array($donnees['id_demandeur']));
                                     $nom_amis_user_pending = $amis_user_pending_check_name->fetch();
                                     echo '
                                       <a class="iq-sub-card" >
                                          <div class="media align-items-center">
                                             <div class="">
                                                <img class="avatar-40 rounded img-fluid" src="images/user/user.png" alt="">
                                             </div>
                                             <div class="media-body ml-3">
                                                <h6 class="mb-0 ">'.$nom_amis_user_pending['name'].'<font color="gray" align="center"> #'.$nom_amis_user_pending['idmbrs'].'</font></h6>
                                                <p class="mb-0"><button type="submit" name="friend_accept" value="'.$donnees['id_demandeur'].'" class="btn mb-1 btn-success"><i class="ri-check-line"> Accepter</i></button> <button type="submit" name="friend_deny" value="'.$donnees['id_demandeur'].'" class="btn mb-1 btn-primary"><i class="las la-ban"> Refuser</i></button></p>
                                             </div>
                                          </div>
                                       </a>                                      
                                         ';
                                    }
                                    //FORMULAIRE DEMANDE D'AMIS POUR ACCEPT OU DENY (BLOQUER PAS FAIT)//
                                    if(isset($_POST['friend_accept'])){
                                       // MODIFIE LA COLONNE STATUT A 2 POUR DEFINIR PERSONNE AMIS //
                                       $amis_user_validate = $bdd->prepare('UPDATE `friends` SET `statut`= 2 WHERE `id_demandeur` = ? AND `id_receveur` = ?');
                                       $amis_user_validate->execute(array(htmlspecialchars($_POST['friend_accept']), $userid));
                                    }
                                    elseif(isset($_POST['friend_deny'])){
                                       $amis_user_deny = $bdd->prepare('DELETE FROM `friends` WHERE `id_demandeur` = ? AND `id_receveur` = ?');
                                       $amis_user_deny->execute(array(htmlspecialchars($_POST['friend_deny']), $userid));
                                    }
                                    elseif(isset($_POST['friend_bloquer'])){
                                       $amis_user_bloquer = $bdd->prepare('UPDATE `friends` SET `statut` = 3,`id_bloqueur` = ? WHERE `id_demandeur` = ? AND `id_receveur` = ?');
                                       $amis_user_bloquer->execute(array($userid, htmlspecialchars($_POST['friend_bloquer']), $userid));
                                    }  
                                 }
                                 else{
                                    echo '
                                    <a class="iq-sub-card" >
                                       <div class="media align-items-center">
                                          <div class="media-body ml-3">
                                             <h6 class="mb-0 ">Pas de nouvelle notification !</h6>
                                          </div>
                                       </div>
                                    </a>                                      
                                      ';
                                 }                         
                                 
                                 ?>
                                 </form>

                                 <form action="" method="POST">
                                 <?php 
                                    //AFFICHAGE DEMANDE DE GROUPE
                                   while ($donnees3 = $groupe_request->fetch()){
                                     $infogroupe = $bdd->prepare('SELECT * FROM `groupe` WHERE `groupe_id` = ?');
                                     $infogroupe->execute(array($donnees3['id_groupe']));
                                     $groupe_info = $infogroupe->fetch();
                                     echo '
                                     <a class="iq-sub-card" >
                                     <div class="media align-items-center">
                                        <div class="">
                                           <img class="avatar-40 rounded img-fluid" src="images/user/groupe.png" alt="">
                                        </div>
                                        <div class="media-body ml-3">
                                           <h6 class="mb-0 ">'.$groupe_info['groupe_name'].'<font color="gray" align="center"> #'.$donnees3['id_groupe'].' (Groupe)</font></h6>
                                           <p class="mb-0"><button type="submit" name="groupe_accept" value="'.$donnees3['id_groupe'].'" class="btn mb-1 btn-success"><i class="ri-check-line"> Accepter</i></button> <button type="submit" name="groupe_deny" value="'.$donnees3['id_groupe'].'" class="btn mb-1 btn-primary"><i class="las la-ban"> Refuser</i></button></p>
                                        </div>
                                     </div>
                                  </a>
                                     ';
                                   }
                                 ?>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </li>
                     <!-- DROPDOWN DROITE -->
                     <?php include('include-dropdown.php');?>
      <!-- TOP Nav Bar END -->
            <!-- Page Content  -->
            <div id="content-page" class="content-page">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-lg-12 col-md-12"> 

                              <?php
                              //MESSAGE ALERT SI TOUT LES PARAMETRES PAS ENREGISTRE//
                              if($userinfo['age'] == NULL OR $userinfo['taille'] == NULL OR $userinfo['genre'] == NULL OR $userinfo['poids'] == NULL){
                                 echo 
                                 '                                
                                    <div class="iq-card">
                                       <div class="iq-card-header d-flex justify-content-between">
                                          <div class="iq-header-title">
                                             <h6 class="text-danger"><i class="ion-alert-circled"></i> Vous n\'avez pas finaliser votre inscription. Certaines fonctionnalitées ne seront pas disponible ! <a class="text-primary" href="profil-edit.php?id='.$userid.'">Finaliser votre inscription</a></h6>
                                          </div>
                                       </div>
                                    </div>
                                 ';
                              }                               
                              ?>      
                              <?php if(isset($erreur)){echo '<font color="red" align="center">' . $erreur.'</font>';}?>
                              <?php if(isset($erreur_info)){echo '<font color="gray" align="center">' . $erreur_info.'</font>';}?>
                              <?php 
                              //==================================EASY==================================//
                                 if(@$_SESSION['difficulty'] == 'easy'){
                                    echo '         
                                    <div class="col-lg-6 col-md-12">                           
                                       <div class="iq-card">
                                          <div class="iq-card-header d-flex justify-content-between">
                                             <div class="iq-header-title">
                                                <h4 class="card-title">Paramètre de votre séances <font color="gray">('.$_SESSION['seance_choisis'].')</font></h4>
                                             </div>
                                          </div>
                                       <div class="iq-card-body">
                                          <form action="" method="post">
                                             <h3><?= @$erreur ?></h3>
                                                <div class="form-row">
                                                   <div class="col-md-6 mb-3">
                                                      <label for="validationTooltip01">Minutes :</label>
                                                      <input type="text" pattern="[A-Za-z]{3}" class="form-control" name="minutes" id="validationTooltip01" value="1" required>
                                                   </div>

                                                   <div class="col-md-6 mb-3">
                                                      <label for="validationTooltip02">Secondes :</label>
                                                      <input type="number" class="form-control" name="secondes" id="validationTooltip01" value="30" required>
                                                   </div>

                                                   <div class="col-md-6 mb-3">
                                                      <label for="validationTooltip01">Minutes Pause :</label>
                                                      <input type="number" class="form-control" name="minutes_pause" id="validationTooltip01" value="0" required>
                                                   </div>

                                                   <div class="col-md-6 mb-3">
                                                      <label for="validationTooltip02">Secondes Pause:</label>
                                                      <input type="number" class="form-control" name="secondes_pause" id="validationTooltip01" value="45" required>
                                                   </div>

                                                   <div class="col-md-6 mb-3">
                                                      <label for="validationTooltip03">Nombres de répetition :</label>
                                                      <input type="number" class="form-control" name="repetition" id="validationTooltip01" value="10" required>
                                                   </div>
                                                </div>
                                             <button type="submit" class="btn btn-primary" name="seance_info_send_easy">Lancer la séance !</button>
                                          </form>
                                    ';
                                    

                                          //==================================MEDIUM==================================//

                                       }elseif(@$_SESSION['difficulty'] == 'medium'){

                                          echo '<form action="" method="post">';
                                          echo'
                                          <div class="iq-card">
                                             <div class="iq-card-header d-flex justify-content-between">
                                                <div class="iq-header-title">
                                                   <h4 class="card-title">Paramètre de votre séances <font color="gray">('.$_SESSION['seance_choisis'].')</font></h4>
                                                </div>
                                             </div>
                                          <div class="iq-card-body">
                                             <div class="form-row">
                                                <div class="col-md-6 mb-3">
                                                   <label for="validationTooltip01">Minutes Pause :</label>
                                                   <input type="number" class="form-control" name="minutes_pause" id="validationTooltip01" value="0" required>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                   <label for="validationTooltip02">Secondes Pause:</label>
                                                   <input type="number" class="form-control" name="secondes_pause" id="validationTooltip01" value="45" required>
                                                </div>
                                             </div>';

                                          // RECUPERATION TOUS LES EXERCICES DE LA SEANCE //
                                          $reponse = $bdd->prepare("SELECT * FROM seances WHERE ids = ?");
                                          $reponse->execute(array($_SESSION['id_seance']));
                                          $donnees = $reponse->fetch();

                                          // BOUCLE POUR CHAQUE EXERCICE //
                                          $nombre_exercice = 0;
                                          for($id_exercice = 1; $id_exercice <= 150; $id_exercice++){
                                          $id_nom_exercice = "exercice$id_exercice";
                                          if(@$donnees[$id_nom_exercice] == true){
                                             $nombre_exercice = $nombre_exercice+1;

                                             // REQUETE INFO POUR CHAQUE EXERCICE //
                                             $reqinfoex = $bdd->prepare("SELECT * FROM exercices WHERE nom = ?");
                                             $reqinfoex->execute(array($donnees[$id_nom_exercice]));
                                             $exinfo = $reqinfoex->fetch();

                                             // INFO DANS VARIABLE //
                                             $repetition_ou_timer = $exinfo['repetitiontimer'];    
                                             $nom_exercice = $donnees[$id_nom_exercice];
                                             
                                             // VERIFICATION TIMER OU REPETITION //
                                             if($repetition_ou_timer == 1){ //REPETITION //
                                                echo "<div class='form-row'>
                                                         <div class='col-md-6 mb-3'>
                                                            <label for='validationTooltip03'><button type='button' class='btn mb-1 btn-primary'>$id_exercice</button> Nombres de répetition pour $nom_exercice :</label>
                                                            <input type='number' class='form-control' name='repetition_exercice[]' id='validationTooltip01' value='10' required>
                                                            <div class='invalid-tooltip'>mauvais nombre de répétition</div>
                                                         </div>
                                                      </div>";
                                             }
                                             elseif($repetition_ou_timer == 2){ // TIMER //
                                                echo "<div class='form-row'>
                                                         <div class='col-md-6 mb-3'>
                                                            <label for='validationTooltip01'><button type='button' class='btn mb-1 btn-primary'>$id_exercice</button> Minutes pour $nom_exercice:</label>
                                                            <input type='number' class='form-control' name='minutes_exercice[]' id='validationTooltip01' value='1' required>
                                                            <div class='valid-tooltip'>Looks good!</div>
                                                         </div>

                                                         <div class='col-md-6 mb-3'>
                                                            <label for='validationTooltip02'><button type='button' class='btn mb-1 btn-primary'>$id_exercice</button> Secondes pour $nom_exercice :</label>
                                                            <input type='number' class='form-control' name='secondes_exercice[]' id='validationTooltip01' value='30' required>
                                                            <div class='valid-tooltip'>Looks good!</div>
                                                         </div>
                                                      </div>"; 
                                             }
                                          }
                                          else{ //COMPTAGE DE NOMBRE DE SEANCE DISPO //
                                             $numero_exercice_dispo[] = $id_exercice;    
                                          }
                                          
                                          }
                                          echo '<button type="submit" class="btn btn-primary" name="seance_info_send_medium">Lancer la séance !</button>';
                                          echo '</form>';

                                          //==================================EXPERT==================================//

                                       }elseif(@$_SESSION['difficulty'] == 'expert'){
                                          echo '
                                          <div class="iq-card">
                                             <div class="iq-card-header d-flex justify-content-between">
                                                <div class="iq-header-title">
                                                   <h4 class="card-title">Paramètre de votre séances <font color="gray">('.$_SESSION['seance_choisis'].')</font></h4>
                                                </div>
                                             </div>
                                          <div class="iq-card-body">
                                          <font class="text-primary">Nous sommes désolé le mode Expert n\'est pour le moment pas disponible.</font>';
                                          //CHANGEMENT & AJOUT D'EXERCICE DANS UNE SEANCE & CHANGEMENT DE TEMPS DE PAUSE ENTRE CHAQUE EXO & CHANGEMENT ENTRE TIMER ET REPETITION//
                                       }

                                       //==================================//////==================================//
      
                   
                              ?>
                           </div>
                        </div>
                     </div>
                      <!-- STATISTIQUES PERSONNELLE -->
                     <div class="col-lg-12">
                        <div class="row">
                           <div class="col-lg-6 col-md-12">
                              <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                 <div class="iq-card-header d-flex justify-content-between align-items-center">
                                    <div class="iq-header-title">
                                       <h4 class="card-title">Statistiques personnelle</h4>
                                    </div>
                                 </div>
                                 <div class="iq-card-body">
                                    <?php 
                                       if($nseps != 0 OR $nsepms != 0){
                                          echo '<div id="morris-donut-chart"></div>';
                                       }
                                       else{
                                          echo 'Aucune statistiques disponibles pour le moment :/';
                                       }
                                    ?>
                                 </div>
                           </div> 
                        </div>  
                     <!-- STATISTIQUES PERSONNELLE END -->   

                     <!-- OBJECTIFS PERSONNELLE -->                                        
                     <div class="col-lg-6 col-md-12">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                           <div class="iq-card-header d-flex justify-content-between align-items-center">
                              <div class="iq-header-title">
                                 <h4 class="card-title">Vos objectifs</h4>
                              </div>
                           </div>  
                           <div class="iq-card-body">
                              <ul class="list-inline p-0 mb-0">     
                              <?php if(isset($erreurobjectif)){echo '<font color="red" align="center">' . $erreurobjectif.'</font>';}?>
                                 <?php 
                                    if(!empty($objectif_user)){   
                                       echo '<form method="POST">';
                                       while($objectifs_info = $objectif_user_req2->fetch()){
                                          echo '
                                          <li>
                                             <div class="d-flex align-items-center justify-content-between mb-3">
                                                <h6>'.$objectifs_info['nom'].' | <font color="gray" align="center"> '.$objectifs_info['info'].'</font></h6><button type="submit" class="btn btn-whith mb-1" name="delete-objectif" value="'.$objectifs_info['id'].'"><a class="text-primary"><i class="ion-close-circled"></i></a></button>
                                             </div>
                                          </li>';
                                       }
                                       echo '</form>';
                                       echo '<a data-toggle="modal" data-target="#modalobjectifadd" href="">Définir un nouvel objectif !</a>';
                                    }
                                    else{
                                       echo 'Vous n\'avez pas encore définis d\'objectifs :/ <a data-toggle="modal" data-target="#modalobjectifadd" href="">Définissez dés maintenant un objectif !</a>';
                                    }
                                 ?>                              
                              </ul>
                           </div>
                        </div>
                     </div>
                     <!-- Modalobjectifadd-->
                     <div class="modal fade" id="modalobjectifadd" tabindex="-1" role="dialog" aria-labelledby="modalobjectifadd" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h5 class="modal-title" id="modalobjectifadd">Ajouter un Objectif Personnel</h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                                 </button>
                              </div>
                              <div class="modal-body">
                              
                                 <form method="POST">
                                       <div class="form-group">
                                          <label for="cpass">Nom de l'Objectif:</label>
                                          <input type="text" name="nomobjectif" class="form-control" id="cpass" placeholder="ex: Pompes">
                                       </div>
                                       <div class="form-group">
                                          <label for="npass">Description de l'Objectif:</label>
                                          <input type="text" name="descriptionobjectif" class="form-control" id="npass" placeholder="ex: Faire 10 pompes tout les jours">
                                       </div>
                              </div>
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                 <button type="submit" class="btn btn-primary" name="objectifpersonnel-submit">Ajouter</button>
                              </div>
                                 </form>
                           </div>
                        </div>
                     </div>
                     <!-- OBJECTIFS PERSONNELLE END --> 

                     <!-- ALL EXERCICES  --> 
                     <div class="col-lg-12">
                        <div class="iq-card">
                           <div class="iq-card-header d-flex justify-content-between align-items-center">
                              <div class="iq-header-title">
                                 <h4 class="card-title">Exercices</h4>
                              </div>
                              <div id="feature-album-slick-arrow" class="slick-aerrow-block"></div>
                           </div>
                           <div class="iq-card-body">
                              <ul class="list-unstyled row  feature-album iq-box-hover mb-0">
                                 <?php    
                                    // AFFICHAGE DE TOUTE LES EXERCICES DE LA BDD //        
                                    $reponse4 = $bdd->query("SELECT * FROM `exercices`");
                                    while ($donnees4 = $reponse4->fetch())
                                    {
                                       echo '
                                       <li class="col-lg-2  iq-music-box">
                                          <div class="iq-card mb-0">
                                             <div class="iq-card-body p-0">
                                                <div class="iq-thumb">
                                                   <div class="iq-music-overlay"></div>
                                                   <a data-toggle="modal" data-target="#modalexercice" href="">
                                                      <img src="images/exercices/'.$donnees4['illustration'].'" class="img-border-radius img-fluid w-100" alt="">
                                                   </a>
                                                   <div class="overlay-music-icon">
                                                      <a data-toggle="modal" data-target="#modalexercice" href="">
                                                         <i class="las la-play-circle"></i>
                                                      </a>
                                                   </div>
                                                </div>
                                                <div class="feature-list text-center">
                                                   <h6 class="font-weight-600 mb-0">'.$donnees4['nom'].'</h6>
                                                </div>
                                             </div>
                                          </div>
                                       </li>';
                                    }
                                 ?>                                                    
                              </ul>
                           </div>
                        </div>
                     </div> 
                     <!--MODAL EXERCICE UNIQUE-->
                     <div class="modal fade" id="modalexercice" tabindex="-1" role="dialog" aria-labelledby="modalexercice" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h5 class="modal-title" id="modalexercice">Exercice</h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                                 </button>
                              </div>
                              <div class="modal-body">
                              test
                              </div>
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                 <button type="submit" class="btn btn-primary" name="objectifpersonnel-submit">Ajouter</button>
                              </div>
                                 </form>
                           </div>
                        </div>
                     </div>
                     <!-- ALL EXERCICES END -->  
                     
                     <!-- STATISTIQUES DE GROUPE  -->            
                        <?php                     
                           if($groupe_user != 0){
                           echo '
                           <div class="col-sm-12 col-lg-12">
                              <div class="iq-card">
                                 <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                       <h4 class="card-title">Statistique du groupe <font class="text-primary">'.$groupe_info_user['groupe_name'].'</font> | <font color="gray" align="center">(Nombre de séances par semaine)</font></h4>
                                    </div>
                                 </div>
                                    <div class="iq-card-body">
                                       <div id="am-simple-chart" style="height: 300px;"></div>
                                    </div>
                              </div>
                           </div>';
                           }
                        ?>
                     <!-- STATISTIQUES DE GROUPE END -->   
                  </div>
               </div>
            </div>
         </div>
   <!-- Wrapper END -->
   <?php
   //include('include-colorcustom.php');    
   ?>
   <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="js/jquery.min.js"></script>
   <script src="js/popper.min.js"></script>
   <script src="js/bootstrap.min.js"></script>
   <!-- Appear JavaScript -->
   <script src="js/jquery.appear.js"></script>
   <!-- Countdown JavaScript -->
   <script src="js/countdown.min.js"></script>
   <!-- Counterup JavaScript -->
   <script src="js/waypoints.min.js"></script>
   <script src="js/jquery.counterup.min.js"></script>
   <!-- Wow JavaScript -->
   <script src="js/wow.min.js"></script>
   <!-- Apexcharts JavaScript -->
   <script src="js/apexcharts.js"></script>
   <!-- Slick JavaScript -->
   <script src="js/slick.min.js"></script>
   <!-- Select2 JavaScript -->
   <script src="js/select2.min.js"></script>
   <!-- Owl Carousel JavaScript -->
   <script src="js/owl.carousel.min.js"></script>
   <!-- Magnific Popup JavaScript -->
   <script src="js/jquery.magnific-popup.min.js"></script>
   <!-- Smooth Scrollbar JavaScript -->
   <script src="js/smooth-scrollbar.js"></script>
   <!-- lottie JavaScript -->
   <script src="js/lottie.js"></script>
   <!-- am core JavaScript -->
   <script src="js/core.js"></script>
   <!-- am charts JavaScript -->
   <script src="js/charts.js"></script>
   <!-- am animated JavaScript -->
   <script src="js/animated.js"></script>
   <!-- am kelly JavaScript -->
   <script src="js/kelly.js"></script>
   <!-- am maps JavaScript -->
   <script src="js/maps.js"></script>
   <!-- am worldLow JavaScript -->
   <script src="js/worldLow.js"></script>
   <!-- Raphael-min JavaScript -->
   <script src="js/raphael-min.js"></script>
   <!-- Morris JavaScript -->
   <script src="js/morris.js"></script>
   <!-- Morris min JavaScript -->
   <script src="js/morris.min.js"></script>
   <!-- Flatpicker Js -->
   <script src="js/flatpickr.js"></script>
   <!-- Style Customizer -->
   <script src="js/style-customizer.js"></script>
   <!-- Chart Custom JavaScript -->
   <script src="js/chart-custom.js"></script>
   <!-- Custom JavaScript -->
   <script src="js/custom.js"></script>
   <script>
//GRAPHIQUE//  
(function(jQuery) {
"use strict";
jQuery(document).ready(function() {
    var rightSideBarMini = false;
    checkRightSideBar(rightSideBarMini);
    jQuery(document).on('click', '.right-sidebar-toggle', function() {
        if (rightSideBarMini) {
            rightSideBarMini = false;
        } else {
            rightSideBarMini = true;
        }
        checkRightSideBar(rightSideBarMini);
    })
});
function checkRightSideBar(rightSideBarMini) {
    if (rightSideBarMini) {
        rightSideBarShow();
    } else {
        rightSideBarHide()
    }
}
function rightSideBarShow() {
    jQuery('.right-sidebar-mini').addClass('right-sidebar')
}
function rightSideBarHide() {
    jQuery('.right-sidebar-mini').removeClass('right-sidebar')
}
})(jQuery);


// GRAPHIQUE POUR LE GROUPE //
 if(jQuery('#am-simple-chart').length){
    am4core.ready(function() {

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("am-simple-chart", am4charts.XYChart);
    chart.colors.list = [am4core.color("<?=$colorhex?>"),];

    // Add data
    chart.data = [{
      "Utilisateur": "<?= $name_groupe_user1 ?>",
      "séances": <?= $nombre_seance_semaine_user1?>
    }, {
      "Utilisateur": "<?= $name_groupe_user2 ?>",
      "séances": <?= $nombre_seance_semaine_user2?>
    }, {
      "Utilisateur": "<?= $name_groupe_user3 ?>",
      "séances": <?= $nombre_seance_semaine_user3?>
    }, {
      "Utilisateur": "<?= $name_groupe_user4 ?>",
      "séances": <?= $nombre_seance_semaine_user4?>
    }];

    // Create axes

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "Utilisateur";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;

    categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
      if (target.dataItem && target.dataItem.index & 2 == 2) {
        return dy + 25;
      }
      return dy;
    });

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = "séances";
    series.dataFields.categoryX = "Utilisateur";
    series.name = "Séances";
    series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
    series.columns.template.fillOpacity = .8;

    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 2;
    columnTemplate.strokeOpacity = 1;

    }); // end am4core.ready()
 }
// GRAPHIQUE PERSONNELLE //
 if(jQuery('#morris-donut-chart').length){
        var donut = new Morris.Donut({
          element: 'morris-donut-chart',
          resize: true,
          colors: ["<?=$colorhex?>", "#1ee2ac"],
          data: [
            {label: "Séances par Mois", value: <?=$nseps?>},
            {label: "Séances par semaines", value: <?=$nsepms?>}
          ],
          hideHover: 'auto'
        });
     }

</script>
</body>
</html>
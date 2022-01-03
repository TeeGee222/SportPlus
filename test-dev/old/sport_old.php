<?php
//DEBUT DE SESSION //
session_start();
//VERIFICATION SESSION OK //
if($_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { // EXPIRATION ? //
  //REDIRECTION A LA PAGE DE CONNEXIO //
   $_SESSION['deco'] = "Votre session a expiré !";
   header('Location: deconnexion.php');
} 
else{ // SI PAS EXPIRER //
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
   include('include/include.php');    
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
      header("Location: connexion.php"); //REDIRECTION VERS connexion.php //
  }
?>
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

//==================================//////==================================//

//CALCUL NOMBRE DE NOTIFICATION (DEMANDE D'AMIS + DEMANDE GROUPE) //

$nombre_notif = $nbre_dmd_amis + $nbre_dmd_groupe;

//==================================//////==================================//
?>
<?php
//==================================SPORT==================================//
//RECUPERATION SC & EX DEPUIS L'URL //
   if(isset($_GET['sc']) AND $_GET['sc'] > 0) {
      if(isset($_GET['ex']) AND $_GET['ex'] > 0) {
         //RECUPERATION ID USER //
         $userid = $_GET['id'];
         //REQUETE SQL //
         $requser = $bdd->prepare('SELECT * FROM membres WHERE idmbrs = ?');
         $requser->execute(array($userid));
         $userinfo = $requser->fetch(); 
         $name = $userinfo['name']; //PASSAGE DU NOM DE L'UTILISATEUR DANS VARIABLE $name //

         //RECUPERATION ID DE LA SEANCE EN COURS //
         $sc_id = $_GET['sc']; 
         //RECUPERATION INFO DE LA SEANCE EN COURS //
         $reqsc = $bdd->prepare('SELECT * FROM seances WHERE ids = ?');
         $reqsc->execute(array($sc_id));
         $scinfo = $reqsc->fetch();
         //PASSAGE DES INFORMATION DE LA BDD DANS VARIABLES //
         $sc_nom = $scinfo['nom'];
         $createur = $scinfo['createur'];

         //RECUPERATION NUMERO + INFO DE L'EXERCICE A FAIRE //
         $exercice_id = $_GET['ex']; 
         $requete_exercice = "exercice$exercice_id";
         $reqex = $bdd->prepare("SELECT $requete_exercice FROM seances WHERE ids = ?");
         $reqex->execute(array($sc_id));
         $exinfo = $reqex->fetch();
         //PASSAGE DES INFORMATION DE LA BDD DANS VARIABLES //
         $exercice_en_cours = $exinfo["exercice$exercice_id"];

         //PASSAGE DANS VARIABLE DE SESSION POUR POUVOIR REPRENDRE LA SEANCE //
         $_SESSION['seance_en_cours'] = $sc_id;
         $_SESSION['exercice_en_cours'] = $exercice_id;

      }
      else{
         //REDIRECTION VERS index.php //
      }
   }
   else{
      //REDIRECTION VERS index.php //
   }

//RECUPERATION INFORMATION SUR L'EXERCICE //
$reqinfoex = $bdd->prepare("SELECT * FROM exercices WHERE nom = ?");
$reqinfoex->execute(array($exercice_en_cours));
$exinfo = $reqinfoex->fetch();

//PASSAGE DES INFORMATION DE LA BDD DANS VARIABLES //
$exercice_repetition_ou_timer = $exinfo['repetitiontimer'];
$exercice_info = $exinfo['information'];
$exercice_illustration = $exinfo['illustration'];

   if($_SESSION['difficulty'] == 'easy'){
      //RECUPERATION VARIABLES DE SESSION //
      $minutes_user = $_SESSION['minutes'];
      $secondes_user = $_SESSION['secondes'];
      $repetition_user_exercice = $_SESSION['repetition'];  
   }
   elseif($_SESSION['difficulty'] == 'medium'){
      //RECUPERATION DES VARIABLES DE SESSION (ARRAY) //
      $repetition_user_array = $_SESSION['repetition_user_array'];
      $minutes_user_array = $_SESSION['minutes_user_array'];
      $secondes_user_array = $_SESSION['secondes_user_array'];
   }
   elseif($_SESSION['difficulty'] == 'expert'){

   }

   if($exercice_id == 1){
      $_SESSION['repetition_compteur'] = 0;
      $_SESSION['minutes_compteur'] = 0;
      $_SESSION['secondes_compteur'] = 0;
   }
   
   if($exercice_repetition_ou_timer == 1){
      if($_SESSION['difficulty'] != 'easy'){
         $_SESSION['repetition_compteur'] = $_SESSION['repetition_compteur']+1;
         $repetition_user_exercice = $repetition_user_array[$_SESSION['repetition_compteur']-1];
      }

   }
   elseif($exercice_repetition_ou_timer == 2){
      if($_SESSION['difficulty'] != 'easy'){

         $_SESSION['minutes_compteur'] = $_SESSION['minutes_compteur']+1;
         $_SESSION['secondes_compteur'] = $_SESSION['secondes_compteur']+1;
   
         $minutes_user = $minutes_user_array[$_SESSION['minutes_compteur']-1];
         $secondes_user = $secondes_user_array[$_SESSION['secondes_compteur']-1];   }
      
   }


//PARAMETRES TIMER //
$heures   = 0;  // LES HEURES < 24 //
$minutes  = @$minutes_user;   // LES MINUTES  < 60 //
$secondes = @$secondes_user;  // LES SECONDES < 60 //


//ARRIVER DU COMPTEUR A 0 //
$exercice_1_id = $exercice_id+1; //+1 POUR PASSER A L'EXERCICE SUIVANT //
$redirection = "sport.php?id=$userid&sc=$sc_id&ex=$exercice_1_id";

//CALCUL DES SECONDES //
$secondes = mktime(date("H") + $heures, date("i") + $minutes, date("s") + $secondes) - time();

?>


<!doctype html>
<html lang="en" style="--iq-primary:rgb(78, 55, 178); --iq-light-primary:rgba(78, 55, 178,0.1); --iq-primary-hover:rgba(78, 55, 178,0.8);">

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
</head>
<body class="sidebar-main">
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
                                       <li><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $donnees['nom'] .';easy"><a><i class="ri-record-circle-line"></i>easy</button></a></li>
                                       <li><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $donnees['nom'] .';medium"><a><i class="ri-record-circle-line"></i>Medium</button></a></li>
                                       <li><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $donnees['nom'] .';expert"><a><i class="ri-record-circle-line"></i>Expert</button></a></li>
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
                                          <li><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $donnees2['nom'] .';expert"><a><i class="ri-record-circle-line"></i>Expert</button></a></li>
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
                     <li><a href="index.php?id=<?=$userid?>&sc=<?=$_SESSION['seance_en_cours']?>&ex=<?=$_SESSION['exercice_en_cours']?>">Home</a></li>
                     <li class="active"><a href="index.php?id=<?=$userid?>&sc=<?=$_SESSION['seance_en_cours']?>&ex=<?=$_SESSION['exercice_en_cours']?>">Séances en cours</a></li>
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
                        <a href="#" class="search-toggle iq-waves-effect text-black rounded">
                           <i class="las la-cog"></i>
                           <span class=" dots"></span>
                        </a>
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
                     <?php include('include/include-dropdown.php');?>
      <!-- TOP Nav Bar END -->
            <!-- Page Content  -->
         <div id="content-page" class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">                                  
                        <div class="iq-card">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title"><?=$exercice_en_cours?>  <font color="gray">(Vous travaillez <?= $sc_nom?>)</font><a href="" class="text-primary"><i class="las la-times"></i></a></h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                            <div class="card text-center">
                              <div class="card-body">
                                 <h5 class="card-title"><?=$exercice_en_cours?> 
                                 <?php 
                                    // CHOIX TIMER OU REPETITION //
                                    if($exercice_repetition_ou_timer == 1){
                                       echo "(x $repetition_user_exercice)"; 
                                    }
                                       ?></h5>
                                    <img src="../images/exercices/<?=$exercice_illustration?>" alt="Nous Sommes désolé l'illustration n'a pas pu être chargé."  width=250/>
                                    <p class="card-text"><?=$exercice_info?></p>
                                    <?php 
                                    // CHOIX TIMER OU REPETITION //
                                    if($exercice_repetition_ou_timer == 1){
                                       echo "<a href='repos.php?id=$userid&sc=$sc_id&ex=$exercice_1_id' class='btn btn-primary'>Terminer !</a>"; 
                                    }
                                    //VERIFICATION SEANCE TERMINER (EN FONCTION DE TIMER OU PAS)//
                                    elseif($exercice_repetition_ou_timer <= 0){
                                       header("Location: ../fin.php?id=$userid&sc=$sc_id&ex=$exercice_1_id");
                                    }
                                    //LANCEMENT DU TIMER //
                                    else{
                                       // NOMBRE DE SECONDES DOIT ETRE > 24H (TIMER) //
                                       if ($secondes <= 3600*24) { 
                                       echo "<div id='minutes' style='font-size: 36px;'></div></span>";
                                       }
                                    }
                                    //ar_dump($_SESSION);
                                 ?>
                              </div>
                              <div class="card-footer text-muted">Séance crée par <?=$createur?></div>
                              </div>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   <!-- Wrapper END -->
   <!-- Optional JavaScript -->
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
   <!-- music js -->
   <script src="js/music-player.js"></script>
   <!-- music-player js -->
   <script src="js/music-player-dashboard.js"></script>
   <!-- Custom JavaScript -->
   <script src="js/custom.js"></script>
   <!-- SCRIPT JS COMPTE A REBOURS -->
   <script type="text/javascript">
   var temps = <?= $secondes?>;
   var timer =setInterval('CompteaRebour()',1000);
   function CompteaRebour(){
   temps-- ;
   m = parseInt((temps%3600)/60) ;
   s = parseInt((temps%3600)%60) ;
   document.getElementById('minutes').innerHTML= (m<10 ? "0"+m : m) + ' mn : ' +(s<10 ? "0"+s : s) + ' s ';
   if ((s == 0 && m ==0)) {
      clearInterval(timer);
      url = "<?= $redirection;?>"
      Redirection(url)
   }
   }
   function Redirection(url) {
   setTimeout("window.location=url", 500)
   }
   </script>
</body>
</html>















































































//RECUPERATION INFORMATION SUR L'EXERCICE //
$reqinfoex = $bdd->prepare("SELECT * FROM exercices WHERE nom = ?");
$reqinfoex->execute(array($exercice_en_cours));
$exinfo = $reqinfoex->fetch();

//PASSAGE DES INFORMATION DE LA BDD DANS VARIABLES //
$exercice_repetition_ou_timer = $exinfo['repetitiontimer'];
$exercice_info = $exinfo['information'];
$exercice_illustration = $exinfo['illustration'];

if($_SESSION['difficulty'] == 'easy'){
    //RECUPERATION VARIABLES DE SESSION //
    $minutes_user = $_SESSION['minutes'];
    $secondes_user = $_SESSION['secondes'];
    $repetition_user_exercice = $_SESSION['repetition'];  
}
elseif($_SESSION['difficulty'] == 'medium'){
    //RECUPERATION DES VARIABLES DE SESSION (ARRAY) //
    $repetition_user_array = $_SESSION['repetition_user_array'];
    $minutes_user_array = $_SESSION['minutes_user_array'];
    $secondes_user_array = $_SESSION['secondes_user_array'];
}
elseif($_SESSION['difficulty'] == 'expert'){

}

if($exercice_id == 1){
    $_SESSION['repetition_compteur'] = 0;
    $_SESSION['minutes_compteur'] = 0;
    $_SESSION['secondes_compteur'] = 0;
}

if($exercice_repetition_ou_timer == 1){
    if($_SESSION['difficulty'] != 'easy'){
        $_SESSION['repetition_compteur'] = $_SESSION['repetition_compteur']+1;
        $repetition_user_exercice = $repetition_user_array[$_SESSION['repetition_compteur']-1];
    }

}
elseif($exercice_repetition_ou_timer == 2){
    if($_SESSION['difficulty'] != 'easy'){

        $_SESSION['minutes_compteur'] = $_SESSION['minutes_compteur']+1;
        $_SESSION['secondes_compteur'] = $_SESSION['secondes_compteur']+1;

        $minutes_user = $minutes_user_array[$_SESSION['minutes_compteur']-1];
        $secondes_user = $secondes_user_array[$_SESSION['secondes_compteur']-1];   }
    
}



<?php
// CHOIX TIMER OU REPETITION //
if($exercice_repetition_ou_timer == 1){
    echo "(x $repetition_user_exercice)"; 
}
    ?></h5>
<img src="../images/exercices/<?=$exercice_illustration?>" alt="Nous Sommes désolé l'illustration n'a pas pu être chargé."  width=250/>
<p class="card-text"><?=$exercice_info?></p>
<?php 
// CHOIX TIMER OU REPETITION //
if($exercice_repetition_ou_timer == 1){
    echo "<a href='repos.php?id=$userid&sc=$sc_id&ex=$exercice_1_id' class='btn btn-primary'>Terminer !</a>"; 
}
//VERIFICATION SEANCE TERMINER (EN FONCTION DE TIMER OU PAS)//
elseif($exercice_repetition_ou_timer <= 0){
    header("Location: ../fin.php?id=$userid&sc=$sc_id&ex=$exercice_1_id");
}
//LANCEMENT DU TIMER //
else{
    // NOMBRE DE SECONDES DOIT ETRE > 24H (TIMER) //
    if ($secondes <= 3600*24) { 
    echo "<div id='minutes' style='font-size: 36px;'></div></span>";
    }
}
//ar_dump($_SESSION);
?>
<?php
//============================VERIFICATION SEANCE ==========================//
include('include/include-verif-session.php');
//=========================FIN VERIFICATION SEANCE =========================//

//======================INCLUDE POUR TITLE + CONNEXION BDD==================//
include('include/include_bdd.php');
include('include/include_info.php');
//====================FIN INCLUDE POUR TITLE + CONNEXION BDD================//

//===================VAR DE SESSION POUR AFFICHER NAVBAR====================//
$_SESSION['page'] = "Profil";
//=================FIN VAR DE SESSION POUR AFFICHER NAVBAR==================//

//==RECUPERATION ID DANS URL +VERIFICATION BDD + RECUPERATION INFORMATION==//
include('include/include-recup-info-user.php');
//FIN RECUPERATION ID DANS URL +VERIFICATION BDD + RECUPERATION INFORMATION//

//============================CHOIX DE LA SEANCE===========================//
include('include/include-choix-seance-traitement.php');
//==========================FIN CHOIX DE LA SEANCE=========================//  

//==================================OBJECTIFS===============================//
include('include/include-objectifs.php');
//===============================FIN OBJECTIFS==============================//

//==================================GROUPE==================================//
include('include/include-traitement-groupe.php');
//================================FIN GROUPE================================//

// CALCUL NOMBRE DE NOTIFICATION (DEMANDE D'AMIS + DEMANDE GROUPE) //
$nombre_notif = $nbre_dmd_amis + $nbre_dmd_groupe;
// FIN CALCUL NOMBRE DE NOTIFICATION (DEMANDE D'AMIS + DEMANDE GROUPE) //

//====================================COLOR=================================//
include('include/include-fonctions.php');
$colorhex = fromRGB(78,55,178);
//==================================END COLOR===============================//

?>

<!doctype html>
<html lang="en" style="--iq-primary:rgb(78, 55, 178); --iq-light-primary:rgba(78, 55, 178,0.1); --iq-primary-hover:rgba(78, 55, 178,0.8);">

<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
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
<body class="sidebar-main-active right-column-fixed">

   <!-- loader Start -->
   <div id="loading">
      <div id="loading-center">
      </div>
   </div>
   <!-- loader END -->

   <!-- Wrapper Start -->
   <div class="wrapper">

   <?php 
      // INCLUDE NAVBAR //
      include('include/include-navbar.php');
      // FIN INCLUDE NAVBAR //
   ?>
   
   <!-- Page Content  -->
   <div id="content-page" class="content-page">
      <div class="container-fluid">
         <div class="row profile-content">
            <div class="col-12 col-md-12 col-lg-4">
               <div class="iq-card">
                  <div class="iq-card-body profile-page">
                     <div class="profile-header">
                        <div class="cover-container text-center">
                           <div class="profile-detail mt-3">
                              <h3><?= $name ?><font color="gray" > #<?= $userid ?></font></h3>
                              <p class="text-primary"><?= $userinfo['description']?></p>
                              <p>Ici vous pouvez vérifier toute vos informations ainsi que vos statistiques.</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="iq-card">
                  <div class="iq-card-header d-flex justify-content-between align-items-center mb-0">
                     <div class="iq-header-title">
                        <h4 class="card-title mb-0">Détails Personnels</h4>
                     </div>
                  </div>
                  <div class="iq-card-body">
                     <ul class="list-inline p-0 mb-0">
                        <li>
                           <div class="d-flex align-items-center justify-content-between mb-3">
                              <h6>Nom</h6>
                              <p class="mb-0"><?=$name?></p>
                           </div>
                        </li>
                        <li>
                           <div class="d-flex align-items-center justify-content-between mb-3">
                              <h6>Genre</h6>
                              <p class="mb-0"><?php if($userinfo['genre'] == 'H'){echo 'Homme';}elseif($userinfo['genre'] == 'F'){echo 'Femme';}else{echo 'non renseigné';}?></p>
                           </div>
                        </li>
                        <li>
                           <div class="d-flex align-items-center justify-content-between mb-3">
                              <h6>Téléphone</h6>
                              <p class="mb-0"><?php if($userinfo['numero_tel'] != NULL){echo $userinfo['numero_tel'];}else{echo 'non renseigné';}?></p>
                           </div>
                        </li>
                        <li>
                           <div class="d-flex align-items-center justify-content-between mb-3">
                              <h6>Email</h6>
                              <p class="mb-0"><?= $userinfo['mail']?></p>
                           </div>
                        </li>
                        <li>
                           <div class="d-flex align-items-center justify-content-between mb-3">
                              <h6>Taille</h6>
                              <p class="mb-0"><?php if($userinfo['taille'] != NULL){echo ''.$userinfo['taille'].' m';}else{echo 'non renseigné';}?></p>
                           </div>
                        </li>
                        <li>
                           <div class="d-flex align-items-center justify-content-between mb-3">
                              <h6>Poids</h6>
                              <p class="mb-0"><?php if($userinfo['poids'] != NULL){echo ''.$userinfo['poids'].' Kg';}else{echo 'non renseigné';}?></p>
                           </div>
                        </li>
                        <li>
                           <div class="d-flex align-items-center justify-content-between mb-3">
                              <h6>Age</h6>
                              <p class="mb-0"><?php if($userinfo['age'] != NULL){echo ''.$userinfo['age'].' ans';}else{echo 'non renseigné';}?></p>
                           </div>
                        </li>
                     </ul>
                  </div>
               </div>
               <div class="iq-card">
                  <div class="iq-card-header d-flex justify-content-between align-items-center mb-0">
                     <div class="iq-header-title">
                        <h4 class="card-title mb-0">Autres Stats</h4>
                     </div>
                  </div>
                  <div class="iq-card-body">
                     <ul class="list-inline p-0 mb-0">
                        <li>
                           <div class="d-flex align-items-center justify-content-between mb-3">
                              <h6>Séance par Semaine</h6>
                              <div class="iq-progress-bar-linear d-inline-block mt-1 w-50">
                                 <div class="iq-progress-bar iq-bg-primary">
                                    <span class="bg-primary" data-percent="<?=$nseps?>"><?=$nseps?></span>
                                 </div>
                              </div>
                           </div>
                        </li>
                        <li>
                           <div class="d-flex align-items-center justify-content-between mb-3">
                              <h6>Séance par Mois</h6>
                              <div class="iq-progress-bar-linear d-inline-block mt-1 w-50">
                                 <div class="iq-progress-bar iq-bg-danger">
                                    <span class="bg-danger" data-percent="<?=$nsepms?>"><?=$nsepms?></span>
                                 </div>
                              </div>
                           </div>
                        </li>
                        <li>
                           <div class="d-flex align-items-center justify-content-between mb-3">
                              <h6>###</h6>
                              <div class="iq-progress-bar-linear d-inline-block mt-1 w-50">
                                 <div class="iq-progress-bar iq-bg-info">
                                    <span class="bg-info" data-percent="0"></span>
                                 </div>
                              </div>
                           </div>
                        </li>
                        <li>
                           <div class="d-flex align-items-center justify-content-between">
                              <h6>###</h6>
                              <div class="iq-progress-bar-linear d-inline-block mt-1 w-50">
                                 <div class="iq-progress-bar iq-bg-success">
                                    <span class="bg-success" data-percent="0"></span>
                                 </div>
                              </div>
                           </div>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
                  <div class="col-md-6">
                     <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between align-items-center mb-0">
                           <div class="iq-header-title">
                              <h4 class="card-title mb-0">Indice</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <ul class="list-inline p-0 mb-0">
                              <li>
                                 <div class="iq-details mb-2">
                                    <span class="title">Masse Musculaire</span>
                                    <div class="percentage float-right text-danger">0 <span>%</span></div>
                                    <div class="iq-progress-bar-linear d-inline-block w-100">
                                       <div class="iq-progress-bar">
                                          <span class="bg-primary" data-percent="0"></span>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li>
                                 <div class="iq-details mb-2">
                                    <span class="title">Masse Musculaire idéale</span>
                                    <div class="percentage float-right text-primary">0 <span>%</span></div>
                                    <div class="iq-progress-bar-linear d-inline-block w-100">
                                       <div class="iq-progress-bar">
                                          <span class="bg-primary" data-percent="0"></span>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li>
                                 <div class="iq-details mb-2">
                                    <span class="title">Masse Corporelle</span>
                                    <div class="percentage float-right text-warning"><?=IMC($userinfo['poids'], $userinfo['taille']);?> <span>%</span></div>
                                    <div class="iq-progress-bar-linear d-inline-block w-100">
                                       <div class="iq-progress-bar">
                                          <span class="bg-warning" data-percent="<?=IMC($userinfo['poids'], $userinfo['taille']);?>"></span>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li>
                                 <div class="iq-details mb-2">
                                    <span class="title">Masse Corporelle idéale</span>
                                    <div class="percentage float-right text-info">0 <span>%</span></div>
                                    <div class="iq-progress-bar-linear d-inline-block w-100">
                                       <div class="iq-progress-bar">
                                          <span class="bg-info" data-percent="0"></span>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li>
                                 <div class="iq-details mb-2">
                                    <span class="title">Poids Idéal Minimal</span>
                                    <div class="percentage float-right text-danger"><?=PoidsIdealMinimal($userinfo['taille']);?> Kg</div>
                                    <div class="iq-progress-bar-linear d-inline-block w-100">
                                       <div class="iq-progress-bar">
                                          <span class="bg-danger" data-percent="<?=PoidsIdealMinimal($userinfo['taille']);?>"></span>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li>
                                 <div class="iq-details">
                                    <span class="title">Poids Idéal Moyen</span>
                                    <div class="percentage float-right text-success"><?=PoidsIdeal($userinfo['taille']);?> Kg</div>
                                    <div class="iq-progress-bar-linear d-inline-block w-100">
                                       <div class="iq-progress-bar">
                                          <span class="bg-success" data-percent="<?=PoidsIdeal($userinfo['taille']);?>"></span>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li>
                                 <div class="iq-details">
                                    <span class="title">Poids Idéal Maximal</span>
                                    <div class="percentage float-right text-info"><?=PoidsIdealMaximal($userinfo['taille']);?> Kg</div>
                                    <div class="iq-progress-bar-linear d-inline-block w-100">
                                       <div class="iq-progress-bar">
                                          <span class="bg-info" data-percent="<?=PoidsIdealMaximal($userinfo['taille']);?>"></span>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li>
                                 <div class="iq-details">
                                    <br>
                                    <div class="percentage float-left text-danger">Attention ! Ces données ne sont que des valeurs indicatives et peuvent ne pas être totalement coherentes !</div>
                                 </div>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>               
      <!-- Wrapper END -->      

<!-- SCRIPT -->
   <?php
      include('include/include-script.php');
   ?>
   </body>
</html>
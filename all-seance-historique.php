<?php
//============================VERIFICATION SEANCE ==========================//
include('include/include-verif-session.php');
//=========================FIN VERIFICATION SEANCE =========================//

//======================INCLUDE POUR TITLE + CONNEXION BDD==================//
include('include/include_bdd.php');
include('include/include_info.php');
//====================FIN INCLUDE POUR TITLE + CONNEXION BDD================//

//===================VAR DE SESSION POUR AFFICHER NAVBAR====================//
$_SESSION['page'] = "Historique de Séance";
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
<html lang="en" style="<?= $userinfo['color']?>">
<head>
   <!-- Required meta tags -->
   <base href="/">
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title><?=title?></title>
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

<body>
   <!-- loader Start -->
   <div id="loading">
      <div id="loading-center"></div>
   </div>
   <!-- loader END -->
   <!-- Wrapper Start -->
    <div class="wrapper">
        <?php 
        // INCLUDE NAVBAR //
        include('include/include-navbar.php');
        // FIN INCLUDE NAVBAR //
        ?>
        <!-- DERNIERES SEANCES -->
        <div id="content-page" class="content-page">
            <div class="container-fluid">
                <div class="row"> 
                    <div class="col-lg-12">
                        <div class="iq-card">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Dernières Séances</h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <ul class="iq-timeline">
                                <?php 
                                $reqschist = $bdd->prepare('SELECT * FROM `historique_seance_user` WHERE id_user = ? ORDER BY `date` DESC');
                                $reqschist->execute(array($_SESSION['idmbrs']));

                                if($reqschist){
                                    while ($info = $reqschist->fetch()) {

                                        $reqinfohist = $bdd->prepare('SELECT `id`, `nom_seance` FROM `seances_info` WHERE `id` = ?');
                                        $reqinfohist->execute(array($info['id_seance_info']));
                                        $scinfohist = $reqinfohist->fetch();

                                        $reqinfohist2 = $bdd->prepare('SELECT `id_seance`, `type` FROM `seances` WHERE `id_difficulte` = ?');
                                        $reqinfohist2->execute(array($info['id_seance_difficulte']));
                                        $scinfohist2 = $reqinfohist2->fetch();

                                        if($scinfohist2['type'] == 'normal'){
                                            $typesc = 'Normal';
                                        }
                                        elseif($scinfohist2['type'] == 'advanced'){
                                            $typesc = 'Advanced';
                                        }
                                        else{
                                            $typesc = 'Custom';
                                        }
                                        
                                        $explode = explode(' ', $info['date']);
                                        $date = $explode[0];
                                        $heures = $explode[1];

                                        $explode_date = explode('-', $date);
                                        $annee = $explode_date[0];
                                        $mois =  $explode_date[1];
                                        $jour =  $explode_date[2];

                                        $mois = obtenirLibelleMois($mois);
                                        $class_timeline = colortimeline();
                                        echo '
                                    <li>
                                        <div class="timeline-dots '.$class_timeline.'"><i class="las la-dumbbell"></i></div>
                                        <h6 class="float-left mb-1">'.$scinfohist['nom_seance'].' '.$typesc.'</h6>
                                        <small class="float-right mt-1">'.$jour.' '.$mois.' '.$annee.'</small>
                                        <div class="d-inline-block w-100">
                                            <p>Durée de la séance '.$info['duree_seance'].'</p>
                                        </div>
                                    </li>';
                                    } 
                                echo '</ul>';                          
                                }
                                else{  
                                    echo '</ul>';  
                                    echo 'Vous n\'avez pas encore réalisé de séances :/ <a href="#menu-seances" class="collapsed" data-toggle="collapse" aria-expanded="false">Commencer votre sport dés maintenant !</a>';                
                                }                        
                                ?>
                                
                            </div>
                        </div>
                    </div>
                    <!-- FIN DERNIERES SEANCES -->   
    </div>
   <!-- Wrapper END -->

   <?php
      // INCLUDE CHAT //
      include('include/include-chat.php');
      // FIN INCLUDE CHAT //
   ?>

<!-- SCRIPT -->
<?php
   // INCLUDE SCRIPT //
   include('include/include-script.php');
   // FIN INCLUDE SCRIPT //
?>   
</body>
</html>
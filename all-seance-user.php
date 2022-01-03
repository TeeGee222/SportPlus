<?php
//============================VERIFICATION SEANCE ==========================//
include('include/include-verif-session.php');
//=========================FIN VERIFICATION SEANCE =========================//

//======================INCLUDE POUR TITLE + CONNEXION BDD==================//
include('include/include_bdd.php');
include('include/include_info.php');
//====================FIN INCLUDE POUR TITLE + CONNEXION BDD================//

//===================VAR DE SESSION POUR AFFICHER NAVBAR====================//
$_SESSION['page'] = "Vos Séances";
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
                                    <h4 class="card-title">Vos Séances</h4>
                                </div>
                            </div>
                            <form action="" method="post">
                            <div class="iq-card-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nom</th>
                                            <th scope="col">Confidentialité</th>
                                            <th scope="col">Creation</th>
                                            <th scope="col">Partage</th>
                                            <th scope="col">Gestion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php 
                                $reqscuser = $bdd->prepare('SELECT * FROM `seances_info` WHERE id_createur = ?');
                                $reqscuser->execute(array($_SESSION['idmbrs']));

                                if($reqscuser){
                                    while ($info = $reqscuser->fetch()) {
                                        if($info['private'] == 0){
                                            $private = 'Publique';
                                        }
                                        elseif($info['private'] == 1){
                                            $private = 'Privée';
                                        }
                                        else{
                                            $private = 'Mes amis seulement';
                                        }

                                        $explode = explode(' ', $info['date_creation']);
                                        $date = $explode[0];
                                        $heures = $explode[1];
            
                                        $explode_date = explode('-', $date);
                                        $annee = $explode_date[0];
                                        $mois =  $explode_date[1];
                                        $jour =  $explode_date[2];
            
                                        $mois = obtenirLibelleMois($mois);

                                        echo '
                                    <tr>
                                        <td><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $info['nom_seance'] .';'. $info['id'] .';'. $info['ids_custom'] .';custom;'. $info['id_createur'] .'"><a class="text-primary"></i>'. $info['nom_seance'] .'</button></a></td>
                                        <td>'.$private.'</td>
                                        <td>'.$jour.' '.$mois.' '.$annee.'</td>
                                        <td><i class="fa fa-share-alt" aria-hidden="true"></i></td>
                                        <td><a class="btn iq-bg-danger btn-rounded btn-sm" href="">Supprimer</a></td>
                                     </tr>';
                                    }                        
                                }
                                else{ 
                                    echo 'Vous n\'avez pas encore crée de séances :/ <a href="custom-create.php?id='.$userid.'">Créer une séance dés maintenant</a>';                
                                }                        
                                ?>
                                    </tbody>
                                </table>
                            </div>
                            </form>
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
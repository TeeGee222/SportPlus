<?php
//============================VERIFICATION SEANCE ==========================//
include('include/include-verif-session.php');
//=========================FIN VERIFICATION SEANCE =========================//

//======================INCLUDE POUR TITLE + CONNEXION BDD==================//
include('include/include_bdd.php');
include('include/include_info.php');
//====================FIN INCLUDE POUR TITLE + CONNEXION BDD================//

//===================VAR DE SESSION POUR AFFICHER NAVBAR====================//
$_SESSION['page'] = "sport";
//=================FIN VAR DE SESSION POUR AFFICHER NAVBAR==================//

//==RECUPERATION ID DANS URL +VERIFICATION BDD + RECUPERATION INFORMATION==//
include('include/include-recup-info-user.php');
//FIN RECUPERATION ID DANS URL +VERIFICATION BDD + RECUPERATION INFORMATION//

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
<?php
//==================================SPORT==================================//

// RECUPERATION INFO DE LA SEANCE EN COURS //
$reqsc = $bdd->prepare('SELECT * FROM seances_info WHERE id = ?');
$reqsc->execute(array($_SESSION['id_seance_info']));
$scinfo = $reqsc->fetch();

$nom_createur_req = $bdd->prepare('SELECT `idmbrs`, `name` FROM `membres` WHERE idmbrs = ?');
$nom_createur_req->execute(array($scinfo['id_createur']));
$nom_createur = $nom_createur_req->fetch();
// FIN RECUPERATION INFO DE LA SEANCE EN COURS //

// COMPTEUR NOMBRE D'EXERCICE SEANCE (AVEC REPOS) //
$nombre_exo_seance = nombre_exo_seance($_SESSION['difficulte_seance_id'], $bdd);
$ratio = round(($_SESSION['exercice_en_cours']/$nombre_exo_seance)*100);

// FIN COMPTEUR NOMBRE D'EXERCICE SEANCE (AVEC REPOS) //


// INFO DE L'EXERCICE A FAIRE //
$requete_exercice = 'exercice'.$_SESSION['exercice_en_cours'].'';
$reqex = $bdd->prepare("SELECT $requete_exercice FROM seances WHERE id_difficulte = ?");
$reqex->execute(array($_SESSION['difficulte_seance_id']));
$exinfo = $reqex->fetch();
// FIN INFO DE L'EXERCICE A FAIRE //

if($exinfo[$requete_exercice]){
    // SEPARATION ID EXERCICE & PARAMETRE DE CELUI-CI //
    $exoparametre = explode(";",$exinfo[$requete_exercice]);
    $id_exercice = $exoparametre[0];
    $param_exercice = $exoparametre[1];

    if (strpos($exoparametre[1], ":") !== FALSE) {
        $param_exo_now_repet = NULL;
        $param_exo_now = explode(":",$exoparametre[1]);
        $param_exo_now_minutes = $param_exo_now[0];
        $param_exo_now_secondes = $param_exo_now[1];
        $exercice_repetition_ou_timer = 2; //TIMER
    }
    else{
        $param_exo_now_minutes = NULL;
        $param_exo_now_secondes = NULL;
        $param_exo_now_repet = $exoparametre[1];
        $exercice_repetition_ou_timer = 1; //REPETITION
    }
    // FIN SEPARATION ID EXERCICE & PARAMETRE DE CELUI-CI //

    // REQUETE INFO POUR CHAQUE EXERCICE //
    $reqinfoex = $bdd->prepare("SELECT * FROM exercices WHERE id = ?");
    $reqinfoex->execute(array($id_exercice));
    $exinfo_now = $reqinfoex->fetch();
    // FIN REQUETE INFO POUR CHAQUE EXERCICE //

    // STOCKAGE INFO DANS VARIABLE //
    $exercice_en_cours = $exinfo_now['nom'];
    $exercice_info = $exinfo_now['information'];
    $exercice_illustration = $exinfo_now['illustration'];
    // FIN STOCKAGE INFO DANS VARIABLE //
}
else{
    //FIN DE SEANCE
    header('Location: end-sport-redirect.php');
} 

if(isset($_POST['exo_suivant'])){
    $exercice_1_id = $_SESSION['exercice_en_cours']+1; //+1 POUR PASSER A L'EXERCICE SUIVANT //
    $_SESSION['exercice_en_cours'] = $exercice_1_id;
    header('Location: sport.php?id='.$_SESSION['idmbrs']);
}


//PARAMETRES TIMER //
$heures   = 0;  // LES HEURES < 24 //
$minutes  = @$param_exo_now_minutes;   // LES MINUTES  < 60 //
$secondes = @$param_exo_now_secondes;  // LES SECONDES < 60 //

//ARRIVER DU COMPTEUR A 0 //
$redirection = "sport.php?id=$userid";

//CALCUL DES SECONDES //
$secondes = mktime(date("H") + $heures, date("i") + $minutes, date("s") + $secondes) - time();

if(isset($_POST['pause-handler'])){
    $_SESSION['date_debut_pause'] = time();
    header('Location: index.php?id='.$userid.'');      
}
if(isset($_POST['end-sport-handler'])){
    header('Location: end-sport-redirect.php');      
}

?>

<!doctype html>
<html lang="en" style="<?= $userinfo['color']?>">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo title; ?></title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/logo.png"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Typography CSS -->
    <link rel="stylesheet" href="css/typography.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="style-test.css">
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
        <?php 
            // INCLUDE INDEX-CONTENT //
            //include('include/include-index-content.php');
            // FIN INCLUDE INDEX-CONTENT //
        ?>        
        <div id="content-page" class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">                                  
                        <div class="iq-card">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Vous travaillez <?=$scinfo['nom_seance']?></h4>
                                </div>
                                <div class="d-flex align-items-center">
                                    <b class="mb-0 text-primary"><a data-toggle="modal" data-target="#seance-end-sure" href=""><i class="las la-times"></i>Arréter la séance</a></b>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <div class="card text-center">

                                    <!-- BAR DE PROGRESSION DE LA SEANCE -->
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: <?=$ratio?>%;" aria-valuenow="<?=$ratio?>" aria-valuemin="0" aria-valuemax="100"><?=$ratio?>%</div>
                                    </div>
                                    <!-- FIN BAR DE PROGRESSION DE LA SEANCE -->

                                    <!-- SLIDER CARD EXO SEANCE-->
                                    <div class="card " style="width: 18rem;">
                                        <img src="images/exercices/<?=$exercice_illustration?>" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h5 class="card-title"><?=$exercice_en_cours?> <?php if($exercice_repetition_ou_timer == 1){echo "<span class='badge badge-primary'>X$param_exo_now_repet</span>";}?></h5>
                                                <h2><a data-toggle="modal" data-target="#exo-info" href="" class="text-primary"><i class="fa fa-info-circle" aria-hidden="true"></i></a></h2>
                                            </div>
                                        </div>

                                    <!-- FIN SLIDER CARD EXO SEANCE-->

                                    <?php 
                                        if($exercice_repetition_ou_timer == 1){// REPETITION //
                                            echo"
                                            <form method='post'>
                                                <button type='submit' class='btn btn-primary' name='exo_suivant'>Suivant !</button>
                                            </form>
                                            <br>
                                            "; 
                                        }
                                        elseif($exercice_repetition_ou_timer == 2 OR $exercice_repetition_ou_timer == 3){// TIMER & REPOS //
                                            echo "
                                            <script>
                                                var temps = $secondes;
                                                var timer = setInterval('CompteaRebour()',1000);
                                            </script>

                                            <ul class='countdown d-flex align-items-center list-inline row'>
                                                <li class='col-md-12 col-lg-6'>
                                                    <div class='iq-card'>
                                                        <div class='iq-card-body'>
                                                            <span id='minutes'>0</span>Minutes
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class='col-md-12 col-lg-6'>
                                                    <div class='iq-card'>
                                                        <div class='iq-card-body'>
                                                            <span id='secondes'>0</span>Seconds
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <form method='post'>
                                                <button type='submit' class='btn btn-primary' name='exo_suivant'>Passer !</button>
                                            </form>
                                            <br>
                                            ";
                                        }
                                    ?>

                                    <div class="card-footer text-muted">Séance créée par <?=$nom_createur['name']?></div>

                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL FIN SEANCE VIA BUTTON -->
        <div class="modal fade" id="seance-end-sure" tabindex="-1" role="dialog" aria-labelledby="seance-end-sure" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="seance-end-sure">N'arretez pas maintenant !</h5>
                    </div>

                    <div class="modal-body">                           
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <form action="" method="post">
                        <button type="submit" class="btn btn-info" name="pause-handler">Revenir plus tard</button>
                        <button type="submit" class="btn btn-primary" name="end-sport-handler">J'arrête</button>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
            <!-- FIN MODAL FIN SEANCE VIA BUTTON -->

            <!-- MODAL INFO EXO -->
            <div class="modal fade" id="exo-info" tabindex="-1" role="dialog" aria-labelledby="exo-info" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="exo-info"><i class="fa fa-info-circle" aria-hidden="true"></i> Information</h5>
                        </div>

                        <div class="modal-body">      
                        <p><?=$exercice_info?></p>                  
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Continuer</button>
                        </div>

                    </div>
                </div>
            </div>
            <!-- FIN MODAL INFO EXO -->
         
        <?php
            // INCLUDE CHAT //
            //include('include/include-chat.php');
            // FIN INCLUDE CHAT //
        ?>
         
    </div>
    <!-- Wrapper END -->


<?php
    // INCLUDE SCRIPT JS //
    include('include/include-script.php');
    // FIN INCLUDE SCRIPT JS //
?>
<script> 

function CompteaRebour(){
    temps-- ;
    m = parseInt((temps%3600)/60) ;
    s = parseInt((temps%3600)%60) ;
    document.getElementById('minutes').innerHTML= (m<10 ? "0"+m : m);
    document.getElementById('secondes').innerHTML= (s<10 ? "0"+s : s);
    if ((s == 0 && m ==0)) {
        clearInterval(timer);
        url = "<?= $redirection;?>"
        ajax();
        Redirection(url);
    }
}

function Redirection(url) {
    setTimeout("window.location=url", 500);
}

function ajax() {
        
    $.ajax({
        type: "POST",
        url: 'ajax/sport-ajax-add-plus-one.php',
    });
};	

</script>

</body>
</html>

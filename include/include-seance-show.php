<?php 
// COMPTEUR NOMBRE D'EXERCICE SEANCE (AVEC REPOS) //
$req_all_exo = $bdd->prepare('SELECT * FROM seances WHERE id_difficulte = ?');
$req_all_exo->execute(array($_SESSION['difficulte_seance_id']));
$req_all_exo_fetch = $req_all_exo->fetch();
$nombre_exo_seance = 0;
$nombre_repos_seance = 0;

for ($i=1; $i <= 100 ; $i++) { 

    $requete_all_exo = 'exercice'.$i.'';

    if($req_all_exo_fetch[$requete_all_exo]){

        // SEPARATION ID EXERCICE //
        $exoparametre = explode(";",$req_all_exo_fetch[$requete_all_exo]);
        $id_exercice = $exoparametre[0];
        // SEPARATION ID EXERCICE //

        if($id_exercice == 18){// EXO == REPOS //
            $nombre_repos_seance = $nombre_repos_seance+1;
        }
        else{
            $nombre_exo_seance =  $nombre_exo_seance+1;
        }       
    }
}
$ratio_exo = round($nombre_exo_seance*100);
$ratio_repos = round($nombre_repos_seance*100);
// FIN COMPTEUR NOMBRE D'EXERCICE SEANCE (AVEC REPOS) //

$normal_advanced = $_SESSION['difficulte_seance'];

?>
<style>
    .scroll {
    max-height: 500px;
    overflow-y: scroll;
    padding: 1rem;
    overflow-x: hidden;
}
</style>
<div class="iq-card">
    <form action="" method="post">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Prévisualisation de <?=$_SESSION['seance_choisis']?> <?=$_SESSION['difficulte_seance']?></h4>
            </div>
            <?php 
                if($_SESSION['difficulte_seance'] != 'normal' OR $_SESSION['difficulte_seance'] != 'advanced'){
                    echo"<div class='d-flex align-items-center'>
                            <b class='mb-0 text-primary'><a href='sport-edit.php?id=".$userid."'>Modifier la Séance</a></b>
                        </div>";
                }            
            ?>
        </div>        
        <div class="iq-card-body scroll">
            <div class='progress'>
                <div class='progress-bar' id='progress-bar-exo' role='progressbar' style='width: <?=$ratio_exo?>%' aria-valuenow='<?=$ratio_exo?>' aria-valuemin='0' aria-valuemax='100'>Exercices</div>
                <div class='progress-bar bg-success' id='progress-bar-repos' role='progressbar' style='width: <?=$ratio_repos?>%' aria-valuenow='<?=$ratio_repos?>' aria-valuemin='0' aria-valuemax='100'>Repos</div>
            </div>
            <br>
                <ul class="iq-timeline">
                <?php 
                // BOUCLE POUR CHAQUE EXERCICE //
                $nombre_exercice = 0;
                for($id_exercice = 1; $id_exercice <= 100; $id_exercice++){

                    // RECUPERATION CHAQUE EXERCICES DE LA SEANCE //
                    $id_nom_exercice = "exercice$id_exercice";
                    $reponse = $bdd->prepare('SELECT * FROM seances WHERE id_difficulte = ?');
                    $reponse->execute(array($_SESSION['difficulte_seance_id']));
                    $donnees = $reponse->fetch();

                    // L'EXERCICE EXISTE //
                    if($donnees[$id_nom_exercice]){ 

                        // SEPARATION ID EXERCICE & PARAMETRE DE CELUI-CI //
                        $exoparametre = explode(";",$donnees[$id_nom_exercice]);
                        $id_exo_now = $exoparametre[0];
                        
                        if (strpos($exoparametre[1], ":") !== FALSE) {
                            $param_exo_now_repet = NULL;
                            $param_exo_now = explode(":",$exoparametre[1]);
                            $param_exo_now_minutes = $param_exo_now[0];
                            $param_exo_now_secondes = $param_exo_now[1];
                            
                            if($id_exo_now == 18){
                                $repetition_ou_timer = 3; //REPOS
                            }
                            else{
                                $repetition_ou_timer = 2; //TIMER
                            }
                        }
                        else{
                            $param_exo_now_minutes = NULL;
                            $param_exo_now_secondes = NULL;
                            $param_exo_now_repet = $exoparametre[1];
                            $repetition_ou_timer = 1; //REPETITION
                        }
                        // FIN SEPARATION ID EXERCICE & PARAMETRE DE CELUI-CI //

                        $nombre_exercice = $nombre_exercice+1;// INCREMENTATION COMPTEUR D'EXERCICE //

                        // REQUETE INFO POUR CHAQUE EXERCICE //
                        $reqinfoex = $bdd->prepare("SELECT * FROM exercices WHERE id = ?");
                        $reqinfoex->execute(array($id_exo_now));
                        $exinfo = $reqinfoex->fetch();
                        // FIN REQUETE INFO POUR CHAQUE EXERCICE //

                        // STOCKAGE INFO DANS VARIABLE //  
                        $nom_exercice = $exinfo['nom'];
                        // FIN STOCKAGE INFO DANS VARIABLE //
                        $class_timeline = colortimeline();

                        if($repetition_ou_timer == 1){ //REPETITION //
                            echo '
                            <li>
                                <div class="timeline-dots border-primary"><i class="las la-dumbbell"></i></div>
                                <h6 class="float-left mb-1">'.$nom_exercice.'</h6>
                                <small class="float-right mt-1">X'.$param_exo_now_repet.'</small>
                                <div class="d-inline-block w-100">
                                    <p>X'.$param_exo_now_repet.'</p>
                                </div>
                            </li>';
                        }
                        elseif($repetition_ou_timer == 2){ // TIMER //
                            echo '
                            <li>
                                <div class="timeline-dots border-primary"><i class="las la-dumbbell"></i></div>
                                <h6 class="float-left mb-1">'.$nom_exercice.'</h6>
                                <small class="float-right mt-1">'.$param_exo_now_minutes.':'.$param_exo_now_secondes.'</small>
                                <div class="d-inline-block w-100">
                                <p>'.$param_exo_now_minutes.':'.$param_exo_now_secondes.'</p>
                            </div>
                            </li>';
                        }
                        elseif($repetition_ou_timer == 3){
                            echo '
                            <li>
                                <div class="timeline-dots border-success"><i class="fa fa-bed" aria-hidden="true"></i></div>
                                <h6 class="float-left mb-1">'.$nom_exercice.'</h6>
                                <small class="float-right mt-1">'.$param_exo_now_minutes.':'.$param_exo_now_secondes.'</small>
                                <div class="d-inline-block w-100">
                                    <p>'.$param_exo_now_minutes.':'.$param_exo_now_secondes.'</p>
                                </div>
                            </li>';

                        } 
                    }
                    // FIN CONDITIONS EXERCICE EXISTE //
                }  
                // FIN BOUCLE POUR CHAQUE EXERCICE //   
                echo '</ul>';                                                                          
                ?>
            </div>
            
            <div class='modal-footer'>
                <button type='submit' class='btn btn-success' name='start_seance'>Démarrer</button>
            </div>
        </div>
    </form>



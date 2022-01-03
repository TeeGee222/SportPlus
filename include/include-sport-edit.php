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

echo "
<form action='' method='POST'>
<div class='col-lg-12'>
    <div class='iq-card'>
    <div class='iq-card-header d-flex justify-content-between'>
        <div class='iq-header-title'>
            <h4 class='card-title'><a href='' class='text-primary'><i class='fa fa-info-circle'></i></a> Paramètre de votre séances $normal_advanced </h4>
        </div>
        <div class='d-flex align-items-center'>
            <div class='custom-control custom-switch custom-switch-text custom-control-inline'>
                <div class='custom-switch-inner'>
                    <p class='mb-0'> Repos </p>
                    <input type='checkbox' name='repos_show' class='custom-control-input' id='hide_show_repos' onclick='hide_show(this)'>
                    <label class='custom-control-label' for='hide_show_repos' data-on-label='On' data-off-label='Off'></label>
                </div>
            </div>
        </div>
    </div>
    <div class='iq-card-body'>
    <!-- BAR DE PROGRESSION DE LA SEANCE -->

    <div class='progress'>
        <div class='progress-bar' id='progress-bar-exo' role='progressbar' style='width: $ratio_exo%' aria-valuenow='$ratio_exo' aria-valuemin='0' aria-valuemax='100'>Exercices</div>
        <div class='progress-bar bg-success' id='progress-bar-repos' role='progressbar' style='width: $ratio_repos%' aria-valuenow='$ratio_repos' aria-valuemin='0' aria-valuemax='100'>Repos</div>
    </div>
    <br>

    <span class='table-add float-right mb-3 mr-2'>
        <button class='btn btn-sm iq-bg-success'  ><i class='ri-add-fill'><span class='pl-1'>Nouvel élément</span></i></button>
    </span>

    <!-- FIN BAR DE PROGRESSION DE LA SEANCE -->
    <div id='table' class='table-editable'>
    <table class='table table-bordered table-responsive-md text-center' id='tableau_exercice'>
        <tbody>";
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

                    // AFFICHAGE DES INPUTS EN FONCTION DU TIMER OU DES REPETITION //
                    
                    echo "<tr id='tr$id_exercice'>
                            <td id='td$id_exercice'>$id_exercice</td>
                            <td>
                                <div class='row'>
                                    <div id='selectplacement$id_exercice' class='col'>
                                        <select id='select$id_exercice' class='form-control select-big' name='exercice[]' onchange='remplissageAuto(this);'  >";
                                        $exo = $bdd->prepare("SELECT * FROM `exercices`");
                                        $exo->execute();
                                        $nbr_exo = 1;
                                        while ($donnees = $exo->fetch())
                                        {
                                            if($donnees['nom'] == $nom_exercice){
                                                echo '<option id="'.$nbr_exo.'_value'.$id_exercice.'" value="'.$donnees['nom'].'" selected>'.$donnees['nom'].'</option>';
                                            }
                                            else{
                                                echo '<option id="'.$nbr_exo.'_value'.$id_exercice.'" value="'.$donnees['nom'].'">'.$donnees['nom'].'</option>';
                                            }
                                            $nbr_exo = $nbr_exo+1;
                                        }
                    echo "            </select>
                                    </div>";
                                    if($repetition_ou_timer == 1){ //REPETITION //
                                        echo " 
                                        <div class='custom-control custom-switch custom-switch-text custom-control-inline'>            
                                            <div class='custom-switch-inner'>
                                                <input type='checkbox' value='$id_exercice' class='custom-control-input' id='checkbox$id_exercice' onclick='checkbox(this)' >
                                                <label class='custom-control-label' id='label_repos_hide_show$id_exercice' for='checkbox$id_exercice' data-on-label='T' data-off-label='R'></label>
                                            </div>
                                        </div>              

                                        <div class='col' id='replace_$id_exercice'>
                                            <input type='number' name='input_repetition$id_exercice' class='form-control select-big' id='input_repetition$id_exercice' placeholder='Répétition:' value='$param_exo_now_repet'  >
                                        </div>
                                        <div class='col' id='replace2_$id_exercice'>

                                        </div>";
                                    }
                                    elseif($repetition_ou_timer == 2){ // TIMER //
                                        echo "    
                                        <div class='custom-control custom-switch custom-switch-text custom-control-inline'>            
                                            <div class='custom-switch-inner'>
                                                <input type='checkbox' value='$id_exercice' class='custom-control-input' id='checkbox$id_exercice' checked onclick='checkbox(this)'  >
                                                <label class='custom-control-label' id='label_repos_hide_show$id_exercice' for='checkbox$id_exercice' data-on-label='T' data-off-label='R'></label>
                                            </div>
                                        </div>
                                        
                                        <div class='col' id='replace_$id_exercice'>
                                        <input type='number' name='input_minutes$id_exercice' class='form-control select-big' id='input_minutes$id_exercice' placeholder='Minutes:' value='$param_exo_now_minutes'  >
                                        </div>
                                        <div class='col' id='replace2_$id_exercice'>
                                        <input type='number' name='input_secondes$id_exercice' class='form-control select-big' id='input_secondes$id_exercice' placeholder='Secondes:' value='$param_exo_now_secondes'  >
                                        </div>";
                                    }
                                    elseif($repetition_ou_timer == 3){
                                        echo "   
                                        <div class='custom-control custom-switch custom-switch-text custom-control-inline'>            
                                            <div class='custom-switch-inner'>
                                                <input type='checkbox' value='$id_exercice' class='custom-control-input' id='checkbox$id_exercice' checked onclick='checkbox(this)' disabled>
                                                <label class='custom-control-label' id='label_repos_hide_show$id_exercice' for='checkbox$id_exercice' data-on-label='T' data-off-label='R'></label>
                                            </div>
                                        </div>

                                        <div class='col' id='replace_$id_exercice'>
                                            <input type='number' name='input_minutes_repos$id_exercice' class='form-control select-big' id='input_minutes_repos$id_exercice' placeholder='Minutes:' value='$param_exo_now_minutes' >
                                        </div>
                                        <div class='col' id='replace2_$id_exercice'>
                                            <input type='number' name='input_secondes_repos$id_exercice'class='form-control select-big' id='input_secondes_repos$id_exercice' placeholder='Secondes:' value='$param_exo_now_secondes' >
                                        </div>"; 
                                    }
                                    // FIN AFFICHAGE DES INPUTS EN FONCTION DU TIMER OU DES REPETITION //
                    echo "
                        </div>
                    </td>
                    <td>
                        <span class='table-remove'>
                            <button type='button' class='btn iq-bg-danger btn-rounded btn-sm'>Supprimer</button>
                        </span>
                    </td>                
                </tr>                                                     
                    ";                                                                  
                }
                // FIN CONDITIONS EXERCICE EXISTE //
                }
                // FIN BOUCLE POUR CHAQUE EXERCICE //                             
    echo "  </tbody>
        </table>";
if($nombre_exercice == 0){
    echo "Il semblerait que la séance ne comporte aucun exercice définis !";
}
else{
    echo "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalseance_before_start'>Suivant</button>";
}
        
    echo "            
    <!--MODAL SEANCE BEFORE START-->
    <div class='modal fade' id='modalseance_before_start' tabindex='-1' role='dialog' aria-labelledby='modalseance_before_start' aria-hidden='true'>
        <div class='modal-dialog modal-dialog-centered' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='modalseance_before_start'>Paramètres Supplémentaires</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class='form-group'>
                        <label for='nseance'>Nom de la Seance:</label>
                        <input type='text' name='nomseance' class='form-control' id='nseance' placeholder='".@$_SESSION['seance_choisis']."'>
                    </div>
                    <select class='form-control' name='selectconfiseance'>
                        <option value='NULL' selected='' disabled=''>Confidentialité</option>
                        <option value='0'>Public</option>
                        <option value='1'>Privée</option>
                        <option value='2'>Mes Amis Seulement</option>
                    </select>
                    <div class='custom-control custom-checkbox custom-control-inline'>
                        <input type='checkbox' class='custom-control-input' id='customCheck6' name='input_new_seance'>
                        <label class='custom-control-label' for='customCheck6'>Sauvegarder dans une nouvelle séance</label>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Annuler</button>
                    <button type='submit' class='btn btn-success' name='save-seance'>Enregistrer</button>
                    <button type='submit' class='btn btn-primary' name='save-start-seance'>Enregistrer & Démarrer</button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL SEANCE BEFORE START -->
</div>
    </div>
    </div>
</div>
</form>";

// FIN CONDITIONS SEANCE CUSTOM //

?>

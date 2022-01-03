<?php 
function crypt_mdp ($mdp_a_crypte) {
    $mdp = $mdp_a_crypte;
    for ($i=0;$i<65535;$i++) { 
    $mdp = sha1($mdp);
    $mdp = md5($mdp);
    }
    return $mdp;
    }
?>


<div id="content-page" class="content-page">
            <div class="container-fluid">
               <div class="row">
               <div class="col-sm-12 col-lg-12">
                <!-- AFFICHAGE PARAMETRE DE SEANCE -->
                <?php
                    if(isset($_SESSION['idmbrs']) && $_SESSION['idmbrs'] == 14524){
                        //include('include/include-vardump.php');
                    }
                    if(isset($_SESSION['difficulte_seance']) && !isset($_SESSION['exercice_en_cours'])){
                        include('include/include-seance-show.php');
                    }
                    elseif(isset($_SESSION['difficulte_seance']) && isset($_SESSION['exercice_en_cours'])){
                        include('include/include-seance-en-cours.php');
                    }
                ?> 
                <!-- FIN AFFICHAGE PARAMETRE DE SEANCE -->   
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Statistiques personnelle</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <?php 
                            stats_nbr_seances($bdd);
                            if($nseps != 0 OR $nsepms != 0){
                                echo '
                                <ul class="nav nav-tabs justify-content-center" id="myTab-2" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="semaine-tab-justify" data-toggle="tab" href="#semaine-justify" role="tab" aria-controls="semaine" aria-selected="true">Cette Semaine</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="mois-tab-justify" data-toggle="tab" href="#mois-justify" role="tab" aria-controls="mois" aria-selected="false">Ce Mois</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent-3">
                                    <div class="tab-pane fade active show" id="semaine-justify" role="tabpanel" aria-labelledby="semaine-tab-justify">
                                    <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Nombre de séance: 
                                                <span class="badge badge-primary badge-pill">'.$nseps.'</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Temps passé:
                                                <span class="badge badge-success badge-pill">'.$tps.'</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Nombre de séance total:
                                            <span class="badge badge-danger badge-pill">'.$nset.'</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-pane fade" id="mois-justify" role="tabpanel" aria-labelledby="mois-tab-justify">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Nombre de séance: 
                                            <span class="badge badge-primary badge-pill">'.$nsepms.'</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Temps passé:
                                            <span class="badge badge-success badge-pill">'.$tpms.'</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Nombre de séance total:
                                        <span class="badge badge-danger badge-pill">'.$nset.'</span>
                                        </li>
                                    </ul>
                                    </div>
                                </div>
                                <br>
                                ';
                                //echo '<div id="morris-donut-chart"></div>';
                            }
                            else{
                                echo 'Aucune statistiques disponibles pour le moment :/';
                            }
                            ?>
                        </div>
                    </div>

                     <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Vos objectifs</h4>
                            </div>
                            <div class="d-flex align-items-center">
                                <b class="mb-0 text-primary"><a href="#">Tout voir</a></b>
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
                            <!-- MODAL OBJECTIF ADD -->
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
                            <!-- FIN MODAL OBJECTIF ADD -->
                        </div>
                    </div>

                </div>

                <div class="col-sm-12 col-lg-6">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Dernières Séances</h4>
                            </div>
                            <div class="d-flex align-items-center">
                                <b class="mb-0 text-primary"><a href="all-seance-historique.php?id=<?=$userid?>">Tout voir</a></b>
                            </div>
                        </div>
                        <div class="iq-card-body">
                        <ul class="iq-timeline">
                        <?php 
                        $reqschist = $bdd->prepare('SELECT * FROM `historique_seance_user` WHERE id_user = ? ORDER BY `date` DESC LIMIT 4');
                        $reqschist->execute(array($_SESSION['idmbrs']));

                        $reqschist2 = $bdd->prepare('SELECT * FROM `historique_seance_user` WHERE id_user = ? ORDER BY `date` DESC LIMIT 4');
                        $reqschist2->execute(array($_SESSION['idmbrs']));
                        $check_reqschist = $reqschist2->fetch();

                        if($check_reqschist){

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
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Nouveautés</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <?php 
                                echo'Nous sommes désolé mais aucune nouveauté n\'est disponible!';
                            ?>
                        </div>
                    </div>

                </div>

                <div class="col-sm-12 col-lg-12">

                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
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
                        </div>
                    </div>

                    <?php                     
                        if($groupe_user != 0){
                        echo '
                        <div class="iq-card">
                            <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Statistique du groupe <font class="text-primary">'.$groupe_info_user['groupe_name'].'</font> | <font color="gray" align="center">(Nombre de séances par semaine)</font></h4>
                            </div>
                            </div>
                            <div class="iq-card-body">
                                <div id="am-simple-chart" style="height: 300px;"></div>
                            </div>
                        </div>';
                        }
                    ?>   
                </div>

            </div>
         </div>
      </div>


























$req_bringsally2 = $bdd->prepare('SELECT * FROM `bringsallyup` WHERE date = ? AND id_user = ?');
   $req_bringsally2->execute(array($donnes['date'],14528));
   $req_fetch2 = $req_bringsally2->fetch();

   if($req_fetch2){
      if(strlen($req_fetch2['secondes']) == 2){
         $temps = ''.$req_fetch2['minutes'].'.'.$req_fetch2['secondes'].'';
      }
      else{
         $temps = ''.$req_fetch2['minutes'].'.0'.$req_fetch2['secondes'].'';
      }
      
      $data_aubin[] = $temps;
   }
   else{
      $data_aubin[] = 0;
   }
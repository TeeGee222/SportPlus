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
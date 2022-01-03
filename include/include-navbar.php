<!-- Sidebar  -->
<div class="iq-sidebar">
    <div class="iq-sidebar-logo d-flex justify-content-between">
    <a href="index.php?id=<?=$userid?>" class="header-logo">
        <img src="images/logo.png" class="img-fluid rounded-normal" alt="">
        <div class="logo-title">
            <span class="text-primary text-uppercase"><?=title?></span> 
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
        <!-- SEANCES SPORT+ -->
            <li>
                <a href="#menu-seances" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-list-check"></i><span>Séances</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                <ul id="menu-seances" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                    <?php    
                        // AFFICHAGE DE TOUTE LES SEANCES DE LA BDD //        
                        $reponse = $bdd->query("SELECT * FROM seances_info WHERE id_createur = 1");// id_createur == 1 => séances de sport+ // 
                        while ($donnees = $reponse->fetch())
                        {
                            echo '
                            <li class="menu-level">
                                <a href="#menu_seances'. $donnees['id_seance'] .'" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="las la-hand-point-right"></i><span>'. $donnees['nom_seance']. '</span></a>
                                <ul id="menu_seances'. $donnees['id_seance'] .'" class="iq-submenu iq-submenu-data collapse">';
    
                                if($donnees['ids_normal']){
                                    echo '<li><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $donnees['nom_seance'] .';'. $donnees['id'] .';'. $donnees['ids_normal'] .';normal;'. $donnees['id_createur'] .'"><a><i class="las la-thumbtack"></i>Normal</button></a></li>';
                                }
                                if($donnees['ids_advanced']){
                                    echo '<li><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $donnees['nom_seance'] .';'. $donnees['id'] .';'. $donnees['ids_advanced'] .';advanced;'. $donnees['id_createur'] .'"><a><i class="las la-thumbtack"></i>Advanced</button></a></li>';
                                }

                            echo '
                                </ul>
                            </li>';
                        }
                    ?>
                </form>
                </ul>
            </li>
            <!-- FIN SEANCES SPORT+ -->

            <!-- CHALLENGES -->
            <li>
                <a href="#menu-challenges" class="iq-waves-effect" data-toggle="collapse" aria-expanded="false"><span class="ripple rippleEffect"></span><i class="las la-dumbbell"></i></i><span>Challenges</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                <ul id="menu-challenges" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" style="">
                <li class="menu-level">
                    <a href="bringsallyup.php?id=<?=$_SESSION['idmbrs']?>" class="iq-waves-effect"><i class="las la-hand-point-right"></i><span>Bring Sally Up</span></a>
                </li>
                <?php    
                    // AFFICHAGE DES CHALLENGES //        
                    $reponse2 = $bdd->query("SELECT * FROM challenges_info WHERE id_createur = 1");// id_createur == 1 => séances de base // 
                    if(!empty($reponse2)){
                        while ($donnees2 = $reponse2->fetch())
                        {
                        echo '                                    
                        <li class="menu-level">
                            <a href="#menu_seance_user'. $donnees2['id_challenge'] .'" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="las la-hand-point-right"></i><span>'. $donnees2['nom_challenge']. '</span></a>
                        </li>';
                        }
                    }
                    else{
                        echo '<li><a href=""><i class="las la-plus"></i>Aucun Challenge disponible !</a></li>';
                    }
                    
                    
                    ?>
                </ul>
            </li>
            <!-- FIN CHALLENGES -->

            <!-- VOS SEANCES -->
            <li>
                <a href="#menu-vos-seances" class="iq-waves-effect" data-toggle="collapse" aria-expanded="false"><span class="ripple rippleEffect"></span><i class="las la-user-tie iq-arrow-left"></i><span>Vos Séances</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                <ul id="menu-vos-seances" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" style="">
                <?php    
                        // AFFICHAGE DES SEANCES DE L'UTILISATEUR //        
                        $reponse3 = $bdd->query("SELECT * FROM seances_info WHERE `id_createur` = $userid LIMIT 5");// id_createur == 1 => séances de base // 
                        if(!empty($reponse3)){
                            while ($donnees3 = $reponse3->fetch())
                            {
                            echo '                                    
                            <li><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $donnees3['nom_seance'] .';'. $donnees3['id'] .';'. $donnees3['ids_custom'] .';custom;'. $donnees3['id_createur'] .'"><a><i class="las la-hand-point-right"></i></i>'. $donnees3['nom_seance'] .'</button></a></li>';
                            }
                        }
                        echo '<li><a class="btn btn-whith" href="all-seance-user.php?id='.$userid.'"><i class="fa fa-eye"></i>Tout voir</a></li>';
                        echo '<li><a href="custom-create.php?id='.$userid.'" class="iq-waves-effect"><i class="las la-plus"></i>Créér une séance</a></li>';
                    ?>
                </ul>
            </li>
            <!-- FIN VOS SEANCES -->
            <!-- SEANCES DES UTILISATEURS COMU -->
            <li>
                <a href="#menu-seances-comu" class="iq-waves-effect" data-toggle="collapse" aria-expanded="false"><span class="ripple rippleEffect"></span><i class="ri-list-check"></i><span>Séance Public</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                <ul id="menu-seances-comu" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" style="">
                <?php    
                        // AFFICHAGE DES SEANCES DE L'UTILISATEUR //        
                        $reponse4 = $bdd->query("SELECT * FROM seances_info WHERE id_createur != 1 AND private = 0");// id_createur == 1 => séances de base // 
                        if(!empty($reponse4)){
                            while ($donnees4 = $reponse4->fetch())
                            {
                            echo '                                    
                            <li class="menu-level">
                                <a href="#menu_seance_comu'. $donnees4['id_seance'] .'" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="las la-hand-point-right"></i><span>'. $donnees4['nom_seance']. '</span></a>
                                    <ul id="menu_seance_comu'. $donnees4['id_seance'] .'" class="iq-submenu iq-submenu-data collapse">
                                    <li><button class="btn btn-whith mb-1" type="submit" href="" name="seance-nom-check" value="'. $donnees4['nom_seance'] .';'. $donnees4['id'] .';'. $donnees4['ids_custom'] .';custom;'. $userid .'"><a><i class="las la-hand-point-right"></i></i>Custom</button></a></li>
                                    </ul>
                            </li>';
                            }
                        }
                        echo '<li><a href="">Aucune Séance <br> Public Disponible!</a></li>';
                    ?>
                </ul>
            </li>
            <!-- FIN SEANCES DES UTILISATEURS COMU -->
            <li>
                <a href="" class="iq-waves-effect" data-toggle="collapse" aria-expanded="false"><i class="las la-calendar"></i><span>Calendrier</span></a>
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
                <a href="index.php?id=<?=$userid?>" class="header-logo">
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
                <?php 
                if($_SESSION['page'] == 'index'){
                    echo '<li class="active"><a href="index.php?id='.$userid.'">Home</a></li>';
                }
                elseif($_SESSION['page'] == 'sport'){
                    echo '<li><a href="index.php?id='.$userid.'">Home</a></li>';
                }
                elseif($_SESSION['page'] != 'index' && $_SESSION['page'] != 'sport'){
                    echo '<li><a href="index.php?id='.$userid.'">Home</a></li>';
                    echo '<li class="active"><a href="#">'.$_SESSION['page'].'</a></li>';
                }

                if(isset($_SESSION['exercice_en_cours']) && isset($_SESSION['date_debut_seance'])){
                    if($_SESSION['page'] == 'sport'){
                        echo '<li class="active"><a href="#">'.$_SESSION['seance_choisis'].'</a></li>';
                    }
                    else{
                        echo '<li><a href="sport.php?id='.$userid.'"> Reprendre '.$_SESSION['seance_choisis'].'</a></li>';
                    }                    
                }                
                ?>

            </ul>

            <!-- AFFICHAGE BARRE DE RECHERCHE -->
            <ul class="navbar-nav ml-auto navbar-list"> 

                <li class="nav-item nav-icon">
                    <div class="iq-search-bar">
                        <form action="#" class="searchbox">
                            <input type="text" class="text search-input" placeholder="Faire une recherche ici..">
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
                        <input type="text" class="text search-input" placeholder="Faire une recherche ici..">
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
                <!-- FIN AFFICHAGE BARRE DE RECHERCHE -->

                <!-- AFFICHAGE NOTIFICATIONS -->
                <li class="nav-item nav-icon">
                <a href="#" class="search-toggle iq-waves-effect text-black rounded">
                    <i class="ri-notification-line block"></i>
                    <?php 
                    if($nombre_notif != 0){
                        echo "<span class='notice-icon dots badge badge-primary'> $nombre_notif</span>";
                    }
                    ?>                    
                </a>
                <div class="iq-sub-dropdown">
                    <div class="iq-card shadow-none m-0">
                        <div class="iq-card-body p-0">
                            <div class="bg-primary p-3">
                            <h5 class="mb-0 text-white">Notifications<small class="badge  badge-light float-right pt-1"><?= $nombre_notif?></small></h5>
                            </div>
                            <form action="" method="POST">
                                <?php include('include/include-notification.php');?>
                            </form>
                        </div>
                    </div>
                </div>
                </li>
                <!-- FIN AFFICHAGE NOTIFICATIONS -->

                <!-- DROPDOWN DROITE USER -->
                <?php include('include/include-dropdown.php');?>
                <!-- FIN DROPDOWN DROITE USER -->
            </ul>
        </div>
<!-- TOP Nav Bar END -->
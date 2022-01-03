<?php
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
       header("Location: deconnexion.php"); //REDIRECTION VERS deconnexion.php //
    }
?>
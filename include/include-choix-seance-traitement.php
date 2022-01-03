<?php
if(isset($_POST['seance-nom-check'])){
    $recuperation_nomseance_difficulte = explode(";", $_POST['seance-nom-check']);
    $_SESSION['seance_choisis'] = $recuperation_nomseance_difficulte[0];// 0 = NOM DE LA SEANCE //
    $_SESSION['id_seance_info'] = $recuperation_nomseance_difficulte[1];// 1 = ID DANS SEANCE INFO //
    $_SESSION['difficulte_seance_id'] = $recuperation_nomseance_difficulte[2];// 2 = IDS DE DIFFICULTE DE LA SEANCE //
    $_SESSION['difficulte_seance'] = $recuperation_nomseance_difficulte[3];// 3 = DIFFICULTE DE LA SEANCE //
    if($recuperation_nomseance_difficulte[4] == $userid){
       $_SESSION['seance_user'] = 1;//1 = SEANCE CREER PAR L'UTILISATEUR //
    }
    else{
       $_SESSION['seance_user'] = 0;// NULL = CREER PAR SPORT+ OU COMMUNAUTE //
    }
    header('Location: index.php?id='.$userid.'');  
}
?>
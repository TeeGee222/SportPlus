<?php 
function PoidsIdeal($taille){
    if ($taille > 0) {
        $taille = $taille/100;
        $PoidsIdeal = $taille**2*21.75;
        return $PoidsIdeal;
    }
}

function PoidsIdealMinimal($taille){
    if ($taille > 0) {
        $taille = $taille/100;
        $PoidsIdealMinimal = $taille**2*18.5;
        return $PoidsIdealMinimal;
    }
}

function PoidsIdealMaximal($taille){
    if ($taille > 0) {
        $taille = $taille/100;
        $PoidsIdealMaximal = $taille**2*25;
        return $PoidsIdealMaximal;
    }
}

function IMC($poids, $taille){
    if ($taille > 0) {
        $taille = $taille/100;
        $IMC = $poids/($taille**2);
        return $IMC;
    }    
}

function fromRGB($R, $G, $B){
    $R = dechex($R);
    if(strlen($R)<2)
    $R = 'O'.$R;
 
    $G = dechex($G);
    if (strlen($G)<2)
    $G = 'O'.$G;
 
    $B = dechex($B);
    if (strlen($B)<2)
    $B = 'O'.$B;
    
    return '#' . $R . $G . $B;
}

function nombre_exo_seance($id_seance_dificulte, $bdd){
    $req_all_exo = $bdd->prepare('SELECT * FROM seances WHERE id_difficulte = ?');
    $req_all_exo->execute(array($_SESSION['difficulte_seance_id']));
    $req_all_exo_fetch = $req_all_exo->fetch();
    $nombre_exo_seance = 0;
    $nombre_repos_seance = 0;
    
    for ($i=1; $i <= 100 ; $i++) { 
    
        $requete_all_exo = 'exercice'.$i.'';
    
        if($req_all_exo_fetch[$requete_all_exo]){
    
            $nombre_exo_seance =  $nombre_exo_seance+1;
    
        }
    }
    return $nombre_exo_seance;
}
function dateDiff($date1, $date2){
    $diff = abs($date1 - $date2);
    $retour = array();

    $tmp = $diff;
    if(strlen($tmp % 60) == 2 ){
        $retour['secondes'] = $tmp % 60;
    }
    else{
        $retour['secondes'] = '0'.$tmp % 60;
    }    
 
    $tmp = floor(($tmp - $retour['secondes'])/60);
    if(strlen($tmp % 60) == 2 ){
        $retour['minutes'] = $tmp % 60;
    }
    else{
        $retour['minutes'] = '0'.$tmp % 60;
    }    
 
    $tmp = floor(($tmp - $retour['minutes'])/60);
    if(strlen($tmp % 60) == 2 ){
        $retour['heures'] = $tmp % 24;
    }
    else{
        $retour['heures'] = '0'.$tmp % 24;
    }  
 
    //$tmp = floor(($tmp - $retour['hour'])/24);
    //$retour['jour'] = $tmp;
 
    return $retour;
}
function obtenirLibelleMois($mois){
    if($mois == '01'){
        $mois = 'Janvier';
    }
    elseif($mois == '02'){
        $mois = 'Février';
    }
    elseif($mois == '03'){
        $mois = 'Mars';
    }
    elseif($mois == '04'){
        $mois = 'Avril';
    }
    elseif($mois == '05'){
        $mois = 'Mai';
    }
    elseif($mois == '06'){
        $mois = 'Juin';
    }
    elseif($mois == '07'){
        $mois = 'Juillet';
    }
    elseif($mois == '08'){
        $mois = 'Août';
    }
    elseif($mois == '09'){
        $mois = 'Septembre';
    }
    elseif($mois == '10'){
        $mois = 'Octobre';
    }
    elseif($mois == '11'){
        $mois = 'Novembre';
    }
    elseif($mois == '12'){
        $mois = 'Decembre';
    }
    return $mois;
}
function colortimeline(){
    $numaleatoire = rand (1 ,5);
    if($numaleatoire == 1){
        $class_timeline = 'border-info';
    }
    elseif($numaleatoire == 2){
        $class_timeline = 'border-warning';
    }
    elseif($numaleatoire == 3){
        $class_timeline = 'border-success';
    }
    elseif($numaleatoire == 4){
        $class_timeline = 'border-danger';
    }
    elseif($numaleatoire == 5){
        $class_timeline = 'border-primary';
    }
    return $class_timeline;
}

function stats_nbr_seances($bdd){
    $reqschist = $bdd->prepare('SELECT * FROM `historique_seance_user` WHERE id_user = ? ORDER BY `date`');
    $reqschist->execute(array($_SESSION['idmbrs']));

    $reqschist2 = $bdd->prepare('SELECT * FROM `historique_seance_user` WHERE id_user = ? ORDER BY `date`');
    $reqschist2->execute(array($_SESSION['idmbrs']));
    if($reqschist2->fetch()){

        $nbr_seance_total = 0;
        $nbr_seance_mois = 0;
        $nbr_seance_semaine = 0;

        $heure_total_semaine = 0;
        $minute_total_semaine = 0;

        $heure_total_mois = 0;
        $minute_total_mois = 0;

        while ($info = $reqschist->fetch()) {
            $list_date[] = $info['date'];
            $list_duree[] = $info['duree_seance'];
            $nbr_seance_total += 1;
        }    
        $last_date = explode('-',end($list_date));
        $annee_last_date = $last_date[0];
        $mois_last_date = $last_date[1];
        $jour_last = explode(' ',$last_date[2]);
        $jour_last_date = $jour_last[0];

        for ($i=0; $i < count($list_date) ; $i++) { 
            if(explode('-',$list_date[$i])[0] == $annee_last_date && explode('-',$list_date[$i])[1] == $mois_last_date && ($jour_last_date - explode(' ',explode('-',$list_date[$i])[2])[0]) <= 7){
                $nbr_seance_semaine += 1;

                if($minute_total_semaine += explode(':',$list_duree[$i])[1] >= 60){
                    $minute_total_semaine -= 60;
                    $heure_total_semaine += 1;
                }
                $heure_total_semaine += explode(':',$list_duree[$i])[0];//Heure
                $minute_total_semaine += explode(':',$list_duree[$i])[1];//Minutes  
            }
            if(explode('-',$list_date[$i])[0] == $annee_last_date && explode('-',$list_date[$i])[1] == $mois_last_date){
                $nbr_seance_mois += 1;
                
                if($minute_total_mois += explode(':',$list_duree[$i])[1] >= 60){
                    $minute_total_mois -= 60;
                    $heure_total_mois += 1;
                }
                $heure_total_mois += explode(':',$list_duree[$i])[0];//Heure
                $minute_total_mois += explode(':',$list_duree[$i])[1];//Minutes                
            }


        }
        if(strlen($minute_total_semaine) == 2){
            $temps_semaine_sports = ''.$heure_total_semaine.' h '.$minute_total_semaine.'';
        }
        else{
            $temps_semaine_sports = ''.$heure_total_semaine.' h 0'.$minute_total_semaine.'';
        }
        
        if(strlen($minute_total_mois) == 2 ){
            $temps_mois_sports = ''.$heure_total_mois.' h '.$minute_total_mois.'';
        }
        else{
            $temps_mois_sports = ''.$heure_total_mois.' h 0'.$minute_total_mois.'';
        }         

        $reqstat = $bdd->prepare('UPDATE `membres` SET `temps_semaines_sports`= ?,`temps_mois_sports`= ?,`nombre_seance_semaine`= ?,`nombre_seance_mois`= ?,`nombre_seance_total`= ? WHERE `idmbrs` = ?');
        $reqstat->execute(array($temps_semaine_sports,$temps_mois_sports,$nbr_seance_semaine,$nbr_seance_mois,$nbr_seance_total,$_SESSION['idmbrs']));
    }
}
?>  

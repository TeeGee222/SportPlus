<?php 
//AFFICHAGE DEMANDE D'AMIS //   
if($nombre_notif != 0){ 

    while ($donnees = $amis_user_pending->fetch()){
        $amis_user_pending_check_name = $bdd->prepare('SELECT `idmbrs`, `name` FROM `membres` WHERE `idmbrs`= ?');
        $amis_user_pending_check_name->execute(array($donnees['id_demandeur']));
        $nom_amis_user_pending = $amis_user_pending_check_name->fetch();
        echo '
        <a class="iq-sub-card" >
            <div class="media align-items-center">
                <div class="">
                <img class="avatar-40 rounded img-fluid" src="images/user/user.png" alt="">
                </div>
                <div class="media-body ml-3">
                <h6 class="mb-0 ">'.$nom_amis_user_pending['name'].'<font color="gray" align="center"> #'.$nom_amis_user_pending['idmbrs'].'</font></h6>
                <p class="mb-0"><button type="submit" name="friend_accept" value="'.$donnees['id_demandeur'].'" class="btn mb-1 btn-success"><i class="ri-check-line"> Accepter</i></button> <button type="submit" name="friend_deny" value="'.$donnees['id_demandeur'].'" class="btn mb-1 btn-primary"><i class="las la-ban"> Refuser</i></button></p>
                </div>
            </div>
        </a>                                      
            ';
    }
    
    //FORMULAIRE DEMANDE D'AMIS POUR ACCEPT OU DENY (BLOQUER PAS FAIT)//
    if(isset($_POST['friend_accept'])){
        // MODIFIE LA COLONNE STATUT A 2 POUR DEFINIR PERSONNE AMIS //
        $amis_user_validate = $bdd->prepare('UPDATE `friends` SET `statut`= 2 WHERE `id_demandeur` = ? AND `id_receveur` = ?');
        $amis_user_validate->execute(array(htmlspecialchars($_POST['friend_accept']), $userid));
    }
    elseif(isset($_POST['friend_deny'])){
        $amis_user_deny = $bdd->prepare('DELETE FROM `friends` WHERE `id_demandeur` = ? AND `id_receveur` = ?');
        $amis_user_deny->execute(array(htmlspecialchars($_POST['friend_deny']), $userid));
    }
    elseif(isset($_POST['friend_bloquer'])){
        $amis_user_bloquer = $bdd->prepare('UPDATE `friends` SET `statut` = 3,`id_bloqueur` = ? WHERE `id_demandeur` = ? AND `id_receveur` = ?');
        $amis_user_bloquer->execute(array($userid, htmlspecialchars($_POST['friend_bloquer']), $userid));
    }  
    }
    else{
    echo '
    <a class="iq-sub-card" >
        <div class="media align-items-center">
            <div class="media-body ml-3">
                <h6 class="mb-0 ">Pas de nouvelle notification !</h6>
            </div>
        </div>
    </a>                                      
        ';
}    
// FIN DEMANDE D'AMIS // 

// AFFICHAGE DEMANDE DE GROUPE //
while ($donnees3 = $groupe_request->fetch()){
    $infogroupe = $bdd->prepare('SELECT * FROM `groupe` WHERE `groupe_id` = ?');
    $infogroupe->execute(array($donnees3['id_groupe']));
    $groupe_info = $infogroupe->fetch();
    echo '
    <a class="iq-sub-card" >
    <div class="media align-items-center">
       <div class="">
          <img class="avatar-40 rounded img-fluid" src="images/user/groupe.png" alt="">
       </div>
       <div class="media-body ml-3">
          <h6 class="mb-0 ">'.$groupe_info['groupe_name'].'<font color="gray" align="center"> #'.$donnees3['id_groupe'].' (Groupe)</font></h6>
          <p class="mb-0"><button type="submit" name="groupe_accept" value="'.$donnees3['id_groupe'].'" class="btn mb-1 btn-success"><i class="ri-check-line"> Accepter</i></button> <button type="submit" name="groupe_deny" value="'.$donnees3['id_groupe'].'" class="btn mb-1 btn-primary"><i class="las la-ban"> Refuser</i></button></p>
       </div>
    </div>
 </a>
    ';
  }
// FIN AFFICHAGE DEMANDE DE GROUPE //
?>
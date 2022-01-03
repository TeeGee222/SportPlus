<?php 
function saveseance($bdd,$nomseance,$Confidentialite){
   // RECUPERATION DERNIERE ID BDD SEANCE //
   $req_id_diff = $bdd->prepare('SELECT `id_difficulte` FROM `seances` ORDER BY `id_difficulte` DESC LIMIT 1');
   $req_id_diff->execute(); 
   $req_id_diff_fetch = $req_id_diff->fetch();
   $id_diff_last = intval($req_id_diff_fetch['id_difficulte'],10);  
   // FIN RECUPERATION DERNIERE ID BDD SEANCE //

   if(isset($_SESSION['seance_choisis']) && isset($_SESSION['id_seance_info']) && isset($_SESSION['difficulte_seance']) && isset($_SESSION['difficulte_seance_id'])){
      $req_id_seance = $bdd->prepare('SELECT `id_seance` FROM `seances_info` WHERE `id`= ?');
      $req_id_seance->execute(array($_SESSION['id_seance_info'])); 
      $req_id_seance_fetch = $req_id_seance->fetch();
      $req_id_seance = $req_id_seance_fetch['id_seance'];
   }
   else{
      $req_id_seance = $bdd->prepare('SELECT `id` FROM `seances_info` ORDER BY `id` DESC LIMIT 1');
      $req_id_seance->execute(); 
      $req_id_seance_fetch = $req_id_seance->fetch();
      $req_id_seance = intval($req_id_seance_fetch['id'],10);
   }

   if($_SESSION['page'] == "Création Séance"){

      // CREATION LIGNE SEANCE //
      $id_diff_next = $id_diff_last+1;
      $req_crea_sc = $bdd->prepare('INSERT INTO `seances`(`id_seance`,`id_difficulte`, `type`) VALUES (?,?,?)');
      $req_crea_sc->execute(array($req_id_seance,$id_diff_next,'custom'));  
      // FIN CREATION SEANCE //      

      // CREATION LIGNE SEANCE INFO //
      $req_crea_scinfo = $bdd->prepare('INSERT INTO `seances_info`(`id_seance`, `nom_seance`, `id_createur`, `private`, `ids_custom`) VALUES (?,?,?,?,?)');
      $req_crea_scinfo->execute(array($req_id_seance,$nomseance,$_SESSION['idmbrs'],$Confidentialite,$id_diff_next));   
      // FIN CREATION LIGNE SEANCE INFO //

   }else{

      if(isset($_POST['input_new_seance'])){
         // CREATION LIGNE SEANCE //
         $id_diff_next = $id_diff_last+1;
         $req_crea_sc = $bdd->prepare('INSERT INTO `seances`(`id_seance`,`id_difficulte`, `type`) VALUES (?,?,?)');
         $req_crea_sc->execute(array($req_id_seance,$id_diff_next,'custom'));  
         // FIN CREATION SEANCE //      
   
         // CREATION LIGNE SEANCE INFO //
         $req_crea_scinfo = $bdd->prepare('INSERT INTO `seances_info`(`id_seance`, `nom_seance`, `id_createur`, `private`, `ids_custom`) VALUES (?,?,?,?,?)');
         $req_crea_scinfo->execute(array($req_id_seance,$nomseance,$_SESSION['idmbrs'],$Confidentialite,$id_diff_next));   
         // FIN CREATION LIGNE SEANCE INFO //
      }
      else{
         $id_diff_next = $_SESSION['difficulte_seance_id'];
      }

   }
   

   //supression ancien exercices
   $insertexo = NULL;
   for ($i=1; $i <101 ; $i++) { 
      $req_exo_template = "exercice$i";
      $reqinsertseance = $bdd->prepare("UPDATE `seances` SET `$req_exo_template`=? WHERE `id_difficulte`=?");
      $reqinsertseance->execute(array($insertexo, $id_diff_next));
   }

   //Ajout des exercices dans la bdd
   $arrayexo = $_POST['exercice'];
   $lastkey_array = count($_POST['exercice']);
   for ($i=1; $i < $lastkey_array+1; $i++) { 
      if($i == 1){
         $i = 0;
         $req_id_exo = $bdd->prepare('SELECT `nom`,`id` FROM exercices WHERE nom = ?');
         $req_id_exo->execute(array($arrayexo[$i]));   
         $req_id_exo_fetch = $req_id_exo->fetch();
         $i = 1;
      }
      else{
         $i = $i-1;
         $req_id_exo = $bdd->prepare('SELECT `nom`,`id` FROM exercices WHERE nom = ?');
         $req_id_exo->execute(array($arrayexo[$i]));   
         $req_id_exo_fetch = $req_id_exo->fetch();
         $i = $i+1;
      }

      //$arrayexo[$i] NOM DE L'EXO //
      //$req_id_exo_fetch['id'] ID DE L'EXERCICE //
      $req_exo_template = "exercice$i";
      $reqinsertseance = $bdd->prepare("UPDATE `seances` SET `$req_exo_template`=? WHERE `id_difficulte`=?");

      if(isset($_POST["input_repetition$i"]) && !isset($_POST["input_minutes$i"]) && !isset($_POST["input_secondes$i"])){
         $insertexo = ''.$req_id_exo_fetch['id'].';'.$_POST["input_repetition$i"].'';            
         $reqinsertseance->execute(array($insertexo, $id_diff_next));
      }
      elseif(!isset($_POST["input_repetition$i"]) && isset($_POST["input_minutes$i"]) && isset($_POST["input_secondes$i"])){
         $insertexo = ''.$req_id_exo_fetch['id'].';'.$_POST["input_minutes$i"].':'.$_POST["input_secondes$i"].'';
         $reqinsertseance->execute(array($insertexo, $id_diff_next)); 
      }
      elseif(!isset($_POST["input_repetition$i"]) && !isset($_POST["input_minutes$i"]) && !isset($_POST["input_secondes$i"]) && isset($_POST["input_minutes_repos$i"]) && isset($_POST["input_secondes_repos$i"])){
         $insertexo = ''.$req_id_exo_fetch['id'].';'.$_POST["input_minutes_repos$i"].':'.$_POST["input_secondes_repos$i"].'';
         $reqinsertseance->execute(array($insertexo, $id_diff_next)); 
      }
   }
   return($id_diff_next);
}

if(isset($_POST['save-seance'])){

   if(isset($_POST['nomseance'])){
      $nomseance = htmlspecialchars($_POST['nomseance']);
   }   
   else{
      $nomseance = $_SESSION['seance_choisis'];
   }

   if(isset($_POST['selectconfiseance'])){
      $Confidentialite = htmlspecialchars($_POST['selectconfiseance']);
   }   
   else{
      $Confidentialite = 1;//privée
   }  
   
   saveseance($bdd,$nomseance,$Confidentialite);

   unset($_SESSION['seance_choisis']);
   unset($_SESSION['id_seance_info']);
   unset($_SESSION['difficulte_seance']);
   unset($_SESSION['difficulte_seance_id']);
   unset($_SESSION['exercice_en_cours']);
   unset($_SESSION['date_debut_seance']);
   unset($_SESSION['date_fin_seance']);
   
   header('Location: index.php?id='.$userid.'');
   
}
if(isset($_POST['save-start-seance'])){
   if(isset($_POST['nomseance'])){
      $nomseance = htmlspecialchars($_POST['nomseance']);
   }   
   if(isset($_POST['selectconfiseance'])){
      $Confidentialite = htmlspecialchars($_POST['selectconfiseance']);
   }   
   if($nomseance == NULL){
      $nomseance = $_SESSION['seance_choisis'];
   }
   if($Confidentialite == NULL){
      $Confidentialite = 1;//privée
   }
   saveseance($bdd,$nomseance,$Confidentialite);

   $req_info_seance = $bdd->prepare('SELECT * FROM `seances_info` WHERE `ids_custom`= ?');
   $req_info_seance->execute(array($id_difficulte)); 
   $req_info_seance_fetch = $req_info_seance->fetch();

   $_SESSION['seance_choisis'] = $req_info_seance_fetch['nom_seance'] ;
   $_SESSION['id_seance_info'] = $req_info_seance_fetch['id'];
   $_SESSION['difficulte_seance'] = 'custom';
   $_SESSION['difficulte_seance_id'] = $id_difficulte;
   $_SESSION['exercice_en_cours'] = 1;
   $_SESSION['date_debut_seance'] = time();

   header('Location: sport.php?id='.$userid.'');
} 

?>
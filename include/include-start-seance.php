<?php
if(isset($_POST['start_seance'])){
    $_SESSION['exercice_en_cours'] = 1;
    $_SESSION['date_debut_seance'] = time();
    header('Location: sport.php?id='.$userid.'');                  
}   
?>
<?php
session_start();
$exercice_1_id = $_SESSION['exercice_en_cours']+1; //+1 POUR PASSER A L'EXERCICE SUIVANT //
$_SESSION['exercice_en_cours'] = $exercice_1_id;

?>

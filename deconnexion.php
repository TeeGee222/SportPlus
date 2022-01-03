<?php
session_start();
 
// Suppression des variables de session et de la session
$_SESSION = array();
session_destroy();
session_unset();
unset($_SESSION['deco']);
unset($_SESSION['groupe']);
unset($_SESSION['idmbrs']);
unset($_SESSION['connection']);
unset($_SESSION['logged_in']);
unset($_SESSION['last_activity']);
unset($_SESSION['expire_time']);
unset($_SESSION['seance_choisis']);
unset($_SESSION['id_seance']);
unset($_SESSION['difficulty']);
unset($_SESSION['difficulte_seance']);
unset($_SESSION['active_class_easy']);
unset($_SESSION['active_class_medium']);
unset($_SESSION['active_class_expert']);
unset($_SESSION['minutes_pause']);
unset($_SESSION['secondes_pause']);
unset($_SESSION['repetition_user_array']);
unset($_SESSION['minutes_user_array']);
unset($_SESSION['secondes_user_array']);
unset($_SESSION['numero_exercice_dispo_array']);
unset($_SESSION['repetition_compteur']);
unset($_SESSION['minutes_compteur']);
unset($_SESSION['secondes_compteur']);
unset($_SESSION['active-dropdown-infoperso']);
unset($_SESSION['active-dropdown-mdpchange']);

 
// Suppression des cookies de connexion automatique
setcookie('login', '');
setcookie('pass_hache', '');
 
 
header('Location: connexion.php');
 
 
?>
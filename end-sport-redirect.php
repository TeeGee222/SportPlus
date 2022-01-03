<?php
session_start();

include('include/include_bdd.php');
include('include/include-fonctions.php');

$_SESSION['date_fin_seance'] = time();
$diff = dateDiff($_SESSION['date_fin_seance'], $_SESSION['date_debut_seance']);
$temps_seance = $diff['heures'].':'.$diff['minutes'].':'.$diff['secondes'];
$reqseancehistorique = $bdd->prepare("INSERT INTO `historique_seance_user`(`id_user`, `id_seance_info`,`id_seance_difficulte`, `duree_seance`) VALUES (?,?,?,?)");
$reqseancehistorique->execute(array($_SESSION['idmbrs'], $_SESSION["id_seance_info"], $_SESSION["difficulte_seance_id"], $temps_seance));

stats_nbr_seances($bdd);

unset($_SESSION['seance_choisis']);
unset($_SESSION['id_seance_info']);
unset($_SESSION['difficulte_seance']);
unset($_SESSION['difficulte_seance_id']);
unset($_SESSION['exercice_en_cours']);
unset($_SESSION['date_debut_seance']);
unset($_SESSION['date_debut_pause']);
unset($_SESSION['date_fin_seance']);
unset($_SESSION['date_fin_pause']);
 
header('Location: index.php?id='.$_SESSION['idmbrs'].'');
 
 
?>
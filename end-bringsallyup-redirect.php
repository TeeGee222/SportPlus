<?php
//============================VERIFICATION SEANCE ==========================//
include('include/include-verif-session.php');
//=========================FIN VERIFICATION SEANCE =========================//

//======================INCLUDE POUR TITLE + CONNEXION BDD==================//
include('include/include_bdd.php');
include('include/include_info.php');
include('include/include-fonctions.php');
//====================FIN INCLUDE POUR TITLE + CONNEXION BDD================//

//===================VAR DE SESSION POUR AFFICHER NAVBAR====================//
$_SESSION['page'] = "redirection";
//=================FIN VAR DE SESSION POUR AFFICHER NAVBAR==================//

//==RECUPERATION ID DANS URL + VERIFICATION BDD + RECUPERATION INFORMATION==//
include('include/include-recup-info-user.php');
//FIN RECUPERATION ID DANS URL + VERIFICATION BDD + RECUPERATION INFORMATION//

//========================= RECUP DONNES URL ===============================//
$min = $_GET['m'];
$sec = $_GET['s'];
$msec = $_GET['ms'];
$req = $bdd->prepare("INSERT INTO `bringsallyup`(`id_user`, `minutes`, `secondes`, `msecondes`) VALUES (?,?,?,?)");
$req->execute(array($_SESSION['idmbrs'], $min, $sec, $msec));
//========================FIN RECUP DONNES URL =============================//

header('Location: index.php?id='.$_SESSION['idmbrs'].'');
?>
<?php 
include('../include/include.php');    
$a = 1;
$table = "seances";
while ($a <= 100) {
    $displayexercice = "exercice$a";
    $displayrepos = "repos_exercice$a";
    echo $displayexercice;
    echo "<br>";
    //echo $displayrepos;
    //echo "<br>";
    $addexercices = $bdd->query("ALTER TABLE $table ADD $displayexercice VARCHAR(25) NULL");
    //$addrepos = $bdd->query("ALTER TABLE $table ADD $displayrepos BIT(1) NOT NULL DEFAULT b'1'");
    //$suppressrepos = $bdd->query("");
    $a = $a+1;
    
}
?>
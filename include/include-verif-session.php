<?php
//DEBUT DE SESSION //
session_start();
//VERIFICATION SESSION OK //
if($_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { // EXPIRATION ? //
   //REDIRECTION A LA PAGE DE CONNEXION //
   $_SESSION['deco'] = "Votre session a expiré !";
   header('Location: deconnexion.php');
} 
else{ // SI PAS EXPIRER //
   $_SESSION['last_activity'] = time(); // ACTUALISATION DE LA DERNIERE ACTIVITE //
}
if(empty($_SESSION['connection']) && $_SESSION['connection'] != true ){
   $_SESSION['deco'] = "Votre session a expiré !";
   header('Location: deconnexion.php');
}
if($_SESSION['idmbrs'] != $_GET['id']){ // ID URL CORRESPOND ID SESSION //
   header('Location: /index.php?id='.$_SESSION['idmbrs']);
}
//FIN VERIFICATION SESSION OK //
?>
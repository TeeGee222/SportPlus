<!--INCLUDE POUR TITLE + CONNEXION BDD-->
<?php
    session_start(); 
    include('include/include_bdd.php');
   include('include/include_info.php');
?>
<?php
if(isset($_POST['forminscription'])){
   $name = htmlspecialchars($_POST['name']);
   $mail = htmlspecialchars($_POST['mail']);
   $mail2 = htmlspecialchars($_POST['mail2']);
   $mdp = sha1($_POST['mdp']);
   $mdp2 = sha1($_POST['mdp2']);
   if(!empty($_POST['name']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2'])){
      if(strlen($name) <= 20){
         if(isset($_POST['termsandconditions'])){
            if($mail == $mail2){
               if(filter_var($mail, FILTER_VALIDATE_EMAIL)){ 
                  $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
                  $reqmail->execute(array($mail));
                  $mailexist = $reqmail->rowCount();
                  if($mailexist == 0){
                     if($mdp == $mdp2){
                        $insertmbr = $bdd->prepare("INSERT INTO membres(name, mail, mdp) VALUES(?, ?, ?)");
                        $insertmbr->execute(array($name, $mail, $mdp));
                        $_SESSION['comptecree'] = "Votre compte à bien été créé !";

                        $reqforid = $bdd->prepare('SELECT * FROM membres WHERE mail = ? AND mdp = ?');
                        $reqforid->execute(array($mail, $mdp));
                        $userinfo = $reqforid->fetch();

                        $info_transfert = $userinfo['idmbrs'].' '.$userinfo['name'].' '.$mail;

                        $_SESSION['idmbrs'] = $userinfo['idmbrs'];
                        $_SESSION['connection'] = true;
                        $_SESSION['logged_in'] = true;
                        $_SESSION['last_activity'] = time();
                        $_SESSION['expire_time'] = 3*60*60;
                        echo shell_exec("python /laragon/www/sport/python_script/verification.py 2>&1" . $info_transfert); 
                        header('Location: inscription-mail-confirm.php');
                     }
                     else
                     {
                        $_SESSION['erreur-inscription'] = "Vos mots de passes ne correspondent pas";
                     }
                  }
                  else
                  {
                     $_SESSION['erreur-inscription'] = "Adresse mail déja utilisée !";
                  }
               }
               else
               {
                  $_SESSION['erreur-inscription'] = "Votre adresse mail n'est pas valide !";
               }
            } 
            else
            {
               $_SESSION['erreur-inscription'] = "Vos addresses mail ne correspondent pas";
            }
         } 
         else{
            $_SESSION['erreur-inscription'] = "Merci d'accepter les Termes et Conditions d'utilisation !";
         }
      }
      else{
         $_SESSION['erreur-inscription'] = "Votre nom ne peut pas dépasser 20 caractéres !";
      } 
   }
   else
   {
      $_SESSION['erreur-inscription'] = "Tous les champs doivent être complétés !";
   }
}

?>
<!doctype html>
<html lang="en" style="--iq-primary:rgb(78, 55, 178); --iq-light-primary:rgba(78, 55, 178,0.1); --iq-primary-hover:rgba(78, 55, 178,0.8);">
   
<head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title><?php echo title; ?></title>
      <!-- Favicon -->
      <link rel="shortcut icon" href="images/favicon.ico" />
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- Typography CSS -->
      <link rel="stylesheet" href="css/typography.css">
      <!-- Style CSS -->
      <link rel="stylesheet" href="css/style.css">
      <!-- Responsive CSS -->
      <link rel="stylesheet" href="css/responsive.css">
   </head>
   <body>
      <!-- loader Start -->
      <div id="loading">
         <div id="loading-center">
         </div>
      </div>
      <!-- loader END -->
      <!-- Sign in Start -->
      <form method="POST" action="">
      <section class="sign-in-page">
         <div class="container">
            <div class="row justify-content-center align-items-center height-self-center">
               <div class="col-md-6 col-sm-12 col-12 align-self-center">
                  <div class="sign-user_card ">
                     <div class="d-flex justify-content-center">
                     </div>
                     <div class="sign-in-page-data">
                        <div class="sign-in-from w-100 m-auto pt-5">
                           <h1 class="mb-3 text-center">Inscription</h1>
                           <div class="input100" align="center"><?php if(isset($_SESSION['erreur-inscription'])){echo '<font color="red" align="center">' . $_SESSION['erreur-inscription'].'</font>';}?></div>
                           <form class="mt-4">
                              <div class="form-group">
                                 <label for="exampleInputEmail2">Votre Nom</label>
                                 <input type="text" name="name" class="form-control mb-0" id="exampleInputEmail2" placeholder="Votre Nom">
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputEmail3">Adresse Mail</label>
                                 <input type="email" name="mail" class="form-control mb-0" id="exampleInputEmail3" placeholder="Adresse Mail">
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputEmail3">Confirmer l'adresse mail</label>
                                 <input type="email" name="mail2" class="form-control mb-0" id="exampleInputEmail4" placeholder="Adresse Mail">
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputPassword2">Mot de Passe</label>
                                 <input type="password" name="mdp" class="form-control mb-0" id="exampleInputPassword2" placeholder="Mot de Passe">
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputPassword2">Vérifier votre Mot de Passe</label>
                                 <input type="password" name="mdp2" class="form-control mb-0" id="exampleInputPassword2" placeholder="Mot de Passe">
                              </div>
                              <div class="d-inline-block w-100">
                                 <div class="custom-control custom-checkbox d-inline-block mt-2">
                                    <input type="checkbox" name="termsandconditions" class="custom-control-input" id="customCheck2">
                                    <label class="custom-control-label" for="customCheck2">J'accepte les <a href="">Termes & Conditions</a></label>
                                 </div>
                              </div>
                              <div class="sign-info mt-3">
                                 <button type="submit" name="forminscription" class="btn btn-primary mb-2">S'inscrire</button>
                                 <span class="d-block line-height-2">déja un compte ?<a href="connexion.php">Se Connecter</a></span>
                              </div>
                           </form>
                        </div>
                     </div>
                     <div class="mt-2">
                        <div class="d-flex justify-content-center links">
                        Déjà un compte? <a href="connexion.php" class="ml-2">Se connecter</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      </form>
      <!-- Sign in END -->
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <!-- Appear JavaScript -->
      <script src="js/jquery.appear.js"></script>
      <!-- Countdown JavaScript -->
      <script src="js/countdown.min.js"></script>
      <!-- Counterup JavaScript -->
      <script src="js/waypoints.min.js"></script>
      <script src="js/jquery.counterup.min.js"></script>
      <!-- Wow JavaScript -->
      <script src="js/wow.min.js"></script>
     
      <!-- Apexcharts JavaScript -->
      <script src="js/apexcharts.js"></script>
      <!-- Slick JavaScript -->
      <script src="js/slick.min.js"></script>
      <!-- Select2 JavaScript -->
      <script src="js/select2.min.js"></script>
      <!-- Owl Carousel JavaScript -->
      <script src="js/owl.carousel.min.js"></script>
      <!-- Magnific Popup JavaScript -->
      <script src="js/jquery.magnific-popup.min.js"></script>
      <!-- Smooth Scrollbar JavaScript -->
      <script src="js/smooth-scrollbar.js"></script>
      <!-- Style Customizer -->
      <script src="js/style-customizer.js"></script>
      <!-- Chart Custom JavaScript -->
      <script src="js/chart-custom.js"></script>
      <!-- Custom JavaScript -->
      <script src="js/custom.js"></script>
   </body>
</html>
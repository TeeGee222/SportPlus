<!--INCLUDE POUR TITLE + CONNEXION BDD-->
<?php
include('include/include_bdd.php');
include('include/include_info.php');
?>
<?php
session_start();
$_SESSION['erreurconnexion'] = @$_SESSION['deco'];
if(isset($_POST['formconnect'])) {
   $mailconnect = htmlspecialchars($_POST['mailconnect']);
	$mdpconnect = sha1($_POST['mdpconnect']);
    if(!empty($mailconnect) AND !empty($mdpconnect))
    {
      $requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ? AND mdp = ?");
	   $requser->execute(array($mailconnect, $mdpconnect));
      $userexist = $requser->rowCount();
      if($userexist == 1)
		{ 
			$reqgrp = $bdd->prepare('SELECT * FROM membres WHERE mail = ?');
			$reqgrp->execute(array($mailconnect));
			$userinfo = $reqgrp->fetch(); 
			$_SESSION['groupe'] = $userinfo['groupe'];
			$_SESSION['idmbrs'] = $userinfo['idmbrs'];
			$_SESSION['connection'] = true;
			$_SESSION['logged_in'] = true;
			$_SESSION['last_activity'] = time();
			$_SESSION['expire_time'] = 3*60*60;
         if($userinfo['verification'] == 1){
            unset($_SESSION['erreurconnexion']);
            header('Location: index.php?id='.$_SESSION['idmbrs']);
         }
         else{
            $info_transfert = $userinfo['idmbrs'] .' '. $userinfo['name'] .' '. $userinfo['mail'];
            $_SESSION['info_transfert'] = $info_transfert;
            //echo shell_exec("python /laragon/www/sport/python_script/verification.py 2>&1" . $info_transfert); 
            unset($_SESSION['erreurconnexion']);
            //header('Location: inscription-mail-confirm.php');
            header('Location: index.php?id='.$_SESSION['idmbrs']);
         }
			
		} 
		else 
		{
			unset($_SESSION['connection']);
			$_SESSION['erreurconnexion'] = "Mot de passe ou mail incorrect !";
		}
    } 
    else 
    {
      $_SESSION['erreurconnexion'] = "Tous les champs doivent être complétés !";
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
                        <div class="sign-in-from w-100 pt-5 m-auto">
                           <h1 class="mb-3 text-center">Connexion</h1>
                           <form class="mt-4">
                           <div class="input100" align="center"><?php if(isset($_SESSION['erreurconnexion'])){echo '<font color="red" align="center">' . $_SESSION['erreurconnexion'].'</font>';}?></div>
                              <div class="form-group">
                                 <label for="exampleInputEmail2">Adresse Mail</label>
                                 <input type="email" class="form-control mb-0" id="exampleInputEmail2" name="mailconnect" placeholder="Adresse Mail">
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputPassword2">Mot de Passe</label>
                                 <input type="password" name="mdpconnect" class="form-control mb-0" id="exampleInputPassword2" placeholder="Mot de Passe">
                              </div>
                              <div class="sign-info">
                                 <button type="submit" name="formconnect" class="btn btn-primary mb-2">Connexion</button>



                                 
                              </div>
                              
                           </form>
                        </div>
                     </div>
                     <div class="mt-2">
                        <div class="d-flex justify-content-center links">
                        Pas de compte? <a href="inscription.php" class="ml-2">S'inscrire</a>
                        </div>
                        <div class="d-flex justify-content-center links">
                           <a href="#">Mot de passe oublié ?</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- Sign in END -->
         </div>
      </section>
      

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
<?php
session_start();
include('include/include_bdd.php');
include('include/include_info.php');
$reqverif = $bdd->prepare("SELECT * FROM `membres` WHERE `idmbrs` = ?");
$reqverif->execute(array($_SESSION['idmbrs']));
$userinfo = $reqverif->fetch();
if(isset($_POST['verif-submit'])){
   if(!empty($_POST['verif-mail-code'])){
      if(is_numeric($_POST['verif-mail-code'])){      
         if($_POST['verif-mail-code'] == $userinfo['verification']){
            unset($_SESSION['erreur_validation']);
            $update = $bdd->prepare("UPDATE `membres` SET `verification`= 1 WHERE `idmbrs` = ?");
            $update->execute(array($_SESSION['idmbrs']));
            header('Location: index.php?id='.$_SESSION['idmbrs']);
         }
         else{
            $_SESSION['erreur_validation'] = "Le code de validation est incorrect !";
         }
      }
      else{
         $_SESSION['erreur_validation'] = "Le code de validation doit être un nombre !";
      }
   }
   else{
      $_SESSION['erreur_validation'] = "Veuillez remplir le champ !";
   }
   
}
if(isset($_POST['resend'])){
   $info_transfert = $_SESSION['info_transfert'];
   shell_exec("python /laragon/www/sport/python_script/verification.py" . $info_transfert); 
}
?>
<!doctype html>
<html lang="en" style="--iq-primary:rgb(78, 55, 178); --iq-light-primary:rgba(78, 55, 178,0.1); --iq-primary-hover:rgba(78, 55, 178,0.8);">
   
<head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title><?php echo title; ?> Mail Confirmation</title>
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
      <section class="sign-in-page">
         <div class="container h-100">
            <div class="row justify-content-center align-items-center h-100">
               <div class="col-md-6 col-sm-12 col-12 ">
                  <div class="sign-user_card ">
                     <div class="sign-in-page-data">
                        <div class="sign-in-from w-100 m-auto pt-5">
                           <img src="images/login/mail.png" width="80"  alt="">
                           <h1 class="mt-3 mb-0">Compte créé !</h1>
                           <p class="text-white">Un email vous a été envoyé ! <form action="" method="post"><button class="btn btn-primary" name="resend" type="submit" href="">Renvoyer le mail</button></form></p>
                           <?php if(isset($_SESSION['erreur_validation'])){echo '<font color="red">' . $_SESSION['erreur_validation'].'</font>';}?>
                           <form action="" method="post">
                           <input placeholder="Entrer le code unique ici" type="number" class="form-control" name="verif-mail-code" id="">
                           <div class="d-inline-block w-100">
                              <button type="submit" name="verif-submit" class="btn btn-primary mt-3">Valider</button>
                           </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Sign in END -->
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script data-cfasync="false" src="../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="js/jquery.min.js"></script>
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
     
      <!-- am core JavaScript -->
      <script src="js/core.js"></script>
      <!-- am charts JavaScript -->
      <script src="js/charts.js"></script>
      <!-- am animated JavaScript -->
      <script src="js/animated.js"></script>
      <!-- am kelly JavaScript -->
      <script src="js/kelly.js"></script>
      <!-- am maps JavaScript -->
      <script src="js/maps.js"></script>
      <!-- am worldLow JavaScript -->
      <script src="js/worldLow.js"></script>
      <!-- Style Customizer -->
      <script src="js/style-customizer.js"></script>
      <!-- Chart Custom JavaScript -->
      <script src="js/chart-custom.js"></script>
      <!-- Custom JavaScript -->
      <script src="js/custom.js"></script>
   </body>
</html>
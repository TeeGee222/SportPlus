<!--INCLUDE POUR TITLE + CONNEXION BDD-->
<?php
session_start();
include('include/include_bdd.php');
include('include/include_info.php');
?>
<!doctype html>
<html lang="en" style="--iq-primary:rgb(78, 55, 178); --iq-light-primary:rgba(78, 55, 178,0.1); --iq-primary-hover:rgba(78, 55, 178,0.8);">
   
<!-- Mirrored from iqonic.design/themes/muzik/html/pages-recoverpw.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 05 Aug 2020 17:02:23 GMT -->
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
      <section class="sign-in-page">
         <div class="container h-100">
            <div class="row justify-content-center align-items-center h-100">
               <div class="col-md-6 col-sm-12 col-12 ">
                  <div class="sign-user_card ">
                     <div class="sign-in-page-data">
                        <div class="sign-in-from w-100 m-auto pt-5">
                           <h1 class="mb-0">Réinitialiser votre mot de passe</h1>
                           <p class="text-white">Entrez votre adresse e-mail et nous vous enverrons un e-mail avec des instructions pour réinitialiser votre mot de passe.</p>
                           <form class="mt-4">
                              <div class="form-group">
                                 <label for="exampleInputEmail1">Adresse e-mail</label>
                                 <input type="email" class="form-control mb-0" id="exampleInputEmail1" placeholder="Enter email">
                              </div>
                              <div class="d-inline-block w-100">
                                 <a href="sign-in-2.html" class="btn btn-primary float-right">Réinitialiser votre mot de passe</a>
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
      
      <!-- SCRIPT -->
         <?php
            include('include/include-script.php');
         ?>
      <!-- FIN SCRIPT -->
   </body>

</html>
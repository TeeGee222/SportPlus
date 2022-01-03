<?php
//============================VERIFICATION SESSION ==========================//
include('include/include-verif-session.php');
//=========================FIN VERIFICATION SESSION =========================//

//======================INCLUDE POUR TITLE + CONNEXION BDD==================//
include('include/include_bdd.php');
include('include/include_info.php');
//====================FIN INCLUDE POUR TITLE + CONNEXION BDD================//

//===================VAR DE SESSION POUR AFFICHER NAVBAR====================//
$_SESSION['page'] = "Editeur de Profil";
//=================FIN VAR DE SESSION POUR AFFICHER NAVBAR==================//

//==RECUPERATION ID DANS URL +VERIFICATION BDD + RECUPERATION INFORMATION==//
include('include/include-recup-info-user.php');
//FIN RECUPERATION ID DANS URL +VERIFICATION BDD + RECUPERATION INFORMATION//

//============================CHOIX DE LA SEANCE===========================//
include('include/include-choix-seance-traitement.php');
//==========================FIN CHOIX DE LA SEANCE=========================//  

//==================================OBJECTIFS===============================//
include('include/include-objectifs.php');
//===============================FIN OBJECTIFS==============================//

//==================================GROUPE==================================//
include('include/include-traitement-groupe.php');
//================================FIN GROUPE================================//

// CALCUL NOMBRE DE NOTIFICATION (DEMANDE D'AMIS + DEMANDE GROUPE) //
$nombre_notif = $nbre_dmd_amis + $nbre_dmd_groupe;
// FIN CALCUL NOMBRE DE NOTIFICATION (DEMANDE D'AMIS + DEMANDE GROUPE) //

//====================================COLOR=================================//
include('include/include-fonctions.php');
$colorhex = fromRGB(78,55,178);
//==================================END COLOR===============================//           

//TRAITEMENT INFORMATION PERSONNELLE //
$modifinfoperso = $bdd->prepare("UPDATE `membres` SET `name`=?, `genre`=?, `anniversaire`=?, `age`=?, `taille`=?, `poids`=?, `description`=? WHERE `idmbrs`=$userid");          
$nodescription = 'Pas de description spécifiée pour le moment !';
if(isset($_POST['infoperso-submit'])){
   
   @$uname = htmlspecialchars($_POST['uname']);
   @$ugenre = htmlspecialchars($_POST['customRadio1']); //GENRE//
   @$uanniv = htmlspecialchars($_POST['anniv']);
   @$uage = htmlspecialchars($_POST['age']);   
   @$utaille = htmlspecialchars($_POST['taille']);
   @$upoids = htmlspecialchars($_POST['poids']);
   @$udescription = htmlspecialchars($_POST['description']);

   if(empty($uname)){
      $uname = $userinfo['name'];
   }

   if(empty($ugenre) AND $userinfo['genre'] != NULL){
      $ugenre = $userinfo['genre'];
      $uage_is_null = false;
   }
   elseif(empty($ugenre) AND $userinfo['genre'] == NULL){
      $ugenre = null;
      $ugenre_is_null = true;
   }
   else{
      $ugenre_is_null = false;
   }

   if(empty($uanniv) AND $userinfo['anniversaire'] != NULL){
      $uanniv = $userinfo['anniversaire']; 
      $uanniv_is_null = false;
   }
   elseif(empty($uanniv) AND $userinfo['anniversaire'] == NULL){
      $uanniv = null;
      $uanniv_is_null = true;
   }
   else{
      $uanniv_is_null = false;
   }

   if(empty($uage) AND $userinfo['age'] != NULL){
      $uage = $userinfo['age'];
      $uage_is_null = false;
   }
   elseif(empty($uage) AND $userinfo['age'] == NULL){
      $uage = null;
      $uage_is_null = true;
   }
   else{
      $uage_is_null = false;
   }

   if(empty($utaille) AND $userinfo['taille'] != NULL){
      $utaille = $userinfo['taille'];
      $utaille_is_null = false;
   }
   elseif(empty($utaille) AND $userinfo['taille'] == NULL){
      $utaille = null;
      $utaille_is_null = true;
   }
   else{
      $utaille_is_null = false;
   }

   if(empty($upoids) AND $userinfo['poids'] != NULL){
      $upoids = $userinfo['poids'];
      $upoids_is_null = false;
   }
   elseif(empty($upoids) AND $userinfo['poids'] == NULL){
      $upoids = null;
      $upoids_is_null = true;
   }
   else{
      $upoids_is_null = false;
   }

   if(empty($udescription)){
      $udescription = 0;
      $udescription = $userinfo['description'];
   }
   if(is_string($uname)){
      $ucaracterename = strlen($uname);//COMPTAGE NOMBRE DE CARACTERE//
      if($ucaracterename <= 20){  
         if($ugenre == "F" OR $ugenre == "H" OR $ugenre_is_null){ 
            if($uanniv_is_null == false){
               $udate = explode("/", $uanniv);
               $checkdate = checkdate($udate['1'], $udate['0'], $udate['2']);            
            }
            elseif($uanniv_is_null == true){
               $checkdate = 1;
            }
            if($checkdate == 1){             
               if($uage >= 0 OR $uage_is_null){
                  if(is_numeric($utaille) OR $utaille_is_null){
                     if($utaille > 0 OR $utaille_is_null){
                        if(is_numeric($upoids) OR $upoids_is_null){
                           if($upoids > 0 OR $upoids_is_null){     
                              if($uname == NULL){
                                 $array_modifinfoperso[0] = NULL;
                              }
                              else{
                                 $array_modifinfoperso[0] = $uname;
                              }

                              if($ugenre == NULL){
                                 $array_modifinfoperso[1] = NULL;
                              }
                              else{
                                 $array_modifinfoperso[1] = $ugenre;
                              }

                              if($uanniv == NULL){
                                 $array_modifinfoperso[2] = NULL;
                              }
                              else{
                                 $array_modifinfoperso[2] = $uanniv;
                              }

                              if($uage == NULL){
                                 $array_modifinfoperso[3] = NULL;
                              }
                              else{
                                 $array_modifinfoperso[3] = $uage;
                              }

                              if($utaille == NULL){
                                 $array_modifinfoperso[4] = NULL;
                              }
                              else{
                                 $array_modifunfoperso[4] = $utaille;
                              }
                              if($upoids == NULL){
                                 $array_modifinfoperso[5] = NULL;
                              }
                              else{
                                 $array_modifinfoperso[5] = $upoids;
                              }
                              if($udescription == NULL){
                                 $array_modifinfoperso[6] = NULL;
                              }
                              else{
                                 $array_modifinfoperso[6] = $udescription;
                              }
                              $modifinfoperso->execute(array($array_modifinfoperso[0],$array_modifinfoperso[1],$array_modifinfoperso[2],$array_modifinfoperso[3],$array_modifinfoperso[4],$array_modifinfoperso[5],$array_modifinfoperso[6]));  
                              header('Location: profil-edit.php?id='.$_SESSION['idmbrs']);                 
                           }
                           else{
                              $erreur = "Veuillez entrer un poids supérieur à 0 !";
                              $erreur_info = '(exemple: "82" )';
                           }
                        }
                        else{
                           $erreur = "Veuillez entrer un poids valide !";
                           $erreur_info = '(format valide exemple: "82" )';
                        }                                                                       
                     }
                     else{
                        $erreur = "Veuillez entrer une taille supérieur à 0 !";
                        $erreur_info = '(exemple: "174" )';
                     }
                  }
                  else{
                     $erreur = "Veuillez entrer une taille valide !";
                     $erreur_info = '(format valide exemple: "174" )';
                  }                             
               }
               else{
                  $erreur = "Merci d'entrer un age correct !";
               }               
            }
            else{
               $erreur = "Date d'anniversaire invalide ou au mauvais format !";
               $erreur_info = "(format valide: jj/mm/aaaa)";
            }
         }
         else{
            $erreur = "Merci de ne pas changer les paramètres des cases !";
         }
      }
      else{
         $erreur = "Votre nom ne peut pas dépasser 20 caractéres !";
      }
   }
   else{
      $erreur = "Votre nom ne doit pas comporter de nombre !";
   }
      
}

//FIN TRAITEMENT INFORMATION PERSONNELLE //

//TRAITEMENT CHANGEMENT DE MOT DE PASSE //

//old-pass new-pass new-pass-confirm mdpchange-submit//
$verifmdpreq = $bdd->prepare("SELECT * FROM membres WHERE idmbrs = ? AND mdp = ?");
$modifmdpreq = $bdd->prepare("UPDATE `membres` SET `mdp`= ? WHERE `idmbrs` = $userid");
if(isset($_POST['mdpchange-submit'])){

   @$oldpass = sha1($_POST['old-pass']);
   @$newpass = sha1($_POST['new-pass']);
   @$newpassconfirm = sha1($_POST['new-pass-confirm']);

   if(!empty($oldpass) AND !empty($newpass) AND !empty($newpassconfirm)){   

      $verifmdpreq->execute(array($userid, $oldpass));
      $mdpiscorrect = $verifmdpreq->rowCount();
      if($mdpiscorrect == 1){
         if($newpass == $newpassconfirm){
            $modifmdpreq->execute(array($newpass));
            $erreur2 = "Votre mot de passe à bien était changé !";
            
         }
         else{
            $erreur2 = "Vos nouveaux mots de passes ne correspondent pas";
         }
      } 
		else{
			$erreur2 = "Mot de passe actuelle incorrect !";
		}      
   } 
   else{
      $erreur2 = "Tous les champs doivent être complétés !";
   }
}
//FIN TRAITEMENT CHANGEMENT DE MOT DE PASSE //

//TRAITEMENT NOTIFICATIONS //
$modifmailreq = $bdd->prepare("UPDATE `membres` SET `mail_notification`= ? WHERE `idmbrs` = $userid"); //1 = notif on | 0 =notif off//
$modifsmsreq = $bdd->prepare("UPDATE `membres` SET `sms_notification`= ? WHERE `idmbrs` = $userid");
if(isset($_POST['emailsmschange-submit'])){
   if(@$_POST['email-notification'] == 'on' ){
      $modifmailreq->execute(array(1));      
   }
   else{
      $modifmailreq->execute(array(0));
   }
   if(@$_POST['sms-notification'] == 'on'){
      $modifsmsreq->execute(array(1));
   }
   else{
      $modifsmsreq->execute(array(0));
   }
   header('Location: profil-edit.php?id='.$_SESSION['idmbrs'].'');
}
//AFFICHAGE//
if($userinfo['mail_notification'] == 1){
   $mail_notification_checked = 'checked';
}
else{
   $mail_notification_checked = '';
}
if($userinfo['sms_notification'] == 1){
   $sms_notification_checked = 'checked';
}
else{
   $sms_notification_checked = '';
}
//$erreur3//
//FIN TRAITEMENT NOTIFICATIONS //

//TRAITEMENT PARAMETRE CONTACT //
if(isset($_POST['contactchange-submit'])){
   $modiftelreq = $bdd->prepare("UPDATE `membres` SET `numero_tel`= ? WHERE `idmbrs` = $userid");
   $tel = @$_POST['numerotelephone'];     
   if(preg_match("#^0[6-7]([-. ]?[0-9]{2}){4}$#", $tel)){
      $meta_carac = array("-", ".", " ");
      $tel= str_replace($meta_carac, "", $tel);
      $tel = chunk_split($tel, 2, "\r");  
      $modiftelreq->execute(array($tel));
      header('Location: profil-edit.php?id='.$_SESSION['idmbrs'].'');
   }   
   else{
      $erreur4 = "Merci de saisir un numero de téléphone valide !";
   }
}
//FIN TRAITEMENT PARAMETRE CONTACT //

//VARIABLE SESSION POUR AFFICHAGE DIV //
if(!isset($_SESSION['active-dropdown-infoperso'])){
   $_SESSION['active-dropdown-infoperso'] = 'active show';
}
//VARIABLE SESSION POUR AFFICHAGE DIV //
?>

<!doctype html>
<html lang="en" style="--iq-primary:rgb(78, 55, 178); --iq-light-primary:rgba(78, 55, 178,0.1); --iq-primary-hover:rgba(78, 55, 178,0.8);">

<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title><?php echo title; ?></title>
   <!-- Favicon -->
   <link rel="shortcut icon" href="images/logo.png" />
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <!-- Typography CSS -->
   <link rel="stylesheet" href="css/typography.css">
   <!-- Style CSS -->
   <link rel="stylesheet" href="css/style.css">
   <!-- Responsive CSS -->
   <link rel="stylesheet" href="css/responsive.css">
</head>
<body class="sidebar-main-active right-column-fixed">
   <!-- START LOADER -->
   <div id="loading">
      <div id="loading-center">
      </div>
   </div>
   <!-- FIN LOADER -->

   <!-- Wrapper Start -->
   <div class="wrapper">
      <?php 
         // INCLUDE NAVBAR //
         include('include/include-navbar.php');
         // FIN INCLUDE NAVBAR //
      ?>

   <!-- Page Content  -->
   <div id="content-page" class="content-page">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <div class="iq-card">
                  <div class="iq-card-body p-0">
                     <div class="iq-edit-list">
                        <ul class="iq-edit-profile d-flex nav nav-pills">
                           <li class="col-md-3 p-0">
                              <a class="nav-link <?= $_SESSION['active-dropdown-infoperso']?>" data-toggle="pill" href="#personal-information">
                              Information Personnelle
                              </a>
                           </li>
                           <li class="col-md-3 p-0">
                              <a class="nav-link <?= $_SESSION['active-dropdown-mdpchange']?>" data-toggle="pill" href="#chang-pwd">
                              Changer de mot de passe
                              </a>
                           </li>
                           <li class="col-md-3 p-0">
                              <a class="nav-link <?= $_SESSION['active-dropdown-emailsmschange']?>" data-toggle="pill" href="#emailandsms">
                              Email & SMS
                              </a>
                           </li>
                           <li class="col-md-3 p-0">
                              <a class="nav-link <?= $_SESSION['active-dropdown-contactchange']?>" data-toggle="pill" href="#manage-contact">
                              Paramètre Contact
                              </a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="iq-edit-list-data">
                  <div class="tab-content">
                     <div class="tab-pane fade <?= $_SESSION['active-dropdown-infoperso']?>" id="personal-information" role="tabpanel">
                        <div class="iq-card">
                           <div class="iq-card-header d-flex justify-content-between">
                              <div class="iq-header-title">
                                 <h4 class="card-title">Information Personnelle</h4>
                              </div>
                           </div>
                           <div class="iq-card-body">
                           <?php if(isset($erreur)){echo '<font color="red" align="center">' . $erreur.'</font>';}?>
                           <?php if(isset($erreur_info)){echo '<font color="gray" align="center">' . $erreur_info.'</font>';}?>
                           <?php if(isset($test)){echo '<font color="red" align="center">' . $test.'</font>';}?>
                              <form method="POST">
                                 <div class=" row align-items-center">
                                    <div class="form-group col-sm-6">
                                       <label for="uname">Nom d'utilisateur:</label>
                                       <input type="text" name="uname" class="form-control" id="uname" placeholder="<?=$name?>">
                                    </div>
                                    <div class="form-group col-sm-6">
                                       <label class="d-block">Genre:</label>
                                       <div class="custom-control custom-radio custom-control-inline">
                                          <input type="radio" id="customRadio6" value="H" name="customRadio1" class="custom-control-input" <?php if($userinfo['genre'] != NULL){if($userinfo['genre'] == "H"){echo 'checked=""';}}?>>                                                
                                          <label class="custom-control-label" for="customRadio6"> Homme </label>
                                       </div>
                                       <div class="custom-control custom-radio custom-control-inline">
                                          <input type="radio" id="customRadio7" value="F" name="customRadio1" class="custom-control-input" <?php if($userinfo['genre'] != NULL){if($userinfo['genre'] == "F"){echo 'checked=""';}}?>>
                                          <label class="custom-control-label" for="customRadio7"> Femme </label>
                                       </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                       <label for="dob">Date de naissance:</label>
                                       <input type="text" name="anniv" class="form-control" id="dob" placeholder="<?php if($userinfo['anniversaire'] != NULL){echo $userinfo['anniversaire'];}else{echo 'jj/mm/aaaa';}?>">
                                    </div>
                                    <div class="form-group col-sm-6">
                                       <label>Age:</label>
                                       <input type="number" name="age" class="form-control" id="uage" placeholder="<?php if($userinfo['age'] != NULL){echo $userinfo['age'];}else{echo 'ex: 24';}?> ans">
                                    </div>
                                    <div class="form-group col-sm-6">
                                       <label>Poids:</label>
                                       <input type="number" name="poids" class="form-control" id="upoids" placeholder="<?php if($userinfo['poids'] != NULL){echo $userinfo['poids'];}else{echo 'ex: 82';}?> Kg">
                                    </div>
                                    <div class="form-group col-sm-6">
                                       <label>Taille:</label>
                                       <input type="number" name="taille" class="form-control" id="utaille" placeholder="<?php if($userinfo['taille'] != NULL){echo $userinfo['taille'];}else{echo 'ex: 174';}?> cm">
                                    </div>
                                    <div class="form-group col-sm-12">
                                       <label>Description:</label>
                                       <textarea class="form-control" name="description" rows="5" style="line-height: 22px;" placeholder="<?php if($userinfo['description'] != NULL){echo $userinfo['description'];}else{echo $nodescription;}?>"></textarea>
                                    </div>
                                 </div>
                                 <button type="submit" name="infoperso-submit" class="btn btn-primary mr-2">Envoyer</button>
                                 <button type="reset" class="btn iq-bg-danger">Annuler</button>
                              </form>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane fade <?= $_SESSION['active-dropdown-mdpchange']?>" id="chang-pwd" role="tabpanel">
                        <div class="iq-card">
                           <div class="iq-card-header d-flex justify-content-between">
                              <div class="iq-header-title">
                                 <h4 class="card-title">Changer de mot de passe</h4>
                              </div>
                           </div>
                           <div class="iq-card-body">
                           <?php if(isset($erreur2)){echo '<font color="red" align="center">' . $erreur2.'</font>';}?>
                           <?php if(isset($erreur2_info)){echo '<font color="gray" align="center">' . $erreur2_info.'</font>';}?>
                              <form method="POST">
                                 <div class="form-group">
                                    <label for="cpass">Mot de passe actuelle:</label>
                                    <a href="javascripe:void();" class="float-right">Mot de passe oublié</a> 
                                    <input type="Password" name="old-pass" class="form-control" id="cpass" value="">
                                 </div>
                                 <div class="form-group">
                                    <label for="npass">Nouveau mot de passe:</label>
                                    <input type="Password" name="new-pass" class="form-control" id="npass" value="">
                                 </div>
                                 <div class="form-group">
                                    <label for="vpass">Confirmer:</label>
                                    <input type="Password" name="new-pass-confirm" class="form-control" id="vpass" value="">
                                 </div>
                                 <button type="submit" name="mdpchange-submit" class="btn btn-primary mr-2">Envoyer</button>
                                 <button type="reset" class="btn iq-bg-danger">Annuler</button>
                              </form>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane fade <?= $_SESSION['active-dropdown-emailsmschange']?>" id="emailandsms" role="tabpanel">
                        <div class="iq-card">
                           <div class="iq-card-header d-flex justify-content-between">
                              <div class="iq-header-title">
                                 <h4 class="card-title">Email & SMS</h4>
                              </div>
                           </div>
                           <div class="iq-card-body">
                           <?php if(isset($erreur3)){echo '<font color="red" align="center">' . $erreur.'</font>';}?>
                           <?php if(isset($erreur3_info)){echo '<font color="gray" align="center">' . $erreur_info.'</font>';}?>
                              <form method="POST">
                                 <div class="form-group row align-items-center">
                                    <label class="col-8 col-md-3" for="emailnotification">Notification par Email:</label>
                                    <div class="col-4 col-md-9 custom-control custom-switch">
                                       <input type="checkbox" name="email-notification" class="custom-control-input" id="emailnotification" <?= $mail_notification_checked?>>
                                       <label class="custom-control-label" for="emailnotification"></label>
                                    </div>
                                 </div>
                                 <div class="form-group row align-items-center">
                                    <label class="col-8 col-md-3" for="smsnotification">Notification par SMS:</label>
                                    <div class="col-4 col-md-9 custom-control custom-switch">
                                       <input type="checkbox" name="sms-notification" class="custom-control-input" id="smsnotification" <?= $sms_notification_checked ?>>
                                       <label class="custom-control-label" for="smsnotification"></label>
                                    </div>
                                 </div>
                                 <button type="submit" name="emailsmschange-submit" class="btn btn-primary mr-2">Envoyer</button>
                                 <button type="reset" class="btn iq-bg-danger">Annuler</button>
                              </form>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane fade <?= $_SESSION['active-dropdown-contactchange']?>" id="manage-contact" role="tabpanel">
                        <div class="iq-card">
                           <div class="iq-card-header d-flex justify-content-between">
                              <div class="iq-header-title">
                                 <h4 class="card-title">Paramètre Contact</h4>
                              </div>
                           </div>
                           <div class="iq-card-body">
                           <?php if(isset($erreur4)){echo '<font color="red" align="center">' . $erreur4.'</font>';}?>
                           <?php if(isset($erreur4_info)){echo '<font color="gray" align="center">' . $erreur4_info.'</font>';}?>
                              <form method="POST">
                                 <div class="form-group">
                                    <label for="cno">Numero de téléphone:</label>
                                    <input type="tel" name="numerotelephone" class="form-control" id="cno" placeholder="<?php if($userinfo['numero_tel'] != NULL){echo $userinfo['numero_tel'];}else{echo '0* ** ** ** **';}?>">
                                 </div>
                                 <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="text" class="form-control" id="email" value="<?= $userinfo['mail']?>" disabled>
                                 </div>
                                 <button type="submit" name="contactchange-submit" class="btn btn-primary mr-2">Envoyer</button>
                                 <button type="reset" class="btn iq-bg-danger">Annuler</button>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
      <!-- Page Content END -->     
<!-- SCRIPT -->
<?php
   include('include/include-script.php');
?>
   </body>

</html>
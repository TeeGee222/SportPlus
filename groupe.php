<?php
//============================VERIFICATION SEANCE ==========================//
include('include/include-verif-session.php');
//=========================FIN VERIFICATION SEANCE =========================//

//======================INCLUDE POUR TITLE + CONNEXION BDD==================//
include('include/include_bdd.php');
include('include/include_info.php');
//====================FIN INCLUDE POUR TITLE + CONNEXION BDD================//

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
//===================VAR DE SESSION POUR AFFICHER NAVBAR====================//
$_SESSION['page'] = 'Votre Groupe';
//=================FIN VAR DE SESSION POUR AFFICHER NAVBAR==================//

// CALCUL NOMBRE DE NOTIFICATION (DEMANDE D'AMIS + DEMANDE GROUPE) //
$nombre_notif = $nbre_dmd_amis + $nbre_dmd_groupe;
// FIN CALCUL NOMBRE DE NOTIFICATION (DEMANDE D'AMIS + DEMANDE GROUPE) //

//====================================COLOR=================================//
include('include/include-fonctions.php');
$colorhex = fromRGB(78,55,178);
//==================================END COLOR===============================//

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

   <!-- loader Start -->
   <div id="loading">
      <div id="loading-center"></div>
   </div>
   <!-- loader END -->

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
               <div class="col-sm-12 col-lg-12"><!-- col-12 col-md-12 col-lg-4 -->
                  <div class="iq-card">
                     <div class="iq-card-body profile-page">
                        <div class="profile-header">
                           <div class="cover-container text-center">
                              <div class="profile-detail mt-3">
                                 <?php 
                                    $admin_groupe_req = $bdd->prepare("SELECT `name` FROM `membres` WHERE `idmbrs` = ?");
                                    $admin_groupe_req->execute(array($groupe_info_user['groupe_id_user_admin']));
                                    $admin_groupe =  $admin_groupe_req->fetch();
                                 
                                 ?>
                                 <h3><?= $groupe_info_user['groupe_name'] ?><font color="gray" > #<?= $groupe_info_user['groupe_id'] ?></font></h3>
                                 <p class="text-primary"><?= $groupe_info_user['description_groupe']?></p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            
            <div class="row">            
               <div class="col-lg-6 col-md-12">
                  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                     <div class="iq-card-header d-flex justify-content-between align-items-center">
                        <div class="iq-header-title">
                           <h4 class="card-title">Membres du Groupe</h4>
                        </div>
                     </div>
                        <div class="iq-card-body">
                           <?php 
                           $user_groupe_req = $bdd->prepare("SELECT `name`,`idmbrs`,`groupe` FROM `membres` WHERE `groupe` = ?");
                           $user_groupe_req->execute(array($groupe_info_user['groupe_id']));
                           while ($user_groupe = $user_groupe_req->fetch()){
                              if($user_groupe['idmbrs'] == $groupe_info_user['groupe_id_user_admin']){
                                 echo '<li><a href="show-profil.php?id='.$userid.'&f='.$user_groupe['idmbrs'].'">
                                 '.$user_groupe['name'].'
                                 <span class="badge badge-danger">Admin</span>
                                 </a></li>';
                              }else{
                                 echo '<li><a href="show-profil.php?id='.$userid.'&f='.$user_groupe['idmbrs'].'">'.$user_groupe['name'].'</a></li>';
                              }                              
                           }
                           ?>
                        
                        </div>
                    </div> 
                </div> 
                <div class="col-lg-6 col-md-12">
                  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                     <div class="iq-card-header d-flex justify-content-between align-items-center">
                        <div class="iq-header-title">
                           <h4 class="card-title">Bientôt d'autres fonctionnalitées !</h4>
                        </div>
                     </div>
                        <div class="iq-card-body">
                        À Bientôt :)
                        </div>
                    </div> 
                </div> 
            </div>

               <div class="iq-card">
                  <div class="iq-card-header d-flex justify-content-between">
                  <div class="iq-header-title">
                        <h4 class="card-title">Statistique du groupe <font class="text-primary"><?=$groupe_info_user['groupe_name']?></font> | <font color="gray" align="center">(Nombre de séances par semaine)</font></h4>
                  </div>
                  </div>
                  <div class="iq-card-body">
                        <div id="am-simple-chart" style="height: 300px;"></div>
                  </div>
               </div>
            </div>

         </div>
      </div>
      <!-- Page Content END-->

   <!-- Wrapper END -->      
   

<!-- SCRIPT -->
<?php
   include('include/include-script.php');
?>
<script>

//GRAPHIQUE//  
(function(jQuery) {
"use strict";
jQuery(document).ready(function() {
    var rightSideBarMini = false;
    checkRightSideBar(rightSideBarMini);
    jQuery(document).on('click', '.right-sidebar-toggle', function() {
        if (rightSideBarMini) {
            rightSideBarMini = false;
        } else {
            rightSideBarMini = true;
        }
        checkRightSideBar(rightSideBarMini);
    })
});
function checkRightSideBar(rightSideBarMini) {
    if (rightSideBarMini) {
        rightSideBarShow();
    } else {
        rightSideBarHide()
    }
}
function rightSideBarShow() {
    jQuery('.right-sidebar-mini').addClass('right-sidebar')
}
function rightSideBarHide() {
    jQuery('.right-sidebar-mini').removeClass('right-sidebar')
}
})(jQuery);


// GRAPHIQUE POUR LE GROUPE //
 if(jQuery('#am-simple-chart').length){
    am4core.ready(function() {

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("am-simple-chart", am4charts.XYChart);
    chart.colors.list = [am4core.color("<?=$colorhex?>"),];

    // Add data
    chart.data = [{
      "Utilisateur": "<?= $name_groupe_user1 ?>",
      "séances": <?= $nombre_seance_semaine_user1?>
    }, {
      "Utilisateur": "<?= $name_groupe_user2 ?>",
      "séances": <?= $nombre_seance_semaine_user2?>
    }, {
      "Utilisateur": "<?= $name_groupe_user3 ?>",
      "séances": <?= $nombre_seance_semaine_user3?>
    }, {
      "Utilisateur": "<?= $name_groupe_user4 ?>",
      "séances": <?= $nombre_seance_semaine_user4?>
    },];

    // Create axes

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "Utilisateur";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;

    categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
      if (target.dataItem && target.dataItem.index & 2 == 2) {
        return dy + 25;
      }
      return dy;
    });

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = "séances";
    series.dataFields.categoryX = "Utilisateur";
    series.name = "Séances";
    series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
    series.columns.template.fillOpacity = .8;

    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 2;
    columnTemplate.strokeOpacity = 1;

    }); // end am4core.ready()
 }
// GRAPHIQUE PERSONNELLE //
 if(jQuery('#morris-donut-chart').length){
        var donut = new Morris.Donut({
          element: 'morris-donut-chart',
          resize: true,
          colors: ["<?=$colorhex?>", "#1ee2ac"],
          data: [
            {label: "Séances par Mois", value: <?=$nseps?>},
            {label: "Séances par semaines", value: <?=$nsepms?>}
          ],
          hideHover: 'auto'
        });
     }
</script>  
</body>

</html>
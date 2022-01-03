<?php
//============================VERIFICATION SEANCE ==========================//
include('include/include-verif-session.php');
//=========================FIN VERIFICATION SEANCE =========================//

//======================INCLUDE POUR TITLE + CONNEXION BDD==================//
include('include/include_bdd.php');
include('include/include_info.php');
//====================FIN INCLUDE POUR TITLE + CONNEXION BDD================//

//===================VAR DE SESSION POUR AFFICHER NAVBAR====================//
$_SESSION['page'] = "index";
//=================FIN VAR DE SESSION POUR AFFICHER NAVBAR==================//

//==RECUPERATION ID DANS URL +VERIFICATION BDD + RECUPERATION INFORMATION==//
include('include/include-recup-info-user.php');
//FIN RECUPERATION ID DANS URL +VERIFICATION BDD + RECUPERATION INFORMATION//

//============================CHOIX DE LA SEANCE===========================//
include('include/include-choix-seance-traitement.php');
//==========================FIN CHOIX DE LA SEANCE=========================// 

//=================================START SEANCE============================//
include('include/include-start-seance.php'); 
//==============================FIN START SEANCE============================//             

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
?>
<?php 

$req_date_bringsally = $bdd->prepare('SELECT `date` FROM `bringsallyup` ORDER BY DATE');
$req_date_bringsally->execute();
while ($donnes = $req_date_bringsally->fetch()) {

   $date = explode(' ',$donnes['date'])[0];
   if(isset($old_date)){
      if($date != $old_date){
         $data_date[] = $date;
      }
   }
   else{
      $data_date[] = $donnes['date'];
   }

   $req_bringsally = $bdd->prepare('SELECT * FROM `bringsallyup` WHERE `date` = ? AND id_user = ?');
   $req_bringsally->execute(array($donnes['date'],14524));
   $row = $req_bringsally->rowCount();
   $req_fetch = $req_bringsally->fetch();
   if($row != 0){

         if(strlen($req_fetch['secondes']) == 2){
            $temps = ''.$req_fetch['minutes'].'.'.$req_fetch['secondes'].'';
         }
         else{
            $temps = ''.$req_fetch['minutes'].'.0'.$req_fetch['secondes'].'';
         }
      
      $data_thomas[] = $temps;
   }
   else{
      $data_thomas[] = 0;
   }

   $req_bringsally2 = $bdd->prepare('SELECT * FROM `bringsallyup` WHERE `date` = ? AND id_user = ?');
   $req_bringsally2->execute(array($donnes['date'],14528));
   $row2 = $req_bringsally2->rowCount();
   $req_fetch2 = $req_bringsally2->fetch();
   if($row2 != 0){
            
         if(strlen($req_fetch2['secondes']) == 2){
            $temps = ''.$req_fetch2['minutes'].'.'.$req_fetch2['secondes'].'';
         }
         else{
            $temps = ''.$req_fetch2['minutes'].'.0'.$req_fetch2['secondes'].'';
         }
      
      $data_aubin[] = $temps;
   }
   else{
      $data_aubin[] = 0;
   }
   
   $old_date = $date;
}
//var_dump($data_thomas);
//echo ('<br>');
//var_dump($data_aubin);
?>
<!doctype html>
<html lang="en" style="<?= $userinfo['color']?>">
<head>
   <!-- Required meta tags -->
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title><?=title?></title>
   <!-- Favicon -->
   <link rel="shortcut icon" href="/sport/images/logo.png" />
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="/sport/css/bootstrap.min.css">
   <!-- Typography CSS -->
   <link rel="stylesheet" href="/sport/css/typography.css">
   <!-- Style CSS -->
   <link rel="stylesheet" href="/sport/css/style.css">
   <!-- Responsive CSS -->
   <link rel="stylesheet" href="/sport/css/responsive.css">
</head>

<body>
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
   
         <?php 
            // INCLUDE INDEX-CONTENT //
            include('include/include-index-content.php');
            // FIN INCLUDE INDEX-CONTENT //
         ?>      
      </div>
   <!-- Wrapper END -->

   <?php
      // INCLUDE CHAT //
      include('include/include-chat.php');
      // FIN INCLUDE CHAT //
   ?>

<!-- SCRIPT -->
<?php
   // INCLUDE SCRIPT //
   include('include/include-script.php');
   // FIN INCLUDE SCRIPT //
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


// GRAPHIQUE GROUPE //
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
    }];

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
// FIN GRAPHIQUE GROUPE //

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
// FIN GRAPHIQUE PERSONNELLE //

// Bring Sally Up //
var data_thomas = [<?php echo '"'.implode('","', $data_thomas).'"' ?>];
var data_aubin = [<?php echo '"'.implode('","', $data_aubin).'"' ?>];
var data_date = [<?php echo '"'.implode('","', $data_date).'"' ?>];

if(jQuery('#apex-line-area').length){
   var options = {
      chart: {
            height: 350,
            type: 'area',
      },
      dataLabels: {
            enabled: false
      },
      stroke: {
            curve: 'smooth'
      },
      colors: ['#ff4545', '#1ee2ac'],
      series: [{
            name: 'Thomas',
            data: data_thomas
      }, {
            name: 'Aubin',
            data: data_aubin
      }],

      xaxis: {
            type: 'datetime',
            categories: data_date,                
      },
      tooltip: {
            x: {
               format: 'dd/MM/yy'
            },
      }
   }

   var chart = new ApexCharts(
      document.querySelector("#apex-line-area"),
      options
   );

   chart.render();
}
</script>   
</body>
</html>
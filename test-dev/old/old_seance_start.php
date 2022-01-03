<?php 
//==================================EASY==================================//
   if(@$_SESSION['difficulty'] == 'easy'){
      echo '         
      <div class="col-lg-12 col-md-12">                           
         <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title">
                  <h4 class="card-title">Paramètre de votre séances <font color="gray">('.$_SESSION['seance_choisis'].')</font></h4>
               </div>
            </div>
         <div class="iq-card-body">
            <form action="" method="post">
               <h3><?= @$erreur ?></h3>
                  <div class="form-row">
                     <div class="col-md-6 mb-3">
                        <label for="validationTooltip01">Minutes :</label>
                        <input type="text" pattern="[A-Za-z]{3}" class="form-control" name="minutes" id="validationTooltip01" value="1" required>
                     </div>

                     <div class="col-md-6 mb-3">
                        <label for="validationTooltip02">Secondes :</label>
                        <input type="number" class="form-control" name="secondes" id="validationTooltip01" value="30" required>
                     </div>

                     <div class="col-md-6 mb-3">
                        <label for="validationTooltip01">Minutes Pause :</label>
                        <input type="number" class="form-control" name="minutes_pause" id="validationTooltip01" value="0" required>
                     </div>

                     <div class="col-md-6 mb-3">
                        <label for="validationTooltip02">Secondes Pause:</label>
                        <input type="number" class="form-control" name="secondes_pause" id="validationTooltip01" value="45" required>
                     </div>

                     <div class="col-md-6 mb-3">
                        <label for="validationTooltip03">Nombres de répetition :</label>
                        <input type="number" class="form-control" name="repetition" id="validationTooltip01" value="10" required>
                     </div>
                  </div>
               <button type="submit" class="btn btn-primary" name="seance_info_send_easy">Lancer la séance !</button>
            </form>
      ';
      

            //==================================MEDIUM==================================//

         }elseif(@$_SESSION['difficulty'] == 'medium'){

            echo '<form action="" method="post">';
            echo'
            <div class="iq-card">
               <div class="iq-card-header d-flex justify-content-between">
                  <div class="iq-header-title">
                     <h4 class="card-title">Paramètre de votre séances <font color="gray">('.$_SESSION['seance_choisis'].')</font></h4>
                  </div>
               </div>
            <div class="iq-card-body">
               <div class="form-row">
                  <div class="col-md-6 mb-3">
                     <label for="validationTooltip01">Minutes Pause :</label>
                     <input type="number" class="form-control" name="minutes_pause" id="validationTooltip01" value="0" required>
                  </div>

                  <div class="col-md-6 mb-3">
                     <label for="validationTooltip02">Secondes Pause:</label>
                     <input type="number" class="form-control" name="secondes_pause" id="validationTooltip01" value="45" required>
                  </div>
               </div>';

            // RECUPERATION TOUS LES EXERCICES DE LA SEANCE //
            $reponse = $bdd->prepare("SELECT * FROM seances WHERE ids = ?");
            $reponse->execute(array($_SESSION['id_seance']));
            $donnees = $reponse->fetch();

            // BOUCLE POUR CHAQUE EXERCICE //
            $nombre_exercice = 0;
            for($id_exercice = 1; $id_exercice <= 150; $id_exercice++){
            $id_nom_exercice = "exercice$id_exercice";
            if(@$donnees[$id_nom_exercice] == true){
               $nombre_exercice = $nombre_exercice+1;

               // REQUETE INFO POUR CHAQUE EXERCICE //
               $reqinfoex = $bdd->prepare("SELECT * FROM exercices WHERE nom = ?");
               $reqinfoex->execute(array($donnees[$id_nom_exercice]));
               $exinfo = $reqinfoex->fetch();

               // INFO DANS VARIABLE //
               $repetition_ou_timer = $exinfo['repetitiontimer'];    
               $nom_exercice = $donnees[$id_nom_exercice];
               
               // VERIFICATION TIMER OU REPETITION //
               if($repetition_ou_timer == 1){ //REPETITION //
                  echo "<div class='form-row'>
                           <div class='col-md-6 mb-3'>
                              <label for='validationTooltip03'><button type='button' class='btn mb-1 btn-primary'>$id_exercice</button> Nombres de répetition pour $nom_exercice :</label>
                              <input type='number' class='form-control' name='repetition_exercice[]' id='validationTooltip01' value='10' required>
                              <div class='invalid-tooltip'>mauvais nombre de répétition</div>
                           </div>
                        </div>";
               }
               elseif($repetition_ou_timer == 2){ // TIMER //
                  echo "<div class='form-row'>
                           <div class='col-md-6 mb-3'>
                              <label for='validationTooltip01'><button type='button' class='btn mb-1 btn-primary'>$id_exercice</button> Minutes pour $nom_exercice:</label>
                              <input type='number' class='form-control' name='minutes_exercice[]' id='validationTooltip01' value='1' required>
                              <div class='valid-tooltip'>Looks good!</div>
                           </div>

                           <div class='col-md-6 mb-3'>
                              <label for='validationTooltip02'><button type='button' class='btn mb-1 btn-primary'>$id_exercice</button> Secondes pour $nom_exercice :</label>
                              <input type='number' class='form-control' name='secondes_exercice[]' id='validationTooltip01' value='30' required>
                              <div class='valid-tooltip'>Looks good!</div>
                           </div>
                        </div>"; 
               }
            }
            else{ //COMPTAGE DE NOMBRE DE SEANCE DISPO //
               $numero_exercice_dispo[] = $id_exercice;    
            }
            
            }
            echo '<button type="submit" class="btn btn-primary" name="seance_info_send_medium">Lancer la séance !</button>';
            echo '</form>';

            //==================================EXPERT==================================//

         }elseif(@$_SESSION['difficulty'] == 'expert'){
            echo '
            <div class="iq-card">
               <div class="iq-card-header d-flex justify-content-between">
                  <div class="iq-header-title">
                     <h4 class="card-title">Paramètre de votre séances <font color="gray">('.$_SESSION['seance_choisis'].')</font></h4>
                  </div>
               </div>
            <div class="iq-card-body">
            <font class="text-primary">Nous sommes désolé le mode Expert n\'est pour le moment pas disponible.</font>';
            //CHANGEMENT & AJOUT D'EXERCICE DANS UNE SEANCE & CHANGEMENT DE TEMPS DE PAUSE ENTRE CHAQUE EXO & CHANGEMENT ENTRE TIMER ET REPETITION//
         }

         //==================================//////==================================//


?>















 <!--
<tr id="tr1">
   <td id="td1">1</td>
   <td>
      <div class="row">
         <div id="selectplacement1" class="col">
            <select id="select1" class="form-control select-big" name="exercice" onchange="remplissageAuto(this);">
            <?php 
               //$exo = $bdd->prepare("SELECT * FROM `exercices`");
               //$exo->execute();
               //while ($donnees = $exo->fetch())
               //{
               //echo '<option value="1">'.$donnees['nom'].'</option>';
               //}
            ?> 
            </select>
         </div>
         <div class="col" id="replace1">
            <input type="number" class="form-control select-big" id="input_minutes1" placeholder="Minutes:">
         </div>
         <div class="col" id="replace21">
            <input type="number" class="form-control select-big" id="input_secondes1" placeholder="Secondes:">
         </div>
      </div>
   </td>
   <td>
      <span class="table-remove">
         <button type="button" class="btn iq-bg-danger btn-rounded btn-sm">Supprimer</button>
      </span>
   </td>                                                     
</tr>-->


<?php 
//==================================EASY==================================//
    // RECUPERATION INFORMATION SEANCE PERSONNALISER EASY //
    if(isset($_POST['seance_info_send_easy'])) { //RECUPERATION DES INFORMATIONS DU FORMULAIRES SUR INFO SEANCE LORS DE L'APPUIE DU BOUTON "seance_info_send_easy" //
      $minutes = $_POST['minutes'];
      $secondes = $_POST['secondes'];  
      $repetition = $_POST['repetition']; 
      $minutes_pause = $_POST['minutes_pause'];
      $secondes_pause = $_POST['secondes_pause']; 
      //VERIFICATION QUE TOUTES LES CONDITIONS SONT RESPECTER //  
      if(!empty($minutes) AND !empty($secondes) AND !empty($repetition)){ 
        if($minutes <= 60){ //VERIFICATION QUE LES MINUTES DE PAUSE NE DEPASSE PAS 20 MIN //
          if($minutes > 0){ //VERIFICATION QUE LE TEMPS DE PAUSE N'EST PAS NEGATIF //
            if($secondes <= 59){ // VERIFICATION QUE LES SECONDES DE PAUSE NE DEPASSE PAS 59 SEC //
              if($repetition <= 50 AND $repetition != 0){
                $_SESSION['minutes'] = $minutes;
                $_SESSION['secondes'] = $secondes;
                $_SESSION['repetition'] = $repetition;
                $_SESSION['minutes_pause'] = $minutes_pause;
                $_SESSION['secondes_pause'] = $secondes_pause;
                $id_seance = $_SESSION['id_seance'];
                header("Location: sport.php?id=$userid&sc=$id_seance&ex=1"); 
              }
              else{
                $erreur = "Vous ne pouvez pas selectionner 0 répétition ! ";
              }
            }
            else{
              $erreur = "Vous ne pouvez pas dépasser 59 secondes de pause !";
            }
          }
          else{
            $erreur = "Vous ne pouvez pas avoir un temps de pause négatif !";
          } 
        }
        else{
          $erreur = "Vous ne pouvez pas dépasser 60 minutes de pause !";
        }
      }
      else{
        $erreur = "Vous devez remplir tout les champs !";
      }
    }
//==================================//////==================================//


//==================================MEDIUM==================================//

  // RECUPERATION INFORMATION SEANCE PERSONNALISER MEDIUM DANS ARRAY //
  if(isset($_POST['repetition_exercice'])) 
  {
    $nombre_repetition = $_POST['repetition_exercice'];
    $total_repetition = count($nombre_repetition);
    for($i=0; $i<$total_repetition; $i++)
    {
      $int_nombre_repetiton = (int)$nombre_repetition[$i]; //CONVERSION EN INT //
      if($int_nombre_repetiton != 0){ //VERIFICATION VALEUR DE REPETITION NON NUL //
        if($int_nombre_repetiton > 0){ // VERIFICATION VALEUR SUPERIEUR A 0 //
          $repetition[] = (int)$nombre_repetition[$i]; //AJOUT DANS LE TABLEAU //
        }
        else{
          $erreur = "Vous ne pouvez pas avoir un nombre de répétition négatif !";
          //ATTENTION EN CAS D'ERREUR UNE VALEUR PAR DEFAULT EST AJOUTE (10) //
          $erreur_info = "La valeur par defaut a été enregistré ! (10)";
          $repetition[] = (int)10;
        }
      }
      else{
        $erreur = "Le nombre de répétition ne peux pas étre égale à 0 !";
        //ATTENTION EN CAS D'ERREUR UNE VALEUR PAR DEFAULT EST AJOUTE (10) //
        $erreur_info = "La valeur par defaut a été enregistré ! (10)";
        $repetition[] = (int)10;
      }            
    }
  }

  //RECUPERATION INFORMATION MINUTES POUR CHAQUE EXERCICE DANS ARRAY //
  if(isset($_POST['minutes_exercice']))
  {
    $nombre_minutes = $_POST['minutes_exercice'];
    $totalminutes = count($nombre_minutes);
    for($i=0; $i<$totalminutes; $i++)
    {
      $int_nombre_minutes = (int)$nombre_minutes[$i]; //CONVERSION EN INT //
      if($int_nombre_minutes <= 60){ //VERIFICATION QUE LES MINUTES NE DEPASSE PAS 60 MIN //
        if($int_nombre_minutes >= 0){ //VERIFICATION QUE LES MINUTES NE SONT PAS NEGATIVE //
          $minutes[] = (int)$nombre_minutes[$i];
        }
        else{
          $erreur = "Vous ne pouvez pas avoir une durée d'exercice négatif !";
          //ATTENTION EN CAS D'ERREUR UNE VALEUR PAR DEFAULT EST AJOUTE (1) //
          $erreur_info = "La valeur par defaut a été enregistré ! (1)";
          $minutes[] = (int)1;
        } 
      }
      else{
        $erreur = "La durée des exercices ne peux pas dépassé 60 minutes !";
        //ATTENTION EN CAS D'ERREUR UNE VALEUR PAR DEFAULT EST AJOUTE (1) //
        $erreur_info = "La valeur par defaut a été enregistré ! (1)";
        $minutes[] = (int)1;
      }
    }
  }

  //RECUPERATION INFORMATION SECONDES POUR CHAQUE EXERCICE DANS ARRAY //
  if(isset($_POST['secondes_exercice']))
  {
    $nombre_secondes = $_POST['secondes_exercice'];
    $totalsecondes = count($nombre_secondes);
    for($i=0; $i<$totalsecondes; $i++)
    {
      $int_nombre_secondes = (int)$nombre_secondes[$i];//CONVERSION EN INT //
      if($int_nombre_secondes <= 59){ // VERIFICATION QUE LES SECONDES NE DEPASSE PAS 59 SEC //
        if($int_nombre_secondes >= 0){
        $secondes[] = (int)$nombre_secondes[$i];
        }
        else{
          $erreur = "Vous ne pouvez pas avoir un nombre de secondes négatif !";
          //ATTENTION EN CAS D'ERREUR UNE VALEUR PAR DEFAULT EST AJOUTE (30) //
          $erreur_info = "La valeur par defaut a été enregistré ! (30)";
          $secondes[] = (int)30;
        }
      }
      else{
        $erreur = "Vous ne pouvez pas dépasser 59 secondes de pause !";
        //ATTENTION EN CAS D'ERREUR UNE VALEUR PAR DEFAULT EST AJOUTE (30) //
        $erreur_info = "La valeur par defaut a été enregistré ! (30)";
        $secondes[] = (int)30;
      }
    }
  }
  //PASSAGE DES ARRAY DE DONNEES CHOISI DANS SESSION
  if(isset($_POST['seance_info_send_medium'])){
    $_SESSION['minutes_pause'] = $_POST['minutes_pause'];
    $_SESSION['secondes_pause'] = $_POST['secondes_pause']; 
    $_SESSION['repetition_user_array'] = @$repetition;
    $_SESSION['minutes_user_array'] = @$minutes;
    $_SESSION['secondes_user_array'] = @$secondes;
    $_SESSION['numero_exercice_dispo_array'] = @$numero_exercice_dispo;
    $id_seance = $_SESSION['id_seance'];
    if(!isset($erreur)){
      header("Location: sport.php?id=$userid&sc=$id_seance&ex=1");
    }
  }
  
?>
















if(@$_SESSION['difficulte_seance'] == 'normal'){
   echo 'normal';
}elseif(@$_SESSION['difficulte_seance'] == 'advanced'){
   echo 'advanced';                                                
}elseif(@$_SESSION['difficulte_seance'] == 'custom'){
// RECUPERATION TOUS LES EXERCICES DE LA SEANCE //
                           
   $reponse = $bdd->prepare("SELECT * FROM seances WHERE ids = ?");
   $reponse->execute(array($_SESSION['id_seance']));
   $donnees = $reponse->fetch();
   //var_dump($donnees);

   // BOUCLE POUR CHAQUE EXERCICE //
   $nombre_exercice = 0;
   for($id_exercice = 1; $id_exercice <= 100; $id_exercice++){
      $id_nom_exercice = "exercice$id_exercice";
      $nombre_exercice = $nombre_exercice+1;

      if($donnees){

      // REQUETE INFO POUR CHAQUE EXERCICE //
      $reqinfoex = $bdd->prepare("SELECT * FROM exercices WHERE nom = ?");
      $reqinfoex->execute(array($donnees[$id_nom_exercice]));
      $exinfo = $reqinfoex->fetch();

      // INFO DANS VARIABLE //
      $repetition_ou_timer = $exinfo['repetitiontimer'];    
      $nom_exercice = $donnees[$id_nom_exercice];

      echo"        
      <tr id='tr$id_exercice'>
      <td id='td$id_exercice'>$id_exercice</td>
      <td>
         <div class='row'>
            <div id='selectplacement$id_exercice' class='col'>
               <select id='select$id_exercice' class='form-control select-big' name='exercice' onchange='remplissageAuto(this);'>";
                  $nombre_dexo2 = 0;
                  $exo = $bdd->prepare("SELECT * FROM `exercices`");
                  $exo->execute();
                  while ($donnees = $exo->fetch())
                  {
                     if($donnees['nom'] == $nom_exercice){
                        echo '<option id="'.$nombre_dexo2.'_value'.$id_exercice.'" value="'.$id_exercice.'" selected>'.$donnees['nom'].'</option>';
                     }
                     else{
                        echo '<option id="'.$nombre_dexo2.'_value'.$id_exercice.'" value="'.$id_exercice.'">'.$donnees['nom'].'</option>';
                     }
                     $nombre_dexo2 = $nombre_dexo2+1;
                  }
         echo  "</select>
            </div>";
         // VERIFICATION TIMER OU REPETITION //
         if($repetition_ou_timer == 1){ //REPETITION //
            echo "<div class='col' id='replace$id_exercice'>
                     <input type='number' class='form-control select-big' id='repetition2' placeholder='Repetition:'>
                  </div>
                  <div class='col' id='replace2$id_exercice'>
                  </div>";
         }
         elseif($repetition_ou_timer == 2){ // TIMER //
            echo "<div class='col' id='replace$id_exercice'>
                  <input type='number' class='form-control select-big' id='input_minutes$id_exercice' placeholder='Minutes:'>
               </div>
               <div class='col' id='replace2$id_exercice'>
                  <input type='number' class='form-control select-big' id='input_secondes$id_exercice' placeholder='Secondes:'>
               </div>"; 
         }
         elseif($repetition_ou_timer == 3){
            echo "<div class='col' id='replace$id_exercice'>
                  <input type='number' class='form-control select-big' id='input_minutes_repos$id_exercice' placeholder='Minutes:'>
               </div>
               <div class='col' id='replace2$id_exercice'>
                  <input type='number' class='form-control select-big' id='input_secondes_repos$id_exercice' placeholder='Secondes:'>
               </div>";
         }
            

      echo"</div>
            </td>
            <td>
               <span class='table-remove'>
                  <button type='button' class='btn iq-bg-danger btn-rounded btn-sm'>Supprimer</button>
               </span>
            </td>                                                     
         </tr> 
         ";
      }
   }    
}else{
   //pas de séance définis
}












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

<script>
/*---------------------------------------------------------------------
   Editable Table
-----------------------------------------------------------------------*/

var exercices_nom = [
<?php 
   $nombre_dexo = 1;
   $reponse = $bdd->query("SELECT * FROM `exercices`");// id_createur == 1 => séances de base // 
   while ($donnees = $reponse->fetch())
   {
   echo "'";
   echo $donnees['nom'];
   echo "'";
   echo ",";
   $nombre_dexo = $nombre_dexo+1;
   }
?> 
];

function options_create(rowCount){
   for (let i = 0; i < exercices_nom.length; i++) {
      if(exercices_nom[i] == 'PLANCHE'){
         if(options === undefined){
            var options = '<option id="'+i+'_value'+rowCount+'" value="'+rowCount+'" selected>'+exercices_nom[i]+'</option>';
         }
         else{
            var options = options+'<option id="'+i+'_value'+rowCount+'" value="'+rowCount+'" selected>'+exercices_nom[i]+'</option>';
         }   
      }  
      else{
         if(options === undefined){
            var options = '<option id="'+i+'_value'+rowCount+'" value="'+rowCount+'">'+exercices_nom[i]+'</option>';
         }
         else{
            var options = options+'<option id="'+i+'_value'+rowCount+'" value="'+rowCount+'">'+exercices_nom[i]+'</option>';
         }   
      } 
   }
   return options;
}

const $tableID = $('#table');
const $BTN = $('#export-btn');
const $EXPORT = $('#export');

 $('.table-add').on('click', 'i', () => {

   var rowCount = $('#tableau_exercice >tbody >tr').length+1; 

   options = options_create(rowCount);

   const newTr = `
   <tr id="tr`+rowCount+`">
   <td id="td`+rowCount+`">`+rowCount+`</td>
   <td>
      <div class="row">
         <div class="col" id="selectplacement`+rowCount+`">
            <select id="select`+rowCount+`"class="form-control select-big" name="exercice" onchange="remplissageAuto(this);">
            `+options+`
            </select>
         </div>
         <div class="col" id="replace`+rowCount+`">
      <input type="number" class="form-control select-big" id="input_minutes`+rowCount+`" placeholder="Minutes:">
       </div>
       <div class="col" id="replace2`+rowCount+`">
          <input type="number" class="form-control select-big" id="input_secondes`+rowCount+`" placeholder="Secondes:">
       </div>
    </div>
 </td>
 <td>
    <span class="table-remove">
       <button type="button" class="btn iq-bg-danger btn-rounded btn-sm">Supprimer</button>
    </span>
 </td>                                                     
</tr>  `;


  $('tbody').append(newTr);//ajout de la ligne//
 });


$tableID.on('click', '.table-remove', function () {

   $(this).parents('tr').detach(); //supression de la ligne//

   //position de la ligne supprimer//
   var positionCount = $(this).parents('tr').attr('id').replace('tr','');
   var posCount = parseInt(positionCount)+1;

   //comptage nombre de ligne tableau//
   var rowCount = $('#tableau_exercice >tbody >tr').length+2; 

   var nombredexo = <?=$nombre_dexo?>

   //boucle pour changer tout les ids et remettre tout dans l'ordre//
   for (let i = posCount; i < rowCount; i++) {
      posCount = posCount-1;

      for (let u = 0; u < nombredexo; u++) {
         $('#'+u+'_value'+i).val(i-1)
         $('#'+u+'_value'+i).attr("id",u+'_value'+posCount); ;
      }
      document.getElementById('td'+i).textContent = posCount;
      document.getElementById('td'+i).id = 'td'+posCount;
      document.getElementById('tr'+i).id = 'tr'+posCount     
      document.getElementById('selectplacement'+i).id = 'selectplacement'+posCount;
      document.getElementById('replace'+i).id = 'replace'+posCount;     
      document.getElementById('replace2'+i).id = 'replace2'+posCount;
      document.getElementById('select'+i).id = 'select'+posCount;

      if(!document.getElementById('input_minutes'+i) && !document.getElementById('input_secondes'+i) && !document.getElementById('input_minutes_repos'+i) && !document.getElementById('input_secondes_repos'+i)){
         document.getElementById('repetition'+i).id = 'repetition'+posCount;
      }
      else if(!document.getElementById('repetition'+i) && !document.getElementById('input_minutes_repos'+i) && !document.getElementById('input_secondes_repos'+i)){
         document.getElementById('input_minutes'+i).id = 'input_minutes'+posCount;  
         document.getElementById('input_secondes'+i).id = 'input_secondes'+posCount; 
      }
      else{
         document.getElementById('input_minutes_repos'+i).id = 'input_minutes_repos'+posCount;  
         document.getElementById('input_minutes_repos'+i).id = 'input_minutes_repos'+posCount;          
      }
      posCount = posCount+2;       
   }

});
 // A few jQuery helpers for exporting only
 jQuery.fn.pop = [].pop;
 jQuery.fn.shift = [].shift;

 $BTN.on('click', () => {

   const $rows = $tableID.find('tr:not(:hidden)');
   const headers = [];
   const data = [];

   // Get the headers (add special header logic here)
   $($rows.shift()).find('th:not(:empty)').each(function () {

     headers.push($(this).text().toLowerCase());
   });

   // Turn all existing rows into a loopable array
   $rows.each(function () {
     const $td = $(this).find('td');
     const h = {};

     // Use the headers from earlier to name our hash keys
     headers.forEach((header, i) => {

       h[header] = $td.eq(i).text();
     });

     data.push(h);
   });

   // Output the result
   $EXPORT.text(JSON.stringify(data));
 });

var exercices = {
<?php 
      $reponse = $bdd->query("SELECT * FROM `exercices`");// id_createur == 1 => séances de base // 
      while ($donnees = $reponse->fetch())
      {
      echo "'";
      echo ''.$donnees['nom'].'';
      echo "'";
      echo ":";
      echo "'";
      echo ''.$donnees['repetitiontimer'].'';
      echo "'";
      echo ",";
      }
?> 
};    

function SuppressTimerInput(row){

   var elem = document.getElementById('input_minutes'+row);
   elem.parentNode.removeChild(elem);
   var elem2 = document.getElementById('input_secondes'+row);
   elem2.parentNode.removeChild(elem2);
}

function SuppressRepetitionInput(row){

   var elem3 = document.getElementById('repetition'+row);
   elem3.parentNode.removeChild(elem3);
}

function SuppressReposInput(row){

   var elem = document.getElementById('input_minutes_repos'+row);
   elem.parentNode.removeChild(elem);
   var elem2 = document.getElementById('input_secondes_repos'+row);
   elem2.parentNode.removeChild(elem2);
}

function CreateRepetitionInput(row){

   var replace = document.getElementById("replace"+row);//Emplacement de l'input

   var list = document.createElement("input");
   list.setAttribute("type", "number");
   list.className = "form-control select-big";  
   list.setAttribute("id", "repetition"+row);
   list.setAttribute("placeholder", "Repetition:");                       
   replace.appendChild(list);
}

function CreateTimerInput(row){

   var replace = document.getElementById("replace"+row);//Emplacement de l'input

   var list = document.createElement("input");
   list.setAttribute("type", "number");
   list.className = "form-control select-big";  
   list.setAttribute("id", "input_minutes"+row);
   list.setAttribute("placeholder", "Minutes:");                       
   replace.appendChild(list);

   var replace = document.getElementById("replace2"+row);//Emplacement de l'input

   var list = document.createElement("input");
   list.setAttribute("type", "number");
   list.className = "form-control select-big";  
   list.setAttribute("id", "input_secondes"+row);
   list.setAttribute("placeholder", "Secondes:");                       
   replace.appendChild(list);
}

function CreateReposInput(row){

   var replace = document.getElementById("replace"+row);//Emplacement de l'input

   var list = document.createElement("input");
   list.setAttribute("type", "number");
   list.className = "form-control select-big";  
   list.setAttribute("id", "input_minutes_repos"+row);
   list.setAttribute("placeholder", "Minutes:");                       
   replace.appendChild(list);

   var replace = document.getElementById("replace2"+row);//Emplacement de l'input

   var list = document.createElement("input");
   list.setAttribute("type", "number");
   list.className = "form-control select-big";  
   list.setAttribute("id", "input_secondes_repos"+row);
   list.setAttribute("placeholder", "Secondes:");                       
   replace.appendChild(list);
}

function ChangeInput(row,Exercice,exercices){
   if (exercices[Exercice] == 1) {

      if(!document.getElementById('input_minutes'+row) && !document.getElementById('input_secondes'+row) && !document.getElementById('input_minutes_repos'+row) && !document.getElementById('input_secondes_repos'+row)){
         SuppressRepetitionInput(row);
      }
      else if(!document.getElementById('repetition'+row) && !document.getElementById('input_minutes_repos'+row) && !document.getElementById('input_secondes_repos'+row)){
         SuppressTimerInput(row);
      }
      else{
         SuppressReposInput(row);
      }
      CreateRepetitionInput(row);

   }
   else if(exercices[Exercice]  == 2){

      if(!document.getElementById('input_minutes'+row) && !document.getElementById('input_secondes'+row) && !document.getElementById('input_minutes_repos'+row) && !document.getElementById('input_secondes_repos'+row)){
         SuppressRepetitionInput(row);
      }
      else if(!document.getElementById('repetition'+row) && !document.getElementById('input_minutes_repos'+row) && !document.getElementById('input_secondes_repos'+row)){
         SuppressTimerInput(row);
      }
      else{
         SuppressReposInput(row);
      }
      CreateTimerInput(row);

   }
   else if(exercices[Exercice]  == 3){

      if(!document.getElementById('input_minutes'+row) && !document.getElementById('input_secondes'+row) && !document.getElementById('input_minutes_repos'+row) && !document.getElementById('input_secondes_repos'+row)){
         SuppressRepetitionInput(row);
      }
      else if(!document.getElementById('repetition'+row) && !document.getElementById('input_minutes_repos'+row) && !document.getElementById('input_secondes_repos'+row)){
         SuppressTimerInput(row);
      }
      else{
         SuppressReposInput(row);
      }
      CreateReposInput(row);

   }
}

function Recup_select_info(obj,choix_rech){
   var idx = obj.selectedIndex;
   if((choix_rech) && (choix_rech == 'valeur')){ 
      return obj.options[idx].value;} // récupère valeur du select
   else if((choix_rech) && (choix_rech == 'texte')){
      return obj.options[idx].innerHTML;} // récupère le contenu html du select
   else{ 
      return idx;} // récupère l'index de position dans le tableau select
}

function remplissageAuto(obj) {
   trcount();
   var row = Recup_select_info(obj,'valeur');
   var Exercice = Recup_select_info(obj,'texte');
   ChangeInput(row,Exercice,exercices);
}
function trcount(){
   var rowCount = $('#tableau_exercice >tbody >tr').length; 
   return rowCount;
}
</script>
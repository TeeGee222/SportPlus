<?php
//============================VERIFICATION SEANCE ==========================//
include('include/include-verif-session.php');
//=========================FIN VERIFICATION SEANCE =========================//

//======================INCLUDE POUR TITLE + CONNEXION BDD==================//
include('include/include_bdd.php');
include('include/include_info.php');
//====================FIN INCLUDE POUR TITLE + CONNEXION BDD================//

//===================VAR DE SESSION POUR AFFICHER NAVBAR====================//
$_SESSION['page'] = "Edition de Séance";
//=================FIN VAR DE SESSION POUR AFFICHER NAVBAR==================//

//==RECUPERATION ID DANS URL +VERIFICATION BDD + RECUPERATION INFORMATION==//
include('include/include-recup-info-user.php');
//FIN RECUPERATION ID DANS URL +VERIFICATION BDD + RECUPERATION INFORMATION//

//============================CHOIX DE LA SEANCE===========================//
include('include/include-choix-seance-traitement.php');
//==========================FIN CHOIX DE LA SEANCE=========================//  

//=============================SAVE-START SEANCE===========================//     
include('include/include-save-start-seance.php');
//===========================FIN SAVE-START SEANCE==========================//      

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
<!doctype html>
<html lang="en" style="<?= $userinfo['color']?>">
<head>
   <!-- Required meta tags -->
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title><?=title?></title>
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
        <div id="content-page" class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">   
                        <!-- AFFICHAGE PARAMETRE DE SEANCE -->
                            <?php
                              include_once('include/include-sport-edit.php');
                            ?> 
                        <!-- FIN AFFICHAGE PARAMETRE DE SEANCE -->   
                    </div>
                </div>
            </div>
        </div> 
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
/*---------------------------------------------------------------------
   Editable Table
-----------------------------------------------------------------------*/
function checkbox(elem){
   if(elem.checked){ //REPETITION
      SuppressRepetitionInput(elem.value)
      CreateTimerInput(elem.value)
   }
   else{ //TIMER
      SuppressTimerInput(elem.value)
      CreateRepetitionInput(elem.value)
   }   
}
function hide_repos(){
   var rowCount = $('#tableau_exercice >tbody >tr').length+1; 

   for (let i = 1; i < rowCount; i++){

   var selectElmt = document.getElementById('select'+i);
   var textselectionne = selectElmt.options[selectElmt.selectedIndex].text;   
   var tr = document.getElementById('tr' + i);

      if(textselectionne == 'REPOS'){
         tr.style.display = "none";
      }
      else{
         tr.style.display = "";
      }
   }      
}

function show_repos(){
   var rowCount = $('#tableau_exercice >tbody >tr').length+1; 
   for (let i = 1; i < rowCount; i++){

      var selectElmt = document.getElementById('select'+i);
      var textselectionne = selectElmt.options[selectElmt.selectedIndex].text;   

      var tr = document.getElementById('tr' + i);

      if(textselectionne == 'REPOS'){
         tr.style.display = "";
      }
   }      
}
function hide_show(elem){
   if(elem.checked){
      hide_repos()
   }
   else{
      show_repos()
   }
}

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
            var options = '<option id="'+i+'_value'+rowCount+'" value="'+exercices_nom[i]+'" selected>'+exercices_nom[i]+'</option>';
         }
         else{
            var options = options+'<option id="'+i+'_value'+rowCount+'" value="'+exercices_nom[i]+'" selected>'+exercices_nom[i]+'</option>';
         }   
      }  
      else{
         if(options === undefined){
            var options = '<option id="'+i+'_value'+rowCount+'" value="'+exercices_nom[i]+'">'+exercices_nom[i]+'</option>';
         }
         else{
            var options = options+'<option id="'+i+'_value'+rowCount+'" value="'+exercices_nom[i]+'">'+exercices_nom[i]+'</option>';
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

   if(rowCount >= 101){
      alert('PAS + de ligne !');  
   }
   else{
      options = options_create(rowCount);

      const newTr = `
      <tr id="tr`+rowCount+`">
      <td id="td`+rowCount+`">`+rowCount+`</td>
      <td>
         <div class="row">
            <div class="col" id="selectplacement`+rowCount+`">
               <select id="select`+rowCount+`"class="form-control select-big" name="exercice[]" onchange="remplissageAuto(this);">
               `+options+`
               </select>
            </div>
            <div class='custom-control custom-switch custom-switch-text custom-control-inline'>            
               <div class='custom-switch-inner'>
                  <input type='checkbox' value='`+rowCount+`' class='custom-control-input' id='checkbox`+rowCount+`' checked onclick='checkbox(this) '>
                  <label class='custom-control-label' id='label_repos_hide_show`+rowCount+`' for='checkbox`+rowCount+`' data-on-label='T' data-off-label='R'></label>
               </div>
            </div>  
            <div class="col" id="replace_`+rowCount+`">
         <input type="number" name='input_minutes`+rowCount+`' class="form-control select-big" id="input_minutes`+rowCount+`" placeholder="Minutes:">
         </div>
         <div class="col" id="replace2_`+rowCount+`">
            <input type="number" name='input_secondes`+rowCount+`' class="form-control select-big" id="input_secondes`+rowCount+`" placeholder="Secondes:">
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
  return false;
   }
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
   for (let i = posCount-1; i < rowCount-1; i++){
      for (let u = 0; u < nombredexo; u++){
         $('#'+u+'_value'+i).val(i-1)
         $('#'+u+'_value'+i).attr("id",u+'_value'+i); ;
      }
      document.getElementById('td'+posCount).textContent = i;      
      document.getElementById('td'+posCount).id = 'td'+i;
      document.getElementById('tr'+posCount).id = 'tr'+i;    
      document.getElementById('selectplacement'+posCount).id = 'selectplacement'+i;
      document.getElementById('replace_'+posCount).id = 'replace_'+i;     
      document.getElementById('replace2_'+posCount).id = 'replace2_'+i;
      document.getElementById('select'+posCount).id = 'select'+i;

      document.getElementById('checkbox'+posCount).setAttribute('value',i);
      document.getElementById('checkbox'+posCount).id = 'checkbox'+i;
      document.getElementById('label_repos_hide_show'+posCount).setAttribute('for', 'checkbox'+i);
      document.getElementById('label_repos_hide_show'+posCount).id = 'label_repos_hide_show'+i;

      if(document.getElementById('input_repetition'+posCount) && !document.getElementById('input_minutes'+posCount) && !document.getElementById('input_secondes'+posCount) && !document.getElementById('input_minutes_repos'+posCount) && !document.getElementById('input_secondes_repos'+posCount)){
         document.getElementById('input_repetition'+posCount).setAttribute('name','input_repetition'+i);
         document.getElementById('input_repetition'+posCount).id = 'input_repetition'+i;
      }
      else if(document.getElementById('input_minutes'+posCount) && document.getElementById('input_secondes'+posCount) && !document.getElementById('input_repetition'+posCount) && !document.getElementById('input_minutes_repos'+posCount) && !document.getElementById('input_secondes_repos'+posCount)){
         document.getElementById('input_minutes'+posCount).setAttribute('name','input_minutes'+i);
         document.getElementById('input_secondes'+posCount).setAttribute('name','input_secondes'+i);
         document.getElementById('input_minutes'+posCount).id = 'input_minutes'+i;  
         document.getElementById('input_secondes'+posCount).id = 'input_secondes'+i; 
      }
      else if(document.getElementById('input_minutes_repos'+posCount) && document.getElementById('input_secondes_repos'+posCount) && !document.getElementById('input_repetition'+posCount) && !document.getElementById('input_minutes'+posCount) && !document.getElementById('input_secondes'+posCount)){
         document.getElementById('input_minutes_repos'+posCount).setAttribute('name','input_minutes'+i);
         document.getElementById('input_secondes_repos'+posCount).setAttribute('name','input_secondes'+i);
         document.getElementById('input_minutes_repos'+posCount).id = 'input_minutes_repos'+i;  
         document.getElementById('input_secondes_repos'+posCount).id = 'input_minutes_repos'+i;         
      }
      posCount = posCount+1; 
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

function SuppressTimerInput(row){

   var elem = document.getElementById('input_minutes'+row);
   elem.parentNode.removeChild(elem);
   var elem2 = document.getElementById('input_secondes'+row);
   elem2.parentNode.removeChild(elem2);
}

function SuppressRepetitionInput(row){

   var elem3 = document.getElementById('input_repetition'+row);
   elem3.parentNode.removeChild(elem3);
}

function SuppressReposInput(row){

   var elem = document.getElementById('input_minutes_repos'+row);
   elem.parentNode.removeChild(elem);
   var elem2 = document.getElementById('input_secondes_repos'+row);
   elem2.parentNode.removeChild(elem2);
}

function CreateRepetitionInput(row){

   var replace = document.getElementById("replace_"+row);//Emplacement de l'input

   var list = document.createElement("input");
   list.setAttribute("type", "number");
   list.className = "form-control select-big";  
   list.setAttribute("id", "input_repetition"+row);
   list.setAttribute("name", "input_repetition"+row);
   list.setAttribute("placeholder", "Repetition:");                       
   replace.appendChild(list);
}

function CreateTimerInput(row){

   var replace = document.getElementById("replace_"+row);//Emplacement de l'input

   var list = document.createElement("input");
   list.setAttribute("type", "number");
   list.className = "form-control select-big";  
   list.setAttribute("id", "input_minutes"+row);
   list.setAttribute("name", "input_minutes"+row);
   list.setAttribute("placeholder", "Minutes:");                       
   replace.appendChild(list);

   var replace = document.getElementById("replace2_"+row);//Emplacement de l'input

   var list = document.createElement("input");
   list.setAttribute("type", "number");
   list.className = "form-control select-big";  
   list.setAttribute("id", "input_secondes"+row);
   list.setAttribute("name", "input_secondes"+row);
   list.setAttribute("placeholder", "Secondes:");                       
   replace.appendChild(list);
}

function CreateReposInput(row){

   var replace = document.getElementById("replace_"+row);//Emplacement de l'input

   var list = document.createElement("input");
   list.setAttribute("type", "number");
   list.className = "form-control select-big";  
   list.setAttribute("id", "input_minutes_repos"+row);
   list.setAttribute("name", "input_minutes_repos"+row);
   list.setAttribute("placeholder", "Minutes:");                       
   replace.appendChild(list);

   var replace = document.getElementById("replace2_"+row);//Emplacement de l'input

   var list = document.createElement("input");
   list.setAttribute("type", "number");
   list.className = "form-control select-big";  
   list.setAttribute("id", "input_secondes_repos"+row);
   list.setAttribute("name", "input_secondes_repos"+row);
   list.setAttribute("placeholder", "Secondes:");                       
   replace.appendChild(list);
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
   var id = obj.id.substring(6,9)
   var row = Recup_select_info(obj,'valeur');
   var Exercice = Recup_select_info(obj,'texte');
   changetoexo(id,Exercice);
}

function changetoexo(id,Exercice){
   if(Exercice != 'REPOS'){
      document.getElementById('checkbox'+id).disabled = false;
      if(!document.getElementById('input_minutes'+id) && !document.getElementById('input_secondes'+id) && !document.getElementById('input_minutes_repos'+id) && !document.getElementById('input_secondes_repos'+id)){
         SuppressRepetitionInput(id);
      }
      else if(!document.getElementById('input_repetition'+id) && !document.getElementById('input_minutes_repos'+id) && !document.getElementById('input_secondes_repos'+id)){
         SuppressTimerInput(id);
      }
      else{
         SuppressReposInput(id);
      }
      CreateTimerInput(id)
   }
   else{
      document.getElementById('checkbox'+id).disabled = true;

      if(!document.getElementById('input_minutes'+id) && !document.getElementById('input_secondes'+id) && !document.getElementById('input_minutes_repos'+id) && !document.getElementById('input_secondes_repos'+id)){
         SuppressRepetitionInput(id);
      }
      else if(!document.getElementById('input_repetition'+id) && !document.getElementById('input_minutes_repos'+id) && !document.getElementById('input_secondes_repos'+id)){
         SuppressTimerInput(id);
      }
      else{
         SuppressReposInput(id);
      }
      CreateReposInput(id)
   }   
}

function trcount(){
   var rowCount = $('#tableau_exercice >tbody >tr').length; 
   return rowCount;
}
window.onload = () => {
setInterval(ratiobar_custom, 1000)
}

function ratiobar_custom(){
   var elem_exo = document.getElementById('progress-bar-exo');
   var elem_repos = document.getElementById('progress-bar-repos');
   var rowCount = trcount();
   var repos = 0;
   var exo = 0;

   for(let i = 1; i < rowCount; i++) {
      var selectElmt = document.getElementById('select'+i);
      var valeurselectionnee = selectElmt.options[selectElmt.selectedIndex].value;
      var textselectionne = selectElmt.options[selectElmt.selectedIndex].text;  
      if(textselectionne == 'REPOS'){
         repos = repos+1;
      } 
      else{
         exo = exo+1;
      }
   }
   ratio_exo = Math.round(exo*100);
   ratio_repos = Math.round(repos*100);

   elem_exo.setAttribute('aria-valuenow',ratio_exo);
   elem_exo.setAttribute('style','width:'+ ratio_exo+ '%');

   elem_repos.setAttribute('aria-valuenow',ratio_repos);
   elem_repos.setAttribute('style','width:'+ratio_repos+ '%');
}
</script>
</body>
</html>
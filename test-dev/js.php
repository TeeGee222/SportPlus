<!DOCTYPE html>
<html>
 
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>test select</title>
</head>
 
<body>
<script id="jsc_insert" type="text/javascript" language="javascript">

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
    //valeur_alias = Recup_select_info(obj);
    var RepetitionOuTimer = Recup_select_info(obj,'valeur');
    var Exercice = Recup_select_info(obj,'texte');
    console.log('RepetitionOuTimer: '+RepetitionOuTimer);
    console.log('Exercice: '+Exercice);

    var elem = document.getElementById('input_minutes');
    elem.parentNode.removeChild(elem);
    var elem = document.getElementById('input_minutes');
    elem.parentNode.removeChild(elem);
}
function SuppressTimer(){
    var elem = document.getElementById('input_minutes');
    elem.parentNode.removeChild(elem);
    var elem = document.getElementById('input_minutes');
    elem.parentNode.removeChild(elem);
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
</script>

<div class="iq-card-body">
    <div id="table" class="table-editable">
        <span class="table-add float-right mb-3 mr-2">
            <button class="btn btn-sm iq-bg-success"><i class="ri-add-fill"><span class="pl-1">Nouvel élément</span></i></button>
        </span>
        <table class="table table-bordered table-responsive-md table-striped text-center">
            <tbody>
                <tr>
                    <td>1</td>
                    <!--<td>
                    <span class="table-up"><a href="#!" class="indigo-text"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></a></span>
                    <span class="table-down"><a href="#!" class="indigo-text"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a></span>
                    </td>-->
                    <td>
                        <style>
                        .select-big{
                        width: 200px;
                        }
                        </style>
                        <div class="row">
                            <div class="col">
                                <select class="form-control select-big" name="exercice" onchange="remplissageAuto(this);">
                                    <option value="2" selected>Planche</option>
                                    <option value="1">Pompes</option>
                                    <option value="2">Crunsh</option>
                                    <option value="0">Repos</option>
                                </select>
                            </div>
                            <div class="col" >
                                <input type="number" class="form-control select-big" id="input_minutes" name="" placeholder="Minutes:">
                            </div>
                            <div class="col">
                                <input type="number" class="form-control select-big" id="input_secondes" name=""  placeholder="Secondes:">
                            </div>
                    </td>
                    <td>
                        <span>
                            <button type="button" class="btn iq-bg-dark btn-rounded btn-sm">Modifier</button>
                        </span>
                        <span class="table-remove">
                            <button type="button" class="btn iq-bg-danger btn-rounded btn-sm">Supprimer</button>
                        </span>
                    </td>
                        </div>
                    </td>
                </tr>                                         
            </tbody>
        </table>
    </div>
</div>
</div>

 
</body>
 
</html>
<?php
//============================VERIFICATION SEANCE ==========================//
include('include/include-verif-session.php');
//=========================FIN VERIFICATION SEANCE =========================//

//======================INCLUDE POUR TITLE + CONNEXION BDD==================//
include('include/include_bdd.php');
include('include/include_info.php');
//====================FIN INCLUDE POUR TITLE + CONNEXION BDD================//

//===================VAR DE SESSION POUR AFFICHER NAVBAR====================//
$_SESSION['page'] = "Vos Amis";
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

if(isset($_POST['supprimer-amis'])){
   $id_relation_for_suppression = htmlspecialchars($_POST['supprimer-amis']);
   $amis_user_deny = $bdd->prepare('DELETE FROM `friends` WHERE `id` = ?');
   $amis_user_deny->execute(array($id_relation_for_suppression));
   header('location: amis.php?id='.$userid.'');
   }
   if(isset($_POST['bloquer-amis'])){
   $id_relation_for_bloque = htmlspecialchars($_POST['bloquer-amis']);
   $amis_user_bloquer = $bdd->prepare('UPDATE `friends` SET `statut` = 3,`id_bloqueur` = ? WHERE `id` = ?');
   $amis_user_bloquer->execute(array($userid, $id_relation_for_bloque));
   header('location: amis.php?id='.$userid.'');
}
?>

<!doctype html>
<html lang="en" style="<?= $userinfo['color']?>">


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
<body>
   <!-- loader Start -->
   <div id="loading">
      <div id="loading-center">
      </div>
   </div>
   <!-- loader END -->

   <!-- Wrapper Start -->
   <div class="wrapper">
      
   <?php 
      // INCLUDE NAVBAR //
      include('include/include-navbar.php');
      // FIN INCLUDE NAVBAR //
   ?>

   <!-- TOP Nav Bar END -->
   <div id="content-page" class="content-page">
      <div class="container-fluid">
         <div class="row">
            <div class="col-sm-12">
               <div class="iq-card">
                  <div class="iq-card-header d-flex justify-content-between">
                     <div class="iq-header-title">
                        <h4 class="card-title">Vos d'Amis</h4>
                     </div>
                  </div>
                  <div class="iq-card-body">
                     <div id="table" class="table">
                        <table class="table table-bordered table-responsive-md text-center">
                           <thead>
                              <tr>
                                 <th>Avatar</th>
                                 <th>Name</th>
                                 <th>Status</th>
                                 <th>Groupe</th>
                                 <th>Operation</th>
                              </tr>
                           </thead>
                           <tbody>
                              <form action="" method="post" name="friend">
                              <?php 
                              $amis_user = $bdd->prepare('SELECT * FROM `friends` WHERE `statut` = 2 AND `id_demandeur` = ? OR `statut` = 2 AND `id_receveur` = ?');
                              $amis_user->execute(array($userid, $userid));

                              while($donnees = $amis_user->fetch()){
                                 if($donnees['id_bloqueur'] != $donnees['id_receveur'] OR $donnees['id_bloqueur'] != $donnees['id_demandeur']){
                                    $amis_user_check_name = $bdd->prepare('SELECT * FROM `membres` WHERE `idmbrs`= ?');
                                    if($donnees['id_demandeur'] == $userid){                                       
                                       $amis_user_check_name->execute(array($donnees['id_receveur']));
                                    }else{
                                       $amis_user_check_name->execute(array($donnees['id_demandeur']));
                                    }
                                       $nom_amis_user = $amis_user_check_name->fetch();
                                       echo '
                                       <tr>
                                          <td class="text-center"><img class="rounded img-fluid avatar-40" src="images/user/user.png" alt="profile"></td>
                                          <td>'.$nom_amis_user['name'].' <font color="gray">#'.$nom_amis_user['idmbrs'].'</font></td>
                                          <td><span class="badge iq-bg-primary">En ligne</span></td>';
                                       if($nom_amis_user['groupe'] != NULL){
                                          echo '<td><span class="badge iq-bg-primary">Dans un Groupe</span></td>';
                                       }
                                       else{
                                          echo '<td><span class="badge iq-bg-danger">Sans Groupe</span></td>';
                                       }
                                       echo '  
                                       <td>
                                          <div class="d-flex align-items-center list-user-action">
                                             <a class="iq-bg-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Voir le Profil" href="show-profil.php?id='.$userid.'&f='.$nom_amis_user['idmbrs'].'"><i class="ri-user-line"></i></a>
                                             <button type="submit" name="supprimer-amis" value="'.$donnees['id'].'" class="btn iq-bg-danger btn-rounded btn-sm my-0"><i class="ri-delete-bin-line"></i></button>
                                             <button type="submit" name="bloquer-amis" value="'.$donnees['id'].'" class="btn btn-linq iq-bg-primary btn-rounded my-0"><i class="ri-indeterminate-circle-line"></i></button>
                                          </div>
                                       </td>                                         
                                       </tr>';
                                       //<a class="iq-bg-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Supprimer" name="supprimer-amis" value="'.$donnees['id'].'" href="javascript:$("#friend").submit();"><i class="ri-delete-bin-line"></i></a>
                                       //<a class="iq-bg-warning" data-toggle="tooltip" data-placement="top" title="" data-original-title="Bloquer" name="bloquer-amis" value="'.$donnees['id'].'" href="#"><i class="ri-indeterminate-circle-line"></i></a>
                                 }
                              } 
                              ?>
                              </form>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!--
            <td><span class="badge iq-bg-primary">Active</span></td>
            <td><span class="badge iq-bg-warning">Pending</span></td>
            <td><span class="badge iq-bg-danger">Inactive</span></td>
            <td><span class="badge iq-bg-success">Complite</span></td>
            <td><span class="badge iq-bg-info">Hold</span></td>

          -->
         <div class="row">
            <div class="col-sm-12">
                  <div class="iq-card">
                     <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                           <h4 class="card-title">Ajout Rapide</h4>
                        </div>
                     </div>
                     <div class="iq-card-body">
                        <div class="table">
                           <div class="row justify-content-between">
                              <div class="col-sm-12 col-md-6">
                                 <div id="user_list_datatable_info" class="dataTables_filter">
                                    <form class="mr-3 position-relative">
                                       <div class="form-group mb-0">
                                          <input type="search" class="form-control" id="exampleInputSearch" placeholder="Search" aria-controls="user-list-table">
                                       </div>
                                    </form>
                                 </div>
                              </div>
                           </div>
                           <table id="user-list-table" class="table table-striped table-bordered mt-4" role="grid" aria-describedby="user-list-page-info">
                             <thead>
                                 <tr>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Groupe</th>
                                    <th>Opération</th>
                                 </tr>
                             </thead>
                             
                             <tbody>
                                <?php 
                                $user_suggestion = $bdd->prepare("SELECT * FROM `membres` WHERE `idmbrs` != ? ORDER BY `idmbrs` DESC LIMIT 8");
                                $user_suggestion->execute(array($userid));


                                while ($info = $user_suggestion->fetch()) {
                                   echo '
                                    <tr>
                                       <td class="text-center"><img class="rounded img-fluid avatar-40" src="images/user/user.png" alt="profile"></td>
                                       <td>'.$info['name'].' <font color="gray">#'.$info['idmbrs'].'</font></td>
                                       <td><span class="badge iq-bg-success">En ligne</span></td>
                                       ';
                                    if($info['groupe'] != NULL){
                                    echo'
                                       <td><span class="badge iq-bg-primary">Dans un Groupe</span></td>
                                    ';
                                    }
                                    else{
                                    echo'
                                       <td><span class="badge iq-bg-danger">Sans Groupe</span></td>
                                    ';
                                    }
                                    echo'
                                       <td>
                                          <div class="d-flex align-items-center list-user-action">
                                             <a class="iq-bg-primary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Voir le Profil" href="#"><i class="ri-user-line"></i></a>
                                             <a class="iq-bg-primary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ajouter" href="#"><i class="ri-user-add-line"></i></a>
                                             <a class="iq-bg-primary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" href="#"><i class="ri-pencil-line"></i></a>
                                             <a class="iq-bg-primary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" href="#"><i class="ri-delete-bin-line"></i></a>
                                          </div>
                                       </td>
                                    </tr>';
                                }
                                ?>
                             </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- SCRIPT -->
   <?php
      include('include/include-script.php');
   ?>
<script>
   window.onload = function(){
      var motsClefs = [
         <?php 
            $reponse = $bdd->query("SELECT * FROM membres");
            while ($donnees = $reponse->fetch())
            {
            echo '"'.$donnees['name'].' #'.$donnees['idmbrs'].'",';
            }
         ?>
      ];
     
    var form = document.getElementById("test");
    var input = form.search;
     
    var list = document.createElement("ul");
    list.className = "suggestions";
    list.style.display = "none";
 
    form.appendChild(list);
 
    input.onkeyup = function(){
        var txt = this.value;
        if(!txt){
            list.style.display = "none";
            return;
        }
         
        var suggestions = 0;
        var frag = document.createDocumentFragment();
         
        for(var i = 0, c = motsClefs.length; i < c; i++){
            if(new RegExp("^"+txt,"i").test(motsClefs[i])){
                var word = document.createElement("li");
                frag.appendChild(word);
                word.innerHTML = motsClefs[i].replace(new RegExp("^("+txt+")","i"),"<strong>$1</strong>");
                word.mot = motsClefs[i];
                word.onmousedown = function(){                 
                    input.focus();
                    input.value = this.mot;
                    list.style.display = "none";
                    return false;
                };             
                suggestions++;
            }
        }
 
        if(suggestions){
            list.innerHTML = "";
            list.appendChild(frag);
            list.style.display = "block";
        }
        else {
            list.style.display = "none";           
        }
    };
 
    input.onblur = function(){
        list.style.display = "none";
        if(this.value=="")
            this.value = "Rechercher...";
    };
};   
</script>
</body>
</html>
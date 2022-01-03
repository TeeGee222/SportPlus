<?php
//============================VERIFICATION SEANCE ==========================//
include('include/include-verif-session.php');
//=========================FIN VERIFICATION SEANCE =========================//

//======================INCLUDE POUR TITLE + CONNEXION BDD==================//
include('include/include_bdd.php');
include('include/include_info.php');
//====================FIN INCLUDE POUR TITLE + CONNEXION BDD================//

//===================VAR DE SESSION POUR AFFICHER NAVBAR====================//
$_SESSION['page'] = "Bring Sally Up Challenge";
//=================FIN VAR DE SESSION POUR AFFICHER NAVBAR==================//

//==RECUPERATION ID DANS URL +VERIFICATION BDD + RECUPERATION INFORMATION==//
include('include/include-recup-info-user.php');
//FIN RECUPERATION ID DANS URL +VERIFICATION BDD + RECUPERATION INFORMATION//

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
    <title><?php echo title; ?></title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/logo.png"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Typography CSS -->
    <link rel="stylesheet" href="css/typography.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="style-test.css">
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
                    <div class="col-sm-12 col-lg-12">                                  
                        <div class="iq-card">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Bring Sally Up Challenge</h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <div class="card text-center">
                                
                                    <audio class="my_audio" controls preload="none" style="display:none">
                                        <source src="sons/bring-sally-up-workout-song.mp3" type="audio/mpeg">
                                    </audio>

                                    <ul class='countdown d-flex align-items-center list-inline row'>
                                        <li class='col-md-12 col-lg-4'>
                                            <div class='iq-card'>
                                                <div class='iq-card-body'>
                                                    <span id='minutes'>00</span>Minutes
                                                </div>
                                            </div>
                                        </li>
                                        <li class='col-md-12 col-lg-4'>
                                            <div class='iq-card'>
                                                <div class='iq-card-body'>
                                                    <span id='secondes'>00</span>Secondes
                                                </div>
                                            </div>
                                        </li>
                                        <li class='col-md-12 col-lg-4'>
                                            <div class='iq-card'>
                                                <div class='iq-card-body'>
                                                    <span id='msecondes'>000</span>Milli Secondes
                                                </div>
                                            </div>
                                        </li>
                                    </ul>

                                    <form name="chronoForm">
                                        <input type="button" class="btn btn-primary" name="startstop" value="Commencer !" onClick="chronoStart();play_audio('play')"/>
                                        <input type="button" class="btn btn-primary" name="stop" value="Arrêter  !" onClick="chronoStop();play_audio('stop')"/>
                                        <input type="button" class="btn btn-primary" name="reset" value="Réinitialiser !" onClick="chronoReset()"/>
                                    </form>

                                        <br>

                                    <form>
                                        <input type="button" class="btn btn-primary" id="btn_save" name="save" value="Enregistrer !" onClick='saveinfo(url)' disabled/>
                                    </form>

                                        <br>

                                    <div class="card-footer text-muted">Challenge Pour Aubin & Thomas</div>

                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
    </div>
    <!-- Wrapper END -->


<?php
    // INCLUDE SCRIPT JS //
    include('include/include-script.php');
    // FIN INCLUDE SCRIPT JS //
?>
<script>
    $(".my_audio").trigger('load');

function play_audio(task) {
    if(task == 'play'){
        $(".my_audio").trigger('play');
    }
    if(task == 'stop'){
        $(".my_audio").trigger('pause');
        $(".my_audio").prop("currentTime",0);
    }
}

var startTime = 0
var start = 0
var end = 0
var diff = 0
var timerID = 0

function chrono(){
	end = new Date()
	diff = end - start
	diff = new Date(diff)
	var msec = diff.getMilliseconds()
	var sec = diff.getSeconds()
	var min = diff.getMinutes()
	var hr = diff.getHours()-1
	if (min < 10){
		min = "0" + min
	}
	if (sec < 10){
		sec = "0" + sec
	}
	if(msec < 10){
		msec = "00" +msec
	}
	else if(msec < 100){
		msec = "0" +msec
	}
    document.getElementById("minutes").innerHTML = min
    document.getElementById("secondes").innerHTML = sec
	document.getElementById("msecondes").innerHTML = msec
	timerID = setTimeout("chrono()", 10)
    url = "end-bringsallyup-redirect.php?id=14524&m="+ min + "&s=" + sec + "&ms=" + msec
}
function chronoStart(){
    elem = document.getElementById('btn_save');
    elem.setAttribute('disabled','');
	start = new Date()
	chrono()
}
function chronoContinue(){
	start = new Date()-diff
	start = new Date(start)
	chrono()
}
function chronoReset(){
    document.getElementById("minutes").innerHTML = 00
    document.getElementById("secondes").innerHTML = 00
	document.getElementById("msecondes").innerHTML = 000
	start = new Date()
}
function chronoStopReset(){
    document.getElementById("minutes").innerHTML = 00
    document.getElementById("secondes").innerHTML = 00
	document.getElementById("msecondes").innerHTML = 000
	document.chronoForm.startstop.onclick = chronoStart
}
function chronoStop(){
	clearTimeout(timerID)
    elem = document.getElementById('btn_save');
    elem.removeAttribute('disabled');
}
function saveinfo(url){
    window.location=url
}

    
</script>

</body>
</html>

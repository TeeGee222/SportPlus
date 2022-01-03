<!DOCTYPE html>
<HTML lang="fr">
	<HEAD>

		<meta charset="utf-8" />
		<script src="http://code.jquery.com/jquery.js" type="text/javascript"></script>
		<title>Titre du site</title>
		<meta name="Description" content="Description du site"/>
		<meta name="author" content="Bob l'éponge" />
		<meta name="Keywords" content="a, b, c"/>

		<SCRIPT TYPE="text/javascript">
			$(function() {
		
				$("#butFonctionAjax").click(function() {

					var form = $('#FormMonFormulaire01');
					var str = form.serialize();
					
					$.ajax( {
						type: "POST",
						url: 'ajax.php',
						data: str,
						success: function( response ) {
							$('#divAffichageResultat').html( response ); //Affichage de l'url cible, ici AjaxTemplate02.php, dans une DIV
							$('#status').text('Posté');
							//console.log( response );
						},
						error: function( response ) {
							$('#status').text('Erreur pour poster le formulaire : '+ response.status + " " + response.statusText);
							//console.log( response );
						}						
					} );
				});

			});	
		</SCRIPT>
		
	</HEAD>

	<BODY>

	<div id="bloc_page">
    <INPUT id="butFonctionAjax" type="BUTTON" value="Lancer fonction Ajax"><br />	
    <div STYLE="margin-left:auto; margin-right:auto; width:400px; position:relative; font-size:10pt; font-family:verdana; border: 2px black solid;" id="divAffichageResultat"></div><br />
    <span id="status"></span><br />
		</div> <!-- div bloc_page -->
	</BODY>
</HTML>

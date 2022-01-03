<?php
    include('include.php');    
?>
<html>
    <head>
        <link rel="stylesheet" href="fic.css" />
    <script>
window.onload = function(){
    var motsClefs = {
    <?php 
        $reponse = $bdd->query("SELECT * FROM membres");// id_createur == 1 => séances de base // 
        while ($donnees = $reponse->fetch())
        {
        echo '"'.$donnees['name'].' #'.$donnees['idmbrs'].'",';
        }
    ?>
    };
     
    var form = document.getElementById("auto-suggest");
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
    </head>
    <body>
    test
    <form id="auto-suggest" action="#" method="post">
    <input type="text" class="search" name="search" value="Rechercher..." onfocus="if(this.value=='Rechercher...')this.value=''" onblur="if(this.value=='')this.value='Rechercher...'" autocomplete="off"/>
    <br>test2
</form>
<br>test3
    </body>
</html>

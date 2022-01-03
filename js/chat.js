// Variables globales
let lastId = 0 // id du dernier message affiché

// On attend le chargement du document
window.onload = () => {
    // On va chercher la zone texte
    let texte = document.querySelector("#texte")
    texte.addEventListener("keyup", verifEntree)

    // On va chercher le bouton valid
    let valid = document.querySelector("#valid")
    valid.addEventListener("click", ajoutMessage)

    // On charge les nouveaux messages
    setInterval(chargeMessages, 1000)

}

/**
 * Charge les derniers messages en Ajax et les insère dans la discussion
 */
function chargeMessages(){
    // On instancie XMLHttpRequest
    let xmlhttp = new XMLHttpRequest()

    // On gère la réponse
    xmlhttp.onreadystatechange = function(){
       
        if (this.readyState == 4){
            if(this.status == 200){
                // On a une réponse
                // On convertit la réponse en objet JS
                let messages = JSON.parse(this.response)
                
                // On retourne l'objet
                messages.reverse()
                
                // On récupère la div #discussion
                let discussion = document.querySelector("#discussion")
                
                for(let i = 0; i < messages.length; i++){
                    //console.log(i);
                    if(i == 0){
                        // On transforme la date du message en JS
                        let dateMessage = new Date(messages[i].date)

                        // On ajoute le contenu avant le contenu actuel de discussion (a écrit a ${dateMessage.toLocaleString().substring(12,17)})
                        discussion.innerHTML = `<p><a class='text-primary'>${messages[i].name}</a> à ${dateMessage.getHours()}h${dateMessage.getMinutes()}: <br>${messages[i].content}</p>`
                    }else{

                    // On transforme la date du message en JS
                    let oldDate = new Date(messages[i-1].date)
                    let dateMessage = new Date(messages[i].date)
                    
                    if(oldDate.getMinutes() == dateMessage.getMinutes() &&  messages[i-1].name == messages[i].name){
                        discussion.innerHTML += `<p>${messages[i].content}</p>`
                    }else{
                        discussion.innerHTML += `<p><a class='text-primary'>${messages[i].name}</a> à ${dateMessage.getHours()}h${dateMessage.getMinutes()}: <br>${messages[i].content}</p>`
                    }
                }
                }
            }else{
                // On gère les erreurs
                let erreur = JSON.parse(this.response)
                alert(erreur.message)
            }
        }
    }

    // On ouvre la requête
    xmlhttp.open("GET", "/sport/ajax/chargeMessages.php?lastId="+lastId)

    // On envoie
    xmlhttp.send()

    // On met la barre de défilement en bas 
    var div = document.getElementById('discussion');
    div.scrollTop = div.scrollHeight;

}


/**
 * Cette fonction vérifie si on a appuyé sur la touche entrée
 */
function verifEntree(e){
    if(e.key == "Enter"){
        ajoutMessage();
    }
}

/**
 * Cette fonction envoie le message en ajax à un fichier ajoutMessage.php
 */
function ajoutMessage(){
    // On récupère le message
    let message = document.querySelector("#texte").value
    
    // On vérifie si le message n'est pas vide
    if(message != ""){
        // On crée un objet JS
        let donnees = {}
        donnees["message"] = message

        // On convertit les données en JSON
        let donneesJson = JSON.stringify(donnees)

        // On envoie les données en POST en Ajax
        // On instancie XMLHttpRequest
        let xmlhttp = new XMLHttpRequest()

        // On gère la réponse
        xmlhttp.onreadystatechange = function(){
            // On vérifie si la requête est terminée
            if(this.readyState == 4){
                // On vérifie qu'on reçoit un code 201
                if(this.status == 201){
                    // L'enregistrement a fonctionné
                    // On efface le champ texte
                    document.querySelector("#texte").value = ""
                }else{
                    // L'enregistrement a échoué
                    let reponse = JSON.parse(this.response)
                    alert(reponse.message)
                }
            }
        }

        // On ouvre la requête
        xmlhttp.open("POST", "/sport/ajax/ajoutMessage.php")

        // On envoie la requête en incluant les données
        xmlhttp.send(donneesJson)
    }
}

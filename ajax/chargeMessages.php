<?php
// On vérifie la méthode utilisée
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // On est en GET
    // On vérifie si on a reçu un id
    if(isset($_GET['lastId'])){
        // On récupère l'id et on le nettoie
        $lastId = (int)strip_tags($_GET['lastId']);

        // On initialise le filtre
        $filtre = ($lastId > 0) ? " WHERE `chat`.`id` > $lastId" : '';

        // On se connecte à la base
        require_once('../include/include_bdd.php');

        // On écrit la requête
        $sql = 'SELECT `chat`.`id`, `chat`.`content`, `chat`.`date`, `membres`.`name` FROM `chat` LEFT JOIN `membres` ON `chat`.`id_user` = `membres`.`idmbrs`'.$filtre.' ORDER BY `chat`.`id` DESC LIMIT 5';
        

        // On exécute la requête
        $query = $bdd->query($sql);

        // On récupère les données
        $messages = $query->fetchAll();

        // On encode en JSON
        $messagesJson = json_encode($messages);

        // On envoie
        echo $messagesJson;
    }
}else{
    // Mauvaise méthode
    http_response_code(405);
    echo json_encode(['message' => 'Mauvaise méthode']);
}
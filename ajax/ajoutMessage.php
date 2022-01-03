<?php
// Ce fichier reçoit les données en json et enregistre le message
session_start();

// On vérifie la méthode
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $donneesJson = file_get_contents('php://input');

    // On convertit les données en objet PHP
    $donnees = json_decode($donneesJson);

    // On vérifie si on a un message
    if(isset($donnees->message) && !empty($donnees->message)){
        // On a un message
        // On le stocke
        // On se connecte
        require_once('../include/include_bdd.php');

        // On écrit la requête
        $sql = 'INSERT INTO `chat`(`content`, `id_user`) VALUES (:message, :user);';

        // On prépare la requête
        $query = $bdd->prepare($sql);

        // On injecte les valeurs
        $query->bindValue(':message', strip_tags($donnees->message), PDO::PARAM_STR);
        $query->bindValue(':user', $_SESSION['idmbrs'], PDO::PARAM_INT);

        // On exécute en vérifiant si ça fonctionne
        if($query->execute()){
            http_response_code(201);
            echo json_encode(['message' => 'Enregistrement effectué']);
        }else{
            http_response_code(400);
            echo json_encode(['message' => 'Une erreur est survenue']);
        }
    }else{
        // Pas de message
        http_response_code(400);
        echo json_encode(['message' => 'Le message est vide']);
    }
}else{
    // Mauvaise méthode
    http_response_code(405);
    echo json_encode(['message' => 'Mauvaise méthode']);
}
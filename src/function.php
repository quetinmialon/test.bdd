<?php

function getPDO(String $DSN, String $users, String $password){
$pdo = new PDO($DSN, $users, $password);
return $pdo;
};

function getPodcastsByPage($pdo, $page, $itemsPerPage) { 
    $offset = ($page - 1) * $itemsPerPage; // on determine a l'avance un OFFset calculé dynamiquement en fonction du nombre de page et d'objet par page attendu
  
    $query = $pdo->prepare('SELECT podcast.id, podcast.Nom AS podcastNom, excerpt,podcast.body,podcast.created_at, podcast.id_categorie, categorie.id AS cat_ID, categorie.Nom, Lien_Fichier_Audio  FROM podcast  JOIN categorie ON podcast.id_categorie = categorie.id ORDER BY podcast.id LIMIT :limit OFFSET :offset'); 
    // requete pour recupérer les info de podcast et y ajouter les info de la table catégorie en fonction de la valeur id_categorie de la table podcast qui doit correspondre a l'id de la table categorie
  
    $query->bindValue('limit', $itemsPerPage, PDO::PARAM_INT); // on bind en entier parce qu'on fait pas confiance au front 
    $query->bindValue('offset', $offset, PDO::PARAM_INT); 
  
    $query->execute(); // et on execute
    $podcasts = $query->fetchAll(); // puis on place tout dans la variable $podcasts sous le format d'un tableau avec fetchall()
    return $podcasts; // et on sort $podcasts de la fonction pour l'utiliser
  }
function getPodcastById($pdo, $id) {
    $query = $pdo->prepare('SELECT  podcast.id, podcast.Nom AS podcastNom, excerpt,podcast.body,podcast.created_at, podcast.id_categorie, categorie.id, categorie.Nom, Lien_Fichier_Audio FROM podcast JOIN categorie ON podcast.id_categorie = categorie.id WHERE podcast.id=:id'); // requete SQL poour recupèrer les info comme pour la requete plus haut mais on demande en plus avec le WHERE de ne garder que celles ou l'id correspond a l'id correspond au parametre entrée dans la fonction

    $query->bindValue('id', $id, PDO::PARAM_INT); // on met en entier parce que vilain front veut nous faire du mal on sait jamais

    $query->execute();

    $podcast = $query->fetch();

    return $podcast;
    
};

function getComment($Id_Podcast, $pdo){
    $query = $pdo->prepare('SELECT * FROM commentaire WHERE ID_podcast =:id'); // on recupère toutes les info de la table commentaire quand l'id_podcast (parametre de la fonction )est égal a l'id 

    $query->bindValue('id', $Id_Podcast, PDO::PARAM_INT); // vilain front tu ne nous auras pas

    $query->execute(); 

    $comment = $query->fetchall(); 

    return $comment;   
};

function getUser($pdo, $ID_user){ 
    if(! is_null($ID_user)) // on vérifie si l'utilisateur n'est pas NULL
    {
        
    $query = $pdo->query("SELECT * FROM commentaire JOIN users ON $ID_user = users.id" ,PDO :: FETCH_ASSOC); // avec une jointure on ajoute les information de l'utilisateur ayant posté le commentaire a la table commentaire


    $user = $query->fetch();

    return $user;
}else { // mise par defaut d'un anonymat en compte utilisateur si l'utilisateur est NULL
        $user['picture'] = 'https://img.freepik.com/vecteurs-libre/mysterieux-personnage-gangster_23-2148483453.jpg?w=740&t=st=1705305562~exp=1705306162~hmac=3acfdb16ec38bf7df6c47d01cd154de88f7aa323aee8f251167b13a6fd0aef1e';
        $user['Fullname'] = 'John Doe l\'anonyme';
        return $user;
    }
}

// après ça marche pas mais je sais pas bien pourquoi alors je suis triste
function postANewComment($pdo){
    
        $query = $pdo->prepare('INSERT INTO commentaire(body, ID_user, ID_podcast)VALUES (:body, :ID_user, :ID_podcast)');
        $query->bindValue('ID_podcast', $_GET['id'], PDO::PARAM_INT);
        $query->bindValue('ID_user', $_POST['ID_user'], PDO::PARAM_INT);
        $query ->bindValue('body', $_POST['body'], PDO :: PARAM_STR);
        $query -> execute();
        echo '<script>alert("commentaire posté")</script>';
        header('location:podcast.php?id='.$_GET['id']);
}
// ça marche ce truc pour le coup mais du coup je m'en sers que pour un truc qui marche pas
function getAllUsers($pdo){
    $query = $pdo->query('SELECT Fullname, id FROM users', PDO :: FETCH_ASSOC);
    $users = $query->fetchAll();
    return $users;
}


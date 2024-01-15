<?php
require 'function.php';
$pdo = getPDO('mysql:host=localhost;dbname=podcast', 'root', '');
$podcast = getPodcastById($pdo,$_GET['id']); // permet de recupérer les info du podcast correspondant a l'url
$comments = getComment( $_GET['id'],$pdo); // même chose mais pour les commentaire du podcast
$users = getAllUsers($pdo); // osef de celle la
if (!empty ($_POST)){
    postANewComment($pdo);
}

// on appel plein de fonction pour recupèrer des trucs de la BDD

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podcasts</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
</head>
<body class="antialiased">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <header class="py-10">
            <h1 class="text-2xl font-semibold text-slate-950">
                <a href="/">
                    Podcasts
                </a>
            </h1>
        </header>
        <div class="divide-y divide-slate-100">
            <article class="flex flex-col space-y-10 py-10 sm:py-12">
                <div class="self-start p-2 bg-slate-800 rounded-md text-sm font-semibold text-slate-50 leading-none"><?=$podcast['Nom']// dans la varible podcast on a plein de trucs recupèrer de la BDD?></div>
                <div class="sm:flex sm:justify-between sm:items-center">
                    <h2 class="text-2xl font-semibold text-slate-950"><?= $podcast['podcastNom'] ?></h2>
                    <time class="block mt-2 sm:mt-0 sm:ml-10 text-slate-500" datetime="<?= $podcast['created_at']  ?>"><?= $podcast['created_at'] ?></time>
                </div>
                <audio class="max-w-sm" src="<?= $podcast['Lien_Fichier_Audio'] ?>" controls></audio>
                <p class="leading-loose text-slate-600"><?= $podcast['body'] ?></p>
            </article>
            <div class="commentaires">
            <form action="" method="post" style="display: inline-flex; flex-direction: column">
                <label for="ID_user">selectionnez un utilisateur</label>
                <select name="ID_user">
                    <option value="Anonyme">Anonyme</option>
                    <?php foreach($users as $user) :// on lance une boucle foreach ici pour avoir autant d'option que d'utilisateur, c'est pas terrible dans les fait mais c'était amusant a faire?>
                        <option value = "<?= $user['id']?>"><?= $user['Fullname']?></option>
                    <?php endforeach; ?>
                </select>
                <label for="body">ecrivez votre commentaire ici...</label>
                <textarea name="body" cols="80" rows="5"></textarea>
                <input type="submit" value="Ajouter un commentaire" id="validationCom">
            </form>
            <?php foreach($comments as $comment) : 
                // on itère dans le tableau comments pour avoir chacun des commentaire individuellement et y affilier son utilisateur grace a getUser()     
                $user = getUser($pdo, $comment['ID_user']);

            ?>

            <div class="py-10 space-y-12">
                <div class="flex items-center gap-x-6">
                    <img class="h-14 w-14 rounded-full" src=<?= $user['picture'] // et du coup bah dans user maintenant comme une image et un nom?> alt="John Doe">
                    <div>
                        <div class="font-semibold text-slate-900"><?= $user['Fullname'] ?></div>
                        <div class="mt-1 text-slate-500"><?= $comment['body']?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>

</html>

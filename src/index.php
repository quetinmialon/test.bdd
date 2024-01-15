<?php
require ("function.php");
$pdo = getPDO('mysql:host=localhost;dbname=podcast', 'root', '');
$itemsPerPage = 2; // Nombre d'items par page fixé en dur 
$page = isset($_GET['page']) ? $_GET['page'] : 1; // on vérifie avec isset si l'url comprend bien un numéro de page, puis via une expression ternaire on applique par défault un numéro de page a 1 ou on recupère le numéro de la page avec la supervariable $get pour la stocker dans la variable $page 



$podcasts = getPodcastsByPage($pdo, $page, $itemsPerPage); 

// Calcul du nombre total de pages
$query = $pdo->query('SELECT COUNT(*) FROM podcast'); // COUNT(*) permet de renvoyer le nombre d'entrée total selectionné ici présent, cependant, la valeur arrive dans un tableau associatif, on utilise donc :
$totalItems = $query->fetchColumn(); // pour recupérer uniquement la valeur associé a COUNT (et non un tableau entier)
$totalPages = ceil($totalItems / $itemsPerPage); // on utilise la fonction ceil() qui permet d'arrondir a l'entier suppérieur pour obtenir le nombre de page 
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
        <div class="grid grid-cols-1 divide-y divide-slate-100">
            <?php foreach ($podcasts as $podcast): // on itère dans $podcasts avec une boucle foreach() et stocker chacune des cellule du tableau dans la variable temporaire $podcast ?>
                <article class="flex flex-col space-y-6 py-10 sm:py-12">
                    <div class="self-start p-2 bg-slate-800 rounded-md text-xs font-semibold text-slate-50 leading-none">
                        <?= $podcast['Nom'] // on utilise donc la variable temporaire $podcast pour afficher les éléments recupèrer et les echo dans l'HTML ?>
                    </div>
                    <div class="sm:flex sm:justify-between sm:items-center">
                        <h2 class="font-semibold text-slate-950">
                            <?= $podcast['podcastNom'] ?>
                        </h2>
                        <time class="block mt-2 sm:mt-0 sm:ml-10 text-sm text-slate-500"
                            datetime="<?= $podcast['created_at'] ?>">
                            <?= $podcast['created_at'] ?>
                        </time>
                    </div>
                    <p class="text-sm leading-6 text-slate-600">
                        <?= $podcast['excerpt'] ?>
                    </p>
                    <a class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800"
                        href="podcast.php?id=<?= $podcast['id'] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                            class="w-4 h-4 mr-2">
                            <path
                                d="M3 3.732a1.5 1.5 0 0 1 2.305-1.265l6.706 4.267a1.5 1.5 0 0 1 0 2.531l-6.706 4.268A1.5 1.5 0 0 1 3 12.267V3.732Z" />
                        </svg>
                        Écouter
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
        <nav aria-label="Page navigation">
            <ul class="list-style-none flex">
                <?php for ($i = 1; $i <= $totalPages; $i++): // boucle for a l'ancienne pour iterer dans le nombre de page total?>
                    <li><a href="?page=<?= $i //au click on ajouter ou on modofie l'url associé a page pour y ajouter la valeur associé a la variable $i?>">
                            <?= $i ?>
                        </a>&nbsp;<!-- permet d'inserer un espace pour l'UX--></li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</body>

</html>
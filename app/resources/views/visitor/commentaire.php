<?php
session_start();

if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}

require '../../../includes/classes/Commentaire.php';
require '../../../includes/functions.php';

if (!isset($_SESSION['user_connecte'])) {
    header("Location: login.php");
    exit;
}

$id_visite = (int)($_GET['id'] ?? 0);
$id_user = $_SESSION['user_connecte'];

if ($id_visite === 0) die("ID visite invalide");

$db = new Database();
$conn = $db->getConnection();

/* POST */
$error = $success = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $note = (int)$_POST['note'];
    $titre = trim($_POST['titre']);
    $texte = trim($_POST['commentaire']);

    $regex_titre = "/^[a-zA-ZÀ-ÿ0-9\s'’.,!?-]{3,100}$/";
    $regex_commentaire = "/^[a-zA-ZÀ-ÿ0-9\s'’.,!?():;-]{10,1000}$/";

    if (!validation($titre, $regex_titre) || !validation($texte, $regex_commentaire) || !Commentaire::validate($titre, $texte, $note)) {
        $error = "Tous les champs doivent être valides.";
    } else {
        $commentaire = new Commentaire($note,$texte,$id_visite, $id_user, $titre);
        $success = $commentaire->save($conn) ? "Commentaire publié avec succès !" : "Erreur lors de l'enregistrement.";
        header("Location: commentaire.php?id=$id_visite&success=1");
        exit;
    }
}

/* Récupération infos visite et commentaires */
$visite = Commentaire::getVisiteInfo($conn, $id_visite);
$commentaires = Commentaire::getCommentairesByVisite($conn, $id_visite);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Commentaire - Zoo ASSAD</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

<nav class="bg-white shadow-lg">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <span class="text-3xl">🦁</span>
            <a href="fiche_speciale.php" class="text-xl font-bold text-gray-800">Zoo ASSAD</a>
        </div>
        <div class="flex space-x-6">
            <a href="home.php" class="text-gray-600 hover:text-blue-600">Accueil</a>
            <a href="animals.php" class="text-gray-600 hover:text-blue-600">Animaux</a>
            <a href="visites.php" class="text-gray-600 hover:text-blue-600">Visites</a>
            <a href="login.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Connexion</a>
        </div>
    </div>
</nav>

<main class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold mb-4">Laisser un commentaire</h1>
        <p class="text-gray-600">Partagez votre expérience sur la visite guidée</p>
    </div>

    <?php if($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $success ?></div>
    <?php endif; ?>

    <?php if($visite): ?>
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Visite concernée</h2>
            <div class="flex items-center"><div class="w-24 font-semibold">Visite:</div> <div class="text-lg"><?= $visite['titre'] ?></div></div>
            <div class="flex items-center mt-2"><div class="w-24 font-semibold">Date:</div> <div><?= $visite['date_heure'] ?></div></div>
            <div class="flex items-center mt-2"><div class="w-24 font-semibold">Guide:</div> <div><?= $visite['nom'] ?></div></div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="POST">
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Note (1 à 5)</label>
                <input type="number" name="note" min="1" max="5" required class="w-32 mx-auto block px-4 py-2 border rounded-lg text-center">
                <div class="flex justify-between text-sm text-gray-500 mt-2">
                    <span>Mauvais</span>
                    <span>Excellent</span>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre du commentaire</label>
                <input type="text" name="titre" required class="w-full px-4 py-2 border rounded-lg" placeholder="Ex: Super expérience !">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Votre commentaire *</label>
                <textarea name="commentaire" rows="6" required class="w-full px-4 py-2 border rounded-lg" placeholder="Décrivez votre expérience..."></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="bg-blue-600 text-white py-3 px-8 rounded-lg font-semibold hover:bg-blue-700 text-lg">
                    <i class="fas fa-paper-plane mr-2"></i>Publier le commentaire
                </button>
            </div>
        </form>
    </div>

    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Commentaires des autres visiteurs</h2>
        <div class="space-y-6">
            <?php if($commentaires): ?>
                <?php foreach($commentaires as $row): ?>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-bold"><?= $row['titre'] ?> !</h3>
                                <div class="flex items-center text-yellow-500">
                                    <span class="ml-2 text-sm text-gray-600"><?= $row['date_commentaire'] ?></span>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">Par <?= $row['nom'] ?></span>
                        </div>
                        <p class="text-gray-700 mt-2 mb-2">Note: <?= $row['note'] ?></p>
                        <p class="text-gray-700"><?= $row['content'] ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-600">Aucun commentaire pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<footer class="bg-gray-800 text-white py-8 mt-12 text-center">
    Zoo Virtuel ASSAD - Partagez votre expérience
</footer>

</body>
</html>

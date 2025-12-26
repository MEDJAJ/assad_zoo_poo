<?php
session_start();


if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}

include '../../../includes/functions.php';
include '../../../includes/classes/Visite.php';


$db = new Database();
$conn = $db->getConnection();


$message = "";
$etat = "";


$regexTitre = '/^[a-zA-ZÀ-ÿ0-9\s]{3,100}$/';
$regexDescription = '/^[a-zA-ZÀ-ÿ0-9\s.,;:!?\'"()\-\n]{10,500}$/';
$regexPrix = '/^\d+(\.\d{1,2})?$/';
$regexLangue = '/^(fr|ar|en)$/';


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $date_visite = $_POST['date_visite'];
    $duree = (int) $_POST['duree'];
    $prix = $_POST['prix'];
    $capacite = (int) $_POST['capacite_max'];
    $langue = $_POST['langue'];

    if (
        !validation($titre, $regexTitre) ||
        !validation($description, $regexDescription) ||
        !validation($prix, $regexPrix) ||
        !validation($langue, $regexLangue)
    ) {
        $etat = "error";
        $message = "Tous les champs doivent être valides";
    } else {

       
        $visite = new Visite();
        $visite->setTitre($titre);
        $visite->setDateVisite($date_visite);
        $visite->setDuree($duree);
        $visite->setPrix($prix);
        $visite->setCapacite($capacite);
        $visite->setLangue($langue);
        $visite->setIdGuide($_SESSION["user_id"]);

        if ($visite->createVisite($conn)){
            $etat = "success";
            $message = "Visite créée avec succès";
        } else {
            $etat = "error";
            $message = "Erreur lors de la création de la visite";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer visite - Zoo ASSAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">

<nav class="bg-white shadow-lg">
    <div class="container mx-auto px-4 py-3 flex justify-between">
        <div class="flex items-center space-x-2">
            <span class="text-3xl">🦁</span>
            <span class="text-xl font-bold">Zoo ASSAD</span>
        </div>
        <div class="flex space-x-6 items-center">
            <a href="guide_dashboard.php">Dashboard</a>
            <a href="create_visite.php" class="text-blue-600 font-semibold">Créer visite</a>
            <span class="flex items-center gap-2">
                <i class="fas fa-user-circle"></i>
                <?= htmlspecialchars($_SESSION['role']) ?>
            </span>
        </div>
    </div>
</nav>

<main class="container mx-auto px-4 py-8">

    <h1 class="text-3xl font-bold mb-6">Créer une nouvelle visite guidée</h1>

    <?php if ($etat === "error"): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6"><?= htmlspecialchars($message) ?></div>
    <?php elseif ($etat === "success"): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow p-8">
        <form method="POST" class="space-y-6">

            <input type="text" name="titre" placeholder="Titre de la visite" class="w-full p-3 border rounded" required>

            <textarea name="description" rows="4" placeholder="Description" class="w-full p-3 border rounded" required></textarea>

            <div class="grid md:grid-cols-2 gap-4">
                <input type="datetime-local" name="date_visite" class="p-3 border rounded" required>
                <input type="number" name="duree" min="15" max="240" placeholder="Durée (min)" class="p-3 border rounded" required>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <input type="number" name="prix" step="0.01" placeholder="Prix (MAD)" class="p-3 border rounded" required>
                <input type="number" name="capacite_max" min="1" max="50" placeholder="Capacité max" class="p-3 border rounded" required>
            </div>

            <select name="langue" class="w-full p-3 border rounded" required>
                <option value="">Langue</option>
                <option value="fr">Français</option>
                <option value="ar">Arabe</option>
                <option value="en">Anglais</option>
            </select>

            <div class="flex gap-4">
                <button class="bg-blue-600 text-white px-6 py-3 rounded">
                    <i class="fas fa-save"></i> Créer
                </button>
                <a href="guide_dashboard.php" class="bg-gray-300 px-6 py-3 rounded">Annuler</a>
            </div>

        </form>
    </div>

</main>
</body>
</html>

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


if (!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: guide_dashboard.php");
    exit;
}

$id_visite = (int) $_GET['id'];


$visiteObj = new Visite();
$visite = $visiteObj->getVisiteById($conn, $id_visite);

if (!$visite) {
    echo "Visite non trouvée";
    exit;
}


$message = "";
$etat = "";


$regexTitre = '/^[a-zA-ZÀ-ÿ0-9\s]{3,100}$/';
$regexPrix = '/^\d+(\.\d{1,2})?$/';
$regexLangue = '/^(fr|ar|en)$/';


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titre = trim($_POST["titre"]);
    $date_heure = $_POST["date_heure"];
    $langue = $_POST["langue"];
    $capacite = (int) $_POST["capaciter_max"];
    $duree = (int) $_POST["duree"];
    $prix = $_POST["prix"];

    if (
        !validation($titre, $regexTitre) ||
        !validation($prix, $regexPrix) ||
        !validation($langue, $regexLangue) ||
        $capacite < 1 || $duree < 1
    ){
        $etat = "error";
        $message = "Tous les champs doivent être valides";
    } else {

        $visiteObj->setTitre($titre);
        $visiteObj->setDateVisite($date_heure);
        $visiteObj->setLangue($langue);
        $visiteObj->setCapacite($capacite);
        $visiteObj->setDuree($duree);
        $visiteObj->setPrix($prix);
      

        if ($visiteObj->updateVisite($conn, $id_visite)) {
            $etat = "success";
            $message = "Visite mise à jour avec succès";

           
            $visite = $visiteObj->getVisiteById($conn, $id_visite);
        } else {
            $etat = "error";
            $message = "Erreur lors de la mise à jour";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Modifier Visite - Zoo ASSAD</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">
        <i class="fas fa-edit"></i> Modifier la visite
    </h1>

    <?php if ($etat === "error"): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php elseif ($etat === "success"): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <input type="text" name="titre" class="p-3 border rounded"
               value="<?= htmlspecialchars($visite['titre']) ?>" required>

        <input type="datetime-local" name="date_heure"
               value="<?= date('Y-m-d\TH:i', strtotime($visite['date_heure'])) ?>"
               class="p-3 border rounded" required>

        <select name="langue" class="p-3 border rounded" required>
            <option value="fr" <?= $visite['langue']=='fr'?'selected':'' ?>>Français</option>
            <option value="ar" <?= $visite['langue']=='ar'?'selected':'' ?>>Arabe</option>
            <option value="en" <?= $visite['langue']=='en'?'selected':'' ?>>Anglais</option>
        </select>

        <input type="number" name="capaciter_max" min="1"
               value="<?= htmlspecialchars($visite['capaciter_max']) ?>"
               class="p-3 border rounded" required>

        <input type="number" name="duree" min="15" max="240"
               value="<?= htmlspecialchars($visite['duree']) ?>"
               class="p-3 border rounded" required>

        <input type="number" name="prix" step="0.01" min="0"
               value="<?= htmlspecialchars($visite['prix']) ?>"
               class="p-3 border rounded" required>

      

        <div class="md:col-span-2 flex justify-end gap-4 mt-4">
            <a href="guide_dashboard.php" class="bg-gray-300 px-6 py-2 rounded">Annuler</a>
            <button class="bg-blue-600 text-white px-6 py-2 rounded">Enregistrer</button>
        </div>

    </form>
</div>

</body>
</html>

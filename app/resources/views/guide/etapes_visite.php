<?php
session_start();

if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}

include '../../../includes/functions.php';
include '../../../includes/classes/EtapVisite.php';

$db   = new Database();
$conn = $db->getConnection();

$message = "";
$etat    = "";

$id_visite = (int)($_GET['id_visite'] ?? 0);
if ($id_visite <= 0) {
    die("Visite invalide");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $regexTitreEtape = '/^[a-zA-ZÀ-ÿ0-9\s]{3,100}$/';
    $regexDescriptionEtape = '/^[a-zA-ZÀ-ÿ0-9\s.,;:!?\'"()\-\n]{0,500}$/';
    $regexOrdreEtape = '/^\d+$/';

    $titreetape       = trim($_POST['titreetape'] ?? '');
    $descriptionetape = trim($_POST['descriptionetape'] ?? '');
    $ordreetape       = (int)($_POST['ordreetape'] ?? 0);

    if (
        !validation($titreetape, $regexTitreEtape) ||
        !validation($descriptionetape, $regexDescriptionEtape) ||
        !validation($ordreetape, $regexOrdreEtape)
    ) {
        $message = "Tous les champs doivent être valides";
        $etat = "error";
    } else {
        $etape = new EtapeVisite();
        $etape->setIdVisite($id_visite);
        $etape->setTitre($titreetape);
        $etape->setDescription($descriptionetape);
        $etape->setOrdre($ordreetape);

        if ($etape->createEtape($conn)) {
            $message = "Étape ajoutée avec succès";
            $etat = "success";
        } else {
            $message = "Erreur lors de l'ajout";
            $etat = "error";
        }
    }
}

$etapeObj = new EtapeVisite();
$etapes = $etapeObj->getEtapesByVisite($conn, $id_visite);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Étapes de visite</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Ajouter des étapes pour une visite guidée</h1>

    <?php if ($etat === "error") { ?>
        <div class="bg-red-100 border border-red-400 text-red-700 p-4 mb-4 rounded">
            <?= $message ?>
        </div>
    <?php } elseif ($etat === "success") { ?>
        <div class="bg-green-100 border border-green-400 text-green-700 p-4 mb-4 rounded">
            <?= $message ?>
        </div>
    <?php } ?>

    <form method="POST" class="bg-white p-6 rounded-xl shadow space-y-6">
        <div>
            <label class="block font-medium mb-2">Titre de l'étape *</label>
            <input type="text" name="titreetape" required
                   class="w-full px-4 py-3 border rounded-lg">
        </div>

        <div>
            <label class="block font-medium mb-2">Description</label>
            <textarea name="descriptionetape" rows="4"
                      class="w-full px-4 py-3 border rounded-lg"></textarea>
        </div>

        <div>
            <label class="block font-medium mb-2">Ordre *</label>
            <input type="number" name="ordreetape" min="1" required
                   class="w-full px-4 py-3 border rounded-lg">
        </div>

        <button type="submit"
                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            Ajouter l'étape
        </button>
    </form>
</div>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center text-blue-500">
        Étapes de la visite guidée
    </h1>

<?php if (!empty($etapes)) { ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php foreach ($etapes as $row) { ?>
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-xl font-bold mb-2">
                <?= htmlspecialchars($row['titre_etape']) ?>
            </h2>
            <p class="text-gray-600 mb-2">
                <?= nl2br(htmlspecialchars($row['description_etape'])) ?>
            </p>
            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">
                Ordre <?= $row['ordre_etape'] ?>
            </span>
        </div>
    <?php } ?>
    </div>
<?php } else { ?>
    <p class="text-center text-gray-500">Aucune étape trouvée</p>
<?php } ?>
</div>

</body>
</html>

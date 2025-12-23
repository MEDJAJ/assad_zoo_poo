<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}


if (!isset($_GET['id'])) {
    header("Location: guide_dashboard.php");
    exit;
}

$id_visite = intval($_GET['id']);
$result = mysqli_query($con, "SELECT * FROM visite_guidee WHERE id_visiteguide = $id_visite");

if (mysqli_num_rows($result) == 0) {
    echo "Visite non trouvée";
    exit;
}

$visite = mysqli_fetch_assoc($result);

$message = "";
$etat = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = trim($_POST["titre"]);
    $date_heure = trim($_POST["date_heure"]);
    $langue = trim($_POST["langue"]);
    $capacite = intval($_POST["capaciter_max"]);
    $duree = intval($_POST["duree"]);
    $prix = floatval($_POST["prix"]);
    $status = trim($_POST["status_visiteguide"]);
    $id_guide = intval($_POST["id_guide"]);

  
    if (empty($titre) || empty($date_heure) || empty($langue) || $capacite < 1 || $duree < 1 || $prix < 0 || empty($status) || $id_guide < 1) {
        $etat = "error";
        $message = "Tous les champs doivent être remplis correctement.";
    } else {
        $titre = mysqli_real_escape_string($con, $titre);
        $date_heure = mysqli_real_escape_string($con, $date_heure);
        $langue = mysqli_real_escape_string($con, $langue);
        $status = mysqli_real_escape_string($con, $status);

        $sql = "UPDATE visite_guidee SET 
                    titre='$titre',
                    date_heure='$date_heure',
                    langue='$langue',
                    capaciter_max=$capacite,
                    duree=$duree,
                    prix=$prix,
                    status_visiteguide='$status',
                    id_guide=$id_guide
                WHERE id_visiteguide=$id_visite";

        if (mysqli_query($con, $sql)) {
            $etat = "success";
            $message = "Visite mise à jour avec succès !";
            $visite = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM visite_guidee WHERE id_visiteguide = $id_visite"));
        } else {
            $etat = "error";
            $message = "Erreur : " . mysqli_error($con);
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
    <h1 class="text-2xl font-bold mb-6">Modifier la visite</h1>

    <?php if ($etat==="error"): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $message ?></div>
    <?php elseif($etat==="success"): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="text" name="titre" placeholder="Titre de la visite" value="<?= htmlspecialchars($visite['titre']) ?>" class="p-3 border rounded-lg" required>
        <input type="datetime-local" name="date_heure" value="<?= date('Y-m-d\TH:i', strtotime($visite['date_heure'])) ?>" class="p-3 border rounded-lg" required>
        
        <select name="langue" class="p-3 border rounded-lg" required>
            <option value="fr" <?= $visite['langue']=='fr'?'selected':'' ?>>Français</option>
            <option value="ar" <?= $visite['langue']=='ar'?'selected':'' ?>>Arabe</option>
            <option value="en" <?= $visite['langue']=='en'?'selected':'' ?>>Anglais</option>
        </select>

        <input type="number" name="capaciter_max" placeholder="Capacité maximale" value="<?= htmlspecialchars($visite['capaciter_max']) ?>" class="p-3 border rounded-lg" min="1" required>
        <input type="number" name="duree" placeholder="Durée (minutes)" value="<?= htmlspecialchars($visite['duree']) ?>" class="p-3 border rounded-lg" min="1" required>
        <input type="number" name="prix" placeholder="Prix (MAD)" value="<?= htmlspecialchars($visite['prix']) ?>" class="p-3 border rounded-lg" min="0" step="0.01" required>

        <select name="status_visiteguide" class="p-3 border rounded-lg" required>
            <option value="planifiee" <?= $visite['status_visiteguide']=='planifiee'?'selected':'' ?>>Planifiée</option>
            <option value="en cours" <?= $visite['status_visiteguide']=='en cours'?'selected':'' ?>>En cours</option>
            <option value="terminee" <?= $visite['status_visiteguide']=='terminee'?'selected':'' ?>>Terminée</option>
            <option value="annulee" <?= $visite['status_visiteguide']=='annulee'?'selected':'' ?>>Annulée</option>
        </select>

        <input type="number" name="id_guide" placeholder="ID Guide" value="<?= htmlspecialchars($visite['id_guide']) ?>" class="p-3 border rounded-lg" min="1" required>

        <div class="md:col-span-2 flex justify-end gap-4 mt-4">
            <a href="guide_dashboard.php" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Annuler</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Enregistrer</button>
        </div>
    </form>
</div>

</body>
</html>

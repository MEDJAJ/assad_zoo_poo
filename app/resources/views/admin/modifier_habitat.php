<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}
include '../../../includes/functions.php';

include '../../../includes/classes/habitat.php';

$db = new Database();
$conn = $db->getConnection();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: habitats_admin.php");
    exit;
}

$id_habitat = (int) $_GET['id'];

$habitat = new Habitat();


$sql = "SELECT * FROM habitats WHERE id_habitat = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id_habitat]);
$current = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$current) {
    echo "Habitat non trouvé";
    exit;
}

$message = "";
$etat = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"]);
    $type = trim($_POST["typeclimat"]);
    $description = trim($_POST["description"]);
    $zone = trim($_POST["zone"]);

    if (
        !validation($nom, "/^[a-zA-ZÀ-ÿ\s]{2,50}$/") ||
        !validation($type, "/^[a-zA-ZÀ-ÿ\s]{2,50}$/") ||
        !validation($description, "/^[a-zA-ZÀ-ÿ\s]{2,250}$/") ||
        !validation($zone, "/^[a-zA-ZÀ-ÿ\s]{2,50}$/")
    ) {
        $etat = "error";
        $message = "Tous les champs doivent être valides";
    } else {
        $habitat->setNom($nom);
        $habitat->setTypeClimat($type);
        $habitat->setDescription($description);
        $habitat->setZoneZoo($zone);

        if ($habitat->update($conn,$id_habitat)) {
            $etat = "success";
            $message = "Habitat mis à jour avec succès";

            $current = [
                'nom' => $nom,
                'typeclimat' => $type,
                'description' => $description,
                'zonezoo' => $zone
            ];
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
<title>Modifier Habitat - Zoo ASSAD</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">

<div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-xl shadow">
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
        <i class="fas fa-edit"></i> Modifier Habitat
    </h1>

    <?php if ($etat==="error"): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($message) ?></div>
    <?php elseif($etat==="success"): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="text" name="nom" placeholder="Nom de l'habitat" class="p-3 border rounded-lg" 
               value="<?= htmlspecialchars($current['nom']) ?>" required>
        <input type="text" name="typeclimat" placeholder="Type de climat" class="p-3 border rounded-lg" 
               value="<?= htmlspecialchars($current['typeclimat']) ?>" required>
        <textarea name="description" placeholder="Description" rows="3" class="p-3 border rounded-lg md:col-span-2" required><?= htmlspecialchars($current['description']) ?></textarea>
        <input type="text" name="zone" placeholder="Zone du zoo" class="p-3 border rounded-lg" 
               value="<?= htmlspecialchars($current['zonezoo']) ?>" required>

        <div class="md:col-span-2 flex justify-end gap-4 mt-4">
            <a href="habitats_admin.php" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Annuler</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Enregistrer</button>
        </div>
    </form>
</div>

</body>
</html>

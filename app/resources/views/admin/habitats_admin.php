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

$habitat = new Habitat();
$message = "";
$etat = "";


if (isset($_GET["success"])) {
    $etat = "success";
    $message = "Habitat créé avec succès";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        if ($habitat->insert($conn)) {
            header("Location: habitats_admin.php?success=1");
            exit;
        } else {
            $etat = "error";
            $message = "Erreur lors de l'insertion";
        }
    }
}


$habitats = $habitat->getAll($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestion Habitats - Zoo ASSAD</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
<div class="flex">


<div class="w-64 bg-gray-900 text-white min-h-screen">
    <div class="p-6">
        <div class="flex items-center space-x-3 mb-8">
            <span class="text-3xl">🦁</span>
            <span class="text-xl font-bold">ASSAD Admin</span>
        </div>
        <nav class="space-y-2">
            <a href="admin_dashboard.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
            <a href="users_admin.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                <i class="fas fa-users"></i><span>Utilisateurs</span>
            </a>
            <a href="animals_admin.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                <i class="fas fa-paw"></i><span>Animaux</span>
            </a>
            <a href="habitats_admin.php" class="flex items-center space-x-3 p-3 bg-blue-700 rounded-lg">
                <i class="fas fa-tree"></i><span>Habitats</span>
            </a>
            <a href="stats.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                <i class="fas fa-chart-bar"></i><span>Statistiques</span>
            </a>
        </nav>
    </div>
</div>


<div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Gestion des Habitats</h1>
    </div>

   
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Ajouter un habitat</h2>

        <?php if($etat==="error"): ?>
        <div class="text-center mt-4 mb-4 text-red-500 border border-black pb-2 bg-red-100">
            <p class="text-red-600 mt-2 font-bold"><?= htmlspecialchars($message) ?></p>
        </div>
        <?php elseif($etat==="success"): ?>
        <div class="text-center mt-4 mb-4 text-green-500 border border-black pb-2 bg-green-100">
            <p class="text-green-600 mt-2 font-bold"><?= htmlspecialchars($message) ?></p>
        </div>
        <?php endif; ?>

        <form class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST">
            <input type="text" placeholder="Nom de l'habitat" class="p-3 border rounded-lg" name="nom" required>
            <input type="text" placeholder="Type de climat" class="p-3 border rounded-lg" name="typeclimat" required>
            <textarea placeholder="Description" rows="3" class="p-3 border rounded-lg md:col-span-2" name="description" required></textarea>
            <input type="text" placeholder="Zone du zoo" class="p-3 border rounded-lg" name="zone" required>
            <div class="md:col-span-2 space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Enregistrer
                </button>
                <button type="reset" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Annuler
                </button>
            </div>
        </form>
    </div>

  
    <?php if(count($habitats) > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($habitats as $row): ?>
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-bold"><?= htmlspecialchars($row["nom"]) ?></h3>
                <div class="flex space-x-2">
                    <a href="modifier_habitat.php?id=<?= $row['id_habitat'] ?>">
                        <button class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </button>
                    </a>
                    <a href="supprimer_habitat.php?id=<?= $row['id_habitat'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet habitat ?');">
                        <button class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </a>
                </div>
            </div>
            <div class="space-y-2">
                <p><strong>Climat:</strong> <?= htmlspecialchars($row["typeclimat"]) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($row["description"]) ?></p>
                <p><strong>Zone:</strong> <?= htmlspecialchars($row["zonezoo"]) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
</div>
</body>
</html>

<?php

if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}


$id_habitat = isset($_GET['habitat']) ? intval($_GET['habitat']) : 0;
$pays = isset($_GET['pays']) ? trim($_GET['pays']) : '';


$habitats = mysqli_query($con, "SELECT id_habitat, nom FROM habitats");


$pays_list = mysqli_query($con, "SELECT DISTINCT pays_origine FROM animaux");


$sql = "
SELECT a.*, h.nom AS habitat_nom
FROM animaux a
INNER JOIN habitats h ON a.id_habitat = h.id_habitat
WHERE 1
";

$params = [];
$types = "";


if ($id_habitat > 0) {
    $sql .= " AND a.id_habitat = ?";
    $params[] = $id_habitat;
    $types .= "i";
}


if (!empty($pays)) {
    $sql .= " AND a.pays_origine = ?";
    $params[] = $pays;
    $types .= "s";
}

$stmt = $con->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result_animaux = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tous les animaux - Zoo ASSAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">


<nav class="bg-white shadow-lg">
    <div class="container mx-auto px-4 py-3 flex justify-between">
        <div class="flex items-center space-x-2">
            <span class="text-3xl">🦁</span>
            <a href="home.php" class="text-xl font-bold">Zoo ASSAD</a>
        </div>
        <div class="space-x-6">
            <a href="home.php">Accueil</a>
            <a href="animals.php" class="text-blue-600 font-semibold">Animaux</a>
            <a href="visites.php">Visites</a>
            <a href="../auth/login.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Connexion</a>
        </div>
    </div>
</nav>


<header class="bg-white py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-6">Tous les animaux</h1>

        <form method="GET" class="flex flex-col md:flex-row gap-4 mb-8">

            
            <div class="flex-1">
                <label class="block text-sm font-medium mb-2">Habitat</label>
                <select name="habitat" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">Tous les habitats</option>
                    <?php while ($h = mysqli_fetch_assoc($habitats)) { ?>
                        <option value="<?= $h['id_habitat'] ?>"
                            <?= ($id_habitat == $h['id_habitat']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($h['nom']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>


            <div class="flex-1">
                <label class="block text-sm font-medium mb-2">Pays d'origine</label>
                <select name="pays" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">Tous les pays</option>
                    <?php while ($p = mysqli_fetch_assoc($pays_list)) { ?>
                        <option value="<?= htmlspecialchars($p['pays_origine']) ?>"
                            <?= ($pays === $p['pays_origine']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['pays_origine']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>


            <div class="flex items-end">
                <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Filtrer
                </button>
            </div>
        </form>
    </div>
</header>


<main class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        <?php if ($result_animaux->num_rows > 0) { ?>
            <?php while ($a = $result_animaux->fetch_assoc()) { ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="../../../assets/uploads/<?= htmlspecialchars($a['image']) ?>"
                         class="w-full h-48 object-cover">

                    <div class="p-4">
                        <h3 class="font-bold text-lg"><?= htmlspecialchars($a['nom']) ?></h3>

                        <div class="text-gray-600 mb-1">
                            <i class="fas fa-paw mr-2"></i><?= htmlspecialchars($a['espece']) ?>
                        </div>

                        <div class="text-gray-600 mb-3">
                            <i class="fas fa-globe mr-2"></i><?= htmlspecialchars($a['pays_origine']) ?>
                        </div>

                      
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p class="col-span-full text-center text-gray-500">
                Aucun animal trouvé.
            </p>
        <?php } ?>

    </div>
</main>

<footer class="bg-gray-800 text-white py-8 mt-12 text-center">
    &copy; 2024 Zoo Virtuel ASSAD
</footer>

</body>
</html>

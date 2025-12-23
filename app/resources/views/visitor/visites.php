<?php
session_start();
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}


$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "
SELECT v.*, u.nom AS nom_guide
FROM visite_guidee v
INNER JOIN Utilisateur u ON v.id_guide = u.id_utilisateure
WHERE 1
";

$params = [];
$types = "";


if (!empty($search)) {
    $sql .= " AND v.titre LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

$sql .= " ORDER BY v.date_heure ASC";

$stmt = $con->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Visites guidées - Zoo ASSAD</title>
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
            <a href="visites.php" class="text-blue-600 font-semibold">Visites</a>
            <a href="../auth/login.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Connexion</a>
        </div>
    </div>
</nav>

<header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4">Visites guidées virtuelles</h1>
        <p class="text-xl">Explorez notre zoo depuis chez vous avec nos guides experts</p>
        <form method="GET" class="mt-6 max-w-3xl mx-auto relative">
            <input type="text" name="search" placeholder="Rechercher une visite par titre..."
                   value="<?= htmlspecialchars($search) ?>"
                   class="w-full px-6 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-black">
            <button type="submit" class="absolute right-3 top-3 bg-blue-600 text-white px-6 py-1 rounded-lg hover:bg-blue-700">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</header>


<main class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($v = $result->fetch_assoc()): 
                
   $reservation_sum = "
    SELECT SUM(nb_personnes) AS total_personnes
    FROM reservation
    WHERE id_visiteguide = " . (int)$v['id_visiteguide'];

    $result_sum = mysqli_query($con, $reservation_sum);
    $row_sum = mysqli_fetch_assoc($result_sum);

    $places_reservees = $row_sum['total_personnes'] ?? 0;

    $capacite_max = (int)$v['capaciter_max'];

    if ($places_reservees >= $capacite_max) {
        $status = "Complet";
        $color="red";
    } elseif ($places_reservees >= ($capacite_max - 3)){
        $status = "Limité";
        $color="yellow";
    } else {
        $status = "Disponible";
         $color="green";
    }


if ($status !== $v['status_visiteguide']) {
    $update_status ="
        UPDATE visite_guidee
        SET status_visiteguide = ?
        WHERE id_visiteguide = ?
    ";
    $stmt_update = $con->prepare($update_status);
    $stmt_update->bind_param("si", $status, $v['id_visiteguide']);
    $stmt_update->execute();
}
                ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold"><?= htmlspecialchars($v['titre']) ?></h3>
                           
                            <span class="bg-<?= $color ?>-100 text-<?= $color ?>-800 px-3 py-1 rounded-full text-sm"><?= htmlspecialchars($status) ?></span>
                        </div>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-blue-500 w-6"></i>
                                <span class="ml-2"><?= date("d M Y - H:i", strtotime($v['date_heure'])) ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-green-500 w-6"></i>
                                <span class="ml-2">Durée: <?= htmlspecialchars($v['duree']) ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tag text-yellow-500 w-6"></i>
                                <span class="ml-2">Prix: <?= htmlspecialchars($v['prix']) ?> MAD</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-language text-purple-500 w-6"></i>
                                <span class="ml-2">Langue: <?= htmlspecialchars($v['langue']) ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users text-red-500 w-6"></i>
                                <span class="ml-2">Capacité max: <?= htmlspecialchars($v['capaciter_max']) ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user text-gray-500 w-6"></i>
                                <span class="ml-2">Guide: <?= htmlspecialchars($v['nom_guide']) ?></span>
                            </div>
                        </div>
                        
                     <?php if ($status !== "Complet"): ?>
    <a href="reservation.php?id=<?= $v['id_visiteguide'] ?>"
       class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700">
        Réserver
    </a>
<?php else: ?>
    <div class="block w-full bg-gray-300 text-gray-600 text-center py-3 rounded-lg cursor-not-allowed">
        Complet
    </div>
<?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="col-span-full text-center text-gray-500">Aucune visite trouvée.</p>
        <?php endif; ?>
    </div>
</main>


<footer class="bg-gray-800 text-white py-8 mt-12 text-center">
    Zoo Virtuel ASSAD - Réservez votre visite guidée virtuelle
</footer>

</body>
</html>

<?php
session_start();
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}
include '../../../includes/classes/Visite.php';
include '../../../includes/classes/reservation.php';

$db = new Database();
$conn = $db->getConnection();
$visite=new Visite();
$reservation=new Reservation();
$search = isset($_GET['search']) ? trim($_GET['search']) : '';


$result = $visite->searchByTitle($conn,$search);



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
        <?php if (count($result) > 0): ?>
            <?php foreach($result as $v){

          
                
 $result_reservation=$reservation->updateStatusVisite($conn,$v)
                ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold"><?= htmlspecialchars($v['titre']) ?></h3>
                           
                            <span class="bg-<?=  $result_reservation['color'] ?>-100 text-<?=  $result_reservation['color'] ?>-800 px-3 py-1 rounded-full text-sm"><?= htmlspecialchars( $result_reservation['status']) ?></span>
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
                        
                     <?php if ( $result_reservation['status'] !== "Complet"): ?>
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
            <?php   }; ?>
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

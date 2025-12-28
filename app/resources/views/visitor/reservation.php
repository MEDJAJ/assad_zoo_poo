<?php
session_start();

if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}

require '../../../includes/classes/Reservation.php';
require '../../../includes/classes/EtapVisite.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_visite = (int)($_GET['id'] ?? 0);
$id_userconnecter = $_SESSION['user_id'];

$db = new Database();
$conn = $db->getConnection();


$stmt = $conn->prepare("
    SELECT v.*, u.nom
    FROM visite_guidee v
    JOIN Utilisateur u ON u.id_utilisateure = v.id_guide
    WHERE v.id_visiteguide = :id
");
$stmt->execute(['id' => $id_visite]);
$visite = $stmt->fetch();

if (!$visite) {
    die("Visite introuvable");
}


$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nb = (int)$_POST['nb_personnes'];
        $reservation = new Reservation($id_visite, $id_userconnecter, $nb);
        $reservation->save($conn);
        $success = "Réservation confirmée avec succès";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}


$etapeModel = new EtapeVisite();
$etapes = $etapeModel->getEtapesByVisite($conn, $id_visite);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - Zoo ASSAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">

<nav class="bg-white shadow-lg">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <span class="text-3xl">🦁</span>
                <a href="fiche_speciale.php" class="text-xl font-bold text-gray-800">Zoo ASSAD</a>
            </div>
            <div class="flex space-x-6">
                <a href="home.php" class="text-gray-600 hover:text-blue-600">Accueil</a>
                <a href="animals.php" class="text-gray-600 hover:text-blue-600">Animaux</a>
                <a href="visites.php" class="text-gray-600 hover:text-blue-600">Visites</a>
                <a href="../auth/login.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Connexion</a>
            </div>
        </div>
    </div>
</nav>

<main class="container mx-auto px-4 py-8">
<div class="max-w-4xl mx-auto">

<?php if ($error): ?>
<div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $success ?></div>
<?php endif; ?>

<div class="text-center mb-8">
    <h1 class="text-3xl font-bold mb-4">Réservation de visite guidée</h1>
    <p class="text-gray-600">Complétez les informations ci-dessous pour réserver votre place</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<div class="lg:col-span-2">

<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
<h2 class="text-2xl font-bold mb-4">Détails de la visite</h2>

<div class="space-y-4">
<div class="flex"><div class="w-32 font-semibold">Visite:</div><div class="font-bold"><?= $visite['titre'] ?></div></div>
<div class="flex"><div class="w-32 font-semibold">Date:</div><?= $visite['date_heure'] ?></div>
<div class="flex"><div class="w-32 font-semibold">Durée:</div><?= $visite['duree'] ?> heures</div>
<div class="flex"><div class="w-32 font-semibold">Guide:</div><?= $visite['nom'] ?></div>
<div class="flex"><div class="w-32 font-semibold">Langue:</div><?= $visite['langue'] ?></div>
<div class="flex"><div class="w-32 font-semibold">Places:</div>
<span class="text-green-600 font-semibold"><?= $visite['capaciter_max'] ?> places</span>
</div>
</div>
</div>

<div class="bg-white rounded-xl shadow-lg p-6">
<h2 class="text-2xl font-bold mb-6">Informations de réservation</h2>

<form method="POST">
<input type="number" name="nb_personnes" min="1"
max="<?= $visite['capaciter_max'] ?>"
class="w-20 text-center border px-3 py-2 rounded-lg" required>

<button type="submit"
class="mt-6 w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700">
<i class="fas fa-check-circle mr-2"></i>Confirmer la réservation
</button>
</form>
</div>
</div>

<div class="bg-blue-50 rounded-xl shadow-lg p-6 h-44">
<h2 class="text-2xl font-bold mb-6">Récapitulatif</h2>
<div class="flex justify-between"><span>Visite:</span><b><?= $visite['titre'] ?></b></div>
<div class="flex justify-between"><span>Prix:</span><b><?= $visite['prix'] ?> MAD</b></div>
</div>

</div>
</div>

<div class="container mx-auto px-4 py-8">
<h1 class="text-3xl font-bold text-center mb-6 text-blue-500">Étapes de la visite guidée</h1>

<?php if (!empty($etapes)): ?>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
<?php foreach ($etapes as $e): ?>
<div class="bg-white shadow-lg rounded-xl p-6">
<h2 class="font-bold"><?= $e['titre_etape'] ?></h2>
<p><?= $e['description_etape'] ?></p>
<span class="text-sm bg-blue-100 px-2 rounded">Ordre <?= $e['ordre_etape'] ?></span>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

</div>
</main>

<div class="fixed bottom-6 right-6 z-50">
    <a href="commentaire.php?id=<?= $id_visite ?>&id_u=<?= $id_userconnecter  ?>">
        <button
            class="w-14 h-14 rounded-full bg-blue-600 hover:bg-blue-700
                   flex items-center justify-center shadow-lg
                   text-white transition duration-300">
            <i class="fas fa-comment-alt text-xl"></i>
        </button>
    </a>
</div>


<footer class="bg-gray-800 text-white py-8 text-center">
Zoo Virtuel ASSAD - Réservation sécurisée
</footer>

</body>
</html>

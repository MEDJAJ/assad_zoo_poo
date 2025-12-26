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

$id = $_SESSION["user_id"];
$user_name_connected = $_SESSION["role"];

$visiteObj = new Visite();

$result = $visiteObj->getVisitesByGuide($conn,$id);
$reservations_result = $visiteObj->getReservationsByGuide($conn,$id);
$result_count = $visiteObj->countVisitesActives($conn,$id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Guide - Zoo ASSAD</title>
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
        <div class="flex space-x-6">
            <a href="guide_dashboard.php" class="text-blue-600 font-semibold">Dashboard</a>
            <a href="create_visite.php">Créer visite</a>
            <a href="../visitor/visites.php">Visites publiques</a>
            <span><?= $user_name_connected ?></span>
        </div>
    </div>
</nav>

<main class="container mx-auto px-4 py-8">

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-gray-500">Visites actives</p>
        <p class="text-3xl font-bold"><?= $result_count ?></p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-gray-500">Réservations</p>
        <p class="text-3xl font-bold"><?= $reservations_result->fetchColumn() ?></p>
    </div>
</div>

<div class="bg-white rounded-xl shadow p-6 mb-8">
    <h2 class="text-xl font-bold mb-4">Mes visites à venir</h2>

    <table class="w-full">
        <thead class="bg-gray-100">
        <tr>
            <th class="p-4">Titre</th>
            <th class="p-4">Date</th>
            <th class="p-4">Capacité</th>
            <th class="p-4">Langue</th>
            <th class="p-4">Statut</th>
            <th class="p-4">Actions</th>
        </tr>
        </thead>
        <tbody>

       <?php foreach ($result as $row) { ?>
<tr class="border-t">
    <td class="p-4"><?= $row['titre'] ?></td>
    <td class="p-4"><?= $row['date_heure'] ?></td>
    <td class="p-4"><?= $row['capaciter_max'] ?></td>
    <td class="p-4"><?= $row['langue'] ?></td>
    <td class="p-4"><?= $row['status_visiteguide'] ?></td>
    <td class="p-4">
         <a href="etapes_visite.php?id_visite=<?=$row['id_visiteguide']?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                    
                                    <i class="fas fa-list mr-1"></i>Étapes
                                </a>
        <a href="modifier_visite.php?id=<?= $row['id_visiteguide'] ?>" class="text-green-600 mr-3">Modifier</a>
        <a href="supprimer_visite.php?id_visite=<?= $row['id_visiteguide'] ?>" class="text-red-600">Annuler</a>
    </td>
</tr>
<?php } ?>



        </tbody>
    </table>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold mb-4">Réservations</h2>

<?php foreach ($reservations_result as $row) { ?>
<div class="flex justify-between border p-4 rounded mb-2">
    <div>
        <p class="font-semibold"><?= $row['nom'] ?></p>
        <p class="text-sm"><?= $row['titre'] ?> - <?= $row['nb_personnes'] ?> personnes</p>
    </div>
    <div>
        <p class="text-sm">Réservé le <?= $row['date_reservation'] ?></p>
    </div>
</div>
<?php } ?>

</div>

</main>
</body>
</html>

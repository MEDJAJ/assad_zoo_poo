<?php
session_start();

if (file_exists('../../../includes/config.php')) {
    require_once '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}

require_once '../../../includes/functions.php';
require_once '../../../includes/classes/utilisateure.php';

$db = new Database();
$conn = $db->getConnection();

$role   = $_GET['role']   ?? null;
$status = $_GET['status'] ?? null;

$users = Utilisateur::getAllUsers($conn, $role, $status);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Utilisateurs - Zoo ASSAD</title>
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
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="users_admin.php" class="flex items-center space-x-3 p-3 bg-blue-700 rounded-lg">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="animals_admin.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                    <i class="fas fa-paw"></i>
                    <span>Animaux</span>
                </a>
                <a href="habitats_admin.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                    <i class="fas fa-tree"></i>
                    <span>Habitats</span>
                </a>
                            <a href="stats.php" class="flex items-center p-3 hover:bg-gray-800 rounded-lg"><i class="fas fa-chart-bar mr-3"></i>Statistiques</a>
            </nav>
        </div>
    </div>

  
    <div class="flex-1 p-8">

        <h1 class="text-3xl font-bold mb-6">Gestion des Utilisateurs</h1>

       
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <form method="GET" class="flex space-x-4">
                <select name="role" class="px-4 py-2 border rounded-lg">
                    <option value="">Tous les rôles</option>
                    <option value="visitor">Visiteur</option>
                    <option value="guide">Guide</option>
                </select>

                <select name="status" class="px-4 py-2 border rounded-lg">
                    <option value="">Tous les statuts</option>
                    <option value="1">Actif</option>
                    <option value="0">Inactif</option>
                </select>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg">
                    Filtrer
                </button>
            </form>
        </div>


        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left">Nom</th>
                        <th class="p-4 text-left">Email</th>
                        <th class="p-4 text-left">Rôle</th>
                        <th class="p-4 text-left">Statut</th>
                        <th class="p-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($users as $row): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-4"><?= htmlspecialchars($row['nom']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($row['email']) ?></td>
                        <td class="p-4"><?= $row['role'] ?></td>
                        <td class="p-4">
                            <?= $row['status_utilisateure'] == 1 ? 'Actif' : 'Inactif' ?>
                        </td>
                        <td class="p-4">
                           <?php
                           if($row['role']=='visitor'){
                            if($row['status_utilisateure']==1){
                                ?>
                                 <a href="toggle_status.php?id=<?= $row['id_utilisateure'] ?>&status=0">
                                <button class="px-4 py-2 bg-green-600 text-white rounded">
                                    Acttive
                                </button>
                            </a>
                          <?php  }else{ ?>
                            <a href="toggle_status.php?id=<?= $row['id_utilisateure'] ?>&status=1">
                                <button class="px-4 py-2 bg-red-600 text-white rounded">
                                    Désactive
                                </button>
                            </a>
                            <?php
                          }

                           }else{
                            
                             if($row['status_utilisateure']==1){
                                ?>
                                 <a href="toggle_status.php?id=<?= $row['id_utilisateure'] ?>&status=0">
                                <button class="px-4 py-2 bg-green-600 text-white rounded">
                                    Approve
                                </button>
                            </a>
                          <?php  }else{ ?>
                            <a href="toggle_status.php?id=<?= $row['id_utilisateure'] ?>&status=1">
                                <button class="px-4 py-2 bg-red-600 text-white rounded">
                                    Désapprove
                                </button>
                            </a>
                            <?php
                           } }
                           ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

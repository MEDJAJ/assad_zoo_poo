<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
}

include '../../../includes/classes/Animal.php';
include '../../../includes/classes/habitat.php';
include '../../../includes/classes/visite.php';
include '../../../includes/classes/utilisateure.php';
$db = new Database();
$conn = $db->getConnection();
$animal=new Animal();
$habitat=new Habitat();
$visite=new Visite();


$req_animaux = $animal->getAll($conn);
$req_visites = $visite->getVisite($conn);
$req_habitats = $habitat->getHabitats($conn);
       
$req_utilisateure=Utilisateur::getAllUsers($conn) ;



?>








<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Zoo ASSAD</title>
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
                    <a href="admin_dashboard.php" class="flex items-center space-x-3 p-3 bg-blue-700 rounded-lg">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="users_admin.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                        <i class="fas fa-users"></i>
                        <span>Utilisateurs</span>
                    </a>
                    <a href="animals_admin.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                        <i class="fas fa-paw"></i>
                        <span>Animaux</span>
                    </a>
                    <a href="habitats_admin.admin" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                        <i class="fas fa-tree"></i>
                        <span>Habitats</span>
                    </a>
                    <a href="stats.admin" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                        <i class="fas fa-chart-bar"></i>
                        <span>Statistiques</span>
                    </a>
                </nav>
            </div>
        </div>

     
        <div class="flex-1 p-8 ">
      
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Dashboard Admin</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Admin</span>
                  <a href="../auth/logout.php">
    <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
    </button>
</a>
                </div>
            </div>

       
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-32">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Total Utilisateurs</p>
                            <p class="text-3xl font-bold"><?= count($req_utilisateure) ?></p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Total Animaux</p>
                            <p class="text-3xl font-bold"><?= count($req_animaux) ?></p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-paw text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Total Visites</p>
                            <p class="text-3xl font-bold"><?= count($req_visites) ?></p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-calendar text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Total Réservations</p>
                            <p class="text-3xl font-bold"><?= count($req_habitats) ?></p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-ticket-alt text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

           
          

                
            </div>
        </div>
    </div>
</body>
</html>
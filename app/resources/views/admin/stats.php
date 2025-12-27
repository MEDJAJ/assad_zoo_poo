
<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
}



include '../../../includes/classes/Animal.php';
include '../../../includes/classes/utilisateure.php';
include '../../../includes/classes/reservation.php';
include '../../../includes/classes/guide.php';
include '../../../includes/classes/visitor.php';
include '../../../includes/classes/commentaire.php';
$db = new Database();
$conn = $db->getConnection();

$animal=new Animal();
$reservation=new Reservation();

$countguide=Guide::getCountGuide($conn);
$countvisitor=Visitor::getCountVisitor($conn);
$req_animaux = $animal->getAll($conn);
$count=$reservation->getCountReservation($conn);
$req_utilisateure=Utilisateur::getAllUsers($conn) ;



$topvisite=$reservation->prendeTopVisiteReservation($conn);



if(!$topvisite){
    die("Error de la récupération de donner");
}

?>





<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Zoo ASSAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <a href="users_admin.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
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
                    <a href="stats.php" class="flex items-center space-x-3 p-3 bg-blue-700 rounded-lg">
                        <i class="fas fa-chart-bar"></i>
                        <span>Statistiques</span>
                    </a>
                </nav>
            </div>
        </div>

      
        <div class="flex-1 p-8">
          
            <div class="mb-8">
                <h1 class="text-3xl font-bold mb-2">Statistiques</h1>
                <p class="text-gray-600">Analyse des données du zoo virtuel</p>
            </div>

         
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Utilisateurs Total</p>
                            <p class="text-3xl font-bold"><?= count($req_utilisateure) ?></p>
                        </div>
                        <i class="fas fa-users text-3xl text-blue-500"></i>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Animaux Total</p>
                            <p class="text-3xl font-bold"><?= count($req_animaux) ?></p>
                        </div>
                        <i class="fas fa-paw text-3xl text-green-500"></i>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Réservations</p>
                            <p class="text-3xl font-bold"><?= $count ?></p>
                        </div>
                        <i class="fas fa-ticket-alt text-3xl text-purple-500"></i>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Visiteurs Totale</p>
                            <p class="text-3xl font-bold"><?= $countvisitor ?></p>
                        </div>
                        <i class="fas fa-money-bill-wave text-3xl text-yellow-500"></i>
                    </div>
                </div>


                   <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Guides Totale</p>
                            <p class="text-3xl font-bold"><?= $countguide ?></p>
                        </div>
                        <i class="fas fa-money-bill-wave text-3xl text-yellow-500"></i>
                    </div>
                </div>
            </div>

        
          

           
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-6">Top des visite les plus réservées</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-4 text-left">Visite</th>
                                <th class="p-4 text-left">Guide</th>
                                <th class="p-4 text-left">Réservations</th>
                                <th class="p-4 text-left">Note moyenne</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        if(count($topvisite)>0){
                            $row_max=Commentaire::MaxNoteParVisite($conn,$topvisite['id_visite']);
                           
                            ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-4 text-yellow-500 font-bold"><?= $topvisite['titre'] ?></td>
                                <td class="p-4 text-yellow-500 font-bold"><?= $topvisite['nom_guide'] ?></td>
                                <td class="p-4 text-yellow-500 font-bold"><?= $topvisite['total_reservations'] ?></td>
                                <td class="p-4">
                                    <span class="text-yellow-500 font-bold"> <?= $row_max['note_moyenne'] ?></span>
                                </td>

                            </tr>
                            <?php
                             } ?>
                         
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

  
</body>
</html>
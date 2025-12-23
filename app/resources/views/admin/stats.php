
<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
}

$requéte_sql_u="SELECT * FROM utilisateur";
$requéte_sql_a="SELECT * FROM animaux";
$requéte_sql_visiteurs="SELECT * FROM utilisateur  WHERE role='visitor'";
$requéte_sql_r="SELECT * FROM reservation";
$requéte_sql_g="SELECT * FROM utilisateur  WHERE role='guide'";

$result__sql_u=mysqli_query($con,$requéte_sql_u);
$result__sql_a=mysqli_query($con,$requéte_sql_a);
$result__sql_v=mysqli_query($con,$requéte_sql_visiteurs);
$result__sql_r=mysqli_query($con,$requéte_sql_r);
$result__sql_g=mysqli_query($con,$requéte_sql_g);

if(!$result__sql_u){
    die("Error de la récuperation utilsateures");
}
if(!$result__sql_a){
    die("Error de la récuperation animaux");
}
if(!$result__sql_v){
    die("Error de la récuperation visiteurs");
}
if(!$result__sql_r){
    die("Error de la récuperation reservation");
}
if(!$result__sql_g){
    die("Error de la récuperation de toutes les quides de app");
}

$requéte_prende_top_reservation="SELECT 
    v.titre,
    g.nom AS nom_guide,
    v.id_visiteguide AS id_visite,
    COUNT(r.id_reservation) AS total_reservations
FROM visite_guidee v
INNER JOIN utilisateur g ON v.id_guide = g.id_utilisateure
LEFT JOIN reservation r ON v.id_visiteguide = r.id_visiteguide
GROUP BY v.id_visiteguide, v.titre, g.nom
ORDER BY total_reservations DESC
LIMIT 1";

$result_prende_top_reservation=mysqli_query($con,$requéte_prende_top_reservation);
if(!$result_prende_top_reservation){
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
                            <p class="text-3xl font-bold"><?= mysqli_num_rows($result__sql_u) ?></p>
                        </div>
                        <i class="fas fa-users text-3xl text-blue-500"></i>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Animaux Total</p>
                            <p class="text-3xl font-bold"><?= mysqli_num_rows($result__sql_a) ?></p>
                        </div>
                        <i class="fas fa-paw text-3xl text-green-500"></i>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Réservations</p>
                            <p class="text-3xl font-bold"><?= mysqli_num_rows($result__sql_r) ?></p>
                        </div>
                        <i class="fas fa-ticket-alt text-3xl text-purple-500"></i>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Visiteurs Totale</p>
                            <p class="text-3xl font-bold"><?= mysqli_num_rows($result__sql_v) ?></p>
                        </div>
                        <i class="fas fa-money-bill-wave text-3xl text-yellow-500"></i>
                    </div>
                </div>


                   <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Guides Totale</p>
                            <p class="text-3xl font-bold"><?= mysqli_num_rows($result__sql_g) ?></p>
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
                        if(mysqli_num_rows($result_prende_top_reservation)>0){
                            $row=mysqli_fetch_assoc($result_prende_top_reservation);
                            $query_note="SELECT MAX(c.note) AS note_moyenne FROM visite_guidee v INNER JOIN commentaire c ON c.id_visiteguide=v.id_visiteguide WHERE v.id_visiteguide=".$row['id_visite'];
                            $result_max_note=mysqli_query($con,$query_note);
                            if(!$result_max_note){
                                die("Error de la récupération de max note");
                            }
                            $row_max=mysqli_fetch_assoc($result_max_note);
                            ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-4 text-yellow-500 font-bold"><?= $row['titre'] ?></td>
                                <td class="p-4 text-yellow-500 font-bold"><?= $row['nom_guide'] ?></td>
                                <td class="p-4 text-yellow-500 font-bold"><?= $row['total_reservations'] ?></td>
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
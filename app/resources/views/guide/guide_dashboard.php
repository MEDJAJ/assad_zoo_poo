<?php
session_start();
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}
include '../../../includes/functions.php';
$id= $_SESSION["user_connecte"];
$user_name_connected=$_SESSION["name_user_connecte"];
$sql="SELECT * FROM visite_guidee WHERE id_guide=$id ";

$result=mysqli_query($con,$sql);



$reservations="SELECT * FROM Utilisateur u INNER JOIN reservation r ON r.id_utilisateure=u.id_utilisateure
INNER JOIN visite_guidee v ON r.id_visiteguide=v.id_visiteguide WHERE id_guide='$id'
";
$reservations_result=mysqli_query($con,$reservations);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guide - Zoo ASSAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-3xl">🦁</span>
                    <span class="text-xl font-bold">Zoo ASSAD</span>
                </div>
                <div class="flex space-x-6">
                    <a href="guide_dashboard.php" class="text-blue-600 font-semibold">Dashboard</a>
                    <a href="create_visite.php" class="text-gray-600 hover:text-blue-600">Créer visite</a>
                    <a href="../visitor/visites.php" class="text-gray-600 hover:text-blue-600">Visites publiques</a>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-user-circle text-gray-400"></i>
                        <span class="text-gray-700"><?= $user_name_connected ?></span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

   
    <main class="container mx-auto px-4 py-8">
       
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">Tableau de bord - Guide</h1>
            <p class="text-gray-600">Gérez vos visites et réservations</p>
        </div>

    
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <?php
                            $sql_count = "SELECT * FROM visite_guidee v
             WHERE v.status_visiteguide != 'Complet'
       AND v.date_heure >= NOW() AND id_guide='$id'
";
$result_count=mysqli_query($con,$sql_count);
if(!$result_count){
die("Error de la récupération de nombre de visite active");
}

                        ?>
                        <p class="text-gray-500">Visites actives</p>
                        <p class="text-3xl font-bold"><?=  mysqli_num_rows($result_count) ?? 0  ?></p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-map-marked-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Réservations </p>
                        <p class="text-3xl font-bold"><?= mysqli_num_rows($reservations_result)?></p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-ticket-alt text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

           
        </div>

     
        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Actions rapides</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="create_visite.php" class="bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 text-center">
                    <i class="fas fa-plus text-2xl mb-2 block"></i>
                    <span>Nouvelle visite</span>
                </a>
                <a href="#visites" class="bg-green-600 text-white p-4 rounded-lg hover:bg-green-700 text-center">
                    <i class="fas fa-list-ol text-2xl mb-2 block"></i>
                    <span>Voir Visites</span>
                </a>
                <a href="#reservations" class="bg-purple-600 text-white p-4 rounded-lg hover:bg-purple-700 text-center">
                    <i class="fas fa-users text-2xl mb-2 block"></i>
                    <span>Voir réservations</span>
                </a>
            </div>
        </div>


        <div class="bg-white rounded-xl shadow p-6 mb-8" id="visites">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Mes visites à venir</h2>
              
            </div>
            
            <div class="overflow-x-auto"  >
                <table class="w-full" >
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-4 text-left">Titre</th>
                            <th class="p-4 text-left">Date</th>
                            <th class="p-4 text-left">Capacité</th>
                              <th class="p-4 text-left">Longue</th>
                            <th class="p-4 text-left">Statut</th>
                            <th class="p-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(mysqli_num_rows($result)>0){

                       while($row=mysqli_fetch_assoc($result)){

            
                        ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-4"><?= $row["titre"] ?></td>
                            <td class="p-4"><?= $row["date_heure"] ?></td>
                            <td class="p-4"><?= $row["capaciter_max"] ?></td>
                      <td class="p-4"><?= $row["langue"] ?></td>
                            <td class="p-4">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                    <?= $row["status_visiteguide"] ?>
                                </span>
                            </td>
                            <td class="p-4">
                                <a href="etapes_visite.php?id_visite=<?=$row['id_visiteguide']?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                    
                                    <i class="fas fa-list mr-1"></i>Étapes
                                </a>

                                 <a href="modifier_visite.php?id=<?= $row["id_visiteguide"]  ?>" class="text-green-600 hover:text-green-800 mr-4">
                                   </i>Modifier
                                </a>

                                <a href="supprimer_visite.php?id_visite=<?= $row["id_visiteguide"]  ?>" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-times mr-1"></i>Annuler
                                </a>
                            </td>
                        </tr>

                        <?php  }            }?>
                      
                    </tbody>
                </table>
            </div>
        </div>

   
        <div id="reservations" class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold"> réservations</h2>
            </div>
            
            <div class="space-y-4">
<?php

if ($reservations_result && mysqli_num_rows($reservations_result) > 0) {
    while ($row = mysqli_fetch_assoc($reservations_result)) {
        

?>
                <div class="flex items-center justify-between p-4 border rounded-lg">

                    <div>
                        <p class="font-semibold"><?= $row['nom'] ?></p>
                        <p class="text-gray-600 text-sm"><?= $row['titre']?> - <?= $row['nb_personnes'] ?> personnes</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Réservé le: <?= $row['date_reservation']?></p>
                    </div>
                </div>
                <?php
                 }
                 } else {
    echo "Aucune réservation trouvée";
}   ?>

          

            </div>
        </div>
    </main>
</body>
</html>
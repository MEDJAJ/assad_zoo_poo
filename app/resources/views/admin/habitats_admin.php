
<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
}
include '../../../includes/functions.php';

$message="";
$etat="";

if (isset($_GET["success"])) {
    $etat = "success";
    $message = "Habitat créé avec succès";
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    
$nom=trim($_POST["nom"]);
$type=trim($_POST["typeclimat"]);
$description=trim($_POST["description"]);
$zone=trim($_POST["zone"]);
if(!validation($nom, "/^[a-zA-ZÀ-ÿ\s]{2,50}$/") || !validation($type, "/^[a-zA-ZÀ-ÿ\s]{2,50}$/") ||
!validation($description, "/^[a-zA-ZÀ-ÿ\s]{2,50}$/") ||
!validation($zone, "/^[a-zA-ZÀ-ÿ\s]{2,50}$/") ){
   $etat = "error";
   $message = "Tous les champs doivent être valides";
}else{

        $nom = mysqli_real_escape_string($con, $nom);
          $type = mysqli_real_escape_string($con, $type);
            $description = mysqli_real_escape_string($con, $description);
              $zone = mysqli_real_escape_string($con, $zone);

$sql="INSERT INTO habitats(nom,typeclimat,description,zonezoo)
VALUES ('$nom','$type','$description','$zone')
";

if(mysqli_query($con,$sql)){
            header("Location: habitats_admin.php?success=1");
            exit;
      
        } else {
            $etat = "error";
            $message = "Erreur d'insertion : " . mysqli_error($con);
        }
}

}

$sql_select="SELECT * FROM habitats";
$result=mysqli_query($con,$sql_select);



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Habitats - Zoo ASSAD</title>
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
                    <a href="users_admin.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                        <i class="fas fa-users"></i>
                        <span>Utilisateurs</span>
                    </a>
                    <a href="animals_admin.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                        <i class="fas fa-paw"></i>
                        <span>Animaux</span>
                    </a>
                    <a href="habitats_admin.php" class="flex items-center space-x-3 p-3 bg-blue-700 rounded-lg">
                        <i class="fas fa-tree"></i>
                        <span>Habitats</span>
                    </a>
                    <a href="stats.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                        <i class="fas fa-chart-bar"></i>
                        <span>Statistiques</span>
                    </a>
                </nav>
            </div>
        </div>

      
        <div class="flex-1 p-8">
       
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Gestion des Habitats</h1>

            </div>

      
            <div class="bg-white rounded-xl shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Ajouter un habitat</h2>
                <?php
                 if($etat==="error"){

echo   "<div class='text-center mt-4 mb-4 text-red-500 border border-black  pb-2 bg-red-100'>";
       echo "<p class='text-red-600 mt-2 font-bold'>$message</p>";
   echo "</div>";
           }elseif($etat==="success"){
        
echo   "<div class='text-center mt-4 mb-4 text-red-500 border border-black  pb-2 bg-green-100'>";
     echo "<p class='text-green-600 mt-2 font-bold'>$message</p>";
   echo "</div>";
           }
            
          
            ?>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST">
                    <input type="text" placeholder="Nom de l'habitat" class="p-3 border rounded-lg" name="nom">
                    <input type="text" placeholder="Type de climat" class="p-3 border rounded-lg" name="typeclimat">
                    <textarea placeholder="Description" rows="3" class="p-3 border rounded-lg md:col-span-2" name="description"></textarea>
                    <input type="text" placeholder="Zone du zoo" class="p-3 border rounded-lg" name="zone">
                    <div class="md:col-span-2 space-x-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Enregistrer
                        </button>
                        <button type="reset" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>

         <?php
             if(mysqli_num_rows($result)>0){
                    ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          

                    <?php   while($row=mysqli_fetch_assoc($result)){

                  ?>
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold"><?= $row["nom"] ?></h3>
                        <div class="flex space-x-2">
                           <a href="modifier_habitat.php?id=<?= $row['id_habitat'] ?> ">
                             <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                           </a>
                          
                            <a href="supprimer_habitat.php?id=<?= $row['id_habitat'] ?>">
                                <button class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                            </a>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p><strong>Climat:</strong> <?= $row["typeclimat"] ?></p>
                        <p><strong>Zone:</strong> <?= $row["description"] ?></p>
                        <p><strong>Animaux:</strong> <?= $row["zonezoo"] ?></p>
                    </div>
                </div>
<?php      }   ?>
                
            </div>
            <?php }?>
        </div>
    </div>
</body>
</html>
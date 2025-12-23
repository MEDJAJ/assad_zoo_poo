
<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}
$message_animal="";
$message_visite="";
$animals_select="SELECT * FROM animaux";
$visite_select="SELECT * FROM visite_guidee WHERE status_visiteguide='Disponible' OR status_visiteguide='Limité'";



$animals_result = mysqli_query($con, $animals_select);
if(!$animals_result){
    $message_animal="Erreur lors de la récupération des animaux : " . mysqli_error($con);
    exit;
}


$visite_result = mysqli_query($con, $visite_select);
if(!$visite_result){
    $message_visite="Erreur lors de la récupération des habitats : " . mysqli_error($con);
    exit;
}


?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Zoo ASSAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .animal-card {
            transition: transform 0.3s ease;
        }
        .animal-card:hover {
            transform: translateY(-5px);
        }
    </style>
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
                    <a href="home.php" class="text-blue-600 font-semibold">Accueil</a>
                    <a href="animals.php" class="text-gray-600 hover:text-blue-600">Animaux</a>
                    <a href="visites.php" class="text-gray-600 hover:text-blue-600">Visites</a>
                    <a href="../auth/login.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Connexion</a>
                </div>
            </div>
        </div>
    </nav>

    
    <header class="hero-gradient text-white">
        <div class="container mx-auto px-4 py-16">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-4">Découvrez le Zoo Virtuel ASSAD</h1>
                <p class="text-xl mb-8">Explorez la faune africaine et les lions de l'Atlas</p>
                
       
               
            </div>
        </div>
    </header>

  
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">Animaux en vedette</h2>
            <?php    if(mysqli_num_rows($animals_result)>0){
               
             ?>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
               <?php    while($row_animal=mysqli_fetch_assoc($animals_result)){
                    
                 ?>
                <div class="animal-card bg-white rounded-xl shadow-md overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?auto=format&fit=crop&w=500" 
                         alt="Lion" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2"><strong>Nom :</strong> <?=   $row_animal["nom"]   ?></h3>
                        <p class="text-gray-600 mb-2"><strong >Alimentation  : </strong><?=   $row_animal["alimentation"]   ?></p>
                        <p class="text-gray-600"><strong>Espece :</strong> <?=   $row_animal["espece"]   ?> </p>
                        <p class="text-gray-600"><strong>Pays :</strong> <?=   $row_animal["pays_origine"]   ?> </p>
                         <p class="text-gray-600"> <strong>Description :</strong> <?=   $row_animal["description"]   ?> </p>
                       
                    </div>
                </div>

                     <?php   } ?>


            

          
        </div>   
    <?php 

}?>
    </div>
    </section>

 
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">Visites guidées disponibles</h2>
            <?php
 if(mysqli_num_rows($visite_result)>0){

 
            ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
  while($row_habitat=mysqli_fetch_assoc($visite_result)){


        ?>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-bold text-xl mb-3"><?php $row_habitat["titre"]?></h3>
                    <div class="space-y-2 mb-4">
                        <p><i class="fas fa-calendar text-blue-500 mr-2"></i><?= $row_habitat["date_heure"]?> </p>
                        <p><i class="fas fa-clock text-green-500 mr-2"></i> <?= $row_habitat["duree"]?></p>
                        <p><i class="fas fa-tag text-yellow-500 mr-2"></i> <?= $row_habitat["prix"]?> MAD</p>
                        <p><i class="fas fa-language text-purple-500 mr-2"></i><?= $row_habitat["langue"]?></p>
                        <p><i class="fas fa-users text-red-500 mr-2"></i><?= $row_habitat["capaciter_max"]?></p>
                    </div>
                    <a href="reservation.php?id=<?= $row_habitat["id_visiteguide"]?>" class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700">
                        Réserver
                    </a>
                </div>

               
             

            <?php }  ?>
          

           
        </div>
         <?php }  ?>
    </section>

    
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <p>&copy; 2024 Zoo Virtuel ASSAD. Tous droits réservés.</p>
                <p class="mt-2">Coupe d'Afrique des Nations 2025 - Maroc</p>
            </div>
        </div>
    </footer>
</body>
</html>
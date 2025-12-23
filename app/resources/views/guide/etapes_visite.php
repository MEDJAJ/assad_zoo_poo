
<?php
session_start();
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}
include '../../../includes/functions.php';
$message="";
$etat="";
 $id_visite = (int) $_GET['id_visite'];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $regexTitreEtape = '/^[a-zA-ZÀ-ÿ0-9\s]{3,100}$/';
$regexDescriptionEtape = '/^[a-zA-ZÀ-ÿ0-9\s.,;:!?\'"()\-\n]{0,500}$/'; 
$regexOrdreEtape = '/^\d+$/';

  $titreetape = mysqli_real_escape_string($con, $_POST['titreetape']);
    $descriptionetape = mysqli_real_escape_string($con, $_POST['descriptionetape']);
    $ordreetape = (int)$_POST['ordreetape'];
    $id_visite = (int) $_GET['id_visite'];
    if(!validation($titreetape,$regexTitreEtape) || !validation($descriptionetape,$regexDescriptionEtape) || !validation($ordreetape,$regexOrdreEtape)){
        $message="toutes les champs doit etre valid";
        $etat="error";
    }else{
        $sql="INSERT INTO etapevisite(titre_etape,description_etape,ordre_etape,id_visite)
        VALUES('$titreetape','$descriptionetape','$ordreetape','$id_visite')
        ";
        if(mysqli_query($con,$sql)){
             $message="Cette Etape visite ajouter avec sucess";
        $etat="success";
        }
    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Ajouter des étapes pour une visite guidée</h1>
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
    <form method="POST" class="bg-white p-6 rounded-xl shadow space-y-6">
    
        <div>
            <label class="block font-medium mb-2">Titre de l'étape *</label>
            <input type="text" name="titreetape" required 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                placeholder="Ex: Zone mammifères asiatiques">
        </div>

      
        <div>
            <label class="block font-medium mb-2">Description de l'étape</label>
            <textarea name="descriptionetape" rows="4"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                placeholder="Décrivez cette étape..."></textarea>
        </div>

    
        <div>
            <label class="block font-medium mb-2">Ordre de l'étape *</label>
            <input type="number" name="ordreetape" required min="1"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                placeholder="Ex: 1">
        </div>

      

        
        <div class="flex space-x-4 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                Ajouter l'étape
            </button>
            <a href="#" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400 font-semibold">
                Annuler
            </a>
        </div>
    </form>
</div>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center text-blue-500">Étapes de la visite guidée</h1>
<?php 
$etapes="SELECT * FROM etapevisite WHERE id_visite=$id_visite";
$result_etapes=mysqli_query($con,$etapes);
if(mysqli_num_rows($result_etapes)>0){


?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
       <?php while($row=mysqli_fetch_assoc($result_etapes)){

       ?>
        <div class="bg-white shadow-lg rounded-xl p-6 hover:shadow-2xl transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800"><?= $row['titre_etape']?></h2>titre_etape
               
            </div>
            <p class="text-gray-600">
                <?= $row['description_etape']?>
            </p>
             <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-2 py-1 rounded-full">
                    Ordre <?= $row['ordre_etape']?>
                </span>
        </div>
<?php }  ?>
</div>
<?php }  ?>
</div>



</body>
</html>
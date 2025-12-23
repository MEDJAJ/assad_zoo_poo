
<?php

if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}
include '../../../includes/functions.php';

$id_visite=isset($_GET['id']) ? intval($_GET['id']) :0;
$id_utilisateure=isset($_GET['id_utilisateure']) ? intval($_GET['id_utilisateure']) :0;
if($id_visite==0){
    die("cette id not existe");
}
if($id_utilisateure==0){
        die("cette id utilisateure not existe");
}

$sql="SELECT * FROM Utilisateur u INNER JOIN visite_guidee v ON v.id_guide=u.id_utilisateure WHERE id_visiteguide=$id_visite";
$result=mysqli_query($con,$sql);
if(!$result){
    die("Error de la récupération de donner");
}




if($_SERVER["REQUEST_METHOD"]=="POST"){
$regex_titre = "/^[a-zA-ZÀ-ÿ0-9\s'’.,!?-]{3,100}$/";
$regex_commentaire = "/^[a-zA-ZÀ-ÿ0-9\s'’.,!?():;-]{10,1000}$/";
$note=$_POST['note'];
$titre=trim($_POST['titre']);
$commentaire=trim($_POST['commentaire']);
if(!validation($titre,$regex_titre) || !validation($commentaire,$regex_commentaire)){
    die("toutes les champs doit etre valide");
}
$sql_commentaire="INSERT INTO commentaire(note,content,id_visiteguide,id_utilisateure,titre)
VALUES('$note','$commentaire','$id_visite','$id_utilisateure','$titre')
";
$result_commentaire=mysqli_query($con,$sql_commentaire);
if(!$result_commentaire){
    die("Error de la Insertition");
}

  header("Location: commentaire.php?id=$id_visite&id_utilisateure=$id_utilisateure&success=1");
    exit;
}


$sql_recupertion_commentaire="SELECT * FROM Utilisateur u INNER JOIN Commentaire c ON u.id_utilisateure=c.id_utilisateure WHERE id_visiteguide='$id_visite' ORDER BY c.date_commentaire DESC";
$result_recuperation_commentaire=mysqli_query($con,$sql_recupertion_commentaire);
if(!$result_recuperation_commentaire){
    die("Error de la récuperation de commentaire");
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaire - Zoo ASSAD</title>
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
                    <a href="login.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Connexion</a>
                </div>
            </div>
        </div>
    </nav>

  
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
          
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold mb-4">Laisser un commentaire</h1>
                <p class="text-gray-600">Partagez votre expérience sur la visite guidée</p>
            </div>
<?php
if(mysqli_num_rows($result)>0){
    $row_result=mysqli_fetch_assoc($result);

?>
         
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-bold mb-4">Visite concernée</h2>
                <div class="flex items-center">
                    <div class="w-24 font-semibold">Visite:</div>
                    <div class="text-lg"><?=$row_result["titre"] ?></div>
                </div>
                <div class="flex items-center mt-2">
                    <div class="w-24 font-semibold">Date:</div>
                    <div><?=$row_result["date_heure"] ?></div>
                </div>
                <div class="flex items-center mt-2">
                    <div class="w-24 font-semibold">Guide:</div>
                    <div><?=$row_result["nom"] ?></div>
                </div>
            </div>

          <?php } ?>
            <div class="bg-white rounded-xl shadow-lg p-6">
             <form method="POST" action="">
    
  
    <div class="mb-8">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Note (1 à 5)
        </label>
        <input 
            type="number"
            name="note"
            min="1"
            max="5"
            required
            class="w-32 mx-auto block px-4 py-2 border border-gray-300 rounded-lg text-center"
            placeholder="1 à 5"
        >
        <div class="flex justify-between text-sm text-gray-500 mt-2">
            <span>Mauvais</span>
            <span>Excellent</span>
        </div>
    </div>

   
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Titre du commentaire
        </label>
        <input 
            type="text"
            name="titre"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg"
            placeholder="Ex: Super expérience !"
        >
    </div>

   
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Votre commentaire *
        </label>
        <textarea 
            name="commentaire"
            rows="6"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg"
            placeholder="Décrivez votre expérience..."
        ></textarea>
    </div>

    <div class="text-center">
        <button 
            type="submit"
            class="bg-blue-600 text-white py-3 px-8 rounded-lg font-semibold hover:bg-blue-700 text-lg"
        >
            <i class="fas fa-paper-plane mr-2"></i>Publier le commentaire
        </button>
    </div>
</form>

            </div>

        
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-6">Commentaires des autres visiteurs</h2>
                
                <div class="space-y-6">
           
<?php       if(mysqli_num_rows($result_recuperation_commentaire)>0){
    while($row=mysqli_fetch_assoc($result_recuperation_commentaire)){

    

         ?>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-bold"><?= $row['titre'] ?> !</h3>
                                <div class="flex items-center text-yellow-500">
                                   
                                    <span class="ml-2 text-sm text-gray-600"><?= $row['date_commentaire'] ?></span>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">Par <?= $row['nom'] ?> </span>
                        </div>
                          <p class="text-gray-700 mt-2 mb-2">
                       Note :   <?=  $row['note'] ?> 
                        </p>
                        <p class="text-gray-700">
                         <?= $row['content'] ?> 
                        </p>
                    </div>

<?php          }          }                  ?>
                 
                   
                </div>
            </div>
        </div>
    </main>


    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p>Zoo Virtuel ASSAD - Partagez votre expérience</p>
        </div>
    </footer>
</body>
</html>
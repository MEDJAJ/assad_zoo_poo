

<?php

session_start();
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$id_u = $_SESSION["user_connecte"] ?? null;

if (!$id_u) {
    die("Utilisateur non connecté");
}


$sql = "SELECT * FROM Utilisateur WHERE id_utilisateure = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_u, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur introuvable");
}


if ($user['status_utilisateure'] == 1) {
    header("Location: ../guide/guide_dashboard.php");
    exit;
}
?>










<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte en attente - Zoo ASSAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center min-h-screen">

    <div class="bg-white rounded-2xl shadow-2xl p-10 max-w-lg text-center animate-fadeIn">
    
        <div class="mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-20 w-20 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
            </svg>
        </div>


        <h1 class="text-3xl font-bold text-gray-800 mb-4">Compte en attente d'approve</h1>

       
        <p class="text-gray-600 mb-6">
            Bonjour, votre compte Guide n’a pas encore été approuvé par l’administrateur. <br>
          
        </p>


        <a href="../guide/activation_compte.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition-colors">
           reload
        </a>
    </div>

</body>
</html>

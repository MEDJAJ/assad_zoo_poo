<?php

if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}


include '../../../includes/functions.php';


class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($nom, $email, $password, $role, $pays) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $status = ($role === 'guide') ? 0 : 1;

        $sql = "INSERT INTO Utilisateur (nom, email, role, mot_passe, status_utilisateure, paye)
                VALUES (:nom, :email, :role, :mot_passe, :status, :pays)";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':mot_passe', $passwordHash);
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->bindParam(':pays', $pays);

            if ($stmt->execute()) {
                return ["etat" => "success", "message" => "Compte créé avec succès"];
            } else {
                return ["etat" => "error", "message" => "Erreur lors de l'insertion"];
            }
        } catch (PDOException $e) {
            return ["etat" => "error", "message" => "Erreur PDO: " . $e->getMessage()];
        }
    }
}


$user = new User($con);

$message = "";
$etat = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $role = trim($_POST["role"]);
    $pays = trim($_POST["pays"]);

    if (
        !validation($nom, "/^[a-zA-ZÀ-ÿ\s]{2,50}$/") ||
        !validation($email, "/^[^\s@]+@[^\s@]+\.[^\s@]+$/") ||
        !validation($password, "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/") ||
        !validation($role, "/^(guide|visitor)$/") ||
        !validation($pays, "/^[a-zA-ZÀ-ÿ\s]{2,50}$/")
    ) {
        $etat = "error";
        $message = "Tous les champs doivent être valides";
    } else {
        $result = $user->register($nom, $email, $password, $role, $pays);
        $etat = $result["etat"];
        $message = $result["message"];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Zoo ASSAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); }
        .card-shadow { box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .input-focus:focus { box-shadow: 0 0 0 3px rgba(66,153,225,0.5); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="fixed inset-0 gradient-bg z-0"></div>
    <div class="relative z-10 w-full max-w-4xl flex">
       
        <div class="hidden md:flex md:w-1/2 bg-white rounded-l-2xl p-8 flex-col justify-center items-center">
            <div class="mb-8 text-center">
                <h1 class="text-5xl mb-2">🦁</h1>
                <h2 class="text-3xl font-bold text-gray-800">Zoo Virtuel ASSAD</h2>
                <p class="text-gray-600 mt-2">Découvrez les lions de l'Atlas</p>
            </div>
        </div>

 
        <div class="w-full md:w-1/2 bg-white card-shadow rounded-2xl md:rounded-r-2xl md:rounded-l-none p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Créer un compte</h1>
                <p class="text-gray-600 mt-2">Rejoignez le zoo virtuel ASSAD</p>
            </div>

       
            <?php if($etat==="error"): ?>
                <div class='text-center mt-4 mb-4 text-red-500 border border-black pb-2 bg-red-100'>
                    <p class='text-red-600 mt-2 font-bold'><?= htmlspecialchars($message) ?></p>
                </div>
            <?php elseif($etat==="success"): ?>
                <div class='text-center mt-4 mb-4 text-green-500 border border-black pb-2 bg-green-100'>
                    <p class='text-green-600 mt-2 font-bold'><?= htmlspecialchars($message) ?></p>
                </div>
            <?php endif; ?>

        
            <form method="POST" action="" class="space-y-6 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-user mr-2"></i>Nom complet *</label>
                    <input type="text" name="nom" required
                        value="<?= isset($_POST["nom"]) ? htmlspecialchars($_POST["nom"]) : '' ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-blue-500 pl-12"
                        placeholder="Votre nom et prénom">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-envelope mr-2"></i>Email *</label>
                    <input type="email" name="email" required
                        value="<?= isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : '' ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-blue-500 pl-12"
                        placeholder="votre@email.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-lock mr-2"></i>Mot de passe *</label>
                    <input type="password" name="password" required
                        value="<?= isset($_POST["password"]) ? htmlspecialchars($_POST["password"]) : '' ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-blue-500 pl-12"
                        placeholder="Créez un mot de passe sécurisé">
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères avec majuscule, minuscule et chiffre</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-user mr-2"></i>Pays *</label>
                    <input type="text" name="pays" required
                        value="<?= isset($_POST["pays"]) ? htmlspecialchars($_POST["pays"]) : '' ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-blue-500 pl-12"
                        placeholder="Votre pays">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-user-tag mr-2"></i>Rôle *</label>
                    <select name="role" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-blue-500 pl-12 appearance-none bg-white">
                        <option value="">Sélectionnez un rôle...</option>
                        <option value="visitor" <?= isset($_POST["role"]) && $_POST["role"]=='visitor' ? 'selected' : '' ?>>👤 Visiteur</option>
                        <option value="guide" <?= isset($_POST["role"]) && $_POST["role"]=='guide' ? 'selected' : '' ?>>🧭 Guide</option>
                    </select>
                </div>

                <button type="submit" class="w-full gradient-bg text-white py-3 px-4 rounded-lg font-semibold hover:opacity-90 transition duration-300 flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>Créer mon compte
                </button>
            </form>
        </div>
    </div>
</body>
</html>

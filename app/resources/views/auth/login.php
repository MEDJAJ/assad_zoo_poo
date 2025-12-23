<?php
session_start();
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php'; 
    
}else {
    echo 'Fichier config.php introuvable';
    exit;
}
include '../../../includes/functions.php';

$db = new Database();
$con = $db->getConnection();


class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email, $password) {
        try {
            $sql = "SELECT * FROM Utilisateur WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user['mot_passe'])) {
                 
                    $_SESSION["user_connecte"] = $user['id_utilisateure'];
                    $_SESSION["name_user_connecte"] = $user['nom'];

                    $role = $user['role'];
                    $status = intval($user['status_utilisateure']);

                    if ($role === "visitor") {
                        if ($status === 0) {
                            return ["etat"=>"error","message"=>"Attente activation de compte"];
                        } else {
                            return ["etat"=>"success","message"=>"Connexion réussie","redirect"=>"../visitor/home.php"];
                        }
                    } elseif ($role === "guide") {
                        if ($status === 0) {
                            return ["etat"=>"success","message"=>"Compte en attente d'autorisation","redirect"=>"../guide/activation_compte.php"];
                        } else {
                            return ["etat"=>"success","message"=>"Connexion réussie","redirect"=>"../guide/guide_dashboard.php"];
                        }
                    } else {
                        return ["etat"=>"success","message"=>"Connexion réussie","redirect"=>"../admin/admin_dashboard.php"];
                    }
                } else {
                    return ["etat"=>"error","message"=>"Mot de passe incorrect"];
                }
            } else {
                return ["etat"=>"error","message"=>"Email introuvable"];
            }

        } catch(PDOException $e) {
            return ["etat"=>"error","message"=>"Erreur PDO: " . $e->getMessage()];
        }
    }
}


$user = new User($con); 
$message = "";
$etat = "";
$redirect = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!validation($email, "/^[^\s@]+@[^\s@]+\.[^\s@]+$/") || 
        !validation($password, "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/")) {
        $etat = "error";
        $message = "Email et mot de passe obligatoires et valides";
    } else {
        $result = $user->login($email, $password);
        $etat = $result["etat"];
        $message = $result["message"];
        if(isset($result["redirect"])) {
            header("Location: " . $result["redirect"]);
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Zoo ASSAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-shadow { box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
        .input-focus:focus { box-shadow: 0 0 0 3px rgba(102,126,234,0.3); }
        .floating-lion { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0%{transform:translateY(0px);} 50%{transform:translateY(-20px);} 100%{transform:translateY(0px);} }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="fixed inset-0 gradient-bg z-0"></div>
    <div class="relative z-10 w-full max-w-4xl flex flex-col md:flex-row items-center">
        <div class="w-full md:w-1/2 mb-10 md:mb-0 md:pr-10 text-white text-center md:text-left">
            <div class="floating-lion inline-block mb-6"><div class="text-8xl">🦁</div></div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Zoo Virtuel<br>ASSAD</h1>
            <p class="text-xl opacity-90 mb-8">Découvrez les lions de l'Atlas et la faune africaine</p>
        </div>

        <div class="w-full md:w-1/2">
            <div class="bg-white card-shadow rounded-2xl p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Connexion</h2>
                    <p class="text-gray-600 mt-2">Accédez à votre compte</p>
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

                <form method="POST" action="" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2"></i>Adresse email
                        </label>
                        <input type="email" name="email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-purple-500 pl-12"
                               placeholder="votre@email.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2"></i>Mot de passe
                        </label>
                        <input type="password" name="password" id="login_password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-purple-500 pl-12 pr-12"
                               placeholder="Votre mot de passe">
                    </div>

                    <button type="submit" class="w-full gradient-bg text-white py-3 px-4 rounded-lg font-semibold hover:opacity-90 flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                    </button>

                    <div class="mt-8 text-center">
                        <p class="text-gray-600">Vous n'avez pas encore de compte ?</p>
                        <a href="register.php" class="inline-block mt-2 px-6 py-2 border-2 border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition font-medium">
                            <i class="fas fa-user-plus mr-2"></i>S'inscrire maintenant
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleLoginPassword() {
            const passwordField = document.getElementById('login_password');
            const icon = event.currentTarget.querySelector('i');
            if(passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.replace('fa-eye','fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.replace('fa-eye-slash','fa-eye');
            }
        }
    </script>
</body>
</html>

<?php

if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}

include '../../../includes/functions.php';

$db = new Database();
$conn = $db->getConnection();

class User {

    private $conn;
    private $nom;
    private $email;
    private $password;
    private $role;
    private $pays;
    private $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setRole($role) {
        $this->role = $role;
        $this->status = ($role === 'guide') ? 0 : 1;
    }

    public function setPays($pays) {
        $this->pays = $pays;
    }

    public function register() {
        $sql = "INSERT INTO Utilisateur 
                (nom, email, role, mot_passe, status_utilisateure, paye)
                VALUES (:nom, :email, :role, :mot_passe, :status, :pays)";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nom', $this->nom);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':role', $this->role);
            $stmt->bindParam(':mot_passe', $this->password);
            $stmt->bindParam(':status', $this->status, PDO::PARAM_INT);
            $stmt->bindParam(':pays', $this->pays);

            return $stmt->execute()
                ? ["etat" => "success", "message" => "Compte créé avec succès"]
                : ["etat" => "error", "message" => "Erreur lors de l'inscription"];

        } catch (PDOException $e) {
            return ["etat" => "error", "message" => $e->getMessage()];
        }
    }
}


$user = new User($conn);
$etat = "";
$message = "";

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
        $user->setNom($nom);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRole($role);
        $user->setPays($pays);

        $result = $user->register();
        $etat = $result["etat"];
        $message = $result["message"];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Zoo ASSAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg { background: linear-gradient(135deg, #1e3c72, #2a5298); }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
<div class="fixed inset-0 gradient-bg"></div>

<div class="relative z-10 w-full max-w-4xl flex bg-white rounded-2xl shadow-xl overflow-hidden">

  
    <div class="hidden md:flex w-1/2 flex-col items-center justify-center bg-gray-50 p-8">
        <h1 class="text-6xl">🦁</h1>
        <h2 class="text-3xl font-bold mt-4">Zoo Virtuel ASSAD</h2>
        <p class="text-gray-600 mt-2 text-center">Découvrez les lions de l'Atlas</p>
    </div>

 
    <div class="w-full md:w-1/2 p-8">
        <h2 class="text-3xl font-bold text-center mb-6">Créer un compte</h2>

        <?php if ($etat): ?>
            <div class="mb-4 p-3 rounded text-center font-semibold
                <?= $etat === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">

            <input type="text" name="nom" placeholder="Nom complet"
                   value="<?= $_POST['nom'] ?? '' ?>"
                   class="w-full p-3 border rounded-lg" required>

            <input type="email" name="email" placeholder="Email"
                   value="<?= $_POST['email'] ?? '' ?>"
                   class="w-full p-3 border rounded-lg" required>

            <input type="password" name="password" placeholder="Mot de passe"
                   class="w-full p-3 border rounded-lg" required>

            <input type="text" name="pays" placeholder="Pays"
                   value="<?= $_POST['pays'] ?? '' ?>"
                   class="w-full p-3 border rounded-lg" required>

            <select name="role" class="w-full p-3 border rounded-lg" required>
                <option value="">Choisir un rôle</option>
                <option value="visitor" <?= (@$_POST['role']=='visitor')?'selected':'' ?>>Visiteur</option>
                <option value="guide" <?= (@$_POST['role']=='guide')?'selected':'' ?>>Guide</option>
            </select>

            <button class="w-full gradient-bg text-white py-3 rounded-lg font-bold hover:opacity-90">
                Créer mon compte
            </button>

        </form>
    </div>
</div>
</body>
</html>

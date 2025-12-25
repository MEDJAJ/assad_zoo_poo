<?php

class Utilisateur
{
    protected $id;
    protected $nom;
    protected $email;
    protected $passwordHash;
    protected $pays;
    protected $role;

    public function __construct($nom, $email, $passwordHash, $pays, $role)
    {
        $this->nom = $nom;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->pays = $pays;
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }

   
    public static function login($conn, $email, $password)
    {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return "Email incorrect";
        }

        if (!password_verify($password, $user['mot_passe'])) {
            return "Mot de passe incorrect";
        }

       
        if ($user['role'] === 'visiteur' && !$user['status_utilisateure']) {
            return "Compte visiteur désactivé";
        }

        if ($user['role'] === 'guide' && !$user['is_approved']) {
           header('Location: ../../resources/views/guide/activation_compte.php');
        }

        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        return true;
    }
}

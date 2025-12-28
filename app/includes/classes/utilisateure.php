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

     public function setNom($nom) {
        $this->nom = $nom;
    }

       public function setEmail($email) {
        $this->email = $email;
    }

       public function setPasswordHash($password) {
        $this->passwordHash = $password;
    }


    public function setPays($pays) {
        $this->pays = $pays;
    }

  public function setRole($role) {
        $this->role = $role;
    }
   
 


      public static function getAllUsers($conn, $role = null, $status = null) {

        $sql = "SELECT * FROM Utilisateur WHERE role != 'admin'";
        $params = [];

        if (!empty($role)) {
            $sql .= " AND role = :role";
            $params[':role'] = $role;
        }

        if ($status !== null && $status !== '') {
            $sql .= " AND status_utilisateure = :status";
            $params[':status'] = (int)$status;
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public static function login($conn, $email, $password)
{
    $sql = "SELECT * FROM utilisateur WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) return "Email incorrect";
    if (!password_verify($password, $user['mot_passe'])) return "Mot de passe incorrect";

    $_SESSION['user_id'] = $user['id_utilisateure'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['status'] = (int)$user['status_utilisateure'];

    return true;
}


    
}

<?php
require_once __DIR__ . '/utilisateure.php';

class Visitor extends Utilisateur
{
    private $isActive;
public function __construct($nom, $email, $passwordHash, $pays, $role,$isActive){
    parent::__construct($nom, $email, $passwordHash, $pays, $role);
    $this->isActive=true;
}



  public function isActive() { return $this->isActive; }

       public function activate($conn,$id) {
        $sql = "UPDATE Utilisateur 
                SET status_utilisateure = 1 
                WHERE id_utilisateure = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }


    public function deactivate($conn,$id) {
        $sql = "UPDATE Utilisateur 
                SET status_utilisateure = 0 
                WHERE id_utilisateure = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }


    public function register($conn)
    {
        $sql = "INSERT INTO utilisateur
                (nom, email, role, mot_passe, status_utilisateure)
                VALUES (:nom, :email, :role, :pass, :status)";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nom' => $this->nom,
            ':email' => $this->email,
            ':role' => $this->role,
            ':pass' => $this->passwordHash,
            ':status' => $this->isActive
        ]);
    }

  
}





?>
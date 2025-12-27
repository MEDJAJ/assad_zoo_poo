<?php

require_once __DIR__ . '/utilisateure.php';

class Guide extends Utilisateur
{
    private $isApproved;

    public function __construct($nom, $email, $password, $pays,$role,$isApproved)
    {
        parent::__construct(
            $nom,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $pays,
            $role
        );

        $this->isApproved = false;
    }

    public function register($conn)
    {
        $sql = "INSERT INTO utilisateur
                (nom, email, role, mot_passe, status_utilisateure,paye)
                VALUES (:nom, :email, :role, :pass, :approved,:pays)";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nom' => $this->nom,
            ':email' => $this->email,
            ':role' => $this->role,
            ':pass' => $this->passwordHash,
            ':approved' => $this->isApproved,
            ':pays'=>$this->pays
        ]);
    }

    public function approve($conn,$id) {
        $sql = "UPDATE Utilisateur 
                SET status_utilisateure = 1 
                WHERE id_utilisateure = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }


    public function disapprove($conn,$id) {
        $sql = "UPDATE Utilisateur 
                SET status_utilisateure = 0 
                WHERE id_utilisateure = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public static function getCountGuide($conn){
        $sql="SELECT * FROM utilisateur WHERE role='guide'";
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $countguide=$stmt->fetchAll(PDO::FETCH_ASSOC);
        return count($countguide);
    }
}

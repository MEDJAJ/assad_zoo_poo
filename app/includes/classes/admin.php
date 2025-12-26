<?php
include 'utilisateure.php';
include 'guide.php';
include 'visitor.php';

class Admin extends Utilisateur
{
    public function __construct($nom, $email, $passwordHash, $pays,$role)
    {
        parent::__construct($nom, $email, $passwordHash, $pays, $role);
    }

   


      public static function toggleUser($conn, $id) {

        $sql = "SELECT role, status_utilisateure 
                FROM Utilisateur 
                WHERE id_utilisateure = :id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return false;

        if ($user['role'] === 'visitor') {
            $visitor = new Visitor( $user['nom'],$user['email'],'visitor',$user['mot_passe'], $user['status_utilisateure'],$user['paye']);
            return $user['status_utilisateure'] == 1
                ? $visitor->deactivate($conn,$id)
                : $visitor->activate($conn,$id);
        }

        if ($user['role'] === 'guide') {
            $guide = new Guide( $user['nom'],$user['email'],'guide',$user['mot_passe'], $user['status_utilisateure'],$user['paye']);
            return $user['status_utilisateure'] == 1
                ? $guide->disapprove($conn,$id)
                : $guide->approve($conn,$id);
        }

        return false;
    }
}

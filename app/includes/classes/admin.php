<?php
include 'Utilisateur.php';
include 'Guide.php';

class Admin extends Utilisateur
{
    public function __construct($nom, $email, $passwordHash, $pays,$role)
    {
        parent::__construct($nom, $email, $passwordHash, $pays, $role);
    }

    public function approuverGuide(Guide $guide, $conn, $idGuide)
    {
        $guide->approve();

        $sql = "UPDATE utilisateur SET is_approved = 1 WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $idGuide]);
    }
}

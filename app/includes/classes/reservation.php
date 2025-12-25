<?php
class Reservation
{
    private $idVisite;
    private $idUser;
    private $nbPersonnes;

    public function __construct($idVisite, $idUser, $nbPersonnes)
    {
        $this->idVisite = $idVisite;
        $this->idUser = $idUser;
        $this->nbPersonnes = $nbPersonnes;
    }


    public function isAvailable($conn)
    {
 
        $stmt = $conn->prepare("SELECT capaciter_max FROM visite_guidee WHERE id_visiteguide = :id");
        $stmt->execute([':id' => $this->idVisite]);
        $visite = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$visite) {
            throw new Exception("Visite inexistante");
        }

  
        $stmt2 = $conn->prepare("SELECT SUM(nb_personnes) AS total_reserve FROM reservation WHERE id_visiteguide = :id");
        $stmt2->execute([':id' => $this->idVisite]);
        $row = $stmt2->fetch(PDO::FETCH_ASSOC);

        $placesReservees = $row['total_reserve'] ?? 0;

        return ($placesReservees + $this->nbPersonnes) <= $visite['capaciter_max'];
    }

   
    public function save($conn)
    {
        if (!$this->isAvailable($conn)) {
            throw new Exception("La visite est complète ou il n'y a pas assez de places disponibles.");
        }

        $stmt = $conn->prepare("
            INSERT INTO reservation (nb_personnes, id_utilisateure, id_visiteguide)
            VALUES (:nb, :user, :visite)
        ");

        return $stmt->execute([
            ':nb' => $this->nbPersonnes,
            ':user' => $this->idUser,
            ':visite' => $this->idVisite
        ]);
    }
}

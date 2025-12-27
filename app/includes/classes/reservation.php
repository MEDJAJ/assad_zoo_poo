<?php
class Reservation
{
    private $idVisite;
    private $idUser;
    private $nbPersonnes;

    public function __construct($idVisite="", $idUser="", $nbPersonnes="")
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



   public function getTotalPersonnes($conn, $id_visite) {
    $sql = "SELECT SUM(nb_personnes) AS total_personnes
            FROM reservation
            WHERE id_visiteguide = :id_visite";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_visite' => $id_visite]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['total_personnes'] ?? 0;
}



    public function updateStatusVisite($con, $visite) {
        $places_reservees = $this->getTotalPersonnes($con, $visite['id_visiteguide']);
        $capacite_max = (int)$visite['capaciter_max'];

        if ($places_reservees >= $capacite_max) {
            $status = "Complet";
            $color = "red";
        } elseif ($places_reservees >= ($capacite_max - 3)) {
            $status = "Limité";
            $color = "yellow";
        } else {
            $status = "Disponible";
            $color = "green";
        }

   
        if ($status !== $visite['status_visiteguide']) {
           $update_sql = "UPDATE visite_guidee
               SET status_visiteguide = :status
               WHERE id_visiteguide = :id_visite";
$stmt_update = $con->prepare($update_sql);
$stmt_update->execute([
    ':status' => $status,
    ':id_visite' => $visite['id_visiteguide']
]);
        }

        return ['status' => $status, 'color' => $color];
    }



    public function getCountReservation($conn){
        $sql="SELECT * FROM reservation";
        $stm=$conn->prepare($sql);
        $stm->execute();
        $reservations=$stm->fetchAll(PDO::FETCH_ASSOC);
        return count($reservations);
    }

    public function prendeTopVisiteReservation($conn){
        $requéte_prende_top_reservation="SELECT 
    v.titre,
    g.nom AS nom_guide,
    v.id_visiteguide AS id_visite,
    COUNT(r.id_reservation) AS total_reservations
FROM visite_guidee v
INNER JOIN utilisateur g ON v.id_guide = g.id_utilisateure
LEFT JOIN reservation r ON v.id_visiteguide = r.id_visiteguide
GROUP BY v.id_visiteguide, v.titre, g.nom
ORDER BY total_reservations DESC
LIMIT 1";
 $stm=$conn->prepare($requéte_prende_top_reservation);
    $stm->execute();
    return $stm->fetch(PDO::FETCH_ASSOC);
    }
   
}

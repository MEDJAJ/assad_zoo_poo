<?php

class Visite {

   
    private $titre;
    private $date_visite;
    private $langue;
    private $capacite;
    private $duree;
    private $prix;
    private $id_guide;
  

   
    public function setTitre($titre) {
        $this->titre = $titre;
    }

  

    public function setDateVisite($date_visite) {
        $this->date_visite = $date_visite;
    }

    public function setLangue($langue) {
        $this->langue = $langue;
    }

    public function setCapacite($capacite) {
        $this->capacite = $capacite;
    }

    public function setDuree($duree) {
        $this->duree = $duree;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
    }

    public function setIdGuide($id_guide) {
        $this->id_guide = $id_guide;
    }

   

 
    public function createVisite($conn) {
        $sql = "INSERT INTO visite_guidee
                (titre, date_heure, langue, capaciter_max, duree, prix, status_visiteguide, id_guide)
                VALUES
                (:titre, :date_visite, :langue, :capacite, :duree, :prix, 'Disponible', :id_guide)";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':titre' => $this->titre,
            ':date_visite' => $this->date_visite,
            ':langue' => $this->langue,
            ':capacite' => $this->capacite,
            ':duree' => $this->duree,
            ':prix' => $this->prix,
            ':id_guide' => $this->id_guide
        ]);
    }

  
    public function updateVisite($conn, int $id_visite) {
        $sql = "UPDATE visite_guidee SET
                titre = :titre,
                date_heure = :date_visite,
                langue = :langue,
                capaciter_max = :capacite,
                duree = :duree,
                prix = :prix
                WHERE id_visiteguide = :id";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':titre' => $this->titre,
            ':date_visite' => $this->date_visite,
            ':langue' => $this->langue,
            ':capacite' => $this->capacite,
            ':duree' => $this->duree,
            ':prix' => $this->prix,
            ':id' => $id_visite
        ]);
    }

  
    public function deleteVisite($conn, int $id_visite) {
        $sql = "DELETE FROM visite_guidee WHERE id_visiteguide = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id_visite]);
    }



    public function getVisiteById($conn, int $id)
{
    $sql = "SELECT * FROM visite_guidee WHERE id_visiteguide = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function getVisitesByGuide( $conn, int $id_guide)
{
    $sql = "SELECT * FROM visite_guidee 
            WHERE id_guide = :id_guide";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_guide', $id_guide, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




public function countVisitesActives( $conn, int $id_guide)
{
    $sql = "SELECT COUNT(*) 
            FROM visite_guidee
            WHERE id_guide = :id
              AND status_visiteguide != 'Complet'
              AND date_heure >= NOW()";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id_guide, PDO::PARAM_INT);
    $stmt->execute();

    return (int) $stmt->fetchColumn();
}




public function getReservationsByGuide($conn, int $id_guide)
{
    $sql = "SELECT 
                u.nom,
                v.titre,
                r.nb_personnes,
                r.date_reservation
            FROM Utilisateur u
            INNER JOIN reservation r 
                ON r.id_utilisateure = u.id_utilisateure
            INNER JOIN visite_guidee v 
                ON r.id_visiteguide = v.id_visiteguide
            WHERE v.id_guide = :id";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id_guide, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt;
}




}

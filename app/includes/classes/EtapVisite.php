<?php


class EtapeVisite {
    private $id_visite;
    private $titre;
    private $description;
    private $ordre;

    public function setIdVisite($id_visite) {
        $this->id_visite = $id_visite;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setOrdre($ordre) {
        $this->ordre = $ordre;
    }

    
    public function createEtape($conn) {
        $sql = "INSERT INTO etapevisite (titre_etape, description_etape, ordre_etape, id_visite)
                VALUES (:titre, :description, :ordre, :id_visite)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':titre' => $this->titre,
            ':description' => $this->description,
            ':ordre' => $this->ordre,
            ':id_visite' => $this->id_visite
        ]);
    }

   
    public function getEtapesByVisite($conn, $id_visite) {
        $sql = "SELECT * FROM etapevisite WHERE id_visite = :id_visite ORDER BY ordre_etape ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_visite' => $id_visite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  }

?>
<?php
class Habitat {
    private $nom;
    private $typeclimat;
    private $description;
    private $zonezoo;

    public function __construct($nom="",$typeclimat="",$description="",$zonezoo=""){
        $this->nom=$nom;
        $this->typeclimat=$typeclimat;
        $this->description=$description;
        $this->zonezoo=$zonezoo;
    }

   
    public function setNom($nom) { $this->nom = $nom; }
    public function setTypeClimat($type) { $this->typeclimat = $type; }
    public function setDescription($desc) { $this->description = $desc; }
    public function setZoneZoo($zone) { $this->zonezoo = $zone; }

   
    public function getAll($conn) {
    
        $sql = "SELECT * FROM habitats";
        return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function insert($conn) {
        
        $sql = "INSERT INTO habitats(nom,typeclimat,description,zonezoo)
                VALUES (:nom, :typeclimat, :description, :zonezoo)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nom' => $this->nom,
            ':typeclimat' => $this->typeclimat,
            ':description' => $this->description,
            ':zonezoo' => $this->zonezoo
        ]);
    }

  
    public function update($conn,int $id): bool {
        
        $sql = "UPDATE habitats SET 
                nom = :nom, typeclimat = :typeclimat, description = :description, zonezoo = :zonezoo
                WHERE id_habitat = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nom' => $this->nom,
            ':typeclimat' => $this->typeclimat,
            ':description' => $this->description,
            ':zonezoo' => $this->zonezoo,
            ':id' => $id
        ]);
    }

  
    public function delete($conn,int $id): bool {
       
        $sql = "DELETE FROM habitats WHERE id_habitat = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>

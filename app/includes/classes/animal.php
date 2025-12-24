<?php
class Animal {
    private $nom;
    private $espece;
    private $alimentation;
    private $image;
    private $pays;
    private $description;
    private $habitat;


    public function __construct(
         $nom="",
         $espece="",
         $alimentation="",
         $image="",
         $pays="",
         $description="",
         $habitat=0
    ) {
        $this->nom = $nom;
        $this->espece = $espece;
        $this->alimentation = $alimentation;
        $this->image = $image;
        $this->pays = $pays;
        $this->description = $description;
        $this->habitat = $habitat;
    }

    
    public function getNom() { return $this->nom; }
    public function getEspece() { return $this->espece; }
    public function getAlimentation() { return $this->alimentation; }
    public function getImage() { return $this->image; }
    public function getPays() { return $this->pays; }
    public function getDescription() { return $this->description; }
    public function getHabitat() { return $this->habitat; }


     public function setNom($nom) { $this->nom = $nom; }
    public function setEspece($espece) { $this->espece = $espece; }
    public function setAlimentation($alimentation) { $this->alimentation = $alimentation; }
    public function setImage($image) { $this->image = $image; }
    public function setPays($pays) { $this->pays = $pays; }
    public function setDescription($description) { $this->description = $description; }
    public function setHabitat($habitat) { $this->habitat = $habitat; }

   
    public function insert( $conn) {
        $sql = "INSERT INTO animaux 
                (nom, espece, alimentation, image, pays_origine, description, id_habitat)
                VALUES (:nom, :espece, :alimentation, :image, :pays, :description, :habitat)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nom' => $this->nom,
            ':espece' => $this->espece,
            ':alimentation' => $this->alimentation,
            ':image' => $this->image,
            ':pays' => $this->pays,
            ':description' => $this->description,
            ':habitat' => $this->habitat
        ]);
    }

   
    public function update( $conn, int $id) {
        $sql = "UPDATE animaux SET
                nom = :nom, espece = :espece, alimentation = :alimentation,
                image = :image, pays_origine = :pays, description = :description, id_habitat = :habitat
                WHERE id_animal = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nom' => $this->nom,
            ':espece' => $this->espece,
            ':alimentation' => $this->alimentation,
            ':image' => $this->image,
            ':pays' => $this->pays,
            ':description' => $this->description,
            ':habitat' => $this->habitat,
            ':id' => $id
        ]);
    }

  
    public function delete($conn, int $id) {
        $stmt = $conn->prepare("DELETE FROM animaux WHERE id_animal = :id");
        return $stmt->execute([':id' => $id]);
    }


    public function getAll($conn) {
        $stmt = $conn->query("SELECT * FROM animaux");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function getHabitats($conn) {
        $stmt = $conn->query("SELECT id_habitat, nom FROM habitats");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>
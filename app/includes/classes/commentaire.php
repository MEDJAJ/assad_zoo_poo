<?php
class Commentaire
{
     private $note;
   private $texte;
    private $idVisite;
    private $idUser;
   private $titre;

    public function __construct($note, $texte, $idVisite, $idUser,$titre)
    {
        $this->note = $note;
        $this->texte = $texte;
        $this->idVisite = $idVisite;
        $this->idUser = $idUser;
        $this->titre=$titre;
    }

 
    public function save($conn)
    {
        $sql = "INSERT INTO commentaire (note,content,id_visiteguide, id_utilisateure,titre)
                VALUES (:note, :text, :idVisite, :idUser,:titre)";

        $stmt = $conn->prepare($sql);

        return $stmt->execute([
            ':note' => $this->note,
            ':text' => $this->texte,
            ':idVisite' =>  $this->idVisite,
            ':idUser' => $this->idUser ,
            ':titre' =>   $this->titre ,

        ]);
    }


    public static function getCommentairesByVisite($conn, $idVisite)
    {
        $sql = "SELECT c.*, u.nom 
                FROM commentaire c
                INNER JOIN utilisateur u ON u.id_utilisateure = c.id_utilisateure
                WHERE c.id_visiteguide = :idVisite
                ORDER BY c.date_commentaire DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':idVisite' => $idVisite]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    
  
    public static function getVisiteInfo($conn, $idVisite)
    {
        $sql = "SELECT v.*, u.nom 
                FROM visite_guidee v
                INNER JOIN utilisateur u ON u.id_utilisateure = v.id_guide
                WHERE v.id_visiteguide = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $idVisite]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

 
    public static function validate($titre, $texte, $note)
    {
        if ($note < 1 || $note > 5) return false;
        if (strlen($titre) < 3 || strlen($titre) > 100) return false;
        if (strlen($texte) < 10 || strlen($texte) > 1000) return false;
        return true;
    }

    public static function MaxNoteParVisite($conn,$id_visite){
       $query_note="SELECT MAX(c.note) AS note_moyenne FROM visite_guidee v INNER JOIN commentaire c ON c.id_visiteguide=v.id_visiteguide WHERE v.id_visiteguide=".$id_visite;
      $stmt=$conn->prepare($query_note);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}




?>

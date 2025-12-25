<?php
class Commentaire
{
    private $id;
    private $idVisite;
    private $idUser;
    private $texte;
    private $note;

    public function __construct($idVisite, $idUser, $texte, $note)
    {
        $this->idVisite = $idVisite;
        $this->idUser = $idUser;
        $this->texte = $texte;
        $this->note = $note;
    }

 
    public function save($conn)
    {
        $sql = "INSERT INTO commentaire (id_visiteguide, id_utilisateure, commentaire, note)
                VALUES (:idVisite, :idUser, :texte, :note)";

        $stmt = $conn->prepare($sql);

        return $stmt->execute([
            ':idVisite' => $this->idVisite,
            ':idUser' => $this->idUser,
            ':texte' => $this->texte,
            ':note' => $this->note
        ]);
    }


    public static function getCommentairesByVisite($conn, $idVisite)
    {
        $sql = "SELECT c.*, u.nom 
                FROM commentaire c
                INNER JOIN utilisateur u ON u.id_utilisateure = c.id_utilisateure
                WHERE c.id_visiteguide = :idVisite
                ORDER BY c.id DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':idVisite' => $idVisite]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>
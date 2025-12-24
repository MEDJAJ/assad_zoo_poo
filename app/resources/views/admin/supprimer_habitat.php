<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}
include '../../../includes/functions.php';

include '../../../includes/classes/habitat.php';

$db = new Database();
$conn = $db->getConnection();


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: habitats_admin.php");
    exit;
}

$id_habitat = (int) $_GET['id'];

$habitat = new Habitat();

if ($habitat->delete($conn,$id_habitat)) {
    header("Location: habitats_admin.php?deleted=1");
    exit;
} else {
    echo "Erreur lors de la suppression de l'habitat.";
}
?>

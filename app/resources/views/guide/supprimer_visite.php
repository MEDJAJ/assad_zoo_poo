<?php


if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}

include '../../../includes/classes/Visite.php';


$db = new Database();
$conn = $db->getConnection();


if (!isset($_GET['id_visite']) || empty($_GET['id_visite'])) {
    header("Location: guide_dashboard.php");
    exit;
}

$id_visite = (int) $_GET['id_visite'];


$visite = new Visite();

if ($visite->deleteVisite($conn, $id_visite)) {
    header("Location: guide_dashboard.php?deleted=1");
    exit;
} else {
    echo "Erreur lors de la suppression de la visite";
}


?>

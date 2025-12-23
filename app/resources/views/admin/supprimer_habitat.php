<?php


if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}
include '../../../includes/functions.php';

if (!isset($_GET['id'])) {
    header("Location: habitats_admin.php");
    exit;
}

$id_habitat = intval($_GET['id']);


$sql = "DELETE FROM habitats WHERE id_habitat = $id_habitat";
if (mysqli_query($con, $sql)) {
    header("Location: habitats_admin.php"); 
    exit;
} else {
    echo "Erreur lors de la suppression : " . mysqli_error($con);
}
?>




<?php

if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}


if (!isset($_GET['id_visite'])) {
    die("Visite non spécifiée");
}

$id_visite = (int)$_GET['id_visite'];


$sql_delete = "DELETE FROM visite_guidee WHERE id_visiteguide = $id_visite";
if (mysqli_query($con, $sql_delete)) {
    header("Location: guide_dashboard.php");
    exit;
} else {
    die("Erreur lors de la suppression : " . mysqli_error($conn));
}
?>

<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($id > 0) {
    $query = "DELETE FROM animaux WHERE id_animal=$id";
    mysqli_query($con, $query);
}


header("Location: animals_admin.php");
exit;
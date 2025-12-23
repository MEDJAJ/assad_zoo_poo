<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}
include '../../../includes/functions.php';

if (!isset($_GET['id']) || !isset($_GET['status'])) {
    header("Location: users_admin.php");
    exit;
}

$id_user = intval($_GET['id']);
$new_status = intval($_GET['status']);


$sql = "UPDATE Utilisateur SET status_utilisateure = $new_status WHERE id_utilisateure = $id_user";
mysqli_query($con, $sql);


header("Location: users_admin.php");
exit;
?>

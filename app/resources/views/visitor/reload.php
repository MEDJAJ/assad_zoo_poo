<?php
$id_visite=intval($_GET['id']);
$id_utilisateure= intval($_GET['id_utilisateure']);
header("Location: commentaire.php?id=$id_visite&id_utilisateure=$id_utilisateure");
exit;


?>
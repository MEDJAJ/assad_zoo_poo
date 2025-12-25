<?php
include '../../../includes/config.php';
include '../../../includes/classes/animal.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $animal = new Animal();
    $animal->delete($conn,$id);
}

header("Location: animals_admin.php");
exit;

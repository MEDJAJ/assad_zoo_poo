<?php
session_start();

require_once '../../../includes/config.php';
require_once '../../../includes/classes/Admin.php';

$db = new Database();
$conn = $db->getConnection();

if (!isset($_GET['id'])) {
    header("Location: users_admin.php");
    exit;
}

$id = (int) $_GET['id'];

Admin::toggleUser($conn, $id);

header("Location: users_admin.php");
exit;

?>
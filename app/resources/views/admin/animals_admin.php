<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}

include '../../../includes/functions.php';
include '../../../includes/classes/Animal.php';

$db = new Database();
$conn = $db->getConnection();

$animauxObj = new Animal();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $imageName = "";
    if (!empty($_FILES["image"]["name"])) {
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "../../../assets/uploads/" . $imageName);
    }

    $animal = new Animal(
        trim($_POST["nom"]),
        trim($_POST["espece"]),
        $_POST["alimentation"],
        $imageName,
        trim($_POST["pays"]),
        trim($_POST["description"]),
        (int)$_POST["habitat"]
    );

    if ($animal->insert($conn)) {
        header("Location: animals_admin.php?success=1");
        exit;
    } else {
        $message = "Erreur lors de l'ajout de l'animal.";
    }
}

$animaux = $animauxObj->getAll($conn);
$habitats = $animauxObj->getHabitats($conn);
$successMessage = isset($_GET["success"]) ? "Animal ajouté avec succès" : "";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion Animaux - Zoo ASSAD</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
<div class="flex">


<div class="w-64 bg-gray-900 text-white min-h-screen">
    <div class="p-6">
        <div class="flex items-center space-x-3 mb-8">
            <span class="text-3xl">🦁</span>
            <span class="text-xl font-bold">ASSAD Admin</span>
        </div>
        <nav class="space-y-2">
            <a href="admin_dashboard.php" class="flex items-center p-3 hover:bg-gray-800 rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i>Dashboard</a>
            <a href="users_admin.php" class="flex items-center p-3 hover:bg-gray-800 rounded-lg"><i class="fas fa-users mr-3"></i>Utilisateurs</a>
            <a href="animals_admin.php" class="flex items-center p-3 bg-blue-700 rounded-lg"><i class="fas fa-paw mr-3"></i>Animaux</a>
            <a href="habitats_admin.php" class="flex items-center p-3 hover:bg-gray-800 rounded-lg"><i class="fas fa-tree mr-3"></i>Habitats</a>
            <a href="stats.php" class="flex items-center p-3 hover:bg-gray-800 rounded-lg"><i class="fas fa-chart-bar mr-3"></i>Statistiques</a>
        </nav>
    </div>
</div>


<div class="flex-1 p-8">
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Gestion des Animaux</h1>
    <button id="openModal" class="bg-green-600 text-white px-4 py-2 rounded-lg"><i class="fas fa-plus mr-2"></i>Ajouter Animal</button>
</div>

<?php if ($message || $successMessage): ?>
<div class="bg-green-100 text-green-700 p-3 rounded mb-6"><?= $message ?: $successMessage ?></div>
<?php endif; ?>


<div id="modal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center">
<div class="bg-white p-6 rounded-xl w-full max-w-xl">
<h2 class="text-xl font-bold mb-4">Ajouter un animal</h2>
<form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
<input name="nom" placeholder="Nom" class="p-3 border rounded-lg" required>
<input name="espece" placeholder="Espèce" class="p-3 border rounded-lg" required>
<select name="alimentation" class="p-3 border rounded-lg">
<option>Carnivore</option>
<option>Herbivore</option>
<option>Omnivore</option>
</select>
<input name="pays" placeholder="Pays d'origine" class="p-3 border rounded-lg" required>
<select name="habitat" class="p-3 border rounded-lg" required>
<option value="">Habitat</option>
<?php foreach ($habitats as $h): ?>
<option value="<?= $h['id_habitat'] ?>"><?= htmlspecialchars($h['nom']) ?></option>
<?php endforeach; ?>
</select>
<textarea name="description" placeholder="Description" class="p-3 border rounded-lg md:col-span-2"></textarea>
<input type="file" name="image" class="p-3 border rounded-lg md:col-span-2">
<div class="md:col-span-2 flex justify-end gap-3">
<button type="button" id="closeModal" class="bg-gray-300 px-4 py-2 rounded-lg">Annuler</button>
<button class="bg-blue-600 text-white px-4 py-2 rounded-lg">Enregistrer</button>
</div>
</form>
</div>
</div>


<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<?php foreach ($animaux as $row): ?>
<div class="bg-white rounded-xl shadow overflow-hidden flex flex-col">
<img src="../../../assets/uploads/<?= $row['image'] ?: 'default.png' ?>" class="w-full h-48 object-cover">
<div class="p-4 flex-1 flex flex-col">
<h3 class="font-bold text-lg"><?= htmlspecialchars($row['nom']) ?></h3>
<p class="text-gray-600"><?= htmlspecialchars($row['espece']) ?> - <?= htmlspecialchars($row['alimentation']) ?></p>
<p class="text-gray-600 mb-2"><?= htmlspecialchars($row['pays_origine']) ?></p>
<p class="text-sm flex-1"><?= htmlspecialchars($row['description']) ?></p>
</div>
<div class="flex space-x-2 p-4">
<a href="modifier_animal.php?id=<?= $row['id_animal'] ?>" class="flex-1">
<button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"><i class="fas fa-edit mr-1"></i>Modifier</button>
</a>
<a href="supprimer_animal.php?id=<?= $row['id_animal'] ?>" class="flex-1" onclick="return confirm('Voulez-vous vraiment supprimer cet animal ?');">
<button class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700"><i class="fas fa-trash mr-1"></i>Supprimer</button>
</a>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
</div>

<script>
const modal = document.getElementById("modal");
document.getElementById("openModal").onclick = () => modal.classList.remove("hidden");
document.getElementById("closeModal").onclick = () => modal.classList.add("hidden");
</script>

</body>
</html>

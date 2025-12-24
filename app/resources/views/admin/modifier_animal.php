<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
    exit;
}

include '../../../includes/functions.php';
include '../../../includes/classes/animal.php';

$db = new Database();
$conn = $db->getConnection();

if (!isset($_GET['id'])) {
    header("Location: animals_admin.php");
    exit;
}

$id_animal = intval($_GET['id']);


$stmt = $conn->prepare("SELECT * FROM animaux WHERE id_animal = :id");
$stmt->execute([':id' => $id_animal]);
$animalData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$animalData) {
    echo "Animal non trouvé";
    exit;
}


$animal = new Animal(
    $animalData['nom'],
    $animalData['espece'],
    $animalData['alimentation'],
    $animalData['image'],
    $animalData['pays_origine'],
    $animalData['description'],
    $animalData['id_habitat']
);

$message = "";


$habitats = $animal->getHabitats($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  
    $nom = trim($_POST["nom"]);
    $espece = trim($_POST["espece"]);
    $alimentation = $_POST["alimentation"];
    $pays = trim($_POST["pays"]);
    $description = trim($_POST["description"]);
    $habitat = intval($_POST["habitat"]);

  
    $imageName = $animalData['image']; 
    if (!empty($_FILES["image"]["name"])) {
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "../../../assets/uploads/" . $imageName);
    }

 
    $animal->setNom($nom);
    $animal->setEspece($espece);
    $animal->setAlimentation($alimentation);
    $animal->setPays($pays);
    $animal->setDescription($description);
    $animal->setHabitat($habitat);
    $animal->setImage($imageName);

   
    if ($animal->update($conn, $id_animal)) {
        $message = "Animal mis à jour avec succès";
    } else {
        $message = "Erreur lors de la mise à jour";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Modifier Animal - Zoo ASSAD</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">

<div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-xl shadow">
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
        <i class="fas fa-edit"></i> Modifier Animal
    </h1>

    <?php if ($message): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-6"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <input name="nom" placeholder="Nom" value="<?= htmlspecialchars($animalData['nom']) ?>" class="p-3 border rounded-lg" required>
        <input name="espece" placeholder="Espèce" value="<?= htmlspecialchars($animalData['espece']) ?>" class="p-3 border rounded-lg" required>

        <select name="alimentation" class="p-3 border rounded-lg">
            <option value="Carnivore" <?= $animalData['alimentation'] === 'Carnivore' ? 'selected' : '' ?>>Carnivore</option>
            <option value="Herbivore" <?= $animalData['alimentation'] === 'Herbivore' ? 'selected' : '' ?>>Herbivore</option>
            <option value="Omnivore" <?= $animalData['alimentation'] === 'Omnivore' ? 'selected' : '' ?>>Omnivore</option>
        </select>

        <input name="pays" placeholder="Pays d'origine" value="<?= htmlspecialchars($animalData['pays_origine']) ?>" class="p-3 border rounded-lg" required>

        <select name="habitat" class="p-3 border rounded-lg">
            <option value="">Sélectionner Habitat</option>
            <?php foreach($habitats as $h): ?>
                <option value="<?= $h['id_habitat'] ?>" <?= $animalData['id_habitat'] == $h['id_habitat'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($h['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <textarea name="description" placeholder="Description" class="p-3 border rounded-lg md:col-span-2"><?= htmlspecialchars($animalData['description']) ?></textarea>

        <?php if ($animalData['image']): ?>
            <div class="md:col-span-2 flex justify-center">
                <img src="../../../assets/uploads/<?= htmlspecialchars($animalData['image']) ?>" class="h-48 object-cover rounded-lg">
            </div>
        <?php endif; ?>

        <input type="file" name="image" class="p-3 border rounded-lg md:col-span-2">

        <div class="md:col-span-2 flex justify-end gap-3 mt-4">
            <a href="animals_admin.php" class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">Annuler</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Enregistrer</button>
        </div>
    </form>
</div>

</body>
</html>

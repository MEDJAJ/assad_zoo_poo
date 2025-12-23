<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
}
include '../../../includes/functions.php';

if (!isset($_GET['id'])) {
    header("Location: animals_admin.php");
    exit;
}

$id_animal = intval($_GET['id']);
$result = mysqli_query($con, "SELECT * FROM animaux WHERE id_animal = $id_animal");

if (mysqli_num_rows($result) == 0) {
    echo "Animal non trouvé";
    exit;
}

$animal = mysqli_fetch_assoc($result);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = mysqli_real_escape_string($con, trim($_POST["nom"]));
    $espece = mysqli_real_escape_string($con, trim($_POST["espece"]));
    $alimentation = mysqli_real_escape_string($con, $_POST["alimentation"]);
    $pays = mysqli_real_escape_string($con, trim($_POST["pays"]));
    $description = mysqli_real_escape_string($con, trim($_POST["description"]));
    $habitat = mysqli_real_escape_string($con, trim($_POST["habitat"]));

    $imageName = $animal['image']; 

    $allowed = ["jpg","jpeg","png","webp"];
    if (!empty($_FILES["image"]["name"])) {
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $uploadPath = "../../../assets/uploads/" . $imageName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath);
    }

    $sql = "UPDATE animaux SET 
                nom='$nom', 
                espece='$espece', 
                alimentation='$alimentation', 
                image='$imageName', 
                pays_origine='$pays', 
                description='$description', 
                id_habitat='$habitat'
            WHERE id_animal = $id_animal";

    if (mysqli_query($con, $sql)) {
        $message = "Animal mis à jour avec succès";
        $animal = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM animaux WHERE id_animal = $id_animal"));
    } else {
        $message = "Erreur lors de la mise à jour : " . mysqli_error($con);
    }
}

$habitats = mysqli_query($con, "SELECT id_habitat, nom FROM habitats");
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
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2"><i class="fas fa-edit"></i> Modifier Animal</h1>

    <?php if ($message): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-6"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <input name="nom" placeholder="Nom" value="<?= htmlspecialchars($animal['nom']) ?>" class="p-3 border rounded-lg" required>
        <input name="espece" placeholder="Espèce" value="<?= htmlspecialchars($animal['espece']) ?>" class="p-3 border rounded-lg" required>

        <select name="alimentation" class="p-3 border rounded-lg">
            <option value="Carnivore" <?= $animal['alimentation'] === 'Carnivore' ? 'selected' : '' ?>>Carnivore</option>
            <option value="Herbivore" <?= $animal['alimentation'] === 'Herbivore' ? 'selected' : '' ?>>Herbivore</option>
            <option value="Omnivore" <?= $animal['alimentation'] === 'Omnivore' ? 'selected' : '' ?>>Omnivore</option>
        </select>

        <input name="pays" placeholder="Pays d'origine" value="<?= htmlspecialchars($animal['pays_origine']) ?>" class="p-3 border rounded-lg" required>

        <select name="habitat" class="p-3 border rounded-lg">
            <option value="">Sélectionner Habitat</option>
            <?php while($rowHab = mysqli_fetch_assoc($habitats)) : ?>
                <option value="<?= $rowHab['id_habitat'] ?>" <?= $animal['id_habitat'] == $rowHab['id_habitat'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($rowHab['nom']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <textarea name="description" placeholder="Description" class="p-3 border rounded-lg md:col-span-2"><?= htmlspecialchars($animal['description']) ?></textarea>

        <?php if ($animal['image']) : ?>
            <div class="md:col-span-2 flex justify-center">
                <img src="../../../assets/uploads/<?= htmlspecialchars($animal['image']) ?>" class="h-48 object-cover rounded-lg">
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

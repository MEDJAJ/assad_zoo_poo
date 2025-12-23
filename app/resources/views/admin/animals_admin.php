<?php
if (file_exists('../../../includes/config.php')) {
    include '../../../includes/config.php';
} else {
    echo 'Fichier config.php introuvable';
}
include '../../../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = mysqli_real_escape_string($con, trim($_POST["nom"]));
    $espece = mysqli_real_escape_string($con, trim($_POST["espece"]));
    $alimentation = mysqli_real_escape_string($con, $_POST["alimentation"]);
    $pays = mysqli_real_escape_string($con, trim($_POST["pays"]));
    $description = mysqli_real_escape_string($con, trim($_POST["description"]));
    $habitat = mysqli_real_escape_string($con, trim($_POST["habitat"]));

    $imageName = "";
    $allowed = ["jpg","jpeg","png","webp"];
    if (!empty($_FILES["image"]["name"])) {
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $uploadPath = "../../../assets/uploads/" . $imageName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath);
    }

    $sql = "INSERT INTO animaux (nom, espece, alimentation, image, pays_origine, description, id_habitat)
            VALUES ('$nom', '$espece', '$alimentation', '$imageName', '$pays', '$description', '$habitat')";
    mysqli_query($con, $sql);

    header("Location: animals_admin.php?success=1");
    exit;
}

$message = "";
if (isset($_GET["success"])) {
    $message = "Animal ajouté avec succès";
}

$result = mysqli_query($con, "SELECT * FROM animaux");
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
                <a href="admin_dashboard.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="users_admin.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="animals_admin.php" class="flex items-center space-x-3 p-3 bg-blue-700 rounded-lg">
                    <i class="fas fa-paw"></i>
                    <span>Animaux</span>
                </a>
                <a href="habitats_admin.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                    <i class="fas fa-tree"></i>
                    <span>Habitats</span>
                </a>
                <a href="stats.php" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistiques</span>
                </a>
            </nav>
        </div>
    </div>


    <div class="flex-1 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Gestion des Animaux</h1>
            <button id="openModal" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i>Ajouter Animal
            </button>
        </div>

        <?php if ($message): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-6">
            <?= $message ?>
        </div>
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
                    <select name="habitat" class="p-3 border rounded-lg">
                        <option value="">Habitat</option>
                        <?php
                        $habitats = mysqli_query($con, "SELECT id_habitat, nom FROM habitats");
                        while($rowHab = mysqli_fetch_assoc($habitats)) {
                            echo '<option value="' . $rowHab['id_habitat'] . '">' . htmlspecialchars($rowHab['nom']) . '</option>';
                        }
                        ?>
                    </select>
                    <textarea name="description" placeholder="Description" class="p-3 border rounded-lg md:col-span-2"></textarea>
                    <input type="file" name="image" class="p-3 border rounded-lg md:col-span-2">
                    <div class="md:col-span-2 flex justify-end gap-3">
                        <button type="button" id="closeModal" class="bg-gray-300 px-4 py-2 rounded-lg">Annuler</button>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>

    
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="bg-white rounded-xl shadow overflow-hidden flex flex-col">
                
                <?php if (!empty($row["image"]) && file_exists("../../../assets/uploads/" . $row["image"])): ?>
                    <img src="../../../assets/uploads/<?= htmlspecialchars($row['image']) ?>" class="w-full h-48 object-cover">
                <?php else: ?>
                    <img src="../../../assets/default.png" class="w-full h-48 object-cover">
                <?php endif; ?>

              
                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="font-bold text-lg"><?= htmlspecialchars($row["nom"]) ?></h3>
                    <p class="text-gray-600"><?= htmlspecialchars($row["espece"]) ?> - <?= htmlspecialchars($row["alimentation"]) ?></p>
                    <p class="text-gray-600 mb-2"><?= htmlspecialchars($row["pays_origine"]) ?></p>
                    <p class="text-sm flex-1"><?= htmlspecialchars($row["description"]) ?></p>
                </div>

           
                <div class="flex space-x-2 p-4">
                    <a href="modifier_animal.php?id=<?= $row['id_animal'] ?>" class="flex-1">
                        <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                            <i class="fas fa-edit mr-1"></i>Modifier
                        </button>
                    </a>
                    <a href="supprimer_animal.php?id=<?= $row['id_animal'] ?>" class="flex-1">
                        <button class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">
                            <i class="fas fa-trash mr-1"></i>Supprimer
                        </button>
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
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

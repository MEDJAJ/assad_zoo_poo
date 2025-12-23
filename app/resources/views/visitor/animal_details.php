<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lion de l'Atlas - Zoo ASSAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .lion-gradient {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
   
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-3xl">🦁</span>
                    <a href="fiche_speciale.php" class="text-xl font-bold text-gray-800">Zoo ASSAD</a>
                </div>
                <div class="flex space-x-6">
                    <a href="home.php" class="text-gray-600 hover:text-blue-600">Accueil</a>
                    <a href="animals.php" class="text-gray-600 hover:text-blue-600">Animaux</a>
                    <a href="visites.php" class="text-gray-600 hover:text-blue-600">Visites</a>
                    <a href="../auth/login.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Connexion</a>
                </div>
            </div>
        </div>
    </nav>

  
    <header class="lion-gradient text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4">🦁 Asaad - Le Lion de l'Atlas</h1>
            <p class="text-xl">Symbole de force et de noblesse du Maroc</p>
        </div>
    </header>


    <main class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
       
            <div>
                <div class="mb-4">
                    <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?auto=format&fit=crop&w=800" 
                         alt="Lion de l'Atlas" class="w-full rounded-lg shadow-lg">
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <img src="https://images.unsplash.com/photo-1562552476-8ac4a2d1d6a0?auto=format&fit=crop&w=400" 
                         alt="Lion 2" class="rounded-lg shadow">
                    <img src="https://images.unsplash.com/photo-1519068737630-e5db30e12e42?auto=format&fit=crop&w-400" 
                         alt="Lion 3" class="rounded-lg shadow">
                    <img src="https://images.unsplash.com/photo-1562771379-eafdca7a02f8?auto=format&fit=crop&w=400" 
                         alt="Lion 4" class="rounded-lg shadow">
                </div>
            </div>

      
            <div>
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-4">Informations générales</h2>
                    <div class="space-y-3">
                        <div class="flex">
                            <span class="font-semibold w-40">Nom scientifique:</span>
                            <span>Panthera leo leo</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold w-40">Famille:</span>
                            <span>Félin</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold w-40">Origine:</span>
                            <span>Maroc (Montagnes de l'Atlas)</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold w-40">Taille:</span>
                            <span>2.5 - 3 mètres</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold w-40">Poids:</span>
                            <span>180 - 250 kg</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold w-40">Alimentation:</span>
                            <span>Carnivore</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold w-40">Habitat:</span>
                            <span>Montagnes et forêts</span>
                        </div>
                    </div>
                </div>

                
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-4 text-red-700">Statut de conservation</h2>
                    <div class="flex items-center mb-3">
                        <div class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold mr-4">
                            ÉTEINT À L'ÉTAT SAUVAGE
                        </div>
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <p class="text-gray-700">
                        Le lion de l'Atlas a disparu à l'état sauvage au 20ème siècle. 
                        Des programmes de conservation tentent de préserver l'espèce en captivité.
                    </p>
                </div>

          
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold mb-4">Description</h2>
                    <p class="text-gray-700 mb-4">
                        Le lion de l'Atlas, également connu sous le nom de lion de Barbarie, 
                        était une sous-espèce de lion originaire des montagnes de l'Atlas en Afrique du Nord.
                    </p>
                    <p class="text-gray-700">
                        Il se distinguait par sa crinière plus foncée et plus fournie, et par sa taille 
                        plus imposante que les autres sous-espèces de lions africains.
                    </p>
                </div>
            </div>
        </div>
    </main>

 
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2024 Zoo Virtuel ASSAD - Projet CAN 2025 Maroc</p>
        </div>
    </footer>
</body>
</html>
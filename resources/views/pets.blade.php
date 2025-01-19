<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }
        .container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #5a67d8;
            border: none;
        }
        .btn-primary:hover {
            background-color: #434190;
        }
        .btn-danger {
            background-color: #e53e3e;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c53030;
        }
        table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center mb-4">Pet Manager</h3>

        <!-- Add/Edit Form -->
        <form id="pet-form">
            <div class="mb-3">
                <label for="name" class="form-label">Pet Name</label>
                <input type="text" class="form-control" id="name" placeholder="Enter pet name" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Pet Type</label>
                <input type="text" class="form-control" id="type" placeholder="Enter pet type (e.g., dog, cat)" required>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Pet Age</label>
                <input type="number" class="form-control" id="age" placeholder="Enter pet age" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Pet</button>
        </form>

        <!-- Pet List -->
        <table class="table table-hover mt-4">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Age</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="pet-list">
                <!-- Dynamically populated rows -->
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const petList = document.getElementById('pet-list');
        const petForm = document.getElementById('pet-form');
        let pets = [];
        let editingIndex = null;

        // Populate table with pets
        function renderPets() {
            petList.innerHTML = '';
            pets.forEach((pet, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${pet.name}</td>
                    <td>${pet.type}</td>
                    <td>${pet.age}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editPet(${index})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deletePet(${index})">Delete</button>
                    </td>
                `;
                petList.appendChild(row);
            });
        }

        // Handle form submission
        petForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const name = document.getElementById('name').value;
            const type = document.getElementById('type').value;
            const age = document.getElementById('age').value;

            if (editingIndex !== null) {
                // Edit existing pet
                pets[editingIndex] = { name, type, age };
                editingIndex = null;
            } else {
                // Add new pet
                pets.push({ name, type, age });
            }

            petForm.reset();
            renderPets();
        });

        // Edit pet
        function editPet(index) {
            const pet = pets[index];
            document.getElementById('name').value = pet.name;
            document.getElementById('type').value = pet.type;
            document.getElementById('age').value = pet.age;
            editingIndex = index;
        }

        // Delete pet
        function deletePet(index) {
            pets.splice(index, 1);
            renderPets();
        }

        // Initial render
        renderPets();
    </script>
</body>
</html>

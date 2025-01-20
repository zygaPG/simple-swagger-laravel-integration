<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .table-container {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3 class="text-center mb-4">Pet Manager</h3>

        <!-- Add/Edit Form -->
        <form id="pet-form" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Pet Name</label>
                <input type="text" class="form-control" id="name" placeholder="Enter pet name" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Pet Type</label>
                <select class="form-control" id="type" required>
                    <option value="" disabled selected>Select pet type</option>
                    <option value="dog">Dog</option>
                    <option value="cat">Cat</option>
                    <option value="bird">Bird</option>
                    <option value="fish">Fish</option>
                    <option value="reptile">Reptile</option>
                    <option value="small mammal">Small Mammal</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Pet Age</label>
                <input type="number" class="form-control" id="age" placeholder="Enter pet age" required>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Pet Photo</label>
                <input type="file" class="form-control" id="photo" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Pet</button>
        </form>

        <!-- Pet List -->
        <div class="table-container">
            <table class="table table-hover mt-4">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
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
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const petList = document.getElementById('pet-list');
        const petForm = document.getElementById('pet-form');
        let pets = [];
        let editingIndex = null;
        const API_URL = '';  // Empty string since we're using relative URLs

        // Uncomment and use CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Fetch pets from Petstore API
        function fetchPets() {
            fetch(`${API_URL}/pets`, {
                method: 'GET',  // Changed to GET to match route
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(response => response.json())
                .then(data => {
                    // Transform Petstore API data to our format
                    pets = data.map(pet => ({
                        id: pet.id,
                        name: pet.name,
                        type: pet.category?.name || 'Unknown',
                        age: parseInt(pet.tags?.[0]?.name) || 0,
                        photoUrl: pet.photoUrls?.[0] || ''
                    }));
                    renderPets();
                })
                .catch(error => console.error('Error fetching pets:', error));
        }

        // Populate table with pets
        function renderPets() {
            petList.innerHTML = '';
            pets.forEach((pet, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td><img src="${pet.photoUrl || 'https://via.placeholder.com/50'}" alt="${pet.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"></td>
                    <td>${pet.name}</td>
                    <td>${pet.type}</td>
                    <td>${pet.age}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editPet(${index})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deletePet(${pet.id})">Delete</button>
                    </td>
                `;
                petList.appendChild(row);
            });
        }

        // Handle form submission for adding/editing a pet
        petForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const name = document.getElementById('name').value;
            const type = document.getElementById('type').value;
            const age = document.getElementById('age').value;
            const petData = { name, type, age };

            if (editingIndex !== null) {
                updatePet(pets[editingIndex].id, petData);
                editingIndex = null;
            } else {
                addPet(petData);
            }

            petForm.reset();
        });

        // Add new pet via Petstore API
        function addPet(petData) {
            const formData = new FormData();
            const photoFile = document.getElementById('photo').files[0];
            
            const apiPetData = {
                id: Date.now(),
                name: petData.name,
                category: {
                    id: 1,
                    name: petData.type
                },
                status: 'available',
                tags: [{
                    id: 1,
                    name: petData.age.toString()
                }],
                photoUrls: [petData.photoUrl || '']
            };

            formData.append('photo', photoFile);
            formData.append('petData', JSON.stringify(apiPetData));

            fetch(`${API_URL}/pet`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
                .then(response => response.json())
                .then(() => {
                    fetchPets();
                    document.getElementById('photo').value = '';
                })
                .catch(error => console.error('Error adding pet:', error));
        }

        // Edit pet
        function editPet(index) {
            const pet = pets[index];
            document.getElementById('name').value = pet.name;
            document.getElementById('type').value = pet.type;
            document.getElementById('age').value = pet.age;
            editingIndex = index;
        }

        // Update pet via Petstore API
        function updatePet(id, petData) {
            const photoFile = document.getElementById('photo').files[0];
            
            const apiPetData = {
                id: id,
                name: petData.name,
                category: {
                    id: 1,
                    name: petData.type
                },
                status: 'available',
                tags: [{
                    id: 1,
                    name: petData.age.toString()
                }],
                photoUrls: [petData.photoUrl || '']
            };

            fetch(`${API_URL}/pet`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(apiPetData)
            })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`Server responded with ${response.status}: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(() => {
                    fetchPets();
                    document.getElementById('photo').value = '';
                })
                .catch(error => {
                    console.error('Error updating pet:', error);
                });
        }

        // Delete pet via Petstore API
        function deletePet(id) {
            fetch(`${API_URL}/pet/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(() => fetchPets())
                .catch(error => console.error('Error deleting pet:', error));
        }

        // Initial fetch
        fetchPets();
    </script>
</body>

</html>
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carousel Management - Erode Architecture Association</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar (same as dashboard) -->
        <div class="w-64 bg-blue-800 text-white">
            <div class="p-4 border-b border-blue-700">
                <h1 class="text-xl font-bold">EAA Admin</h1>
                <p class="text-sm text-blue-200">Welcome, <?php echo $_SESSION['admin_username']; ?></p>
            </div>
            
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="dashboard.php" class="flex items-center p-2 rounded hover:bg-blue-700">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="carousel.php" class="flex items-center p-2 rounded bg-blue-700">
                            <i class="fas fa-images mr-3"></i> Carousel Management
                        </a>
                    </li>
                    <li>
                        <a href="team.php" class="flex items-center p-2 rounded hover:bg-blue-700">
                            <i class="fas fa-users mr-3"></i> Team Management
                        </a>
                    </li>
                    <li>
                        <a href="settings.php" class="flex items-center p-2 rounded hover:bg-blue-700">
                            <i class="fas fa-cog mr-3"></i> Settings
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" class="flex items-center p-2 rounded hover:bg-blue-700">
                            <i class="fas fa-sign-out-alt mr-3"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm p-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Carousel Management</h2>
                    <button id="addCarouselBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">
                        <i class="fas fa-plus mr-2"></i> Add New Slide
                    </button>
                </div>
            </header>
            
            <main class="p-6">
                <div id="notification" class="hidden fixed top-4 right-4 z-50"></div>
                
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtitle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Button Text</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Button Path</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="carouselTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Data will be loaded here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add/Edit Carousel Modal -->
    <div id="carouselModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold" id="modalTitle">Add New Carousel Slide</h3>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="carouselForm" class="space-y-4">
                <input type="hidden" id="carouselId">
                
                <div>
                    <label for="carouselImage" class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    <div class="flex items-center">
                        <img id="imagePreview" src="" alt="Preview" class="hidden h-32 w-auto rounded-md mr-4">
                        <input type="file" id="carouselImage" name="carouselImage" accept="image/*" class="hidden">
                        <button type="button" id="uploadBtn" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                            <i class="fas fa-upload mr-2"></i> Upload Image
                        </button>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Recommended size: 1920x1080 pixels</p>
                </div>
                
                <div>
                    <label for="carouselTitle" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" id="carouselTitle" name="carouselTitle"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="carouselSubtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                    <textarea id="carouselSubtitle" name="carouselSubtitle" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="carouselButtonText" class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                        <input type="text" id="carouselButtonText" name="carouselButtonText" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="carouselButtonPath" class="block text-sm font-medium text-gray-700 mb-1">Button Path (URL)</label>
                        <input type="text" id="carouselButtonPath" name="carouselButtonPath" placeholder="e.g., /about.html or #"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label for="carouselDisplayOrder" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                    <input type="number" id="carouselDisplayOrder" name="carouselDisplayOrder"  value="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" id="saveBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM elements
            const addCarouselBtn = document.getElementById('addCarouselBtn');
            const carouselModal = document.getElementById('carouselModal');
            const closeModal = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const carouselForm = document.getElementById('carouselForm');
            const uploadBtn = document.getElementById('uploadBtn');
            const carouselImage = document.getElementById('carouselImage');
            const imagePreview = document.getElementById('imagePreview');
            const carouselTableBody = document.getElementById('carouselTableBody');
            const notification = document.getElementById('notification');
            
            let currentAction = 'add'; // 'add' or 'edit'
            let currentCarouselId = null;
            
            // Event listeners
            addCarouselBtn.addEventListener('click', () => {
                currentAction = 'add';
                document.getElementById('modalTitle').textContent = 'Add New Carousel Slide';
                carouselForm.reset();
                document.getElementById('carouselDisplayOrder').value = 0;
                document.getElementById('carouselButtonPath').value = '#';
                imagePreview.classList.add('hidden');
                carouselModal.classList.remove('hidden');
            });
            
            closeModal.addEventListener('click', () => {
                carouselModal.classList.add('hidden');
            });
            
            cancelBtn.addEventListener('click', () => {
                carouselModal.classList.add('hidden');
            });
            
            uploadBtn.addEventListener('click', () => {
                carouselImage.click();
            });
            
            carouselImage.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            // Load carousel data
            function loadCarouselData() {
                fetch('../api/get_carousel.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderCarouselTable(data.carouselItems);
                        } else {
                            showNotification('error', data.message || 'Failed to load carousel data');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'An error occurred while loading carousel data');
                    });
            }
            
            // Render carousel table
            function renderCarouselTable(items) {
                carouselTableBody.innerHTML = '';
                
                if (items.length === 0) {
                    carouselTableBody.innerHTML = `
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No carousel items found</td>
                        </tr>
                    `;
                    return;
                }
                
                items.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">${item.display_order}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="../${item.image_path}" alt="Carousel Image" class="h-16 w-auto rounded-md">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${item.title}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate">${item.subtitle}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${item.button_text}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500 max-w-xs truncate" title="${item.button_path}">${item.button_path}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="editCarouselItem(${item.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteCarouselItem(${item.id})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    `;
                    carouselTableBody.appendChild(row);
                });
            }
            
            // Form submission
            carouselForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData();
                if (carouselImage.files[0]) {
                    formData.append('image', carouselImage.files[0]);
                }
                formData.append('title', document.getElementById('carouselTitle').value);
                formData.append('subtitle', document.getElementById('carouselSubtitle').value);
                formData.append('button_text', document.getElementById('carouselButtonText').value);
                formData.append('button_path', document.getElementById('carouselButtonPath').value);
                formData.append('display_order', document.getElementById('carouselDisplayOrder').value);
                
                let url = '../api/add_carousel.php';
                if (currentAction === 'edit' && currentCarouselId) {
                    url = '../api/update_carousel.php';
                    formData.append('id', currentCarouselId);
                }
                
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', data.message);
                        loadCarouselData();
                        carouselModal.classList.add('hidden');
                    } else {
                        showNotification('error', data.message || 'Operation failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'An error occurred');
                });
            });
            
            // Show notification
            function showNotification(type, message) {
                notification.innerHTML = `
                    <div class="px-4 py-3 rounded relative ${type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'}">
                        <span class="block sm:inline">${message}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                            <svg class="fill-current h-6 w-6 ${type === 'success' ? 'text-green-500' : 'text-red-500'}" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                `;
                notification.classList.remove('hidden');
                
                setTimeout(() => {
                    notification.classList.add('hidden');
                }, 5000);
            }
            
            // Global functions for table actions
            window.editCarouselItem = function(id) {
                currentAction = 'edit';
                currentCarouselId = id;
                document.getElementById('modalTitle').textContent = 'Edit Carousel Slide';
                
                fetch(`../api/get_carousel_item.php?id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const item = data.item;
                            document.getElementById('carouselId').value = item.id;
                            document.getElementById('carouselTitle').value = item.title;
                            document.getElementById('carouselSubtitle').value = item.subtitle;
                            document.getElementById('carouselButtonText').value = item.button_text;
                            document.getElementById('carouselButtonPath').value = item.button_path;
                            document.getElementById('carouselDisplayOrder').value = item.display_order;
                            
                            if (item.image_path) {
                                imagePreview.src = `../${item.image_path}`;
                                imagePreview.classList.remove('hidden');
                            }
                            
                            carouselModal.classList.remove('hidden');
                        } else {
                            showNotification('error', data.message || 'Failed to load carousel item');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'An error occurred while loading carousel item');
                    });
            };
            
            window.deleteCarouselItem = function(id) {
                if (confirm('Are you sure you want to delete this carousel item?')) {
                    fetch(`../api/delete_carousel.php?id=${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('success', data.message);
                            loadCarouselData();
                        } else {
                            showNotification('error', data.message || 'Failed to delete carousel item');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'An error occurred while deleting carousel item');
                    });
                }
            };
            
            // Initial load
            loadCarouselData();
        });
    </script>
</body>
</html>  
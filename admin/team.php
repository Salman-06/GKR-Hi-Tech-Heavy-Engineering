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
    <title>Team Management - Erode Architecture Association</title>
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
                        <a href="carousel.php" class="flex items-center p-2 rounded hover:bg-blue-700">
                            <i class="fas fa-images mr-3"></i> Carousel Management
                        </a>
                    </li>
                    <li>
                        <a href="team.php" class="flex items-center p-2 rounded bg-blue-700">
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
                    <h2 class="text-xl font-semibold text-gray-800">Team Management</h2>
                    <button id="addTeamBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">
                        <i class="fas fa-plus mr-2"></i> Add New Member
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="teamTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Data will be loaded here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add/Edit Team Member Modal -->
    <div id="teamModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold" id="modalTitle">Add New Team Member</h3>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="teamForm" class="space-y-4">
                <input type="hidden" id="teamId">
                
                <div>
                    <label for="memberPhoto" class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                    <div class="flex items-center">
                        <img id="photoPreview" src="" alt="Preview" class="hidden h-32 w-32 rounded-full object-cover mr-4">
                        <input type="file" id="memberPhoto" name="memberPhoto" accept="image/*" class="hidden">
                        <button type="button" id="uploadPhotoBtn" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                            <i class="fas fa-upload mr-2"></i> Upload Photo
                        </button>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Recommended size: 500x500 pixels</p>
                </div>
                
                <div>
                    <label for="memberName" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" id="memberName" name="memberName" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="memberPosition" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <input type="text" id="memberPosition" name="memberPosition" required
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
            const addTeamBtn = document.getElementById('addTeamBtn');
            const teamModal = document.getElementById('teamModal');
            const closeModal = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const teamForm = document.getElementById('teamForm');
            const uploadPhotoBtn = document.getElementById('uploadPhotoBtn');
            const memberPhoto = document.getElementById('memberPhoto');
            const photoPreview = document.getElementById('photoPreview');
            const teamTableBody = document.getElementById('teamTableBody');
            const notification = document.getElementById('notification');
            
            let currentAction = 'add'; // 'add' or 'edit'
            let currentTeamId = null;
            
            // Event listeners
            addTeamBtn.addEventListener('click', () => {
                currentAction = 'add';
                document.getElementById('modalTitle').textContent = 'Add New Team Member';
                teamForm.reset();
                photoPreview.classList.add('hidden');
                teamModal.classList.remove('hidden');
            });
            
            closeModal.addEventListener('click', () => {
                teamModal.classList.add('hidden');
            });
            
            cancelBtn.addEventListener('click', () => {
                teamModal.classList.add('hidden');
            });
            
            uploadPhotoBtn.addEventListener('click', () => {
                memberPhoto.click();
            });
            
            memberPhoto.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoPreview.src = e.target.result;
                        photoPreview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            // Load team data
            function loadTeamData() {
                fetch('../api/get_team.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderTeamTable(data.teamMembers);
                        } else {
                            showNotification('error', data.message || 'Failed to load team data');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'An error occurred while loading team data');
                    });
            }
            
            // Render team table
            function renderTeamTable(members) {
                teamTableBody.innerHTML = '';
                
                if (members.length === 0) {
                    teamTableBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No team members found</td>
                        </tr>
                    `;
                    return;
                }
                
                members.forEach(member => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="../${member.photo_path}" alt="Team Member" class="h-16 w-16 rounded-full object-cover">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${member.name}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${member.position}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="editTeamMember(${member.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteTeamMember(${member.id})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    `;
                    teamTableBody.appendChild(row);
                });
            }
            
            // Form submission
            teamForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData();
                if (memberPhoto.files[0]) {
                    formData.append('photo', memberPhoto.files[0]);
                }
                formData.append('name', document.getElementById('memberName').value);
                formData.append('position', document.getElementById('memberPosition').value);
                
                let url = '../api/add_team.php';
                if (currentAction === 'edit' && currentTeamId) {
                    url = '../api/update_team.php';
                    formData.append('id', currentTeamId);
                }
                
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', data.message);
                        loadTeamData();
                        teamModal.classList.add('hidden');
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
            window.editTeamMember = function(id) {
                currentAction = 'edit';
                currentTeamId = id;
                document.getElementById('modalTitle').textContent = 'Edit Team Member';
                
                fetch(`../api/get_team_member.php?id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const member = data.member;
                            document.getElementById('teamId').value = member.id;
                            document.getElementById('memberName').value = member.name;
                            document.getElementById('memberPosition').value = member.position;
                            
                            if (member.photo_path) {
                                photoPreview.src = `../${member.photo_path}`;
                                photoPreview.classList.remove('hidden');
                            }
                            
                            teamModal.classList.remove('hidden');
                        } else {
                            showNotification('error', data.message || 'Failed to load team member');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'An error occurred while loading team member');
                    });
            };
            
            window.deleteTeamMember = function(id) {
                if (confirm('Are you sure you want to delete this team member?')) {
                    fetch(`../api/delete_team.php?id=${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('success', data.message);
                            loadTeamData();
                        } else {
                            showNotification('error', data.message || 'Failed to delete team member');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'An error occurred while deleting team member');
                    });
                }
            };
            
            // Initial load
            loadTeamData();
        });
    </script>
</body>
</html>
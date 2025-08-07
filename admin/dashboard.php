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
    <title>Admin Dashboard - Erode Architecture Association</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
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
                <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
            </header>
            
            <main class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Stats Cards -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                <i class="fas fa-images text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-500">Carousel Items</h3>
                                <p class="text-2xl font-bold" id="carouselCount">Loading...</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-500">Team Members</h3>
                                <p class="text-2xl font-bold" id="teamCount">Loading...</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                                <i class="fas fa-eye text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-500">Site Visits</h3>
                                <p class="text-2xl font-bold">1,234</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="mt-8 bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-4">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <p class="font-medium">You updated the carousel</p>
                                <p class="text-sm text-gray-500">2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="p-2 rounded-full bg-green-100 text-green-600 mr-4">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div>
                                <p class="font-medium">New team member added</p>
                                <p class="text-sm text-gray-500">1 day ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Fetch counts on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetch('api/get_counts.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('carouselCount').textContent = data.carouselCount;
                        document.getElementById('teamCount').textContent = data.teamCount;
                    }
                })
                .catch(error => {
                    console.error('Error fetching counts:', error);
                });
        });
    </script>
</body>
</html>
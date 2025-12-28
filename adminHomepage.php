<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .dashboard { display: flex; }
        .sidebar { width: 200px; background: #f4f4f4; padding: 20px; }
        .sidebar a { display: block; margin: 10px 0; text-decoration: none; color: #333; }
        .content { flex: 1; padding: 20px; }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <div class="dashboard">
        <div class="sidebar">
            <h3>Menu</h3>
            <a href="#manage-users">Manage Users</a>
            <a href="#manage-buses">Manage Buses</a>
            <a href="#view-bookings">View Bookings</a>
            <a href="#reports">Reports</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="content">
            <h2>Welcome, Admin!</h2>
            <p>Use the menu to navigate to different admin functions.</p>
            <div id="manage-users">
                <h3>Manage Users</h3>
                <p>Here you can add, edit, or delete user accounts.</p>
                <!-- Placeholder for future functionality -->
            </div>
            <div id="manage-buses">
                <h3>Manage Buses</h3>
                <p>Add or update bus routes and schedules.</p>
                <!-- Placeholder -->
            </div>
            <div id="view-bookings">
                <h3>View Bookings</h3>
                <p>Check all customer bookings.</p>
                <!-- Placeholder -->
            </div>
            <div id="reports">
                <h3>Reports</h3>
                <p>Generate reports on sales, usage, etc.</p>
                <!-- Placeholder -->
            </div>
        </div>
    </div>
</body>
</html>
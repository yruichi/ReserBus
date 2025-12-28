<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'customer') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Homepage</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
            margin-top: 70px; /* Account for fixed navbar */
        }
        .homepage { text-align: center; }
        .options { display: flex; justify-content: center; gap: 20px; margin-top: 30px; }
        .option { border: 1px solid #ccc; padding: 20px; width: 200px; }
        .option a { text-decoration: none; color: #333; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/customerNavbar.php'; ?>
    <div class="homepage">
        <h1>Welcome to Bus Reservation System</h1>
        <p>Hello, Customer! Manage your bus bookings here.</p>
        
        <div class="options">
            <div class="option">
                <h3>Search Buses</h3>
                <p>Find available buses and routes.</p>
                <a href="customerSearchBuses.php">Go to Search</a>
            </div>
            <div class="option">
                <h3>My Bookings</h3>
                <p>View and manage your bookings.</p>
                <a href="customerMyBookings.php">View Bookings</a>
            </div>
            <div class="option">
                <h3>Profile</h3>
                <p>Update your account details.</p>
                <a href="customerProfile.php">Edit Profile</a>
            </div>
        </div>
        
        <p><a href="logout.php">Logout</a></p>
    </div>
    
    <!-- Placeholder sections -->
    <div id="profile" style="margin-top: 50px;">
        <h2>Profile</h2>
        <p>Profile management coming soon.</p>
    </div>
</body>
</html>
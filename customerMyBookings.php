<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'customer') {
    header('Location: login.php');
    exit();
}
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// Simulated bookings with more details (in real app, from database)
$bookings = [
    [
        'id' => 1,
        'route' => 'City A to City B',
        'date' => '2025-12-30',
        'time' => '10:00 AM',
        'bus_number' => 'BUS-101',
        'seat_number' => 'A12',
        'status' => 'confirmed',
        'payment_status' => 'paid'
    ],
    [
        'id' => 2,
        'route' => 'Edsa Carousel: PITX  Terminal, City of Dreams, DFA, Roxas Boulevard, Taft Avenue (curbside), Ayala Avenue Bus Stop, MRT-3 Buendia Station, Guadalupe Bridge, MRT-3 Ortigas Station, MRT-3 Santolan Station, Main Avenue, Cubao, Nepa Q. Mart, MRT-3 Quezon Ave. Station, MRT-3 North Ave. Station, LRT-1 Roosevelt Station, Kalinga Road, LRT-1 Balintawak Station, Bagong Barrio, Monumento',
        'date' => '2025-12-25',
        'time' => '2:00 PM',
        'bus_number' => 'BUS-202',
        'seat_number' => 'B05',
        'status' => 'cancelled',
        'payment_status' => 'refunded'
    ],
    [
        'id' => 3,
        'route' => 'City A to City D',
        'date' => '2025-12-28',
        'time' => '8:00 AM',
        'bus_number' => 'BUS-303',
        'seat_number' => 'C08',
        'status' => 'pending',
        'payment_status' => 'pending'
    ]
];

// Handle cancel
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_booking'])) {
    $booking_id = $_POST['booking_id'];
    // Simulate cancel
    foreach ($bookings as &$booking) {
        if ($booking['id'] == $booking_id && $booking['status'] == 'confirmed') {
            // Check if within allowed time (e.g., 24 hours before)
            $booking_date = strtotime($booking['date']);
            if ($booking_date > time() + 86400) { // 24 hours
                $booking['status'] = 'cancelled';
                $booking['payment_status'] = 'refunded';
                $success = 'Booking cancelled successfully.';
            } else {
                $error = 'Cannot cancel booking less than 24 hours before departure.';
            }
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
            margin-top: 70px; /* Account for fixed navbar */
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            height: 60px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .logo .red { color: #dc3545; }
        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-menu li {
            margin: 0 15px;
        }
        .nav-menu a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 20px;
            transition: background 0.3s;
        }
        .nav-menu a:hover {
            background: #f8f9fa;
        }
        .nav-menu .active {
            background: #007bff;
            color: white;
        }
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }
        .hamburger div {
            width: 25px;
            height: 3px;
            background: #333;
            margin: 3px 0;
            transition: 0.3s;
        }
        .profile-icon {
            margin-right: 20px;
        }
        .profile-icon a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fa;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
        }
        .profile-icon a:hover {
            background: #007bff;
            color: white;
        }
        .profile-icon.active > div {
            background: #007bff;
            color: white;
        }
        .profile-icon svg {
            width: 20px;
            height: 20px;
        }
        @media (max-width: 768px) {
            .nav-menu {
                position: fixed;
                top: 60px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 60px);
                background: white;
                flex-direction: column;
                justify-content: flex-start;
                padding-top: 20px;
                transition: left 0.3s;
            }
            .nav-menu.active {
                left: 0;
            }
            .nav-menu li {
                margin: 10px 0;
            }
            .hamburger {
                display: flex;
            }
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .confirmed { color: green; }
        .pending { color: orange; }
        .cancelled { color: red; }
        .ticket { background: #f9f9f9; padding: 20px; border: 1px solid #ccc; margin-top: 20px; }
        button { padding: 5px 10px; margin: 2px; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/customerNavbar.php'; ?>
    <h1>My Bookings</h1>
    <p>View and manage your bus reservations.</p>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Route</th>
            <th>Date</th>
            <th>Time</th>
            <th>Bus Number</th>
            <th>Seat Number</th>
            <th>Status</th>
            <th>Payment Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($bookings as $booking): ?>
        <tr>
            <td><?php echo $booking['id']; ?></td>
            <td><?php echo htmlspecialchars($booking['route']); ?></td>
            <td><?php echo htmlspecialchars($booking['date']); ?></td>
            <td><?php echo htmlspecialchars($booking['time']); ?></td>
            <td><?php echo htmlspecialchars($booking['bus_number']); ?></td>
            <td><?php echo htmlspecialchars($booking['seat_number']); ?></td>
            <td class="<?php echo $booking['status']; ?>"><?php echo ucfirst($booking['status']); ?></td>
            <td><?php echo ucfirst($booking['payment_status']); ?></td>
            <td>
                <a href="ticket.php?id=<?php echo $booking['id']; ?>" target="_blank"><button>View Ticket</button></a>
                <?php if ($booking['status'] == 'confirmed'): ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                        <button type="submit" name="cancel_booking" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="customerHomepage.php">Back to Homepage</a></p>
    <script>
        // Hamburger menu toggle
        document.querySelector('.hamburger').addEventListener('click', function() {
            document.querySelector('.nav-menu').classList.toggle('active');
        });
    </script>
</body>
</html>
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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid ticket ID');
}

$booking_id = (int)$_GET['id'];

// Simulated bookings (same as myBookings.php)
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
        'route' => 'Edsa Carousel: PITX – Terminal, City of Dreams, DFA, Roxas Boulevard, Taft Avenue (curbside), Ayala Avenue Bus Stop, MRT-3 Buendia Station, Guadalupe Bridge, MRT-3 Ortigas Station, MRT-3 Santolan Station, Main Avenue, Cubao, Nepa Q. Mart, MRT-3 Quezon Ave. Station, MRT-3 North Ave. Station, LRT-1 Roosevelt Station, Kalinga Road, LRT-1 Balintawak Station, Bagong Barrio, Monumento',
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

$ticket = null;
foreach ($bookings as $booking) {
    if ($booking['id'] == $booking_id) {
        $ticket = $booking;
        break;
    }
}

if (!$ticket) {
    die('Ticket not found');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Ticket</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; text-align: center; }
        .ticket { border: 2px solid #000; padding: 20px; max-width: 400px; margin: auto; }
        h1 { margin-top: 0; }
        p { margin: 10px 0; }
        @media print {
            body * { visibility: hidden; }
            .ticket, .ticket * { visibility: visible; }
            .ticket { position: absolute; left: 0; top: 0; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <h1>Bus Reservation Ticket</h1>
        <p><strong>Booking ID:</strong> <?php echo $ticket['id']; ?></p>
        <p><strong>Passenger:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p><strong>Route:</strong> <?php echo htmlspecialchars($ticket['route']); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($ticket['date']); ?></p>
        <p><strong>Time:</strong> <?php echo htmlspecialchars($ticket['time']); ?></p>
        <p><strong>Bus Number:</strong> <?php echo htmlspecialchars($ticket['bus_number']); ?></p>
        <p><strong>Seat Number:</strong> <?php echo htmlspecialchars($ticket['seat_number']); ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($ticket['status']); ?></p>
        <p><strong>Payment Status:</strong> <?php echo ucfirst($ticket['payment_status']); ?></p>
        <p>Thank you for choosing our service!</p>
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
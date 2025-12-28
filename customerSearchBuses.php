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

// Simulated available buses
$available_buses = [
    [
        'id' => 1,
        'route' => 'Edsa Carousel: PITX – Terminal, City of Dreams, DFA, Roxas Boulevard, Taft Avenue (curbside), Ayala Avenue Bus Stop, MRT-3 Buendia Station, Guadalupe Bridge, MRT-3 Ortigas Station, MRT-3 Santolan Station, Main Avenue, Cubao, Nepa Q. Mart, MRT-3 Quezon Ave. Station, MRT-3 North Ave. Station, LRT-1 Roosevelt Station, Kalinga Road, LRT-1 Balintawak Station, Bagong Barrio, Monumento',
        'date' => '2025-12-30',
        'time' => '10:00 AM',
        'bus_number' => 'BUS-101',
        'available_seats' => 20,
        'price' => 150
    ],
    [
        'id' => 2,
        'route' => 'Edsa Carousel: PITX – Terminal, City of Dreams, DFA, Roxas Boulevard, Taft Avenue (curbside), Ayala Avenue Bus Stop, MRT-3 Buendia Station, Guadalupe Bridge, MRT-3 Ortigas Station, MRT-3 Santolan Station, Main Avenue, Cubao, Nepa Q. Mart, MRT-3 Quezon Ave. Station, MRT-3 North Ave. Station, LRT-1 Roosevelt Station, Kalinga Road, LRT-1 Balintawak Station, Bagong Barrio, Monumento',
        'date' => '2025-12-30',
        'time' => '2:00 PM',
        'bus_number' => 'BUS-202',
        'available_seats' => 15,
        'price' => 150
    ],
    [
        'id' => 3,
        'route' => 'City A to City B',
        'date' => '2025-12-31',
        'time' => '8:00 AM',
        'bus_number' => 'BUS-303',
        'available_seats' => 25,
        'price' => 200
    ]
];

$search_results = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $date = $_POST['date'];
    $return_date = isset($_POST['return_date']) ? $_POST['return_date'] : null;

    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));

    if ($from === $to) {
        $error = 'Departure and destination cannot be the same.';
    } elseif (empty($date) || $date < $tomorrow) {
        $error = 'Departure date must be at least 1 day in advance.';
    } else {
        // Simple search (in real app, query database)
        foreach ($available_buses as $bus) {
            if (stripos($bus['route'], $from) !== false && stripos($bus['route'], $to) !== false && $bus['date'] == $date) {
                $search_results[] = $bus;
            } elseif (stripos($bus['route'], 'Edsa Carousel') !== false && $bus['date'] == $date) {
                // Special case for Edsa Carousel
                $search_results[] = $bus;
            }
        }
    }
}

// Handle booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book'])) {
    $bus_id = $_POST['bus_id'];
    // Simulate booking (in real app, save to database)
    $bus = null;
    foreach ($available_buses as $b) {
        if ($b['id'] == $bus_id) {
            $bus = $b;
            break;
        }
    }
    if ($bus && $bus['available_seats'] > 0) {
        // Simulate seat selection (random for demo)
        $seat = 'A' . rand(1, 10);
        // In real app, update database
        $success = "Booking confirmed! Route: {$bus['route']}, Date: {$bus['date']}, Time: {$bus['time']}, Seat: $seat";
    } else {
        $error = 'No seats available or bus not found.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Buses</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
            margin-top: 70px; /* Account for fixed navbar */
        }
        .hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://via.placeholder.com/1920x600?text=Bus+Terminal') no-repeat center center/cover;
            height: 60vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
        }
        .hero h1 {
            font-size: 3rem;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .hero p {
            font-size: 1.2rem;
            margin: 10px 0 0;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        .search-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            max-width: 800px;
            width: 90%;
            margin: -50px auto 50px;
            position: relative;
            z-index: 1;
        }
        .trip-type {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .trip-type button {
            background: #f0f0f0;
            border: none;
            padding: 10px 20px;
            margin: 0 5px;
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .trip-type button.active {
            background: #007bff;
            color: white;
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            flex: 1;
            min-width: 200px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        .search-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
            margin-top: 20px;
        }
        .search-btn:hover {
            background: #c82333;
        }
        .results {
            max-width: 1000px;
            margin: 50px auto;
            padding: 0 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .book-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .book-btn:hover {
            background: #218838;
        }
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            .search-card {
                padding: 20px;
                margin: -30px auto 30px;
            }
            .form-row {
                flex-direction: column;
            }
            table {
                font-size: 14px;
            }
        }
        /* Routes Section Styles */
        .routes-section {
            max-width: 1000px;
            margin: 50px auto;
            padding: 0 20px;
        }
        .routes-section h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 2rem;
        }
        .route-tabs {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
        }
        .tab-btn {
            background: #f0f0f0;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }
        .tab-btn.active {
            background: #007bff;
            color: white;
        }
        .tab-btn:hover {
            background: #007bff;
            color: white;
        }
        .route-content {
            position: relative;
        }
        .route-grid {
            display: none;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .route-grid.active {
            display: grid;
        }
        .route-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        .route-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border-color: #007bff;
        }
        .route-info h3 {
            margin: 0 0 8px 0;
            color: #333;
            font-size: 1.1rem;
        }
        .route-info p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }
        .route-price {
            margin-top: 15px;
            font-size: 1.2rem;
            font-weight: bold;
            color: #dc3545;
            text-align: right;
        }
        @media (max-width: 768px) {
            .route-tabs {
                flex-direction: column;
                align-items: center;
            }
            .route-grid {
                grid-template-columns: 1fr;
            }
            .routes-section h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/customerNavbar.php'; ?>
    <div class="hero">
        <a href="customerHomepage.php" style="position: absolute; top: 20px; left: 20px; color: white; text-decoration: none; font-size: 18px;">&larr; Back to Homepage</a>
        <h1>Bus Online Booking</h1>
        <p>Search, compare, and book bus tickets across the Philippines.</p>
    </div>

    <div class="search-card">
        <div class="trip-type">
            <button class="active" id="one-way">One Way</button>
            <button id="round-trip">Round Trip</button>
        </div>
        <form method="post" action="">
            <div class="form-row">
                <div class="form-group">
                    <label for="from">From</label>
                    <input type="text" id="from" name="from" value="Manila" readonly required>
                </div>
                <div class="form-group">
                    <label for="to">To</label>
                    <select id="to" name="to" required>
                        <option value="">Select destination</option>
                        <option value="Baguio">Baguio</option>
                        <option value="Tagaytay">Tagaytay</option>
                        <option value="Naga">Naga</option>
                        <option value="Baler">Baler</option>
                        <option value="Batangas">Batangas</option>
                        <option value="Vigan">Vigan</option>
                        <option value="Legazpi">Legazpi</option>
                        <option value="Laoag">Laoag</option>
                        <option value="Iloilo">Iloilo</option>
                        <option value="Bacolod">Bacolod</option>
                        <option value="Cebu">Cebu</option>
                        <option value="Davao">Davao</option>
                        <option value="Cagayan de Oro">Cagayan de Oro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Departure Date</label>
                    <input type="date" id="date" name="date" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                </div>
                <div class="form-group" id="return-date-group" style="display: none;">
                    <label for="return-date">Return Date</label>
                    <input type="date" id="return-date" name="return_date" min="<?php echo date('Y-m-d', strtotime('+2 days')); ?>">
                </div>
                <div class="form-group">
                    <label for="passengers">Passengers</label>
                    <select id="passengers" name="passengers">
                        <option value="1">1 Passenger</option>
                        <option value="2">2 Passengers</option>
                        <option value="3">3 Passengers</option>
                        <option value="4">4 Passengers</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="search" class="search-btn">Search Buses</button>
        </form>
    </div>

    <!-- Routes Section -->
    <div class="routes-section">
        <h2>Popular Routes</h2>
        <div class="route-tabs">
            <button class="tab-btn active" data-region="metro-manila">Metro Manila</button>
            <button class="tab-btn" data-region="north-luzon">North Luzon</button>
            <button class="tab-btn" data-region="south-luzon">South Luzon</button>
            <button class="tab-btn" data-region="visayas">Visayas</button>
            <button class="tab-btn" data-region="mindanao">Mindanao</button>
        </div>
        <div class="route-content">
            <div class="route-grid active" id="metro-manila">
                <div class="route-card" data-from="Manila" data-to="Tagaytay">
                    <div class="route-info">
                        <h3>Manila → Tagaytay</h3>
                        <p>Via SLEX • Scenic route</p>
                    </div>
                    <div class="route-price">₱150 - ₱250</div>
                </div>
                <div class="route-card" data-from="Manila" data-to="Batangas">
                    <div class="route-info">
                        <h3>Manila → Batangas</h3>
                        <p>Via SLEX • Port route</p>
                    </div>
                    <div class="route-price">₱220 - ₱350</div>
                </div>
                <div class="route-card" data-from="Manila" data-to="Naga">
                    <div class="route-info">
                        <h3>Manila → Naga</h3>
                        <p>Via SLEX & Maharlika • Bicol route</p>
                    </div>
                    <div class="route-price">₱400 - ₱600</div>
                </div>
                <div class="route-card" data-from="Manila" data-to="Baler">
                    <div class="route-info">
                        <h3>Manila → Baler</h3>
                        <p>Via Maharlika Highway • Aurora route</p>
                    </div>
                    <div class="route-price">₱350 - ₱500</div>
                </div>
            </div>
            <div class="route-grid" id="north-luzon">
                <div class="route-card" data-from="Manila" data-to="Baguio">
                    <div class="route-info">
                        <h3>Manila → Baguio</h3>
                        <p>Via NLEX • Mountain route</p>
                    </div>
                    <div class="route-price">₱350 - ₱550</div>
                </div>
                <div class="route-card" data-from="Manila" data-to="Vigan">
                    <div class="route-info">
                        <h3>Manila → Vigan</h3>
                        <p>Via NLEX & Maharlika • Ilocos route</p>
                    </div>
                    <div class="route-price">₱450 - ₱650</div>
                </div>
                <div class="route-card" data-from="Manila" data-to="Laoag">
                    <div class="route-info">
                        <h3>Manila → Laoag</h3>
                        <p>Via Maharlika Highway • Northern route</p>
                    </div>
                    <div class="route-price">₱550 - ₱750</div>
                </div>
            </div>
            <div class="route-grid" id="south-luzon">
                <div class="route-card" data-from="Manila" data-to="Tagaytay">
                    <div class="route-info">
                        <h3>Manila → Tagaytay</h3>
                        <p>Via SLEX • Scenic route</p>
                    </div>
                    <div class="route-price">₱150 - ₱250</div>
                </div>
                <div class="route-card" data-from="Manila" data-to="Naga">
                    <div class="route-info">
                        <h3>Manila → Naga</h3>
                        <p>Via SLEX & Maharlika • Bicol route</p>
                    </div>
                    <div class="route-price">₱400 - ₱600</div>
                </div>
                <div class="route-card" data-from="Manila" data-to="Baler">
                    <div class="route-info">
                        <h3>Manila → Baler</h3>
                        <p>Via Maharlika Highway • Aurora route</p>
                    </div>
                    <div class="route-price">₱350 - ₱500</div>
                </div>
                <div class="route-card" data-from="Manila" data-to="Batangas">
                    <div class="route-info">
                        <h3>Manila → Batangas</h3>
                        <p>Via SLEX • Port route</p>
                    </div>
                    <div class="route-price">₱220 - ₱350</div>
                </div>
                <div class="route-card" data-from="Manila" data-to="Legazpi">
                    <div class="route-info">
                        <h3>Manila → Legazpi</h3>
                        <p>Via SLEX & Maharlika • Bicol route</p>
                    </div>
                    <div class="route-price">₱450 - ₱650</div>
                </div>
            </div>
            <div class="route-grid" id="visayas">
                <div class="route-card" data-from="Manila" data-to="Iloilo">
                    <div class="route-info">
                        <h3>Manila → Iloilo</h3>
                        <p>Via ferry • Western Visayas</p>
                    </div>
                    <div class="route-price">₱1,350 - ₱1,950</div>
                </div>
                <div class="route-card" data-from="Manila" data-to="Bacolod">
                    <div class="route-info">
                        <h3>Manila → Bacolod</h3>
                        <p>Via ferry • Negros Island</p>
                    </div>
                    <div class="route-price">₱1,400 - ₱2,000</div>
                </div>
                <div class="route-card" data-from="Manila" data-to="Cebu">
                    <div class="route-info">
                        <h3>Manila → Cebu</h3>
                        <p>Via ferry • Island hopping</p>
                    </div>
                    <div class="route-price">₱1,200 - ₱1,800</div>
                </div>
            </div>
            <div class="route-grid" id="mindanao">
                <div class="route-card" data-from="Manila" data-to="Davao">
                    <div class="route-info">
                        <h3>Manila → Davao</h3>
                        <p>Via ferry • Southern route</p>
                    </div>
                    <div class="route-price">₱1,500 - ₱2,200</div>
                </div>
                <div class="route-card" data-from="Manila" data-to="Cagayan de Oro">
                    <div class="route-info">
                        <h3>Manila → Cagayan de Oro</h3>
                        <p>Via ferry • Northern Mindanao</p>
                    </div>
                    <div class="route-price">₱1,400 - ₱2,000</div>
                </div>
            </div>
        </div>
    </div>

    <div class="results">
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

        <?php if (!empty($search_results)): ?>
        <h2>Available Buses</h2>
        <table>
            <tr>
                <th>Route</th>
                <th>Date</th>
                <th>Time</th>
                <th>Bus Number</th>
                <th>Available Seats</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php foreach ($search_results as $bus): ?>
            <tr>
                <td><?php echo htmlspecialchars($bus['route']); ?></td>
                <td><?php echo htmlspecialchars($bus['date']); ?></td>
                <td><?php echo htmlspecialchars($bus['time']); ?></td>
                <td><?php echo htmlspecialchars($bus['bus_number']); ?></td>
                <td><?php echo $bus['available_seats']; ?></td>
                <td><?php echo $bus['price']; ?> PHP</td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="bus_id" value="<?php echo $bus['id']; ?>">
                        <button type="submit" name="book" class="book-btn">Book Now</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])): ?>
        <p>No buses found for the selected criteria.</p>
        <?php endif; ?>
    </div>

    <script>
        // Simple trip type toggle (for demo)
        document.getElementById('one-way').addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('round-trip').classList.remove('active');
            document.getElementById('return-date-group').style.display = 'none';
            document.getElementById('return-date').required = false;
        });
        document.getElementById('round-trip').addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('one-way').classList.remove('active');
            document.getElementById('return-date-group').style.display = 'block';
            document.getElementById('return-date').required = true;
        });

        // Update return date min when departure date changes
        document.getElementById('date').addEventListener('change', function() {
            var departureDate = new Date(this.value);
            if (departureDate) {
                var nextDay = new Date(departureDate);
                nextDay.setDate(departureDate.getDate() + 1);
                var minReturnDate = nextDay.toISOString().split('T')[0];
                document.getElementById('return-date').min = minReturnDate;
            }
        });

        // Route tabs functionality
        const tabBtns = document.querySelectorAll('.tab-btn');
        const routeGrids = document.querySelectorAll('.route-grid');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all tabs
                tabBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked tab
                this.classList.add('active');

                // Hide all route grids
                routeGrids.forEach(grid => grid.classList.remove('active'));

                // Show the corresponding route grid
                const region = this.getAttribute('data-region');
                document.getElementById(region).classList.add('active');
            });
        });

        // Route card click to auto-fill search form
        const routeCards = document.querySelectorAll('.route-card');
        routeCards.forEach(card => {
            card.addEventListener('click', function() {
                const from = this.getAttribute('data-from');
                const to = this.getAttribute('data-to');

                // Fill the search form
                document.getElementById('from').value = from;
                document.getElementById('to').value = to;

                // Scroll to search form
                document.querySelector('.search-card').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                // Highlight the search form briefly
                const searchCard = document.querySelector('.search-card');
                searchCard.style.boxShadow = '0 10px 30px rgba(0,123,255,0.3)';
                setTimeout(() => {
                    searchCard.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';
                }, 2000);
            });
        });
    </script>
</body>
</html>
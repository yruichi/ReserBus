<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'customer') {
    header('Location: login.php');
    exit();
}
if (!isset($_SESSION['username']) || !isset($_SESSION['email']) || !isset($_SESSION['password'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$email = $_SESSION['email'];

// List of existing emails (excluding current user's)
$existing_emails = ['customer@example.com', 'john@example.com', 'jane@example.com', 'alex@example.com'];
$existing_emails = array_diff($existing_emails, [$email]);

// Simulated booking history (in real app, from database)
$booking_history = [
    ['id' => 1, 'route' => 'City A to City B', 'date' => '2025-12-30', 'status' => 'booked'],
    ['id' => 2, 'route' => 'Edsa Carousel: PITX – Terminal, City of Dreams, DFA, Roxas Boulevard, Taft Avenue (curbside), Ayala Avenue Bus Stop, MRT-3 Buendia Station, Guadalupe Bridge, MRT-3 Ortigas Station, MRT-3 Santolan Station, Main Avenue, Cubao, Nepa Q. Mart, MRT-3 Quezon Ave. Station, MRT-3 North Ave. Station, LRT-1 Roosevelt Station, Kalinga Road, LRT-1 Balintawak Station, Bagong Barrio, Monumento', 'date' => '2025-12-25', 'status' => 'cancelled'],
    ['id' => 3, 'route' => 'City A to City D', 'date' => '2025-12-28', 'status' => 'booked']
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $new_username = $_POST['username'];
        $new_email = $_POST['email'];
        
        if (empty($new_username) || empty($new_email)) {
            $error = 'Username and email cannot be empty';
        } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format';
        } elseif (in_array($new_email, $existing_emails)) {
            $error = 'Email already in use by another user';
        } else {
            if ($new_email !== $email) {
                // Email changed, send OTP
                $otp = rand(100000, 999999);
                $_SESSION['otp'] = $otp;
                $_SESSION['new_username'] = $new_username;
                $_SESSION['new_email'] = $new_email;
                
                // Send OTP email
                $subject = 'Email Verification OTP';
                $message = "Your OTP for email verification is: $otp\n\nThis OTP will expire in 10 minutes.";
                $headers = 'From: noreply@busreservation.com';
                
                if (mail($new_email, $subject, $message, $headers)) {
                    $show_otp_modal = true;
                } else {
                    $error = 'Failed to send OTP email. Please try again.';
                }
            } else {
                // Only username changed
                $_SESSION['username'] = $new_username;
                $username = $new_username;
                $success = 'Profile updated successfully!';
            }
        }
    } elseif (isset($_POST['verify_otp'])) {
        $entered_otp = $_POST['otp'];
        if ($entered_otp == $_SESSION['otp']) {
            // OTP correct, update profile
            $_SESSION['username'] = $_SESSION['new_username'];
            $_SESSION['email'] = $_SESSION['new_email'];
            $username = $_SESSION['new_username'];
            $email = $_SESSION['new_email'];
            $success = 'Profile updated successfully!';
            // Update existing_emails
            $existing_emails = array_diff($existing_emails, [$email]);
            // Clear session
            unset($_SESSION['otp'], $_SESSION['new_username'], $_SESSION['new_email']);
        } else {
            $error = 'Invalid OTP. Please try again.';
            $show_otp_modal = true;
        }
    } elseif (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (empty($current_password) || empty($new_password)) {
            $error = 'All password fields are required';
        } elseif ($current_password !== $_SESSION['password']) {
            $error = 'Current password is incorrect';
        } elseif (strlen($new_password) < 8) {
            $error = 'New password must be at least 8 characters long';
        } elseif (!preg_match('/[A-Z]/', $new_password)) {
            $error = 'New password must contain at least one uppercase letter';
        } elseif (!preg_match('/[a-z]/', $new_password)) {
            $error = 'New password must contain at least one lowercase letter';
        } elseif (!preg_match('/[0-9]/', $new_password)) {
            $error = 'New password must contain at least one number';
        } elseif ($new_password !== $confirm_password) {
            $error = 'New passwords do not match';
        } else {
            // Simulate password update
            $_SESSION['password'] = $new_password;
            $success = 'Password updated successfully!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
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
        .profile-icon > div, .profile-icon a {
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
        .profile-icon.active > div {
            background: #007bff;
            color: white;
        }
        .profile-icon a:hover {
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
        .profile { max-width: 800px; margin: auto; }
        .section { margin-bottom: 30px; border: 1px solid #ccc; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .booked { color: green; }
        .cancelled { color: red; }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 5px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover { color: black; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <span class="red">Bus</span>Terminal.ph
        </div>
        <ul class="nav-menu">
            <li><a href="customerHomepage.php">Home</a></li>
            <li><a href="customerSearchBuses.php">Book Tickets</a></li>
            <li><a href="#">Schedules</a></li>
            <li><a href="#">Fares</a></li>
            <li><a href="#">Routes</a></li>
        </ul>
        <div class="profile-icon active">
            <div title="My Profile">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/>
                    <path d="M20 20c0-4.4-3.6-8-8-8s-8 3.6-8 8" stroke="currentColor" stroke-width="2"/>
                </svg>
            </div>
        </div>
        <div class="hamburger">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </nav>
    <div class="profile">
        <h1>My Profile</h1>
        
        <div class="section">
            <h2>Profile Details</h2>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        </div>
        
        <div class="section">
            <h2>Edit Personal Information</h2>
            <form method="post" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required><br><br>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>
                
                <input type="submit" name="update_profile" value="Update Profile">
            </form>
        </div>
        
        <div class="section">
            <h2>Change Password</h2>
            <form method="post" action="">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required><br><br>
                
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required><br><br>
                
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required><br><br>
                
                <input type="submit" name="change_password" value="Change Password">
            </form>
        </div>
        
        <div class="section">
            <h2>Booking History</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Route</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($booking_history as $booking): ?>
                <tr>
                    <td><?php echo $booking['id']; ?></td>
                    <td><?php echo htmlspecialchars($booking['route']); ?></td>
                    <td><?php echo htmlspecialchars($booking['date']); ?></td>
                    <td class="<?php echo $booking['status']; ?>"><?php echo ucfirst($booking['status']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
        <?php 
        if (isset($error)) echo "<p style='color:red;'>$error</p>"; 
        if (isset($success)) echo "<p style='color:green;'>$success</p>";
        ?>
        
        <p><a href="customerHomepage.php">Back to Homepage</a></p>
    </div>
    
    <!-- OTP Modal -->
    <div id="otpModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Email Verification</h2>
            <p>An OTP has been sent to your new email address. Please enter it below to verify.</p>
            <form method="post" action="">
                <label for="otp">OTP:</label>
                <input type="text" id="otp" name="otp" required maxlength="6"><br><br>
                <input type="submit" name="verify_otp" value="Verify OTP">
            </form>
        </div>
    </div>
    
    <script>
        // Hamburger menu toggle
        document.querySelector('.hamburger').addEventListener('click', function() {
            document.querySelector('.nav-menu').classList.toggle('active');
        });
        
        // Modal functionality
        var modal = document.getElementById('otpModal');
        var span = document.getElementsByClassName('close')[0];
        
        span.onclick = function() {
            modal.style.display = 'none';
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
        
        <?php if (isset($show_otp_modal) && $show_otp_modal) { ?>
            modal.style.display = 'block';
        <?php } ?>
    </script>
</body>
</html>
<?php
// Customer Navbar Component
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
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
</style>

<nav class="navbar">
    <div class="logo">
        <span class="red">Bus</span>Terminal.ph
    </div>
    <ul class="nav-menu">
        <li><a href="customerHomepage.php" class="<?php echo ($current_page == 'customerHomepage.php') ? 'active' : ''; ?>">Home</a></li>
        <li><a href="customerSearchBuses.php" class="<?php echo ($current_page == 'customerSearchBuses.php') ? 'active' : ''; ?>">Book Tickets</a></li>
        <li><a href="#">Schedules</a></li>
        <li><a href="#">Fares</a></li>
        <li><a href="#">Routes</a></li>
    </ul>
    <?php if ($current_page == 'customerProfile.php'): ?>
    <div class="profile-icon active">
        <div title="My Profile">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/>
                <path d="M20 20c0-4.4-3.6-8-8-8s-8 3.6-8 8" stroke="currentColor" stroke-width="2"/>
            </svg>
        </div>
    </div>
    <?php else: ?>
    <div class="profile-icon">
        <a href="customerProfile.php" title="My Profile">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/>
                <path d="M20 20c0-4.4-3.6-8-8-8s-8 3.6-8 8" stroke="currentColor" stroke-width="2"/>
            </svg>
        </a>
    </div>
    <?php endif; ?>
    <div class="hamburger">
        <div></div>
        <div></div>
        <div></div>
    </div>
</nav>

<script>
    // Hamburger menu toggle
    document.querySelector('.hamburger').addEventListener('click', function() {
        document.querySelector('.nav-menu').classList.toggle('active');
    });
</script>
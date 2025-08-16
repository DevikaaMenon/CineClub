<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: enter.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard • Cineclub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, sans-serif;
        }

        body {
            background-color: #14181c;
            color: #99AABB;
            min-height: 100vh;
        }

        /* Navbar Styles */
        .navbar {
            background-color: rgba(20, 24, 28, 0.95);
            padding: 16px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .dot-1 { background-color: #00E054; }
        .dot-2 { background-color: #40BCF4; }
        .dot-3 { background-color: #FF8000; }

        /* Main Content Styles */
        .main-content {
            padding: 40px 0;
        }

        h1 {
            color: #fff;
            font-size: 32px;
            font-weight: 400;
            margin-bottom: 30px;
        }

        .admin-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .admin-card {
            background-color: #2c3440;
            border-radius: 4px;
            padding: 24px;
            text-decoration: none;
            color: #fff;
            transition: transform 0.2s ease, background-color 0.2s ease;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .admin-card:hover {
            background-color: #3a4453;
            transform: translateY(-2px);
        }

        .card-icon {
            background-color: #00E054;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .card-content h3 {
            color: #fff;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .card-content p {
            color: #99AABB;
            font-size: 14px;
        }

        .logout-btn {
            background: none;
            border: 1px solid #99AABB;
            color: #99AABB;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            border-color: #fff;
            color: #fff;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-email {
            color: #99AABB;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="logo-section">
                    <div class="dot dot-1"></div>
                    <div class="dot dot-2"></div>
                    <div class="dot dot-3"></div>
                </div>
                <div class="navbar-user">
                    <span class="user-email"><?php echo $_SESSION['email']; ?></span>
                    <a href="logout.php" class="logout-btn">Sign out</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <h1>Admin Dashboard</h1>

            <div class="admin-cards">
                <a href="add_movie.php" class="admin-card">
                    <div class="card-icon">➕</div>
                    <div class="card-content">
                        <h3>Add Movie</h3>
                        <p>Add new movies to the database</p>
                    </div>
                </a>

                <a href="manage_movies.php" class="admin-card">
                    <div class="card-icon">🎬</div>
                    <div class="card-content">
                        <h3>Manage Movies</h3>
                        <p>Edit or remove existing movies</p>
                    </div>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
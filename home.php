<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

$search = $_GET['search'] ?? '';
if ($search) {
    $query = "SELECT id, title, poster, release_year, genre FROM movies WHERE title LIKE ? OR genre LIKE ?";
    $stmt = $conn->prepare($query);
    $search_term = "%$search%";
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $query = "SELECT id, title, poster, release_year, genre FROM movies";
    $result = mysqli_query($conn, $query);
}

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cineclub</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="imgs/logo1.jpg" type="image/x-icon" />
    <style>
        :root {
            --dark-bg: #14181c;
            --card-bg: #2c3440;
            --accent-green: #00E054;
            --text-muted: #99AABB;
            --hover-bg: #3a4453;
        }
        
        body {
            background-color: var(--dark-bg);
            color: var(--text-muted);
            font-family: Georgia, 'Times New Roman', Times, serif;
        }
        
        .navbar {
            background-color: rgba(20, 24, 28, 0.95) !important;
            transition: top 0.3s; 
        }
        
        .navbar.hide {
            top: -100px;
        }
        
        .hero-section {
            background: linear-gradient(rgba(20,24,28,0), rgba(20,24,28,1)), url('imgs/2.jpg'); /* Add your hero image path */
            background-size: cover;
            background-position: center;
            height: 75vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .feature-card {
            background-color: var(--card-bg);
            transition: background-color 0.3s ease;
        }
        
        .feature-card:hover {
            background-color: var(--hover-bg);
        }
        
        .btn-green {
            background-color: var(--accent-green);
            border: none;
            color: white;
        }
        
        .btn-green:hover {
            background-color: #00c048;
            color: white;
        }
        
        .search-input {
            background-color: var(--card-bg);
            border: none;
            color: white;
        }
        
        .search-input:focus {
            background-color: var(--hover-bg);
            color: white;
            box-shadow: none;
        }
        
        .nav-link {
            color: var(--text-muted) !important;
        }
        
        .nav-link:hover {
            color: var(--accent-green) !important;
        }
        
        .text-green {
            color: var(--accent-green) !important;
        }

        .navbar-brand img {
            height: 50px; 
            width: auto; 
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <img src="imgs/logou.jpg" alt="Cineclub Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">PROFILE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="watchlist.php">LISTS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="members.php">MEMBERS</a>
                    </li>
                </ul>
                <form method="GET" action="" class="d-flex">
                    <input 
                        class="form-control search-input me-2" 
                        type="search" 
                        name="search" 
                        placeholder="Search films..." 
                        value="<?php echo htmlspecialchars($search); ?>"
                    >
                    <button type="submit" class="btn btn-green">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 text-white mb-4">Track films you've watched.</h1>
            <h2 class="display-5 text-white mb-5">Save those you want to see.</h2>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h3 class="text-uppercase mb-4" style="color: #567;">Features</h3>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card p-4 rounded">
                        <i class="fas fa-eye text-green mb-3"></i>
                        <h4 class="text-white">Track Films</h4>
                        <p>Keep track of every film you've ever watched.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card p-4 rounded">
                        <i class="fas fa-heart text-green mb-3"></i>
                        <h4 class="text-white">Rate & Review</h4>
                        <p>Share your thoughts on films with the community.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card p-4 rounded">
                        <i class="fas fa-list text-green mb-3"></i>
                        <h4 class="text-white">Create Lists</h4>
                        <p>Make and share lists of your favorite films.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h3 class="text-uppercase mb-4" style="color: #567;">Latest Movies</h3>
            <div class="row g-4">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($movie = $result->fetch_assoc()): ?>
                        <div class="col-md-4 col-lg-3">
                            <a href="reviews.php?movie_id=<?php echo $movie['id']; ?>" class="text-decoration-none">
                                <div class="movie-card rounded overflow-hidden">
                                    <img src="<?php echo htmlspecialchars($movie['poster']); ?>" 
                                         onerror="this.onerror=null; this.src='posters/default.jpg';" 
                                         class="card-img-top" 
                                         alt="Movie Poster">
                                    <div class="card-body">
                                        <h5 class="text-white"><?php echo htmlspecialchars($movie['title']); ?></h5>
                                        <p class="small mb-0"><?php echo htmlspecialchars($movie['release_year']); ?> • <?php echo htmlspecialchars($movie['genre']); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No movies found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="py-4 mt-5" style="background-color: var(--card-bg);">
        <div class="container">
            <p class="mb-0">© 2024 Cineclub. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        let prevScrollPos = window.pageYOffset;
        const navbar = document.querySelector('.navbar');

        window.onscroll = function() {
            const currentScrollPos = window.pageYOffset;
            if (prevScrollPos > currentScrollPos) {
                navbar.classList.remove('hide'); // Show navbar when scrolling up
            } else {
                navbar.classList.add('hide'); // Hide navbar when scrolling down
            }
            prevScrollPos = currentScrollPos;
        };
    </script>
</body>
</html>
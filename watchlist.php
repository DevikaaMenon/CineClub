<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: enter.php");
    exit();
}

include 'db.php';

$user_email = $_SESSION['email'];


$query = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

$user_id = $user['id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_watchlist'])) {
    $watchlist_name = $_POST['watchlist_name'];

    $query = "INSERT INTO watchlists (user_id, name) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $user_id, $watchlist_name);
    $stmt->execute();

    header("Location: watchlist.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_watchlist'])) {
    $watchlist_id = $_POST['watchlist_id'];
    $movie_id = $_POST['movie_id'];

    $query = "INSERT INTO watchlist_movies (watchlist_id, movie_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $watchlist_id, $movie_id);
    $stmt->execute();

    header("Location: watchlist.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_movie'])) {
    $watchlist_movie_id = $_POST['watchlist_movie_id'];

    $query = "DELETE FROM watchlist_movies WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $watchlist_movie_id);
    $stmt->execute();

    header("Location: watchlist.php");
    exit();
}

$query = "SELECT * FROM watchlists WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$watchlists = $stmt->get_result();

$query = "SELECT id, title FROM movies";
$movies_result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist - Cineclub</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #14181c;
            color: #99aabb;
            padding: 20px;
        }
        .watchlist-card {
            background-color: #2c3440;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .watchlist-card h3 {
            color: #00e054;
        }
        .movie-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #394452;
        }
        .movie-item:last-child {
            border-bottom: none;
        }
        .movie-item p {
            margin: 0;
        }
        .delete-btn {
            color: #ff6b6b;
            cursor: pointer;
        }
        .delete-btn:hover {
            color: #ff3b3b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Watchlists</h1>

        <div class="watchlist-card">
            <h3>Create a New Watchlist</h3>
            <form method="POST" action="">
                <input type="text" name="watchlist_name" placeholder="Watchlist Name" required>
                <button type="submit" name="create_watchlist" class="btn btn-danger">Create</button>
            </form>
        </div>

        <div class="watchlist-card">
            <h3>Add Movie to Watchlist</h3>
            <form method="POST" action="">
                <select name="watchlist_id" required>
                    <option value="">Select a Watchlist</option>
                    <?php while ($watchlist = $watchlists->fetch_assoc()): ?>
                        <option value="<?php echo $watchlist['id']; ?>"><?php echo htmlspecialchars($watchlist['name']); ?></option>
                    <?php endwhile; ?>
                </select>
                <select name="movie_id" required>
                    <option value="">Select a Movie</option>
                    <?php while ($movie = $movies_result->fetch_assoc()): ?>
                        <option value="<?php echo $movie['id']; ?>"><?php echo htmlspecialchars($movie['title']); ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" name="add_to_watchlist" class="btn btn-danger">Add Movie</button>
            </form>
        </div>

        <?php
        $watchlists->data_seek(0); 
        while ($watchlist = $watchlists->fetch_assoc()): 
            $watchlist_id = $watchlist['id'];
            $query = "SELECT watchlist_movies.id, movies.title 
                      FROM watchlist_movies 
                      JOIN movies ON watchlist_movies.movie_id = movies.id 
                      WHERE watchlist_movies.watchlist_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $watchlist_id);
            $stmt->execute();
            $movies = $stmt->get_result();
        ?>
            <div class="watchlist-card">
                <h3><?php echo htmlspecialchars($watchlist['name']); ?></h3>
                <?php if ($movies->num_rows > 0): ?>
                    <?php while ($movie = $movies->fetch_assoc()): ?>
                        <div class="movie-item">
                            <p><?php echo htmlspecialchars($movie['title']); ?></p>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="watchlist_movie_id" value="<?php echo $movie['id']; ?>">
                                <button type="submit" name="delete_movie" class="delete-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No movies in this watchlist yet.</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>



<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: enter.php");
    exit();
}

include 'db.php';

$movie_id = $_GET['movie_id'] ?? die("Movie ID not provided.");
$user_email = $_SESSION['email'];


$query = "SELECT * FROM movies WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if (!$movie) {
    die("Movie not found.");
}


$query = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    $query = "INSERT INTO reviews (user_id, movie_id, rating, review) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiis", $user['id'], $movie_id, $rating, $review);
    $stmt->execute();

    
    header("Location: reviews.php?movie_id=$movie_id");
    exit();
}


$query = "SELECT reviews.*, users.name 
          FROM reviews 
          JOIN users ON reviews.user_id = users.id 
          WHERE reviews.movie_id = ? 
          ORDER BY reviews.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$reviews = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - <?php echo htmlspecialchars($movie['title']); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #14181c;
            color: #99aabb;
            padding: 20px;
        }
        .movie-hero {
            background-color: #2c3440;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .movie-poster {
            width: 200px;
            height: 300px;
            border-radius: 10px;
            margin-right: 20px;
            object-fit: cover;
        }
        .movie-details {
            flex: 1;
        }
        .movie-title {
            color: #00e054;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .movie-meta {
            color: #99aabb;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }
        .movie-description {
            color: #dee5ec;
            font-size: 1rem;
            line-height: 1.6;
        }
        .review-form {
            background-color: #2c3440;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .review-form h2 {
            color: #00e054;
            margin-bottom: 15px;
        }
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-start;
            margin-bottom: 15px;
        }
        .star-rating input {
            display: none;
        }
        .star-rating label {
            font-size: 28px;
            color: #ccc;
            cursor: pointer;
            margin-right: 5px;
        }
        .star-rating input:checked ~ label {
            color: #ffcc00;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffcc00;
        }
        .review-textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #394452;
            color: white;
            margin-bottom: 15px;
            resize: vertical;
            min-height: 100px;
        }
        .review-textarea:focus {
            outline: none;
            box-shadow: 0 0 0 2px #00e054;
        }
        .review-card {
            background-color: #2c3440;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .review-card h4 {
            color: #00e054;
            margin-bottom: 10px;
        }
        .review-rating {
            color: #ffcc00;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        .review-text {
            color: #dee5ec;
            line-height: 1.6;
        }
        .review-date {
            color: #778899;
            font-size: 0.9rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="movie-hero">
            <img src="<?php echo htmlspecialchars($movie['poster']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>" class="movie-poster">
            <div class="movie-details">
                <h1 class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></h1>
                <div class="movie-meta">
                    <?php echo htmlspecialchars($movie['release_year']); ?> • <?php echo htmlspecialchars($movie['genre']); ?>
                </div>
                <p class="movie-description"><?php echo htmlspecialchars($movie['description']); ?></p>
            </div>
        </div>

        <div class="review-form">
            <h2>Add Your Review</h2>
            <form method="POST" action="">
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" required>
                    <label for="star5">★</label>
                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4">★</label>
                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3">★</label>
                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2">★</label>
                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1">★</label>
                </div>
                <textarea name="review" class="review-textarea" placeholder="Write your review..." required></textarea>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        </div>

        <h2>All Reviews</h2>
        <?php if ($reviews->num_rows > 0): ?>
            <?php while ($review = $reviews->fetch_assoc()): ?>
                <div class="review-card">
                    <h4><?php echo htmlspecialchars($review['name']); ?></h4>
                    <div class="review-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $review['rating']): ?>
                                <i class="fas fa-star"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <p class="review-text"><?php echo nl2br(htmlspecialchars($review['review'])); ?></p>
                    <p class="review-date">Reviewed on <?php echo $review['created_at']; ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reviews yet. Be the first to review this movie!</p>
        <?php endif; ?>
    </div>
</body>
</html>
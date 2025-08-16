<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: enter.php");
    exit();
}

include 'db.php';

$user_email = $_SESSION['email'];

// Fetch user details
$query = "SELECT id, name, email, role FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Fetch user reviews
$query = "SELECT reviews.*, movies.title 
          FROM reviews 
          JOIN movies ON reviews.movie_id = movies.id 
          WHERE reviews.user_id = ? 
          ORDER BY reviews.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$reviews = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - <?php echo htmlspecialchars($user['name']); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #14181c;
            color: #99aabb;
            padding: 20px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .review {
            background-color: #2c3440;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .review h4 {
            color: #00e054;
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="profile-header">
    <img src="imgs/user.png" alt="Default Profile Picture" class="profile-picture">
        <h1><?php echo htmlspecialchars($user['name']); ?></h1>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
    </div>

    <!-- Role-Specific Content -->
    <?php if ($user['role'] === 'admin'): ?>
        <h2>Admin Dashboard</h2>
        <p>Welcome, Admin! You have access to special features.</p>
    <?php else: ?>
        <h2>User Dashboard</h2>
        <p>Welcome, User! Explore your profile.</p>
    <?php endif; ?>

    <!-- Reviews Section -->
    <h2>My Reviews</h2>
    <?php if ($reviews->num_rows > 0): ?>
        <?php while ($review = $reviews->fetch_assoc()): ?>
            <div class="review">
                <h4><?php echo htmlspecialchars($review['title']); ?></h4>
                <p><strong>Rating:</strong> <?php echo $review['rating']; ?> stars</p>
                <p><?php echo htmlspecialchars($review['review']); ?></p>
                <p><small>Reviewed on <?php echo $review['created_at']; ?></small></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You haven't reviewed any movies yet.</p>
    <?php endif; ?>
</body>
</html>
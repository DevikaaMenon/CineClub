<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: enter.php");
    exit();
}

include 'db.php';

$search = $_GET['search'] ?? '';
$query = "SELECT id, name, email FROM users WHERE deleted = 0 AND (name LIKE ? OR email LIKE ?) ORDER BY name ASC";
$stmt = $conn->prepare($query);
$search_term = "%$search%";
$stmt->bind_param("ss", $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members - Cineclub</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #14181c;
            color: #99aabb;
            padding: 20px;
        }
        .member-card {
            background-color: #2c3440;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .member-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        .member-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 20px;
            object-fit: cover;
            background-color: #394452; 
            display: flex;
            align-items: center;
            justify-content: center;
            color: #99aabb; 
            font-size: 24px;
        }
        .member-details {
            flex: 1;
        }
        .member-name {
            color: #00e054;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }
        .member-email {
            color: #99aabb;
            font-size: 0.9rem;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-input {
            background-color: #2c3440;
            border: none;
            color: white;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
        }
        .search-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #00e054;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Members</h1>

        <form method="GET" action="members.php" class="search-bar">
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                placeholder="Search members by name or email..." 
                value="<?php echo htmlspecialchars($search); ?>"
            >
        </form>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($member = $result->fetch_assoc()): ?>
                <div class="member-card">
                    <div class="member-avatar">
                        <?php echo strtoupper(substr($member['name'], 0, 1)); ?>
                    </div>
                    <div class="member-details">
                        <div class="member-name"><?php echo htmlspecialchars($member['name']); ?></div>
                        <div class="member-email"><?php echo htmlspecialchars($member['email']); ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No members found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
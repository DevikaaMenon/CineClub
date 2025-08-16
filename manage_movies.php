<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: enter.php");
    exit();
}

include 'db.php';

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM movies WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<div class='alert success'>✅ Movie deleted successfully!</div>";
    } else {
        echo "<div class='alert error'>❌ Error deleting movie.</div>";
    }
    $stmt->close();
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $release_year = $_POST['release_year'];

    $query = "UPDATE movies SET title = ?, genre = ?, release_year = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $title, $genre, $release_year, $id);
    
    if ($stmt->execute()) {
        echo "<div class='alert success'>✅ Movie updated successfully!</div>";
    } else {
        echo "<div class='alert error'>❌ Error updating movie.</div>";
    }
    $stmt->close();
}

$result = mysqli_query($conn, "SELECT * FROM movies");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Movies</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #14181c;
            color: #99aabb;
            padding: 20px;
        }

        h2 {
            color: #00e054;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #2c3440;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #3a4453;
        }

        th {
            background-color: #1c2127;
            color: #00e054;
            font-weight: bold;
        }

        tr:hover {
            background-color: #3a4453;
        }

        img {
            border-radius: 5px;
            width: 50px;
            height: auto;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #3a4453;
            border-radius: 5px;
            background-color: #2c3440;
            color: #99aabb;
        }

        button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        button[name="edit"] {
            background-color: #00e054;
            color: white;
        }

        button[name="edit"]:hover {
            background-color: #00c048;
        }

        button[name="delete"] {
            background-color: #ff4d4d;
            color: white;
        }

        button[name="delete"]:hover {
            background-color: #e60000;
        }

        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert.success {
            background-color: #00e054;
            color: white;
        }

        .alert.error {
            background-color: #ff4d4d;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Movie List</h2>
    <table>
        <tr>
            <th>Poster</th>
            <th>Title</th>
            <th>Genre</th>
            <th>Year</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><img src="<?= $row['poster'] ?>" alt="<?= $row['title'] ?>"></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="title" value="<?= $row['title'] ?>">
            </td>
            <td>
                    <input type="text" name="genre" value="<?= $row['genre'] ?>">
            </td>
            <td>
                    <input type="number" name="release_year" value="<?= $row['release_year'] ?>">
            </td>
            <td>
                    <button type="submit" name="edit">💾 Save</button>
                </form>
                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this movie?');">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete">❌ Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
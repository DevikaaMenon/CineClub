<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: enter.php");
    exit();
}

include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Movie</title>
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

        form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #2c3440;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        input[type="text"], input[type="number"], textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #3a4453;
            border-radius: 5px;
            background-color: #1c2127;
            color: #99aabb;
            font-family: 'Georgia', serif;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        input[type="file"] {
            padding: 5px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #00e054;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #00c048;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #00e054;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Add New Movie</h2>
    <form action="upload_movie.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Movie Title</label>
            <input type="text" name="title" placeholder="Enter movie title" required>
        </div>
        <div class="form-group">
            <label for="genre">Genre</label>
            <input type="text" name="genre" placeholder="Enter genre">
        </div>
        <div class="form-group">
            <label for="release_year">Release Year</label>
            <input type="number" name="release_year" placeholder="Enter release year">
        </div>
        <div class="form-group">
            <label for="description">Movie Description</label>
            <textarea name="description" placeholder="Enter movie description"></textarea>
        </div>
        <div class="form-group">
            <label for="poster">Movie Poster</label>
            <input type="file" name="poster" accept="image/*" required>
        </div>
        <button type="submit" name="submit">Upload Movie</button>
    </form>
</body>
</html>
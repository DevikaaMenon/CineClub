<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: enter.php");
    exit();
}

include 'db.php'; 

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $release_year = $_POST['release_year'];
    $description = $_POST['description'];

    
    $target_dir = "posters/";
    $poster_name = basename($_FILES["poster"]["name"]);
    $target_file = $target_dir . time() . "_" . $poster_name; 

    
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = array("jpg", "jpeg", "png", "gif");

    if (!in_array($imageFileType, $allowed_types)) {
        die("Invalid file type. Only JPG, JPEG, PNG & GIF are allowed.");
    }

    if (move_uploaded_file($_FILES["poster"]["tmp_name"], $target_file)) {
        
        $sql = "INSERT INTO movies (title, genre, release_year, description, poster) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiss", $title, $genre, $release_year, $description, $target_file);

        if ($stmt->execute()) {
            echo "Movie added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Failed to upload image.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
         body {
            font-family: 'Georgia', serif;
            background-color: #14181c;
            color:white;
            padding: 20px;
        }
    </style>
</head>
<body>
    
</body>
</html>

<?php
session_start(); // Start the session

// Database connection
$host = 'localhost'; // Change if your database is hosted elsewhere
$db = 'db_waris'; // Replace with your database name
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error ) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a specific response is requested
if (isset($_GET['view'])) {
    $index = intval($_GET['view']);
    $result = $conn->query("SELECT * FROM `user` WHERE id = $index");

    if ($result->num_rows > 0) {
        $response = $result->fetch_assoc();
        // Store the response data in session variables
        $_SESSION['name'] = $response['name'];
        $_SESSION['email'] = $response['email'];
        $_SESSION['feedback'] = $response['feedback'];
        $_SESSION['gender'] = $response['gender'];
        $_SESSION['membership'] = $response['membership'];
        $_SESSION['age'] = $response['age'];
        $_SESSION['country'] = $response['country'];
        $_SESSION['employment'] = $response['employment'];
        $_SESSION['education'] = $response['education'];
        $_SESSION['preferredContact'] = $response['preferredContact'];
        $_SESSION['interests'] = $response['interests'];
        $_SESSION['internetUsage'] = $response['internetUsage'];
        $_SESSION['onlineShopping'] = $response['onlineShopping'];
        $_SESSION['satisfaction'] = $response['satisfaction'];
        $_SESSION['favoriteColor'] = $response['favoriteColor'];
    } else {
        // Redirect to view page if the index is invalid
        header("Location: view.php");
        exit();
    }
} else {
    // Redirect to view page if no specific response is requested
    header("Location: view.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <title>Form Data Received</title>
    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .data-item {
            width: 30%; /* Adjust width to fit three items in a row */
            margin-bottom: 20px; /* Space between rows */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: Add shadow for better visibility */
            padding: 10px; /* Optional: Add padding for aesthetics */
            border: 1px solid #ccc; /* Optional: Add border for separation */
            border-radius: 5px; /* Optional: Rounded corners */
        }

        h1 {
            margin-left: 250px;
        }

        .sidebar h2 {
            text-align: center; /* Center text within the sidebar */
            align-items: center;
            margin-top: 2px;
        }

        .image-container {
            display: flex; /* Use flexbox on the container */
            justify-content: center; /* Center the image horizontally */
            align-items: center; /* Center the image vertically (if the container has a height) */
        }

        img {
            width: 50px; /* Set a specific width */
            height: auto; /* Maintain aspect ratio */
            max-width: 100%; /* Ensure the image is responsive */
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="image-container">
        <img src="logo-removebg-preview.png" alt="Description of image">
    </div>
    <h2>WARIS</h2>
    <a href="index.php"><i class="fa-solid fa-square-poll-vertical"></i> Take Survey</a>
    <a href="view.php"><i class="fa-solid fa-eye"></i> View All Responses</a>
</div>

<div>
    <div>
        <br>
        <h1>Form Data Received</h1>
    </div>

    <div class ="container">

    <div class="data-item">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Feedback:</strong> <?php echo htmlspecialchars($_SESSION['feedback']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($_SESSION['gender']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Membership:</strong> <?php echo htmlspecialchars($_SESSION['membership']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Age:</strong> <?php echo htmlspecialchars($_SESSION['age']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Country:</strong> <?php echo htmlspecialchars($_SESSION['country']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Employment Status:</strong> <?php echo htmlspecialchars($_SESSION['employment']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Education Level:</strong> <?php echo htmlspecialchars($_SESSION['education']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Preferred Contact Method:</strong> <?php echo htmlspecialchars($_SESSION['preferredContact']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Interests:</strong> <?php echo htmlspecialchars($_SESSION['interests']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Internet Usage:</strong> <?php echo htmlspecialchars($_SESSION['internetUsage']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Online Shopping:</strong> <?php echo htmlspecialchars($_SESSION['onlineShopping']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Satisfaction:</strong> <?php echo htmlspecialchars($_SESSION['satisfaction']); ?></p>
    </div>
    <div class="data-item">
        <p><strong>Favorite Color:</strong> <?php echo htmlspecialchars($_SESSION['favoriteColor']); ?></p>
    </div>

    <div class="button-view">
        <br>
        <a href="view.php" class="button-link">Back to Responses</a>
    </div>

    </div>

</div>

</body>
</html>
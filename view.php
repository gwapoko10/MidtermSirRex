<?php
// Database connection
$host = 'localhost'; // Change if your database is hosted elsewhere
$db = 'db_waris'; // Replace with your database name
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all responses
$responses = [];
$result = $conn->query("SELECT * FROM `user`");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $responses[] = $row;
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $idToDelete = intval($_GET['delete']);
    $conn->query("DELETE FROM `user` WHERE id = $idToDelete"); // Assuming 'id' is the primary key
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Survey Responses</title>
</head>
<body>
    <style>
           .sidebar h2{
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


<div class="sidebar">  
        <div class="image-container">
            <img src="logo-removebg-preview.png" alt="Description of image">
        </div>

        <h2>WARIS</h2>

        <a href="index.php"><i class="fa-solid fa-square-poll-vertical"></i> Take Survey</a>
        <a href="view.php"><i class="fa-solid fa-eye"></i> View All Responses</a>
</div>


<div class="container">
    <h1>Survey Responses</h1>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Feedback</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($responses as $index => $response): ?>
        <tr>
            <td><?php echo htmlspecialchars($response['id']); ?></td>
            <td><?php echo htmlspecialchars($response['name']); ?></td>
            <td><?php echo htmlspecialchars($response['email']); ?></td>
            <td><?php echo htmlspecialchars($response['feedback']); ?></td>
            <td>
                <a href=" display.php?view=<?php echo htmlspecialchars($response['id']); ?>" class="button-link view-edit">View</a>
                <a href="edit.php?edit=<?php echo htmlspecialchars($response['id']); ?>" class="button-link view-edit">Edit</a>
                <a href="view.php?delete=<?php echo htmlspecialchars($response['id']); ?>" onclick="return confirm('Are you sure you want to delete this response?');" class="button-link delete">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="button-view">
        <a href="index.php" class="button-link">Back to Survey</a>
    </div>
</div>

</body>
</html>
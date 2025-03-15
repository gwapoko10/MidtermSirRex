<?php
// Function to read responses from the JSON file
function readResponses($filePath) {
    if (file_exists($filePath)) {
        return json_decode(file_get_contents($filePath), true);
    }
    return [];
}

// Function to save responses to the JSON file
function saveResponses($filePath, $responses) {
    file_put_contents($filePath, json_encode($responses, JSON_PRETTY_PRINT));
}

// Handle delete request
if (isset($_GET['delete'])) {
    $idToDelete = intval($_GET['delete']);
    $filePath = 'responses.json';
    $responses = readResponses($filePath);
    
    if (isset($responses[$idToDelete])) {
        unset($responses[$idToDelete]);
        saveResponses($filePath, array_values($responses)); // Re-index the array
    }
}

// Read all responses
$responses = readResponses('responses.json');
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
    <title>Survey Responses</title>
</head>
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
<body>

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
            <td><?php echo $index + 1; ?></td> <!-- Add 1 to the index for display -->
            <td><?php echo htmlspecialchars($response['name']); ?></td>
            <td><?php echo htmlspecialchars($response['email']); ?></td>
            <td><?php echo htmlspecialchars($response['feedback']); ?></td>
            <td>
                <a href="display.php?view=<?php echo $index; ?>" class="button-link view-edit">View</a>
                <a href="edit.php?edit=<?php echo $index; ?>" class="button-link view-edit">Edit</a>
                <a href="view.php?delete=<?php echo $index; ?>" onclick="return confirm('Are you sure you want to delete this response?');" class="button-link delete">Delete</a>
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
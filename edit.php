<?php
session_start(); // Start the session

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

// Read all responses
$filePath = 'responses.json';
$responses = readResponses($filePath);

// Check if an edit request is made
if (isset($_GET['edit'])) {
    $index = intval($_GET['edit']);
    if (isset($responses[$index])) {
        $responseToEdit = $responses[$index];
    } else {
        header("Location: view.php"); // Redirect if the index is invalid
        exit();
    }
}

// Handle form submission for editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update the response data
    $responses[$index] = [
        'name' => htmlspecialchars(trim($_POST['name'])),
        'email' => htmlspecialchars(trim($_POST['email'])),
        'feedback' => htmlspecialchars(trim($_POST['feedback'])),
        'gender' => htmlspecialchars(trim($_POST['gender'])),
        'membership' => htmlspecialchars(trim($_POST['membership'])),
        'age' => htmlspecialchars(trim($_POST['age'])),
        'country' => htmlspecialchars(trim($_POST['country'])),
        'employment' => htmlspecialchars(trim($_POST['employment'])),
        'education' => htmlspecialchars(trim($_POST['education'])),
        'preferredContact' => htmlspecialchars(trim($_POST['preferredContact'])),
        'interests' => isset($_POST['interests']) ? implode(", ", $_POST['interests']) : '',
        'internetUsage' => htmlspecialchars(trim($_POST['internetUsage'])),
        'onlineShopping' => htmlspecialchars(trim($_POST['onlineShopping'])),
        'satisfaction' => htmlspecialchars(trim($_POST['satisfaction'])),
        'favoriteColor' => isset($_POST['favoriteColor']) ? implode(", ", $_POST['favoriteColor']) : '',
    ];

    // Save the updated responses
    saveResponses($filePath, $responses);
    header("Location: view.php"); // Redirect to view page after saving
    exit();
}
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
    <title>Edit Response</title>
</head>

    <style>
        .choice {
            display: flex; /* Use flexbox for layout */
        }

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
    <h1>Edit Response</h1>

    <form action="" method="POST">
        <div class="question-container">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($responseToEdit['name']); ?>" >
        </div>
        <div class="question-container">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($responseToEdit['email']); ?>" >
        </div>
        <div class="question-container">
            <label for="feedback">Feedback:</label>
            <textarea name="feedback" id="feedback" ><?php echo htmlspecialchars($responseToEdit['feedback']); ?></textarea>
        </div>
        <div class="question-container">
            <label>Gender:</label>
                    <input type="radio" id="male" name="gender" value="Male" <?php echo ($responseToEdit['gender'] == 'Male') ? 'checked' : ''; ?>>Male
                    <input type="radio" id="female" name="gender" value="Female" <?php echo ($responseToEdit['gender'] == 'Female') ? 'checked' : ''; ?>>Female
            <?php if (isset($errors['gender'])) echo '<div class="error">' . $errors['gender'] . '</div>'; ?>
        </div>
        <div class="question-container">
            <label for="membership">Membership:</label>
            <input type="text" name="membership" id="membership" value="<?php echo htmlspecialchars($responseToEdit['membership']); ?>" >
        </div>
        <div class="question-container">
            <label for="age">Age:</label>
            <input type="number" name="age" id="age" value="<?php echo htmlspecialchars($responseToEdit['age']); ?>" >
        </div>
        <div class="question-container">
            <label for="country">Country:</label>
            <input type="text" name="country" id="country" value="<?php echo htmlspecialchars($responseToEdit['country']); ?>" >
        </div>
        <div class="question-container">
            <label for="employment">Employment Status:</label>
            <input type="text" name="employment" id="employment" value="<?php echo htmlspecialchars($responseToEdit['employment']); ?>" >
        </div>
        <div class="question-container">
            <label for="education">Education Level:</label>
            <input type="text" name="education" id="education" value="<?php echo htmlspecialchars($responseToEdit['education']); ?>" >
        </div>
        <div class="question-container">
            <label for="preferredContact">Preferred Contact Method:</label>
            <input type="text" name="preferredContact" id="preferredContact" value="<?php echo htmlspecialchars($responseToEdit['preferredContact']); ?>" >
        </div>
        <div class="question-container">
            <label for="interests">Interests:</label>
            <input type="text" name="interests[]" id="interests" value="<?php echo htmlspecialchars($responseToEdit['interests']); ?>">
        </div>
        <div class="question-container">
            <label for="internetUsage">Internet Usage:</label>
            <input type="text" name="internetUsage" id="internetUsage" value="<?php echo htmlspecialchars($responseToEdit['internetUsage']); ?>" >
        </div>
        <div class="question-container">
            <label for="onlineShopping">Online Shopping:</label>
            <input type="text" name="onlineShopping" id="onlineShopping" value="<?php echo htmlspecialchars($responseToEdit['onlineShopping']); ?>" >
        </div>
        <div class="question-container">
            <label for="satisfaction">Satisfaction:</label>
            <input type="text" name="satisfaction" id="satisfaction" value="<?php echo htmlspecialchars($responseToEdit['satisfaction']); ?>" >
        </div>
        <div class="question-container">
            <label for="favoriteColor">Favorite Color:</label>
            <input type="text" name="favoriteColor[]" id="favoriteColor" value="<?php echo htmlspecialchars($responseToEdit['favoriteColor']); ?>">
        </div>
        <div class="button-container">
            <input type="submit" value="Update">
        </div>
    </form>

    <div class="button-view">
        <a href="view.php" class="button-link">Cancel</a>
    </div>
</div>

</body>
</html>
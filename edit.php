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
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an edit request is made
if (isset($_GET['edit'])) {
    $index = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM `user` WHERE id = $index");

    if ($result->num_rows > 0) {
        $responseToEdit = $result->fetch_assoc();
    } else {
        header("Location: view.php"); // Redirect if the index is invalid
        exit();
    }
}

// Handle form submission for editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables
    $name = $email = $feedback = $gender = $membership = $age = $country = $employment = $education = $preferredContact = $interests = $internetUsage = $onlineShopping = $satisfaction = $favoriteColor = "";
    $errors = [];

    // Validate and sanitize the form data

    // Name
    if (empty(trim($_POST['name']))) {
        $errors['name'] = "Name is required and cannot be just whitespace.";
    } else {
        $name = htmlspecialchars(trim($_POST['name']));
        if (preg_match('/[0-9]/', $name)) {
            $errors['name'] = "Name cannot contain numbers.";
        }
    }

    // Email validation
    if (empty(trim($_POST['email']))) {
        $errors['email'] = "Email is required and cannot be just whitespace.";
    } elseif (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    } else {
        $email = htmlspecialchars(trim($_POST['email']));
    }

    // Feedback
    if (empty(trim($_POST['feedback']))) {
        $errors['feedback'] = "Feedback is required and cannot be just whitespace.";
    } else {
        $feedback = htmlspecialchars(trim($_POST['feedback']));
    }

    // Gender
    if (empty(trim($_POST['gender']))) {
        $errors['gender'] = "Gender is required.";
    } else {
        $gender = htmlspecialchars(trim($_POST['gender']));
    }

    // Membership
    if (empty(trim($_POST['membership']))) {
        $errors['membership'] = "Membership status is required.";
    } else {
        $membership = htmlspecialchars(trim($_POST['membership']));
    }

    // Age
    if (empty(trim($_POST['age']))) {
        $errors['age'] = "Age is required.";
    } else {
        $age = htmlspecialchars(trim($_POST['age']));
    }

    // Country
    if (empty(trim($_POST['country']))) {
        $errors['country'] = "Country is required.";
    } else {
        $country = htmlspecialchars(trim($_POST['country']));
    }

    // Employment status
    if (empty(trim($_POST['employment']))) {
        $errors['employment'] = "Employment status is required.";
    } else {
        $employment = htmlspecialchars(trim($_POST['employment']));
    }

    // Education
    if (empty(trim($_POST['education']))) {
        $errors['education'] = "Education level is required.";
    } else {
        $education = htmlspecialchars(trim($_POST['education']));
    }

    // Preferred contact method
    if (empty(trim($_POST['preferredContact']))) {
        $errors['preferredContact'] = "Preferred contact method is required.";
    } else {
        $preferredContact = htmlspecialchars(trim($_POST['preferredContact']));
    }

    // Interests
    if (isset($_POST['interests'])) {
        $interests = implode(", ", $_POST['interests']);
    }

    // Internet usage
    if (empty(trim($_POST['internetUsage']))) {
        $errors['internetUsage'] = "Internet usage frequency is required.";
    } else {
        $internetUsage = htmlspecialchars(trim($_POST['internetUsage']));
    }
    
    // Online shopping preference
    if (empty(trim($_POST['onlineShopping']))) {
        $errors['onlineShopping'] = "Online shopping preference is required.";
    } else {
        $onlineShopping = htmlspecialchars(trim($_POST['onlineShopping']));
    }

    // Satisfaction
    if (empty(trim ($_POST['satisfaction']))) {
        $errors['satisfaction'] = "Satisfaction level is required.";
    } else {
        $satisfaction = htmlspecialchars(trim($_POST['satisfaction']));
    }

    // Favorite Color
    if (isset($_POST['favoriteColor'])) {
        $favoriteColor = implode(", ", $_POST['favoriteColor']);
    } else {
        $errors['favoriteColor'] = "At least one favorite color is required.";
    }

    // Check if there are any errors
    if (empty($errors)) {
        // Prepare and bind
        $stmt = $conn->prepare("UPDATE `user` SET name=?, email=?, feedback=?, gender=?, membership=?, age=?, country=?, employment=?, education=?, preferredContact=?, interests=?, internetUsage=?, onlineShopping=?, satisfaction=?, favoriteColor=? WHERE id=?");

        // Bind parameters
        $stmt->bind_param("sssssisssssssssi", 
            $name,
            $email,
            $feedback,
            $gender,
            $membership,
            $age,
            $country,
            $employment,
            $education,
            $preferredContact,
            $interests,
            $internetUsage,
            $onlineShopping,
            $satisfaction,
            $favoriteColor,
            $index
        );

        if ($stmt->execute()) {
            header("Location: view.php"); // Redirect to view page after saving
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
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
    <style>
        .choice {
            display: flex; /* Use flexbox for layout */
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

        .error {
            color: red; /* Style for error messages */
            font-size: 0.9em; /* Slightly smaller font size */
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="image-container">
        <img src="logo-removebg-preview.png" alt="Description of image"> <!-- Ensure the image source is correct -->
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
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($responseToEdit['name']); ?>" required>
            <?php if (isset($errors['name'])) echo "<p class='error'>{$errors['name']}</p>"; ?>
            </div>
        <div class="question-container">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($responseToEdit['email']); ?>" required>
            <?php if (isset($errors['email'])) echo "<p class='error'>{$errors['email']}</p>"; ?>
        </div>
        <div class="question-container">
            <label for="feedback">Feedback:</label>
            <textarea name="feedback" id="feedback" required><?php echo htmlspecialchars($responseToEdit['feedback']); ?></textarea>
            <?php if (isset($errors['feedback'])) echo "<p class='error'>{$errors['feedback']}</p>"; ?>
        </div>
        <div class="question-container">
            <label>Gender:</label>
            <input type="radio" id="male" name="gender" value="Male" <?php echo ($responseToEdit['gender'] == 'Male') ? 'checked' : ''; ?>>Male
            <input type="radio" id="female" name="gender" value="Female" <?php echo ($responseToEdit['gender'] == 'Female') ? 'checked' : ''; ?>>Female
            <?php if (isset($errors['gender'])) echo "<p class='error'>{$errors['gender']}</p>"; ?>
        </div>
        <div class="question-container">
            <label for="membership">Membership:</label>
            <input type="text" name="membership" id="membership" value="<?php echo htmlspecialchars($responseToEdit['membership']); ?>" required>
            <?php if (isset($errors['membership'])) echo "<p class='error'>{$errors['membership']}</p>"; ?>
        </div>
        <div class="question-container">
            <label for="age">Age:</label>
            <input type="number" name="age" id="age" value="<?php echo htmlspecialchars($responseToEdit['age']); ?>" required>
            <?php if (isset($errors['age'])) echo "<p class='error'>{$errors['age']}</p>"; ?>
        </div>
        <div class="question-container">
            <label for="country">Country:</label>
            <input type="text" name="country" id="country" value="<?php echo htmlspecialchars($responseToEdit['country']); ?>" required>
            <?php if (isset($errors['country'])) echo "<p class='error'>{$errors['country']}</p>"; ?>
        </div>
        <div class="question-container">
            <label for="employment">Employment Status:</label>
            <input type="text" name="employment" id="employment" value="<?php echo htmlspecialchars($responseToEdit['employment']); ?>" required>
            <?php if (isset($errors['employment'])) echo "<p class='error'>{$errors['employment']}</p>"; ?>
        </div>
        <div class="question-container">
            <label for="education">Education Level:</label>
            <input type="text" name="education" id="education" value="<?php echo htmlspecialchars($responseToEdit['education']); ?>" required>
            <?php if (isset($errors['education'])) echo "<p class='error'>{$errors['education']}</p>"; ?>
        </div>
        <div class="question-container">
            <label for="preferredContact">Preferred Contact Method:</label>
            <input type="text" name="preferredContact" id="preferredContact" value="<?php echo htmlspecialchars($responseToEdit['preferredContact']); ?>" required>
            <?php if (isset($errors['preferredContact'])) echo "<p class='error'>{$errors['preferredContact']}</p>"; ?>
        </div>
        <div class="question-container">
            <label for="interests">Interests:</label>
            <input type="text" name="interests[]" id="interests" value="<?php echo htmlspecialchars($responseToEdit['interests']); ?>">
        </div>
        <div class="question-container">
            <label for="internetUsage">Internet Usage:</label>
            <input type="text" name="internetUsage" id="internetUsage" value="<?php echo htmlspecialchars($responseToEdit['internetUsage']); ?>" required>
            <?php if (isset($errors['internetUsage'])) echo "<p class='error'>{$errors['internetUsage']}</p>"; ?>
        </div>
        <div class="question-container">
            <label for="onlineShopping">Online Shopping:</label>
            <input type="text" name="onlineShopping" id="onlineShopping" value="<?php echo htmlspecialchars($responseToEdit['onlineShopping']); ?>" required>
            <?php if (isset($errors['onlineShopping'])) echo "<p class='error'>{$errors['onlineShopping']}</p>"; ?>
        </div>
        <div class="question-container">
            <label for=" satisfaction">Satisfaction:</label>
            <input type="text" name="satisfaction" id="satisfaction" value="<?php echo htmlspecialchars($responseToEdit['satisfaction']); ?>" required>
            <?php if (isset($errors['satisfaction'])) echo "<p class='error'>{$errors['satisfaction']}</p>"; ?>
        </div>
        <div class="question-container">
            <label for="favoriteColor">Favorite Color:</label>
            <input type="text" name="favoriteColor[]" id="favoriteColor" value="<?php echo htmlspecialchars($responseToEdit['favoriteColor']); ?>">
            <?php if (isset($errors['favoriteColor'])) echo "<p class='error'>{$errors['favoriteColor']}</p>"; ?>
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
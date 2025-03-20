<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables
    $name = $email = $feedback = $gender = $membership = $age = $country = $employment = $education = $preferredContact = $interests = $internetUsage = $onlineShopping = $satisfaction = $favoriteColor = "";

    // Validate and sanitize the form data
    $errors = [];

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
    if (empty(trim($_POST['satisfaction']))) {
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

    // Check for errors
    if (empty($errors)) {
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

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO `user` (name, email, feedback, gender, membership, age, country, employment, education, preferredContact, interests, internetUsage, onlineShopping, satisfaction, favoriteColor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssisssssssss", $name, $email, $feedback, $gender, $membership, $age, $country, $employment, $education, $preferredContact, $interests, $internetUsage, $onlineShopping, $satisfaction, $favoriteColor);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: display.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close connections
        $stmt->close();
        $conn->close();
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
    <title>Questionnaire Form</title>
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
    <h1>Questionnaire</h1>
    <br>
    <form action="" method="POST">
        <div class="question-container">
            <label for="name">1. What is your name?</label>
            <input type="text" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" >
            <?php if (isset($errors['name'])) echo '<div class="error">' . $errors['name'] . '</div>'; ?>
        </div>
        <br>
        <div class="question-container">
            <label for="email">2. What is your email?</label>
            <input type="email" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" >
            <?php if (isset($errors['email'])) echo '<div class="error">' . $errors['email'] . '</div>'; ?>
        </div>
        <br>
        <div class="question-container">
            <label for="feedback">3. Please provide your feedback:</label>
            <textarea id="feedback" name="feedback" ><?php echo isset($feedback) ? $feedback : ''; ?></textarea>
 <?php if (isset($errors['feedback'])) echo '<div class="error">' . $errors['feedback'] . '</div>'; ?>
        </div>
        <br>
        <div class="question-container">
            <label>4. What is your gender?</label>       
                    <input type="radio" id="male" name="gender" value="Male" <?php echo (isset($gender) && $gender == 'Male') ? 'checked' : ''; ?>>Male                    
                    <input type="radio" id="female" name="gender" value="Female" <?php echo (isset($gender) && $gender == 'Female') ? 'checked' : ''; ?>>Female              
                <?php if (isset($errors['gender'])) echo '<div class="error">' . $errors['gender'] . '</div>'; ?>           
        </div>
        <br>
        <div class="question-container">
            <label for="membership">5. Are you a member?</label>
            <select id="membership" name="membership" >
                <option value="">Select...</option>
                <option value="yes" <?php echo (isset($membership) && $membership == 'yes') ? 'selected' : ''; ?>>Yes</option>
                <option value="no" <?php echo (isset($membership) && $membership == 'no') ? 'selected' : ''; ?>>No</option>
            </select>
            <?php if (isset($errors['membership'])) echo '<div class="error">' . $errors['membership'] . '</div>'; ?>
        </div>
        <br>
        <div class="question-container">
            <label for="age">6. What is your age?</label>
            <input type="number" id="age" name="age" value="<?php echo isset($age) ? $age : ''; ?>" >
            <?php if (isset($errors['age'])) echo '<div class="error">' . $errors['age'] . '</div>'; ?>
        </div>
        <br>
        <div class="question-container">
            <label for="country">7. What country are you from?</label>
            <input type="text" id="country" name="country" value="<?php echo isset($country) ? $country : ''; ?>" >
            <?php if (isset($errors['country'])) echo '<div class="error">' . $errors['country'] . '</div>'; ?>
        </div>
        <br>
        <div class="question-container">
            <label for="employment">8. What is your employment status?</label>
            <input type="text" id="employment" name="employment" value="<?php echo isset($employment) ? $employment : ''; ?>" >
            <?php if (isset($errors['employment'])) echo '<div class="error">' . $errors['employment'] . '</div>'; ?>
        </div>
        <br>
        <div class="question-container">
            <label for="education">9. What is your education level?</label>
            <input type="text" id="education" name="education" value="<?php echo isset($education) ? $education : ''; ?>" >
            <?php if (isset($errors['education'])) echo '<div class="error">' . $errors['education'] . '</div>'; ?>
        </div>
        <br>
        <div class="question-container">
            <label for="preferredContact">10. Preferred contact method:</label>
            <input type="text" id="preferredContact" name="preferredContact" value="<?php echo isset($preferredContact) ? $preferredContact : ''; ?>" >
            <?php if (isset($errors['preferredContact'])) echo '<div class="error">' . $errors['preferredContact'] . '</div>'; ?>
        </div>
        <br>
        <div class="question-container">
            <label>11. What are your interests? (Select all that apply)</label>
            <input type="checkbox" name="interests[]" value="sports" <?php echo (isset($interests) && in_array('sports', explode(", ", $interests))) ? 'checked' : ''; ?>> Sports
            <input type="checkbox" name="interests[]" value="music" <?php echo (isset($interests) && in_array('music', explode(", ", $interests))) ? 'checked' : ''; ?>> Music
            <input type="checkbox" name="interests[]" value="travel" <?php echo (isset($interests) && in_array('travel', explode(", ", $interests))) ? 'checked' : ''; ?>> Travel
            <input type="checkbox" name="interests[]" value="reading" <?php echo (isset($interests) && in_array('reading', explode(", ", $interests))) ? 'checked' : ''; ?>> Reading
        </div>
        <br>
        
        <div class="question-container">
            <label for="internetUsage">12. How often do you use the internet?</label>
            <input type="text" id="internetUsage" name="internetUsage" value="<?php echo isset($internetUsage) ? $internetUsage : ''; ?>" >
            <?php if (isset($errors['internetUsage'])) echo '<div class="error">' . $errors['internetUsage'] . '</div>'; ?>
        </div>
        <br>
        <div class="question-container">
            <label for="onlineShopping">13. Do you prefer online shopping?</label>
            <select id="onlineShopping" name="onlineShopping" >
                <option value="">Select...</option>
                <option value="yes" <?php echo (isset($onlineShopping) && $onlineShopping == 'yes') ? 'selected' : ''; ?>>Yes</option>
                <option value="no" <?php echo (isset($onlineShopping) && $onlineShopping == 'no') ? 'selected' : ''; ?>>No</option>
            </select>
            <?php if (isset($errors['onlineShopping'])) echo '<div class="error">' . $errors['onlineShopping'] . '</div>'; ?>
        </div>
        <br>
        <div class="question-container">
            <label for="satisfaction">14. How satisfied are you with our service?</label>
            <input type="text" id="satisfaction" name="satisfaction" value="<?php echo isset($satisfaction) ? $satisfaction : ''; ?>" >
            <?php if (isset($errors['satisfaction'])) echo '<div class="error">' . $errors['satisfaction'] . '</div>'; ?>
        </div>
        <br>
        <div class="question-container">
            <label>15. What are your favorite colors? (Select all that apply)</label>
            <input type="checkbox" name="favoriteColor[]" value="red" <?php echo (isset($favoriteColor) && in_array('red', explode(", ", $favoriteColor))) ? 'checked' : ''; ?>> Red
            <input type="checkbox" name="favoriteColor[]" value="blue" <?php echo (isset($favoriteColor) && in_array('blue', explode(", ", $favoriteColor))) ? 'checked' : ''; ?>> Blue
            <input type="checkbox" name="favoriteColor[]" value="green" <?php echo (isset($favoriteColor) && in_array('green', explode(", ", $favoriteColor))) ? 'checked' : ''; ?>> Green
            <input type="checkbox" name="favoriteColor[]" value="yellow" <?php echo (isset($favoriteColor) && in_array('yellow', explode(", ", $favoriteColor))) ? 'checked' : ''; ?>> Yellow
            <input type="checkbox" name="favoriteColor[]" value="purple" <?php echo (isset($favoriteColor) && in_array('purple', explode(", ", $favoriteColor))) ? 'checked' : ''; ?>> Purple
            <?php if (isset($errors['favoriteColor'])) echo '<div class="error">' . $errors['favoriteColor'] . '</div>'; ?>
        </div>
        <br>
        <div class="button-container">
            <input type="submit" value="Submit">
        </div>
    </form>
</div>

</body>
</html>

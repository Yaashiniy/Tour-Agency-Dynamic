<?php
// Database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "niva_tour_agency";

// Create connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Defined variables with empty values
$userName = $userEmail = $userPassword = ""; //variables to store user input 
$successMessage = $errorMessage = ""; 

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == 'POST') { //check if the form is submitted using POST method
    $userName = $_POST["username"]; // retrieve data from the form using &_POST and assign the values to the corresponding variables
    $userEmail = $_POST["email"];
    $userPassword = $_POST["password"]; 
    
    // Simple validation
    if (!empty($userName) && !empty($userEmail) && !empty($userPassword)) { //ensure all values filled in username, email, password.
        // Hash password for security
        $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT); //encrypts the user's password, difficult to decode

        // Insert data into the users sign up table
        $sql = "INSERT INTO users_sign_up (username, email, password) VALUES ('$userName', '$userEmail', '$hashedPassword')";
    
//construct an SQL query to insert data into users_sign_up table in database    
    if ($conn->query($sql) === TRUE) { //Execute query using this
            // Redirect to SignInForm.php after successful sign-up
            header("Location: SignInForm.php");
            exit();
        } else {
            $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $errorMessage = "All fields are required.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up to Niva Travel</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #000;
            overflow: hidden;
        }
		
		
        /* Background video */
        #background-video {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 30%;
            min-height: 30%;
            z-index: -1;
        }
		
			
        /* Flex container for image and form */
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            overflow: hidden;
            width: 58%;
            max-width: 800px;
            height: 80%;
        }

        /* Image container */
        .image-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .image-container img {
            width: 111%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Sign-up form styling */
        .signup-form {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 20px;
            color: #fff;
            text-align: center;
        }

        .signup-form h1 {
            margin-bottom: 20px;
            color: #fff;
        }

        .signup-form input[type="text"],
        .signup-form input[type="email"],
        .signup-form input[type="password"] {
            width: 80%;
            padding: 10px;
            margin: 30px 0;
            border: none;
			border-bottom: 2px solid #40B5AD;
            border-radius: 0;
            background: transparent;
			outline: none;
			transition: border-color 0.3s;  
			color: white;
        }
		
		.signup-form input[type="text"]:focus,
.signup-form input[type="email"]:focus,
.signup-form input[type="password"]:focus {
    border-bottom-color: cyan;  /* Change color on focus */
}

        .signup-form button {
            width: 30%;
            padding: 10px;
            background-color: #40B5AD;
            color: white;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: bold;
        }

        .signup-form button:hover {
            background-color: black;
        }

        .message, .error {
            margin-top: 10px;
            color: #4CAF50;
        }

        .error {
            color: #ff4d4d;
        }
    </style>
</head>
<body>

<!-- Background Video -->
<video autoplay muted loop id="background-video">
    <source src="Images/signup.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

<div class="container"> <!--Container to structure the page-->
    <!-- Image Container -->
    <div class="image-container">
        <img src="Images/Sign Up Pic.png" alt="Sign Up Image">
    </div>

    <!-- Sign-Up Form -->
    <div class="signup-form">
        <h2>Sign Up to Niva Travel.</h2>
        <?php if (!empty($successMessage)) echo "<p class='message'>$successMessage</p>"; ?>
        <?php if (!empty($errorMessage)) echo "<p class='error'>$errorMessage</p>"; ?> <!--Display success or error messages-->
        
        <form action="TourSignUp.php" method="POST">
            <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($userName); ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($userEmail); ?>" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
    </div>
</div>

</body>
</html>

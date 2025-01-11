<?php
session_start(); // Enable to store and retrieve data across multiple pages using $_SESSION
include 'db_connection.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //ensure code only runs when the form is submmiteed using the POST method
    $email = $_POST['email']; // Fetch Email from form
    $password = $_POST['password']; // Fetch Password from form, POST method usually for submitting sensitive data

    // Query to verify user credentials like user_id and password
    $sql = "SELECT user_id, Password FROM users_sign_up WHERE Email = ?"; //? as placeholder protects against SQL injection attacks
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); //s specifies the type of parameter which is string
    $stmt->execute();
    $result = $stmt->get_result();

//check if the query found a matching record in the database. 
    if ($result->num_rows > 0) {  // return the num of rows in result. if >0,a user with provided email exists.
        $row = $result->fetch_assoc(); // fetch the row of data from the result

        if (password_verify($password, $row['Password'])) { //compare the passwor entered by the user with the hashed password stored in database
            // Set session variables
            $_SESSION['user_id'] = $row['user_id']; //store user_id & email in session variables so can use them on other pages , to identify logged-in user
            $_SESSION['email'] = $email;

            // Redirect to user's account page
            header("Location: user's_account.php");
            exit();
        } else { //alert box for invalid pass
            echo "<script>alert('Invalid email or password!'); window.location.href='signinForm.php';</script>";
        }
    } else { //for unfound email
        echo "<script>alert('No account found with this email!'); window.location.href='signinForm.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In to NivaTravel</title>
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
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures the entire video fits within the viewport */
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
            width: 60%;
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
            width: 115%;
            height: 90%;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Login form styling */
        .login-form {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 20px;
            color: #fff;
            text-align: center;
        }

        .login-form h2 {
            margin-bottom: 20px;
            color: #fff;
        }

        .login-form input[type="email"],
        .login-form input[type="password"] {
            width: 80%;
            padding: 10px;
            margin: 20px 0;
            border: none;
            border-bottom: 2px solid #40B5AD;
            background: transparent;
            outline: none;
            transition: border-color 0.3s;
            color: white;
        }

        .login-form input[type="email"]:focus,
        .login-form input[type="password"]:focus {
            border-bottom-color: cyan;
        }

        .login-form button {
            width: 30%;
            padding: 10px;
            background-color: #40B5AD;
            color: white;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: bold;
        }

        .login-form button:hover {
            background-color: white;
            color: darkcyan;
        }

        .error {
            margin-top: 10px;
            color: #ff4d4d;
        }
		
    </style>
</head>
<body>

<!-- Background Video -->
<video autoplay muted loop id="background-video">
    <source src="Images/SignIn.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video> n 

<div class="container">
    <!-- Image Container -->
    <div class="image-container">
        <img src="Images/Sign In Pic.png" alt="Sign In Image">
    </div>

    <!-- Sign-In Form -->
    <div class="login-form">
        <h2>Sign In to NivaTravel.</h2>
        <?php if (!empty($errorMessage)) echo "<p class='error'>$errorMessage</p>"; ?>

        <form action="SignInForm.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</div>

</body>
</html>



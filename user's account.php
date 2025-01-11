<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION["user_id"])) {
    header("SignInForm.php");
    exit();
}

// Display user's account information
echo "<h1>Welcome, " . htmlspecialchars($_SESSION["username"]) . "!</h1>";
echo "<p>This is your account page.</p>";

// Optionally add a logout link
echo '<a href="logout.php">Logout</a>';
?>

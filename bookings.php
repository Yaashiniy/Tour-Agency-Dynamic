<?php
session_start();
include 'db_connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //ensure form submitted using POST method
    // Get form data,email
    $email = $_POST['Email']; // Get email from the form

    // Check if the email exists in the users_sign_up table
    $sql = "SELECT user_id FROM users_sign_up WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) { //check if there's at least one row in the (meaning the email exist
        // Email exists, allow booking
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];

        // Get other form data
        $fullName = $_POST['Full_Name'];
        $phone = $_POST['Phone_Number'];
        $tourName = $_POST['Tour_Name'];
        $adults = $_POST['No_of_Adult'];
        $children = $_POST['No_of_Children'];
        $infants = $_POST['No_of_Infants'];
        $remarks = $_POST['Additional_Remarks'];

        // Insert into bookings table
        $sql = "INSERT INTO bookings (user_id, Full_Name, Email, Phone_Number, Tour_Name, No_of_Adult, No_of_Children, No_of_Infants, Additional_Remarks) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; //? for each value to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssiiis", $user_id, $fullName, $email, $phone, $tourName, $adults, $children, $infants, $remarks); //i=integer,s=string , indicates types of values 

        if ($stmt->execute()) { //execute query to insert data into bookings table
            echo "<script>
                alert('Booking added successfully. Sign In to view your bookings.');
                window.location.href = 'SignInForm.php'; // Redirect to bookings page or another page
            </script>";
        } else {
            echo "<script>
                alert('Error: " . $stmt->error . "');
            </script>";
        }
   }  else {
        // Email does not exist, prompt to sign up
        echo "<script>
            alert('Your email is not registered. Please sign up to book a tour.');
            window.location.href = 'TourSignUp.php'; // Redirect to sign-up page
        </script>";
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
    <title>Booking Form</title>
    <style>
        /* General styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            overflow: hidden; /* Prevents unnecessary scrolling */
        }

        /* Fullscreen video */
        .background-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -2; /* Ensures video stays in the background */
        }

        /* Overlay for video */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Dark overlay for readability */
            z-index: -1;
        }

        /* Form container */
        .form-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1); /* Transparent background */
            border: 1px solid rgba(255, 255, 255, 0.3); /* Border for visibility */
            border-radius: 10px;
            backdrop-filter: blur(10px); /* Blur effect for form */
            color: white;
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: darkcyan;
        }

        /* Form grid for two columns */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-grid .full-width {
            grid-column: span 2; /* Spans both columns */
        }

        /* Inputs with transparent background and lines only */
        .form-container input, .form-container textarea {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            background: none;
            border: none;
            border-bottom: 2px solid white;
            color: white;
            font-size: 16px;
            outline: none;
        }

        .form-container input::placeholder,
        .form-container textarea::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-container textarea {
            resize: vertical;
        }

        button {
            width: 50%;
            padding: 10px;
			margin: 10px;
            background-color: transparent;
            color: white;
            border: 2px solid white;
            border-radius: 9px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
			width: 150px;
        }

        button:hover {
            background-color: white;
            color: black;
        }
		
    </style>
</head>
<body>
    <!-- Background video -->
    <video class="background-video" autoplay muted loop>
        <source src="Images/bgvideo.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Dark overlay -->
    <div class="overlay"></div>

    <!-- Booking form -->
    <div class="form-container">
        <h1>Book Your Tour</h1>
        <form action="bookings.php" method="POST">
            <div class="form-grid">
                <div class="form-group"> <!--No label for="name"...bla bla..replaced with placeholder-->
                    <input type="text" id="Full_Name" name="Full_Name" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <input type="email" id="Email" name="Email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="text" id="Phone_Number" name="Phone_Number" placeholder="Phone Number" required>
                </div>
                <div class="form-group">
				<input type="text" id="Tour_Name" name="Tour_Name" placeholder="Tour Name (Exp:India 5D4N)" >
				</div>

                <div class="form-group">
                    <input type="number" id="No_of_Adult" name="No_of_Adult" placeholder="Number of Adults" required min="1">
                </div>
                <div class="form-group">
                    <input type="number" id="No_of_Children" name="No_of_Children" placeholder="Number of Children" min="0">
                </div>
                <div class="form-group">
                    <input type="number" id="No_of_Infants" name="No_of_Infants" placeholder="Number of Infants" min="0">
                </div>
                <div class="form-group full-width">
                    <textarea id="Additional_Remarks" name="Additional_Remarks" rows="4" placeholder="Additional Remarks"></textarea>
                </div>
            </div>
            <button type="submit">Submit Booking</button>
        </form>
    </div>
</body>
</html>

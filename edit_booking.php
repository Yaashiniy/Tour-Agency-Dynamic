<?php
session_start();
include 'db_connection.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must log in to edit your booking!'); window.location.href='signinForm.php';</script>";
    exit();
}

// Get the booking ID from the URL
if (!isset($_GET['booking_id'])) {
    echo "<script>alert('No booking ID provided!'); window.location.href='user's_account.php';</script>";
    exit();
}

$booking_id = $_GET['booking_id'];

// Fetch the booking details to prefill the form
$sql = "SELECT * FROM bookings WHERE booking_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {//if no results are found
    echo "<script>alert('Booking not found!'); window.location.href='user's_account.php';</script>";
    exit();
}

$booking = $result->fetch_assoc();

// Handle form submission to update the booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the updated form data
    $fullName = $_POST['Full_Name'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone_Number'];
    $tourName = $_POST['Tour_Name'];
    $adults = $_POST['No_of_Adult'];
    $children = $_POST['No_of_Children'];
    $infants = $_POST['No_of_Infants'];
    $remarks = $_POST['Additional_Remarks'];

    // Update the booking in the database
    $update_sql = "UPDATE bookings SET 
                        Full_Name = ?, 
                        Email = ?, 
                        Phone_Number = ?, 
                        Tour_Name = ?, 
                        No_of_Adult = ?, 
                        No_of_Children = ?, 
                        No_of_Infants = ?, 
                        Additional_Remarks = ? 
                    WHERE booking_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssiiiss", $fullName, $email, $phone, $tourName, $adults, $children, $infants, $remarks, $booking_id); //typs of parameter

    if ($update_stmt->execute()) {
        echo "<script>
                alert('Booking updated successfully!');
                window.location.href = 'user\'s_account.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error updating booking: " . $update_stmt->error . "');
                window.location.href = 'edit_booking.php?booking_id=$booking_id';
              </script>";
    }

    $update_stmt->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking - Niva Tour</title>
    <link rel="stylesheet" href="styles.css">
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
            width: 80%;
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
        }

        button:hover {
            background-color: white;
            color: black;
        }
    </style>
</head>
<body>
 <video class="background-video" autoplay muted loop>
        <source src="Images/bgvideo.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
   <div class="form-container">
        <h1>Edit Your Booking</h1>
        <form action="edit_booking.php?booking_id=<?php echo $booking_id; ?>" method="POST"> 
           <div class="form-grid">
                <div class="form-group">
                    <input type="text" id="Full_Name" name="Full_Name" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <input type="email" id="Email" name="Email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="text" id="Phone_Number" name="Phone_Number" placeholder="Phone Number" required>
                </div>
               <div class="form-group">
   <div class="form-group">
				<input type="text" id="Tour_Name" name="Tour_Name" placeholder="Tour Name" required>
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
            <button type="submit">Update Booking</button>
        </form>
    </div>
</body>
</html>

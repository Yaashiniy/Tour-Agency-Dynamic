<?php
include 'db_connection.php';


<?php

// Handle form submission for booking
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['Full_Name'];
    $email = $_POST['Email'];
    $phone_number = $_POST['Phone_Number'];
    $tour_name = $_POST['Tour_Name'];
    $no_of_adult = $_POST['No_of_Adult'];
    $no_of_child = $_POST['No_of_child'];
    $no_of_infant = $_POST['No_of_Infant'];
    $additional_remarks = $_POST['Additional_Remarks'];

    // Insert booking data into the database
    $sql = "INSERT INTO bookings (Full_Name, Email, Phone_Number, Tour_Name, No_of_Adult, No_of_child, No_of_Infant, Additional_Remarks)
            VALUES ('$full_name', '$email', '$phone_number', '$tour_name', '$no_of_adult', '$no_of_child', '$no_of_infant', '$additional_remarks')";

    if (mysqli_query($conn, $sql)) {
        echo "Booking successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Book Your Tour</h1>
    <form action="bookings.php" method="POST">
        <div class="form-group">
            <label for="Full_Name">Full Name:</label>
            <input type="text" id="Full_Name" name="Full_Name" required>
        </div>
        <div class="form-group">
            <label for="Email">Email:</label>
            <input type="email" id="Email" name="Email" required>
        </div>
        <div class="form-group">
            <label for="Phone_Number">Phone Number:</label>
            <input type="text" id="Phone_Number" name="Phone_Number" required>
        </div>
        <div class="form-group">
            <label for="Tour_Name">Tour Name:</label>
            <input type="text" id="Tour_Name" name="Tour_Name" required>
        </div>
        <div class="form-group">
            <label for="No_of_Adult">Number of Adults:</label>
            <input type="number" id="No_of_Adult" name="No_of_Adult" required min="1">
        </div>
        <div class="form-group">
            <label for="No_of_child">Number of Children (Ages 2-12):</label>
            <input type="number" id="No_of_child" name="No_of_child" min="0">
        </div>
        <div class="form-group">
            <label for="No_of_Infant">Number of Infants (Below 2):</label>
            <input type="number" id="No_of_Infant" name="No_of_Infant" min="0">
        </div>
        <div class="form-group">
            <label for="Additional_Remarks">Additional Remarks:</label>
            <textarea id="Additional_Remarks" name="Additional_Remarks" rows="4"></textarea>
        </div>
        <button type="submit">Submit Booking</button>
    </form>
</div>

</body>
</html>

<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must log in to access your account!'); window.location.href='signinForm.php';</script>";
    exit();
}

// Get the logged-in user's email
$email = $_SESSION['email'];

// Fetch the user's full name from the database
$sql_user = "SELECT Full_Name FROM bookings WHERE Email = ? LIMIT 1";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $email);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_row = $result_user->fetch_assoc();
$full_name = $user_row ? $user_row['Full_Name'] : 'User'; //If the query finds a result, the full name is stored in $full_name. If not, a default value of 'User' is assigned.

// Fetch bookings for the logged-in user
$sql = "SELECT * FROM bookings WHERE Email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result(); //stores the result in $result for display later
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account - Niva Tour</title>
    <link rel="stylesheet" href="styles.css">
	<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- Material Icons -->
    <style>
        body {
            font-family:'Bebas Neue', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e8f6f6;
			letter-spacing:1px;
        }
		
		.container {
        display: flex;
        flex-direction: column;
        align-items: center; /* Centers content horizontally */
        justify-content: center; /* Centers content vertically */
        text-align: center; /* Aligns text to the center */
    }


        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: darkcyan;
            padding: 10px 20px;
        }

        .navbar img {
            width: 70px;
            cursor: pointer;
        }
		
		.navbar div {
    display: flex;
    justify-content: center;
    align-items: center;
    flex: 1; /* This ensures the navigation items are centered */
}
		

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            font-size: 1.1em;
			font-family: Poppins;
        }
		
		.navbar a:hover {
		color: cyan;
}

        .navbar .add-booking {
            margin-left: auto;
            background-color: #004f55;
            padding: 10px 15px;
            border-radius: 20px;
            font-size: 1em;
        }

        .welcome {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 15px 0;
        }

        .welcome img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }
		


.logout-button i {
    font-size: 1.5em;
    color: white;
    cursor: pointer;
}



        .welcome-text {
            font-size: 1.5em;
            color: #006766;
        }

        .table-wrapper {
            width: 90%;
            overflow-x: auto;
    
        }

        table {
            width: 100%;
            border-collapse: collapse;
			 margin: 20px auto; /* Centers the table horizontally */
        }

		
		table td {
		font-family: 'Poppins', sans-serif; /* Poppins for table data */
		padding: 10px;
		text-align: left;
		border: 1px solid #ddd;
		}

        th {
            background-color: darkcyan;
            color: white;
			 font-family: 'Poppins', sans-serif;
			 padding: 10px;
			text-align: left;
			border: 1px solid #ddd;
			letter-spacing: 1px;
			 
        }

        tr:hover {
            background-color: #e1f4f4;
        }

        .material-icons {
            cursor: pointer;
            font-size: 20px;
            color: #006766;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
            }

            .welcome {
                flex-direction: column;
                text-align: center;
            }

            th, td {
                font-size: 0.9em;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <div class="navbar">
        <img src="Images/niva travel agency.png" alt="NivaTravel Logo" onclick="window.location.href='homepage.html'" title="homepage">
        <div>
            <a href="homepage.html">Home</a>
            <a href="homepage.html#about-us">About Us</a>
            <a href="homepage.html#destinations">Destinations</a>
            <a href="contact.html">Contact</a>
        </div>
        <a href="homepage.html#destinations" class="add-booking">Add Booking</a>
		 <a href="logout.php" class="logout-button" title="Log Out">
        <i class="material-icons">logout</i>
    </a>
    </div>

    <!-- Welcome Section -->
    <div class="container">
    <div class="welcome">
        <img src="Images/profilepic.png" alt="Profile Picture" title="profile picture"> <!-- Placeholder -->
        <div class="welcome-text"><h2>Welcome, <?php echo htmlspecialchars($full_name); ?>! </h2></div> <!--prevent cross-site scripting by escaping special char in the user's name.-->
    </div>

    <h2>Your Bookings</h2>

 <div class="table-wrapper">
    <?php if ($result->num_rows > 0) { ?> <!--check for records--> 
        <p>Your booking will be processed within 6 working days.</p>
        <table>
            <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Tour Name</th>
                <th>Adults</th>
                <th>Children</th>
                <th>Infants</th>
                <th>Remarks</th>
                <th>Date and Time</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?> <!--while loop to go through each booking $row and populate table with data-->
                <tr>
                    <td><?php echo htmlspecialchars($row['Full_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Email']); ?></td>
                    <td><?php echo htmlspecialchars($row['Phone_Number']); ?></td>
                    <td><?php echo htmlspecialchars($row['Tour_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['No_of_Adult']); ?></td> 
                    <td><?php echo htmlspecialchars($row['No_of_Children']); ?></td>
                    <td><?php echo htmlspecialchars($row['No_of_Infants']); ?></td>
                    <td><?php echo htmlspecialchars($row['Additional_Remarks']); ?></td>
                    <td><?php echo htmlspecialchars($row['Booking_Date']); ?></td>
                    <td>
                        <i class="material-icons" onclick="window.location.href='edit_booking.php?booking_id=<?php echo $row['booking_id']; ?>'" title="edit booking">edit</i>
                        <i class="material-icons" onclick="if(confirm('Are you sure you want to delete this booking?')) { window.location.href='delete_booking.php?booking_id=<?php echo $row['booking_id']; ?>'; }" title="delete booking">delete</i>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No bookings to view.</p>
    <?php } ?>
</div>

   
    </div>
</div>

</body>
</html>

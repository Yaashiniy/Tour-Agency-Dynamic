<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signinForm.php");
    exit();
}

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Delete the booking from the database
    $sql = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        echo "<script>alert('Booking deleted successfully!'); window.location.href='user\'s_account.php';</script>";
    } else {
        echo "Error deleting booking: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No booking found to delete.";
}
?>

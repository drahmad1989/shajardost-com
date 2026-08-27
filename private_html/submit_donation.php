<?php
include 'includes/db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['donor_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $package = mysqli_real_escape_string($conn, $_POST['package']);
    $message = mysqli_real_escape_string($conn, $_POST['custom_message']);

    // Extract price roughly from string (simple logic)
    $amount = 0; // You can parse the string if needed, or add a hidden input for exact price

    $sql = "INSERT INTO donations (donor_name, contact_number, email, package, custom_message) 
            VALUES ('$name', '$phone', '$email', '$package', '$message')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Thank you! Please send the payment receipt on WhatsApp now.'); window.location.href='https://wa.me/923333197977';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
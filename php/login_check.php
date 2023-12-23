<?php
// Include the database connection file
include 'db_connect.php';

// Retrieve form data
$user_id = $_POST['user_id'];
$password = $_POST['password'];

// Query the database
$sql = "SELECT matricNo, passwordHash FROM users WHERE userID = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $matricNo = $row['matricNo'];
    $hashed_password = $row['passwordHash'];

    // Verify password
    if (password_verify($password, $hashed_password)) {
        // Password is correct
        echo "Login successful!";
    } else {
        // Password is incorrect
        echo "Invalid password.";
    }
} else {
    // User not found
    echo "Invalid user ID.";
}

// Close database connection (you can omit this if you want to reuse the connection in other parts of your script)
$conn->close();
?>

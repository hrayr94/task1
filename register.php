<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

try {
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Connected successfully";
} catch(PDOException $e) {
die("Connection failed: " . $e->getMessage());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate inputs (basic validation, you can add more as needed)
    if (empty($name) || empty($email) || empty($password)) {
        echo "All fields are required.";
    } else {
        // Hash the password using PHP's password_hash function
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Prepare SQL statement to insert data into database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            // Execute the statement and check for success
            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                echo "Error: Unable to execute the query.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

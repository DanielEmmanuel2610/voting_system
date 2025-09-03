<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $lname = $_POST["lname"];
    $student_id = $_POST["student_id"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];
    $role = $_POST["role"];

    if ($password !== $confirmpassword) {
        echo "Passwords do not match.";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT * FROM jiji WHERE student_id = ?");
    $check->bind_param("s", $student_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Student ID already exists.";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO jiji (name, lname, student_id, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $lname, $student_id, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "Registration successful. <a href='login.html'>Login now</a>";
    } else {
        echo "Registration failed: " . $stmt->error;
    }
}
?>

<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST["email"];
    $password = $_POST["password"];
    $role     = "admin"; // Only allow admins

    $sql = "SELECT id, name, password FROM jiji WHERE email=? AND role=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION["admin_id"] = $id;
            $_SESSION["admin_name"] = $name;
            $_SESSION["role"] = $role;
            header("Location: .php");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Admin not found.";
    }
}
?>

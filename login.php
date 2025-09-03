<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM jiji WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["student_id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["role"] = $user["role"];

        if ($user["role"] === "admin") {
            header("Location: dashboard_admin.php");
        } else {
            header("Location:selectleader.html");
        }
        exit();
    } else {
        echo "Invalid login credentials.";
    }
}
?>

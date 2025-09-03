<?php 
include("db.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $name     = $_POST["name"];
    $lname    = $_POST["lname"];
    $student_id    = $_POST["student_id"];
    $email    = $_POST["email"];
    $password   = $_POST["password"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role     = "admin"; // Fixed role for this page

    $sql = "SELECT * FROM jiji WHERE email=?";
    $check_stmt = $conn->prepare($sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if($check_stmt->num_rows > 0){
        echo "Admin already exists.";
    } else {
        $sql = "INSERT INTO jiji (name,lname,student_id, email, password, role) VALUES (?,?,?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($sql);
        $insert_stmt->bind_param("ssssss", $name, $lname,$student_id,$email, $password, $role);

        if($insert_stmt->execute()){
            header("location: loginad.html");
            exit();
        } else {
            echo "Registration failed: " . $conn->error;
        }
    }
}
?>

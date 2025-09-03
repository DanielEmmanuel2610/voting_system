<?php
include("db.php");

$student_id = $_POST["student_id"];
$leader = $_POST["leader"];

// Validate student exists and is a 'user' (not admin)
$check_user = $conn->prepare("SELECT * FROM jiji WHERE student_id = ? AND role = 'user'");
$check_user->bind_param("s", $student_id);
$check_user->execute();
$result = $check_user->get_result();

if ($result->num_rows === 0) {
    echo " Student ID not found ";
    exit();
}

// Check if already voted
$check_vote = $conn->prepare("SELECT * FROM votes WHERE student_id = ?");
$check_vote->bind_param("s", $student_id);
$check_vote->execute();
$check_vote->store_result();

if ($check_vote->num_rows > 0) {
    echo " You have already voted.";
    exit();
}

// Insert the vote
$insert = $conn->prepare("INSERT INTO votes (student_id, leader) VALUES (?, ?)");
$insert->bind_param("ss", $student_id, $leader);

if ($insert->execute()) {
    echo " Your vote has been recorded";
} else {
    echo "Error recording vote.";
}
?>

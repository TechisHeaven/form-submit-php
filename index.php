<?php
require 'functions/custom.function.php';
// require_once 'db/connection.php';
require_once 'config/config.php';

// Establish the database connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check the connection
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // collect value of input field
    $fullname = isset($_REQUEST["fullname"]) ? customRealEscapeString($mysqli, $_REQUEST["fullname"]) : "";
    $email = isset($_REQUEST["email"]) ? customRealEscapeString($mysqli, $_REQUEST["email"]) : "";
    $year = isset($_REQUEST["year"]) ? customRealEscapeString($mysqli, $_REQUEST["year"]) : "";
    $branch = isset($_REQUEST["branch"]) ? customRealEscapeString($mysqli, $_REQUEST["branch"]) : "";
    $message = isset($_REQUEST["message"]) ? customRealEscapeString($mysqli, $_REQUEST["message"]) : "";

    if (empty($fullname) || empty($email) || empty($year) || empty($branch) || empty($message)) {
        //if anyone of above is empty show error 
        die("data is empty");
    }
    $checkEmailSql = "select email from `users` where email = '$email'";
    $result = $mysqli->query($checkEmailSql);

    if ($result->num_rows > 0) {
        $sendingData = array("message" => "Email Already Exists", 'status' => 403);
        header('Content-type: application/json');
        echo json_encode($sendingData);
        return;
    }

    //sql query for inserting user data to database
    $sql = "insert into `users` (`fullname`, `email`, `year`, `branch`, `message`) values('$fullname' , '$email', '$year' , '$branch' ,'$message')";
    //running sql query through mysql connection variable
    $result = $mysqli->query($sql);
    if ($result !== false && $result > 0) {
        $sendingData = array("message" => "Success Submit user", 'status' => 201);
        header('Content-type: application/json');
        echo json_encode($sendingData);
    } else {
        // $sendingData = array("message" => "Something Went Wrong", 'status' => 409);
        $sendingData = array("message" => "Something Went Wrong", 'status' => 500);
        header('Content-type: application/json');
        echo json_encode($sendingData);
        return;
    }

}


?>
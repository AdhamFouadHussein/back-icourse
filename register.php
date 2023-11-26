<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
// Database configuration
$dbhost = "localhost";
$dbuser = "id21577008_doma";
$dbpass = "Doma@1212";
$dbname = "id21577008_tafl";


    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
// Set the charset to utf8
$conn->set_charset("utf8");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get JSON as a string
    $json_str = file_get_contents('php://input');

    // Get as an object
    $json_obj = json_decode($json_str);

    $username = $json_obj->username;
    $name = $json_obj->fullName;
    $email = $json_obj->email;
    $password =$json_obj->password; 
    $phone_number =$json_obj->phone_number; 
    $city  =$json_obj->city; 
    $address  =$json_obj->address;  
    // Check if username already exists
    $check = "SELECT * FROM users WHERE username = '$username' AND email = '$email' ";
    $result = $conn->query($check);

    if ($result->num_rows > 0) {
        // Username already exists
        $response = array('status' => 401, 'message' => 'Username or Email already exists');
    } else {
        $token = bin2hex(random_bytes(16)); // This generates a crypto-secure 32 characters long token
        $sql = "INSERT INTO users (full_name, email, password, username, phone_number, 	city, address, token)
        VALUES ('$name', '$email', '$password', '$username', '$phone_number', 	'$city', '$address', '$token')";

if ($conn->query($sql) === TRUE) {
    // New record created successfully
   
    $response = array(
        'status' => 200, 
        'message' => 'New record created successfully',
        'username' => $username,
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'phone_number' => $phone_number,
        'city' => $city,
        'address' => $address,
        'token'=>$token
    );
} else {
    // Error occurred
    $response = array('status' => 401, 'message' => 'Error: ' . $sql . "<br>" . $conn->error);
}

    }

    $conn->close();

    // Set Content-type to JSON
    header('Content-type: application/json');
    echo json_encode($response);
?>



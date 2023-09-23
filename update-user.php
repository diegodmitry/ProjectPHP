<?php
  $conexao = new mysqli("localhost", "root", "", "casopratico");
  if ($conexao->connect_error) {
    die("Connection failed: " . $conexao->connect_error);
  }

  // Get the raw POST data from the request body
  $json = file_get_contents('php://input');

  // print $json;
  print_r($json);

  // Decode the JSON data into a PHP object
  $formDataJSON = json_decode($json);

  // Get the form data from the formDataJSON object
  $username = $formDataJSON->username;
  $password = $password = password_hash($formDataJSON->password, PASSWORD_BCRYPT);
  $email = $formDataJSON->email;
  $phone = $formDataJSON->phone;
  $role = $formDataJSON->role;
  $id = $formDataJSON->id;

  // Update the user data in the database
  $sql = "UPDATE users SET username = '$username', password = '$password', email = '$email', phone = '$phone', role = '$role' WHERE id = $id";

  if ($conexao->query($sql) === TRUE) {
    $response = array("success" => true);
  } else {
    $response = array("success" => false, "error" => $conexao->error);
  }
  // echo json_encode($response);

  $conexao->close();
?>
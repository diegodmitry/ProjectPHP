
<?php
  $conexao = new mysqli("localhost", "root", "", "casopratico");
  if ($conexao->connect_error) {
    die("Connection failed: " . $conexao->connect_error);
  }

  // Get the user ID from the AJAX request
  $id = $_POST["id"];

  // Delete the user from the database
  $sql = "DELETE FROM users WHERE id = $id";
  if ($conexao->query($sql) === TRUE) {
    echo "User deleted successfully";
  } else {
    echo "Error deleting user: " . $conexao->error;
  }

  $conexao->close();
?>

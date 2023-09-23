<?php
// Check if the login form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Database configuration
    $host = "localhost";
    $username_db = "root";
    $password_db = "";
    $database = "casopratico";

    // Create a connection to the database
    $conn = mysqli_connect($host, $username_db, $password_db, $database);

    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Execute a SQL query to retrieve data from the database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    // Check if the query was successful
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Check if the username exists in the database
    if (mysqli_num_rows($result) == 1) {
      // Get the hashed password from the database
      $row = mysqli_fetch_assoc($result);
      $hashedPassword = $row["password"];

      // Check if the password is encrypted
      if (password_verify($password, $hashedPassword)) {
          // Password is correct
          if ($row["role"] == "admin") {
              header("Location: admin.php");
              exit();
          } else {
              header("Location: user.php");
              exit();
          }
      } else if ($password == $row["password"]) {
          // Password is not encrypted but matches the stored password
          if ($row["role"] == "admin") {
              header("Location: admin.php");
              exit();
          } else {
              header("Location: user.php");
              exit();
          }
      } else {
          // Password is incorrect
          $error = "Invalid username or password";
      }
  } else {
      // Username does not exist in the database
      $error = "Invalid username or password";
  }

  // Close the database connection
  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="login.css">
  <title>Login</title>
</head>
<body>
  <div class="container">
    <h1>Login</h1>
    <?php if (isset($error)) { ?>
      <p><?php echo $error; ?></p>
    <?php } ?>
    <form method="post" onsubmit="saveUsername()">
      <label for="username">Username:</label>
      <input type="text" name="username" required>
      <br>
      <label for="password">Password:</label>
      <input type="password" name="password" required>
      <br>
      <button type="submit">Login</button>
    </form>
  </div>
  <script>
    function saveUsername() {
      // Get the username input element
      var usernameInput = document.getElementsByName("username")[0];

      // Get the username value
      var username = usernameInput.value;

      // Save the username to LocalStorage
      localStorage.setItem("username", username);
    }
  </script>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Page</title>
  <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>
  <?php
    $conexao = new mysqli("localhost", "root", "", "casopratico");
    if ($conexao->connect_error) {
      die("Connection failed: " . $conexao->connect_error);
    }
    $sql = "SELECT * FROM users";
    $result = $conexao->query($sql);
  ?>
  <h1>Admin Page</h1>
  <button id="logout-button">Log out</button>
  <p id="username">Hi, admin.</p>

  <!-- Add a button to create a new user -->
  <button id="create-user-button" onClick="createUser()">Create User</button>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Password</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Role</th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php
      while ($row = $result->fetch_assoc()) {
        echo "<tr data-id='" . $row["id"] . "'>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . str_repeat("&#8226;", 8) . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["phone"] . "</td>";
        echo "<td>" . $row["role"] . "</td>";
        echo "<td><button class='deleteBtn' onclick='deleteUser(" . $row["id"] . ")'>Delete</button></td>";
        echo "<td><button onclick='showEditForm(" . $row["id"] . ")'>Edit</button></td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>


  <script>
    // Retrieve the username from LocalStorage
    const username = localStorage.getItem("username");

    // Select the paragraph element
    const usernameElement = document.getElementById("username");

    // Update the paragraph element
    usernameElement.textContent = `Hi, ${username}.`;

    // Function to delete a user
    function deleteUser(id) {
      if (confirm("Are you sure you want to delete this user?")) {
        // Send an AJAX request to delete the user
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_user.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (xhr.readyState === xhr.DONE) {
            if (xhr.status === 200) {
              // Reload the page to update the table
              location.reload();
            } else {
              console.error(xhr.statusText);
            }
          }
        };
        xhr.onerror = function() {
          console.error(xhr.statusText);
        };
        xhr.send(`id=${id}`);
      }
    }

    function showEditForm(id) {

      // Get the table row that was clicked
      const row = document.querySelector(`tr[data-id="${id}"]`);

      // Get the user data from the table row
      // console.log('row:', row);
      const username = row.cells[1].textContent;
      const password = row.cells[2].textContent;
      const email = row.cells[3].textContent;
      const phone = row.cells[4].textContent;
      const role = row.cells[5].textContent;

      // Create the edit form
      const form = document.createElement("form");
      form.innerHTML = `
        <label for="id">Id:</label>
        <input type="number" id="id" name="id" value="${row.cells[0].textContent}" disabled>
        <br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="${username}">
        <br>
        <label for="password">Password:</label>
        <input type="text" id="password" name="password" value="${password}">
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="${email}">
        <br>
        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" value="${phone}">
        <br>
        <label for="role">Role:</label>
        <select id="role" name="role">
          <option value="admin" ${role === "admin" ? "selected" : ""}>Admin</option>
          <option value="user" ${role === "user" ? "selected" : ""}>User</option>
        </select>
        <br>
        <button type="submit">Save</button>
        <button type="button" onclick="hideEditForm()">Cancel</button>
      `;

      // Add a data attribute to the form to store the user ID
      form.dataset.id = id;

      // Create the modal dialog box
      const modal = document.createElement("div");
      modal.classList.add("modal");
      modal.appendChild(form);

      // Add the modal dialog box to the page
      document.body.appendChild(modal);

      // Show the modal dialog box
      modal.style.display = "block";

      // Add an event listener to the form's submit event
      form.addEventListener("submit", (event) => {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Get the form data and convert it to JSON
        const formData = new FormData(form);
        const formDataJSON = {};
        formData.forEach((value, key) => {
          formDataJSON[key] = value;
        });

        // Get the ID of the selected row
        const id = row.cells[0].textContent;

        // Add the ID to the formDataJSON object
        formDataJSON.id = id;

        fetch("update-user.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(formDataJSON),
        })
          .then((response) => response.json())
          .then((data) => {
            console.log('data:', data); // Log the response to the console

            alert('User updated successfully');

            // Reload the current page
            location.reload();

            // Hide the edit form
            hideEditForm();
          })
          .catch((error) => {
            console.error('error:', error);
            if (error instanceof SyntaxError) {
              console.log("The response from the server is not valid JSON.");
            }
          });
      });

    }

    function hideEditForm() {
      // Get the modal dialog box element
      const modal = document.querySelector(".modal");

      // Remove the modal dialog box and form elements from the page
      modal.remove();

      // Show the table row that was hidden
      const row = document.querySelector(".hidden");
      if (row) {
        row.classList.remove("hidden");
      }
    }

    const createUserButton = document.getElementById("create-user-button");

    createUserButton.addEventListener("click", () => {
      // Create a new div element to contain the form
      const modal = document.createElement("div");
      modal.classList.add("modal");

      // Display a form to collect user data
      const form = document.createElement("form");
      form.innerHTML = `
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" required>
        <br>
        <label for="role">Role:</label>
        <select id="role" name="role" required>
          <option value="">Select a role</option>
          <option value="admin">Admin</option>
          <option value="user">User</option>
        </select>
        <br>
        <button type="submit">Create User</button>
        <button type="button" onclick="hideEditForm()">Cancel</button>
      `;

      // Submit the form data to the server to create the new user
      form.addEventListener("submit", (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        const formDataJSON = JSON.stringify(Object.fromEntries(formData));

        fetch("create-user.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: formDataJSON,
        })
          .then((response) => response.json())
          .then((data) => {
            console.log(data); // Log the response to the console

            // Reload the current page
            location.reload();
          })
          .catch((error) => {
            console.error(error);
            if (error instanceof SyntaxError) {
              console.log("The response from the server is not valid JSON.");
            }
          });
      });

      // Add the form to the modal dialog box
      modal.appendChild(form);

      // Add the modal dialog box to the page
      document.body.appendChild(modal);

      // Add CSS styles to position the modal dialog box in the center of the screen
      modal.style.display = "block";
      modal.style.position = "fixed";
      modal.style.zIndex = "1";
      modal.style.left = "0";
      modal.style.top = "0";
      modal.style.width = "100%";
      modal.style.height = "100%";
      modal.style.overflow = "auto";
      modal.style.backgroundColor = "rgba(0,0,0,0.4)";
      modal.style.paddingTop = "100px";
      modal.style.textAlign = "center";

      // Add CSS styles to hide the modal dialog box when the user clicks outside of it
      window.addEventListener("click", (event) => {
        const modal = document.querySelector(".modal");
        if (event.target == modal) {
          modal.style.display = "none";
        }
      });

      // Add the form to the page
      // document.body.appendChild(form);
    });

    function logout() {
      // Clear the localStorage
      localStorage.clear();

      // Redirect the user to the login page
      window.location.href = "login.php";
    }

    // Add a click event listener to the log out button
    document.getElementById("logout-button").addEventListener("click", logout);

  </script>
</body>
</html>
<?php
include('../controller/login_process.php');
class LoginPage
{
  private $user;

  public function __construct($conn)
  {
    $this->user = new User($conn);
	 $this->conn = $conn;
  }
  
  public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $controller = new login_process($this->conn);
            $controller->processLogin($username, $password);
        }
    }

  public function render()
  {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Login - SIM-CatFe</title>
      <link rel="stylesheet" href="../styles.css">
	  <style>
            .error-message {
                text-align: center;
                margin-top: 10px; /* Adjust the margin as needed */
                padding: 10px; /* Adjust the padding as needed */
                background-color: ; /* Background color for the error message */
                color: #ff0000; /* Text color for the error message */
            }
        </style>
    </head>

    <body>

      <header>
        <h1>SIM CatFe</h1>
      </header>

      <main>
        <center><h2>Login</h2></center>

<form action="../pages/login.php" method="post">

          <label for="username">Username:</label>
          <input type="username" id="username" name="username" required><br>
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required><br>
          <input type="submit" value="Login">
        <div class="error-message">
            <?php
            if (isset($_SESSION['login_error'])) {
                echo '<p>' . $_SESSION['login_error'] . '</p>';
                unset($_SESSION['login_error']);
            }
            ?>
        </div>
		</form>
      </main>

      <footer>
        <p>&copy; <?php echo date("Y"); ?> CatFe 2023</p>
      </footer>
    </body>
    </html>
    <?php

  }

}



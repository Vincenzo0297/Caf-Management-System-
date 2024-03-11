<?php
include('../config/session_start.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suspended Page - CatFe</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <header>
        <h1>SIM CatFe</h1>
    </header>
    

    <main>
 
    

        <div class="error-container">
            <p>Your account has been suspended.</p>
            <p>Please contact the system admin for further assistance.</p>
            <a href="../controller/logout.php">Logout</a>
        </div>
    </main>
    

    <footer>
        <p>&copy; <?php echo date("Y"); ?> CatFe 2023</p>
    </footer>
</body>

</html>

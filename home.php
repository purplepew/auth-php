<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>


    <?php
    session_start();

    $username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
    echo "<h1>Welcome, $username</h1>";
    ?>

</body>

</html>
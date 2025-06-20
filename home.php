<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/logoutBtn.css" />
    <link rel="stylesheet" href="./styles/catsGallery.css" />
    <link rel="stylesheet" href="./styles/infoForms.css" />
    <script src="./scripts/infoSubmitHandler.js" defer></script>
    <script src="./scripts/logoutHandler.js" defer></script>
    <script src="./scripts/prefetchCats.js" defer></script>
    <script src="./scripts/renderCats.js" defer></script>
    <title>Document</title>
</head>

<body>


    <?php
    session_start();

    // Redirect if 'username' session is not set
    if (!isset($_SESSION['username'])) {
        header('Location: index.html');
        exit();
    }

    require './utils/db.php';

    $username = $_SESSION['username'];
    $result = $conn->query("SELECT has_info FROM users WHERE username = '$username'");
    $user = $result->fetch_assoc();
    ?>

    <?php
    // Fetch has_info for JS
    $hasInfoJS = $user['has_info'] ? 'true' : 'false';
    ?>

    <div class="welcome">
        <p>Welcome, <strong><?= htmlspecialchars($username) ?></strong>!</p>
        <button id="logoutBtn" class="danger-btn">Logout</button>
        <p id="logoutResponse" class="toaster"></p>
    </div>

    <?php if (!$user['has_info']): ?>
        <form method="POST" id="infoForm" class="form-card form-panel">
            <div class="row">
                <div class="form-group">
                    <label for="Firstname" class="form-label" title="15 Characters max">First name:</label>
                    <input type="text" name="Firstname" required class="form-input">
                </div>

                <div class="form-group">
                    <label for="Lastname" class="form-label">Last name:</label>
                    <input type="text" name="Lastname" required class="form-input">
                </div>
            </div>

            <div class="form-group">
                <label for="Gender" class="form-label">Gender:</label>
                <input type="text" name="Gender" required class="form-input">
            </div>

            <div class="form-group">
                <label for="Birthday" class="form-label">Birthday:</label>
                <input type="date" name="Birthday" required class="form-input">
            </div>

            <div class="form-group">
                <label for="Address" class="form-label">Address:</label>
                <input type="text" name="Address" required class="form-input">
            </div>

            <p id="infoResponse" class="toaster"></p>

            <input type="submit" value="Add information" class="primary-btn">
            <p>Fill out your additional information to see more cats!</p>
        </form>
    <?php else: ?>
        <p>You’ve already completed your profile. 🎉</p>
    <?php endif; ?>

    <h1>🐱 Cat Gallery</h1>
    <div id="catGallery" class="masonry"></div>

    <script>
        const hasInfo = <?= $hasInfoJS ?>;
    </script>

</body>

</html>
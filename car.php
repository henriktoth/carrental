<?php
require_once "jsonstorage.php";
require_once "auth.php";

session_start();

$auth = new Auth();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Details</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <header class="header">
        <div class="logo"><a href="index.php">iKarRental</a></div>
        <div class="buttons">
        <?php if ($auth->is_authenticated()): ?>
            <img src="./images/user.png" alt="user" class="userimg">
            <a href="logout.php" class="logout">Kijelentkezés</a>
        <?php else: ?>
            <a href="login.php" class="login">Bejelentkezés</a>
            <a href="register.php" class="register">Regisztráció</a>
        <?php endif; ?>
        </div>
    </header>

    <main class="main">
        <?php
            $carId = $_GET['id'];
            $carsData = json_decode(file_get_contents('./data/cars.json'), true);
            $car = null;
            foreach ($carsData as $c) {
                if ($c['id'] == $carId) {
                    $car = $c;
                    break;
                }
            }
        ?>
                <div class="car">
                    <img src="<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['model']); ?>" class="car-image">
                    <div class="car-details">
                        <h2><?php echo htmlspecialchars($car['brand']) . ' ' . htmlspecialchars($car['model']); ?></h2>
                        <p>Évárat: <?php echo htmlspecialchars($car['year']); ?></p>
                        <p>Váltó: <?php echo htmlspecialchars($car['transmission']); ?></p>
                        <p>Üzemanyag: <?php echo htmlspecialchars($car['fuel']); ?></p>
                        <p>Utasok száma: <?php echo htmlspecialchars($car['passengers']); ?></p>
                        <p>Ár: <?php echo number_format($car['price'], 0, ',', ' ') . " Ft"; ?></p>
                    </div>
                </div>
    <form>
        <button class="car_select_date">Dátum választása</button>
        <button class="car_reserve">Lefoglalom</button>
    </form>
    </main>
</body>
</html>

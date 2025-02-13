<?php
require_once "jsonstorage.php";
require_once "auth.php";

session_start();

$auth = new Auth();

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>

    <header>
        <div class="logo">iKarRental</div>
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
        <h1>Kölcsönözz autókat könnyedén!</h1>
        <?php if (!$auth->is_authenticated()): ?>
            <a href="register.php" class="register">Regisztráció</a>
        <?php endif; ?>
        
        <form method="GET">
            <div class="filters">
                <input type="number" name="passengers" placeholder="Férőhely" min="0">
                <input type="date">
                <input type="date">
                <select name="transmission">
                    <option value="Manual">Manuális</option>
                    <option value="Automatic">Automata</option>
                </select>
                <input type="number" name="price_min" placeholder="Ár (min)" min="0">
                <input type="number" name="price_max" placeholder="Ár (max)" min="0">
                <button class="filter-btn" type="submit">Szűrés</button>
            </div>
        </form>

        <?php
        $jsonData = file_get_contents('./data/cars.json');
        $cars = json_decode($jsonData, true);
        
        if (!empty($_GET)) {
            $passengers = isset($_GET['passengers']) ? (int)$_GET['passengers'] : null;
            $transmission = isset($_GET['transmission']) ? $_GET['transmission'] : null;
            $price_min = isset($_GET['price_min']) ? (int)$_GET['price_min'] : null;
            $price_max = isset($_GET['price_max']) ? (int)$_GET['price_max'] : null;

            $filteredCars = array_filter($cars, function ($car) use ($passengers, $transmission, $price_min, $price_max) {
                $matches = true;
                if ($passengers !== null && $car['passengers'] < $passengers) {
                    $matches = false;
                }
                if ($transmission !== null && $car['transmission'] !== $transmission) {
                    $matches = false;
                }
                if ($price_min !== null && $car['price'] < $price_min) {
                    $matches = false;
                }
                if ($price_max !== null && $car['price'] > $price_max) {
                    $matches = false;
                }

                return $matches;
            });

            foreach ($filteredCars as $car) {
                echo '<div class="car-card">';
                echo '<img src="' . htmlspecialchars($car["image"]) . '" alt="' . htmlspecialchars($car["brand"] . ' ' . $car["model"]) . '">';
                echo '<h2>' . htmlspecialchars($car["brand"] . ' ' . $car["model"]) . '</h2>';
                echo '<p>' . htmlspecialchars($car["passengers"]) . ' férőhely - ' . htmlspecialchars($car["transmission"] . ' - ' . $car["fuel"]) . '</p>';
                echo '<div class="price">' . htmlspecialchars(number_format($car["price"], 0, '.', ' ')) . ' Ft</div>';
                echo '<a href="car.php?id=' . htmlspecialchars($car["id"]) . '" class="book-btn" style="text-decoration: none;">Foglalás</a>';
                echo '</div>';
            }
        }
        else{
            foreach ($cars as $car) {
                echo '<div class="car-card">';
                echo '<img src="' . htmlspecialchars($car["image"]) . '" alt="' . htmlspecialchars($car["brand"] . ' ' . $car["model"]) . '">';
                echo '<h2>' . htmlspecialchars($car["brand"] . ' ' . $car["model"]) . '</h2>';
                echo '<p>' . htmlspecialchars($car["passengers"]) . ' férőhely - ' . htmlspecialchars($car["transmission"] . ' - ' . $car["fuel"]) . '</p>';
                echo '<div class="price">' . htmlspecialchars(number_format($car["price"], 0, '.', ' ')) . ' Ft</div>';
                echo '<a href="car.php?id=' . htmlspecialchars($car["id"]) . '" class="book-btn" style="text-decoration: none;">Foglalás</a>';
                echo '</div>';
            }
        }
        ?>
    </main>
</body>
</html>

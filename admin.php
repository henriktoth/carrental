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
    <h1>Admin felület</h1>
    <form method="GET" class="admin-form">
        <h2>Új autó hozzáadása</h2>
        <input type="text" name="brand" placeholder="Márka" required>
        <input type="text" name="model" placeholder="Model" required>
        <input type="number" name="year" placeholder="Évjárat" required>
        <select name="transmission" required>
            <option value="">Válassz váltót</option>
            <option value="Manual">Manuális</option>
            <option value="Automatic">Automata</option>
        </select>
        <select name="fuel" required>
            <option value="">Válassz üzemenyagot</option>
            <option value="Benzin">Benzin</option>
            <option value="Dizel">Dízel</option>
            <option value="Elektromos">Elektromos</option>
            <option value="Hibrid">Hibrid</option>
        </select>
        <input type="number" name="passengers" placeholder="Utasok száma" required>
        <input type="number" name="price" placeholder="Ár" required>
        <button type="submit">Add Car</button>
    </form>
    <?php
    $errors = [];
    if (!empty($_GET)) {
        // Év validáció
        $currentYear = date('Y');
        if ($_GET['year'] < 1900 || $_GET['year'] > $currentYear) {
            $errors[] = "Az évjárat 1900 és " . $currentYear . " között lehet.";
        }

        // Utasok számának validációja
        if ($_GET['passengers'] < 1 || $_GET['passengers'] > 9) {
            $errors[] = "Az utasok száma 1 és 9 között lehet.";
        }

        // Ár validációja
        if ($_GET['price'] <= 0 || $_GET['price'] > 1000000) {
            $errors[] = "Az ár 1 Ft és 1.000.000 Ft között lehet.";
        }

        if (!empty($errors)) {
            echo '<div class="error-messages">';
            foreach ($errors as $error) {
            echo '<p class="error" style="color: red;">' . htmlspecialchars($error) . '</p>';
            }
            echo '</div>';
        }
        else{
            $newCar = [
                'id' => uniqid(),
                'brand' => $_GET['brand'],
                'model' => $_GET['model'],
                'year' => intval($_GET['year']),
                'transmission' => $_GET['transmission'],
                'fuel' => $_GET['fuel'],
                'passengers' => intval($_GET['passengers']),
                'price' => intval($_GET['price']),
                'image' => ".\/images\/car.jpg"
            ];
            
            $jsonData = file_get_contents('./data/cars.json');
            $cars = json_decode($jsonData, true);
            $cars[] = $newCar;
            file_put_contents('./data/cars.json', json_encode($cars, JSON_PRETTY_PRINT));
            header('Location: admin.php');
            exit();
        }
    }
    ?>
    <?php
        $jsonData = file_get_contents('./data/cars.json');
        $cars = json_decode($jsonData, true);
        foreach ($cars as $car) {
            echo '<div class="car-card">';
            echo '<img src="' . htmlspecialchars($car["image"]) . '" alt="' . htmlspecialchars($car["brand"] . ' ' . $car["model"]) . '">';
            echo '<h2>' . htmlspecialchars($car["brand"] . ' ' . $car["model"]) . '</h2>';
            echo '<p>' . htmlspecialchars($car["passengers"]) . ' férőhely - ' . htmlspecialchars($car["transmission"] . ' - ' . $car["fuel"]) . '</p>';
            echo '<div class="price">' . htmlspecialchars(number_format($car["price"], 0, '.', ' ')) . ' Ft</div>';
            echo '<a class="delete-btn" style="text-decoration: none;">Törlés</a>';
            echo '</div>';
        }
        ?>
    </main>
</body>
</html>

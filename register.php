<?php
require_once "auth.php";

$errors = [];
$auth = new Auth();
function is_empty($input, $key)
{
    return !(isset($input[$key]) && trim($input[$key]) !== "");
}
function validate($input, &$errors, $auth)
{
    if (is_empty($input, "username")) {
        $errors[] = "Teljes név megadása kötelező";
    } elseif (strpos($input['username'], ' ') === false) {
        $errors[] = "A névnek tartalmaznia kell legalább egy szóközt";
    }
    if (is_empty($input, "email")) {
        $errors[] = "E-mail megadása kötelező";
    } elseif (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Érvénytelen e-mail formátum";
    }
    if (is_empty($input, "password")) {
        $errors[] = "Jelszó megadása kötelező";
    }
    if (is_empty($input, "password_again")) {
        $errors[] = "Ismételt jelszó megadása kötelező";
    } elseif ($input['password'] !== $input['password_again']) {
        $errors[] = "A két jelszó nem egyezik";
    }
    if (count($errors) == 0) {
        if ($auth->user_exists($input['username'])) {
            $errors[] = "A felhasználó már létezik";
        }
    }

    return !(bool)$errors;
}
if (count($_POST) != 0) {
    if (validate($_POST, $errors, $auth)) {
        $auth->register($_POST);
        header('Location: login.php');
        exit();
    }
}
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
        <div class="logo"> <a href="index.php">iKarRental</a></div>
        <div class="buttons">
            <a href="login.php" class="login">Bejelentkezés</a>
            <a href="register.php" class="register">Regisztráció</a>
        </div>
    </header>

    <main class="main">
        <h1>REGISZTRÁCIÓ</h1>
        <div class="login-form">
            <form method="POST">
                <label for="username">Teljes név<label>
                <input type="text" name="username" placeholder="Teljes név">
                <label for="email">E-mail<label>
                <input type="email" name="email" placeholder="E-mail">
                <label for="password">Jelszó</label>
                <input type="password" name="password" placeholder="Jelszó">
                <label for="password">Jelszó újra</label>
                <input type="password" name="password_again" placeholder="Jelszó újra">
                <button type="submit">Belépés</button>
            </form>
            <?php if ($errors) {?>
            <ul style="list-style: none;">
                <?php foreach ($errors as $error) {?>
                <li style="color: red;"><?=$error?></li>
                <?php }?>
            </ul>
            <?php }?> 
        </div>

    </main>
</body>
</html>

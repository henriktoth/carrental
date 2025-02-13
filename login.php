<?php
require_once "auth.php";

session_start();

$errors = [];
$auth = new Auth();

function is_empty($input, $key)
{
    return !(isset($input[$key]) && trim($input[$key]) !== "");
}

function validate($input, &$errors, $auth)
{

    if (is_empty($input, "email")) {
        $errors[] = "E-mail megadása kötelező";
    }
    if (is_empty($input, "password")) {
        $errors[] = "Jelszó megadása kötelező";
    }
    if (count($errors) == 0) {
        if (!$auth->check_credentials($input['email'], $input['password'])) {
            $errors[] = "Hibás E-mail vagy jelszó";
        }
    }

    return !(bool) $errors;
}
$errors = [];
if (count($_POST) != 0) {
    if (validate($_POST, $errors, $auth)) {
        $auth->login($_POST);
        if ($_POST['email'] === 'admin@ikarrental.hu') {
            header('Location: admin.php');
        } else {
            header('Location: index.php');
        }
        die();
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
        <div class="logo"><a href="index.php">iKarRental</a></div>
        <div class="buttons">
            <a href="login.php" class="login">Bejelentkezés</a>
            <a href="register.php" class="register">Regisztráció</a>
        </div>
    </header>

    <main class="main">
        <h1>BELÉPÉS</h1>
        <div class="login-form">
            <form method="POST">
                <label for="email">E-mail cim<label>
                <input type="email" name="email" placeholder="E-mail">
                <label for="password">Jelszó</label>
                <input type="password" name="password" placeholder="Jelszó">
                <button type="submit">Belépés</button>
            </form>
            <?php if ($errors) {?>
            <ul>
                <?php foreach ($errors as $error) {?>
                <li><?=$error?></li>
                <?php }?>
            </ul>
            <?php }?> 
        </div>
    </main>
</body>
</html>

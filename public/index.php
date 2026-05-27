<?php
session_start();
require '../src/db.php';
require '../src/AccountRepository.php';

$accountRepo = new AccountRepository($pdo);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF-Validation failed, request denied.");
    }

    $card = $_POST['cardnumber'];
    $pin = $_POST['pincode'];

    $user = $accountRepo->getCustomerByCardNumber($card);


    if ($user && password_verify($pin, $user['pin_hash'])) {

        session_regenerate_id(true);

        $_SESSION['card_number'] = $card;
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: ../templates/admin.php");
        } else {
            header("Location: ../templates/dashboard.php");
        }
        exit();
    } else {
        $error = "Felaktig Kort-nummer eller PIN-kod!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATM</title>
    <link rel="stylesheet" href="../templates/css/atmproject.css">

</head>

<body>
    <h1 class="wrapper header-title">ATM</h1>
    <form action="" method="POST" class="wrapper hero-section-form">
        <div class="cardnumber-and-pin">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <label for="cardnumber" class="cardnumber-title">Card Number</label> <br>
            <input type="text" placeholder="Card-Number" name="cardnumber" id="cardnumber" class="cardnumber-input" maxlength="4">
            <br>
            <label for="pincode" class="pincode-title">PIN-Code</label> <br>
            <input type="password" placeholder="PIN-Code" name="pincode" id="pincode" class="pincode-input" maxlength="4">
            <br>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <button class="login-button" type="submit">Log in</button>
        </div>
    </form>
</body>

</html>
<?php
session_start();
include "database/pdo_connection.php";

if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}

$error = "";

if (
    isset($_POST['email']) && $_POST['email'] !== ''
    && isset($_POST['password']) && $_POST['password'] !== ''
) {
    if (isset($_POST['sub'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // استفاده از prepared statements
        $result = $conn->prepare("SELECT * FROM users WHERE email=?");
        $result->bindValue(1, $email);
        $result->execute();

        if ($result->rowCount() >= 1) {
            $user = $result->fetch(PDO::FETCH_ASSOC);

            // استفاده از password_verify برای بررسی هش شده بودن رمز عبور
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header("location:panel/index.php");
                exit();
            } else {
                $error = "رمز عبور اشتباه است";
            }
        } else {
            $error = "ایمیل اشتباه است";
        }
    }
} else {
    if (!empty($_POST)) {
        $error = "فرم را پر کنید";
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="styles/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/css/style.css">
    <link rel="stylesheet" href="styles/css/auth.css">
    <!-- Css Reset -->
    <link rel="stylesheet" href="styles/css/reset.css">
    <!-- Vazir Font -->
    <link rel="stylesheet" href="fonts/vazir.css">
    <!-- Fontawsome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
          integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>ورود به حساب کاربری</title>
</head>
<body>
<section class="d-flex justify-content-center align-items-center min-h-screen bg">
    <div id="overlay"></div>
    <div class="form-container">
        <form action="#" method="POST">
            <section style="color:red;">
                <?php
                if ($error !== "") echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8');
                ?>
            </section>

            <h1 class="title">ورود به حساب کاربری</h1>
            <div class="mt-3 position-relative">
                <input name="email" type="email" class="field " placeholder="ایمیل ..."
                       value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <i class="fa fa-user field_icon"></i>
            </div>
            <div class="mt-3 position-relative">
                <input name="password" type="password" class="field " id="fieldPass" placeholder="رمز عبور ...">
                <i class="fa fa-lock field_icon"></i>
                <button type="button" id="showPass"></button>
            </div>

            <div class="mt-3">
                <button name="sub" type="submit" class="btn-submit bg-primary ">
                    <i class="fa fa-sign-in ms-1"></i>
                    <span>ورود به حساب کاربری</span>
                </button>
            </div>

            <p class="text">
                حساب کاربری ندارید ؟ <a href="/blog-pure/register.php" class="text-primary">یکی بسازید</a>
            </p>
        </form>
    </div>
</section>

<script src="js/showPassword.js"></script>
<script src="js/darkMode.js"></script>
</body>
</html>

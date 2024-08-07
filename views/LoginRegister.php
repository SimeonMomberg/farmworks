<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmworks</title>
    <link rel="stylesheet" href="../assets/css/LoginRegister.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
</head>
<body>

    <div class="wrapper">
        <div class="card-switch">
            <label class="switch">
                <input type="checkbox" class="toggle">
                <span class="slider"></span>
                <span class="card-side"></span>
                <div class="flip-card__inner">
                    <div class="flip-card__front">
                        <div class="title">Log in</div>
                        <form class="flip-card__form" action="../assets/js/login.php" method="POST">
                            <input class="flip-card__input" name="email" placeholder="Email" type="email" required>
                            <input class="flip-card__input" name="password" placeholder="Password" type="password" required>
                            <button class="flip-card__btn" name="submit" type="submit">Let's go!</button>
                        </form>
                    </div>
                    <div class="flip-card__back">
                        <div class="title">Sign up</div>
                        <form class="flip-card__form" action="../assets/js/register.php" method="POST">
                            <input class="flip-card__input" name="name" placeholder="Name" type="text" required>
                            <input class="flip-card__input" name="email" placeholder="Email" type="email" required>
                            <input class="flip-card__input" name="password" placeholder="Password" type="password" required>
                            <button class="flip-card__btn" name="submit" type="submit">Confirm!</button>
                        </form>
                    </div>
                </div>
            </label>
        </div>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="<?= base_url('css/login.css'); ?>">
    <link rel="icon" type="image/png" href="<?= base_url('assets/favicon.png') ?>">
</head>

<body>
    <div class="left-section">
        <p>Xploréa</p>
    </div>
    <div class="right-section">
        <form action="<?= site_url('/auth/signup') ?>" method="POST" class="main-signup">
            <p class="header">Sign in</p> <br>
            <table class="isian">
                <tr>
                    <td>
                        <p>Email</p>
                    </td>
                </tr>
                <tr>
                    <td><input type="email" name="mail" id="mail" placeholder="Enter your email"></td>
                </tr>
                <tr>
                    <td>
                        <p>Username</p>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" name="user" id="user" placeholder="Create username"></td>
                </tr>
                <tr>
                    <td>
                        <p>Password</p>
                    </td>
                </tr>
                <tr>
                    <td><input type="password" name="pass" id="pass" placeholder="Create password"></td>
                </tr>
                <tr>
                    <td><br><input id="button" type="submit" value="Sign in"></td>
                </tr>
                <tr>
                    <td class="divider"><br><p>Or</p></td>
                </tr>
                <tr>
                    <td id="google">
                        <a href="#"><img src="Assets/pngwing.com.png" alt="">Sign in with Google</a>
                    </td>
                </tr>
                <tr>
                    <td class="signup">
                        <p>Already have an account? <a href="login-page.php">Log in</a></p>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <style>
        .right-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
    </style>
</body>
</html>
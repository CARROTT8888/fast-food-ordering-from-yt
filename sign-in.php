<?php
session_start();
include 'foodDB.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT userId, fullName, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['userId'] = $user['userId'];
        $_SESSION['fullName'] = $user['fullName'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: me/home.php");
        exit();
    } else {
        $error = "Invalid email or password.";
        die("<script>alert('$error'); window.history.back();</script>");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <!-- Webpage Icon -->
    <link rel="Icon" href="img/youtube icon.png">
    <style>
        *::selection {
            color: #fff;
            background: #eb4034;
        }

        body {
            align-items: center;
            /*box-sizing: border-box;*/
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .page {
            position: relative;
            top: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            align-items: center;
        }

        .card {
            width: 420px;
            background: #f1f1f1;
            border-radius: 10px;
            padding: 32px;
            color: var(--body-color);
            box-shadow: var(--body-shadow);
        }

        .title {
            margin: 0;
            font-size: 26px;
            font-weight: 600;
            align-items: center;
        }

        img {
            width: 30px;
            height: 30px;
            vertical-align: middle;
            margin-left: 5px;
            align-items: center;
        }

        .subtitle {
            margin: 8px 0 24px;
            font-size: 14px;
            color: #7d7d7d;
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: flex;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input {
            width: 95%;
            padding: 12px 14px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            outline: none;
        }

        input:focus {
            border-color: #eb4034;
            border: 5px sold #eb4034;
        }

        input::placeholder {
            color: #7d7d7d;
        }

        .password-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .password-wrapper {
            position: relative;
        }

        .remember {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .checkbox-icon {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .signin {
            width: 100%;
            padding: 12px;
            background: red;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .signin:hover {
            opacity: 0.9;
        }

        .signup {
            margin-top: 20px;
            font-size: 14px;
            color: #7d7d7d;
            text-align: center;
            align-items: center;
        }

        .signup a {
            text-decoration: underline;
            text-align: center;
            color: #eb4034;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <form method="POST">
        <section class="page">
            <div class="card">
                <h1 class="title">
                    Sign In
                    <?php $imageName = "circle-user.png";
                    $imagePath = "img/" . $imageName;
                    echo '<img src="' . $imagePath . '" alt="signup icon">'; ?>
                </h1>
                <p class="subtitle">
                    Sign in to continue to YouTube Food
                </p>

                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="johndoe@gmail.com" required />
                </div>

                <div class="field">
                    <label style="display: flex; justify-content: space-between;">
                        <span>Password</span>
                        <a style="color: red; text-decoration: underline;" href="forget-password.php">Forget Password?</a>
                    </label>
                    <div class="password-wrapper">
                        <input type="password" name="password" placeholder="********" required />
                    </div>
                </div>

                <div class="remember">
                    <input type="checkbox" class="checkbox-icon" />
                    <label htmlFor="remember">Remember me</label>
                </div>

                <button class="signin">Sign In</button>

                <p class="signup">
                    If you don't have an account, <a href="/web/sign-up">Sign Up</a>
                </p>
            </div>

        </section>
    </form>
</body>

</html>
<?php
session_start();
include 'foodDB.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contactNumber = $_POST['contactNumber'];
    $confirmPassword = $_POST['confirmPassword'];
    $address = $_POST['address'];

    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
        die("<script>alert('$error'); window.history.back();</script>");
    }

    if ($contactNumber && !preg_match('/^\d{3}-\d{7,10}$/', $contactNumber)) {
        $error = "Invalid contact number format. Please use XXX-XXXXXXX format.";
        die("<script>alert('$error'); window.history.back();</script>");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO users (fullName, username, email, password, contactNumber, address)
    VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "ssssss",
        $fullName,
        $username,
        $email,
        $hashedPassword,
        $contactNumber,
        $address
    );

    if ($stmt->execute()) {
        $_SESSION['userId'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        header("Location: sign-in.php");
        exit();
    } else {
        echo "ERROR" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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

        .forgot {
            font-size: 13px;
            color: var(--body-primary-color);
            text-decoration: underline;
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

        .signup {
            width: 100%;
            padding: 12px;
            background: red;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .signup:hover {
            opacity: 0.9;
        }

        .signin {
            margin-top: 20px;
            font-size: 14px;
            color: #7d7d7d;
            text-align: center;
            align-items: center;
        }

        .signin a {
            text-decoration: underline;
            text-align: center;
            color: #eb4034;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php if ($error): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <section class="page">
            <div class="card">
                <h1 class="title">
                    Sign Up
                    <?php $imageName = "circle-user.png";
                    $imagePath = "img/" . $imageName;
                    echo '<img src="' . $imagePath . '" alt="signup icon">'; ?>
                </h1>
                <p class="subtitle">
                    Register your email below to create your account
                </p>

                <div class="field">
                    <label>Full Name</label>
                    <input type="text" name="fullName" placeholder="John Doe" required />
                </div>

                <div class="field">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="johndoe" required />
                </div>

                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="johndoe@gmail.com" required />
                </div>

                <div class="field">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" placeholder="********" required />
                    </div>
                </div>

                <div class="field">
                    <label>Confirm Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="confirmPassword" placeholder="********" required />
                    </div>
                </div>
                <div class="field">
                    <label>Address</label>
                    <input type="text" name="address" placeholder="John Doe City, Malaysia" required />
                </div>

                <div class="field">
                    <label>Contact Number</label>
                    <input type="tel" name="contactNumber" placeholder="012-3456789" required />
                </div>

                <button class="signup">Sign Up</button>

                <p class="signin">
                    If you already have the account, <a href="/web/sign-in">Sign In</a>
                </p>
            </div>

        </section>
    </form>
</body>

</html>
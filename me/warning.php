<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Access Denied</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .warning-container {
            background-color: #ffffff;
            padding: 35px;
            width: 90%;
            max-width: 520px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .warning-image img {
            width: 100%;
            max-width: 260px;
            margin-bottom: 20px;
        }

        h1 {
            color: #dc3545;
            margin-bottom: 15px;
        }

        p {
            color: #333;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 28px;
            background-color: #28a745;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 15px;
        }

        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="warning-container">

    <!-- Image -->
    <div class="warning-image">
        <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fmedia.istockphoto.com%2Fid%2F1817119432%2Fvector%2Flocked-full-of-food-refrigerator-with-padlock-door-diet-concept-vector-graphic-design.jpg%3Fs%3D170667a%26w%3D0%26k%3D20%26c%3DwzAn_Zibs6WpAeQFjLCex_Mz1cPPKOlMXQLCtopshKg%3D&f=1&nofb=1&ipt=f560255484b8c4377f9f287f4826e5944cdcc18e0a8bfd572d041eab31c79570"  alt="Restricted Area">
    </div>

    <!-- Text -->
    <h1>Access Restricted</h1>

    <p>
        This area is reserved for administrators only.<br>
        You do not have permission to access this page.
    </p>

    <p>
        Please return to the homepage to continue browsing our food content.
    </p>

    <a href="/web/me" class="btn">Return to Homepage</a>

</div>

</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>YouTube Food</title>

    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <!-- CSS File -->
    <link rel="stylesheet" href="/styles/homepage.css">

    <!-- Webpage Icon -->
    <link rel="Icon" href="img/youtube icon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
        scroll-behavior: smooth;
        list-style: none;
        text-decoration: none;
    }

    :root {
        --primary-color: #eb4034;
        --text-color: #181818;
        --background-color: #fff;
        --primary-text-size: 5rem;
        --secondary-text-size: 4rem;
        --about-text-size: 50px;
        --p-text-size: 1.5rem;

        /*--button-background: dodgerblue;*/
        --button-color: white;
        /*--dropdown-highlight: dodgerblue;*/
        --dropdown-width: 160px;
        --dropdown-background: white;
        --dropdown-color: black;
    }

    *::selection {
        color: var(--background-color);
        background: var(--primary-color);
    }

    body {
        background: var(--background-color);
    }

    /*::-webkit-scrollbar {
    width: 20px;
}

::-webkit-scrollbar-track {
    background: #eb4034;
}*/

    header {
        position: fixed;
        top: 0;
        right: 0;
        width: 100%;
        height: auto;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 30px 170px;
        background: var(--background-color);
    }

    @media only screen and (max-width: 1000px) {
        /*.navbar {
        display: none;
        color: red;
    }

    .navbar a {
        display: none;
    }

    header {
        padding: 20px 40px;
    }*/

        .hero {
            grid-template-columns: 1fr;
            text-align: center;
            row-gap: 2rem;
        }

        .hero-text {
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin: auto;
            width: 300px;
        }

        .hero-text .today-menu-button {
            margin: auto;
        }

        a.button {
            display: none;
        }

    }

    @media screen and (max-width: 1200px) {
        .navbar {
            display: none;
            color: red;
        }

        .navbar a {
            display: none;
        }

        header {
            padding: 20px 40px;
        }
    }

    @media screen and (max-width: 850px) {
        /*.navbar {
        display: none;
        color: red;
    }

    .navbar a {
        display: none;
    }

    header {
        padding: 20px 40px;
    }*/

        .yt-icon-logo {
            display: none;
        }
    }

    .logo {
        color: var(--primary-color);
        font-weight: bolder;
        font-size: 2.4em;
    }

    .navbar {
        display: flex;
    }

    .navbar a {
        color: var(--text-color);
        font-size: 1.2em;
        padding: 10px;
    }

    .navbar a:hover {
        color: grey;
        transition: 0.5s;
    }

    #yt-icon {
        font-size: 2rem;
        cursor: pointer;
        display: none;
    }

    .hero-text span {
        font-weight: bold;
        font-size: 3.5rem;
    }

    .yt-icon-logo {
        width: 400px;
        height: 400px;
    }

    section {
        padding: 70px 17%;
        color: var(--text-color);
    }

    .hero {
        width: 100%;
        min-height: 90vh;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-gap: 1.5rem;
        align-items: center;
    }

    .hero h1 {
        font-size: var(--primary-text-size);
    }

    .hero h2 {
        margin: 1rem 0 2rem;
    }

    .today-menu-button {
        display: inline-block;
        padding: 10px;
        background: var(--primary-color);
        color: var(--background-color);
        border-radius: 5px;
        border-width: 0px;
        cursor: pointer;
    }

    .about h1 {
        font-size: var(--about-text-size);
        text-align: center;
    }

    .about p {
        margin: 8px;
        line-height: 1.7em;
        font-size: var(--p-text-size);
    }

    .about .learn-more-button {
        font-size: 20px;
        display: inline-block;
        padding: 10px;
        background: var(--primary-color);
        color: var(--background-color);
        border-radius: 5px;
        border-width: 0px;
        cursor: pointer;
    }

    .about .learn-more-button:hover {
        background: #181818;
        color: #fff;
        transition: 0.3s;
    }

    .about .learn-more-button:focus {
        outline-color: transparent;
        /*outline-style: solid;*/
        box-shadow: 0 0 0 2px aliceblue;
    }

    .section-title {
        text-align: center;
    }

    .section-title h1 {
        font-size: var(--secondary-text-size);
    }

    .section-title span {
        font-size: 20px;
    }

    @media screen and (max-width: 1024px) {
        .menu-box {
            width: 300px;
        }
    }

    @media screen and (max-width: 768px) {
        .menu-box {
            width: 100%;
        }
    }

    .menu-box-img img {
        width: 200px;
        height: 189px;
    }

    .menu-items {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(330px, 1fr));
    gap: 4rem;
    padding: 1rem;
    justify-items: center;
    justify-content: center;
}

.menu-box {
    width: 330px;
    height: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f1f1f1;
    border-radius: 20px;
    justify-self: center;
}

    .menu-box h2 {
        text-decoration: underline;
    }

    .menu-box h3 {
        font-size: 18px;
        margin: 4px;
    }

    .menu-box span {
        font-size: 18px;
        text-decoration: line-through;
    }

    .menu-box button {
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
        font-size: 25px;
        width: 150px;
        height: 45px;
        margin: 5px;
        background: white;
        cursor: pointer;
        border-width: 0px;
        border-radius: 10px;
        color: var(--text-color);
    }

    .menu-box button:hover {
        background: #595a5c;
        transition: 0.5s;
    }

    .menu-box button:focus {
        outline-color: transparent;
        /*outline-style: solid;*/
        box-shadow: 0 0 0 2px aliceblue;
    }

    .menu-box:hover {
        width: 350px;
        height: 450px;
        transition: 0.3s;
    }

    .order {
        text-align: center;
    }

    .order span {
        font-size: 30px;
        font-family: Arial;
        line-height: 60px;
    }

    .order .order-button {
        width: 100px;
        height: 50px;
        /*margin: auto;*/
        font-size: 20px;
        font-weight: 500;
        border-radius: 20px;
        cursor: pointer;
        background: grey;
        color: aliceblue;
    }

    .order .order-button:hover {
        background: var(--primary-color);
        transition: 0.5s;
    }

    .order .order-button:focus {
        outline-color: transparent;
        /*outline-style: solid;*/
        box-shadow: 0 0 0 2px aliceblue;
    }

    .service-items {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, auto));
        grid-gap: 1.5rem;
        align-items: center;
        margin: 20px;
    }

    .service-box {
        text-align: center;
        padding: 20px 25px;
    }

    .service-box img {
        width: 120px;
        height: 120px;
    }

    .service-box h2 {
        margin: 4px;
        font-size: 22px;
        text-decoration: underline;
    }

    .service-box span {
        line-height: 1.5;
        /*line-height: 1.7*/
        ;
    }

    .members {
        background: #f1f1f1;
        padding: 65px;
        text-align: center;
        max-width: 85%;
        margin: 100px;
        border-radius: 10px;
    }

    .members .join-button {
        font-size: 25px;
        width: 150px;
        height: 55px;
        margin: 5px;
        background: #fff;
        color: #181818;
        cursor: pointer;
    }

    .members .join-button:hover {
        background: #181818;
        color: #fff;
        transition: 0.3s;
    }

    .members .join-button:focus {
        outline-color: transparent;
        /*outline-style: solid;*/
        box-shadow: 0 0 0 2px aliceblue;
    }

    .内容 {
        display: flex;
        flex-wrap: wrap;
    }

    .脚本 {
        padding: 10px;
    }

    .一起486 {
        /*width: 25%;*/
        width: 245px;
    }

    .一起486 h4 {
        font-size: 25px;
        /*color: var(--字幕顔色);*/
        margin-bottom: 24px;
        position: relative;
    }

    .一起486 h4::before {
        content: '';
        position: absolute;
        height: 4px;
        width: 55px;
        left: 0;
        bottom: -8px;
        background: var(--其顔色);
    }

    .一起486 ul li:not(:last-child) {
        margin-bottom: 13px;
    }

    .一起486 ul li a {
        color: gray;
        display: block;
        font-size: 1.1rem;
        text-transform: capitalize;
        transition: .3s;
    }

    .一起486 ul li a:hover {
        color: var(--字幕顔色);
        transform: translateX(-10px);
    }

    .一起486 .社交媒體 {
        width: 250px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .一起486 .社交媒體 a {
        height: 35px;
        width: 35px;
        background: var(--primary-color);
        color: var(--text-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50px;
        font-size: 20px;
        transition: .4s;
    }

    .一起486 .社交媒體 a:hover {
        transform: scale(1.3);
        color: var(--background-color);
        background: var(--text-color);
    }

    /* Boring button styles */
    a.button {
        /* Frame */
        display: flex;
        border-radius: 50px;
        box-sizing: border-box;

        /* Style */
        border: none;

        cursor: pointer;
    }

    a.button:active {
        filter: brightness(75%);
    }

    /* Dropdown styles */
    .dropdown {
        position: relative;
        padding: 0;
        margin-right: 1em;
    }

    .dropdown summary {
        list-style: none;
        list-style-type: none;
    }

    .dropdown>summary::-webkit-details-marker {
        display: none;
    }

    .dropdown summary:focus {
        outline: none;
    }

    .dropdown summary:focus a.button {
        border: 2px solid black;
    }

    .dropdown summary:focus {
        outline: none;
    }

    .dropdown ul {
        position: absolute;
        margin: 20px 0 0 0;
        padding: 20px 0;
        width: var(--dropdown-width);
        left: 50%;
        margin-left: calc((var(--dropdown-width) / 2) * -1);
        box-sizing: border-box;
        z-index: 2;
        border: 1px solid var(--dropdown-color);
        background: var(--dropdown-background);
        border-radius: 6px;
        list-style: none;
    }

    .dropdown ul li {
        padding: 0;
        margin: 0;
    }

    .dropdown ul li a:link,
    .dropdown ul li a:visited {
        display: inline-block;
        padding: 10px 0.8rem;
        width: 100%;
        box-sizing: border-box;

        color: var(--dropdown-color);
        text-decoration: none;
    }

    .dropdown ul li a:hover {
        background-color: var(--primary-color);
        color: var(--dropdown-background);
    }

    /* Dropdown triangle */
    .dropdown ul::before {
        content: ' ';
        position: absolute;
        width: 0;
        height: 0;
        top: -10px;
        left: 50%;
        margin-left: -10px;
        border-style: solid;
        border-width: 0 10px 10px 10px;
        border-color: transparent transparent var(--dropdown-color) transparent;
    }


    /* Close the dropdown with outside clicks */
    .dropdown>summary::before {
        display: none;
    }

    .dropdown[open]>summary::before {
        content: ' ';
        display: block;
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        z-index: 1;
    }
    </style>

</head>

<body>
    <!-----header----->
    <?php include_once 'includes/home/header.php'; ?>

    <!-----hero----->
    <?php include_once 'includes/home/hero.php'; ?>

    <!-----about----->
    <?php include_once 'includes/home/about.php'; ?>

    <!-----today menu----->
    <?php include_once 'includes/home/menu.php'; ?>

    <!-----services----->
    <?php include_once 'includes/home/services.php'; ?>


    <!-----members----->
    <section class="members">
        <h2>Join us to get discount! <br> Lets start</h2>
        <button class="join-button" onclick="monyet.play();">Join us! <i class='bx bxs-ghost'></i></button>
    </section>

    <!-----contact----->
    <?php include_once 'includes/home/footer.php'; ?>

    <script src="script.js"></script>
</body>

</html>
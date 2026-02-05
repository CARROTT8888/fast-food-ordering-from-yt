<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    
    <title>About Us</title>

    <style>
        /* CSS Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
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
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        header {
            background-color: #eb4034;
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 0 20px;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* About Section */
        .about-section {
            padding: 4rem 0;
        }

        .about-content {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            align-items: center;
        }

        .about-text {
            flex: 1;
            min-width: 300px;
        }

        .about-image {
            flex: 1;
            min-width: 300px;
            text-align: center;
        }

        .about-image img {
            max-width: 100%;
            border-radius: 10px;
            /*box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);*/
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }

        p {
            margin-bottom: 1.5rem;
        }

        /* Team Section */
        .team-section {
            background-color: white;
            padding: 4rem 0;
        }

        .team-members {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }

        .team-member:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .team-member {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            width: 280px;
            text-align: center;
            transition: transform 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
        }

        .team-member:hover {
            transform: translateY(-10px);
        }

        .member-image {
            height: 220px;
            overflow: hidden;
        }

        .member-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .member-info {
            padding: 1.5rem;
        }

        .member-info h3 {
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .member-info p {
            color: #7f8c8d;
            font-style: italic;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-top: 2rem;
        }

        .back-to-top {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #3498db;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            text-align: center;
            line-height: 50px;
            font-size: 1.5rem;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .about-content {
                flex-direction: column;
            }
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
            text-decoration: none;
        }

        .一起486 .社交媒體 a:hover {
            transform: scale(1.3);
            color: var(--background-color);
            background: var(--text-color);
        }
    </style>
</head>

<body>

    <header>
        <div class="container">
            <h1>About Our Food Project</h1>
            <p class="subtitle">Meet the people behind our passion for food</p>
        </div>
    </header>

    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Our Story</h2>
                    <p>Our mission is to give everyone a voice and show them the world.
                        We believe that everyone deserves to have a voice,
                        and that the world is a better place when we listen,
                        share and build community through our stories.
                        <br>
                        Now we have a new mission! That new mission is to give everyone an enjoy a viewing experience
                        for the best,
                        so we made the menu to let everyone order our foods!
                        We always believe everyone deserves to have a nice viewing experience for enjoy everytime!
                        Here we go to order now.
                    </p>
                </div>

                <div class="about-image">
                    <img src="./img/youtube-logo.jpg" alt="Food project image">
                </div>
            </div>
        </div>
    </section>

    <section class="team-section">
        <div class="container">
            <h2>Meet Our Team</h2>

            <div class="team-members">

                <div class="team-member">
                    <div class="member-image">
                        <img src="./img/member1.jpg" alt="KieranOngZhiMing">
                    </div>
                    <div class="member-info">
                        <h3>Kieran Ong Zhi Ming</h3>
                        <p>242DT242C7</p>
                    </div>
                </div>

                <div class="team-member">
                    <div class="member-image">
                        <img src="./img/member2.jpg" alt="DanielPuaZiJian">
                    </div>
                    <div class="member-info">
                        <h3>Daniel Pua Zi Jian</h3>
                        <p>242DT241JQ</p>
                    </div>
                </div>

                <div class="team-member">
                    <div class="member-image">
                        <img src="./img/member3.jpg" alt="TengXinNing">
                    </div>
                    <div class="member-info">
                        <h3>Teng Xin Ning</h3>
                        <p>242DT241KC</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-----contact----->
    <?php include_once 'includes/home/footer.php'; ?>

    <div class="back-to-top" id="backToTop">↑</div>

    <script>
        const backToTopButton = document.getElementById('backToTop');

        window.addEventListener('scroll', () => {
            backToTopButton.style.display = window.pageYOffset > 300 ? 'block' : 'none';
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        const teamMembers = document.querySelectorAll('.team-member');

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });

        teamMembers.forEach(member => observer.observe(member));
    </script>

</body>

</html>
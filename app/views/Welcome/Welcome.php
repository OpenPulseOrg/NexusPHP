<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ title }}</title>
    {{ cssPath }}
    {{ faviconPath }}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">


</head>

<body>
    <nav class="nav-bar">
        <div class="container">
            <div class="logo">NexusPHP Framework</div>
        </div>
    </nav>

    <header class="hero" data-aos="fade-in">
        <div class="container">
            <h1 class="headline">Unleash the Power of PHP</h1>
            <p class="sub-headline">NexusPHP offers an efficient, powerful, and flexible toolset for web application development.</p>
            <a href="#" class="cta">Get Started</a>
        </div>
    </header>

    <main class="main-content" data-aos="fade-up">
        <div class="container">
            <section class="info-section">
                <h2 class="section-heading">Why NexusPHP?</h2>
                <p class="section-description">NexusPHP is designed with a simple and intuitive code structure. It reduces development complexity while providing powerful features that facilitate high-performance applications. Explore our GitHub repository for more details and code samples.</p>
                <a href="https://github.com/kevingorman1000/NexusPHP" class="cta-secondary">Visit Our GitHub</a>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>© 2023 NexusPHP Framework. All rights reserved. | <a href="https://www.apache.org/licenses/LICENSE-2.0.txt" class="text-white">Apache License 2.0</a></p>
        </div>
    </footer>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

    {{ jsPath }}
</body>

</html>
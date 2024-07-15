<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Surge: About Us</title>
  <link rel="stylesheet" href="css/style2.css">
</head>

<body>
  <div class="cursor"></div>
  <button class="home-button">Return to Home</button>
  <section class="hero">
    <div class="heading">
      <h1>About Us</h1>
    </div>
    <div class="container">
      <div class="hero-content">
        <h2>Welcome To Our Website</h2>
        <p>
          Here we are dedicated to bringing you the most up-to-date and comprehensive game information and reviews. Whether you're an experienced gamer or a newbie, our site is the best place to learn about the world of gaming. Our team consists of a group of passionate game enthusiasts who provide you with high quality gaming news, in-depth reviews, strategy guides and industry news every day. We believe that gaming is not just a form of entertainment, but also an expression of art and culture. Through our content, we hope to share this passion with gamers around the world, help you discover new games, improve your gaming skills, and connect with like-minded people. Thank you for visiting Surge, and we hope you find everything you need here and enjoy every moment of your gaming time.
        </p>
      </div>
    </div>
  </section>

  <footer>
    <div class="contact-info">
      <p>Email: <a href="mailto:surgeportalnet@yahoo.com">surgeportalnet@yahoo.com</a></p>
    </div>
  </footer>

  <footer class="bg-dark text-light text-center">
    <p>&copy; 2024 Surge</p>
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
        const homeButton = document.querySelector(".home-button");
        
        homeButton.addEventListener("click", () => {
          window.location.href = "index.php";
      });
    });

    document.addEventListener('mousemove', (event) => {
      const cursor = document.querySelector('.cursor');
      cursor.style.left = `${event.pageX}px`;
      cursor.style.top = `${event.pageY}px`;
    });
  </script>

</body>
</html>

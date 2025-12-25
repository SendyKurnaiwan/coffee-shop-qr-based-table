<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kopi Bigen</title>

    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- feather icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- style -->
    <link rel="stylesheet" href="css/stylew.css" />
  </head>
  <body>
    <!-- navbar start -->
    <nav class="navbar">
      <a href="#" class="navbar-logo">Kopi<span>639</span></a>

      <div class="navbar-nav">
        <a href="#">Home</a>
        <a href="#about">Tentang Kami</a>
        <a href="/menu">Menu</a>
        <a href="#contact">kontak</a>
      </div>

      <div class="navbar-extra">
        <a href="#" id="search"><i data-feather="search"></i></a>
        <a href="#" id="shopping"><i data-feather="shopping-cart"></i></a>
        <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
      </div>
    </nav>

    <!-- navbar end -->

    <!-- hero section start -->
    <section class="hero" id="home">
      <main class="content">
        <h1>Mari Nikmati Secangkir <span>Kopi</span></h1>
        <p>
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Explicabo,
          voluptate.
        </p>
        <a href="/menu" class="cta">Beli Sekarang</a>
      </main>
    </section>

    <!-- hero section end -->

    <!-- about section start -->

    <section id="about" class="about">
      <h2><span>Tentang</span> Kami</h2>

      <div class="row">
        <div class="about-img">
          <img src="img/tentang-kami.jpg" alt="Tentang Kami" />
        </div>
        <div class="content">
          <h3>Kenapa memilih kopi kami?</h3>
          <p>
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maxime ex
            perspiciatis necessitatibus ipsam dicta nobis!
          </p>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum aut
            libero modi dicta nisi maxime quas veniam hic obcaecati excepturi.
          </p>
        </div>
      </div>
    </section>
    <section id="contact" class="contact">
      <h2><span>Kontak</span> Kami</h2>
      <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Amet, sequi.
        Velit quae veritatis adipisci veniam.
      </p>
      <div class="row">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d21778.896464244226!2d115.21243895009903!3d-8.67609161341027!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1677866648157!5m2!1sid!2sid"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          class="map"
        ></iframe>

        <form action="">
          <div class="input-group">
            <i data-faether="user"></i>
            <input type="text" placeholder="nama" />
          </div>
          <div class="input-group">
            <i data-faether="mail"></i>
            <input type="text" placeholder="email" />
          </div>
          <div class="input-group">
            <i data-faether="phone"></i>
            <input type="text" placeholder="no hp" />
          </div>
          <button type="submit" class="btn">kirim pesan</button>
        </form>
      </div>
    </section>

    <!-- contact section end -->

    <!-- footer start -->
    <footer>
      <div class="social">
        <a href="https://instagram.com/bintnggwijyaa?igshid=YmMyMTA2M2Y="><i data-feather="instagram"></i></a>
        <a href="#"><i data-feather="twitter"></i></a>
        <a href="#"><i data-feather="facebook"></i></a>
        <a href="https://wa.me/6285174280520"><i data-feather="message-circle"></i></a>
      </div>
      <div class="links">
        <a href="#">Home</a>
        <a href="#about">Tentang Kami</a>
        <a href="#home">Menu</a>
        <a href="#contact">Kontak</a>
      </div>

      <div class="credit">
        <p>Created By Bintang Wijaya | $copy; 2023.</p>
      </div>
    </footer>

    <!-- footer end -->

    <!-- faether icons -->
    <script>
      feather.replace();
    </script>
    <!-- my javascript -->
    <script src="js/script.js"></script>
  </body>
</html>

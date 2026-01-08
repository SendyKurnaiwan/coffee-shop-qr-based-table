<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gol</title>

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
      <a href="#" class="navbar-logo"><img src="img/logo goldmalabar.jpg"></a>
@auth
            <form action="{{ route('logout') }}" method="POST" style="display: inline; float: right;margin-top: 11px;">
                @csrf
                <button type="submit" class="btn btn-danger" >Logout</button>
            </form>
        @endauth
      <div class="navbar-nav">
        <a href="#">Home</a>
        <a href="#about">Tentang Kami</a>
        <a href="/menu">Menu</a>
        <a href="#contact">kontak</a>
      </div>

      <div class="navbar-extra">
        <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
      </div>
    </nav>

    <!-- navbar end -->

    <!-- hero section start -->
    <section class="hero" id="home">
      <main class="content">
        <video autoplay muted loop playsinline>
            <source src="img/videoplayback.mp4" type="video/mp4">
        </video>
        <div class="video-overlay"></div>
        <div  class="video-content">
          <h1>The Taste of Exquisite <span>Coffee</span></h1>
          <p> 
            Discover the rich and bold flavors of our premium Golden Malabar coffee, sourced from the finest plantations
          </p>
          <a href="/menu" class="cta" style="font-weight: bold;">BUY NOW</a>
        </div>
      </main>
    </section>

    <!-- hero section end -->

    <!-- about section start -->

    <section id="about" class="about">
      <h2><span>About </span>Us</h2>

      <div class="row">
        <div class="about-img">
          <img src="img/tentang-kami.jpg" alt="Tentang Kami" />
        </div>
        <div class="content">
          <h3>Golden Malabar</h3>
          <p>
            From the Highlands to the Island of the Gods.
Golden Malabar extends its heritage to Bali bringing the same spirit of craftsmanship, quality, and Indonesia coffee excellence to new horizons.
Our Bali showcase celebrates the art, culture, and hospitality offering a refined experience that reflects the essence of our origin. 
Where the spirit of Indonesia's highlands meets the warmth of Bali's island charm
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
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3943.605376931943!2d115.168498!3d-8.7289694!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd2474487ee2bbb%3A0x115bf5e7c2ba4853!2sGolden%20Malabar%20Bali!5e0!3m2!1sid!2sid!4v1767076180801!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

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

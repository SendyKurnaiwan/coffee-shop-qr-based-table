<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Kami</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/stylew.css">
   <!-- font -->
   <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- feather icons -->
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
<nav class="navbar">
    <a href="/" class="back"><-Back</a>


      <div class="navbar-extra">
        <a href="#" id="search"><i data-feather="search"></i></a>
        <a href="#" id="shopping"><i data-feather="shopping-cart"></i></a>
        <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
      </div>
    </nav>

  <section id="menu" class="menu">
    <h2><span>Menu</span> Kami</h2>
    <h4>
      Lorem ipsum dolor sit amet consectetur adipisicing elit. Libero magnam
      officia eius explicabo, pariatur veritatis?
    </h4>

    
    <div class="row">
      @foreach ($data as $row)
      <div class="box">
      <div class="menu-card">
        <img src="{{ asset('fotomenu/'.$row->foto) }}" alt="Espreso" class="menu-card-img" />
        <h3 class="menu-card-tittle">{{$row->nama}}</h3>
        <p class="menu-card-price">{{ frupiah($row->harga)}}</p>
        <a href="#" class="cta">Beli</a>
      </div>
    </div>
    @endforeach 
    </div>
  </section>
</body>
</html>
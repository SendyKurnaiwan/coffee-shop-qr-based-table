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
        <?php
          $imagePath = 'fotomenu/'.$row->foto;
          $extension = strtolower(pathinfo($row->foto, PATHINFO_EXTENSION));
          $isJpg = in_array($extension, ['jpg', 'jpeg', 'jfif']);
          $isPng = in_array($extension, ['png']);
          
          $boxStyle = '';
          
          if ($isJpg) {
            $boxStyle = "style=\"background-image: url('" . asset($imagePath) . "');
            
            border-bottom: 0px;
            background-position: center center;\"";
          }
        ?>

        <div class="box" <?php echo $boxStyle; ?>>
          <div class="menu-card"<?php if ($isJpg): echo "style=\"background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); border-radius: 0 0 20px 20px;\""; endif; ?>>
            <div>
              <?php if ($isPng): ?>
                <img src="{{ asset('fotomenu/'.$row->foto) }}" alt="{{$row->nama}}" class="menu-card-img" />
              <?php endif; ?>
              <h3 <?php if ($isJpg): echo "class=\"has-jpg-bg-title\""; else :
                echo "class=\"menu-card-title\""; endif; ?>>{{$row->nama}}</h3>
              <p <?php if ($isJpg): echo "class=\"has-jpg-bg-price\""; else :
                echo "class=\"menu-card-price\""; endif; ?>>{{ frupiah($row->harga)}}</p>
              <a href="#" class="cta">Beli</a>
            </div>
          </div>
        </div>
      @endforeach 
    </div>
  </section>

  <script>
    feather.replace();
  </script>
</body>
</html>
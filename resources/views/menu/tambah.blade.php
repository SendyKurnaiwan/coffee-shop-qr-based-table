
<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- JavaScript -->
        <script src="/bootstrap-4.6.2-dist/js/bootstrap.js"></script>
    
        <!-- CSS -->
        <link href="/bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">
        <link href="/css/crud.css" rel="stylesheet">
        <title>add</title>

    </head>
    <body>
        <h1 align="center">Tambah</h1>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="/insert" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" name="nama"class="form-control"  placeholder="Isikan Nama Barang Anda">
                                    <div class="form-text"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Harga :</label>
                                    <input type="text" name="harga" class="form-control" placeholder="Ketikan Harga Yang Anda Inginkan">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Gambar</label>
                                    <input type="file" name="foto" class="form-control">
                                </div>
                                <!-- <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                </div> -->
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="/" type="button" class = "ml-1 btn btn-success">Kembali</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
</html>
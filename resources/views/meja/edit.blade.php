
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
        <title>edit</title>

    </head>
    <body>
        <h1 align="center">Edit</h1>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="/update-meja/id={{ $data->id}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="name" class="form-control" placeholder="Isikan Nama Anda" value="{{ $data->name}}">
                                    <div class="form-text"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Username :</label>
                                    <input type="text" name="username" class="form-control" placeholder="Ketikan Username Yang Anda Inginkan" value="{{ $data->username}}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password :</label>
                                    <input type="text" name="password" class="form-control" placeholder="Ketikan Password Yang Anda Inginkan" value="{{ $data->password}}">
                                </div>
                                <!-- <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                </div> -->
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="/meja" type="button" class = "ml-1 btn btn-success">Kembali</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
</html>
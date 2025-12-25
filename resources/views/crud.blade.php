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
        <title>Document</title>

    </head>
    <body>
        <h1 align="center">Crud</h1>
        <div class="container">
        <a href="/tambah" type="button" class = "btn btn-success">tambah</a>
            <div class="row">
                @if ($pesan = Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        {{ $pesan }}
                    </div>
                @endif
                <table class="table table-striped">
                    <thead>
                        <tr align="left">
                            <th scope="col">ID</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                        <tr>
                            <th scope="row">{{$row->id}}</th>
                            <td>{{$row->nama}}</td>
                            <td>
                                <img src="{{ asset('fotomenu/'.$row->foto) }}" alt="" class="gambar-menu">
                            </td>
                            <td>{{ frupiah($row->harga)}}</td>
                            <td>
                                <a href="/edit/id={{$row->id}}" type="button" class = "btn btn-warning">Edit</a>
                                <a href="/hapus/id={{$row->id}}" type="button" class = "btn btn-danger">Hapus</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </body>
</html>
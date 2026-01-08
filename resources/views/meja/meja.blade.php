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
        <title>admin</title>

    </head>
    <body>
        <h1 align="center">Crud</h1>
        <div class="container">
           <a href="/admin" type="button" class = "btn btn-warning">Menu</a> 
        <a href="/meja/tambah" type="button" class = "btn btn-success" style="margin-left: 20px">tambah</a>
        @auth
            <form action="{{ route('logout') }}" method="POST" style="display: inline; float: right;margin-top: 11px;">
                @csrf
                <button type="submit" class="btn btn-danger" >Logout</button>
            </form>
        @endauth
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
                            <th scope="col">Name</th>
                            <th scope="col">Username</th>
                            <th scope="col">Password</th>
                            <th scope="col">QR Code</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                        <tr>
                            <th scope="row">{{$row->id}}</th>
                            <td>{{$row->name}}</td>
                            <td>{{$row->username}}</td>
                            <td>{{$row->password}}</td>
                            @if($row->role === 'meja')
                            <td>{!! QrCode::size(20)->backgroundColor(255, 255, 255)->color(0, 0, 0)->generate('https://kopi639.ct.ws/meja/id='.$row->id) !!}</td>
                            @else
                            <td></td>
                            @endif
                            <td>
                                <a href="/meja/edit/id={{$row->id}}" type="button" class = "btn btn-warning">Edit</a>
                                <a href="/meja/hapus/id={{$row->id}}" type="button" class = "btn btn-danger">Hapus</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </body>
</html>
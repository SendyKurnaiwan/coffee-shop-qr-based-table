<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>register</title>
    <link href="/bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">
</head>
<body>
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <main class="form-registration">
                <h1 class="h3 mb-3 fw-normal text-center">Registrasi Form<Form></Form></h1>
                <form action="/register" method="post">
                    @csrf
                  <img class="mb-4" src="../assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
                  
                  <div class="form-floating">
                    <label for="name">Name</label>
                    @error('name')<div class="invalid-feedback">{{ $message }} @enderror
                    <input type="text" name="name" class="form-control rounded-top @error('name')is-invalid @enderror"  id="name" placeholder="Name" required value="{{ old('name') }}">
                  </div>
                    
                  <div class="form-floating">
                    <label for="username">Username</label>
                    @error('username')<div class="invalid-feedback">{{ $message }}@enderror
                    <input type="text" name="username" class="form-control @error('username')is-invalid @enderror" id="username" placeholder="Username" required value="{{ old('username') }}">
                  </div>
                  <div class="form-floating">
                    <label for="email">Email address</label>
                    @error('email')<div class="invalid-feedback">{{ $message }}@enderror
                    <input type="email" name="email" class="form-control @error('email')is-invalid @enderror" id="email" placeholder="name@example.com" required value="{{ old('email') }}">
                  </div>
                  <div class="form-floating">
                    <label for="password">Password</label>
                    @error('password')<div class="invalid-feedback">{{ $message }}@enderror
                    <input type="password" name="password" class="form-control rounded-bottom @error('password')is-invalid @enderror" id="password" placeholder="Password" required >
                  </div>
                  <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">register</button>
                 
                </form>
                <small class="d-block text-center mt-3">Sudah Login? <a href="/login">Login</a></small>
              </main>
        </div>
    </div>
</body>
</html>
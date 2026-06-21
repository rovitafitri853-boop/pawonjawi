<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-card { border-radius: 15px; overflow: hidden; }
        .login-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>

<div class="container login-container">
    <div class="row w-100 justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0 login-card">
                <div class="card-body p-5">
                    <h3 class="text-center fw-bold mb-4">Login</h3>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login.proses') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email / Username</label>
                            <input type="text" name="email" class="form-control" required placeholder="Masukkan email...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="********">
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
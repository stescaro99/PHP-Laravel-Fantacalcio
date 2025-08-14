<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrati</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5" style="max-width:480px;">
    <h3 class="mb-4">Registrazione</h3>
    <form method="POST" action="{{ route('register.post') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
        @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Conferma Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
      </div>
      <button class="btn btn-primary w-100" type="submit">Registrati</button>
    </form>
    <p class="mt-3">Hai già un account? <a href="{{ route('login') }}">Accedi</a></p>
  </div>
</body>
</html>

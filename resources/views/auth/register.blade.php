<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - DapurKode</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 420px;
      width: 100%;
      background: white;
      border-radius: 16px;
      padding: 35px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .logo {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 15px;
    }

    .logo img {
      width: 100px;
      height: 100px;
      object-fit: contain;
      margin-bottom: 10px;
    }

    .logo-text {
      font-size: 24px;
      font-weight: 700;
      color: #4f46e5;
    }

    .form-header {
      margin-bottom: 20px;
    }

    .form-title {
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 6px;
      color: #1f2937;
    }

    .form-subtitle {
      font-size: 14px;
      color: #6b7280;
    }

    .form-group {
      text-align: left;
      margin-bottom: 18px;
    }

    .form-label {
      display: block;
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 6px;
      color: #374151;
    }

    .form-input {
      width: 100%;
      padding: 12px;
      border: 2px solid #e5e7eb;
      border-radius: 10px;
      font-size: 14px;
      transition: border 0.2s;
      background: #f9fafb;
    }

    .form-input:focus {
      border-color: #4f46e5;
      outline: none;
      box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
      background: #fff;
    }

    .register-btn {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 10px;
      background: linear-gradient(135deg, #4f46e5, #7c3aed);
      color: white;
      font-weight: 600;
      cursor: pointer;
      margin: 20px 0;
      font-size: 15px;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .register-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(79,70,229,0.3);
    }

    .login-link {
      font-size: 14px;
      color: #6b7280;
    }

    .login-link a {
      color: #4f46e5;
      font-weight: 600;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo">
      <img src="{{ asset('images/dapurkode.png') }}" alt="DapurKode Logo">
    </div>

    <div class="form-header">
      <h2 class="form-title">Daftar Sekarang</h2>
      <p class="form-subtitle">Untuk Login.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div class="form-group">
        <label class="form-label" for="fullname">Nama Lengkap</label>
        <input type="text" id="fullname" name="fullname" class="form-input" placeholder="Masukkan nama lengkap Anda" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="email">Alamat Email</label>
        <input type="email" id="email" name="email" class="form-input" placeholder="Masukkan alamat email Anda" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input type="password" id="password" name="password" class="form-input" placeholder="Buat password yang kuat" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Konfirmasi password Anda" required>
      </div>

      <button type="submit" class="register-btn">Daftar Sekarang</button>

      <div class="login-link">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
      </div>
    </form>
  </div>
</body>
</html>

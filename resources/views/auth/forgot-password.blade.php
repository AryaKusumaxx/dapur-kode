<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DapurKode - Lupa Password</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
    }

    .card {
      background: white;
      padding: 40px;
      border-radius: 16px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      text-align: center;
    }

    .logo {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 15px;
    }

    .logo img {
      width: 100px;
      height: 100px;
      border-radius: 12px;
      object-fit: contain;
      margin-right: 12px;
    }

    h2 {
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 8px;
      color: #1f2937;
    }

    p.subtitle {
      font-size: 14px;
      color: #6b7280;
      margin-bottom: 25px;
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
    }

    .form-input:focus {
      border-color: #4f46e5;
      outline: none;
      box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
    }

    .submit-btn {
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

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(79,70,229,0.3);
    }

    .extra-links {
      font-size: 14px;
      color: #6b7280;
      margin-top: 15px;
    }

    .extra-links a {
      color: #4f46e5;
      font-weight: 600;
      text-decoration: none;
    }

    .extra-links a:hover {
      text-decoration: underline;
    }

    .error-msg {
      color: red;
      font-size: 13px;
      margin-top: 6px;
    }

    .status-msg {
      color: green;
      font-size: 14px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="logo">
      <img src="{{ asset('images/dapurkode.png') }}" alt="DapurKode Logo">
    </div>
    <h2>Lupa Password</h2>
    <p class="subtitle">
      Masukkan email Anda, kami akan mengirim link untuk reset password.
    </p>

    <!-- Session Status -->
    @if (session('status'))
      <div class="status-msg">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf

      <!-- Email -->
      <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" name="email" class="form-input" 
               value="{{ old('email') }}" placeholder="Masukkan alamat email" required autofocus>
        @error('email')
          <div class="error-msg">{{ $message }}</div>
        @enderror
      </div>

      <!-- Tombol Kirim -->
      <button type="submit" class="submit-btn">Kirim Link Reset</button>

      <!-- Kembali ke login -->
      <div class="extra-links">
        <a href="{{ route('login') }}">Kembali ke login</a>
      </div>
    </form>
  </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | SportVenue</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #455563;
        color: white;
    }
    .container {
        display: flex;
        width: 90%;
        max-width: 1200px;
        height: 80%;
        background-color: #1d2337;
        border-radius: 10px;
        overflow: hidden;
    }
    .left-panel {
        width: 45%;
        background-image: url(https://images.unsplash.com/photo-1632300873131-1dd749c83f97?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8bWluaSUyMHNvY2NlcnxlbnwwfHwwfHx8MA%3D%3D);
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        /* padding: 2rem; */
        margin: 20px;
        border-radius: 10px;
    }
    .left-panel h1 {
      color: white;
      font-size: 1.5rem;
      font-weight: 600;
      text-align: center;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    .right-panel {
      width: 55%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-container {
      width: 100%;
      max-width: 525px;
      padding: 0 2rem;
    }
    .login-header {
      margin-bottom: 2.5rem;
      text-align: center;
    }
    .login-header h2 {
      font-family: 'system-ui', sans-serif;
      font-size: 3rem;
      font-weight: 300;
      color: #ffffff;
      margin-bottom: 0.5rem;
    }
    .login-header p {
      color: #ffffff;
      font-size: 0.875rem;
    }
    .login-header p a {
      color: #7a9ce3;
      text-decoration: none;
      font-weight: 500;
    }
    .login-header p a:hover {
      text-decoration: underline;
    }
    .form-group {
      margin-bottom: 1.25rem;
    }
    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-size: 0.875rem;
      font-weight: 500;
      color: #ffffff;
    }
    .form-group input {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      font-size: 0.875rem;
      color: #111827;
      transition: border-color 0.2s;
    }
    .form-group input:focus {
      outline: none;
      border-color: #2563eb;
    }
    .login-button {
      width: 100%;
      padding: 0.75rem;
      background-color: #2563eb;
      color: white;
      border: none;
      border-radius: 0.375rem;
      font-size: 0.875rem;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    .login-button:hover {
      background-color: #1d4ed8;
    }
    .divider {
      display: flex;
      align-items: center;
      margin: 1.5rem 0;
      color: #9ca3af;
      font-size: 0.75rem;
    }
    .divider::before,
    .divider::after {
      content: "";
      flex: 1;
      border-bottom: 1px solid #e5e7eb;
    }
    .divider::before {
      margin-right: 0.5rem;
    }
    .divider::after {
      margin-left: 0.5rem;
    }
    .social-login {
      display: flex;
      gap: 1rem;
      margin-top: 1.5rem;
    }
    .social-button {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0.5rem;
      border: 1px solid #e5e7eb;
      border-radius: 0.375rem;
      background-color: white;
      font-size: 0.875rem;
      font-weight: 500;
      color: #374151;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    .social-button:hover {
      background-color: #f9fafb;
    }
    .social-button svg {
      margin-right: 0.5rem;
      width: 1rem;
      height: 1rem;
    }

    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }
      .left-panel,
      .right-panel {
        width: 100%;
      }
      .left-panel {
        height: 200px;
        padding: 1rem;
      }
      .right-panel {
        padding: 2rem 1rem;
      }
      .login-container {
        max-width: 100%;
      }
    }
  </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <h1>Capturing Moments, Creating Memories</h1>
        </div>
        <div class="right-panel">
            <div class="login-container">
            <div class="login-header">
                <h2>Daftar </h2>
                <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
            </div>
            
            <form action="{{ route('register') }}" method="POST">
            @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" placeholder="Nama Anda" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                {{-- <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email Anda" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div> --}}
                <div class="form-group">
                    <label>No. WhatsApp</label>
                    <input type="text" name="phone" placeholder="0812..." required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <button type="submit" class="login-button">Daftar</button>
            </form>
        </div>
    </div>
</body>
</html>

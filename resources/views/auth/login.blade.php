<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | MuslimSpace</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      /* background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                  url('https://images.unsplash.com/photo-1543168256-418811576931?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') no-repeat center center fixed; */
      background-color: #0b8360;
      background-size: cover;
      color: #333;
      padding: 20px;
    }
    
    .login-container {
      width: 100%;
      max-width: 420px;
      background-color: rgba(255, 255, 255, 0.95);
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .login-header {
      background: linear-gradient(135deg, #0d8b66 0%, #0a6b4e 100%);
      padding: 1.7rem 2rem;
      text-align: center;
      color: white;
      position: relative;
    }
    
    .login-header::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M0 0h40v40H0V0zm10 10h20v20H10V10z'/%3E%3C/g%3E%3C/svg%3E");
      opacity: 0.2;
    }
    
    .login-header h1 {
      font-size: 1.8rem;
      font-weight: 600;
      position: relative;
    }
    
    .login-header p {
      font-size: 0.9rem;
      opacity: 0.9;
      font-weight: 300;
    }
    
    .login-header i {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      display: block;
      color: rgba(255, 255, 255, 0.9);
    }
    
    .login-body {
      padding: 2.5rem 2rem;
    }
    
    .form-group {
      margin-bottom: 1rem;
      position: relative;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
      font-weight: 500;
      color: #0a6b4e;
    }
    
    .form-group input {
      width: 100%;
      padding: 0.9rem 1rem 0.9rem 3rem;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      font-size: 0.95rem;
      color: #333;
      transition: all 0.3s ease;
      background-color: #f9f9f9;
    }
    
    .form-group input:focus {
      outline: none;
      border-color: #0d8b66;
      box-shadow: 0 0 0 3px rgba(13, 139, 102, 0.1);
      background-color: white;
    }
    
    .input-icon {
      position: absolute;
      left: 1rem;
      top: 45px;
      color: #0d8b66;
      font-size: 1.1rem;
    }
    
    .login-button {
      width: 100%;
      padding: 0.9rem;
      background: linear-gradient(135deg, #0d8b66 0%, #0a6b4e 100%);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 0.5rem;
    }
    
    .login-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(13, 139, 102, 0.3);
    }
    
    .login-button:active {
      transform: translateY(0);
    }
    
    .divider {
      display: flex;
      align-items: center;
      margin: 1.5rem 0;
      color: #777;
      font-size: 0.85rem;
    }
    
    .divider::before,
    .divider::after {
      content: "";
      flex: 1;
      border-bottom: 1px solid #e0e0e0;
    }
    
    .divider::before {
      margin-right: 0.75rem;
    }
    
    .divider::after {
      margin-left: 0.75rem;
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
      padding: 0.75rem;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      background-color: white;
      font-size: 0.9rem;
      font-weight: 500;
      color: #555;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .social-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .social-button i {
      margin-right: 0.5rem;
      font-size: 1.1rem;
    }
    
    .google-btn {
      color: #DB4437;
      border-color: #e0e0e0;
    }
    
    .facebook-btn {
      color: #4267B2;
      border-color: #e0e0e0;
    }
    
    .register-link {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.9rem;
      color: #666;
    }
    
    .register-link a {
      color: #0d8b66;
      text-decoration: none;
      font-weight: 500;
    }
    
    .register-link a:hover {
      text-decoration: underline;
    }
    
    .forgot-password {
      text-align: right;
      margin-top: 0.5rem;
    }
    
    .forgot-password a {
      color: #0d8b66;
      text-decoration: none;
      font-size: 0.85rem;
    }
    
    .forgot-password a:hover {
      text-decoration: underline;
    }
    
    @media (max-width: 480px) {
      .login-container {
        max-width: 100%;
      }
      
      .login-header {
        padding: 1.5rem 1.5rem;
      }
      
      .login-body {
        padding: 2rem 1.5rem;
      }
      
      .social-login {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-header">
      <i class="fas fa-mosque"></i>
      <h1>Selamat Datang</h1>
      <p>Amal Sholih Masuk Dulu</p>
    </div>
    
    <div class="login-body">
      <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="email">Email</label>
          <i class="fas fa-envelope input-icon"></i>
          <input type="email" id="email" name="email" placeholder="alamat.email@contoh.com" required />
        </div>
        
        <div class="form-group">
          <label for="password">Kata Sandi</label>
          <i class="fas fa-lock input-icon"></i>
          <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required />
        </div>
        
        <div class="forgot-password">
          <a href="#">Lupa kata sandi?</a>
        </div>
        
        <button type="submit" class="login-button">Masuk</button>
      </form>
      <div class="register-link">
        Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
      </div>
    </div>
  </div>
</body>
</html>
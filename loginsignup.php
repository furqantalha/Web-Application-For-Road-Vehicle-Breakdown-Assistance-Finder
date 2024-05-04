<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login and Sign Up</title>
  <style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    html, body {
      display: grid;
      height: 100%;
      width: 100%;
      place-items: center;
      background: -webkit-linear-gradient(left, #003366, #004080, #0059b3, #0073e6);
    }

    ::selection {
      background: #1a75ff;
      color: #fff;
    }

    .wrapper {
      overflow: hidden;
      max-width: 390px;
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.1);
    }

    .title-text {
      display: flex;
      width: 200%;
    }

    .title {
      width: 50%;
      font-size: 35px;
      font-weight: 600;
      text-align: center;
      transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .slide-controls {
      position: relative;
      display: flex;
      height: 50px;
      width: 100%;
      overflow: hidden;
      margin: 30px 0 10px 0;
      justify-content: space-between;
      border: 1px solid lightgrey;
      border-radius: 15px;
    }

    .slide-controls .slide {
      height: 100%;
      width: 100%;
      color: #fff;
      font-size: 18px;
      font-weight: 500;
      text-align: center;
      line-height: 48px;
      cursor: pointer;
      z-index: 1;
      transition: all 0.6s ease;
    }

    .slide-controls label.signup {
      color: #000;
    }

    .slide-controls .slider-tab {
      position: absolute;
      height: 100%;
      width: 50%;
      left: 0;
      z-index: 0;
      border-radius: 15px;
      background: -webkit-linear-gradient(left, #003366, #004080, #0059b3, #0073e6);
      transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    input[type="radio"] {
      display: none;
    }

    #signup:checked ~ .slider-tab {
      left: 50%;
    }

    #signup:checked ~ label.signup {
      color: #fff;
      cursor: default;
      user-select: none;
    }

    #signup:checked ~ label.login {
      color: #000;
    }

    #login:checked ~ label.signup {
      color: #000;
    }

    #login:checked ~ label.login {
      cursor: default;
      user-select: none;
    }

    .form-container {
      width: 100%;
      overflow: hidden;
    }

    .form-inner {
      display: flex;
      width: 200%;
    }

    form {
      width: 50%;
      transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .field {
      height: 50px;
      width: 100%;
      margin-top: 20px;
    }

    input {
      height: 100%;
      width: 100%;
      outline: none;
      padding-left: 15px;
      border-radius: 15px;
      border: 1px solid lightgrey;
      border-bottom-width: 2px;
      font-size: 17px;
      transition: all 0.3s ease;
    }

    input:focus {
      border-color: #1a75ff;
    }

    input::placeholder {
      color: #999;
      transition: all 0.3s ease;
    }

    input:focus::placeholder {
      color: #1a75ff;
    }

    .pass-link {
      margin-top: 5px;
    }

    .signup-link {
      text-align: center;
      margin-top: 30px;
    }

    a {
      color: #1a75ff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    .btn {
      height: 50px;
      width: 100%;
      border-radius: 15px;
      position: relative;
      overflow: hidden;
    }

    .btn-layer {
      height: 100%;
      width: 300%;
      position: absolute;
      left: -100%;
      background: -webkit-linear-gradient(right, #003366, #004080, #0059b3, #0073e6);
      border-radius: 15px;
      transition: all 0.4s ease;
    }

    .btn:hover .btn-layer {
      left: 0;
    }

    input[type="submit"] {
      height: 100%;
      width: 100%;
      z-index: 1;
      position: relative;
      background: none;
      border: none;
      color: #fff;
      padding-left: 0;
      border-radius: 15px;
      font-size: 20px;
      font-weight: 500;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="title-text">
      <div class="title login">Login Form</div>
      <div class="title signup">Signup Form</div>
    </div>
    <div class="slide-controls">
      <input type="radio" name="slide" id="login" checked>
      <input type="radio" name="slide" id="signup">
      <label for="login" class="slide login">Login</label>
      <label for="signup" class="slide signup">Signup</label>
      <div class="slider-tab"></div>
    </div>
    <div class="form-container">
      <div class="form-inner">
        <form action="clogin.php" method="post" class="login">
          <div class="field">
            <input type="text" name="username" placeholder="Email Address" required>
          </div>
          <div class="field">
            <input type="password" name="password" placeholder="Password" required>
          </div>
          <div class="pass-link"><a href="adminsignlogin.php">admin login?</a></div>
          <div class="field btn">
            <div class="btn-layer"></div>
            <input type="submit" value="login">
          </div>
          <div class="signup-link">Not a member? <a href="#">Signup now</a></div>
          <div class="homepage"><a href="index.php">Back to home</a></div>
        </form>
        <form action="csignup.php" method="post" class="signup">
          <div class="field">
            <input type="text" name="username" placeholder="Email Address" required>
          </div>
          <div class="field">
            <input type="password" name="password" placeholder="Password" required>
          </div>
          <div class="field">
            <input type="password" name="password" placeholder="Confirm password" required>
          </div>
          <div class="field btn">
            <div class="btn-layer"></div>
            <input type="submit" value="Signup">
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    const loginText = document.querySelector(".title-text .login");
    const loginForm = document.querySelector("form.login");
    const loginBtn = document.querySelector("label.login");
    const signupBtn = document.querySelector("label.signup");
    const signupLink = document.querySelector("form .signup-link a");

    signupBtn.onclick = (() => {
      loginForm.style.marginLeft = "-50%";
      loginText.style.marginLeft = "-50%";
    });

    loginBtn.onclick = (() => {
      loginForm.style.marginLeft = "0%";
      loginText.style.marginLeft = "0%";
    });

    signupLink.onclick = (() => {
      signupBtn.click();
      return false;
    });
  </script>
</body>
</html>

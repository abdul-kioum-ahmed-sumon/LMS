<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register & Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="style.css">


</head>

<body>
  <div style="position: fixed; top: 0; left: 0; width: 100%; background-color: #c9d6ff; background:linear-gradient(to right,#e2e2e2,#c9d6ff); padding: 20px; text-align: center;">
    <div>
      <div class="contact-info d-flex align-items-center">
        <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:info@baust.edu.bd">info@baust.edu.bd</a></i>
        <i class="bi bi-phone d-flex align-items-center ms-4"><span style="margin-left: 50px;">+88-01769675559</span></i>
      </div>
    </div>
  </div>

  <div class="container" id="signup" style="display:none;">
    <h1 class="form-title">Register</h1>
    <form method="post" action="register.php">
      <div class="input-group" style="margin-top: 20px;">
        <i class="fas fa-user"></i>
        <input type="text" name="fName" id="fName" placeholder="First Name" required>
        <label for="fname">First Name</label>
      </div>

      <div class="input-group" style="margin-top: 20px;">
        <i class="fas fa-user"></i>
        <input type="text" name="lName" id="lName" placeholder="Last Name" required>
        <label for="lName">Last Name</label>
      </div>
      <div class="input-group" style="margin-top: 20px;">
        <i class="fas fa-envelope"></i>
        <input type="number" name="dept_id" id="dept_id" placeholder="ID" required>
        <label for="dept_id">ID</label>
      </div>
      <div class="input-group" style="margin-top: 20px;">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <label for="email">Email</label>
      </div>
      <div class="input-group" style="margin-top: 20px;">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <label for="password">Password</label>
      </div>
      <input type="submit" class="btn" style="margin-top: 20px;" value="Sign Up" name="signUp">
    </form>
    <div class="links" style="margin-top: 20px;">
      <p style="margin-top: 7px;">Already Have Account ?</p>
      <button id="signInButton" class="menu__link">Sign In</button>
    </div>
  </div>

  <div class="container" id="signIn">
    <h1 class="form-title">Sign In</h1>
    <form method="post" action="register.php">
      <div class="input-group" style="margin-top: 20px;">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <label for="email">Email</label>
      </div>
      <div class="input-group" style="margin-top: 20px;">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <label for="password">Password</label>
      </div>
      <p class="recover">
        <a href="#" class=" menu__link">Recover Password</a>
      </p>
      <input type="submit" class="btn" value="Sign In" name="signIn">
    </form>

    <div class="links">
      <p style="margin-top: 7px;">Don't have account yet?</p>
      <button id="signUpButton" class="menu__link">Sign Up</button>
    </div>
  </div>

  <div style="position: fixed; bottom: 0; left: 0; width: 100%;  background-color: #c9d6ff; background:linear-gradient(to right,#e2e2e2,#c9d6ff); padding: 20px; text-align: center;">
    <p>© <span>Copyright</span> <strong class="px-1">Library Management System</strong> <span>All Rights Reserved</span></p>
  </div>



  <script src="script.js"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - HEP Program Registration System</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .signup-container {
            max-width: 450px;
            margin: 80px auto;
            background-color: #fcbecf;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .signup-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2a2675;
        }

        .signup-container label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }

        .signup-container input {
            width: 400px;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .signup-container .form-buttons {
            display: flex;
            justify-content: center;
        }

        .signup-container .back {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <img src="assets/uitm logo.jpeg" alt="Logo" class="logo">
        <div class="nav-title">HEP Program Registration System</div>
    </div>

    <div class="signup-container">
        <h2>Create Account</h2>
        <form action="signup_process.php" method="POST">
            <label for="user_id">User ID</label>
            <input type="text" name="user_id" id="user_id" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <div class="form-buttons">
                <button type="submit" class="button">Sign Up</button>
            </div>

        </form>

        <div class="back">
            <a href="login.html" style="color: #2a2675; font-weight: bold;">Back to Login</a>
        </div>
    </div>

</body>
</html>

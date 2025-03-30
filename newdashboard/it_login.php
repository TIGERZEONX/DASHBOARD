<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM it_admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['it_admin_id'] = $id;
            $_SESSION['it_admin_name'] = $name;
            header("Location: it_dashboard.php");
            exit();
        } else {
            $error = "❌ Incorrect password!";
        }
    } else {
        $error = "❌ No account found with that email!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>IT Admin Login</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #0a192f;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        /* Login Form */
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            width: 350px;
            box-shadow: 0px 0px 20px rgba(0, 255, 255, 0.3);
            animation: fadeIn 1s ease-in-out;
        }

        .login-container h2 {
            margin-bottom: 15px;
            font-size: 22px;
            font-weight: bold;
        }

        /* Input Fields */
        .input-box {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            outline: none;
            border-radius: 5px;
            font-size: 16px;
            transition: 0.3s;
            background: #0a1f44;
            color: #fff;
        }

        .input-box:focus {
            box-shadow: 0px 0px 10px cyan;
            transform: scale(1.05);
        }

        /* Submit Button */
        .login-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            background: cyan;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-btn:hover {
            background: #00c4ff;
            transform: scale(1.05);
            box-shadow: 0px 0px 10px cyan;
        }

        /* Error Message */
        .error-msg {
            margin-top: 10px;
            color: red;
            font-size: 14px;
        }

        /* Register Link */
        .register-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .register-link a {
            color: cyan;
            text-decoration: none;
            transition: 0.3s;
        }

        .register-link a:hover {
            text-decoration: underline;
            color: #00c4ff;
        }

        /* Fade-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>🔐 IT Admin Login</h2>
        <form method="POST">
            <input type="email" name="email" class="input-box" placeholder="📧 Email" required>
            <input type="password" name="password" class="input-box" placeholder="🔑 Password" required>
            <button type="submit" class="login-btn">Login</button>
        </form>

        <?php if (isset($error)) { echo "<p class='error-msg'>$error</p>"; } ?>

        <p class="register-link">Don't have an account? <a href="it_register.php">Register</a></p>
    </div>

</body>
</html>

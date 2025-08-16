<?php 
session_start();

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login';

$formData = [
    'login' => [
        'email' => $_SESSION['login_email'] ?? '',
    ],
    'register' => [
        'name' => $_SESSION['register_name'] ?? '',
        'email' => $_SESSION['register_email'] ?? '',
        'role' => $_SESSION['register_role'] ?? '',
    ]
];

session_unset();

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cineclub Sign In</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #14181c;
            color: #99AABB;
        }

        .container {
            margin: 0 15px;
            width: 100%;
            max-width: 360px;
        }

        .form-box {
            background-color: #2c3440;
            width: 100%;
            padding: 30px;
            border-radius: 3px;
            display: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }

        .form-box.active {
            display: block;
        }

        h2 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 24px;
            color: #fff;
            font-weight: 400;
        }

        input,
        select {
            width: 100%;
            padding: 10px 12px;
            background: #38434d;
            border-radius: 3px;
            border: 1px solid #38434d;
            outline: none;
            font-size: 15px;
            color: #fff;
            margin-bottom: 16px;
            transition: all 0.2s ease;
        }

        input:focus,
        select:focus {
            border-color: #00E054;
        }

        input::placeholder {
            color: #99AABB;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #00E054;
            border-radius: 3px;
            border: none;
            cursor: pointer;
            font-size: 15px;
            color: #fff;
            font-weight: 500;
            margin-bottom: 20px;
            transition: 0.2s;
        }

        button:hover {
            background: #00c048;
        }

        p {
            font-size: 15px;
            text-align: center;
            margin-bottom: 10px;
            color: #99AABB;
        }

        p a {
            color: #00E054;
            text-decoration: none;
            transition: color 0.2s;
        }

        p a:hover {
            color: #fff;
        }

        .error-message {
            padding: 12px;
            background-color: rgba(255, 0, 0, 0.1);
            border-radius: 3px;
            font-size: 14px;
            color: #ff6b6b;
            text-align: center;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 0, 0, 0.2);
        }

        .field-error {
            color: #ff6b6b;
            font-size: 13px;
            margin-top: -12px;
            margin-bottom: 12px;
            display: block;
        }

        .logo {
            text-align: center;
            margin-bottom: 24px;
        }

        .dots {
            display: flex;
            gap: 4px;
            justify-content: center;
            margin-bottom: 24px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .dot-1 { background-color: #00E054; }
        .dot-2 { background-color: #40BCF4; }
        .dot-3 { background-color: #FF8000; }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23ffffff' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 35px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <div class="dots">
                <div class="dot dot-1"></div>
                <div class="dot dot-2"></div>
                <div class="dot dot-3"></div>
            </div>
        </div>

        <div class="form-box <?= isActiveForm('login', $activeForm) ?>" id="login-form">
            <form action="login_register.php" method="post" onsubmit="return validateLoginForm()">
                <h2>Sign In</h2>
                <?= showError($errors['login']) ?>
                <input type="email" name="email" placeholder="Email address" value="<?= htmlspecialchars($formData['login']['email']) ?>" required>
                <span id="login-email-error" class="field-error"></span>
                <input type="password" name="password" placeholder="Password" required>
                <span id="login-password-error" class="field-error"></span>
                <button type="submit" name="login">Sign in</button>
                <p>Don't have an account? <a href="#" onclick="showForm('register-form')">Create one</a></p>
            </form>
        </div>

        <div class="form-box <?= isActiveForm('register', $activeForm) ?>" id="register-form">
            <form action="login_register.php" method="post" onsubmit="return validateRegisterForm()">
                <h2>Create Account</h2>
                <?= showError($errors['register']) ?>
                <input type="text" name="name" placeholder="Name" value="<?= htmlspecialchars($formData['register']['name']) ?>" required>
                <span id="register-name-error" class="field-error"></span>
                <input type="email" name="email" placeholder="Email address" value="<?= htmlspecialchars($formData['register']['email']) ?>" required>
                <span id="register-email-error" class="field-error"></span>
                <input type="password" name="password" placeholder="Password" required>
                <span id="register-password-error" class="field-error"></span>
                <select name="role" required>
                    <option value="">Select role</option>
                    <option value="user" <?= isset($formData['register']['role']) && $formData['register']['role'] === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= isset($formData['register']['role']) && $formData['register']['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <span id="register-role-error" class="field-error"></span>
                <button type="submit" name="register">Create account</button>
                <p>Already have an account? <a href="#" onclick="showForm('login-form')">Sign in</a></p>
            </form>
        </div>
    </div>

    <script>
        function showForm(formId) {
            document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
            document.getElementById(formId).classList.add("active");
        }

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function validateLoginForm() {
            let isValid = true;
            const email = document.forms[0].email.value.trim();
            const password = document.forms[0].password.value.trim();
            
            // Reset error messages
            document.getElementById('login-email-error').textContent = '';
            document.getElementById('login-password-error').textContent = '';
            
            // Validate email
            if (!email) {
                document.getElementById('login-email-error').textContent = 'Email is required';
                isValid = false;
            } else if (!validateEmail(email)) {
                document.getElementById('login-email-error').textContent = 'Please enter a valid email address';
                isValid = false;
            }
            
            // Validate password
            if (!password) {
                document.getElementById('login-password-error').textContent = 'Password is required';
                isValid = false;
            }
            
            return isValid;
        }

        function validateRegisterForm() {
            let isValid = true;
            const name = document.forms[1].name.value.trim();
            const email = document.forms[1].email.value.trim();
            const password = document.forms[1].password.value.trim();
            const role = document.forms[1].role.value;
            
            // Reset error messages
            document.getElementById('register-name-error').textContent = '';
            document.getElementById('register-email-error').textContent = '';
            document.getElementById('register-password-error').textContent = '';
            document.getElementById('register-role-error').textContent = '';
            
            // Validate name
            if (!name) {
                document.getElementById('register-name-error').textContent = 'Name is required';
                isValid = false;
            }
            
            // Validate email
            if (!email) {
                document.getElementById('register-email-error').textContent = 'Email is required';
                isValid = false;
            } else if (!validateEmail(email)) {
                document.getElementById('register-email-error').textContent = 'Please enter a valid email address';
                isValid = false;
            }
            
            // Validate password
            if (!password) {
                document.getElementById('register-password-error').textContent = 'Password is required';
                isValid = false;
            } else if (password.length < 6) {
                document.getElementById('register-password-error').textContent = 'Password must be at least 6 characters';
                isValid = false;
            }
            
            // Validate role
            if (!role) {
                document.getElementById('register-role-error').textContent = 'Please select a role';
                isValid = false;
            }
            
            return isValid;
        }
    </script>
</body>
</html>
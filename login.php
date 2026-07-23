<?php 
    session_start();
    include('DBConnection.php');

    if(isset($_POST['logbtn'])) {
        // Sanitize user inputs - only keep basic SQL injection protection
        $uname = mysqli_real_escape_string($conn, $_POST['username']);
        $pass = mysqli_real_escape_string($conn, $_POST['password']);

        // Use prepared statement to prevent SQL injection
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify if password matches
            if($pass === $user['password']) {  // Note: In production, use password_verify()
                $_SESSION["uname"] = $uname;
                $_SESSION["user_id"] = $user['id'];  // Store user ID if available
                header("Location: index.php?success=1");
                exit();
            } else {
                $er_invalid = "Incorrect password. Please try again.";
            }
        } else {
            $er_invalid = "Username not found. Please register or try again.";
        }
        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Indian Railways | Secure Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" href="asset/img/logo/rail_icon.png">

    <!-- Google Fonts + Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Add this meta tag -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <style>
        :root {
            --primary: #0066B3;
            --primary-light: #4D9CDB;
            --secondary: #FF671F;
            --accent: #FFAC1C;
            --white: #FFFFFF;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-600: #4B5563;
            --gray-900: #111827;
            --error: #EF4444;
            --success: #10B981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, 
                          rgba(0, 102, 179, 0.08) 0%, 
                          rgba(255, 103, 31, 0.06) 50%, 
                          rgba(255, 172, 28, 0.04) 100%),
                      url('asset/img/railway-pattern.png');
            background-size: cover;
            background-attachment: fixed;
            color: var(--gray-900);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .app-bar {
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 10;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo {
            height: 2.5rem;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 600;
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            position: relative;
        }

        .login-card {
            width: 100%;
            max-width: 28rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1),
                        0 8px 10px -6px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, 
                          rgba(0, 102, 179, 0.1) 0%, 
                          transparent 70%);
            z-index: -1;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: var(--gray-600);
            font-size: 1rem;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--gray-900);
            font-size: 0.9375rem;
        }

        .input-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .material-symbols-rounded {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-600);
            font-size: 1.25rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3.25rem;
            font-size: 1rem;
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 102, 179, 0.1);
            background: var(--white);
        }

        .error-message {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            font-size: 0.8125rem;
            color: var(--error);
        }

        /* Button */
        .login-button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            color: var(--white);
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 102, 179, 0.2),
                        0 2px 4px -1px rgba(0, 102, 179, 0.12);
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 102, 179, 0.3),
                        0 4px 6px -2px rgba(0, 102, 179, 0.15);
        }

        .login-button:active {
            transform: translateY(0);
        }

        /* Forgot Password */
        .forgot-password {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9375rem;
            transition: all 0.2s ease;
        }

        .forgot-password:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }

        /* Footer */
        .footer {
            padding: 1.5rem 2rem;
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-top: 1px solid rgba(255, 255, 255, 0.3);
        }

        .footer-text {
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: var(--gray-600);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }

        .password-toggle:hover,
        .password-toggle:focus {
            color: var(--primary);
            outline: none;
        }

        .password-toggle.visible {
            color: var(--primary);
        }

        /* Requirements Details */
        .login-requirements {
            margin-top: 1.5rem;
            margin-bottom: 2rem;
        }

        .requirements-details {
            background: rgba(0, 102, 179, 0.05);
            border-radius: 0.5rem;
            padding: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .requirements-summary {
            cursor: pointer;
            color: var(--primary);
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.25rem;
        }

        .requirements-content {
            font-size: 0.8125rem;
            color: var(--gray-600);
            padding: 0.5rem;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.25rem;
        }

        .requirement-icon {
            font-size: 1rem;
            color: var(--primary);
        }

        .requirements-list {
            margin-left: 2rem;
            margin-top: 0.25rem;
        }

        .requirements-list li {
            margin-bottom: 0.25rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .app-bar {
                padding: 1rem;
            }
            
            .login-card {
                padding: 2rem 1.5rem;
                border-radius: 1.25rem;
            }
        }

        @media (max-width: 480px) {
            .form-input {
                padding: 0.875rem 0.875rem 0.875rem 3rem;
            }
            
            .material-symbols-rounded {
                left: 0.875rem;
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="app-bar">
        <div class="logo-container">
            <img src="asset/img/logo/rail_icon.png" alt="Indian Railways" class="logo">
            <span class="logo-text">Indian Railways</span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="login-card">
            <div class="login-header">
                <h1 class="login-title">Welcome Back</h1>
                <p class="login-subtitle">Sign in to access your account</p>
            </div>

            <?php if (isset($er_invalid)): ?>
                <div class="error-message" style="margin-bottom: 1.5rem; justify-content: center;">
                    <span class="material-symbols-rounded">error</span>
                    <span><?php echo $er_invalid; ?></span>
                </div>
            <?php endif; ?>

            <!-- Update the login form -->
            <form method="post" action="" autocomplete="off">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-container">
                        <span class="material-symbols-rounded">person</span>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               class="form-input" 
                               placeholder="Enter your username" 
                               autocomplete="new-username"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-container">
                        <span class="material-symbols-rounded">lock</span>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-input" 
                               placeholder="Enter your password" 
                               autocomplete="new-password"
                               required>
                        <button type="button" 
                                class="password-toggle" 
                                id="password-toggle" 
                                aria-label="Toggle password visibility">
                            <span class="material-symbols-rounded">visibility_off</span>
                        </button>
                    </div>
                </div>

                <button type="submit" name="logbtn" class="login-button">
                    <span class="material-symbols-rounded">login</span>
                    Sign In
                </button>

                <a href="forgot_password.php" class="forgot-password">Forgot password?</a>
            </form>

            <!-- Add this after the login-subtitle paragraph -->
            <div class="login-requirements mb-4">
                <details class="requirements-details">
                    <summary class="requirements-summary">Password Requirements</summary>
                    <div class="requirements-content">
                        <p class="requirement-item">
                            <span class="material-symbols-rounded requirement-icon">info</span>
                            Password must include:
                        </p>
                        <ul class="requirements-list">
                            <li>At least 8 characters</li>
                            <li>At least one uppercase letter</li>
                            <li>At least one lowercase letter</li>
                            <li>At least one number</li>
                            <li>At least one special character (!@#$%^&*)</li>
                        </ul>
                    </div>
                </details>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p class="footer-text">© 2023 Indian Railways. All rights reserved.</p>
    </footer>

    <script>
        // Enhanced touch feedback
        document.addEventListener('DOMContentLoaded', function() {
            // Touch feedback for buttons
            document.querySelectorAll('.login-button, .forgot-password').forEach(element => {
                element.addEventListener('touchstart', () => {
                    element.style.transform = 'scale(0.98)';
                    element.style.opacity = '0.9';
                }, { passive: true });
                
                element.addEventListener('touchend', () => {
                    element.style.transform = '';
                    element.style.opacity = '';
                }, { passive: true });
            });

            // Input field interactions
            document.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('focus', () => {
                    input.previousElementSibling.style.color = 'var(--primary)';
                });
                
                input.addEventListener('blur', () => {
                    input.previousElementSibling.style.color = 'var(--gray-600)';
                });
            });

            // Password visibility toggle
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('password-toggle');
            const toggleIcon = toggleButton.querySelector('.material-symbols-rounded');

            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type');
                
                if (type === 'password') {
                    passwordInput.setAttribute('type', 'text');
                    toggleIcon.textContent = 'visibility';
                    this.classList.add('visible');
                } else {
                    passwordInput.setAttribute('type', 'password');
                    toggleIcon.textContent = 'visibility_off';
                    this.classList.remove('visible');
                }
            });
        });
    </script>
</body>
</html>
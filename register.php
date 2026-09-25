<?php
session_start();
include "dbconn.php";
$errors = $_SESSION['register_errors'] ?? [];
$formValues = $_SESSION['register_values'] ?? [];
unset($_SESSION['register_errors'], $_SESSION['register_values']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Onehome | Create Account</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }

        .login-shell {
            width: min(460px, 100%);
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 18px 32px rgba(12, 28, 52, 0.08);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 28px 24px;
            text-align: center;
        }

        .login-header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .login-body {
            padding: 24px;
        }

        .login-form {
            display: grid;
            gap: 18px;
        }

        .login-actions {
            margin-top: 8px;
        }

        .link-row {
            text-align: center;
            margin-top: 14px;
            color: var(--muted);
        }

        .link-row a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="login-header">
            <h1>Create Account</h1>
        </div>

        <div class="login-body">
            <?php if (!empty($errors)): ?>
                <div class="alert error">
                    <?php foreach ($errors as $error): ?>
                        <div><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form class="login-form" method="POST" action="register_process.php">
                <div class="field">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($formValues['full_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                </div>

                <div class="field">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($formValues['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required />
                </div>

                <div class="field">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required />
                </div>

                <div class="login-actions">
                    <button type="submit" class="btn" style="width:100%;">Create Account</button>
                </div>
            </form>

            <div class="link-row">
                <a href="login.php">Already have an account? Sign in</a>
            </div>
            <div class="link-row">
                <a href="index.php">Back to customer request</a>
            </div>
        </div>
    </div>
</body>
</html>

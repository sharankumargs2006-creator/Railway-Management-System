<?php
include('DBConnection.php');
$msg = "";
$show_reset_form = false;
$username = "";
$email = "";

// Step 1: User verifies username & email
if (isset($_POST['verifybtn'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $sql = "SELECT * FROM user WHERE username='$username' AND email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $show_reset_form = true;
    } else {
        $msg = "❌ Invalid username or email.";
    }
}

// Step 2: User submits new password
if (isset($_POST['resetbtn'])) {
    $username = $_POST['username'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass === $confirm_pass) {
        // If using hashed passwords (recommended), use password_hash()
        // $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
        // $update_sql = "UPDATE user SET password='$hashed_password' WHERE username='$username'";

        // If using plain text (not secure), use this:
        $update_sql = "UPDATE user SET password='$new_pass' WHERE username='$username'";

        if ($conn->query($update_sql) === TRUE) {
            $msg = "✅ Password successfully updated. <a href='login.php'>Click here to login</a>";
        } else {
            $msg = "❌ Error updating password.";
        }
    } else {
        $msg = "❌ Passwords do not match.";
        $show_reset_form = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
</head>
<body class="bg-dark text-white">

<div class="container mt-5">
    <h2 class="text-center">Forgot Password</h2>
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>

    <?php if (!$show_reset_form): ?>
        <!-- Step 1: Verify username/email -->
        <form action="" method="post" class="mt-4">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email address:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <input type="submit" name="verifybtn" class="btn btn-primary" value="Verify">
            <a href="login.php" class="btn btn-secondary ml-2">Back to Login</a>
        </form>

    <?php else: ?>
        <!-- Step 2: Enter new password -->
        <form action="" method="post" class="mt-4">
            <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">
            <div class="form-group">
                <label>New Password:</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <input type="submit" name="resetbtn" class="btn btn-success" value="Reset Password">
        </form>
    <?php endif; ?>
</div>

</body>
</html>

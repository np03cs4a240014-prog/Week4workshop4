<?php

$errors = [
    'name' => '',
    'email' => '',
    'password' => '',
    'confirm' => ''
];

$name = $email = '';
$success = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get input values safely
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    
    if (empty($name)) {
        $errors['name'] = "Name is required";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters";
    } elseif (!preg_match('/[@$!%*?&]/', $password)) {
        $errors['password'] = "Password must include a special character";
    }

    if (empty($confirm)) {
        $errors['confirm'] = "Confirm your password";
    } elseif ($password !== $confirm) {
        $errors['confirm'] = "Passwords do not match";
    }

   
    if (!array_filter($errors)) {

        $file = "users.json";

        
        $data = file_get_contents($file);
        $users = json_decode($data, true);

        if (!is_array($users)) {
            $users = [];
        }

     
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

       
        $newUser = [
            "name" => $name,
            "email" => $email,
            "password" => $hashedPassword
        ];

        
        $users[] = $newUser;

        
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));

        $success = "Registration successful!";
        $name = $email = '';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
        /* Reset */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: "Times New Roman";
}

/* Page background */
body {
    min-height: 100vh;
    background: midnightblue;
    display: flex;
    justify-content: center;
    align-items: center;
    
}

/* Main container */
.container {
    width: 420px;
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Heading */
.container h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

/* Labels */
label {
    display: block;
    margin-top: 15px;
    margin-bottom: 5px;
    font-weight: 600;
    color: #444;
}

/* Inputs */
input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    transition: 0.3s;
}

input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 5px rgba(102, 126, 234, 0.5);
}

/* Error messages */
.error {
    color: #e74c3c;
    font-size: 13px;
    margin-top: 4px;
}

/* Success message */
.success {
    background: #eafaf1;
    color: #2ecc71;
    padding: 10px;
    border-radius: 6px;
    text-align: center;
    font-weight: 600;
    margin-bottom: 15px;
}

/* Button */
button {
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    background: #667eea;
    border: none;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #5a67d8;
}

/* Mobile responsive */
@media (max-width: 480px) {
    .container {
        width: 90%;
        padding: 20px;
    }
}

    </style>
</head>

<body>
<div class="container">
    <h2>Register</h2>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
        <div class="error"><?= $errors['name'] ?></div>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
        <div class="error"><?= $errors['email'] ?></div>

        <label>Password</label>
<div class="password-wrapper">
    <input type="password" name="password" id="password">
    <i class="fa-solid fa-eye" id="togglePassword"></i>
</div>
<div class="error"><?= $errors['password'] ?></div>

        
<label>Confirm Password</label>
<div class="password-wrapper">
    <input type="password" name="confirm_password" id="confirm_password">
    <i class="fa-solid fa-eye" id="toggleConfirm"></i>
</div>
<div class="error"><?= $errors['confirm'] ?></div>
        <button type="submit">Register</button>
    </form>
</div>
</body>

</html>

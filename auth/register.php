<?php
require_once __DIR__ . '/../includes/header.php';

if (!empty($_SESSION['user_id'])) {
    redirect('match/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || $email === '' || $password === '' || $confirm === '') {
        flash('error', 'All fields are required.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Please use a valid email address.');
    } elseif ($password !== $confirm) {
        flash('error', 'Passwords do not match.');
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            flash('error', 'That email is already registered.');
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
            $insert->execute([$name, $email, $hash]);
            flash('success', 'Your account has been created. Please log in.');
            redirect('auth/login.php');
        }
    }
}
?>
<div class="mx-auto max-w-3xl rounded-3xl border border-white/10 bg-[#13121A]/90 p-8 shadow-2xl shadow-black/20">
    <h1 class="mb-4 text-3xl font-extrabold">Create your account</h1>
    <p class="text-gray-400 mb-8">Register and start trading skills with people who want what you can teach.</p>
    <form method="post" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-300">Full name</label>
            <input type="text" name="name" class="field mt-2" value="<?php echo sanitize_input($_POST['name'] ?? ''); ?>" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300">Email address</label>
            <input type="email" name="email" class="field mt-2" value="<?php echo sanitize_input($_POST['email'] ?? ''); ?>" required>
        </div>
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-300">Password</label>
                <input type="password" name="password" class="field mt-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300">Confirm password</label>
                <input type="password" name="confirm_password" class="field mt-2" required>
            </div>
        </div>
        <button type="submit" class="button-primary w-full justify-center">Register account</button>
    </form>
    <p class="mt-6 text-sm text-gray-400">Already have an account? <a href="<?php echo BASE_URL; ?>/auth/login.php" class="text-purple-300 hover:text-purple-100">Log in</a>.</p>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';

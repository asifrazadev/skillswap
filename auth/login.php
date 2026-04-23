<?php
require_once __DIR__ . '/../includes/header.php';

if (!empty($_SESSION['user_id'])) {
    redirect('match/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        flash('error', 'Both email and password are required.');
    } else {
        $stmt = $pdo->prepare('SELECT id, password_hash, name FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            flash('error', 'Invalid credentials, please try again.');
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            flash('success', 'Welcome back, ' . $user['name'] . '!');
            redirect('match/index.php');
        }
    }
}
?>
<div class="mx-auto max-w-3xl rounded-3xl border border-white/10 bg-[#13121A]/90 p-4 shadow-2xl shadow-black/20">
    <h1 class="mb-4 text-2xl font-extrabold">Log in to SkillSwap</h1>
    <p class="text-gray-400 mb-8">Access your profile, browse matches, and send swap requests.</p>
    <form method="post" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-300">Email address</label>
            <input type="email" name="email" class="field mt-2" value="<?php echo sanitize_input($_POST['email'] ?? ''); ?>" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300">Password</label>
            <input type="password" name="password" class="field mt-2" required>
        </div>
        <button type="submit" class="button-primary w-full justify-center">Sign in</button>
    </form>
    <p class="mt-6 text-sm text-gray-400">Need an account? <a href="<?php echo BASE_URL; ?>/auth/register.php" class="text-purple-300 hover:text-purple-100">Register here</a>.</p>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';

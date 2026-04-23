<?php
require_once __DIR__ . '/../includes/header.php';

$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : $_SESSION['user_id'] ?? null;

if (!$userId) {
    redirect('auth/login.php');
}

$stmt = $pdo->prepare('SELECT id, name, bio, location, avg_rating FROM users WHERE id = ?');
$stmt->execute([$userId]);
$profile = $stmt->fetch();

if (!$profile) {
    flash('error', 'Profile not found.');
    redirect('match/index.php');
}

$skillsStmt = $pdo->prepare('SELECT s.name, us.type FROM user_skills us JOIN skills s ON us.skill_id = s.id WHERE us.user_id = ? ORDER BY us.type, s.name');
$skillsStmt->execute([$userId]);
$skills = $skillsStmt->fetchAll();

$offers = array_filter($skills, fn($item) => $item['type'] === 'offered');
$wants = array_filter($skills, fn($item) => $item['type'] === 'wanted');
?>
<div class="grid gap-8 lg:grid-cols-[1fr_320px]">
    <section class="space-y-6 rounded-3xl border border-white/10 bg-[#13121A]/90 p-8 shadow-lg shadow-black/20">
        <div>
            <h1 class="text-3xl font-extrabold"><?php echo sanitize_input($profile['name']); ?></h1>
            <p class="mt-2 text-sm text-gray-400">Location: <?php echo sanitize_input($profile['location'] ?: 'Not set'); ?></p>
            <div class="mt-4 flex items-center gap-2 text-sm text-gray-200">
                <span class="badge">Rating <?php echo number_format($profile['avg_rating'] ?: 0, 1); ?></span>
            </div>
        </div>
        <div>
            <h2 class="mb-3 text-xl font-semibold">Bio</h2>
            <p class="text-gray-300"><?php echo nl2br(sanitize_input($profile['bio'] ?: 'No bio yet.')); ?></p>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-3xl border border-white/10 bg-[#0D0C14] p-5">
                <h3 class="mb-3 text-lg font-semibold">Offers</h3>
                <?php if ($offers): ?>
                    <ul class="space-y-2 text-gray-300">
                        <?php foreach ($offers as $offer): ?>
                            <li>• <?php echo sanitize_input($offer['name']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-gray-500">No offered skills listed.</p>
                <?php endif; ?>
            </div>
            <div class="rounded-3xl border border-white/10 bg-[#0D0C14] p-5">
                <h3 class="mb-3 text-lg font-semibold">Wants</h3>
                <?php if ($wants): ?>
                    <ul class="space-y-2 text-gray-300">
                        <?php foreach ($wants as $want): ?>
                            <li>• <?php echo sanitize_input($want['name']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-gray-500">No wanted skills listed.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <aside class="space-y-6 rounded-3xl border border-white/10 bg-[#13121A]/90 p-8 shadow-lg shadow-black/20">
        <?php if ($_SESSION['user_id'] === $profile['id']): ?>
            <a href="<?php echo BASE_URL; ?>/profile/edit.php" class="button-primary w-full justify-center">Edit profile</a>
            <a href="<?php echo BASE_URL; ?>/match/index.php" class="button-secondary w-full justify-center">Find matches</a>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>/match/index.php" class="button-secondary w-full justify-center">Browse matches</a>
        <?php endif; ?>
        <div class="rounded-3xl border border-white/10 bg-[#0D0C14] p-5">
            <h3 class="mb-3 text-lg font-semibold">Quick actions</h3>
            <ul class="space-y-3 text-gray-300">
                <li><strong>Visit the Match page</strong> to locate direct swap partners.</li>
                <li><strong>Complete swaps</strong> from your swap dashboard after acceptance.</li>
            </ul>
        </div>
    </aside>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';

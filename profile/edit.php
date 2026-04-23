<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';

$user = current_user();

$skillStmt = $pdo->query('SELECT id, name, category FROM skills ORDER BY category, name');
$allSkills = $skillStmt->fetchAll();

$selectedStmt = $pdo->prepare('SELECT skill_id, type FROM user_skills WHERE user_id = ?');
$selectedStmt->execute([$_SESSION['user_id']]);
$currentSkills = $selectedStmt->fetchAll();

$offeredIds = array_column(array_filter($currentSkills, fn($row) => $row['type'] === 'offered'), 'skill_id');
$wantedIds = array_column(array_filter($currentSkills, fn($row) => $row['type'] === 'wanted'), 'skill_id');

$pageTitle = 'Edit your profile';
?>
<div class="mx-auto max-w-4xl rounded-3xl border border-white/10 bg-[#13121A]/90 p-4 shadow-2xl shadow-black/20">
    <h1 class="mb-4 text-2xl font-extrabold">Edit Profile</h1>
    <form method="post" action="<?php echo BASE_URL; ?>/profile/update_skills.php" class="space-y-8">
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-300">Full name</label>
                <input type="text" name="name" class="field mt-2" value="<?php echo sanitize_input($user['name']); ?>" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300">Location</label>
                <input type="text" name="location" class="field mt-2" value="<?php echo sanitize_input($user['location']); ?>">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300">Bio</label>
            <textarea name="bio" rows="5" class="field mt-2"><?php echo sanitize_input($user['bio']); ?></textarea>
        </div>
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-3xl border border-white/10 bg-[#0D0C14] p-5">
                <h2 class="mb-4 text-lg font-semibold">I can teach</h2>
                <div class="space-y-3">
                    <?php foreach ($allSkills as $skill): ?>
                        <label class="flex items-center gap-3 text-gray-300">
                            <input type="checkbox" name="offered_skills[]" value="<?php echo $skill['id']; ?>" <?php echo in_array($skill['id'], $offeredIds) ? 'checked' : ''; ?> class="h-4 w-4 rounded border-white/20 bg-[#0D0C14] text-purple-500 focus:ring-purple-500">
                            <span><?php echo sanitize_input($skill['name']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="rounded-3xl border border-white/10 bg-[#0D0C14] p-5">
                <h2 class="mb-4 text-lg font-semibold">I want to learn</h2>
                <div class="space-y-3">
                    <?php foreach ($allSkills as $skill): ?>
                        <label class="flex items-center gap-3 text-gray-300">
                            <input type="checkbox" name="wanted_skills[]" value="<?php echo $skill['id']; ?>" <?php echo in_array($skill['id'], $wantedIds) ? 'checked' : ''; ?> class="h-4 w-4 rounded border-white/20 bg-[#0D0C14] text-pink-500 focus:ring-pink-500">
                            <span><?php echo sanitize_input($skill['name']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <button type="submit" class="button-primary">Save profile</button>
            <a href="<?php echo BASE_URL; ?>/profile/view.php" class="button-secondary">Cancel</a>
        </div>
    </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';

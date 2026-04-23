<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';

$current = current_user();

$offered = $pdo->prepare('SELECT skill_id FROM user_skills WHERE user_id = ? AND type = ?');
$offered->execute([$_SESSION['user_id'], 'offered']);
$offeredSkills = $offered->fetchAll(PDO::FETCH_COLUMN);

$wanted = $pdo->prepare('SELECT skill_id FROM user_skills WHERE user_id = ? AND type = ?');
$wanted->execute([$_SESSION['user_id'], 'wanted']);
$wantedSkills = $wanted->fetchAll(PDO::FETCH_COLUMN);

$pageTitle = 'Find direct matches';

$matches = [];
if ($offeredSkills && $wantedSkills) {
    $query = <<<SQL
SELECT DISTINCT u.id AS user_id,
       u.name AS user_name,
       u.location,
       u.avg_rating,
       s_offer.name AS their_offer,
       s_want.name AS their_want,
       my_offer.skill_id AS my_offered_skill_id,
       my_want.skill_id AS my_wanted_skill_id
FROM user_skills my_offer
JOIN user_skills my_want ON my_offer.user_id = ? AND my_offer.type = 'offered' AND my_want.user_id = ? AND my_want.type = 'wanted'
JOIN user_skills their_offer ON their_offer.type = 'wanted' AND their_offer.skill_id = my_offer.skill_id
JOIN user_skills their_want ON their_want.type = 'offered' AND their_want.skill_id = my_want.skill_id AND their_offer.user_id = their_want.user_id
JOIN users u ON u.id = their_offer.user_id AND u.id != ?
JOIN skills s_offer ON s_offer.id = their_offer.skill_id
JOIN skills s_want ON s_want.id = their_want.skill_id
WHERE NOT EXISTS (
    SELECT 1 FROM swap_requests 
    WHERE ((sender_id = ? AND receiver_id = u.id) OR (sender_id = u.id AND receiver_id = ?)) 
    AND status IN ('pending', 'accepted', 'completed')
)
AND NOT EXISTS (
    SELECT 1 FROM user_skills 
    WHERE user_id = u.id AND type = 'offered' AND skill_id IN (SELECT skill_id FROM user_skills WHERE user_id = ? AND type = 'offered')
)
SQL;
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id']]);
    $matches = $stmt->fetchAll();
}
?>
<div class="space-y-8">
    <div class="rounded-3xl border border-white/10 bg-[#13121A]/90 p-4 shadow-xl shadow-black/20">
        <h1 class="mb-2 text-xl font-extrabold">Direct Match Results</h1>
        <p class="text-gray-400 text-sm">Matching users who want what you offer and offer what you want.</p>
    </div>

    <?php if (!$offeredSkills || !$wantedSkills): ?>
        <div class="rounded-3xl border border-yellow-500/20 bg-yellow-500/10 p-8 text-yellow-100">
            <p class="font-medium">Update your profile with offered and wanted skills first.</p>
            <a href="<?php echo BASE_URL; ?>/profile/edit.php" class="button-primary mt-4 inline-flex">Edit profile</a>
        </div>
    <?php elseif (empty($matches)): ?>
        <div class="rounded-3xl border border-white/10 bg-[#0D0C14]/90 p-8 text-gray-300">
            <h2 class="text-xl font-semibold">No direct matches found yet.</h2>
            <p class="mt-3">You can still update your preferences or browse the same skill categories again later.</p>
        </div>
    <?php else: ?>
        <div class="grid gap-6">
            <?php foreach ($matches as $match): ?>
                <div class="rounded-3xl border border-white/10 bg-[#0D0C14]/90 p-4 shadow-lg shadow-black/20">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-lg font-bold"><?php echo sanitize_input($match['user_name']); ?></h2>
                            <p class="text-gray-400 text-sm">Location: <?php echo sanitize_input($match['location'] ?: 'Unknown'); ?></p>
                            <p class="mt-2 text-sm text-gray-300">Rating: <?php echo number_format($match['avg_rating'] ?: 0,1); ?> ⭐</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="<?php echo BASE_URL; ?>/profile/view.php?user_id=<?php echo $match['user_id']; ?>" class="button-secondary">View profile</a>
                            <a href="<?php echo BASE_URL; ?>/swaps/request.php?receiver_id=<?php echo $match['user_id']; ?>&offered_skill_id=<?php echo $match['my_offered_skill_id']; ?>&wanted_skill_id=<?php echo $match['my_wanted_skill_id']; ?>" class="button-primary">Request swap</a>
                        </div>
                    </div>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-3xl border border-white/10 bg-[#13121A]/80 p-4">
                            <p class="text-sm text-gray-400 uppercase tracking-[0.2em]">They offer</p>
                            <p class="mt-3 text-lg font-semibold"><?php echo sanitize_input($match['their_offer']); ?></p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-[#13121A]/80 p-4">
                            <p class="text-sm text-gray-400 uppercase tracking-[0.2em]">They want</p>
                            <p class="mt-3 text-lg font-semibold"><?php echo sanitize_input($match['their_want']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';

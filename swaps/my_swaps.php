<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';

$pageTitle = 'My swap requests';

$query = <<<SQL
SELECT sr.*, 
       sender.name AS sender_name,
       receiver.name AS receiver_name,
       offered.name AS offered_skill,
       wanted.name AS wanted_skill,
       t.id AS thread_id,
       r.id AS has_rated
FROM swap_requests sr
JOIN users sender ON sender.id = sr.sender_id
JOIN users receiver ON receiver.id = sr.receiver_id
JOIN skills offered ON offered.id = sr.offered_skill_id
JOIN skills wanted ON wanted.id = sr.wanted_skill_id
LEFT JOIN threads t ON t.swap_id = sr.id
LEFT JOIN ratings r ON r.swap_id = sr.id AND r.rater_id = ?
WHERE sr.sender_id = ? OR sr.receiver_id = ?
ORDER BY sr.created_at DESC
SQL;
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id']]);
$swaps = $stmt->fetchAll();
?>
<div class="space-y-8">
    <div class="rounded-3xl border border-white/10 bg-[#13121A]/90 p-8 shadow-xl shadow-black/20">
        <h1 class="text-3xl font-extrabold">My Swaps</h1>
        <p class="mt-2 text-gray-400">Manage requests you sent and requests you received.</p>
    </div>

    <?php if (empty($swaps)): ?>
        <div class="rounded-3xl border border-white/10 bg-[#0D0C14]/90 p-8 text-gray-300">
            <p class="text-lg">No swap activity yet. Visit the match page to create your first request.</p>
            <a href="<?php echo BASE_URL; ?>/match/index.php" class="button-primary mt-4 inline-flex">Find matches</a>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($swaps as $swap): ?>
                <div class="rounded-3xl border border-white/10 bg-[#0D0C14]/90 p-6 shadow-lg shadow-black/20">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <span class="badge"><?php echo sanitize_input(ucfirst($swap['status'])); ?></span>
                            <h2 class="mt-3 text-2xl font-bold"><?php echo sanitize_input($swap['sender_id'] === $_SESSION['user_id'] ? 'You requested' : 'Request from ' . $swap['sender_name']); ?></h2>
                            <p class="mt-2 text-gray-400">Sender offered: <?php echo sanitize_input($swap['offered_skill']); ?> • Sender wants: <?php echo sanitize_input($swap['wanted_skill']); ?></p>
                            <p class="mt-1 text-sm text-gray-500">Created: <?php echo date('M j, Y', strtotime($swap['created_at'])); ?></p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <?php if ($swap['status'] === 'pending' && $swap['receiver_id'] === $_SESSION['user_id']): ?>
                                <a href="<?php echo BASE_URL; ?>/swaps/respond.php?id=<?php echo $swap['id']; ?>&action=accept" class="button-primary">Accept</a>
                                <a href="<?php echo BASE_URL; ?>/swaps/respond.php?id=<?php echo $swap['id']; ?>&action=decline" class="button-secondary">Decline</a>
                            <?php endif; ?>
                            <?php if ($swap['status'] === 'accepted'): ?>
                                <a href="<?php echo BASE_URL; ?>/swaps/complete.php?id=<?php echo $swap['id']; ?>" class="button-primary">Mark complete</a>
                            <?php endif; ?>
                            <?php if ($swap['status'] === 'completed' && !$swap['has_rated']): ?>
                                <a href="<?php echo BASE_URL; ?>/ratings/rate.php?swap_id=<?php echo $swap['id']; ?>" class="button-primary">Leave rating</a>
                            <?php endif; ?>
                            <?php if ($swap['thread_id']): ?>
                                <a href="<?php echo BASE_URL; ?>/messages/thread.php?swap_id=<?php echo $swap['id']; ?>" class="button-secondary">View thread</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-3xl border border-white/10 bg-[#13121A]/80 p-4">
                            <p class="text-sm text-gray-400 uppercase tracking-[0.2em]">Offered by sender</p>
                            <p class="mt-2 text-lg font-semibold"><?php echo sanitize_input($swap['offered_skill']); ?></p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-[#13121A]/80 p-4">
                            <p class="text-sm text-gray-400 uppercase tracking-[0.2em]">Wanted by sender</p>
                            <p class="mt-2 text-lg font-semibold"><?php echo sanitize_input($swap['wanted_skill']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';

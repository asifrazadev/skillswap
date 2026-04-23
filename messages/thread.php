<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';

$swapId = intval($_GET['swap_id'] ?? 0);
if (!$swapId) {
    flash('error', 'Missing swap reference.');
    redirect('swaps/my_swaps.php');
}

$stmt = $pdo->prepare('SELECT sr.*, t.id AS thread_id FROM swap_requests sr LEFT JOIN threads t ON t.swap_id = sr.id WHERE sr.id = ?');
$stmt->execute([$swapId]);
$swap = $stmt->fetch();

if (!$swap || !in_array($_SESSION['user_id'], [$swap['sender_id'], $swap['receiver_id']], true)) {
    flash('error', 'You cannot access that thread.');
    redirect('swaps/my_swaps.php');
}

if (!$swap['thread_id']) {
    $insert = $pdo->prepare('INSERT INTO threads (swap_id) VALUES (?)');
    $insert->execute([$swapId]);
    $swap['thread_id'] = $pdo->lastInsertId();
}

$messagesStmt = $pdo->prepare('SELECT m.*, u.name AS sender_name FROM messages m JOIN users u ON u.id = m.sender_id WHERE m.thread_id = ? ORDER BY m.sent_at ASC');
$messagesStmt->execute([$swap['thread_id']]);
$messages = $messagesStmt->fetchAll();

$pageTitle = 'Swap conversation';
?>
<div class="space-y-8">
    <div class="rounded-3xl border border-white/10 bg-[#13121A]/90 p-8 shadow-xl shadow-black/20">
        <h1 class="text-3xl font-extrabold">Swap thread</h1>
        <p class="mt-2 text-gray-400">Discuss the details of your swap here. Refresh after sending a message.</p>
    </div>

    <div class="rounded-3xl border border-white/10 bg-[#0D0C14]/90 p-8 shadow-lg shadow-black/20">
        <div class="mb-6 grid gap-4 md:grid-cols-2">
            <div>
                <p class="text-sm text-gray-400 uppercase tracking-[0.2em]">Swap status</p>
                <p class="mt-2 text-lg font-semibold"><?php echo sanitize_input(ucfirst($swap['status'])); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-400 uppercase tracking-[0.2em]">Participants</p>
                <p class="mt-2 text-lg font-semibold"><?php echo sanitize_input($swap['sender_id'] === $_SESSION['user_id'] ? 'You and receiver' : 'You and sender'); ?></p>
            </div>
        </div>
        <div class="space-y-4">
            <?php if (empty($messages)): ?>
                <p class="text-gray-400">No messages yet. Start the conversation with a short note.</p>
            <?php else: ?>
                <?php foreach ($messages as $message): ?>
                    <div class="rounded-3xl border border-white/10 bg-[#13121A]/80 p-4">
                        <div class="flex items-center justify-between gap-4 text-sm text-gray-400">
                            <span><?php echo sanitize_input($message['sender_name']); ?></span>
                            <span><?php echo date('M j, Y H:i', strtotime($message['sent_at'])); ?></span>
                        </div>
                        <p class="mt-3 text-gray-100"><?php echo nl2br(sanitize_input($message['body'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-[#13121A]/90 p-8 shadow-lg shadow-black/20">
        <h2 class="mb-4 text-xl font-semibold">Send a new message</h2>
        <form method="post" action="<?php echo BASE_URL; ?>/messages/send.php" class="space-y-4">
            <input type="hidden" name="thread_id" value="<?php echo sanitize_input($swap['thread_id']); ?>">
            <input type="hidden" name="swap_id" value="<?php echo $swapId; ?>">
            <textarea name="body" rows="5" class="field w-full" placeholder="Write your message..." required></textarea>
            <button type="submit" class="button-primary">Send message</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';

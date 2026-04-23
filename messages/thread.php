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

$pageTitle = 'Swap chat';
?>
<div class="flex flex-col h-screen max-h-screen">
    <!-- Header -->
    <div class="bg-[#0B0A10] border-b border-white/10 px-4 py-3 flex items-center gap-4">
        <a href="<?php echo BASE_URL; ?>/swaps/my_swaps.php" class="text-gray-400 hover:text-white">&larr; Back</a>
        <div>
            <h1 class="text-lg font-semibold">Swap Chat</h1>
            <p class="text-sm text-gray-400"><?php echo sanitize_input(ucfirst($swap['status'])); ?> • <?php echo sanitize_input($swap['sender_id'] === $_SESSION['user_id'] ? 'You and receiver' : 'You and sender'); ?></p>
        </div>
    </div>

    <!-- Messages Area -->
    <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-3 bg-[#13121A]">
        <?php if (empty($messages)): ?>
            <div class="text-center text-gray-500 py-8">
                <p>No messages yet. Start the conversation!</p>
            </div>
        <?php else: ?>
            <?php foreach ($messages as $message): ?>
                <div class="flex <?php echo $message['sender_id'] === $_SESSION['user_id'] ? 'justify-end' : 'justify-start'; ?>">
                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-2xl <?php echo $message['sender_id'] === $_SESSION['user_id'] ? 'bg-blue-600 text-white' : 'bg-white text-gray-900'; ?>">
                        <p class="text-sm"><?php echo nl2br(sanitize_input($message['body'])); ?></p>
                        <p class="text-xs mt-1 opacity-70"><?php echo date('H:i', strtotime($message['sent_at'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Message Form -->
    <div class="bg-[#0B0A10] border-t border-white/10 p-4">
        <form id="message-form" class="flex gap-3">
            <input type="hidden" name="thread_id" value="<?php echo sanitize_input($swap['thread_id']); ?>">
            <input type="hidden" name="swap_id" value="<?php echo $swapId; ?>">
            <textarea name="body" rows="1" class="flex-1 field resize-none" placeholder="Type a message..." required></textarea>
            <button type="submit" class="button-primary px-4 py-2">Send</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messages-container');
    const messageForm = document.getElementById('message-form');
    const threadId = <?php echo $swap['thread_id']; ?>;
    let lastMessageId = <?php echo empty($messages) ? 0 : end($messages)['id']; ?>;

    // Auto-resize textarea
    const textarea = messageForm.querySelector('textarea');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });

    // Submit form via AJAX
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('<?php echo BASE_URL; ?>/messages/send.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(() => {
            textarea.value = '';
            textarea.style.height = 'auto';
            pollMessages(); // Immediately poll for new messages
        })
        .catch(error => console.error('Error:', error));
    });

    // Poll for new messages
    function pollMessages() {
        fetch(`<?php echo BASE_URL; ?>/messages/fetch.php?thread_id=${threadId}&last_id=${lastMessageId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) return;
            data.forEach(msg => {
                const isOwn = msg.sender_id == <?php echo $_SESSION['user_id']; ?>;
                const msgDiv = document.createElement('div');
                msgDiv.className = `flex ${isOwn ? 'justify-end' : 'justify-start'}`;
                msgDiv.innerHTML = `
                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-2xl ${isOwn ? 'bg-blue-600 text-white' : 'bg-white text-gray-900'}">
                        <p class="text-sm">${msg.body.replace(/\n/g, '<br>')}</p>
                        <p class="text-xs mt-1 opacity-70">${new Date(msg.sent_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                    </div>
                `;
                messagesContainer.appendChild(msgDiv);
                lastMessageId = msg.id;
            });
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        })
        .catch(error => console.error('Poll error:', error));
    }

    // Poll every 3 seconds
    setInterval(pollMessages, 3000);

    // Scroll to bottom initially
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
});
</script>
<?php require_once __DIR__ . '/../includes/footer.php';


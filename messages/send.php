<?php
require_once __DIR__ . '/../includes/auth_check.php';

$threadId = intval($_POST['thread_id'] ?? 0);
$swapId = intval($_POST['swap_id'] ?? 0);
$body = trim($_POST['body'] ?? '');

if (!$threadId || !$swapId || $body === '') {
    flash('error', 'Please enter a message before sending.');
    redirect('messages/thread.php?swap_id=' . $swapId);
}

$stmt = $pdo->prepare('SELECT sr.* FROM swap_requests sr JOIN threads t ON t.swap_id = sr.id WHERE sr.id = ? AND t.id = ?');
$stmt->execute([$swapId, $threadId]);
$swap = $stmt->fetch();

if (!$swap || !in_array($_SESSION['user_id'], [$swap['sender_id'], $swap['receiver_id']], true)) {
    flash('error', 'You cannot send a message to this thread.');
    redirect('swaps/my_swaps.php');
}

$insert = $pdo->prepare('INSERT INTO messages (thread_id, sender_id, body) VALUES (?, ?, ?)');
$insert->execute([$threadId, $_SESSION['user_id'], $body]);

flash('success', 'Message sent.');
redirect('messages/thread.php?swap_id=' . $swapId);

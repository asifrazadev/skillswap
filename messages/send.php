<?php
require_once __DIR__ . '/../includes/auth_check.php';

$threadId = intval($_POST['thread_id'] ?? 0);
$swapId = intval($_POST['swap_id'] ?? 0);
$body = trim($_POST['body'] ?? '');

if (!$threadId || !$swapId || $body === '') {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Please enter a message before sending.']);
        exit;
    }
    flash('error', 'Please enter a message before sending.');
    redirect('messages/thread.php?swap_id=' . $swapId);
}

$stmt = $pdo->prepare('SELECT sr.* FROM swap_requests sr JOIN threads t ON t.swap_id = sr.id WHERE sr.id = ? AND t.id = ?');
$stmt->execute([$swapId, $threadId]);
$swap = $stmt->fetch();

if (!$swap || !in_array($_SESSION['user_id'], [$swap['sender_id'], $swap['receiver_id']], true)) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'You cannot send a message to this thread.']);
        exit;
    }
    flash('error', 'You cannot send a message to this thread.');
    redirect('swaps/my_swaps.php');
}

$insert = $pdo->prepare('INSERT INTO messages (thread_id, sender_id, body) VALUES (?, ?, ?)');
$insert->execute([$threadId, $_SESSION['user_id'], $body]);

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
}

flash('success', 'Message sent.');
redirect('messages/thread.php?swap_id=' . $swapId);

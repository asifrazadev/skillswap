<?php
require_once __DIR__ . '/../includes/auth_check.php';

$swapId = intval($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';

if (!$swapId || !in_array($action, ['accept', 'decline'], true)) {
    flash('error', 'Invalid response action.');
    redirect('swaps/my_swaps.php');
}

$stmt = $pdo->prepare('SELECT * FROM swap_requests WHERE id = ?');
$stmt->execute([$swapId]);
$swap = $stmt->fetch();

if (!$swap || $swap['receiver_id'] !== $_SESSION['user_id'] || $swap['status'] !== 'pending') {
    flash('error', 'You are not allowed to respond to that request.');
    redirect('swaps/my_swaps.php');
}

$newStatus = ($action === 'accept') ? 'accepted' : 'declined';
$update = $pdo->prepare('UPDATE swap_requests SET status = ? WHERE id = ?');
$update->execute([$newStatus, $swapId]);

flash('success', 'The swap request has been ' . ($newStatus === 'accepted' ? 'accepted.' : 'declined.'));
redirect('swaps/my_swaps.php');

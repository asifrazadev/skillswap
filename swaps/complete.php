<?php
require_once __DIR__ . '/../includes/auth_check.php';

$swapId = intval($_GET['id'] ?? 0);

if (!$swapId) {
    flash('error', 'Missing swap ID.');
    redirect('swaps/my_swaps.php');
}

$stmt = $pdo->prepare('SELECT * FROM swap_requests WHERE id = ?');
$stmt->execute([$swapId]);
$swap = $stmt->fetch();

if (!$swap || $swap['status'] !== 'accepted' || !in_array($_SESSION['user_id'], [$swap['sender_id'], $swap['receiver_id']], true)) {
    flash('error', 'Unable to complete this swap.');
    redirect('swaps/my_swaps.php');
}

$update = $pdo->prepare('UPDATE swap_requests SET status = ? WHERE id = ?');
$update->execute(['completed', $swapId]);

flash('success', 'Swap marked as completed. You may leave a rating now.');
redirect('swaps/my_swaps.php');

<?php
require_once __DIR__ . '/../includes/auth_check.php';

$receiverId = intval($_GET['receiver_id'] ?? 0);
$offeredSkillId = intval($_GET['offered_skill_id'] ?? 0);
$wantedSkillId = intval($_GET['wanted_skill_id'] ?? 0);

if (!$receiverId || !$offeredSkillId || !$wantedSkillId) {
    flash('error', 'Invalid swap request parameters.');
    redirect('match/index.php');
}

if ($receiverId === $_SESSION['user_id']) {
    flash('error', 'You cannot request a swap with yourself.');
    redirect('match/index.php');
}

$stmt = $pdo->prepare('SELECT 1 FROM user_skills WHERE user_id = ? AND skill_id = ? AND type = ?');
$stmt->execute([$_SESSION['user_id'], $offeredSkillId, 'offered']);
if (!$stmt->fetch()) {
    flash('error', 'Your offered skill selection is invalid.');
    redirect('match/index.php');
}

$stmt->execute([$receiverId, $offeredSkillId, 'wanted']);
if (!$stmt->fetch()) {
    flash('error', 'That user does not want the skill you offered.');
    redirect('match/index.php');
}

$stmt->execute([$_SESSION['user_id'], $wantedSkillId, 'wanted']);
if (!$stmt->fetch()) {
    flash('error', 'Your wanted skill selection is invalid.');
    redirect('match/index.php');
}

$stmt->execute([$receiverId, $wantedSkillId, 'offered']);
if (!$stmt->fetch()) {
    flash('error', 'That user does not offer the skill you want.');
    redirect('match/index.php');
}

$insert = $pdo->prepare('INSERT INTO swap_requests (sender_id, receiver_id, offered_skill_id, wanted_skill_id, status) VALUES (?, ?, ?, ?, ?)');
$insert->execute([$_SESSION['user_id'], $receiverId, $offeredSkillId, $wantedSkillId, 'pending']);
$swapId = $pdo->lastInsertId();

$threadInsert = $pdo->prepare('INSERT INTO threads (swap_id) VALUES (?)');
$threadInsert->execute([$swapId]);

flash('success', 'Swap request sent successfully.');
redirect('swaps/my_swaps.php');

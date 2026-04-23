<?php
require_once __DIR__ . '/../includes/auth_check.php';

$threadId = intval($_GET['thread_id'] ?? 0);
$lastId = intval($_GET['last_id'] ?? 0);

if (!$threadId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing thread_id']);
    exit;
}

// Check if user can access this thread
$stmt = $pdo->prepare('SELECT sr.id FROM swap_requests sr JOIN threads t ON t.swap_id = sr.id WHERE t.id = ? AND (sr.sender_id = ? OR sr.receiver_id = ?)');
$stmt->execute([$threadId, $_SESSION['user_id'], $_SESSION['user_id']]);
if (!$stmt->fetch()) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$messagesStmt = $pdo->prepare('SELECT m.id, m.body, m.sent_at, u.name AS sender_name, m.sender_id FROM messages m JOIN users u ON u.id = m.sender_id WHERE m.thread_id = ? AND m.id > ? ORDER BY m.sent_at ASC');
$messagesStmt->execute([$threadId, $lastId]);
$messages = $messagesStmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($messages);

<?php
require_once __DIR__ . '/../includes/auth_check.php';

$name = trim($_POST['name'] ?? '');
$bio = trim($_POST['bio'] ?? '');
$location = trim($_POST['location'] ?? '');
$offered = $_POST['offered_skills'] ?? [];
$wanted = $_POST['wanted_skills'] ?? [];

if ($name === '') {
    flash('error', 'Your name is required.');
    redirect('profile/edit.php');
}

// Verify user exists
$userCheck = $pdo->prepare('SELECT id FROM users WHERE id = ?');
$userCheck->execute([$_SESSION['user_id']]);
if (!$userCheck->fetch()) {
    flash('error', 'User not found. Please log in again.');
    redirect('auth/login.php');
}

$update = $pdo->prepare('UPDATE users SET name = ?, bio = ?, location = ? WHERE id = ?');
$update->execute([$name, $bio, $location, $_SESSION['user_id']]);

$pdo->prepare('DELETE FROM user_skills WHERE user_id = ?')->execute([$_SESSION['user_id']]);

$insert = $pdo->prepare('INSERT INTO user_skills (user_id, skill_id, type) VALUES (?, ?, ?)');
// Also verify skill exists to prevent FK errors
$skillCheck = $pdo->prepare('SELECT id FROM skills WHERE id = ?');
foreach (array_unique($offered) as $skillId) {
    $skillId = intval($skillId);
    if ($skillId > 0) {
        $skillCheck->execute([$skillId]);
        if ($skillCheck->fetch()) {
            $insert->execute([$_SESSION['user_id'], $skillId, 'offered']);
        }
    }
}
foreach (array_unique($wanted) as $skillId) {
    $skillId = intval($skillId);
    if ($skillId > 0) {
        $skillCheck->execute([$skillId]);
        if ($skillCheck->fetch()) {
            $insert->execute([$_SESSION['user_id'], $skillId, 'wanted']);
        }
    }
}

flash('success', 'Profile and skill preferences updated.');
redirect('profile/view.php');


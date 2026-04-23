<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

function sanitize_input($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function flash($type, $message = null) {
    if ($message !== null) {
        $_SESSION['flash'][$type] = $message;
        return;
    }

    if (!empty($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $msg;
    }

    return null;
}

function redirect($path) {
    $location = $path;
    if (strpos($path, 'http') !== 0 && strpos($path, '/') !== 0) {
        $location = BASE_URL . '/' . ltrim($path, '/');
    } elseif (strpos($path, '/') === 0) {
        $location = BASE_URL . $path;
    }

    header('Location: ' . $location);
    exit;
}

function current_user() {
    global $pdo;
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT id, name, email, bio, location, avg_rating FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function require_login() {
    if (empty($_SESSION['user_id'])) {
        flash('error', 'Please log in to access that page.');
        redirect('auth/login.php');
    }
}

function update_user_avg_rating($userId) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT AVG(stars) AS avg_rating FROM ratings WHERE ratee_id = ?');
    $stmt->execute([$userId]);
    $avg = $stmt->fetchColumn();
    $avg = $avg !== null ? number_format($avg, 2) : 0.0;
    $update = $pdo->prepare('UPDATE users SET avg_rating = ? WHERE id = ?');
    $update->execute([$avg, $userId]);
}

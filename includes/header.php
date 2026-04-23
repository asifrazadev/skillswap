<?php
require_once __DIR__ . '/functions.php';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? sanitize_input($pageTitle) : SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-heading: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;
            --color-bg-main: #0B0A10;
            --color-bg-secondary: #13121A;
            --color-primary: #7C3AED;
            --color-secondary: #EC4899;
        }
        body { font-family: var(--font-body); background-color: var(--color-bg-main); color: white; }
        a { color: inherit; }
        .glass { background-color: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); }
        .button-primary { @apply inline-flex items-center justify-center gap-2 rounded-full px-5 py-3 text-sm font-semibold bg-gradient-to-br from-purple-600 to-pink-500 hover:-translate-y-0.5 transition-all; }
        .button-secondary { @apply inline-flex items-center justify-center gap-2 rounded-full px-5 py-3 text-sm font-semibold border border-white/15 hover:bg-white/5 transition-all; }
        .field { @apply w-full rounded-2xl border border-white/10 bg-[#0F0E16] px-4 py-3 text-sm text-white focus:border-purple-500 focus:outline-none; }
        .badge { @apply inline-flex items-center gap-2 rounded-full bg-white/5 px-3 py-2 text-xs uppercase tracking-[0.2em] text-gray-300; }
    </style>
</head>
<body class="min-h-screen">
<header class="bg-[#0B0A10] border-b border-white/10 sticky top-0 z-50">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-6 py-4">
        <a href="<?php echo BASE_URL; ?>" class="text-2xl font-heading font-extrabold tracking-tight text-white">SkillSwap</a>
        <nav class="flex flex-wrap items-center gap-3 text-sm font-medium text-gray-200">
            <a href="<?php echo BASE_URL; ?>/index.php" class="hover:text-white">Home</a>
            <a href="<?php echo BASE_URL; ?>/match/index.php" class="hover:text-white">Match</a>
            <?php if ($user): ?>
                <a href="<?php echo BASE_URL; ?>/profile/view.php" class="hover:text-white">Profile</a>
                <a href="<?php echo BASE_URL; ?>/swaps/my_swaps.php" class="hover:text-white">My Swaps</a>
            <?php endif; ?>
        </nav>
        <div class="flex flex-wrap items-center gap-3">
            <?php if ($user): ?>
                <span class="badge"><?php echo sanitize_input($user['name']); ?></span>
                <a href="<?php echo BASE_URL; ?>/auth/logout.php" class="button-secondary">Logout</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/auth/login.php" class="button-secondary">Login</a>
                <a href="<?php echo BASE_URL; ?>/auth/register.php" class="button-primary">Register</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<main class="mx-auto max-w-6xl px-6 py-8"> 
    <?php if ($message = flash('success')): ?>
        <div class="mb-6 rounded-3xl border border-green-500/20 bg-green-500/10 px-6 py-4 text-green-100">
            <?php echo sanitize_input($message); ?>
        </div>
    <?php endif; ?>
    <?php if ($message = flash('error')): ?>
        <div class="mb-6 rounded-3xl border border-red-500/20 bg-red-500/10 px-6 py-4 text-red-100">
            <?php echo sanitize_input($message); ?>
        </div>
    <?php endif; ?>

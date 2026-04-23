<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';

$swapId = intval($_GET['swap_id'] ?? $_POST['swap_id'] ?? 0);
if (!$swapId) {
    flash('error', 'Swap not found.');
    redirect('swaps/my_swaps.php');
}

$stmt = $pdo->prepare('SELECT sr.*, sender.name AS sender_name, receiver.name AS receiver_name FROM swap_requests sr JOIN users sender ON sender.id = sr.sender_id JOIN users receiver ON receiver.id = sr.receiver_id WHERE sr.id = ?');
$stmt->execute([$swapId]);
$swap = $stmt->fetch();

if (!$swap || $swap['status'] !== 'completed' || !in_array($_SESSION['user_id'], [$swap['sender_id'], $swap['receiver_id']], true)) {
    flash('error', 'You can only rate completed swaps in which you participated.');
    redirect('swaps/my_swaps.php');
}

$rateeId = $_SESSION['user_id'] === $swap['sender_id'] ? $swap['receiver_id'] : $swap['sender_id'];
$rateeName = $_SESSION['user_id'] === $swap['sender_id'] ? $swap['receiver_name'] : $swap['sender_name'];

$existsStmt = $pdo->prepare('SELECT id FROM ratings WHERE swap_id = ? AND rater_id = ?');
$existsStmt->execute([$swapId, $_SESSION['user_id']]);
$hasRated = (bool) $existsStmt->fetch();

if ($hasRated) {
    flash('error', 'You have already submitted a rating for this swap.');
    redirect('swaps/my_swaps.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stars = intval($_POST['stars'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($stars < 1 || $stars > 5) {
        flash('error', 'Please select a rating between 1 and 5 stars.');
    } else {
        $insert = $pdo->prepare('INSERT INTO ratings (swap_id, rater_id, ratee_id, stars, comment) VALUES (?, ?, ?, ?, ?)');
        $insert->execute([$swapId, $_SESSION['user_id'], $rateeId, $stars, $comment]);
        update_user_avg_rating($rateeId);
        flash('success', 'Your rating has been submitted.');
        redirect('swaps/my_swaps.php');
    }
}

$pageTitle = 'Rate your swap partner';
?>
<div class="mx-auto max-w-3xl rounded-3xl border border-white/10 bg-[#13121A]/90 p-8 shadow-2xl shadow-black/20">
    <h1 class="mb-3 text-3xl font-extrabold">Rate <?php echo sanitize_input($rateeName); ?></h1>
    <p class="text-gray-400 mb-8">Leave a simple 1–5 rating and optional feedback.</p>
    <form method="post" class="space-y-6">
        <input type="hidden" name="swap_id" value="<?php echo $swapId; ?>">
        <div>
            <label class="block text-sm font-medium text-gray-300">Stars</label>
            <select name="stars" class="field mt-2" required>
                <option value="">Choose rating</option>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?> star<?php echo $i === 1 ? '' : 's'; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300">Comment <span class="text-gray-500">(optional)</span></label>
            <textarea name="comment" rows="5" class="field mt-2" placeholder="What went well or what could improve..."></textarea>
        </div>
        <button type="submit" class="button-primary">Submit rating</button>
        <a href="<?php echo BASE_URL; ?>/swaps/my_swaps.php" class="button-secondary">Back to swaps</a>
    </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';

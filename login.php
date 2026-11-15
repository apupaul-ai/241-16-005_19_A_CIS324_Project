<?php
require_once __DIR__ . '/config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, name, password_hash, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        header('Location: /pothik/index.php');
        exit;
    }
    $errors[] = 'Invalid email or password.';
}

$pageTitle = 'Login';
require __DIR__ . '/includes/header.php';
?>

<section class="max-w-md mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-pothik-green mb-6">Login</h1>

    <?php if (isset($_GET['registered'])): ?>
        <div class="mb-4 rounded-lg border border-green-300 bg-green-50 p-4 text-sm text-green-700">
            Account created. Please login.
        </div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700">
            <?= htmlspecialchars($errors[0]) ?>
        </div>
    <?php endif; ?>

    <form method="post" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1" for="email">Email</label>
            <input class="w-full border rounded-lg px-3 py-2" type="email" id="email" name="email"
                   value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="password">Password</label>
            <input class="w-full border rounded-lg px-3 py-2" type="password" id="password" name="password" required>
        </div>
        <button class="w-full bg-pothik-green text-white font-semibold py-2 rounded-lg hover:opacity-90" type="submit">
            Login
        </button>
    </form>

    <p class="mt-4 text-sm text-gray-600">
        New here? <a class="text-pothik-green underline" href="/pothik/register.php">Create an account</a>
    </p>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
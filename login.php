<?php
require_once __DIR__ . '/includes/csrf_auto.php';
require_once __DIR__ . '/config/config.php';

const MAX_ATTEMPTS = 5;
const LOCK_SECONDS = 300;

$errors = [];
$email = '';

$attempts = $_SESSION['login_attempts'] ?? 0;
$lockedUntil = $_SESSION['login_locked_until'] ?? 0;
$locked = $lockedUntil > time();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($locked) {
        $wait = (int) ceil(($lockedUntil - time()) / 60);
        $errors[] = "Too many failed attempts. Try again in about {$wait} minute(s).";
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT id, name, password_hash, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $next = $_SESSION['login_next'] ?? '/pothik/index.php';
            session_regenerate_id(true);
            unset($_SESSION['login_attempts'], $_SESSION['login_locked_until'], $_SESSION['login_next']);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Only allow redirects inside this site (blocks open-redirect tricks).
            if (strpos($next, '/pothik/') !== 0 || strpos($next, '//') !== false) {
                $next = '/pothik/index.php';
            }
            header('Location: ' . $next);
            exit;
        }

        $_SESSION['login_attempts'] = ++$attempts;
        if ($attempts >= MAX_ATTEMPTS) {
            $_SESSION['login_locked_until'] = time() + LOCK_SECONDS;
            $_SESSION['login_attempts'] = 0;
            $errors[] = 'Too many failed attempts. Login locked for 5 minutes.';
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}

$pageTitle = 'Login';
require __DIR__ . '/includes/header.php';
?>

<section class="max-w-md mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-pothik-green mb-6">Login</h1>

    <?php if (isset($_GET['required'])): ?>
        <div class="mb-4 rounded-lg border border-amber-300 bg-amber-50 p-4 text-sm text-amber-800">
            Please login or create an account to use Pothik's features.
        </div>
    <?php endif; ?>

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
<?php
/**
 * MORTYX — Registration Page
 * POST-based registration with CSRF protection, auto-login on success.
 */

require_once __DIR__ . '/../includes/Auth.php';

Auth::init();

// Already logged in? Go to account.
if (Auth::check()) {
    header('Location: ../customer/account.php');
    exit;
}

$error = null;
$old = ['name' => '', 'email' => '', 'phone' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name']  = $_POST['name'] ?? '';
    $old['email'] = $_POST['email'] ?? '';
    $old['phone'] = $_POST['phone'] ?? '';

    if (!Auth::validateCsrf($_POST['_csrf'] ?? null)) {
        $error = 'Session expired. Please try again.';
    } else {
        $result = Auth::register(
            $_POST['name'] ?? '',
            $_POST['email'] ?? '',
            $_POST['password'] ?? '',
            $_POST['phone'] ?? ''
        );

        if ($result['success']) {
            $redirect = $_SESSION['return_url'] ?? '../customer/account.php';
            unset($_SESSION['return_url']);
            header('Location: ' . $redirect);
            exit;
        }

        $error = $result['error'];
    }
}

$csrf = Auth::csrfToken();
$page_title = 'Create Account';
?>
<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> | MORTYX APPAREL</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: { 950:'#080809', 900:'#0e0e11', 800:'#16161b', 700:'#22222a', 600:'#32323c' },
                        brand: { accent:'#ffffff', muted:'#94a3b8', neon:'#e2fd52' }
                    },
                    fontFamily: {
                        heading: ['Syne','sans-serif'],
                        sans: ['Plus Jakarta Sans','sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color:#080809; color:#f1f5f9; font-family:'Plus Jakarta Sans',sans-serif; }
        .font-heading { font-family:'Syne',sans-serif; letter-spacing:-0.04em; }
    </style>
</head>
<body class="bg-[#080809] text-zinc-100 min-h-screen flex flex-col antialiased">

    <!-- Minimal top bar -->
    <div class="border-b border-zinc-800/60 py-6 px-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="../index.php" class="font-heading font-black text-2xl tracking-tighter text-white hover:tracking-normal transition-all duration-300">
                MORTYX<span class="text-zinc-500 text-lg">®</span>
            </a>
            <a href="login.php" class="text-xs uppercase tracking-wider font-semibold text-zinc-400 hover:text-white transition-colors">
                ← Sign In
            </a>
        </div>
    </div>

    <!-- Registration Form -->
    <main class="flex-1 flex items-center justify-center px-4 py-16">
        <div class="w-full max-w-sm space-y-8">

            <!-- Header -->
            <div class="space-y-2">
                <h1 class="font-heading font-black text-3xl tracking-tight text-white uppercase">Create Account</h1>
                <p class="text-xs text-zinc-400 tracking-wide">Join MORTYX to get exclusive access to drops and track your orders.</p>
            </div>

            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="border border-red-900/50 bg-red-950/30 px-4 py-3 text-xs text-red-400 tracking-wide">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="" class="space-y-5">
                <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($csrf); ?>">

                <div class="space-y-1.5">
                    <label for="name" class="block text-[11px] uppercase tracking-widest font-semibold text-zinc-500">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                        minlength="2"
                        autocomplete="name"
                        value="<?php echo htmlspecialchars($old['name']); ?>"
                        placeholder="Your full name"
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-sm px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-zinc-500 transition-colors">
                </div>

                <div class="space-y-1.5">
                    <label for="email" class="block text-[11px] uppercase tracking-widest font-semibold text-zinc-500">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        autocomplete="email"
                        value="<?php echo htmlspecialchars($old['email']); ?>"
                        placeholder="you@example.com"
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-sm px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-zinc-500 transition-colors">
                </div>

                <div class="space-y-1.5">
                    <label for="password" class="block text-[11px] uppercase tracking-widest font-semibold text-zinc-500">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        placeholder="Min. 8 characters"
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-sm px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-zinc-500 transition-colors">
                </div>

                <div class="space-y-1.5">
                    <label for="phone" class="block text-[11px] uppercase tracking-widest font-semibold text-zinc-500">Phone <span class="text-zinc-600 normal-case">(optional)</span></label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        autocomplete="tel"
                        value="<?php echo htmlspecialchars($old['phone']); ?>"
                        placeholder="08xxxxxxxxxx"
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-sm px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-zinc-500 transition-colors">
                </div>

                <button
                    type="submit"
                    class="w-full py-3.5 bg-white text-black font-heading font-black text-xs uppercase tracking-widest hover:bg-zinc-200 transition-colors">
                    Create Account
                </button>
            </form>

            <!-- Alternate Action -->
            <div class="text-center pt-2 border-t border-zinc-900">
                <p class="text-xs text-zinc-500 pt-4">
                    Already have an account?
                    <a href="login.php" class="text-white font-semibold hover:underline underline-offset-4">Sign in</a>
                </p>
            </div>
        </div>
    </main>

    <!-- Minimal Footer -->
    <div class="border-t border-zinc-900 py-6 px-4 text-center">
        <p class="text-[11px] text-zinc-600 uppercase tracking-widest">© <?php echo date('Y'); ?> MORTYX APPAREL CO.</p>
    </div>

</body>
</html>


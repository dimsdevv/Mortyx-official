<?php
/**
 * MORTYX — Login Page
 * Clean Minimalist UI (CALLE Style)
 */

require_once __DIR__ . '/../includes/Auth.php';

Auth::init();

// Already logged in? Go to account.
if (Auth::check()) {
    header('Location: ../customer/account.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Auth::validateCsrf($_POST['_csrf'] ?? null)) {
        $error = 'Session expired. Please try again.';
    } else {
        $result = Auth::login(
            $_POST['email'] ?? '',
            $_POST['password'] ?? ''
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
$page_title = 'Sign In';
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> | MORTYX APPAREL</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        heading: ['Syne','sans-serif'],
                        sans: ['Plus Jakarta Sans','sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #ffffff; color: #111827; font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Syne', sans-serif; letter-spacing: -0.04em; }
    </style>
</head>
<body class="bg-white text-zinc-900 min-h-screen flex flex-col antialiased">

    <!-- Minimal top bar -->
    <div class="border-b border-zinc-200 py-5 px-6 bg-white">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="../index.php" class="font-heading font-black text-2xl tracking-tighter text-black uppercase hover:opacity-80 transition-opacity">
                MORTYX
            </a>
            <a href="register.php" class="text-xs uppercase tracking-wider font-bold text-zinc-600 hover:text-black transition-colors">
                Create Account →
            </a>
        </div>
    </div>

    <!-- Login Form -->
    <main class="flex-1 flex items-center justify-center px-4 py-16 bg-zinc-50/50">
        <div class="w-full max-w-sm space-y-8 bg-white border border-zinc-200 p-8 rounded shadow-sm">

            <!-- Header -->
            <div class="space-y-1.5 text-center">
                <span class="text-[11px] font-mono text-zinc-400 uppercase tracking-widest">CUSTOMER PORTAL</span>
                <h1 class="font-heading font-black text-2xl tracking-tight text-black uppercase">Sign In</h1>
                <p class="text-xs text-zinc-500">Access your MORTYX account to view orders.</p>
            </div>

            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-600 rounded">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="" class="space-y-4">
                <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($csrf); ?>">

                <div class="space-y-1">
                    <label for="email" class="block text-[11px] uppercase tracking-wider font-bold text-zinc-700">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        autocomplete="email"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                        placeholder="your.email@domain.com"
                        class="w-full bg-white border border-zinc-300 rounded px-3.5 py-2.5 text-xs text-black placeholder-zinc-400 focus:outline-none focus:border-black transition-colors">
                </div>

                <div class="space-y-1">
                    <label for="password" class="block text-[11px] uppercase tracking-wider font-bold text-zinc-700">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full bg-white border border-zinc-300 rounded px-3.5 py-2.5 text-xs text-black placeholder-zinc-400 focus:outline-none focus:border-black transition-colors">
                </div>

                <button
                    type="submit"
                    class="w-full py-3 bg-black hover:bg-zinc-800 text-white font-heading font-black text-xs uppercase tracking-widest transition-colors rounded">
                    Sign In
                </button>
            </form>

            <!-- Alternate Action -->
            <div class="text-center pt-3 border-t border-zinc-100">
                <p class="text-xs text-zinc-500">
                    Don't have an account?
                    <a href="register.php" class="text-black font-bold hover:underline underline-offset-4">Create one</a>
                </p>
            </div>
        </div>
    </main>

    <!-- Minimal Footer -->
    <div class="border-t border-zinc-200 py-6 px-4 text-center bg-white">
        <p class="text-[11px] text-zinc-400 uppercase tracking-widest">© <?php echo date('Y'); ?> MORTYX APPAREL CO.</p>
    </div>

</body>
</html>

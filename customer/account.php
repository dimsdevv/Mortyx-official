<?php
/**
 * MORTYX — Customer Account Portal
 * Protected route: requires authentication.
 * Shows profile info and order history placeholder.
 */

require_once __DIR__ . '/../includes/Auth.php';

Auth::init();
Auth::requireAuth('../auth/login.php');

$user = Auth::user();
$page_title = 'My Account';
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
            <div class="flex items-center space-x-6">
                <a href="../index.php" class="text-xs uppercase tracking-wider font-semibold text-zinc-400 hover:text-white transition-colors">Catalog</a>
                <a href="../auth/logout.php" class="text-xs uppercase tracking-wider font-semibold text-zinc-400 hover:text-red-400 transition-colors">Sign Out</a>
            </div>
        </div>
    </div>

    <!-- Account Content -->
    <main class="flex-1 max-w-4xl mx-auto px-4 py-16 w-full">

        <!-- Welcome Header -->
        <div class="mb-12 space-y-2">
            <span class="text-xs font-mono text-zinc-500 uppercase tracking-widest">MEMBER PORTAL</span>
            <h1 class="font-heading font-black text-3xl sm:text-4xl tracking-tight text-white uppercase">
                <?php echo htmlspecialchars($user['name']); ?>
            </h1>
        </div>

        <!-- Profile + Orders Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Profile Card -->
            <div class="lg:col-span-1 bg-zinc-950 border border-zinc-800 p-6 space-y-6">
                <div class="border-b border-zinc-800 pb-3">
                    <span class="text-[11px] font-mono text-zinc-400 uppercase tracking-widest">Profile</span>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <span class="block text-zinc-500 uppercase tracking-wider mb-1">Name</span>
                        <span class="text-white font-medium"><?php echo htmlspecialchars($user['name']); ?></span>
                    </div>
                    <div>
                        <span class="block text-zinc-500 uppercase tracking-wider mb-1">Email</span>
                        <span class="text-white font-medium"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div>
                        <span class="block text-zinc-500 uppercase tracking-wider mb-1">Phone</span>
                        <span class="text-white font-medium"><?php echo htmlspecialchars($user['phone'] ?: '—'); ?></span>
                    </div>
                    <div>
                        <span class="block text-zinc-500 uppercase tracking-wider mb-1">Role</span>
                        <span class="inline-block bg-zinc-900 border border-zinc-800 px-2 py-0.5 text-[10px] font-mono text-zinc-300 uppercase tracking-wider"><?php echo htmlspecialchars($user['role']); ?></span>
                    </div>
                </div>

                <a href="../auth/logout.php" class="block w-full py-2.5 border border-zinc-800 text-center text-xs uppercase tracking-wider font-semibold text-zinc-400 hover:text-red-400 hover:border-red-900/50 transition-colors">
                    Sign Out
                </a>
            </div>

            <!-- Order History -->
            <div class="lg:col-span-2 bg-zinc-950 border border-zinc-800 p-6 space-y-6">
                <div class="border-b border-zinc-800 pb-3 flex items-center justify-between">
                    <span class="text-[11px] font-mono text-zinc-400 uppercase tracking-widest">Order History</span>
                    <span class="text-[10px] bg-zinc-900 border border-zinc-800 px-2 py-0.5 text-zinc-500 font-mono">0 ORDERS</span>
                </div>

                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16 space-y-4 text-center">
                    <svg class="w-12 h-12 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-xs text-zinc-500">No orders yet.</p>
                    <a href="../index.php#catalog" class="text-xs uppercase tracking-wider font-bold text-white hover:underline underline-offset-4">
                        Browse the catalog →
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- Minimal Footer -->
    <div class="border-t border-zinc-900 py-6 px-4 text-center mt-auto">
        <p class="text-[11px] text-zinc-600 uppercase tracking-widest">© <?php echo date('Y'); ?> MORTYX APPAREL CO.</p>
    </div>

</body>
</html>


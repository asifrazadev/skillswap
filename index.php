<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill-Swap | The Ultimate Barter Network</title>
    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Tailwind v4 via Browser -->
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

        body {
            font-family: var(--font-body);
            background-color: var(--color-bg-main);
            color: white;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
        }

        /* Custom utilities */
        .glassmorphism {
            background-color: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .fade-in-up { opacity: 0; transform: translateY(30px); transition: all 0.6s cubic-bezier(0.22, 1, 0.36, 1); }
        .fade-in-up.visible { opacity: 1; transform: translateY(0); }
        .reveal { opacity: 0; transform: translateY(50px); transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="text-gray-100 antialiased leading-relaxed">

    <!-- Header Navigation -->
    <header class="navbar fixed top-0 w-full z-50 flex justify-between items-center px-6 py-5 md:px-12 bg-[#0B0A10]/80 backdrop-blur-md border-b border-white/10 transition-all duration-200">
        <div class="logo font-heading font-extrabold text-2xl tracking-tight">
            <span class="gradient-text">SkillSwap</span>
        </div>
        
        <nav class="nav-links hidden md:flex gap-10 font-medium pb-0">
            <a href="#how-it-works" class="hover:text-pink-500 transition-colors">How It Works</a>
            <a href="#explore" class="hover:text-pink-500 transition-colors">Explore Swaps</a>
            <a href="#about" class="hover:text-pink-500 transition-colors">About</a>   
        </nav>
        
        <div class="nav-actions hidden md:flex gap-4">
            <button onclick="window.location.href='auth/login.php'" class="px-6 py-2 rounded-full border border-white/10 hover:bg-white/5 transition-all font-heading font-semibold">Log In</button>
            <button onclick="window.location.href='auth/register.php'" class="px-6 py-2 rounded-full bg-gradient-to-br from-purple-600 to-pink-500 hover:-translate-y-0.5 hover:shadow-[0_4px_20px_rgba(124,58,237,0.4)] transition-all font-heading font-semibold text-white">Join Network</button>
        </div>
        
        <!-- Mobile Menu Toggle -->
        <div class="mobile-menu-toggle flex md:hidden flex-col gap-[5px] cursor-pointer z-50">
            <span class="w-6 h-[2px] bg-white transition-all duration-300"></span>
            <span class="w-6 h-[2px] bg-white transition-all duration-300"></span>
            <span class="w-6 h-[2px] bg-white transition-all duration-300"></span>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero relative min-h-screen flex items-center justify-center text-center bg-cover bg-center bg-no-repeat" style="background-image: url('assets/img/hero_bg.jpg');">
        <div class="absolute inset-0 bg-gradient-to-b from-[#0B0A10]/30 to-[#0B0A10] z-0"></div>
        
        <div class="hero-content relative z-10 max-w-4xl px-6 pt-24 pb-12 mx-auto">
            
            <h1 class="font-extrabold text-5xl md:text-7xl mb-6 leading-tight fade-in-up" style="transition-delay: 100ms;">Skill-Swap & <br><span class="gradient-text">Barter Network.</span></h1>
            
            <p class="text-lg md:text-xl text-gray-400 mb-10 max-w-2xl mx-auto fade-in-up" style="transition-delay: 200ms;">No money — pure barter. Offer a skill you have ("I can teach Arabic"), and request one you want ("I want Photoshop lessons"). The platform automatically finds your perfect match.</p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center fade-in-up" style="transition-delay: 300ms;">
                <button class="px-8 py-4 rounded-full text-lg font-heading font-semibold bg-gradient-to-br from-purple-600 to-pink-500 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(124,58,237,0.4)] transition-all">Start Swapping</button>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-24 px-6 relative">
        <div class="max-w-7xl mx-auto text-center mb-16 reveal">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4">Key Features</h2>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">Built on a bidirectional SQL match engine that redefines community skill exchange.</p>
        </div>
        
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="p-8 bg-[#13121A] rounded-3xl border border-white/5 hover:-translate-y-2 hover:shadow-[0_15px_30px_rgba(0,0,0,0.4)] transition-all duration-500 reveal">
                <div class="text-4xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold mb-3">Bidirectional Matching</h3>
                <p class="text-gray-400 leading-relaxed">The engine queries where A's offer equals B's request, AND B's offer equals A's request.</p>
            </div>
            
            <div class="p-8 bg-[#13121A] rounded-3xl border border-white/5 hover:-translate-y-2 hover:shadow-[0_15px_30px_rgba(0,0,0,0.4)] transition-all duration-500 reveal" style="transition-delay: 100ms;">
                <div class="text-4xl mb-4">🤝</div>
                <h3 class="text-2xl font-bold mb-3">Offer + Request Profiles</h3>
                <p class="text-gray-400 leading-relaxed">Every profile explicitly outlines what you can give and what you want to receive.</p>
            </div>
            
            <div class="p-8 bg-[#13121A] rounded-3xl border border-white/5 hover:-translate-y-2 hover:shadow-[0_15px_30px_rgba(0,0,0,0.4)] transition-all duration-500 reveal" style="transition-delay: 200ms;">
                <div class="text-4xl mb-4">💬</div>
                <h3 class="text-2xl font-bold mb-3">Private Thread Negotiation</h3>
                <p class="text-gray-400 leading-relaxed">Once a swap request is accepted, a secure message thread drops you right into planning.</p>
            </div>
            
            <div class="p-8 bg-[#13121A] rounded-3xl border border-white/5 hover:-translate-y-2 hover:shadow-[0_15px_30px_rgba(0,0,0,0.4)] transition-all duration-500 reveal" style="transition-delay: 300ms;">
                <div class="text-4xl mb-4">⭐</div>
                <h3 class="text-2xl font-bold mb-3">Trust & Review System</h3>
                <p class="text-gray-400 leading-relaxed">Post-swap reviews and star ratings build a verified, trustworthy barter ecosystem.</p>
            </div>
        </div>
    </section>

    <!-- Explore Matches Demo Section -->
    <section id="explore" class="py-24 px-6 relative bg-[#13121A]">
        <div class="max-w-7xl mx-auto text-center mb-16 reveal">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4">The Community</h2>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">See who's swapping right now. (Our humans are very talented).</p>
        </div>
        
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Match Card 1 -->
            <div class="glassmorphism rounded-3xl overflow-hidden  group reveal hover:shadow-[0_10px_40px_rgba(124,58,237,0.2)] transition-shadow duration-500">
                <div class="relative aspect-auto h-64 overflow-hidden">
                    <img src="assets/img/human_guitar.png" alt="Human teaching guitar" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                    <div class="absolute top-4 right-4 bg-black/60 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Trending</div>
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-bold mb-4 font-heading">Prof. Michael</h3>
                    <div class="flex flex-col gap-2">
                        <span class="inline-block px-4 py-2 rounded-xl text-sm font-medium bg-purple-600/10 text-purple-300 border border-purple-500/30">Offers: Guitar Classes</span>
                        <span class="inline-block px-4 py-2 rounded-xl text-sm font-medium bg-pink-500/10 text-pink-400 border border-pink-500/30">Wants: Web Dev</span>
                    </div>
                </div>
            </div>

            <!-- Match Card 2 -->
            <div class="glassmorphism rounded-3xl overflow-hidden group reveal hover:shadow-[0_10px_40px_rgba(236,72,153,0.2)] transition-shadow duration-500" style="transition-delay: 200ms;">
                <div class="relative aspect-auto h-64 overflow-hidden">
                    <img src="assets/img/human_coding.png" alt="Human coding" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                    <div class="absolute top-4 right-4 bg-purple-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">New</div>
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-bold mb-4 font-heading">Hacker Dave</h3>
                    <div class="flex flex-col gap-2">
                        <span class="inline-block px-4 py-2 rounded-xl text-sm font-medium bg-purple-600/10 text-purple-300 border border-purple-500/30">Offers: JavaScript/PHP</span>
                        <span class="inline-block px-4 py-2 rounded-xl text-sm font-medium bg-pink-500/10 text-pink-400 border border-pink-500/30">Wants: Arabic Lessons</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 px-6 relative">
        <div class="max-w-7xl mx-auto text-center mb-16 reveal">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-6">About The Project</h2>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">More than just a standard web app — an original concept built with a tangible social impact.</p>
        </div>
        
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <!-- Left side text -->
            <div class="reveal glassmorphism p-8 md:p-12 rounded-3xl border border-white/10 hover:shadow-[0_15px_40px_rgba(124,58,237,0.15)] transition-shadow duration-500">
                <h3 class="text-3xl font-heading font-bold mb-6 gradient-text">The Mission</h3>
                <p class="text-gray-300 leading-relaxed mb-6 text-lg">
                    SkillSwap was built to democratize learning by removing the financial friction of education. We believe everyone has a valuable skill to share, and something new they want to learn in return.
                </p>
                <p class="text-gray-300 leading-relaxed text-lg pb-6 ">
                    By leveraging a pure barter system, our platform allows local and global communities to uplift each other organically without ever having to touch their wallets.
                </p>
            </div>

            <!-- Right side tech details -->
            <div class="reveal flex flex-col gap-6" style="transition-delay: 200ms;">
                <div class="glassmorphism p-8 rounded-3xl border border-white/10 relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-purple-600/20 rounded-full blur-3xl group-hover:bg-purple-600/40 transition-colors"></div>
                    <h4 class="text-xl font-bold mb-3 text-white">The Tech Stack</h4>
                    <p class="text-gray-400 mb-6 text-sm">A lightweight, highly performant foundation.</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-sm font-medium text-gray-200">PHP 8</span>
                        <span class="px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-sm font-medium text-gray-200">MySQL</span>
                        <span class="px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-sm font-medium text-gray-200">Vanilla JS</span>
                        <span class="px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-sm font-medium text-gray-200">Tailwind CSS v4</span>
                    </div>
                </div>
                
                <div class="glassmorphism p-8 rounded-3xl border border-pink-500/30 bg-pink-500/5 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                    <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-pink-500/20 rounded-full blur-3xl group-hover:bg-pink-500/40 transition-colors"></div>
                    <h4 class="text-xl font-bold mb-3 text-pink-400">Bidirectional SQL Engine</h4>
                    <p class="text-gray-300 leading-relaxed text-sm">
                        What sets this portfolio piece apart from standard tutorials is the complex query logic required. The engine constantly runs bidirectional queries (<code class="bg-black/50 px-2 py-0.5 rounded text-pink-300 font-mono text-xs">A's offer = B's request</code> AND <code class="bg-black/50 px-2 py-0.5 rounded text-pink-300 font-mono text-xs">B's offer = A's request</code>) to discover perfect barter arrangements in real-time.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-32 px-6 text-center reveal">
        <div class="max-w-4xl mx-auto p-12 md:p-20 rounded-3xl bg-gradient-to-br from-purple-600/10 to-pink-500/10 border border-white/10 backdrop-blur-md">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-6">Ready to trade your talents?</h2>
            <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">Join thousands of users trading skills without touching their wallets.</p>
            <button class="px-10 py-5 rounded-full text-lg font-heading font-semibold bg-gradient-to-br from-purple-600 to-pink-500 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(124,58,237,0.4)] transition-all text-white">Create Free Account</button>
        </div>
    </section>

    <footer class="py-10 text-center border-t border-white/10 mt-10">
        <div class="container mx-auto px-6">
            <p class="text-gray-500 text-sm">&copy; 2026 SkillSwap Barter Network. A portfolio project.</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>

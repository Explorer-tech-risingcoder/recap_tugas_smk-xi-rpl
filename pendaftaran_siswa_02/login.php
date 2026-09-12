<?php
session_start();
include 'koneksi.php';

// Jika sudah login, langsung lempar ke dashboard (index.php)
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$toast_msg = "";
$toast_type = "success";
$show_register = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // === REGISTER ===
    if ($_POST['action'] == 'register') {
        $show_register = true; 
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
        $user = mysqli_real_escape_string($koneksi, $_POST['username_reg']);
        $pass = $_POST['password_reg'];

        $cek_user = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE username='$user'");
        if (mysqli_num_rows($cek_user) > 0) {
            $toast_msg = "Username sudah dipakai! Pilih yang lain.";
            $toast_type = "error";
        } else {
            $insert = mysqli_query($koneksi, "INSERT INTO tb_user (nama_lengkap, username, password) VALUES ('$nama', '$user', '$pass')");
            
            if ($insert) {
                $toast_msg = "Akun berhasil dibuat! Silakan Sign In.";
                $toast_type = "success";
                $show_register = false; 
            } else {
                $toast_msg = "Error database: " . mysqli_error($koneksi);
                $toast_type = "error";
            }
        }
    } 
    // === LOGIN ===
    elseif ($_POST['action'] == 'login') {
        $user = mysqli_real_escape_string($koneksi, $_POST['username_login']);
        $pass = $_POST['password_login'];

        $cek = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE username='$user'");
        if (mysqli_num_rows($cek) > 0) {
            $data = mysqli_fetch_assoc($cek);
            
            if ($pass == $data['password']) {
                $_SESSION['username'] = $data['username'];
                $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
                
                header("Location: index.php");
                exit;
            } else {
                $toast_msg = "Password salah!";
                $toast_type = "error";
            }
        } else {
            $toast_msg = "Username tidak ditemukan!";
            $toast_type = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sistem Pendaftaran - Login</title>
    
    <!-- Panggil Tailwind & Google Fonts/Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Konfigurasi bawaan Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], },
                    colors: { androidDark: '#0f172a', androidLight: '#ffffff', primaryAccent: '#a78bfa', },
                    animation: { 'spring-bounce': 'springBounce 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards', },
                    keyframes: { springBounce: { '0%': { transform: 'scale(0.95)', opacity: '0' }, '100%': { transform: 'scale(1)', opacity: '1' }, } }
                }
            }
        }
    </script>

    <!-- Panggil File CSS Terpisah -->
    <link rel="stylesheet" href="css/login-register.css">
</head>
<body class="auth-body min-h-screen flex flex-col antialiased selection:bg-primaryAccent selection:text-white">

    <main class="flex-1 flex items-center justify-center p-6 w-full max-w-md mx-auto relative z-10">
        <div class="view-container <?= $show_register ? 'show-register' : '' ?>" id="app-container">
            
            <!-- FORM LOGIN -->
            <div id="login-view" class="auth-view glass-panel p-8 shadow-m3-elevation">
                <div class="mb-10 text-center animate-spring-bounce">
                    <div class="w-16 h-16 bg-white rounded-2xl mx-auto mb-4 flex items-center justify-center shadow-lg transform rotate-3">
                        <span class="material-symbols-outlined text-androidDark text-3xl">lock</span>
                    </div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Welcome back</h1>
                    <p class="text-slate-400 mt-2 text-sm">Sign in to continue</p>
                </div>

                <form method="POST" action="">
                    <input type="hidden" name="action" value="login">
                    
                    <div class="m3-input-group">
                        <input type="text" name="username_login" id="login-username" class="m3-input" placeholder=" " required autocomplete="username">
                        <label for="login-username" class="m3-label">Username</label>
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">person</span>
                    </div>

                    <div class="m3-input-group mb-8">
                        <input type="password" name="password_login" id="login-password" class="m3-input" placeholder=" " required autocomplete="current-password">
                        <label for="login-password" class="m3-label">Password</label>
                        <button type="button" onclick="togglePassword('login-password', 'login-pwd-icon')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors focus:outline-none">
                            <span id="login-pwd-icon" class="material-symbols-outlined">visibility_off</span>
                        </button>
                    </div>

                    <button type="submit" class="m3-button w-full py-4 text-lg shadow-lg relative">Sign In</button>
                </form>

                <div class="mt-8 text-center toggle-text text-sm">
                    Belum punya akun? <span onclick="toggleView('register')" role="button">Register</span>
                </div>
            </div>

            <!-- FORM REGISTER -->
            <div id="register-view" class="auth-view glass-panel p-8 shadow-m3-elevation">
                <div class="mb-8 text-center">
                    <div class="w-16 h-16 bg-primaryAccent rounded-2xl mx-auto mb-4 flex items-center justify-center shadow-lg transform -rotate-3">
                        <span class="material-symbols-outlined text-white text-3xl">person_add</span>
                    </div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Create Account</h1>
                    <p class="text-slate-400 mt-2 text-sm">Join us today</p>
                </div>

                <form method="POST" action="">
                    <input type="hidden" name="action" value="register">
                    
                    <div class="m3-input-group">
                        <input type="text" name="nama_lengkap" id="reg-fullname" class="m3-input" placeholder=" " required autocomplete="name">
                        <label for="reg-fullname" class="m3-label">Full Name</label>
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">badge</span>
                    </div>

                    <div class="m3-input-group">
                        <input type="text" name="username_reg" id="reg-username" class="m3-input pr-12" placeholder=" " required autocomplete="username">
                        <label for="reg-username" class="m3-label">Username</label>
                        <button type="button" onclick="generateAutoUsername()" class="absolute right-4 top-1/2 -translate-y-1/2 text-primaryAccent hover:text-white transition-colors focus:outline-none" title="Generate Username">
                            <span id="ai-user-btn" class="material-symbols-outlined">auto_awesome</span>
                        </button>
                    </div>

                    <div class="m3-input-group mb-8">
                        <input type="password" name="password_reg" id="reg-password" class="m3-input" placeholder=" " required autocomplete="new-password">
                        <label for="reg-password" class="m3-label">Password</label>
                        <button type="button" onclick="togglePassword('reg-password', 'reg-pwd-icon')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors focus:outline-none">
                            <span id="reg-pwd-icon" class="material-symbols-outlined">visibility_off</span>
                        </button>
                    </div>

                    <button type="submit" class="m3-button w-full py-4 text-lg shadow-lg relative bg-primaryAccent text-white hover:bg-white hover:text-androidDark">Sign Up</button>
                </form>

                <div class="mt-8 text-center toggle-text text-sm">
                    Sudah punya akun? <span onclick="toggleView('login')" role="button">Sign In</span>
                </div>
            </div>

        </div>
    </main>

    <!-- Elemen Toast Popup -->
    <div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-white text-androidDark px-6 py-3 rounded-full shadow-2xl transform translate-y-24 opacity-0 transition-all duration-300 z-50 flex items-center gap-3 font-medium text-sm">
        <span id="toast-icon" class="material-symbols-outlined text-green-500">check_circle</span>
        <span id="toast-message">Success!</span>
    </div>

    <!-- Panggil File JS Terpisah -->
    <script src="js/login-register.js"></script>

    <!-- Trigger Toast dari PHP jika ada pesan (Murni jembatan PHP ke JS) -->
    <?php if($toast_msg != ""): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                setTimeout(function() {
                    showToast("<?= $toast_msg ?>", "<?= $toast_type ?>");
                }, 500);
            });
        </script>
    <?php endif; ?>
</body>
</html>

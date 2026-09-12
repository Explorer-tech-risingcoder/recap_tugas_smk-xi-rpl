/* js/login-register.js */

// 1. Efek Flip 3D antara Login dan Register
function toggleView(view) {
    const container = document.getElementById('app-container');
    if (view === 'register') container.classList.add('show-register');
    else container.classList.remove('show-register');
}

// 2. Menampilkan / Menyembunyikan Password
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') { 
        input.type = 'text'; 
        icon.textContent = 'visibility'; 
        icon.classList.replace('text-slate-400', 'text-primaryAccent'); 
    } else { 
        input.type = 'password'; 
        icon.textContent = 'visibility_off'; 
        icon.classList.replace('text-primaryAccent', 'text-slate-400'); 
    }
}

// 3. Auto Generate Username Backend Style (Lokal)
function generateAutoUsername() {
    const fullName = document.getElementById('reg-fullname').value.trim();
    if (!fullName) { 
        showToast('Tolong isi Full Name terlebih dahulu!', 'error'); 
        return; 
    }
    
    const randomNumbers = Math.floor(10 + Math.random() * 90);
    const firstName = fullName.split(' ')[0].toLowerCase().replace(/[^a-z]/g, '');
    document.getElementById('reg-username').value = `${firstName}${randomNumbers}_pro`;
    document.getElementById('reg-username').focus();
    showToast('Username otomatis berhasil dibuat!');
}

// 4. Logika Toast Notification (Popup Pesan)
let toastTimeout;
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    document.getElementById('toast-message').textContent = message;
    const icon = document.getElementById('toast-icon');
    
    if (type === 'error') { 
        icon.textContent = 'error'; 
        icon.className = 'material-symbols-outlined text-red-500'; 
    } else { 
        icon.textContent = 'check_circle'; 
        icon.className = 'material-symbols-outlined text-green-500'; 
    }

    toast.classList.remove('translate-y-24', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');
    
    clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => { 
        toast.classList.remove('translate-y-0', 'opacity-100'); 
        toast.classList.add('translate-y-24', 'opacity-0'); 
    }, 3000);
}

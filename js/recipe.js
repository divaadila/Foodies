document.getElementById('form').addEventListener('submit', function(event) {
    event.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ username: username, password: password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const loginButton = document.querySelector('.login');
    const popup = document.querySelector('.popup');

    loginButton.addEventListener('click', function(event) {
        event.preventDefault(); // Mencegah link dari tindakan defaultnya
        popup.classList.toggle('hidden');
    });

    popup.addEventListener('click', function(event) {
        if (event.target === popup) {
            popup.classList.add('hidden');
        }
    });

    const overlayBtn = document.getElementById('overlayBtn');
    const container = document.getElementById('container');

    overlayBtn.addEventListener('click', () => {
        container.classList.toggle('right-panel-active');
        overlayBtn.classList.remove('btnScaled');
        window.requestAnimationFrame(() => {
            overlayBtn.classList.add('btnScaled');
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
    var userMenu = document.getElementById('dropdown-icon');
    var dropdown = document.getElementById('dropdown');

    userMenu.addEventListener('click', function() {
        dropdown.classList.toggle('show');
    });

    // Menutup dropdown jika klik dilakukan di luar dropdown
    document.addEventListener('click', function(event) {
        if (!userMenu.contains(event.target) && event.target !== userMenu) {
            dropdown.classList.remove('show');
        }
    });

    // Log out functionality
    var logoutButton = document.getElementById('logout');
    if (logoutButton) {
        logoutButton.addEventListener('click', function(event) {
            event.preventDefault();
            // Lakukan proses logout di sini, misalnya dengan menghapus session dan mengarahkan ke halaman login
            window.location.href = 'logout.php'; // Ganti dengan URL logout yang sesuai
        });
    }
});



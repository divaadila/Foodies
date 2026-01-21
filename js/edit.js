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
});

function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Set default tab
document.querySelector('.tablinks').click();

    // Dropdown menu
    var userMenu = document.getElementById('user-menu');
    var dropdownIcon = document.getElementById('dropdown-icon');
    var dropdown = document.getElementById('dropdown');
    var userIcon = document.getElementById('user-icon');

    dropdownIcon.addEventListener('click', function() {
        dropdown.classList.toggle('hidden');
    });

    // Redirect to profile.php when user icon is clicked
    userIcon.addEventListener('click', function(event) {
        event.stopPropagation();
        window.location.href = 'profile.php';
    });

    // Close dropdown if click is outside of the menu
    document.addEventListener('click', function(event) {
        if (!userMenu.contains(event.target)) {
            dropdown.classList.add('hidden');
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

    document.addEventListener("DOMContentLoaded", function() {
        var tabs = document.querySelectorAll('.tab');
        var containers = document.querySelectorAll('.recipes-container');

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                tabs.forEach(function(item) {
                    item.classList.remove('active');
                });
                containers.forEach(function(container) {
                    container.classList.remove('active');
                });
                tab.classList.add('active');
                document.querySelector(tab.getAttribute('data-target')).classList.add('active');
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const profilePictureUpload = document.getElementById('profile-picture-upload');
        const profileImgPreview = document.getElementById('profile-img-preview');
    
        profileImgPreview.addEventListener('click', function() {
            profilePictureUpload.click();
        });
    
        profilePictureUpload.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImgPreview.innerHTML = `<img src="${e.target.result}" alt="Profile Picture" class="profile-img">`;
                }
                reader.readAsDataURL(file);
            }
        });
    });

    document.getElementById('edit-profile-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Menghentikan pengiriman form langsung

        // Lakukan pengiriman form secara asynchronous menggunakan Fetch API atau XMLHttpRequest
        fetch('process_edit_profile.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Tampilkan alert jika berhasil
                document.getElementById('alert-message').style.display = 'block';

                // Redirect ke halaman profil setelah beberapa detik
                setTimeout(function() {
                    window.location.href = 'profile.php';
                }, 3000); // Redirect setelah 3 detik (3000 milidetik)
            } else {
                // Tampilkan pesan kesalahan jika ada
                alert('Gagal memperbarui profil.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim data.');
        });
    });
    
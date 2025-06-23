<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Xploréa</title>
    <link rel="stylesheet" href="<?= base_url('css/login.css'); ?>">
    <link rel="icon" type="image/png" href="<?= base_url('assets/favicon.png') ?>">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="left-section">
        <p>Xploréa</p>
    </div>

    <div class="right-section" id="rightSection">
        <form action="<?= base_url('auth/login'); ?>" method="POST" class="main">
            <p class="header">Log in</p> <br>

            <!-- Pesan error yang diperbaiki -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="
                    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
                    border: none;
                    border-radius: 12px;
                    color: white;
                    padding: 15px 20px;
                    margin-bottom: 20px;
                    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
                    position: relative;
                    animation: slideInDown 0.5s ease-out;
                ">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong style="font-weight: 600;">Login Failed!</strong>
                            <div style="margin-top: 5px; font-size: 0.9rem; opacity: 0.9;">
                                <?= session()->getFlashdata('error'); ?>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="
                        position: absolute;
                        top: 10px;
                        right: 15px;
                        background: none;
                        border: none;
                        color: white;
                        font-size: 1.2rem;
                        cursor: pointer;
                    " onclick="this.parentElement.style.display='none'; adjustHeight();">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Pesan success (jika ada) -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="
                    background: linear-gradient(135deg, #51cf66, #40c057);
                    border: none;
                    border-radius: 12px;
                    color: white;
                    padding: 15px 20px;
                    margin-bottom: 20px;
                    box-shadow: 0 4px 15px rgba(81, 207, 102, 0.3);
                    position: relative;
                    animation: slideInDown 0.5s ease-out;
                ">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-check-circle-fill" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong style="font-weight: 600;">Success!</strong>
                            <div style="margin-top: 5px; font-size: 0.9rem; opacity: 0.9;">
                                <?= session()->getFlashdata('success'); ?>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="
                        position: absolute;
                        top: 10px;
                        right: 15px;
                        background: none;
                        border: none;
                        color: white;
                        font-size: 1.2rem;
                        cursor: pointer;
                    " onclick="this.parentElement.style.display='none'; adjustHeight();">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            <?php endif; ?>

            <table class="isian">
                <tr>
                    <td><p>Email / Username</p></td>
                </tr>
                <tr>
                    <td><input type="text" name="mail" id="mail" placeholder="Email or Username" required></td>
                </tr>
                <tr>
                    <td><p>Password</p></td>
                </tr>
                <tr>
                    <td><input type="password" name="pass" id="pass" placeholder="Password" required></td>
                </tr>
                <tr>
                    <td><a class="forgot" href="#">Forgot password?</a></td>
                </tr>
                <tr>
                    <td><input id="button" type="submit" value="Log in"></td>
                </tr>
                <tr>
                    <td class="divider"><br><p>Or</p></td>
                </tr>
                <tr>
                    <td id="google">
                        <a href="#"><i class="bi bi-google"></i>Continue with Google</a>
                    </td>
                </tr>
                <tr>
                    <td class="signup">
                        <p>Don't have an account? <a href="<?= base_url('xplorea/signup'); ?>">Sign up</a></p>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <style>
        @keyframes slideInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert {
            transition: all 0.3s ease;
        }

        .alert:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-close:hover {
            opacity: 0.7;
            transform: scale(1.1);
        }

        /* Responsive design untuk alert */
        @media (max-width: 768px) {
            .alert {
                padding: 12px 15px;
                font-size: 0.9rem;
            }
            
            .alert i {
                font-size: 1rem !important;
            }
        }

        /* Pastikan right-section menyesuaikan tinggi */
        .right-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .main {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
    </style>

    <script>
        // Function untuk menyesuaikan tinggi
        function adjustHeight() {
            const rightSection = document.getElementById('rightSection');
            const form = rightSection.querySelector('.main');
            const formHeight = form.offsetHeight;
            const windowHeight = window.innerHeight;
            
            // Jika form lebih tinggi dari viewport, gunakan form height
            // Jika tidak, gunakan viewport height
            if (formHeight > windowHeight) {
                rightSection.style.height = formHeight + 'px';
                rightSection.style.minHeight = 'auto';
            } else {
                rightSection.style.height = '100vh';
                rightSection.style.minHeight = '100vh';
            }
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert.style.display !== 'none') {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-10px)';
                        setTimeout(function() {
                            alert.style.display = 'none';
                            adjustHeight(); // Adjust height after hiding alert
                        }, 300);
                    }
                }, 5000);
            });

            // Initial height adjustment
            adjustHeight();
        });

        // Adjust height on window resize
        window.addEventListener('resize', adjustHeight);
    </script>
</body>

</html>

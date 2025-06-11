<?php include 'auth.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="style.css">
    <title>Ulasan - Senku Coffee</title>
    <style>
    html,
    body {
        height: 100%;
    }

    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    footer {
        margin-top: auto;
    }
    </style>
</head>

<body>
    <div class="header">
        <!-- header -->
        <?php include 'navbar.php'; ?>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-4 mb-2 py-3" style="background: #f8f8f8; border-top: 1px solid #e0e0e0;">
        <small>
            &copy; 2025 Senku Coffee &middot;
            Jl. Kopi No. 123, Jakarta &middot;
            <a href="mailto:info@senkucoffee.com" class="text-decoration-none">info@senkucoffee.com</a>
        </small>
    </footer>
</body>

</html>
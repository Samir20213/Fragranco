<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | FRAGRANCO</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #000000, #1a1a1a, #3d2b1f);
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        .welcome-box {
            text-align: center;
            animation: fadeInUp 2s ease-in-out;
        }

        .welcome-box h1 {
            font-size: 65px;
            color: gold;
            letter-spacing: 4px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .welcome-box p {
            font-size: 22px;
            color: #f5f5f5;
            letter-spacing: 2px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        setTimeout(() => {
            window.location.href = "home.php";
        }, 3000);
    </script>
</head>
<body>

    <div class="welcome-box">
        <h1>FRAGRANCO</h1>
        <p>Luxury in Every Drop</p>
    </div>

</body>
</html>
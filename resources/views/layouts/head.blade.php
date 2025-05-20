<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adits Book</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            position: relative;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 40px;
            background: #dc2239;
            z-index: -1;
        }
        .category-img {
            width: 60px;
            filter: brightness(0) invert(1);
        }
        .category-img-goldfish {
            width: 40px;
            filter: brightness(0) invert(1);
        }
        .swiper-slide img {
            border-radius: 10px;
        }
        .mobile-navbar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            border-top: 1px solid #ddd;
            z-index: 1000;
        }
        .mobile-navbar .nav-item {
            text-align: center;
            flex-grow: 1;
            color: #555;
            text-decoration: none;
            font-size: 14px;
        }
        .mobile-navbar .nav-item i {
            display: block;
            font-size: 18px;
            margin-bottom: 3px;
        }
        .mobile-navbar .nav-item:active,
        .mobile-navbar .nav-item:hover {
            color: #dc2239;
        }
        
        /* Atur ukuran slide untuk mobile */
        @media (max-width: 480px) {
            .swiper-slide {
                width: 80% !important;  /* Lebih kecil dari 100% untuk melihat sebagian slide berikutnya */
            }
        }
        .swiper-container {
    width: 100%;
    padding: 0 10px;
}
        
    </style>
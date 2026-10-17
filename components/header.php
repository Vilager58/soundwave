<header class="site-header">
    <div class="container">
        <div class="header-inner">
            <a href="/" class="brand-logo">
                <div class="brand-icon"><i class="bi bi-soundwave"></i></div>
                <span>SOUND<span>WAVE</span></span>
            </a>

            <div class="catalog-dropdown-wrapper">
                <button class="catalog-btn" type="button">
                    <i class="bi bi-grid-fill"></i>
                    Каталог
                </button>
                <div class="mega-menu">
                    <div class="mega-menu-col">
                        <h4>Гитары</h4>
                        <ul class="mega-menu-list">
                            <li><a href="catalog.php?cat=electric">Электрогитары</a></li>
                            <li><a href="catalog.php?cat=acoustic">Акустические гитары</a></li>
                            <li><a href="catalog.php?cat=bass">Бас-гитары</a></li>
                        </ul>
                    </div>
                    <div class="mega-menu-col">
                        <h4>Клавишные</h4>
                        <ul class="mega-menu-list">
                            <li><a href="catalog.php?cat=synths">Синтезаторы</a></li>
                            <li><a href="catalog.php?cat=pianos">Пианино</a></li>
                        </ul>
                    </div>
                    <div class="mega-menu-col">
                        <h4>Студия</h4>
                        <ul class="mega-menu-list">
                            <li><a href="catalog.php?cat=mics">Микрофоны</a></li>
                            <li><a href="catalog.php?cat=amps">Усилители</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <nav class="main-nav">
                <a href="catalog.php" class="nav-link-custom">Все товары</a>
                <a href="about.html" class="nav-link-custom">О нас</a>
                <a href="contacts.html" class="nav-link-custom">Контакты</a>
            </nav>

            <div class="header-search">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="header-search-input" placeholder="Поиск инструментов, брендов..."
                    autocomplete="off">
                <div id="search-autocomplete-box" class="search-autocomplete"></div>
            </div>

            <div class="header-actions">
                <a href="favorites.html" class="action-btn" title="Избранное">
                    <i class="bi bi-heart"></i>
                    <span class="action-badge fav-count-badge" style="display: none">0</span>
                </a>
                <a href="cart.php" class="action-btn" title="Корзина">
                    <i class="bi bi-bag"></i>
                    <span class="action-badge cart-count-badge" style="display: none">0</span>
                </a>
                <a href=<?= isset($_SESSION['uid']) ? "account.php" : "login.php"; ?> class="action-btn" title="Личный кабинет">
                    <i class="bi bi-person"></i>
                </a>
                <button class="mobile-menu-btn" id="mobile-menu-toggle" aria-label="Открыть меню">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<div class="drawer-overlay" id="drawer-overlay"></div>
<aside class="mobile-nav-drawer" id="mobile-nav-drawer">
    <div class="mobile-drawer-header">
        <span class="brand-logo" style="font-size: 20px;">
            <span style="color: var(--yellow-main);"><i class="bi bi-soundwave"></i></span>
            SOUNDWAVE
        </span>
        <button class="btn-icon-sq" id="mobile-drawer-close"><i class="bi bi-x-lg"></i></button>
    </div>
    <ul class="mobile-nav-links">
        <li><a href="/">Главная</a></li>
        <li><a href="catalog.php">Каталог товаров</a></li>
        <li><a href="cart.php">Корзина</a></li>
        <li><a href="favorites.html">Избранное</a></li>
        <li><a href="account.php">Личный кабинет</a></li>
        <li><a href="about.html">О нас</a></li>
        <li><a href="contacts.html">Контакты</a></li>
    </ul>
</aside>
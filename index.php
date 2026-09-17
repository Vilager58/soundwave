<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SOUNDWAVE | Интернет-магазин музыкальных инструментов</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/common.css">
  <link rel="stylesheet" href="css/index.css">
</head>

<body>

  <?php require('./components/header.php') ?>

  <main>
    <section class="hero-section">
      <div class="container">
        <div class="hero-content">
          <div class="hero-tag">
            <span class="hero-tag-dot"></span>
            Коллекция 2026 / Pro Audio & Gear
          </div>
          <h1 class="hero-title">Найди свой <span>звук</span></h1>
          <p class="hero-subtitle">
            Профессиональные музыкальные инструменты и студийное оборудование для тех, кто создаёт музыку без
            компромиссов.
          </p>
          <div class="hero-buttons">
            <a href="catalog.php" class="btn-brand">
              Перейти в каталог
              <i class="bi bi-arrow-right"></i>
            </a>
            <a href="#new-arrivals" class="btn-outline-brand">
              Смотреть новинки
            </a>
          </div>
        </div>
      </div>
    </section>

    <section style="padding: 80px 0;">
      <div class="container">
        <div class="section-header">
          <div class="section-subtitle">Категории</div>
          <h2 class="section-title">Выбери инструмент</h2>
          <p class="section-desc">Широкий ассортимент оригинальных инструментов с официальной гарантией</p>
        </div>

        <div class="categories-grid">
          <a href="catalog.php?cat=electric" class="category-card">
            <img src="img/photo-1550291652-6ea9114a47b1.avif" alt="Электрогитары" class="category-bg">
            <div class="category-overlay"></div>
            <div class="category-info">
              <div>
                <h3>Электрогитары</h3>
                <span>142 товара</span>
              </div>
              <div class="category-arrow"><i class="bi bi-arrow-up-right"></i></div>
            </div>
          </a>

          <a href="catalog.php?cat=acoustic" class="category-card">
            <img src="img/photo-1510915361894-db8b60106cb1.avif" alt="Акустические гитары" class="category-bg">
            <div class="category-overlay"></div>
            <div class="category-info">
              <div>
                <h3>Акустические гитары</h3>
                <span>98 товаров</span>
              </div>
              <div class="category-arrow"><i class="bi bi-arrow-up-right"></i></div>
            </div>
          </a>

          <a href="catalog.php?cat=bass" class="category-card">
            <img src="https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=800&q=80"
              alt="Бас-гитары" class="category-bg">
            <div class="category-overlay"></div>
            <div class="category-info">
              <div>
                <h3>Бас-гитары</h3>
                <span>64 товара</span>
              </div>
              <div class="category-arrow"><i class="bi bi-arrow-up-right"></i></div>
            </div>
          </a>

          <a href="catalog.php?cat=synths" class="category-card">
            <img src="img/photo-1598488035138-bdab2431ce03.jpg" alt="Синтезаторы" class="category-bg">
            <div class="category-overlay"></div>
            <div class="category-info">
              <div>
                <h3>Синтезаторы</h3>
                <span>115 товаров</span>
              </div>
              <div class="category-arrow"><i class="bi bi-arrow-up-right"></i></div>
            </div>
          </a>

          <a href="catalog.php?cat=drums" class="category-card">
            <img src="img/photo-1519892300165-cb5542fb47c7.jpg" alt="Ударные" class="category-bg">
            <div class="category-overlay"></div>
            <div class="category-info">
              <div>
                <h3>Ударные</h3>
                <span>83 товара</span>
              </div>
              <div class="category-arrow"><i class="bi bi-arrow-up-right"></i></div>
            </div>
          </a>

          <a href="catalog.php?cat=mics" class="category-card">
            <img src="img/photo-1590602847861-f357a9332bbc.jpg" alt="Микрофоны" class="category-bg">
            <div class="category-overlay"></div>
            <div class="category-info">
              <div>
                <h3>Микрофоны</h3>
                <span>120 товаров</span>
              </div>
              <div class="category-arrow"><i class="bi bi-arrow-up-right"></i></div>
            </div>
          </a>
        </div>
      </div>
    </section>

    <section style="padding: 20px 0 80px;">
      <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
          <div>
            <div class="section-subtitle">Популярное</div>
            <h2 class="section-title mb-0">Хиты продаж</h2>
            <p class="section-desc mb-0">Инструменты, которые выбирают музыканты</p>
          </div>
          <a href="catalog.php" class="btn-wenge">
            Все хиты <i class="bi bi-arrow-right"></i>
          </a>
        </div>

        <div class="row g-4">
          <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="product-card">
              <div class="product-image-box">
                <div class="product-badges">
                  <span class="badge-hit">Хит</span>
                </div>
                <button class="product-fav-btn" data-id="fender-strat" aria-label="В избранное">
                  <i class="bi bi-heart"></i>
                </button>
                <a href="product.php?id=fender-strat">
                  <img src="img/photo-1550291652-6ea9114a47b1.avif" alt="Fender Player Stratocaster">
                </a>
              </div>
              <div class="product-content">
                <div class="product-category-tag">Электрогитары</div>
                <a href="product.php?id=fender-strat" class="product-title">Fender Player Stratocaster</a>
                <p class="product-desc">Классическое стекло Stratocaster с тремя звукоснимателями Alnico 5.</p>
                <div class="product-rating">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <span class="rating-count">4.9 (128)</span>
                </div>
                <div class="product-price-row">
                  <div class="price-box">
                    <span class="old-price">99 990 ₽</span>
                    <span class="current-price">89 990 ₽</span>
                  </div>
                  <button class="btn-add-cart" data-add-cart="fender-strat" title="Добавить в корзину">
                    <i class="bi bi-bag-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="product-card">
              <div class="product-image-box">
                <div class="product-badges">
                  <span class="badge-hit">Хит</span>
                </div>
                <button class="product-fav-btn" data-id="yamaha-p225" aria-label="В избранное">
                  <i class="bi bi-heart"></i>
                </button>
                <a href="product.php?id=yamaha-p225">
                  <img src="img/photo-1525201548942-d8732f6617a0.jpg" alt="Yamaha P-225">
                </a>
              </div>
              <div class="product-content">
                <div class="product-category-tag">Цифровые пианино</div>
                <a href="product.php?id=yamaha-p225" class="product-title">Yamaha P-225 Black</a>
                <p class="product-desc">Молоточковая механика GHC, тембр концертного рояля CFX.</p>
                <div class="product-rating">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <span class="rating-count">4.9 (95)</span>
                </div>
                <div class="product-price-row">
                  <div class="price-box">
                    <span class="current-price">74 990 ₽</span>
                  </div>
                  <button class="btn-add-cart" data-add-cart="yamaha-p225" title="Добавить в корзину">
                    <i class="bi bi-bag-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="product-card">
              <div class="product-image-box">
                <div class="product-badges">
                  <span class="badge-new">NEW</span>
                </div>
                <button class="product-fav-btn" data-id="roland-juno-d" aria-label="В избранное">
                  <i class="bi bi-heart"></i>
                </button>
                <a href="product.php?id=roland-juno-d">
                  <img src="img/photo-1598488035139-bdbb2231ce04.jpg" alt="Roland JUNO-D">
                </a>
              </div>
              <div class="product-content">
                <div class="product-category-tag">Синтезаторы</div>
                <a href="product.php?id=roland-juno-d" class="product-title">Roland JUNO-D6</a>
                <p class="product-desc">Сценический синтезатор со звуками ZEN-Core и микрофонным входом.</p>
                <div class="product-rating">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-half"></i>
                  <span class="rating-count">4.7 (32)</span>
                </div>
                <div class="product-price-row">
                  <div class="price-box">
                    <span class="old-price">119 990 ₽</span>
                    <span class="current-price">109 990 ₽</span>
                  </div>
                  <button class="btn-add-cart" data-add-cart="roland-juno-d" title="Добавить в корзину">
                    <i class="bi bi-bag-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="product-card">
              <div class="product-image-box">
                <div class="product-badges">
                  <span class="badge-hit">Хит</span>
                </div>
                <button class="product-fav-btn" data-id="shure-sm58" aria-label="В избранное">
                  <i class="bi bi-heart"></i>
                </button>
                <a href="product.php?id=shure-sm58">
                  <img src="img/photo-1590602847861-f357a9332bbc.jpg" alt="Shure SM58">
                </a>
              </div>
              <div class="product-content">
                <div class="product-category-tag">Микрофоны</div>
                <a href="product.php?id=shure-sm58" class="product-title">Shure SM58-LCE</a>
                <p class="product-desc">Легендарный кардиоидный динамический вокальный микрофон.</p>
                <div class="product-rating">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <span class="rating-count">5.0 (310)</span>
                </div>
                <div class="product-price-row">
                  <div class="price-box">
                    <span class="old-price">16 290 ₽</span>
                    <span class="current-price">14 490 ₽</span>
                  </div>
                  <button class="btn-add-cart" data-add-cart="shure-sm58" title="Добавить в корзину">
                    <i class="bi bi-bag-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section style="padding: 0 0 80px;">
      <div class="container">
        <div class="promo-section-wrap">
          <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
              <div class="section-subtitle">Ограниченная скидка</div>
              <h2 class="section-title mb-0">Спецпредложения</h2>
            </div>
            <span class="badge-sale" style="font-size: 14px; padding: 6px 14px;">Выгода до 25%</span>
          </div>

          <div class="row g-4">
            <div class="col-lg-3 col-sm-6">
              <div class="product-card">
                <div class="product-image-box">
                  <div class="product-badges">
                    <span class="badge-sale">-10%</span>
                  </div>
                  <button class="product-fav-btn" data-id="gibson-les-paul" aria-label="В избранное">
                    <i class="bi bi-heart"></i>
                  </button>
                  <a href="product.php?id=gibson-les-paul">
                    <img src="img/photo-1516924962500-2b4b3b99ea02.jpg" alt="Gibson Les Paul Studio">
                  </a>
                </div>
                <div class="product-content">
                  <div class="product-category-tag">Электрогитары</div>
                  <a href="product.php?id=gibson-les-paul" class="product-title">Gibson Les Paul Studio</a>
                  <div class="product-price-row">
                    <div class="price-box">
                      <span class="old-price">169 990 ₽</span>
                      <span class="current-price">154 990 ₽</span>
                    </div>
                    <button class="btn-add-cart" data-add-cart="gibson-les-paul" title="В корзину">
                      <i class="bi bi-bag-plus"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-sm-6">
              <div class="product-card">
                <div class="product-image-box">
                  <div class="product-badges">
                    <span class="badge-sale">-15%</span>
                  </div>
                  <button class="product-fav-btn" data-id="marshall-mg30gfx" aria-label="В избранное">
                    <i class="bi bi-heart"></i>
                  </button>
                  <a href="product.php?id=marshall-mg30gfx">
                    <img src="img/photo-1563245372-f21724e3856d.avif" alt="Marshall MG30GFX">
                  </a>
                </div>
                <div class="product-content">
                  <div class="product-category-tag">Усилители</div>
                  <a href="product.php?id=marshall-mg30gfx" class="product-title">Marshall MG30GFX Gold</a>
                  <div class="product-price-row">
                    <div class="price-box">
                      <span class="old-price">34 990 ₽</span>
                      <span class="current-price">29 990 ₽</span>
                    </div>
                    <button class="btn-add-cart" data-add-cart="marshall-mg30gfx" title="В корзину">
                      <i class="bi bi-bag-plus"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-sm-6">
              <div class="product-card">
                <div class="product-image-box">
                  <div class="product-badges">
                    <span class="badge-sale">-11%</span>
                  </div>
                  <button class="product-fav-btn" data-id="yamaha-pacifica" aria-label="В избранное">
                    <i class="bi bi-heart"></i>
                  </button>
                  <a href="product.php?id=yamaha-pacifica">
                    <img src="img/photo-1510915361894-db8b60106cb1.avif" alt="Yamaha Pacifica 112V">
                  </a>
                </div>
                <div class="product-content">
                  <div class="product-category-tag">Электрогитары</div>
                  <a href="product.php?id=yamaha-pacifica" class="product-title">Yamaha Pacifica 112V</a>
                  <div class="product-price-row">
                    <div class="price-box">
                      <span class="old-price">44 990 ₽</span>
                      <span class="current-price">39 990 ₽</span>
                    </div>
                    <button class="btn-add-cart" data-add-cart="yamaha-pacifica" title="В корзину">
                      <i class="bi bi-bag-plus"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-sm-6">
              <div class="product-card">
                <div class="product-image-box">
                  <div class="product-badges">
                    <span class="badge-sale">-10%</span>
                  </div>
                  <button class="product-fav-btn" data-id="fender-strat" aria-label="В избранное">
                    <i class="bi bi-heart"></i>
                  </button>
                  <a href="product.php?id=fender-strat">
                    <img src="img/photo-1550291652-6ea9114a47b1.avif" alt="Fender Player Stratocaster">
                  </a>
                </div>
                <div class="product-content">
                  <div class="product-category-tag">Электрогитары</div>
                  <a href="product.php?id=fender-strat" class="product-title">Fender Stratocaster Sunburst</a>
                  <div class="product-price-row">
                    <div class="price-box">
                      <span class="old-price">99 990 ₽</span>
                      <span class="current-price">89 990 ₽</span>
                    </div>
                    <button class="btn-add-cart" data-add-cart="fender-strat" title="В корзину">
                      <i class="bi bi-bag-plus"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section style="padding: 0 0 80px;">
      <div class="container">
        <div class="advantages-grid">
          <div class="advantage-item">
            <div class="advantage-icon"><i class="bi bi-truck"></i></div>
            <div>
              <h4>Доставка по всей России</h4>
              <p>Бережно доставляем инструменты и оборудование в специальной защитной упаковке.</p>
            </div>
          </div>
          <div class="advantage-item">
            <div class="advantage-icon"><i class="bi bi-shield-check"></i></div>
            <div>
              <h4>Гарантия</h4>
              <p>Официальная гарантия до 3 лет на все товары и собственный сервисный центр.</p>
            </div>
          </div>
          <div class="advantage-item">
            <div class="advantage-icon"><i class="bi bi-headset"></i></div>
            <div>
              <h4>Помощь эксперта</h4>
              <p>Практикующие музыканты и звукорежиссёры подберут оборудование под ваши задачи.</p>
            </div>
          </div>
          <div class="advantage-item">
            <div class="advantage-icon"><i class="bi bi-credit-card"></i></div>
            <div>
              <h4>Безопасная оплата</h4>
              <p>Надёжная онлайн-оплата с фискальным чеком, рассрочка 0% и оплата при получении.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section style="padding: 0 0 80px;">
      <div class="container">
        <div class="consult-banner">
          <div class="consult-text">
            <div class="section-subtitle">Персональный подбор</div>
            <h2>Не знаешь, с чего начать?</h2>
            <p>Расскажите, какую музыку вы играете и какой у вас бюджет — наши эксперты подберут идеальный инструмент и
              всё необходимое для старта.</p>
          </div>
          <a href="contacts.html" class="btn-brand" style="white-space: nowrap;">
            Получить консультацию
            <i class="bi bi-chat-dots-fill"></i>
          </a>
        </div>
      </div>
    </section>

    <section id="new-arrivals" style="padding: 0 0 80px;">
      <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
          <div>
            <div class="section-subtitle">Свежие поступления</div>
            <h2 class="section-title mb-0">Новинки</h2>
          </div>
          <a href="catalog.php?filter=new" class="btn-wenge">Все новинки <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row g-4">
          <div class="col-lg-4 col-sm-6">
            <div class="product-card">
              <div class="product-image-box">
                <div class="product-badges">
                  <span class="badge-new">NEW</span>
                </div>
                <button class="product-fav-btn" data-id="tama-imperialstar" aria-label="В избранное">
                  <i class="bi bi-heart"></i>
                </button>
                <a href="product.php?id=tama-imperialstar">
                  <img src="img/photo-1519892300165-cb5542fb47c7.jpg" alt="TAMA Imperialstar">
                </a>
              </div>
              <div class="product-content">
                <div class="product-category-tag">Ударные</div>
                <a href="product.php?id=tama-imperialstar" class="product-title">TAMA Imperialstar 22</a>
                <p class="product-desc">Полный комплект акустических барабанов со стойками и тарелками Meinl.</p>
                <div class="product-price-row">
                  <div class="price-box">
                    <span class="current-price">94 990 ₽</span>
                  </div>
                  <button class="btn-add-cart" data-add-cart="tama-imperialstar" title="В корзину">
                    <i class="bi bi-bag-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-sm-6">
            <div class="product-card">
              <div class="product-image-box">
                <div class="product-badges">
                  <span class="badge-new">NEW</span>
                </div>
                <button class="product-fav-btn" data-id="roland-juno-d" aria-label="В избранное">
                  <i class="bi bi-heart"></i>
                </button>
                <a href="product.php?id=roland-juno-d">
                  <img src="img/photo-1598488035139-bdbb2231ce04.jpg" alt="Roland JUNO-D6">
                </a>
              </div>
              <div class="product-content">
                <div class="product-category-tag">Клавишные</div>
                <a href="product.php?id=roland-juno-d" class="product-title">Roland JUNO-D6 Synth</a>
                <p class="product-desc">Синтезатор нового поколения со звуковым движком ZEN-Core.</p>
                <div class="product-price-row">
                  <div class="price-box">
                    <span class="old-price">119 990 ₽</span>
                    <span class="current-price">109 990 ₽</span>
                  </div>
                  <button class="btn-add-cart" data-add-cart="roland-juno-d" title="В корзину">
                    <i class="bi bi-bag-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-sm-6">
            <div class="product-card">
              <div class="product-image-box">
                <div class="product-badges">
                  <span class="badge-new">NEW</span>
                </div>
                <button class="product-fav-btn" data-id="shure-sm58" aria-label="В избранное">
                  <i class="bi bi-heart"></i>
                </button>
                <a href="product.php?id=shure-sm58">
                  <img src="img/photo-1590602847861-f357a9332bbc.jpg" alt="Shure SM58">
                </a>
              </div>
              <div class="product-content">
                <div class="product-category-tag">Микрофоны</div>
                <a href="product.php?id=shure-sm58" class="product-title">Shure SM58 50th Edition</a>
                <p class="product-desc">Особая юбилейная серия микрофона в отделке темного венге с тиснением.</p>
                <div class="product-price-row">
                  <div class="price-box">
                    <span class="current-price">18 990 ₽</span>
                  </div>
                  <button class="btn-add-cart" data-add-cart="shure-sm58" title="В корзину">
                    <i class="bi bi-bag-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include('./components/footer_big.php') ?>

  <script src="js/main.js"></script>
</body>

</html>
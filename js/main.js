const SOUNDWAVE_DATA = {
  products: [{
      id: "fender-strat",
      title: "Fender Player Stratocaster",
      brand: "Fender",
      category: "Электрогитары",
      categorySlug: "electric",
      price: 89990,
      oldPrice: 99990,
      badge: "Хит",
      badgeType: "hit",
      rating: 4.9,
      reviews: 128,
      inStock: true,
      sku: "SW-10482",
      popularity: 98,
      dateAdded: "2026-01-15",
      image: "img/photo-1550291652-6ea9114a47b1.avif",
      gallery: [
        "img/photo-1550291652-6ea9114a47b1.avif",
        "img/photo-1510915361894-db8b60106cb1.avif",
        "img/photo-1516924962500-2b4b3b99ea02.jpg"
      ],
      desc: "Классическое стекло Stratocaster с тремя звукоснимателями Alnico 5.",
      fullDesc: "Fender Player Stratocaster — это квинтэссенция аутентичного тона и нестареющего стиля Fender. Инструмент оснащён тремя синглами Player Series Alnico 5, обеспечивающими звонкий кристальный верх, плотные средние частоты и упругий бас. Удобный кленовый гриф профиля Modern C с накладкой радиусом 9.5 дюймов и 22 ладами medium-jumbo дарит непревзойденный комфорт как при исполнении скоростных пассажей, так и при бендах.",
      colors: [{
          name: "3-Color Sunburst",
          dot: "linear-gradient(135deg, #b05a18, #2a1506)"
        },
        {
          name: "Black Gloss",
          dot: "#111111"
        },
        {
          name: "Polar White",
          dot: "#f0f0f0"
        }
      ],
      configs: ["SSS (3 сингла)", "HSS (хамбакер + 2 сингла)"],
      packages: [{
          name: "Базовая",
          extraPrice: 0
        },
        {
          name: "PRO (+ чехол и кабель Klotz)",
          extraPrice: 5990
        }
      ],
      specs: [{
          label: "Форма корпуса",
          value: "Stratocaster"
        },
        {
          label: "Материал корпуса",
          value: "Ольха (Alder)"
        },
        {
          label: "Гриф",
          value: "Клён, профиль Modern C"
        },
        {
          label: "Крепление грифа",
          value: "На болтах (4 болта)"
        },
        {
          label: "Мензура",
          value: '25.5" (648 мм)'
        },
        {
          label: "Количество ладов",
          value: "22 Medium Jumbo"
        },
        {
          label: "Звукосниматели",
          value: "3 × Player Series Alnico 5 Strat Single-Coil"
        },
        {
          label: "Бридж",
          value: "2-точечное синхронизированное тремоло"
        },
        {
          label: "Страна производства",
          value: "Мексика"
        },
        {
          label: "Гарантия",
          value: "2 года"
        }
      ]
    },
    {
      id: "gibson-les-paul",
      title: "Gibson Les Paul Studio",
      brand: "Gibson",
      category: "Электрогитары",
      categorySlug: "electric",
      price: 154990,
      oldPrice: 169990,
      badge: "-10%",
      badgeType: "sale",
      rating: 4.8,
      reviews: 84,
      inStock: true,
      sku: "SW-10512",
      popularity: 94,
      dateAdded: "2026-02-10",
      image: "img/photo-1516924962500-2b4b3b99ea02.jpg",
      gallery: [
        "img/photo-1516924962500-2b4b3b99ea02.jpg",
        "img/photo-1550291652-6ea9114a47b1.avif",
        "img/photo-1525201548942-d8732f6617a0.jpg"
      ],
      desc: "Плотный легендарный сустейн, вклеенный гриф и хамбакеры 490R/498T.",
      fullDesc: "Gibson Les Paul Studio сочетает в себе все главные достоинства культовой гитары Les Paul: массивный корпус из красного дерева с кленовым топом, вклеенный гриф SlimTaper и горячие звукосниматели 490R / 498T с отсечками Push/Pull для универсального звучания от винтажного блюза до современного хард-рока.",
      colors: [{
          name: "Smokehouse Burst",
          dot: "linear-gradient(135deg, #7c4b28, #18120c)"
        },
        {
          name: "Ebony Black",
          dot: "#0f0f0f"
        },
        {
          name: "Wine Red",
          dot: "#5c131d"
        }
      ],
      configs: ["HH (Хамбакеры с Push/Pull)"],
      packages: [{
          name: "Базовая (в комплекте Soft Case)",
          extraPrice: 0
        },
        {
          name: "Deluxe (+ жесткий кейс Gibson USA)",
          extraPrice: 14990
        }
      ],
      specs: [{
          label: "Форма корпуса",
          value: "Singlecut (Les Paul)"
        },
        {
          label: "Материал корпуса",
          value: "Махагони (красное дерево) с топом из клёна"
        },
        {
          label: "Гриф",
          value: "Махагони, профиль SlimTaper"
        },
        {
          label: "Накладка",
          value: "Палисандр (Rosewood)"
        },
        {
          label: "Мензура",
          value: '24.75" (628 мм)'
        },
        {
          label: "Количество ладов",
          value: "22 Medium"
        },
        {
          label: "Звукосниматели",
          value: "490R (нек), 498T (бридж) с отсечками"
        },
        {
          label: "Бридж",
          value: "Aluminum Nashville Tune-O-Matic"
        },
        {
          label: "Страна производства",
          value: "США (Nashville, TN)"
        },
        {
          label: "Гарантия",
          value: "2 года"
        }
      ]
    },
    {
      id: "yamaha-p225",
      title: "Yamaha P-225 Black",
      brand: "Yamaha",
      category: "Клавишные",
      categorySlug: "pianos",
      price: 74990,
      oldPrice: null,
      badge: "Хит",
      badgeType: "hit",
      rating: 4.9,
      reviews: 95,
      inStock: true,
      sku: "SW-20104",
      popularity: 92,
      dateAdded: "2026-02-01",
      image: "img/photo-1590602847861-f357a9332bbc.jpg",
      gallery: [
        "img/photo-1590602847861-f357a9332bbc.jpg",
        "img/photo-1598488035139-bdbb2231ce04.jpg",
        "img/photo-1511671782779-c97d3d27a1d4.avif"
      ],
      desc: "Компактное цифровое пианино с молоточковой клавиатурой GHC.",
      fullDesc: "Yamaha P-225 — элегантное цифровое фортепиано с обновленной клавиатурой Graded Hammer Compact (GHC), максимально точно передающей ощущение игры на акустическом рояле. В сердце инструмента сэмплы флагманского концертного рояля Yamaha CFX с технологией Virtual Resonance Modeling (VRM Lite).",
      colors: [{
          name: "Black Matte",
          dot: "#1c1c1c"
        },
        {
          name: "White Satin",
          dot: "#f5f5f5"
        }
      ],
      configs: ["88 клавиш, молоточковая механика GHC"],
      packages: [{
          name: "Базовая (педаль сустейна + пюпитр)",
          extraPrice: 0
        },
        {
          name: "PRO (+ стойка L-200 и 3-педальный блок LP-1)",
          extraPrice: 12990
        }
      ],
      specs: [{
          label: "Клавиатура",
          value: "88 клавиш GHC с матовыми черными клавишами"
        },
        {
          label: "Тон-генератор",
          value: "Yamaha CFX с технологией VRM Lite"
        },
        {
          label: "Полифония",
          value: "192 голоса"
        },
        {
          label: "Количество тембров",
          value: "24 встроенных тембра"
        },
        {
          label: "Акустическая система",
          value: "2 × 7 Вт, овальные динамики"
        },
        {
          label: "Интерфейсы",
          value: "USB to Host (Audio & MIDI), Bluetooth Audio, 2 × AUX OUT"
        },
        {
          label: "Вес",
          value: "11.5 кг"
        },
        {
          label: "Страна производства",
          value: "Индонезия"
        },
        {
          label: "Гарантия",
          value: "2 года"
        }
      ]
    },
    {
      id: "roland-juno-d",
      title: "Roland JUNO-D6",
      brand: "Roland",
      category: "Клавишные",
      categorySlug: "synths",
      price: 109990,
      oldPrice: 119990,
      badge: "NEW",
      badgeType: "new",
      rating: 4.7,
      reviews: 32,
      inStock: true,
      sku: "SW-20380",
      popularity: 88,
      dateAdded: "2026-03-01",
      image: "img/photo-1598488035139-bdbb2231ce04.jpg",
      gallery: [
        "img/photo-1598488035139-bdbb2231ce04.jpg",
        "img/photo-1590602847861-f357a9332bbc.jpg",
        "img/photo-1598488035138-bdab2431ce03.jpg"
      ],
      desc: "Синтезатор нового поколения со звуковым движком ZEN-Core.",
      fullDesc: "Roland JUNO-D6 открывает новую главу легендарной линейки JUNO. Оснащённый флагманским движком ZEN-Core, он предлагает более 3800 высококлассных звуков — от акустических фортепиано и винтажных синтезаторов Roland до современных электронных текстур. Работает как от сети, так и от аккумулятора USB-C.",
      colors: [{
        name: "Classic Dark",
        dot: "#141414"
      }],
      configs: ["61 динамическая клавиша синтезаторного типа"],
      packages: [{
          name: "Базовая комплектация",
          extraPrice: 0
        },
        {
          name: "Stage Pack (+ Х-стойка + полужесткий чехол)",
          extraPrice: 8990
        }
      ],
      specs: [{
          label: "Клавиатура",
          value: "61 клавиша с динамической чувствительностью"
        },
        {
          label: "Звуковой движок",
          value: "ZEN-Core (поддержка Wave Expansion)"
        },
        {
          label: "Количество тембров",
          value: "Более 3800 тембров + 95 ударных установок"
        },
        {
          label: "Секвенсор",
          value: "8-дорожечный шаговый секвенсор"
        },
        {
          label: "Аудиоинтерфейс",
          value: "USB-C Audio/MIDI, микрофонный вход с вокодером"
        },
        {
          label: "Питание",
          value: "Сетевой адаптер или внешний аккумулятор USB-C"
        },
        {
          label: "Страна производства",
          value: "Малайзия"
        },
        {
          label: "Гарантия",
          value: "2 года"
        }
      ]
    },
    {
      id: "shure-sm58",
      title: "Shure SM58-LCE",
      brand: "Shure",
      category: "Микрофоны",
      categorySlug: "mics",
      price: 14490,
      oldPrice: 16290,
      badge: "Хит",
      badgeType: "hit",
      rating: 5.0,
      reviews: 310,
      inStock: true,
      sku: "SW-30018",
      popularity: 99,
      dateAdded: "2025-11-20",
      image: "img/photo-1590602847861-f357a9332bbc.jpg",
      gallery: [
        "img/photo-1590602847861-f357a9332bbc.jpg",
        "img/photo-1563245372-f21724e3856d.avif",
        "img/photo-1511671782779-c97d3d27a1d4.avif"
      ],
      desc: "Мировой стандарт динамического вокального микрофона.",
      fullDesc: "Shure SM58 — это признанный золотой эталон сценического и студийного вокала. Кардиоидная диаграмма направленности изолирует основной источник звука и отсекает посторонние сценические шумы. Прочнейший металлический корпус и стальная сетка со встроенным поп-фильтром выдерживают любые испытания туровой жизни.",
      colors: [{
        name: "Matte Dark Grey",
        dot: "#3a3a3a"
      }],
      configs: ["Без выключателя (LCE)", "С выключателем (SE)"],
      packages: [{
          name: "Базовая (микрофон + чехол + держатель)",
          extraPrice: 0
        },
        {
          name: "Vocalist Kit (+ кабель XLR 6м + стойка K&M)",
          extraPrice: 3990
        }
      ],
      specs: [{
          label: "Тип",
          value: "Динамический"
        },
        {
          label: "Диаграмма направленности",
          value: "Кардиоидная"
        },
        {
          label: "Частотный диапазон",
          value: "50 Гц – 15 000 Гц"
        },
        {
          label: "Выходное сопротивление",
          value: "300 Ом"
        },
        {
          label: "Чувствительность",
          value: "-54.5 dBV/Pa (1.85 мВ)"
        },
        {
          label: "Разъём",
          value: "XLR (3-pin)"
        },
        {
          label: "Страна производства",
          value: "Мексика"
        },
        {
          label: "Гарантия",
          value: "2 года"
        }
      ]
    },
    {
      id: "marshall-mg30gfx",
      title: "Marshall MG30GFX Gold",
      brand: "Marshall",
      category: "Усилители",
      categorySlug: "amps",
      price: 29990,
      oldPrice: 34990,
      badge: "-15%",
      badgeType: "sale",
      rating: 4.8,
      reviews: 67,
      inStock: true,
      sku: "SW-40092",
      popularity: 89,
      dateAdded: "2026-01-28",
      image: "img/photo-1563245372-f21724e3856d.avif",
      gallery: [
        "img/photo-1563245372-f21724e3856d.avif",
        "img/photo-1550291652-6ea9114a47b1.avif",
        "img/photo-1516924962500-2b4b3b99ea02.jpg"
      ],
      desc: "Комбоусилитель 30 Вт с 10-дюймовым динамиком и блоком цифровых эффектов.",
      fullDesc: "Marshall MG30GFX — компактный, но мощный гитарный комбоусилитель легендарной серии Gold. 4 переключаемых канала (Clean, Crunch, OD1, OD2), встроенный блок цифровых эффектов (Reverb, Chorus, Phaser, Flanger, Delay) и кастомный 10-дюймовый динамик позволяют нарулить фирменный британский рок-саунд прямо в комнате или на репетиции.",
      colors: [{
        name: "Marshall Gold / Black",
        dot: "linear-gradient(135deg, #caa14c, #111)"
      }],
      configs: ["Мощность 30 Вт, динамик 10 дюймов"],
      packages: [{
          name: "Базовая (усилитель + сетевой кабель)",
          extraPrice: 0
        },
        {
          name: "PRO (+ футсвич Marshall PEDL-90008)",
          extraPrice: 4490
        }
      ],
      specs: [{
          label: "Тип",
          value: "Транзисторный с DSP-процессором"
        },
        {
          label: "Выходная мощность",
          value: "30 Вт"
        },
        {
          label: "Динамик",
          value: '1 × 10" Custom Speaker (4 Ом)'
        },
        {
          label: "Каналы",
          value: "4 (Clean, Crunch, OD-1, OD-2)"
        },
        {
          label: "Эффекты",
          value: "Reverb, Chorus, Phaser, Flanger, Vibe, Delay"
        },
        {
          label: "Входы/выходы",
          value: "Инструментальный Jack 6.3 мм, AUX 3.5 мм, выход на наушники"
        },
        {
          label: "Вес",
          value: "10.8 кг"
        },
        {
          label: "Гарантия",
          value: "2 года"
        }
      ]
    },
    {
      id: "tama-imperialstar",
      title: "TAMA Imperialstar 22",
      brand: "TAMA",
      category: "Ударные",
      categorySlug: "drums",
      price: 94990,
      oldPrice: null,
      badge: "NEW",
      badgeType: "new",
      rating: 4.9,
      reviews: 21,
      inStock: true,
      sku: "SW-50119",
      popularity: 86,
      dateAdded: "2026-03-05",
      image: "img/photo-1519892300165-cb5542fb47c7.jpg",
      gallery: [
        "img/photo-1519892300165-cb5542fb47c7.jpg",
        "img/photo-1590602847861-f357a9332bbc.jpg",
        "img/photo-1511671782779-c97d3d27a1d4.avif"
      ],
      desc: "Полный комплект акустических барабанов со стойками и тарелками Meinl.",
      fullDesc: "TAMA Imperialstar 22 — готовый комплект ударной установки высшего класса для начинающих и выступающих барабанщиков. Кадушки из 100% тополя (6 слоев, 8 мм) обеспечивают сбалансированное звучание с глубоким резонансом. В комплект входит полный набор надежной фурнитуры Stage Master и комплект тарелок Meinl HCS (Германия).",
      colors: [{
          name: "Hairline Black",
          dot: "#181818"
        },
        {
          name: "Coffee Teak Wrap",
          dot: "#4a3525"
        },
        {
          name: "Burgundy Walnut",
          dot: "#4d1a24"
        }
      ],
      configs: ["5 барабанов (22/10/12/16/14) + хардвер + тарелки"],
      packages: [{
          name: "Комплект с тарелками Meinl HCS",
          extraPrice: 0
        },
        {
          name: "PRO (+ набор демпферов Evans + чехлы)",
          extraPrice: 9990
        }
      ],
      specs: [{
          label: "Конфигурация",
          value: 'Бас-бочка 22"×16", томы 10"×7", 12"×8", флор-том 16"×15", малый 14"×5"'
        },
        {
          label: "Материал кадушек",
          value: "100% тополь (Poplar), 6 слоёв, 8 мм"
        },
        {
          label: "Фурнитура",
          value: "Двойные ножки, стойки TAMA Stage Master"
        },
        {
          label: "Тарелки в комплекте",
          value: 'Meinl HCS: 14" Hi-Hat, 16" Crash, 20" Ride'
        },
        {
          label: "Педаль",
          value: "TAMA Iron Cobra 200 Power Glide"
        },
        {
          label: "Стул барабанщика",
          value: "В комплекте (с регулировкой высоты)"
        },
        {
          label: "Гарантия",
          value: "2 года"
        }
      ]
    },
    {
      id: "yamaha-pacifica",
      title: "Yamaha Pacifica 112V",
      brand: "Yamaha",
      category: "Электрогитары",
      categorySlug: "electric",
      price: 39990,
      oldPrice: 44990,
      badge: "Хит",
      badgeType: "hit",
      rating: 4.8,
      reviews: 142,
      inStock: true,
      sku: "SW-10114",
      popularity: 95,
      dateAdded: "2026-02-15",
      image: "img/photo-1510915361894-db8b60106cb1.avif",
      gallery: [
        "img/photo-1510915361894-db8b60106cb1.avif",
        "img/photo-1550291652-6ea9114a47b1.avif",
        "img/photo-1516924962500-2b4b3b99ea02.jpg"
      ],
      desc: "Универсальный стратокастер H-S-S с отсечкой катушки и массивом ольхи.",
      fullDesc: "Yamaha Pacifica 112V давно заслужила репутацию лучшей универсальной гитары в своем ценовом классе. Корпус из массива ольхи, звукосниматели Alnico V конфигурации HSS с возможностью отсечки хамбакера в сингл делают эту гитару одинаково убедительной в джазе, фанке, блюзе и металле.",
      colors: [{
          name: "Sonic Blue",
          dot: "#9bbcd4"
        },
        {
          name: "Old Violin Sunburst",
          dot: "linear-gradient(135deg, #a8581e, #1a100a)"
        },
        {
          name: "Black",
          dot: "#1a1a1a"
        }
      ],
      configs: ["HSS (хамбакер + 2 сингла Alnico V)"],
      packages: [{
          name: "Базовая комплектация",
          extraPrice: 0
        },
        {
          name: "Start Pack (+ кабель Fender + ремень + тюнер)",
          extraPrice: 2990
        }
      ],
      specs: [{
          label: "Форма корпуса",
          value: "Pacifica Doublecut"
        },
        {
          label: "Материал корпуса",
          value: "Массив ольхи (Solid Alder)"
        },
        {
          label: "Гриф",
          value: "Клён с матовым сатиновым покрытием"
        },
        {
          label: "Накладка",
          value: "Палисандр (Rosewood)"
        },
        {
          label: "Мензура",
          value: '25.5" (648 мм)'
        },
        {
          label: "Количество ладов",
          value: "22"
        },
        {
          label: "Звукосниматели",
          value: "Alnico V: 2 × Single, 1 × Humbucker с Push/Pull"
        },
        {
          label: "Бридж",
          value: "Vintage Tremolo со литыми седлами"
        },
        {
          label: "Гарантия",
          value: "2 года"
        }
      ]
    }
  ]
};

function getStoredCart() {
  const data = localStorage.getItem("soundwave_cart");
  if (data) {
    try {
      return JSON.parse(data);
    } catch (e) {
      return [];
    }
  }
  return [{
      id: "fender-strat",
      quantity: 1
    },
    {
      id: "shure-sm58",
      quantity: 2
    },
    {
      id: "marshall-mg30gfx",
      quantity: 1
    }
  ];
}

function setStoredCart(cart) {
  localStorage.setItem("soundwave_cart", JSON.stringify(cart));
  updateCartBadges();
}

function addToCart(productId, quantity = 1) {
  const cart = getStoredCart();
  const existing = cart.find((item) => item.id === productId);
  if (existing) {
    existing.quantity += quantity;
  } else {
    cart.push({
      id: productId,
      quantity: quantity
    });
  }
  setStoredCart(cart);
  showToast("Товар добавлен в корзину");
  if (typeof renderCartPage === "function") {
    renderCartPage();
  }
}

function updateCartBadges() {
  const cart = getStoredCart();
  const totalCount = cart.reduce((acc, item) => acc + item.quantity, 0);
  document.querySelectorAll(".cart-count-badge").forEach((badge) => {
    badge.textContent = totalCount;
    badge.style.display = totalCount > 0 ? "flex" : "none";
  });
}

function getStoredFavs() {
  const data = localStorage.getItem("soundwave_favs");
  if (data) {
    try {
      return JSON.parse(data);
    } catch (e) {
      return [];
    }
  }
  return ["fender-strat", "yamaha-p225"];
}

function setStoredFavs(favs) {
  localStorage.setItem("soundwave_favs", JSON.stringify(favs));
  updateFavBadges();
}

function toggleFav(productId) {
  let favs = getStoredFavs();
  if (favs.includes(productId)) {
    favs = favs.filter((id) => id !== productId);
    showToast("Удалено из избранного");
  } else {
    favs.push(productId);
    showToast("Добавлено в избранное");
  }
  setStoredFavs(favs);
  if (typeof renderFavoritesPage === "function") {
    renderFavoritesPage();
  }
}

function updateFavBadges() {
  const favs = getStoredFavs();
  document.querySelectorAll(".fav-count-badge").forEach((badge) => {
    badge.textContent = favs.length;
    badge.style.display = favs.length > 0 ? "flex" : "none";
  });
  document.querySelectorAll(".product-fav-btn").forEach((btn) => {
    const id = btn.getAttribute("data-id");
    if (id && favs.includes(id)) {
      btn.classList.add("active");
      btn.innerHTML = '<i class="bi bi-heart-fill"></i>';
    } else if (id) {
      btn.classList.remove("active");
      btn.innerHTML = '<i class="bi bi-heart"></i>';
    }
  });
}

async function getCurrentUser() {
  response = await fetch('/api/user.php', {
      method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
      let user = {
        name: data.message.firstname + ' ' + data.message.lastname + ' ' + data.message.patronymic,
        email: data.message.email,
        phone: data.message.phone,
        birthday: data.message.birthday,
        city: data.message.city,
        address: data.message.adress,
        instrument: data.message.instrument,
        bonuses: data.message.bonus_balance,
        ordersCount: 1
      }

      return user
    })
    .catch(error => {
      showToast(error);
    });
  return response
};


async function getStoredOrders() {
  response = await fetch('/api/user_orders.php', {
      method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
      return data
    })
    .catch(error => {
      showToast(error);
    });
  return response
}

function addStoredOrder(order) {
  const orders = getStoredOrders();
  orders.unshift(order);
  localStorage.setItem("soundwave_orders", JSON.stringify(orders));

  const user = getCurrentUser();
  if (user) {
    user.ordersCount = orders.length;
    user.bonuses = (user.bonuses || 0) + Math.round(order.total * 0.05);

  }
  return order;
}

function repeatOrder(orderId) {
  const orders = getStoredOrders();
  const order = orders.find(o => o.id === orderId);
  if (!order) return;
  const cart = getStoredCart();
  order.items.forEach(it => {
    const ex = cart.find(c => c.id === it.id);
    if (ex) {
      ex.quantity += it.quantity;
    } else {
      cart.push({
        id: it.id,
        quantity: it.quantity
      });
    }
  });
  setStoredCart(cart);
  showToast("Товары из заказа добавлены в корзину!");
  setTimeout(() => {
    window.location.href = "cart.html";
  }, 700);
}

function getRecentProducts() {
  const data = localStorage.getItem("soundwave_recent");
  if (data) {
    try {
      return JSON.parse(data);
    } catch (e) {
      return [];
    }
  }
  return ["fender-strat", "yamaha-p225", "marshall-mg30gfx"];
}

function addRecentProduct(productId) {
  let list = getRecentProducts();
  list = list.filter(id => id !== productId);
  list.unshift(productId);
  if (list.length > 8) list = list.slice(0, 8);
  localStorage.setItem("soundwave_recent", JSON.stringify(list));
}

function getProductReviews(productId) {
  const defaultReviews = {
    "fender-strat": [{
        author: "Михаил Р.",
        date: "2 марта 2026",
        rating: 5,
        text: "Гитара превзошла все ожидания! Звук потрясающий, сустейн бесконечный. Отделка 3-Color Sunburst идеальная.",
        verified: true
      },
      {
        author: "Артём Ковалев",
        date: "24 февраля 2026",
        rating: 5,
        text: "Классический стратовский звук с датчиками Alnico 5. Гриф приятный, лады закатаны без нареканий.",
        verified: true
      },
      {
        author: "Сергей В.",
        date: "15 января 2026",
        rating: 4,
        text: "Отличный инструмент для сцены и студии. Заводская отстройка почти идеальная, чуть опустил струны.",
        verified: false
      }
    ],
    "gibson-les-paul": [{
        author: "Денис Г.",
        date: "5 марта 2026",
        rating: 5,
        text: "Настоящий Gibson с плотным мощным низом. В перегрузе поёт великолепно!",
        verified: true
      },
      {
        author: "Владимир",
        date: "18 февраля 2026",
        rating: 5,
        text: "Сустейн и хамбакеры 490R/498T творят чудеса. Очень доволен покупкой в SOUNDWAVE.",
        verified: true
      }
    ],
    "yamaha-p225": [{
        author: "Елена Смирнова",
        date: "27 февраля 2026",
        rating: 5,
        text: "Клавиатура GHC очень отзывчивая, звук рояля CFX глубокий и естественный. Для занятий дома — лучший выбор.",
        verified: true
      },
      {
        author: "Павел К.",
        date: "10 февраля 2026",
        rating: 5,
        text: "Компактный размер, качественная сборка, подключение по USB без задержек.",
        verified: true
      }
    ],
    "roland-juno-d": [{
      author: "Максим DJ",
      date: "4 марта 2026",
      rating: 5,
      text: "Движок ZEN-Core выдаёт невероятные текстуры! Лёгкий, питание от USB-C позволяет репетировать везде.",
      verified: true
    }],
    "shure-sm58": [{
        author: "Игорь Новиков",
        date: "4 марта 2026",
        rating: 5,
        text: "Легенда! Неубиваемый микрофон, вокал звучит плотно и пробивно в любом миксе.",
        verified: true
      },
      {
        author: "Анна Вокальная",
        date: "19 февраля 2026",
        rating: 5,
        text: "Использую в студии и на лайвах. Никакой обратной связи, надёжность 100%.",
        verified: true
      }
    ],
    "marshall-mg30gfx": [{
      author: "Дмитрий рок",
      date: "1 марта 2026",
      rating: 5,
      text: "Отличный комбарь для квартиры и гаража. Эффекты звучат сочно, 30 Вт хватает с запасом.",
      verified: true
    }],
    "tama-imperialstar": [{
      author: "Константин Ударный",
      date: "6 марта 2026",
      rating: 5,
      text: "Тополь звучит сбалансированно, стойки массивные. Комплект тарелок Meinl в комплекте — супербонус!",
      verified: true
    }],
    "yamaha-pacifica": [{
      author: "Олег Б.",
      date: "20 февраля 2026",
      rating: 5,
      text: "Лучшая первая и рабочая гитара. Отсечка на бриджевом хамбакере даёт массу вариантов звучания.",
      verified: true
    }]
  };

  const stored = localStorage.getItem("soundwave_reviews");
  let allStored = {};
  if (stored) {
    try {
      allStored = JSON.parse(stored);
    } catch (e) {}
  }

  const custom = allStored[productId] || [];
  const base = defaultReviews[productId] || [{
    author: "Покупатель SOUNDWAVE",
    date: "1 марта 2026",
    rating: 5,
    text: "Отличный инструмент, всё соответствует заявленным характеристикам!",
    verified: true
  }];

  return [...custom, ...base];
}

function addProductReview(productId, review) {
  const stored = localStorage.getItem("soundwave_reviews");
  let allStored = {};
  if (stored) {
    try {
      allStored = JSON.parse(stored);
    } catch (e) {}
  }
  if (!allStored[productId]) allStored[productId] = [];
  allStored[productId].unshift({
    author: review.author || "Музыкант SOUNDWAVE",
    date: "Сегодня",
    rating: Number(review.rating) || 5,
    text: review.text,
    verified: true
  });
  localStorage.setItem("soundwave_reviews", JSON.stringify(allStored));
  showToast("Спасибо! Ваш отзыв опубликован");
}

function formatPrice(num) {
  return (num || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " ₽";
}

function showToast(message) {
  let container = document.querySelector(".toast-container");
  if (!container) {
    container = document.createElement("div");
    container.className = "toast-container";
    document.body.appendChild(container);
  }
  const toast = document.createElement("div");
  toast.className = "toast-msg";
  toast.innerHTML = `<i class="bi bi-check-circle-fill"></i><span>${message}</span>`;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.transition = "opacity 0.4s ease, transform 0.4s ease";
    toast.style.opacity = "0";
    toast.style.transform = "translateY(20px)";
    setTimeout(() => toast.remove(), 400);
  }, 2400);
}

document.addEventListener("DOMContentLoaded", () => {
  updateCartBadges();
  updateFavBadges();


  document.addEventListener("click", (e) => {
    const addBtn = e.target.closest("[data-add-cart]");
    if (addBtn) {
      e.preventDefault();
      const id = addBtn.getAttribute("data-add-cart");
      const qtyInput = document.querySelector("#product-quantity-input");
      const qty = qtyInput ? parseInt(qtyInput.value, 10) || 1 : 1;
      addToCart(id, qty);
    }

    const favBtn = e.target.closest(".product-fav-btn, [data-toggle-fav]");
    if (favBtn) {
      e.preventDefault();
      const id = favBtn.getAttribute("data-id") || favBtn.getAttribute("data-toggle-fav");
      if (id) {
        toggleFav(id);
      }
    }
  });

  const searchInput = document.querySelector("#header-search-input");
  const searchResults = document.querySelector("#search-autocomplete-box");

  if (searchInput && searchResults) {
    searchInput.addEventListener("input", () => {
      const val = searchInput.value.trim().toLowerCase();
      if (val.length >= 2) {
        const matches = SOUNDWAVE_DATA.products.filter(
          (p) =>
          p.title.toLowerCase().includes(val) ||
          p.category.toLowerCase().includes(val) ||
          (p.brand && p.brand.toLowerCase().includes(val))
        );

        if (matches.length > 0) {
          let html = "";
          matches.slice(0, 4).forEach((item) => {
            html += `
              <a href="product.html?id=${item.id}" class="autocomplete-item">
                <div>
                  <strong>${item.title}</strong>
                  <div style="font-size: 11px; color: var(--text-muted);">${item.category} • ${item.brand || ''}</div>
                </div>
                <span style="font-weight: 700; color: var(--yellow-main);">${formatPrice(item.price)}</span>
              </a>
            `;
          });
          html += `<a href="catalog.html?q=${encodeURIComponent(val)}" class="autocomplete-footer">Показать все результаты (${matches.length})</a>`;
          searchResults.innerHTML = html;
          searchResults.classList.add("active");
        } else {
          searchResults.innerHTML = `<div class="autocomplete-item" style="color: var(--text-muted);">Ничего не найдено</div>`;
          searchResults.classList.add("active");
        }
      } else {
        searchResults.classList.remove("active");
      }
    });

    document.addEventListener("click", (e) => {
      if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.remove("active");
      }
    });
  }

  const mobileToggle = document.querySelector("#mobile-menu-toggle");
  const mobileDrawer = document.querySelector("#mobile-nav-drawer");
  const mobileClose = document.querySelector("#mobile-drawer-close");
  const drawerOverlay = document.querySelector("#drawer-overlay");

  if (mobileToggle && mobileDrawer && drawerOverlay) {
    const openDrawer = () => {
      mobileDrawer.classList.add("open");
      drawerOverlay.classList.add("active");
    };
    const closeDrawer = () => {
      mobileDrawer.classList.remove("open");
      drawerOverlay.classList.remove("active");
    };

    mobileToggle.addEventListener("click", openDrawer);
    if (mobileClose) mobileClose.addEventListener("click", closeDrawer);
    drawerOverlay.addEventListener("click", closeDrawer);
  }
});
<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Вход и регистрация | SOUNDWAVE</title>
  <meta name="description"
    content="Авторизация в личном кабинете интернет-магазина музыкальных инструментов SOUNDWAVE.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/common.css">
  <link rel="stylesheet" href="css/login.css">
</head>

<body>

  <?php require('./components/header.php') ?>

  <main>
    <div class="auth-wrapper">
      <div class="auth-card">
        <div class="auth-header">
          <a href="/" class="brand-logo">
            <div class="brand-icon"><i class="bi bi-soundwave"></i></div>
            <span>SOUND<span>WAVE</span></span>
          </a>
          <h1 class="auth-title" id="auth-title-text">Вход в личный кабинет</h1>
          <p class="auth-subtitle" id="auth-subtitle-text">Получите доступ к заказам, персональным ценам и бонусам</p>
        </div>

        <div class="auth-tabs">
          <button type="button" class="auth-tab-btn active" id="tab-btn-login">Вход</button>
          <button type="button" class="auth-tab-btn" id="tab-btn-register">Регистрация</button>
        </div>

        <form id="login-form" class="auth-form">
          <div class="form-field">
            <label for="login-email">Email</label>
            <div class="input-with-icon">
              <i class="bi bi-envelope"></i>
              <input type="email" name="login-login" value="alexander.sound@example.com" placeholder="name@example.com"
                required>
            </div>
          </div>

          <div class="form-field">
            <label for="login-password">Пароль</label>
            <div class="input-with-icon">
              <i class="bi bi-lock"></i>
              <input type="password" name="login-password" id="login-password" value="123456" placeholder="Введите пароль" required>
              <button type="button" class="password-toggle-btn"
                onclick="togglePasswordVisibility('login-password', this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <div class="auth-options-row">
            <label class="custom-checkbox">
              <input type="checkbox" checked>
              <span>Запомнить меня</span>
            </label>
            <a href="#" class="forgot-link" onclick="showToast('Ссылка для сброса пароля отправлена на email')">Забыли
              пароль?</a>
          </div>

          <button type="submit" class="btn-brand" style="width: 100%; margin-top: 8px;">
            Войти в аккаунт
            <i class="bi bi-box-arrow-in-right"></i>
          </button>
        </form>

        <form id="register-form" class="auth-form" style="display: none;">

          <div class="form-field">
            <label for="reg-name">Фамилия</label>
            <div class="input-with-icon">
              <i class="bi bi-person"></i>
              <input type="text" name="reg-lastname" placeholder="Музыкантов" required>
            </div>
          </div>

          <div class="form-field">
            <label for="reg-name">Имя</label>
            <div class="input-with-icon">
              <i class="bi bi-person"></i>
              <input type="text" name="reg-name" placeholder="Константин" required>
            </div>
          </div>

          <div class="form-field">
            <label for="reg-name">Отчество</label>
            <div class="input-with-icon">
              <i class="bi bi-person"></i>
              <input type="text" name="reg-patronymic" placeholder="Тромбонович" required>
            </div>
          </div>

          <div class="form-field">
            <label for="reg-email">Электронная почтоа</label>
            <div class="input-with-icon">
              <i class="bi bi-envelope"></i>
              <input type="email" name="reg-email" placeholder="konstantin@soundwave.ru" required>
            </div>
          </div>

          <div class="form-field">
            <label for="reg-phone">Номер телефона</label>
            <div class="input-with-icon">
              <i class="bi bi-telephone"></i>
              <input type="tel" name="reg-phone" placeholder="+7 (999) 000-00-00" required>
            </div>
          </div>

          <div class="form-field">
            <label for="reg-password">Придумайте пароль</label>
            <div class="input-with-icon">
              <i class="bi bi-lock"></i>
              <input type="password" name="reg-password" id="reg-password" placeholder="Минимум 6 символов" required>
              <button type="button" class="password-toggle-btn"
                onclick="togglePasswordVisibility('reg-password', this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <label class="custom-checkbox" style="font-size: 12px; margin-top: 4px;">
            <input type="checkbox" checked required>
            <span>Я согласен с условиями оферты и обработкой персональных данных</span>
          </label>

          <button type="submit" class="btn-brand" style="width: 100%; margin-top: 8px;">
            Зарегистрироваться
            <i class="bi bi-person-plus-fill"></i>
          </button>
        </form>

        <div class="demo-credentials-box">
          <strong>Демо-доступ (тестовые данные):</strong>
          <div>Email: <code>alexander.sound@example.com</code></div>
          <div>Пароль: <code>123456</code></div>
          <button type="button" class="btn-wenge" style="width: 100%; margin-top: 8px; padding: 6px; font-size: 12px;"
            onclick="fillDemoCredentials()">
            Заполнить тестовые данные в 1 клик
          </button>
        </div>

        <div class="social-login-separator">
          <span>или через сервисы</span>
        </div>

        <div class="social-login-buttons">
          <button type="button" class="btn-social" onclick="handleSocialAuth('VK ID')">
            <i class="bi bi-shield-check" style="color: #0077ff;"></i>
            VK ID
          </button>
          <button type="button" class="btn-social" onclick="handleSocialAuth('Яндекс ID')">
            <i class="bi bi-record-circle" style="color: #fc3f1d;"></i>
            Яндекс ID
          </button>
        </div>
      </div>
    </div>
  </main>

  <footer class="site-footer">
    <div class="container">
      <div class="footer-bottom">
        <div>© 2026 SOUNDWAVE. Все права защищены.</div>
        <div>Сыктывкар, Суетная 67</div>
      </div>
    </div>
  </footer>

  <script src="js/main.js"></script>
  <script>
    const loginForm = document.querySelector('#login-form');
    const registerForm = document.querySelector('#register-form');

    function switchAuthTab(mode) {
      const tabLogin = document.querySelector('#tab-btn-login');
      const tabRegister = document.querySelector('#tab-btn-register');
      const title = document.querySelector('#auth-title-text');
      const subtitle = document.querySelector('#auth-subtitle-text');
      tabLogin.addEventListener('click', (e)=>{
        e.preventDefault();
        switchAuthTab('login');
      })
      tabRegister.addEventListener('click', (e)=>{
        e.preventDefault();
        switchAuthTab('register');
      })
      if (mode === 'login') {
        loginForm.style.display = 'flex';
        registerForm.style.display = 'none';
        tabLogin.classList.add('active');
        tabRegister.classList.remove('active');
        title.textContent = 'Вход в личный кабинет';
        subtitle.textContent = 'Получите доступ к заказам, персональным ценам и бонусам';
      } else {
        loginForm.style.display = 'none';
        registerForm.style.display = 'flex';
        tabLogin.classList.remove('active');
        tabRegister.classList.add('active');
        title.textContent = 'Создание аккаунта';
        subtitle.textContent = 'Зарегистрируйтесь и получите 1 500 приветственных бонусов';
      }
    }

    function togglePasswordVisibility(fieldId, btn) {
      const field = document.getElementById(fieldId);
      const icon = btn.querySelector('i');
      if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'bi bi-eye-slash';
      } else {
        field.type = 'password';
        icon.className = 'bi bi-eye';
      }
    }

    

    registerForm.addEventListener('submit', function (event) {
      event.preventDefault();

      const formData = new FormData(registerForm);

      fetch('/api/register.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          showToast('Аккаунт создан!');
          setTimeout(() => {
            window.location.href = 'account.php';
          }, 700);

        })
        .catch(error => {
          showToast(error);
        });
    });

    loginForm.addEventListener('submit', function (event) {
      event.preventDefault();

      const formData = new FormData(loginForm);

      fetch('/api/v1/login', {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.status == 'error') {
            showToast(data.message);
          } else {
            showToast(data.message);
            setTimeout(() => {
              window.location.href = 'account.php';
            }, 700);
          }
        })
        .catch(error => {
          showToast(error);
        });
    });

    switchAuthTab('login');

  </script>
</body>

</html>
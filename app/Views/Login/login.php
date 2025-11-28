<!DOCTYPE html>
<html class="h-full" data-theme="true" data-theme-mode="light" dir="ltr" lang="pt-BR">
<head>
  <base href="<?= base_url('public/template/') ?>">
  <title>Login - Finamassa</title>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport"/>
  <link href="<?= base_url('assets/media/app/favicon.ico') ?>" rel="shortcut icon"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="<?= base_url('assets/vendors/apexcharts/apexcharts.css') ?>" rel="stylesheet"/>
  <link href="<?= base_url('assets/vendors/keenicons/styles.bundle.css') ?>" rel="stylesheet"/>
  <link href="<?= base_url('assets/css/styles.css') ?>" rel="stylesheet"/>
</head>
<body class="antialiased flex h-full text-base text-gray-700 dark:bg-coal-500">
  <!-- Theme Mode -->
  <script>
    const defaultThemeMode = 'light';
    let themeMode;

    if (document.documentElement) {
      if (localStorage.getItem('theme')) {
        themeMode = localStorage.getItem('theme');
      } else if (document.documentElement.hasAttribute('data-theme-mode')) {
        themeMode = document.documentElement.getAttribute('data-theme-mode');
      } else {
        themeMode = defaultThemeMode;
      }

      if (themeMode === 'system') {
        themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      }

      document.documentElement.classList.add(themeMode);
    }
  </script>
  <!-- End of Theme Mode -->
  
  <!-- Page -->
  <style>
    .page-bg {
      background-image: url('<?= base_url('assets/media/images/2600x1200/bg-10.png') ?>');
    }
    .dark .page-bg {
      background-image: url('<?= base_url('assets/media/images/2600x1200/bg-10-dark.png') ?>');
    }
  </style>
  
  <div class="flex items-center justify-center grow bg-center bg-no-repeat page-bg">
    <div class="card max-w-[370px] w-full">
      <form action="<?= base_url('Login/validaLogin') ?>" class="card-body flex flex-col gap-5 p-10" id="sign_in_form" method="post">
        <?= csrf_field() ?>
        
        <div class="text-center mb-5">
          <img class="max-h-[60px] mx-auto mb-4" src="<?= base_url('assets/media/app/WhatsApp_Image_2025-11-24_at_11.22.48-removebg-preview.png') ?>" alt="Finamassa"/>
          <h3 class="text-lg font-medium text-gray-900 leading-none mb-2.5">
            Entrar no Sistema
          </h3>
          <p class="text-sm text-gray-500">Sistema de Gestão</p>
        </div>

        <!-- Mensagens de Erro/Sucesso -->
        <?php if (isset($errologin)): ?>
          <div class="alert alert-danger flex items-center gap-2.5 p-3 rounded-md bg-red-50 border border-red-200">
            <i class="ki-filled ki-information-2 text-red-500"></i>
            <span class="text-sm text-red-700"><?= esc($errologin) ?></span>
          </div>
        <?php endif; ?>

        <?php if (isset($rec_erro_user)): ?>
          <div class="alert alert-danger flex items-center gap-2.5 p-3 rounded-md bg-red-50 border border-red-200">
            <i class="ki-filled ki-information-2 text-red-500"></i>
            <span class="text-sm text-red-700"><?= esc($rec_erro_user) ?></span>
          </div>
        <?php endif; ?>

        <?php if (isset($rec_erro_token)): ?>
          <div class="alert alert-danger flex items-center gap-2.5 p-3 rounded-md bg-red-50 border border-red-200">
            <i class="ki-filled ki-information-2 text-red-500"></i>
            <span class="text-sm text-red-700"><?= esc($rec_erro_token) ?></span>
          </div>
        <?php endif; ?>

        <?php if (isset($rec_sucesso_email)): ?>
          <div class="alert alert-success flex items-center gap-2.5 p-3 rounded-md bg-green-50 border border-green-200">
            <i class="ki-filled ki-check-circle text-green-500"></i>
            <span class="text-sm text-green-700"><?= esc($rec_sucesso_email) ?></span>
          </div>
        <?php endif; ?>

        <?php if (isset($rec_sucesso)): ?>
          <div class="alert alert-success flex items-center gap-2.5 p-3 rounded-md bg-green-50 border border-green-200">
            <i class="ki-filled ki-check-circle text-green-500"></i>
            <span class="text-sm text-green-700"><?= esc($rec_sucesso) ?></span>
          </div>
        <?php endif; ?>

        <!-- Campo Email -->
        <div class="flex flex-col gap-1">
          <label class="form-label font-normal text-gray-900" for="email">
            Email
          </label>
          <label class="input">
            <i class="ki-filled ki-sms"></i>
            <input 
              type="email" 
              id="email" 
              name="email" 
              placeholder="seu@email.com" 
              value="<?= old('email') ?>" 
              required
              autofocus
            />
          </label>
          <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('email')): ?>
            <span class="text-2sm text-red-600"><?= session()->getFlashdata('validation')->getError('email') ?></span>
          <?php endif; ?>
        </div>

        <!-- Campo Senha -->
        <div class="flex flex-col gap-1">
          <div class="flex items-center justify-between gap-1">
            <label class="form-label font-normal text-gray-900" for="senha">
              Senha
            </label>
            <a class="text-2sm link shrink-0" href="<?= base_url('Login/esqueceuSenha') ?>">
              Esqueceu a senha?
            </a>
          </div>
          <label class="input" data-toggle-password="true">
            <i class="ki-filled ki-lock"></i>
            <input 
              type="password" 
              id="senha" 
              name="senha" 
              placeholder="Digite sua senha" 
              required
            />
            <button class="btn btn-icon" data-toggle-password-trigger="true" type="button">
              <i class="ki-filled ki-eye text-gray-500 toggle-password-active:hidden"></i>
              <i class="ki-filled ki-eye-slash text-gray-500 hidden toggle-password-active:block"></i>
            </button>
          </label>
          <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('senha')): ?>
            <span class="text-2sm text-red-600"><?= session()->getFlashdata('validation')->getError('senha') ?></span>
          <?php endif; ?>
        </div>

        <!-- Checkbox Lembrar-me -->
        <label class="checkbox-group">
          <input class="checkbox checkbox-sm" name="lembrar" type="checkbox" value="1"/>
          <span class="checkbox-label">
            Lembrar-me
          </span>
        </label>

        <!-- Botão de Submit -->
        <button type="submit" class="btn btn-primary flex justify-center grow" id="btn_submit">
          <span class="indicator-label">Entrar</span>
          <span class="indicator-progress">
            Aguarde...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
          </span>
        </button>
      </form>
    </div>
  </div>
  <!-- End of Page -->

  <!-- Scripts -->
  <script src="<?= base_url('assets/js/core.bundle.js') ?>"></script>
  <script src="<?= base_url('assets/vendors/apexcharts/apexcharts.min.js') ?>"></script>
  
  <script>
    // Validação do formulário
    document.getElementById('sign_in_form').addEventListener('submit', function(e) {
      const email = document.getElementById('email').value.trim();
      const senha = document.getElementById('senha').value;
      const btnSubmit = document.getElementById('btn_submit');
      
      // Validação básica
      if (!email || !senha) {
        e.preventDefault();
        SwalWarning('Atenção!', 'Por favor, preencha todos os campos.');
        return false;
      }
      
      // Validação de email
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        e.preventDefault();
        SwalWarning('Atenção!', 'Por favor, insira um email válido.');
        return false;
      }
      
      // Mostrar loading
      btnSubmit.setAttribute('data-kt-indicator', 'on');
      btnSubmit.disabled = true;
    });

    // Auto-focus no campo email se estiver vazio
    window.addEventListener('load', function() {
      const emailField = document.getElementById('email');
      if (!emailField.value) {
        emailField.focus();
      }
    });
  </script>
</body>
</html>

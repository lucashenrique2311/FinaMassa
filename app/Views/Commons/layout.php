<!DOCTYPE html>
<html class="h-full" data-theme="true" data-theme-mode="light" dir="ltr" lang="pt-BR">
<head>
  <base href="<?= base_url('assets/') ?>">
  <title><?= isset($title) ? esc($title) : 'Sistema de Gestão' ?></title>
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
  
  <!-- Header -->
  <div class="fixed top-0 start-0 end-0 z-30 flex items-center justify-between h-[--tw-header-height] bg-[--tw-page-bg] dark:bg-[--tw-page-bg-dark] border-b border-gray-300 dark:border-gray-200 lg:hidden" id="header">
    <div class="flex items-center gap-2.5 px-3.5">
      <button class="btn btn-icon btn-sm" data-drawer-toggle="true" data-drawer-target="#sidebar">
        <i class="ki-filled ki-menu text-xl"></i>
      </button>
      <a href="<?= base_url('Dashboard') ?>">
        <img class="h-[42px] max-h-[50px]" src="<?= base_url('assets/media/app/WhatsApp_Image_2025-11-24_at_11.22.48-removebg-preview.png') ?>"/>
      </a>
    </div>
    <div class="flex items-center gap-2.5 px-3.5">
      <a class="btn btn-icon btn-sm" href="<?= base_url('Login/sair') ?>">
        <i class="ki-filled ki-exit-right"></i>
      </a>
    </div>
  </div>
  <!-- End of Header -->
  
  <!-- Sidebar -->
  <div class="fixed top-0 bottom-0 z-20 hidden lg:flex flex-col shrink-0 w-[--tw-sidebar-width] bg-[--tw-page-bg] dark:bg-[--tw-page-bg-dark]" data-drawer="true" data-drawer-class="drawer drawer-start flex top-0 bottom-0" data-drawer-enable="true|lg:false" id="sidebar">
    <!-- Sidebar Header -->
    <div id="sidebar_header">
      <div class="flex items-center gap-2.5 px-3.5 h-[70px]">
        <a href="<?= base_url('Dashboard') ?>">
          <img class="dark:hidden h-[42px]" src="<?= base_url('assets/media/app/mini-logo-circle.svg') ?>"/>
          <img class="hidden dark:inline-block h-[42px]" src="<?= base_url('assets/media/app/mini-logo-circle-dark.svg') ?>"/>
        </a>
        <div class="menu menu-default grow" data-menu="true">
          <div class="menu-item grow" data-menu-item-offset="0px, 15px" data-menu-item-placement="bottom-start" data-menu-item-toggle="dropdown" data-menu-item-trigger="hover">
            <div class="menu-label cursor-pointer text-gray-900 font-medium grow justify-between">
              <span class="text-base font-medium text-gray-900 grow justify-start">
                Sistema Gestão
              </span>
              <span class="menu-arrow">
                <i class="ki-filled ki-down"></i>
              </span>
            </div>
            <div class="menu-dropdown w-48 py-2">
              <div class="menu-item">
                <a class="menu-link" href="<?= base_url('Dashboard') ?>">
                  <span class="menu-icon">
                    <i class="ki-filled ki-home-3"></i>
                  </span>
                  <span class="menu-title">Dashboard</span>
                </a>
              </div>
              <div class="menu-item">
                <a class="menu-link" href="<?= base_url('Login/sair') ?>">
                  <span class="menu-icon">
                    <i class="ki-filled ki-exit-right"></i>
                  </span>
                  <span class="menu-title">Sair</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End of Sidebar Header -->
    
    <!-- Sidebar menu -->
    <div class="flex items-stretch grow shrink-0 justify-center my-5" id="sidebar_menu">
      <div class="scrollable-y-auto light:[--tw-scrollbar-thumb-color:var(--tw-content-scrollbar-color)] grow" data-scrollable="true" data-scrollable-dependencies="#sidebar_header, #sidebar_footer" data-scrollable-height="auto" data-scrollable-offset="0px" data-scrollable-wrappers="#sidebar_menu">
        <!-- Primary Menu -->
        <div class="menu flex flex-col w-full gap-1.5 px-3.5" data-menu="true" data-menu-accordion-expand-all="false" id="sidebar_primary_menu">
          <div class="menu-item">
            <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Dashboard') ?>">
              <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
                <i class="ki-filled ki-home-3"></i>
              </span>
              <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                Dashboard
              </span>
            </a>
          </div>
          
          <!-- Menu Usuários -->
          <div class="menu-item" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
            <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent">
              <span class="menu-icon items-start text-gray-600 text-lg menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
                <i class="ki-filled ki-profile-user"></i>
              </span>
              <span class="menu-title font-medium text-sm text-gray-800 menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                Cadastros
              </span>
              <span class="menu-arrow text-gray-600 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
                <i class="ki-filled ki-down text-xs menu-item-show:hidden"></i>
                <i class="ki-filled ki-up text-xs hidden menu-item-show:inline-flex"></i>
              </span>
            </div>
            <div class="menu-accordion gap-px ps-7">
              <div class="menu-item">
                <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Usuarios') ?>">
                  <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                    Usuários
                  </span>
                </a>
              </div>
            </div>
          </div>
        </div>
        <!-- End of Primary Menu -->
      </div>
    </div>
    <!-- End of Sidebar menu -->
    
    <!-- Sidebar Footer -->
    <div id="sidebar_footer" class="flex items-center gap-2.5 px-3.5 py-2.5 border-t border-gray-300 dark:border-gray-200">
      <div class="flex items-center gap-2.5 grow">
        <div class="relative shrink-0">
          <img alt="" class="rounded-full size-8" src="<?= base_url('assets/media/avatars/300-1.png') ?>"/>
        </div>
        <div class="flex flex-col gap-0.5 grow min-w-0">
          <div class="text-sm font-medium text-gray-900 truncate">
            <?= esc($usuario['nome'] ?? $usuario['RAZAO_SOCIAL'] ?? 'Usuário') ?>
          </div>
          <div class="text-2xs font-medium text-gray-500 truncate">
            <?= esc($usuario['email'] ?? $usuario['EMAIL'] ?? '') ?>
          </div>
        </div>
      </div>
      <a class="btn btn-icon btn-sm" href="<?= base_url('Login/sair') ?>">
        <i class="ki-filled ki-exit-right"></i>
      </a>
    </div>
    <!-- End of Sidebar Footer -->
  </div>
  <!-- End of Sidebar -->
  
  <!-- Wrapper -->
  <div class="flex flex-col lg:flex-row grow pt-[--tw-header-height] lg:pt-0">
    <!-- Main -->
    <div class="flex flex-col grow items-stretch rounded-xl bg-[--tw-content-bg] dark:bg-[--tw-content-bg-dark] border border-gray-300 dark:border-gray-200 lg:ms-[--tw-sidebar-width] mt-0 lg:mt-[15px] m-[15px]">
      <div class="flex flex-col grow lg:scrollable-y-auto lg:[scrollbar-width:auto] lg:light:[--tw-scrollbar-thumb-color:var(--tw-content-scrollbar-color)] pt-5" id="scrollable_content">
        <main class="grow" role="content">
          <?php if (isset($content)): ?>
            <?= $content ?>
          <?php else: ?>
            <?= $this->include('Dashboard/dashboard_content') ?>
          <?php endif; ?>
        </main>
      </div>
    </div>
    <!-- End of Main -->
  </div>
  <!-- End of Wrapper -->
  
  <!-- Scripts -->
  <script src="<?= base_url('assets/js/core.bundle.js') ?>"></script>
  <script src="<?= base_url('assets/vendors/apexcharts/apexcharts.min.js') ?>"></script>
  <?php if (isset($scripts)): ?>
    <?= $scripts ?>
  <?php endif; ?>
</body>
</html>


<!--
Product: Metronic is a toolkit of UI components built with Tailwind CSS for developing scalable web applications quickly and efficiently
Version: v9.1.1
Author: Keenthemes
Contact: support@keenthemes.com
Website: https://www.keenthemes.com
Support: https://devs.keenthemes.com
Follow: https://www.twitter.com/keenthemes
License: https://keenthemes.com/metronic/tailwind/docs/getting-started/license
-->
<!DOCTYPE html>
<html class="h-full" data-theme="true" data-theme-mode="light" dir="ltr" lang="en">
 <head>
  <title>
   <?= isset($title) ? esc($title) . ' - ' : '' ?>Finamassa
  </title>
  <meta charset="utf-8"/>
  <meta content="follow, index" name="robots"/>
  <link href="https://127.0.0.1:8001/metronic-tailwind-html/demo6/network/user-table/saas-users" rel="canonical"/>
  <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport"/>
  <meta content="SaaS users table, powered by Tailwind CSS" name="description"/>
  <meta content="@keenthemes" name="twitter:site"/>
  <meta content="@keenthemes" name="twitter:creator"/>
  <meta content="summary_large_image" name="twitter:card"/>
  <meta content="Finamassa - Sistema de Gestão" name="twitter:title"/>
  <meta content="SaaS users table, powered by Tailwind CSS" name="twitter:description"/>
  <meta content="<?= base_url('assets/media/app/og-image.png') ?>" name="twitter:image"/>
  <meta content="<?= base_url() ?>" property="og:url"/>
  <meta content="en_US" property="og:locale"/>
  <meta content="website" property="og:type"/>
  <meta content="Fina Massa" property="og:site_name"/>
  <meta content="Fina Massa - Sistema de Gestão" property="og:title"/>
  <meta content="Sistema de gestão para pizzarias" property="og:description"/>
  <meta content="<?= base_url('assets/media/app/og-image.png') ?>" property="og:image"/>
  <link href="<?= base_url('assets/media/app/apple-touch-icon.png') ?>" rel="apple-touch-icon" sizes="180x180"/>
  <link href="<?= base_url('assets/media/app/favicon-32x32.png') ?>" rel="icon" sizes="32x32" type="image/png"/>
  <link href="<?= base_url('assets/media/app/favicon-16x16.png') ?>" rel="icon" sizes="16x16" type="image/png"/>
  <link href="<?= base_url('assets/media/app/favicon.ico') ?>" rel="shortcut icon"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="<?= base_url('assets/vendors/apexcharts/apexcharts.css') ?>" rel="stylesheet"/>
  <link href="<?= base_url('assets/vendors/keenicons/styles.bundle.css') ?>" rel="stylesheet"/>
  <link href="<?= base_url('assets/css/styles.css') ?>" rel="stylesheet"/>
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Select2 Custom Styles -->
  <style>
    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
      border: 1px solid #e5e7eb !important;
      border-radius: 0.375rem;
      min-height: 2rem;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default .select2-selection--multiple:focus {
      border-color: #e5e7eb !important;
      outline: none;
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--focus .select2-selection--multiple {
      border-color: #e5e7eb !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 2rem;
      padding-left: 0.625rem;
      padding-right: 1.75rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 2rem;
      right: 0.5rem;
    }
    /* Ajuste para selects com classe select-sm */
    .select-sm + .select2-container--default .select2-selection--single,
    .select-sm + .select2-container--default .select2-selection--multiple {
      min-height: 2rem;
    }
    .select-sm + .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 2rem;
      padding-left: 0.625rem;
      padding-right: 1.75rem;
    }
    .select-sm + .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 2rem;
    }
    
    /* Fix para switch labels - mostrar apenas o texto do estado atual */
    .switch {
      width: auto !important;
      min-width: fit-content !important;
      max-width: none !important;
    }
    .switch .switch-label {
      min-width: auto !important;
      width: auto !important;
      max-width: none !important;
      overflow: visible !important;
      text-overflow: clip !important;
      white-space: nowrap !important;
      display: inline-block !important;
    }
    /* Esconde o texto inativo quando o switch está marcado */
    .switch:has(input[type="checkbox"]:checked) .switch-label-inactive,
    .switch:has(input[type="checkbox"][checked]) .switch-label-inactive {
      display: none !important;
    }
    /* Esconde o texto ativo quando o switch está desmarcado */
    .switch:has(input[type="checkbox"]:not(:checked)) .switch-label-active,
    .switch:has(input[type="checkbox"]:not([checked])) .switch-label-active {
      display: none !important;
    }
    /* Mostra o texto ativo quando o switch está marcado */
    .switch:has(input[type="checkbox"]:checked) .switch-label-active,
    .switch:has(input[type="checkbox"][checked]) .switch-label-active {
      display: inline-block !important;
    }
    /* Mostra o texto inativo quando o switch está desmarcado */
    .switch:has(input[type="checkbox"]:not(:checked)) .switch-label-inactive,
    .switch:has(input[type="checkbox"]:not([checked])) .switch-label-inactive {
      display: inline-block !important;
    }
    .switch-label-active,
    .switch-label-inactive {
      white-space: nowrap !important;
      overflow: visible !important;
      text-overflow: clip !important;
      min-width: fit-content !important;
      width: auto !important;
      max-width: none !important;
    }
    /* Por padrão, mostra inativo e esconde ativo */
    .switch-label-active {
      display: none !important;
    }
    .switch-label-inactive {
      display: inline-block !important;
    }
    /* Quando marcado, mostra ativo e esconde inativo */
    .switch:has(input[type="checkbox"]:checked) .switch-label-active,
    .switch:has(input[type="checkbox"][checked]) .switch-label-active {
      display: inline-block !important;
    }
    .switch:has(input[type="checkbox"]:checked) .switch-label-inactive,
    .switch:has(input[type="checkbox"][checked]) .switch-label-inactive {
      display: none !important;
    }
    /* Quando não marcado, mostra inativo e esconde ativo */
    .switch:has(input[type="checkbox"]:not(:checked)) .switch-label-active,
    .switch:has(input[type="checkbox"]:not([checked])) .switch-label-active {
      display: none !important;
    }
    .switch:has(input[type="checkbox"]:not(:checked)) .switch-label-inactive,
    .switch:has(input[type="checkbox"]:not([checked])) .switch-label-inactive {
      display: inline-block !important;
    }
  </style>
  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
 </head>
 <body class="antialiased flex h-full text-base text-gray-700 [--tw-page-bg:#F6F6F9] [--tw-page-bg-dark:var(--tw-coal-200)] [--tw-content-bg:var(--tw-light)] [--tw-content-bg-dark:var(--tw-coal-500)] [--tw-content-scrollbar-color:#e8e8e8] [--tw-header-height:60px] [--tw-sidebar-width:270px] bg-[--tw-page-bg] dark:bg-[--tw-page-bg-dark] lg:overflow-hidden">
  <!-- Theme Mode -->
  <script>
   const defaultThemeMode = 'light'; // light|dark|system
		let themeMode;

		if ( document.documentElement ) {
			if ( localStorage.getItem('theme')) {
					themeMode = localStorage.getItem('theme');
			} else if ( document.documentElement.hasAttribute('data-theme-mode')) {
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
  <!-- Base -->
  <div class="flex grow">
   <!-- Header -->
   <header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-[--tw-page-bg] dark:bg-[--tw-page-bg-dark] h-[--tw-header-height]" id="header">
    <!-- Container -->
    <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
     <a href="<?= base_url('Dashboard') ?>">
      <img class="min-h-[30px] max-h-[40px]" src="<?= base_url('assets/media/app/WhatsApp_Image_2025-11-24_at_11.22.48-removebg-preview.png') ?>"/>
     </a>
     <button class="btn btn-icon btn-light btn-clear btn-sm -me-2" data-drawer-toggle="#sidebar">
      <i class="ki-filled ki-menu">
      </i>
     </button>
    </div>
    <!-- End of Container -->
   </header>
   <!-- End of Header -->
   <!-- Sidebar -->
   <div class="fixed top-0 bottom-0 z-20 hidden lg:flex flex-col shrink-0 w-[--tw-sidebar-width] bg-[--tw-page-bg] dark:bg-[--tw-page-bg-dark]" data-drawer="true" data-drawer-class="drawer drawer-start flex top-0 bottom-0" data-drawer-enable="true|lg:false" id="sidebar">
    <!-- Sidebar Header -->
    <div id="sidebar_header">
     <div class="flex items-center gap-2.5 px-3.5 h-[70px]">
      <a href="<?= base_url('Dashboard') ?>">
       <img class="h-[42px] max-h-[50px]" src="<?= base_url('assets/media/app/WhatsApp_Image_2025-11-24_at_11.22.48-removebg-preview.png') ?>"/>
      </a>
      <div class="menu menu-default grow" data-menu="true">
       <div class="menu-item grow" data-menu-item-offset="0px, 15px" data-menu-item-placement="bottom-start" data-menu-item-toggle="dropdown" data-menu-item-trigger="hover">
        <div class="menu-label cursor-pointer text-gray-900 font-medium grow justify-between">
         <span class="text-base font-medium text-gray-900 grow justify-start">
          FinaMassa
         </span>
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
       <!-- Dashboard -->
       <div class="menu-item">
        <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Dashboard') ?>">
         <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
          <i class="ki-filled ki-home-3">
          </i>
         </span>
         <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
          Dashboard
         </span>
        </a>
       </div>
       
       <!-- Cadastros -->
       <div class="menu-item" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
        <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent">
         <span class="menu-icon items-start text-gray-600 text-lg menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
          <i class="ki-filled ki-notepad-edit">
          </i>
         </span>
         <span class="menu-title font-medium text-sm text-gray-800 menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
          Cadastros
         </span>
         <span class="menu-arrow text-gray-600 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
          <i class="ki-filled ki-down text-xs menu-item-show:hidden">
          </i>
          <i class="ki-filled ki-up text-xs hidden menu-item-show:inline-flex">
          </i>
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
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Produtos') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Produtos
           </span>
          </a>
         </div>
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('CategoriasProduto') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Categorias de Produtos
           </span>
          </a>
         </div>
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('IngredientesPadrao') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Ingredientes Padrão
           </span>
          </a>
         </div>
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Fornecedores') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Fornecedores
           </span>
          </a>
         </div>
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Depositos') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Depósitos
           </span>
          </a>
         </div>
        </div>
       </div>
       
       <!-- Estoque -->
       <div class="menu-item" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
        <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent">
         <span class="menu-icon items-start text-gray-600 text-lg menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
          <i class="ki-filled ki-archive">
          </i>
         </span>
         <span class="menu-title font-medium text-sm text-gray-800 menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
          Estoque
         </span>
         <span class="menu-arrow text-gray-600 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
          <i class="ki-filled ki-down text-xs menu-item-show:hidden">
          </i>
          <i class="ki-filled ki-up text-xs hidden menu-item-show:inline-flex">
          </i>
         </span>
        </div>
        <div class="menu-accordion gap-px ps-7">
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Estoque') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Controle de Estoque
           </span>
          </a>
         </div>
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Estoque/entrada') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Entrada
           </span>
          </a>
         </div>
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Estoque/saida') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Saída
           </span>
          </a>
         </div>
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Estoque/ajuste') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Ajuste
           </span>
          </a>
         </div>
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Estoque/historico') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Histórico
           </span>
          </a>
         </div>
        </div>
       </div>
       
       <!-- Pedidos/Vendas -->
       <div class="menu-item" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
        <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent">
         <span class="menu-icon items-start text-gray-600 text-lg menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
          <i class="ki-filled ki-handcart">
          </i>
         </span>
         <span class="menu-title font-medium text-sm text-gray-800 menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
          Pedidos de Venda
         </span>
         <span class="menu-arrow text-gray-600 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
          <i class="ki-filled ki-down text-xs menu-item-show:hidden">
          </i>
          <i class="ki-filled ki-up text-xs hidden menu-item-show:inline-flex">
          </i>
         </span>
        </div>
        <div class="menu-accordion gap-px ps-7">
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Pedidos') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Listar Pedidos
           </span>
          </a>
         </div>
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Pedidos/novo') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Novo Pedido
           </span>
          </a>
         </div>
        </div>
       </div>
       
       <!-- Relatórios -->
       <div class="menu-item" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
        <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-here:border-gray-200 menu-item-here:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200">
         <span class="menu-icon items-start text-gray-600 text-lg menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
          <i class="ki-filled ki-chart-line-up">
          </i>
         </span>
         <span class="menu-title font-medium text-sm text-gray-800 menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
          Relatórios
         </span>
         <span class="menu-arrow text-gray-600 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
          <i class="ki-filled ki-down text-xs menu-item-show:hidden">
          </i>
          <i class="ki-filled ki-up text-xs hidden menu-item-show:inline-flex">
          </i>
         </span>
        </div>
        <div class="menu-accordion gap-px ps-7">
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Relatorios/pedidos') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Relatório de Pedidos
           </span>
          </a>
         </div>
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Relatorios/estoque') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Relatório de Estoque
           </span>
          </a>
         </div>
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Relatorios/produtos') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Produtos Mais Vendidos
           </span>
          </a>
         </div>
        </div>
       </div>
       
       <!-- Configurações -->
       <div class="menu-item" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
        <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent">
         <span class="menu-icon items-start text-gray-600 text-lg menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
          <i class="ki-filled ki-setting-2">
          </i>
         </span>
         <span class="menu-title font-medium text-sm text-gray-800 menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
          Configurações
         </span>
         <span class="menu-arrow text-gray-600 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
          <i class="ki-filled ki-down text-xs menu-item-show:hidden">
          </i>
          <i class="ki-filled ki-up text-xs hidden menu-item-show:inline-flex">
          </i>
         </span>
        </div>
        <div class="menu-accordion gap-px ps-7">
         <div class="menu-item">
          <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Permissoes') ?>">
           <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
            Permissões
           </span>
          </a>
         </div>
        </div>
       </div>
      </div>
      <!-- End of Primary Menu -->
     </div>
    </div>
    <!-- End of Sidebar menu-->
    <!-- Footer -->
    <div id="sidebar_footer" class="px-3.5 py-3 border-t border-gray-200">
      <div class="menu-item">
        <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-link-hover:bg-light menu-link-hover:border-gray-200" href="<?= base_url('Login/sair') ?>">
          <span class="menu-icon items-start text-lg text-gray-600 menu-link-hover:text-gray-800">
            <i class="ki-filled ki-exit-down"></i>
          </span>
          <span class="menu-title text-sm text-gray-800 font-medium menu-link-hover:text-gray-900">
            Sair
          </span>
        </a>
      </div>
    </div>
    <!-- End of Footer -->
   </div>
   <!-- End of Sidebar -->
   <!-- Wrapper -->
   <div class="flex flex-col lg:flex-row grow pt-[--tw-header-height] lg:pt-0">
    <!-- Main -->
    <div class="flex flex-col grow items-stretch rounded-xl bg-[--tw-content-bg] dark:bg-[--tw-content-bg-dark] border border-gray-300 dark:border-gray-200 lg:ms-[--tw-sidebar-width] mt-0 lg:mt-[15px] m-[15px]">
     <div class="flex flex-col grow lg:scrollable-y-auto lg:[scrollbar-width:auto] lg:light:[--tw-scrollbar-thumb-color:var(--tw-content-scrollbar-color)] pt-5" id="scrollable_content">
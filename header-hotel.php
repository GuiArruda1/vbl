<?php
/**
 * Header Template — Hotel Microsite (Vila Baleira Porto Santo)
 * Header transparente com navegação do hotel e seletores.
 *
 * @package Vila_Baleira
 */

// Identifica o Hotel Pai associado (quer estejamos na Home do hotel, num quarto ou numa subpágina)
$current_hotel_id = get_the_ID();
global $post;
if (is_singular('vbl_quarto')) {
  $parent_hotel = vbl_field('vbl_quarto_hotel', get_the_ID());
  if ($parent_hotel) {
    $current_hotel_id = is_object($parent_hotel) ? $parent_hotel->ID : (int) $parent_hotel;
  }
} elseif (!empty($post->post_parent)) {
  $current_hotel_id = $post->post_parent;
}

$hotel_name = vbl_field('vbl_hotel_name', $current_hotel_id, 'Porto Santo');
$hotel_booking_url = vbl_field('vbl_hotel_booking_url', $current_hotel_id, '#');
$hotel_permalink = $current_hotel_id ? get_permalink($current_hotel_id) : get_permalink();
$hotel_header_logo = vbl_field('vbl_hotel_header_logo', get_the_ID()) ?: vbl_field('vbl_hotel_header_logo', $current_hotel_id);
$hotel_header_logo_scrolled = vbl_field('vbl_hotel_header_logo_scrolled', get_the_ID()) ?: vbl_field('vbl_hotel_header_logo_scrolled', $current_hotel_id);
$lang_data = function_exists('vbl_get_languages') ? vbl_get_languages() : array('current' => 'PT', 'languages' => array());

// Subpáginas reais do hotel criadas no WordPress (sem fallback para forçar a criação das páginas no backoffice)
$subpage_links = array();

$child_pages = get_children(array(
  'post_parent' => $current_hotel_id,
  'post_type' => 'page',
  'post_status' => 'publish',
));

if (!empty($child_pages)) {
  foreach ($child_pages as $cp) {
    $tpl = get_page_template_slug($cp->ID);
    if (strpos($tpl, 'hotel-sobre') !== false) {
      $subpage_links['sobre'] = array('url' => get_permalink($cp->ID), 'title' => get_the_title($cp->ID) ?: 'O Hotel', 'id' => $cp->ID);
    } elseif (strpos($tpl, 'hotel-rooms') !== false) {
      $subpage_links['rooms'] = array('url' => get_permalink($cp->ID), 'title' => get_the_title($cp->ID) ?: 'Rooms & Suites', 'id' => $cp->ID);
    } elseif (strpos($tpl, 'hotel-atividades') !== false) {
      $subpage_links['atividades'] = array('url' => get_permalink($cp->ID), 'title' => get_the_title($cp->ID) ?: 'Atividades', 'id' => $cp->ID);
    } elseif (strpos($tpl, 'hotel-restaurantes') !== false) {
      $subpage_links['restaurantes'] = array('url' => get_permalink($cp->ID), 'title' => get_the_title($cp->ID) ?: 'Restaurantes', 'id' => $cp->ID);
    } elseif (strpos($tpl, 'hotel-regiao') !== false) {
      $subpage_links['regiao'] = array('url' => get_permalink($cp->ID), 'title' => get_the_title($cp->ID) ?: 'Região', 'id' => $cp->ID);
    } elseif (strpos($tpl, 'hotel-eventos') !== false) {
      $subpage_links['eventos'] = array('url' => get_permalink($cp->ID), 'title' => get_the_title($cp->ID) ?: 'Eventos & Salas', 'id' => $cp->ID);
    } elseif (strpos($tpl, 'hotel-contactos') !== false) {
      $subpage_links['contactos'] = array('url' => get_permalink($cp->ID), 'title' => get_the_title($cp->ID) ?: 'Contactos', 'id' => $cp->ID);
    }
  }
}

// Active page state detection
$current_page_id = get_the_ID();
$current_template = (string) get_page_template_slug($current_page_id);

$is_sobre_active        = (strpos($current_template, 'hotel-sobre') !== false) || (isset($subpage_links['sobre']['id']) && $current_page_id === $subpage_links['sobre']['id']);
$is_rooms_active        = (strpos($current_template, 'hotel-rooms') !== false) || is_singular('vbl_quarto') || (isset($subpage_links['rooms']['id']) && $current_page_id === $subpage_links['rooms']['id']);
$is_atividades_active   = (strpos($current_template, 'hotel-atividades') !== false) || (isset($subpage_links['atividades']['id']) && $current_page_id === $subpage_links['atividades']['id']);
$is_restaurantes_active = (strpos($current_template, 'hotel-restaurantes') !== false) || (isset($subpage_links['restaurantes']['id']) && $current_page_id === $subpage_links['restaurantes']['id']);
$is_regiao_active       = (strpos($current_template, 'hotel-regiao') !== false) || (isset($subpage_links['regiao']['id']) && $current_page_id === $subpage_links['regiao']['id']);
$is_eventos_active      = (strpos($current_template, 'hotel-eventos') !== false) || (isset($subpage_links['eventos']['id']) && $current_page_id === $subpage_links['eventos']['id']);
$is_contactos_active    = (strpos($current_template, 'hotel-contactos') !== false) || (isset($subpage_links['contactos']['id']) && $current_page_id === $subpage_links['contactos']['id']);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body <?php body_class("font-display text-white bg-black overflow-x-hidden"); ?>>
  <?php wp_body_open(); ?>

  <!-- ====== HEADER DO HOTEL (TRANSPARENTE -> BRANCO NO SCROLL) ====== -->
  <header id="headerHotel"
    class="font-body fixed top-0 left-0 w-full z-50 bg-gradient-to-b from-black/60 via-black/30 to-transparent transition-all duration-300">
    <div
      class="max-w-[1920px] mx-auto h-20 xl:h-24 flex items-center justify-between px-6 xl:px-[8.33%] transition-all duration-300">

      <!-- Grupo Esquerda: Logo + Menu Principal (Menu perto do logotipo) -->
      <div class="flex items-center gap-8 2xl:gap-14 min-w-0">
        <!-- Logo do Hotel -->
        <a href="<?php echo esc_url($hotel_permalink); ?>" class="flex items-center gap-3 group flex-shrink-0">
          <?php if (!empty($hotel_header_logo)): ?>
            <img src="<?php echo esc_url($hotel_header_logo); ?>" alt="<?php echo esc_attr($hotel_name); ?>"
              class="h-8 xl:h-10 w-auto max-w-[220px] object-contain transition-all duration-300 <?php echo !empty($hotel_header_logo_scrolled) ? 'vbl-logo-transparent' : 'vbl-hotel-logo-img filter brightness-0 invert'; ?>">
            <?php if (!empty($hotel_header_logo_scrolled)): ?>
              <img src="<?php echo esc_url($hotel_header_logo_scrolled); ?>" alt="<?php echo esc_attr($hotel_name); ?>"
                class="h-8 xl:h-10 w-auto max-w-[220px] object-contain transition-all duration-300 hidden vbl-logo-scrolled">
            <?php endif; ?>
          <?php else: ?>
            <img src="<?php echo vbl_img('logo-top.svg'); ?>" alt="Vila Baleira"
              class="h-8 xl:h-9 w-auto vbl-hotel-logo-img filter brightness-0 invert transition-all duration-300">
            <div class="flex flex-col border-l border-white/30 pl-3 vbl-hotel-logo-text transition-colors duration-300">
              <span
                class="font-display text-[16px] xl:text-[18px] tracking-[1.5px] uppercase leading-tight text-white transition-colors duration-300">VILA
                BALEIRA</span>
              <span
                class="font-body text-[10px] xl:text-[11px] tracking-[2px] uppercase text-white/80 font-light transition-colors duration-300"><?php echo esc_html($hotel_name); ?></span>
            </div>
          <?php endif; ?>
        </a>

        <!-- Menu Principal do Hotel (Desktop — renderiza apenas páginas reais criadas no WordPress) -->
        <nav class="hidden xl:flex items-center gap-5 2xl:gap-7">
          <?php if (!empty($subpage_links['sobre'])): ?>
            <a href="<?php echo esc_url($subpage_links['sobre']['url']); ?>"
              class="vbl-hotel-nav-link <?php echo $is_sobre_active ? 'is-active ' : ''; ?>text-[12px] 2xl:text-[13px] tracking-[1.2px] 2xl:tracking-[1.3px] uppercase text-white transition-colors whitespace-nowrap"><?php echo esc_html($subpage_links['sobre']['title']); ?></a>
          <?php endif; ?>

          <?php if (!empty($subpage_links['rooms'])): ?>
            <!-- Dropdown Rooms & Suites (Mega Menu) -->
            <div class="group flex items-center h-[80px] xl:h-[96px]">
              <a href="<?php echo esc_url($subpage_links['rooms']['url']); ?>"
                class="vbl-hotel-nav-link <?php echo $is_rooms_active ? 'is-active ' : ''; ?>text-[12px] 2xl:text-[13px] tracking-[1.2px] 2xl:tracking-[1.3px] uppercase text-white transition-colors flex items-center gap-1.5 py-2 relative h-full whitespace-nowrap">
                <span><?php echo esc_html($subpage_links['rooms']['title']); ?></span>
                <svg class="w-2.5 h-1.5 text-current group-hover:rotate-180 transition-transform duration-300"
                  viewBox="0 0 10 6" fill="none">
                  <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                </svg>
              </a>

              <!-- Mega Menu Container -->
              <div
                class="fixed top-[80px] xl:top-[96px] left-0 w-full bg-white shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 border-t border-black/10 z-40">
                <div class="max-w-[1920px] mx-auto w-full px-6 xl:px-[8.33%] py-12 relative">

                  <!-- Slider Arrows -->
                  <button
                    class="absolute left-6 xl:left-[4%] top-[40%] z-10 w-10 h-10 border border-[#00B5B4] text-[#00B5B4] bg-transparent flex items-center justify-center hover:bg-[#00B5B4] hover:text-white transition-colors cursor-pointer"
                    aria-label="Anterior">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 30 30" fill="none" stroke="currentColor"
                      stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M28 15H1M1 15L14 2M1 15L14 28" />
                    </svg>
                  </button>

                  <button
                    class="absolute right-6 xl:right-[4%] top-[40%] z-10 w-10 h-10 border border-[#00B5B4] text-[#00B5B4] bg-transparent flex items-center justify-center hover:bg-[#00B5B4] hover:text-white transition-colors cursor-pointer"
                    aria-label="Seguinte">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 30 30" fill="none" stroke="currentColor"
                      stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M1 15H28M28 15L15 2M28 15L15 28" />
                    </svg>
                  </button>

                  <!-- Rooms Grid (Apenas quartos reais associados ao hotel) -->
                  <div class="grid grid-cols-5 gap-6">
                    <?php
                    $mega_rooms_posts = get_posts(array(
                      'post_type' => 'vbl_quarto',
                      'posts_per_page' => 5,
                      'meta_query' => array(
                        array(
                          'key' => 'vbl_quarto_hotel',
                          'value' => $current_hotel_id,
                          'compare' => '=',
                        ),
                      ),
                    ));

                    if (!empty($mega_rooms_posts)):
                      foreach ($mega_rooms_posts as $rpost):
                        $rimg = get_the_post_thumbnail_url($rpost->ID, 'medium');
                        if (empty($rimg)) {
                          $rimg = vbl_img('hoteis/porto-santo-520x400.jpg');
                        }
                        ?>
                        <a href="<?php echo esc_url(get_permalink($rpost->ID)); ?>" class="group/room flex flex-col gap-4">
                          <div class="w-full aspect-[4/3] overflow-hidden bg-gray-100">
                            <img src="<?php echo esc_url($rimg); ?>"
                              alt="<?php echo esc_attr(get_the_title($rpost->ID)); ?>"
                              class="w-full h-full object-cover group-hover/room:scale-105 transition-transform duration-500 opacity-90 group-hover/room:opacity-100">
                          </div>
                          <div class="flex flex-col gap-1.5 mt-2">
                            <div class="flex items-center gap-3">
                              <div class="w-6 h-px bg-[#0da9a6]"></div>
                              <span class="font-body text-[8px] tracking-[1.5px] uppercase text-[#0da9a6]">QUARTO</span>
                            </div>
                            <h3
                              class="font-display text-[15px] xl:text-[16px] text-[#0d5257] uppercase leading-tight group-hover/room:text-[#0da9a6] transition-colors">
                              <?php echo esc_html(get_the_title($rpost->ID)); ?>
                            </h3>
                          </div>
                        </a>
                      <?php endforeach;
                    endif; ?>
                  </div>

                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php if (!empty($subpage_links['atividades'])): ?>
            <a href="<?php echo esc_url($subpage_links['atividades']['url']); ?>"
              class="vbl-hotel-nav-link <?php echo $is_atividades_active ? 'is-active ' : ''; ?>text-[12px] 2xl:text-[13px] tracking-[1.2px] 2xl:tracking-[1.3px] uppercase text-white transition-colors whitespace-nowrap"><?php echo esc_html($subpage_links['atividades']['title']); ?></a>
          <?php endif; ?>

          <?php if (!empty($subpage_links['restaurantes'])): ?>
            <a href="<?php echo esc_url($subpage_links['restaurantes']['url']); ?>"
              class="vbl-hotel-nav-link <?php echo $is_restaurantes_active ? 'is-active ' : ''; ?>text-[12px] 2xl:text-[13px] tracking-[1.2px] 2xl:tracking-[1.3px] uppercase text-white transition-colors whitespace-nowrap"><?php echo esc_html($subpage_links['restaurantes']['title']); ?></a>
          <?php endif; ?>

          <?php if (!empty($subpage_links['regiao'])): ?>
            <a href="<?php echo esc_url($subpage_links['regiao']['url']); ?>"
              class="vbl-hotel-nav-link <?php echo $is_regiao_active ? 'is-active ' : ''; ?>text-[12px] 2xl:text-[13px] tracking-[1.2px] 2xl:tracking-[1.3px] uppercase text-white transition-colors whitespace-nowrap"><?php echo esc_html($subpage_links['regiao']['title']); ?></a>
          <?php endif; ?>

          <?php if (!empty($subpage_links['eventos'])): ?>
            <a href="<?php echo esc_url($subpage_links['eventos']['url']); ?>"
              class="vbl-hotel-nav-link <?php echo $is_eventos_active ? 'is-active ' : ''; ?>text-[12px] 2xl:text-[13px] tracking-[1.2px] 2xl:tracking-[1.3px] uppercase text-white transition-colors whitespace-nowrap"><?php echo esc_html($subpage_links['eventos']['title']); ?></a>
          <?php endif; ?>

          <?php if (!empty($subpage_links['contactos'])): ?>
            <a href="<?php echo esc_url($subpage_links['contactos']['url']); ?>"
              class="vbl-hotel-nav-link <?php echo $is_contactos_active ? 'is-active ' : ''; ?>text-[12px] 2xl:text-[13px] tracking-[1.2px] 2xl:tracking-[1.3px] uppercase text-white transition-colors whitespace-nowrap"><?php echo esc_html($subpage_links['contactos']['title']); ?></a>
          <?php endif; ?>
        </nav>
      </div>

      <!-- Ações à Direita (Hotéis, Idioma, Reservar) -->
      <div class="hidden xl:flex items-center gap-6 2xl:gap-8 flex-shrink-0">
        <!-- Hotéis dropdown trigger (Abre Megamenu igual ao institucional) -->
        <button type="button"
          class="vbl-hotels-trigger text-[13px] tracking-[1.3px] uppercase text-white hover:text-white transition-colors flex items-center gap-1.5 cursor-pointer"
          data-mega-trigger data-mega-top="80">
          <span>Hotéis</span>
          <svg class="vbl-dropdown-arrow w-2.5 h-1.5 text-current transition-transform duration-300" viewBox="0 0 10 6" fill="none">
            <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
          </svg>
        </button>

        <!-- Selector Idioma Desktop -->
        <div class="relative group h-full flex items-center">
          <button type="button"
            class="vbl-lang-trigger text-[13px] tracking-[1.3px] uppercase text-white hover:text-white transition-colors flex items-center gap-1.5 cursor-pointer py-2">
            <span><?php echo esc_html($lang_data['current']); ?></span>
            <svg class="w-2.5 h-1.5 text-current group-hover:rotate-180 transition-transform duration-300"
              viewBox="0 0 10 6" fill="none">
              <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
            </svg>
          </button>
          <div
            class="absolute top-full right-0 hidden group-hover:flex flex-col bg-white border border-[#eee8e5] py-2 min-w-[90px] shadow-xl text-[#0d5257] z-50">
            <?php foreach ($lang_data['languages'] as $lang_item): ?>
              <a href="<?php echo esc_url($lang_item['url']); ?>"
                class="px-4 py-1.5 text-[12px] uppercase <?php echo !empty($lang_item['is_current']) ? 'font-bold text-[#0da9a6]' : 'text-[#0d5257] hover:text-[#0da9a6] hover:bg-[#f8f6f4]'; ?> transition-colors">
                <?php echo esc_html($lang_item['code']); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Botão Reservar -->
        <a href="<?php echo esc_url($hotel_booking_url); ?>"
          class="vbl-hotel-btn-reservar group inline-flex items-center border border-white text-white text-[13px] tracking-[1.4px] uppercase px-5 py-2 hover:bg-[#0da9a6] hover:border-[#0da9a6] hover:text-white transition-all duration-300">
          <span class="mr-3">Reservar</span>
          <svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45" viewBox="0 0 11 11"
            fill="none">
            <path
              d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z"
              fill="currentColor" />
          </svg>
        </a>
      </div>

      <!-- Mobile & Tablet Controls -->
      <div class="xl:hidden flex items-center gap-3">
        <!-- Language Switcher Mobile Header -->
        <div class="relative group">
          <button type="button" class="text-[12px] sm:text-[14px] tracking-[1.4px] uppercase text-white flex items-center gap-1.5 cursor-pointer vbl-hotel-lang-mobile">
            <span><?php echo esc_html($lang_data['current']); ?></span>
            <svg class="w-2 h-1 group-hover:rotate-180 transition-transform duration-300 text-white" viewBox="0 0 8 4" fill="none" aria-hidden="true"><path d="M1 0.5L4 3.5L7 0.5" stroke="currentColor" stroke-width="1.2"/></svg>
          </button>
          <div class="absolute top-full right-0 hidden group-hover:flex flex-col bg-white border border-[#eee8e5] py-2 min-w-[80px] shadow-xl text-[#0d5257] z-50">
            <?php foreach ($lang_data['languages'] as $lang_item): ?>
              <a href="<?php echo esc_url($lang_item['url']); ?>" class="px-4 py-1.5 text-[12px] uppercase <?php echo !empty($lang_item['is_current']) ? 'font-bold text-[#0da9a6]' : 'text-[#0d5257] hover:text-[#0da9a6] hover:bg-[#f8f6f4]'; ?> transition-colors">
                <?php echo esc_html($lang_item['code']); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <a href="<?php echo esc_url($hotel_booking_url); ?>"
          class="vbl-hotel-btn-reservar group hidden [@media(min-width:426px)_and_(max-width:1279px)]:flex items-center gap-2 sm:gap-4 px-3 py-1.5 sm:px-4 sm:py-2 border border-white text-white text-[12px] sm:text-[14px] tracking-[1.2px] sm:tracking-[1.4px] uppercase transition-all duration-300">
          <span>Reservar</span>
          <svg class="w-[9px] h-[9px] sm:w-[10px] sm:h-[10px] transition-transform duration-300 group-hover:rotate-45"
            viewBox="0 0 11 11" fill="none" aria-hidden="true">
            <path
              d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z"
              fill="currentColor" />
          </svg>
        </a>
        <button class="w-8 h-8 flex flex-col items-center justify-center gap-1.5 menu-toggle cursor-pointer"
          aria-label="Abrir Menu">
          <span class="w-6 h-px bg-white transition-colors duration-300"></span>
          <span class="w-6 h-px bg-white transition-colors duration-300"></span>
          <span class="w-4 h-px bg-white self-end transition-colors duration-300"></span>
        </button>
      </div>

    </div>
  </header>

  <!-- ====== MEGA MENU — Dropdown Hotéis ====== -->
  <div id="megaMenu"
    class="vbl-mega-menu hidden xl:block fixed border-b border-[#eee8e5] max-h-0 overflow-hidden opacity-0 transition-all duration-[400ms] ease-in-out"
    style="background:#ffffff;z-index:45">
    <div
      class="max-w-[1920px] mx-auto px-[clamp(24px,3.33vw,64px)] py-[clamp(32px,2.92vw,56px)] flex items-start justify-between gap-[clamp(16px,1.25vw,24px)]">
      <?php
      $mega_hotels = function_exists('vbl_get_mega_menu_hotels') ? vbl_get_mega_menu_hotels() : array();
      foreach ($mega_hotels as $hotel):
        ?>
        <a href="<?php echo esc_url($hotel['url']); ?>"
          class="vbl-hotel-card group flex flex-col gap-6 min-w-0 flex-1 max-w-[320px]">
          <div class="w-full h-[200px] overflow-hidden bg-gray-100">
            <img src="<?php echo esc_url($hotel['img']); ?>" alt="<?php echo esc_attr($hotel['name']); ?>"
              class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
          </div>
          <div class="flex flex-col gap-2">
            <div class="flex items-center gap-4">
              <div class="w-10 [@media(min-width:1180px)_and_(max-width:1333px)]:w-6 h-px"
                style="background-color: <?php echo esc_attr($hotel['color']); ?>;"></div>
              <span
                class="font-body text-[10px] tracking-[1px] uppercase text-[#0d5257]"><?php echo esc_html($hotel['tagline']); ?></span>
            </div>
            <p class="font-display text-[20px] text-[#0d5257] group-hover:opacity-80 uppercase transition-all">
              <?php echo esc_html($hotel['name']); ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- ====== MOBILE MENU OVERLAY ====== -->
  <div id="mobileMenu"
    class="fixed inset-0 z-[60] bg-white translate-x-full transition-transform duration-300 ease-out xl:hidden overflow-y-auto">
    <div class="flex items-center justify-between px-6 h-20">
      <a href="<?php echo esc_url($hotel_permalink); ?>" class="flex items-center gap-3">
        <?php if (!empty($hotel_header_logo_scrolled)): ?>
          <img src="<?php echo esc_url($hotel_header_logo_scrolled); ?>" alt="<?php echo esc_attr($hotel_name); ?>"
            class="h-7 w-auto max-w-[160px] object-contain">
        <?php elseif (!empty($hotel_header_logo)): ?>
          <img src="<?php echo esc_url($hotel_header_logo); ?>" alt="<?php echo esc_attr($hotel_name); ?>"
            class="h-7 w-auto max-w-[160px] object-contain">
        <?php else: ?>
          <img src="<?php echo vbl_img('logo-top.svg'); ?>" alt="Vila Baleira" class="h-7 w-auto">
          <div class="flex flex-col border-l border-black/20 pl-3">
            <span class="font-display text-[15px] tracking-[1.5px] uppercase leading-tight text-[#0d5257]">VILA
              BALEIRA</span>
            <span
              class="font-body text-[9px] tracking-[1.5px] uppercase text-[#0d5257]/80 font-light"><?php echo esc_html($hotel_name); ?></span>
          </div>
        <?php endif; ?>
      </a>
      <button id="menuClose" class="w-8 h-8 flex items-center justify-center" aria-label="Fechar menu">
        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="none">
          <line x1="2" y1="2" x2="18" y2="18" stroke="#0d5257" stroke-width="1.5" />
          <line x1="18" y1="2" x2="2" y2="18" stroke="#0d5257" stroke-width="1.5" />
        </svg>
      </button>
    </div>
    <nav class="font-body flex flex-col px-6 pt-8 gap-6" id="mobileNav">
      <!-- Hotéis accordion trigger -->
      <button type="button" id="mobileHotelToggle"
        class="flex items-center justify-between w-full text-left cursor-pointer">
        <span
          class="text-[16px] tracking-[1.6px] uppercase text-[#0d5257] font-medium pointer-events-none">Hotéis</span>
        <svg class="w-3 h-2 text-[#0d5257] transition-transform duration-300 pointer-events-none" id="mobileHotelArrow"
          viewBox="0 0 12 8" fill="none">
          <path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.5" />
        </svg>
      </button>
      <!-- Hotel Discovery Cards (accordion content) -->
      <div id="mobileHotelCards" class="overflow-hidden">
        <div class="flex flex-col gap-3 pb-2">
          <?php
          $mobile_hotels = function_exists('vbl_get_mega_menu_hotels') ? vbl_get_mega_menu_hotels() : array();
          foreach ($mobile_hotels as $h): ?>
            <a href="<?php echo esc_url($h['url']); ?>" class="flex items-center gap-4 rounded-sm overflow-hidden group"
              style="min-height:72px">
              <div class="w-[100px] md:w-[120px] h-[72px] flex-shrink-0 overflow-hidden bg-gray-100">
                <img src="<?php echo esc_url($h['img']); ?>" alt="<?php echo esc_attr($h['name']); ?>"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
              </div>
              <div class="flex flex-col gap-2 py-3 pr-4 min-w-0">
                <div class="flex items-center gap-4">
                  <div class="w-8 [@media(min-width:1180px)_and_(max-width:1333px)]:w-6 h-px"
                    style="background-color: <?php echo esc_attr($h['color']); ?>;"></div>
                  <span
                    class="font-body text-[9px] md:text-[10px] tracking-[0.8px] uppercase text-[#0d5257] font-normal"><?php echo esc_html($h['tagline']); ?></span>
                </div>
                <span
                  class="font-display text-[15px] md:text-[17px] text-[#0d5257] uppercase leading-tight"><?php echo esc_html($h['name']); ?></span>
              </div>
            </a>
          <?php endforeach; ?>
          <a href="<?php echo esc_url(home_url('/hoteis')); ?>"
            class="flex items-center justify-center gap-3 py-3 mt-1 border-t border-[#eee8e5]">
            <span class="text-[12px] tracking-[1.2px] uppercase text-[#bc945b] font-medium">Ver todos os hotéis</span>
            <svg class="w-[8px] h-[8px] text-[#bc945b]" viewBox="0 0 11 11" fill="none">
              <path d="M1 10L10 1M10 1H3M10 1V8" stroke="currentColor" stroke-width="1" />
            </svg>
          </a>
        </div>
      </div>
      <?php if (!empty($subpage_links['sobre'])): ?>
        <a href="<?php echo esc_url($subpage_links['sobre']['url']); ?>"
          class="text-[16px] tracking-[1.6px] uppercase <?php echo $is_sobre_active ? 'text-[#0da9a6] font-bold underline underline-offset-4' : 'text-[#0d5257] font-medium'; ?>"><?php echo esc_html($subpage_links['sobre']['title']); ?></a>
      <?php endif; ?>
      <?php if (!empty($subpage_links['rooms'])): ?>
        <!-- Rooms & Suites accordion trigger -->
        <button type="button" id="mobileRoomsToggle"
          class="flex items-center justify-between w-full text-left cursor-pointer">
          <span
            class="text-[16px] tracking-[1.6px] uppercase <?php echo $is_rooms_active ? 'text-[#0da9a6] font-bold underline underline-offset-4' : 'text-[#0d5257] font-medium'; ?> pointer-events-none"><?php echo esc_html($subpage_links['rooms']['title']); ?></span>
          <svg class="w-3 h-2 text-[#0d5257] transition-transform duration-300 pointer-events-none" id="mobileRoomsArrow"
            viewBox="0 0 12 8" fill="none">
            <path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.5" />
          </svg>
        </button>
        <!-- Rooms Discovery Cards (accordion content) -->
        <div id="mobileRoomsCards" class="overflow-hidden">
          <div class="flex flex-col gap-3 pb-2">
            <?php
            $mobile_rooms_posts = get_posts(array(
              'post_type' => 'vbl_quarto',
              'posts_per_page' => 6,
              'meta_query' => array(
                array(
                  'key' => 'vbl_quarto_hotel',
                  'value' => $current_hotel_id,
                  'compare' => '=',
                ),
              ),
            ));

            if (!empty($mobile_rooms_posts)):
              foreach ($mobile_rooms_posts as $mr_post):
                $mr_img = get_the_post_thumbnail_url($mr_post->ID, 'medium');
                if (empty($mr_img)) {
                  $mr_img = vbl_img('hoteis/porto-santo-520x400.jpg');
                }
                ?>
                <a href="<?php echo esc_url(get_permalink($mr_post->ID)); ?>"
                  class="flex items-center gap-4 rounded-sm overflow-hidden group" style="min-height:72px">
                  <div class="w-[100px] md:w-[120px] h-[72px] flex-shrink-0 overflow-hidden bg-gray-100">
                    <img src="<?php echo esc_url($mr_img); ?>"
                      alt="<?php echo esc_attr(get_the_title($mr_post->ID)); ?>"
                      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                  </div>
                  <div class="flex flex-col gap-1.5 py-3 pr-4 min-w-0">
                    <div class="flex items-center gap-3">
                      <div class="w-6 h-px bg-[#0da9a6]"></div>
                      <span
                        class="font-body text-[8px] md:text-[9px] tracking-[1px] uppercase text-[#0da9a6] font-normal">QUARTO</span>
                    </div>
                    <span
                      class="font-display text-[15px] md:text-[17px] text-[#0d5257] uppercase leading-tight"><?php echo esc_html(get_the_title($mr_post->ID)); ?></span>
                  </div>
                </a>
              <?php endforeach;
            endif; ?>
            <a href="<?php echo esc_url($subpage_links['rooms']['url']); ?>"
              class="flex items-center justify-center gap-3 py-3 mt-1 border-t border-[#eee8e5]">
              <span class="text-[12px] tracking-[1.2px] uppercase text-[#0da9a6] font-medium">Ver todos os quartos</span>
              <svg class="w-[8px] h-[8px] text-[#0da9a6]" viewBox="0 0 11 11" fill="none">
                <path d="M1 10L10 1M10 1H3M10 1V8" stroke="currentColor" stroke-width="1" />
              </svg>
            </a>
          </div>
        </div>
      <?php endif; ?>
      <?php if (!empty($subpage_links['atividades'])): ?>
        <a href="<?php echo esc_url($subpage_links['atividades']['url']); ?>"
          class="text-[16px] tracking-[1.6px] uppercase <?php echo $is_atividades_active ? 'text-[#0da9a6] font-bold underline underline-offset-4' : 'text-[#0d5257] font-medium'; ?>"><?php echo esc_html($subpage_links['atividades']['title']); ?></a>
      <?php endif; ?>
      <?php if (!empty($subpage_links['restaurantes'])): ?>
        <a href="<?php echo esc_url($subpage_links['restaurantes']['url']); ?>"
          class="text-[16px] tracking-[1.6px] uppercase <?php echo $is_restaurantes_active ? 'text-[#0da9a6] font-bold underline underline-offset-4' : 'text-[#0d5257] font-medium'; ?>"><?php echo esc_html($subpage_links['restaurantes']['title']); ?></a>
      <?php endif; ?>
      <?php if (!empty($subpage_links['regiao'])): ?>
        <a href="<?php echo esc_url($subpage_links['regiao']['url']); ?>"
          class="text-[16px] tracking-[1.6px] uppercase <?php echo $is_regiao_active ? 'text-[#0da9a6] font-bold underline underline-offset-4' : 'text-[#0d5257] font-medium'; ?>"><?php echo esc_html($subpage_links['regiao']['title']); ?></a>
      <?php endif; ?>
      <?php if (!empty($subpage_links['eventos'])): ?>
        <a href="<?php echo esc_url($subpage_links['eventos']['url']); ?>"
          class="text-[16px] tracking-[1.6px] uppercase <?php echo $is_eventos_active ? 'text-[#0da9a6] font-bold underline underline-offset-4' : 'text-[#0d5257] font-medium'; ?>"><?php echo esc_html($subpage_links['eventos']['title']); ?></a>
      <?php endif; ?>
      <?php if (!empty($subpage_links['contactos'])): ?>
        <a href="<?php echo esc_url($subpage_links['contactos']['url']); ?>"
          class="text-[16px] tracking-[1.6px] uppercase <?php echo $is_contactos_active ? 'text-[#0da9a6] font-bold underline underline-offset-4' : 'text-[#0d5257] font-medium'; ?>"><?php echo esc_html($subpage_links['contactos']['title']); ?></a>
      <?php endif; ?>
      <div class="h-px bg-[#eee8e5] my-2"></div>
      <a href="<?php echo esc_url($hotel_booking_url); ?>"
        class="vbl-hotel-btn-reservar flex items-center justify-between w-full px-6 py-4 border border-[#0da9a6] text-[#0da9a6] text-[14px] tracking-[1.4px] uppercase my-2">
        <span>Reservar</span>
        <svg class="w-[10px] h-[10px]" viewBox="0 0 11 11" fill="none">
          <path d="M1 10L10 1M10 1H3M10 1V8" stroke="currentColor" stroke-width="1.2" />
        </svg>
      </a>
      <!-- Mobile Menu Languages -->
      <div class="flex items-center gap-4 pt-2">
        <span class="text-[12px] uppercase text-[#0d5257]/60">Idioma:</span>
        <div class="flex items-center gap-3">
          <?php foreach ($lang_data['languages'] as $lang_item): ?>
            <a href="<?php echo esc_url($lang_item['url']); ?>"
              class="text-[13px] uppercase <?php echo !empty($lang_item['is_current']) ? 'font-bold text-[#0da9a6] border-b-2 border-[#0da9a6]' : 'text-[#0d5257] hover:text-[#0da9a6]'; ?> transition-colors pb-0.5">
              <?php echo esc_html($lang_item['code']); ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </nav>
  </div>
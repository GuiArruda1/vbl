<?php
/**
 * Header Template — Vila Baleira
 * Inclui: header fixo, mega menu hotéis, mobile menu, header transparente
 *
 * @package Vila_Baleira
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class( "font-display text-black bg-white overflow-x-hidden" ); ?>>
<?php wp_body_open(); ?>

<?php
/**
 * Header visibility logic:
 * - Homepage (front_page) + Gift Card: header starts HIDDEN, appears on scroll
 * - ALL other pages: header is VISIBLE from the start
 */
$is_banner_page = is_front_page() || is_page_template( 'page-templates/page-giftcard.php' );
$header_initial = $is_banner_page ? 'opacity-0 pointer-events-none' : 'opacity-100';
$lang_data      = function_exists( 'vbl_get_languages' ) ? vbl_get_languages() : array( 'current' => 'PT', 'languages' => array() );
?>

  <!-- ====== HEADER FIXO ====== -->
  <header id="headerFixo" class="font-body fixed top-0 left-0 w-full h-20 z-50 bg-white border-b border-[#eee8e5] <?php echo $header_initial; ?> transition-opacity duration-300">
    <div class="max-w-[1920px] mx-auto h-full flex items-center justify-between px-6 xl:px-10">
      <nav class="hidden xl:flex items-center gap-6 xl:gap-10">
        <?php vbl_header_nav_items( 'fixo' ); ?>
      </nav>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center gap-1">
        <img src="<?php echo vbl_img( 'logo-top.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-7 xl:h-8 w-auto">
        <img src="<?php echo vbl_img( 'logo-bottom.svg' ); ?>" alt="" class="h-[8px] xl:h-[12px] w-auto">
      </a>
      <div class="hidden xl:flex items-center gap-6 xl:gap-10">
        <a href="<?php echo esc_url( home_url( '/contactos' ) ); ?>" class="vbl-nav-link text-[14px] tracking-[1.4px] uppercase text-[#0d5257]">Contactos</a>
        
        <!-- Language Switcher Desktop -->
        <div class="relative group">
          <button type="button" class="vbl-nav-link text-[14px] tracking-[1.4px] uppercase text-[#0d5257] flex items-center gap-2 cursor-pointer">
            <span><?php echo esc_html( $lang_data['current'] ); ?></span>
            <svg class="w-2 h-1 group-hover:rotate-180 transition-transform duration-300" viewBox="0 0 8 4" fill="none"><path d="M1 0.5L4 3.5L7 0.5" stroke="currentColor" stroke-width="1.2"/></svg>
          </button>
          <div class="absolute top-full right-0 hidden group-hover:flex flex-col bg-white border border-[#eee8e5] py-2 min-w-[90px] shadow-xl text-[#0d5257] z-50">
            <?php foreach ( $lang_data['languages'] as $lang_item ) : ?>
              <a href="<?php echo esc_url( $lang_item['url'] ); ?>" class="px-4 py-1.5 text-[12px] uppercase <?php echo ! empty( $lang_item['is_current'] ) ? 'font-bold text-[#0da9a6]' : 'text-[#0d5257] hover:text-[#0da9a6] hover:bg-[#f8f6f4]'; ?> transition-colors">
                <?php echo esc_html( $lang_item['code'] ); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <a href="#" class="group vbl-btn-reservar flex items-center gap-16 px-4 py-2 border border-[#0d5257] text-[#0d5257] text-[14px] tracking-[1.4px] uppercase transition-all duration-500 ease-in-out">
			<span>Reservar</span>
			<svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>			
		</a>
      </div>
      <!-- Mobile Hamburger Button -->
      <button class="xl:hidden w-8 h-8 flex flex-col items-center justify-center gap-1.5 menu-toggle cursor-pointer" aria-label="Abrir Menu" aria-expanded="false">
        <span class="w-6 h-px bg-[#0d5257]"></span>
        <span class="w-6 h-px bg-[#0d5257]"></span>
        <span class="w-4 h-px bg-[#0d5257] self-end"></span>
      </button>

      <!-- Mobile & Tablet RESERVAR Button -->
      <div class="xl:hidden flex items-center gap-3">
        <div class="relative group">
          <button type="button" class="text-[12px] sm:text-[14px] tracking-[1.4px] uppercase text-[#0d5257] flex items-center gap-1.5 cursor-pointer">
            <span><?php echo esc_html( $lang_data['current'] ); ?></span>
            <svg class="w-2 h-1 group-hover:rotate-180 transition-transform duration-300" viewBox="0 0 8 4" fill="none" aria-hidden="true"><path d="M1 0.5L4 3.5L7 0.5" stroke="currentColor" stroke-width="1.2"/></svg>
          </button>
          <div class="absolute top-full right-0 hidden group-hover:flex flex-col bg-white border border-[#eee8e5] py-2 min-w-[80px] shadow-xl text-[#0d5257] z-50">
            <?php foreach ( $lang_data['languages'] as $lang_item ) : ?>
              <a href="<?php echo esc_url( $lang_item['url'] ); ?>" class="px-4 py-1.5 text-[12px] uppercase <?php echo ! empty( $lang_item['is_current'] ) ? 'font-bold text-[#0da9a6]' : 'text-[#0d5257] hover:text-[#0da9a6] hover:bg-[#f8f6f4]'; ?> transition-colors">
                <?php echo esc_html( $lang_item['code'] ); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
        <a href="#" class="vbl-btn-reservar group hidden [@media(min-width:426px)_and_(max-width:1279px)]:flex items-center gap-2 sm:gap-4 px-3 py-1.5 sm:px-4 sm:py-2 border border-[#0d5257] text-[#0d5257] text-[12px] sm:text-[14px] tracking-[1.2px] sm:tracking-[1.4px] uppercase transition-all duration-300">
          <span>Reservar</span>
          <svg class="w-[9px] h-[9px] sm:w-[10px] sm:h-[10px] transition-transform duration-300 group-hover:rotate-45" viewBox="0 0 11 11" fill="none" aria-hidden="true">
            <path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
          </svg>
        </a>
      </div>
    </div>
  </header>

  <!-- ====== MEGA MENU — Dropdown Hotéis ====== -->
  <div id="megaMenu" class="vbl-mega-menu hidden xl:block border-b border-[#eee8e5] max-h-0 overflow-hidden opacity-0 transition-all duration-[400ms] ease-in-out" style="background:#ffffff;z-index:45">
    <div class="max-w-[1920px] mx-auto px-[clamp(24px,3.33vw,64px)] py-[clamp(32px,2.92vw,56px)] flex items-start justify-between gap-[clamp(16px,1.25vw,24px)]">
      <?php
      $mega_hotels = function_exists( 'vbl_get_mega_menu_hotels' ) ? vbl_get_mega_menu_hotels() : array();
      foreach ( $mega_hotels as $hotel ) :
      ?>
      <a href="<?php echo esc_url( $hotel['url'] ); ?>" class="vbl-hotel-card group flex flex-col gap-6 min-w-0 flex-1 max-w-[320px]">
        <div class="w-full h-[200px] overflow-hidden bg-gray-100">
          <img src="<?php echo esc_url( $hotel['img'] ); ?>" alt="<?php echo esc_attr( $hotel['name'] ); ?>" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
        </div>
        <div class="flex flex-col gap-2">
          <div class="flex items-center gap-4">
            <div class="w-10 [@media(min-width:1180px)_and_(max-width:1333px)]:w-6 h-px" style="background-color: <?php echo esc_attr( $hotel['color'] ); ?>;"></div>
            <span class="font-body text-[10px] tracking-[1px] uppercase text-[#0d5257]"><?php echo esc_html( $hotel['tagline'] ); ?></span>
          </div>
          <p class="font-display text-[20px] text-[#0d5257] group-hover:opacity-80 uppercase transition-all"><?php echo esc_html( $hotel['name'] ); ?></p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- ====== MOBILE MENU OVERLAY ====== -->
  <div id="mobileMenu" class="fixed inset-0 z-[60] bg-white translate-x-full transition-transform duration-300 ease-out xl:hidden overflow-y-auto">
    <div class="flex items-center justify-between px-6 h-20">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex flex-col items-center gap-1">
        <img src="<?php echo vbl_img( 'logo-top.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-7 w-auto">
        <img src="<?php echo vbl_img( 'logo-bottom.svg' ); ?>" alt="" class="h-[10px] w-auto">
      </a>
      <button id="menuClose" class="w-8 h-8 flex items-center justify-center" aria-label="Fechar menu">
        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="none"><line x1="2" y1="2" x2="18" y2="18" stroke="#0d5257" stroke-width="1.5"/><line x1="18" y1="2" x2="2" y2="18" stroke="#0d5257" stroke-width="1.5"/></svg>
      </button>
    </div>
    <nav class="font-body flex flex-col px-6 pt-8 gap-6" id="mobileNav">
      <!-- Hotéis accordion trigger -->
      <button type="button" id="mobileHotelToggle" onclick="window.toggleMobileHotels && window.toggleMobileHotels(event)" class="flex items-center justify-between w-full text-left cursor-pointer">
        <span class="text-[16px] tracking-[1.6px] uppercase text-[#0d5257] font-medium pointer-events-none">Hotéis</span>
        <svg class="w-3 h-2 text-[#0d5257] transition-transform duration-300 pointer-events-none" id="mobileHotelArrow" viewBox="0 0 12 8" fill="none"><path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.5"/></svg>
      </button>
      <!-- Hotel Discovery Cards (accordion content) -->
      <div id="mobileHotelCards" class="overflow-hidden">
        <div class="flex flex-col gap-3 pb-2">
          <?php
          $mobile_hotels = function_exists( 'vbl_get_mega_menu_hotels' ) ? vbl_get_mega_menu_hotels() : array();
          foreach ( $mobile_hotels as $h ) : ?>
          <a href="<?php echo esc_url( $h['url'] ); ?>" class="flex items-center gap-4 rounded-sm overflow-hidden group" style="min-height:72px">
            <div class="w-[100px] md:w-[120px] h-[72px] flex-shrink-0 overflow-hidden bg-gray-100">
              <img src="<?php echo esc_url( $h['img'] ); ?>" alt="<?php echo esc_attr( $h['name'] ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
            <div class="flex flex-col gap-2 py-3 pr-4 min-w-0">
              <div class="flex items-center gap-4">
                <div class="w-8 [@media(min-width:1180px)_and_(max-width:1333px)]:w-6 h-px" style="background-color: <?php echo esc_attr( $h['color'] ); ?>;"></div>
                <span class="font-body text-[9px] md:text-[10px] tracking-[0.8px] uppercase text-[#0d5257] font-normal"><?php echo esc_html( $h['tagline'] ); ?></span>
              </div>
              <span class="font-display text-[15px] md:text-[17px] text-[#0d5257] uppercase leading-tight"><?php echo esc_html( $h['name'] ); ?></span>
            </div>            
          </a>
          <?php endforeach; ?>
          <!-- Ver todos link -->
          <a href="<?php echo esc_url( home_url( '/hoteis' ) ); ?>" class="flex items-center justify-center gap-3 py-3 mt-1 border-t border-[#eee8e5]">
            <span class="text-[12px] tracking-[1.2px] uppercase text-[#bc945b] font-medium">Ver todos os hotéis</span>
            <svg class="w-[8px] h-[8px] text-[#bc945b]" viewBox="0 0 11 11" fill="none"><path d="M1 10L10 1M10 1H3M10 1V8" stroke="currentColor" stroke-width="1"/></svg>
          </a>
        </div>
      </div>
      <?php
      $locations = get_nav_menu_locations();
      $mobile_menu_id = ! empty( $locations['mobile'] ) ? $locations['mobile'] : ( ! empty( $locations['primary'] ) ? $locations['primary'] : 0 );
      $mobile_items = $mobile_menu_id ? wp_get_nav_menu_items( $mobile_menu_id ) : array();

      if ( ! empty( $mobile_items ) && is_array( $mobile_items ) ) :
          foreach ( $mobile_items as $mitem ) :
              if ( ! empty( $mitem->menu_item_parent ) ) {
                  continue;
              }
              if ( stripos( $mitem->url, '/hoteis' ) !== false || stripos( $mitem->title, 'hotéis' ) !== false || stripos( $mitem->title, 'hoteis' ) !== false || stripos( $mitem->title, 'hotels' ) !== false ) {
                  continue; // Exibe no accordion de hotéis acima
              }
              ?>
              <a href="<?php echo esc_url( $mitem->url ); ?>" class="text-[16px] tracking-[1.6px] uppercase text-[#0d5257] font-medium"><?php echo esc_html( $mitem->title ); ?></a>
              <?php
          endforeach;
      else : ?>
        <a href="<?php echo esc_url( home_url( '/o-grupo' ) ); ?>" class="text-[16px] tracking-[1.6px] uppercase text-[#0d5257] font-medium">O Grupo</a>
        <a href="<?php echo esc_url( home_url( '/experiencias' ) ); ?>" class="text-[16px] tracking-[1.6px] uppercase text-[#0d5257] font-medium">Experiências</a>
        <a href="<?php echo esc_url( home_url( '/gift-card' ) ); ?>" class="text-[16px] tracking-[1.6px] uppercase text-[#0d5257] font-medium">Gift Card</a>
        <a href="<?php echo esc_url( home_url( '/contactos' ) ); ?>" class="text-[16px] tracking-[1.6px] uppercase text-[#0d5257] font-medium">Contactos</a>
      <?php endif; ?>
      <div class="h-px bg-[#eee8e5] my-2"></div>
		<a href="#" class="vbl-btn-reservar flex items-center justify-between w-full px-6 py-4 border border-[#0d5257] text-[#0d5257] text-[14px] tracking-[1.4px] uppercase my-2">
		  <span>Reservar</span>
		  <svg class="w-[10px] h-[10px]" viewBox="0 0 11 11" fill="none"><path d="M1 10L10 1M10 1H3M10 1V8" stroke="currentColor" stroke-width="1.2"/></svg>
		</a>
      <!-- Mobile Menu Languages -->
      <div class="flex items-center gap-4 pt-2">
        <span class="text-[12px] uppercase text-[#0d5257]/60">Idioma:</span>
        <div class="flex items-center gap-3">
          <?php foreach ( $lang_data['languages'] as $lang_item ) : ?>
            <a href="<?php echo esc_url( $lang_item['url'] ); ?>" class="text-[13px] uppercase <?php echo ! empty( $lang_item['is_current'] ) ? 'font-bold text-[#0da9a6] border-b-2 border-[#0da9a6]' : 'text-[#0d5257] hover:text-[#0da9a6]'; ?> transition-colors pb-0.5">
              <?php echo esc_html( $lang_item['code'] ); ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </nav>
  </div>

<?php
/* NO automatic spacer — each page template handles its own spacing:
   - Immersive hero pages (O Grupo, Hotéis, Experiências): use pt-20 on the hero section
   - Simple pages (Contactos, Notícias, Modelo Notícia): add <div class="h-20"></div> in the template
   This matches the Astro VBL-V2 pattern exactly. */
?><?php
/**
 * Output nav items for header (with hover classes + mega menu trigger)
 * Reads dynamically from WordPress 'primary' menu location if assigned.
 */
function vbl_header_nav_items( $variant = 'fixo' ) {
    $color       = $variant === 'transparent' ? 'text-white' : 'text-[#0d5257]';
    $hover_class = $variant === 'transparent' ? 'vbl-nav-link-white' : 'vbl-nav-link';
    $stroke      = $variant === 'transparent' ? 'white' : 'currentColor';

    $locations = get_nav_menu_locations();
    if ( ! empty( $locations['primary'] ) ) {
        $menu_items = wp_get_nav_menu_items( $locations['primary'] );
        if ( ! empty( $menu_items ) && is_array( $menu_items ) ) {
            foreach ( $menu_items as $item ) {
                if ( ! empty( $item->menu_item_parent ) ) {
                    continue; // Skip submenu items in flat bar
                }
                // Skip 'Contactos' in left bar if it's placed on the right side
                if ( mb_strtolower( trim( $item->title ) ) === 'contactos' ) {
                    continue;
                }

                $is_mega = false;
                $classes = (array) $item->classes;
                if ( in_array( 'mega-menu', $classes, true ) ||
                     stripos( $item->url, '/hoteis' ) !== false ||
                     stripos( $item->title, 'hotéis' ) !== false ||
                     stripos( $item->title, 'hoteis' ) !== false ||
                     stripos( $item->title, 'hotels' ) !== false ) {
                    $is_mega = true;
                }

                $mega_attr = $is_mega ? ' data-mega-trigger' : '';
                $arrow     = $is_mega
                    ? ' <svg class="vbl-dropdown-arrow w-2 h-1" viewBox="0 0 8 4" fill="none"><path d="M1 0.5L4 3.5L7 0.5" stroke="' . $stroke . '" stroke-width="1.2"/></svg>'
                    : '';
                $extra     = $is_mega ? ' flex items-center gap-2' : '';

                echo '<a href="' . esc_url( $item->url ) . '" class="' . $hover_class . ' text-[14px] tracking-[1.4px] uppercase ' . $color . $extra . '"' . $mega_attr . '>' . esc_html( $item->title ) . $arrow . '</a>';
            }
            return;
        }
    }

    // Default Fallback
    $items = array(
        array( 'url' => '/hoteis', 'label' => __( 'Hotéis', 'vila-baleira' ), 'mega' => true ),
        array( 'url' => '/o-grupo', 'label' => __( 'O Grupo', 'vila-baleira' ), 'mega' => false ),
        array( 'url' => '/experiencias', 'label' => __( 'Experiências', 'vila-baleira' ), 'mega' => false ),
        array( 'url' => '/gift-card', 'label' => __( 'Gift Card', 'vila-baleira' ), 'mega' => false ),
    );

    foreach ( $items as $item ) {
        $mega_attr = ! empty( $item['mega'] ) ? ' data-mega-trigger' : '';
        $arrow     = ! empty( $item['mega'] )
            ? ' <svg class="vbl-dropdown-arrow w-2 h-1" viewBox="0 0 8 4" fill="none"><path d="M1 0.5L4 3.5L7 0.5" stroke="' . $stroke . '" stroke-width="1.2"/></svg>'
            : '';
        $extra     = ! empty( $item['mega'] ) ? ' flex items-center gap-2' : '';

        echo '<a href="' . esc_url( home_url( $item['url'] ) ) . '" class="' . $hover_class . ' text-[14px] tracking-[1.4px] uppercase ' . $color . $extra . '"' . $mega_attr . '>' . esc_html( $item['label'] ) . $arrow . '</a>';
    }
}

/**
 * Fallback menus (original do Mateus — mantido para compatibilidade)
 */
function vbl_fallback_menu() {
    $items = array(
        array( 'url' => '/hoteis', 'label' => 'Hotéis', 'dropdown' => true ),
        array( 'url' => '/o-grupo', 'label' => 'O Grupo' ),
        array( 'url' => '/experiencias', 'label' => 'Experiências' ),
        array( 'url' => '/gift-card', 'label' => 'Gift Card' ),
    );
    foreach ( $items as $item ) {
        $dropdown = ! empty( $item['dropdown'] ) ? ' <svg class="vbl-dropdown-arrow w-2 h-1" viewBox="0 0 8 4" fill="none"><path d="M1 0.5L4 3.5L7 0.5" stroke="currentColor" stroke-width="1.2"/></svg>' : '';
        $extra = ! empty( $item['dropdown'] ) ? ' flex items-center gap-2' : '';
        $mega = ! empty( $item['dropdown'] ) ? ' data-mega-trigger' : '';
        echo '<a href="' . esc_url( home_url( $item['url'] ) ) . '" class="vbl-nav-link text-[14px] tracking-[1.4px] uppercase text-[#0d5257]' . $extra . '"' . $mega . '>' . esc_html( $item['label'] ) . $dropdown . '</a>';
    }
}

function vbl_fallback_menu_mobile() {
    $items = array( 'Hotéis' => '/hoteis', 'O Grupo' => '/o-grupo', 'Experiências' => '/experiencias', 'Gift Card' => '/gift-card', 'Contactos' => '/contactos' );
    $first = true;
    foreach ( $items as $label => $url ) {
        echo '<a href="' . esc_url( home_url( $url ) ) . '" class="text-[16px] tracking-[1.6px] uppercase text-[#0d5257] font-medium">' . esc_html( $label ) . '</a>';
        if ( $first ) {
            echo '<div class="flex flex-col gap-3 pl-6 -mt-2">';
            foreach ( array( 'Porto Santo', 'Suites', 'Village', 'Funchal', 'Residence' ) as $h ) {
                echo '<a href="' . esc_url( home_url( '/hoteis' ) ) . '" class="text-[13px] tracking-[1px] uppercase text-[#0d5257]/70 font-light">' . esc_html( $h ) . '</a>';
            }
            echo '</div>';
            $first = false;
        }
    }
}

/**
 * Custom Walker for Nav Menus (original do Mateus — com hover classes adicionadas)
 */
class VBL_Nav_Walker extends Walker_Nav_Menu {
    private $variant;

    public function __construct( $variant = 'fixo' ) {
        $this->variant = $variant;
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $color = $this->variant === 'transparent' ? 'text-white' : 'text-[#0d5257]';
        $hover = $this->variant === 'transparent' ? 'vbl-nav-link-white' : 'vbl-nav-link';
        $stroke = $this->variant === 'transparent' ? 'white' : 'currentColor';
        $has_children = in_array( 'menu-item-has-children', $item->classes );

        $extra_classes = $has_children ? ' flex items-center gap-2' : '';
        $dropdown = $has_children ? ' <svg class="vbl-dropdown-arrow w-2 h-1" viewBox="0 0 8 4" fill="none"><path d="M1 0.5L4 3.5L7 0.5" stroke="' . $stroke . '" stroke-width="1.2"/></svg>' : '';
        $mega = $has_children ? ' data-mega-trigger' : '';

        $output .= '<a href="' . esc_url( $item->url ) . '" class="' . $hover . ' text-[14px] tracking-[1.4px] uppercase ' . $color . $extra_classes . '"' . $mega . '>' . esc_html( $item->title ) . $dropdown . '</a>';
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ) {}
    public function start_lvl( &$output, $depth = 0, $args = null ) {}
    public function end_lvl( &$output, $depth = 0, $args = null ) {}
}
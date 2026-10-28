<?php
/**
 * Hero Component — Hotel Microsite (Vila Baleira Porto Santo)
 * Recompõe exatamente o layout, tipografia e elementos do design de referência.
 *
 * @package Vila_Baleira
 */

$current_page_id = get_the_ID();
$mirai_hotel = function_exists( 'vbl_resolve_hotel_mirai' ) ? vbl_resolve_hotel_mirai( $current_page_id ) : array(
    'chain_selector' => '23740',
    'hotel_id'       => '100377467',
    'name'           => 'Vila Baleira Porto Santo All Inclusive',
    'short_name'     => 'Porto Santo',
);

$hotel_name   = vbl_field( 'vbl_hotel_name', false, $mirai_hotel['short_name'] ?: 'Porto Santo' );
$booking_url  = vbl_field( 'vbl_hotel_booking_url', false, '#' );
$tagline      = vbl_field( 'vbl_hotel_tagline', false, 'THE ESSENCE OF FAMILY' );
$default_title = 'HOTEL VILA BALEIRA<br>' . ( ! empty( $mirai_hotel['short_name'] ) ? strtoupper( $mirai_hotel['short_name'] ) : 'PORTO SANTO' );
$title        = vbl_field( 'vbl_hotel_title', false, $default_title );
$description  = vbl_field( 'vbl_hotel_desc', false, 'Faucibus nec pellentesque interdum mauris sed tellus, lorem ipsum dolor at sit amet consectetur, lectus elit at adipiscing euismod gravida libero duis.' );
$hero_bg      = vbl_field( 'vbl_hotel_hero_img', false, vbl_img( 'sobre-fotografia.jpg' ) );
if ( empty( $hero_bg ) ) {
    $hero_bg = vbl_img( 'sobre-fotografia.jpg' );
}
$accent_color = function_exists( 'vbl_get_hotel_accent_color' ) ? vbl_get_hotel_accent_color() : '#00B5B4';
?>

<!-- ====== HERO DO HOTEL ====== -->
<section class="relative w-full h-screen min-h-[720px] max-h-[1080px] flex flex-col justify-between overflow-hidden bg-black text-white">

  <div class="absolute inset-0 z-0">
    <img src="<?php echo esc_url( $hero_bg ); ?>" alt="<?php echo esc_attr( strip_tags( $title ) ); ?>" class="w-full h-full object-cover object-center filter brightness-[0.70]">
    <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/30 to-black/80"></div>
  </div>

  <!-- Spacer para compensar o Header Transparente -->
  <div class="h-24 z-10"></div>

  <!-- Conteúdo Principal do Hero -->
  <div class="relative z-10 max-w-[1920px] w-full mx-auto px-6 xl:px-[8.33%] py-12 flex flex-col justify-end gap-6 my-auto">
    
    <!-- Tagline com Linha Indicadora -->
    <div class="flex items-center gap-4">
      <div class="w-10 h-px flex-shrink-0" style="background-color: <?php echo esc_attr( $accent_color ); ?>;"></div>
      <span class="font-body text-[12px] xl:text-[14px] tracking-[2px] uppercase text-white/90 font-medium">
        <?php echo esc_html( $tagline ); ?>
      </span>
    </div>

    <!-- Título Principal do Hotel (Display Serif Uppercase) -->
    <h1 class="font-display text-[44px] sm:text-[60px] md:text-[76px] lg:text-[88px] xl:text-[100px] leading-[1.02] tracking-[2px] uppercase text-white font-normal max-w-[1400px]">
      <?php echo wp_kses_post( $title ); ?>
    </h1>

    <!-- Descrição Parágrafo -->
    <p class="font-body font-light text-[15px] sm:text-[16px] xl:text-[18px] leading-relaxed text-white/85 max-w-[680px]">
      <?php echo esc_html( $description ); ?>
    </p>

  </div>

  <!-- Widget de Reserva Overlay (Fundo do Hero) -->
  <div id="reservas" class="relative z-10 w-full max-w-[1920px] mx-auto px-6 xl:px-[8.33%] pb-8 sm:pb-12">
    <div class="vbl-booking-engine font-body relative w-full border border-white/40 flex flex-col md:flex-row items-stretch bg-transparent backdrop-blur-[2px]"
         data-fixed-hotel="true"
         data-chain-id="501671"
         data-chain-selector="<?php echo esc_attr( $mirai_hotel['chain_selector'] ); ?>"
         data-hotel-id="<?php echo esc_attr( $mirai_hotel['hotel_id'] ); ?>"
         data-hotel-name="<?php echo esc_attr( $mirai_hotel['name'] ); ?>">
      
      <!-- ONDE (Hotel Fixo do Microsite) -->
      <div class="flex-1 flex flex-col justify-center gap-1 border-b md:border-b-0 md:border-r border-white/40 py-4 px-6 xl:px-8 cursor-default select-none">
        <span class="text-[9px] xl:text-[10px] tracking-[1px] uppercase text-white/70">ONDE</span>
        <div class="flex items-center justify-between">
          <span class="vbl-selected-hotel text-[13px] xl:text-[14px] text-white font-medium uppercase tracking-[1px] truncate">
            <?php echo esc_html( $mirai_hotel['short_name'] ?: $hotel_name ); ?>
          </span>
        </div>
      </div>
      
      <!-- QUANDO -->
      <div class="vbl-field-trigger flex-1 flex flex-col justify-center gap-1 border-b md:border-b-0 md:border-r border-white/40 py-4 px-6 xl:px-8 cursor-pointer hover:bg-white/5 transition-colors" data-target="dropdownWhen">
        <span class="text-[9px] xl:text-[10px] tracking-[1px] uppercase text-white/70">QUANDO</span>
        <div class="flex items-center justify-between gap-2">
          <span class="vbl-selected-dates text-[13px] xl:text-[14px] text-white font-medium uppercase tracking-[1px] truncate">Entrada · Saída</span>
          <svg class="w-[10px] h-[10px] text-white flex-shrink-0" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
        </div>
      </div>
      
      <!-- QUEM -->
      <div class="vbl-field-trigger flex-1 flex flex-col justify-center gap-1 border-b md:border-b-0 md:border-r border-white/40 py-4 px-6 xl:px-8 cursor-pointer hover:bg-white/5 transition-colors" data-target="dropdownWho">
        <span class="text-[9px] xl:text-[10px] tracking-[1px] uppercase text-white/70">QUEM</span>
        <div class="flex items-center justify-between gap-2">
          <span class="vbl-selected-guests text-[13px] xl:text-[14px] text-white font-medium uppercase tracking-[1px] truncate">2 adultos · 1 quarto</span>
          <svg class="w-[10px] h-[10px] text-white flex-shrink-0" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
        </div>
      </div>
      
      <!-- PROMOÇÃO -->
      <label class="flex-1 flex flex-col justify-center gap-1 border-b md:border-b-0 md:border-r border-white/40 py-4 px-6 xl:px-8 cursor-text hover:bg-white/5 transition-colors">
        <span class="text-[9px] xl:text-[10px] tracking-[1px] uppercase text-white/70">PROMOÇÃO</span>
        <input type="text" name="promocode" class="vbl-promo-input w-full bg-transparent border-0 p-0 text-[13px] xl:text-[14px] tracking-[1px] uppercase text-white placeholder-white/70 font-medium focus:outline-none focus:ring-0" placeholder="CÓDIGO" aria-label="Código promocional" autocomplete="off" maxlength="40">
      </label>

      <!-- PESQUISAR -->
      <button type="button" class="vbl-search-submit flex-1 max-w-full md:max-w-[240px] flex items-center justify-between py-4 px-6 xl:px-8 hover:bg-white/10 text-white cursor-pointer transition-colors group">
        <span class="text-[13px] xl:text-[14px] font-medium uppercase tracking-[1.4px]">PESQUISAR</span>
        <svg class="w-[10px] h-[10px] text-white transition-transform duration-300 group-hover:rotate-45" viewBox="0 0 11 11" fill="none">
          <path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
        </svg>
      </button>

      <!-- ====== DROPDOWNS POPUPS ====== -->
      <!-- Popups Quando (Datas) -->
      <div class="vbl-popup-when vbl-booking-popup font-body hidden absolute bottom-[calc(100%+12px)] left-0 md:left-[15%] w-full sm:w-[400px] bg-black/60 backdrop-blur-md border border-white/60 shadow-[0_20px_50px_rgba(0,0,0,0.5)] p-5 text-white z-50 rounded-none">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-white/20">
          <span class="text-[11px] uppercase tracking-[1px] text-white/70 font-medium">Selecione as Datas da Estadia</span>
          <button type="button" class="vbl-close-popup text-white/60 hover:text-white text-lg leading-none cursor-pointer" aria-label="Fechar">&times;</button>
        </div>
        <div class="grid grid-cols-2 gap-3 mb-4">
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] uppercase tracking-[1px] text-white/70">Check-in (Entrada)</label>
            <input type="date" class="vbl-checkin-input bg-white/5 border border-white/40 text-white text-[13px] px-3 py-2 rounded-none outline-none focus:border-white focus:bg-white/10 transition-colors w-full cursor-pointer [color-scheme:dark]">
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] uppercase tracking-[1px] text-white/70">Check-out (Saída)</label>
            <input type="date" class="vbl-checkout-input bg-white/5 border border-white/40 text-white text-[13px] px-3 py-2 rounded-none outline-none focus:border-white focus:bg-white/10 transition-colors w-full cursor-pointer [color-scheme:dark]">
          </div>
        </div>
        <div class="flex items-center justify-between pt-3 border-t border-white/20">
          <span class="vbl-nights-count text-[11px] uppercase tracking-[1px] text-white/90 font-medium">2 Noites</span>
          <button type="button" class="vbl-confirm-dates px-5 py-2 border border-white text-white bg-transparent hover:bg-white/20 text-[11px] uppercase tracking-[1.2px] font-medium transition-colors cursor-pointer">Confirmar</button>
        </div>
      </div>

      <!-- Popups Quem (Hóspedes & Quartos) -->
      <div class="vbl-popup-who vbl-booking-popup font-body hidden absolute bottom-[calc(100%+12px)] left-0 md:left-[35%] w-full sm:w-[360px] bg-black/60 backdrop-blur-md border border-white/60 shadow-[0_20px_50px_rgba(0,0,0,0.5)] p-5 text-white z-50 rounded-none">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-white/20">
          <span class="text-[11px] uppercase tracking-[1px] text-white/70 font-medium">Hóspedes e Quartos</span>
          <button type="button" class="vbl-close-popup text-white/60 hover:text-white text-lg leading-none cursor-pointer" aria-label="Fechar">&times;</button>
        </div>
        <div class="flex flex-col gap-4 mb-5">
          <!-- Adultos -->
          <div class="flex items-center justify-between">
            <div>
              <div class="text-[13px] uppercase tracking-[1px] font-medium">Adultos</div>
              <div class="text-[10px] text-white/60">Idade 12+ anos</div>
            </div>
            <div class="flex items-center gap-3">
              <button type="button" class="vbl-counter-btn w-8 h-8 rounded-none border border-white/40 hover:border-white hover:bg-white/15 flex items-center justify-center text-white text-base transition-colors cursor-pointer select-none" data-action="dec" data-target="adults">−</button>
              <span class="vbl-count-adults w-6 text-center text-[15px] font-medium">2</span>
              <button type="button" class="vbl-counter-btn w-8 h-8 rounded-none border border-white/40 hover:border-white hover:bg-white/15 flex items-center justify-center text-white text-base transition-colors cursor-pointer select-none" data-action="inc" data-target="adults">+</button>
            </div>
          </div>
          <!-- Crianças -->
          <div class="flex items-center justify-between">
            <div>
              <div class="text-[13px] uppercase tracking-[1px] font-medium">Crianças</div>
              <div class="text-[10px] text-white/60">0 aos 11 anos</div>
            </div>
            <div class="flex items-center gap-3">
              <button type="button" class="vbl-counter-btn w-8 h-8 rounded-none border border-white/40 hover:border-white hover:bg-white/15 flex items-center justify-center text-white text-base transition-colors cursor-pointer select-none" data-action="dec" data-target="children">−</button>
              <span class="vbl-count-children w-6 text-center text-[15px] font-medium">0</span>
              <button type="button" class="vbl-counter-btn w-8 h-8 rounded-none border border-white/40 hover:border-white hover:bg-white/15 flex items-center justify-center text-white text-base transition-colors cursor-pointer select-none" data-action="inc" data-target="children">+</button>
            </div>
          </div>
          <!-- Quartos -->
          <div class="flex items-center justify-between">
            <div>
              <div class="text-[13px] uppercase tracking-[1px] font-medium">Quartos</div>
              <div class="text-[10px] text-white/60">Quantidade</div>
            </div>
            <div class="flex items-center gap-3">
              <button type="button" class="vbl-counter-btn w-8 h-8 rounded-none border border-white/40 hover:border-white hover:bg-white/15 flex items-center justify-center text-white text-base transition-colors cursor-pointer select-none" data-action="dec" data-target="rooms">−</button>
              <span class="vbl-count-rooms w-6 text-center text-[15px] font-medium">1</span>
              <button type="button" class="vbl-counter-btn w-8 h-8 rounded-none border border-white/40 hover:border-white hover:bg-white/15 flex items-center justify-center text-white text-base transition-colors cursor-pointer select-none" data-action="inc" data-target="rooms">+</button>
            </div>
          </div>
        </div>
        <div class="flex justify-end pt-3 border-t border-white/20">
          <button type="button" class="vbl-confirm-who px-5 py-2 border border-white text-white bg-transparent hover:bg-white/20 text-[11px] uppercase tracking-[1.2px] font-medium transition-colors cursor-pointer">Confirmar</button>
        </div>
      </div>

    </div>
  </div>

</section>

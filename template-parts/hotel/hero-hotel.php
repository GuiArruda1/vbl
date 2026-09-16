<?php
/**
 * Hero Component — Hotel Microsite (Vila Baleira Porto Santo)
 * Recompõe exatamente o layout, tipografia e elementos do design de referência.
 *
 * @package Vila_Baleira
 */

$hotel_name   = vbl_field( 'vbl_hotel_name', false, 'Porto Santo' );
$booking_url  = vbl_field( 'vbl_hotel_booking_url', false, '#' );
$tagline      = vbl_field( 'vbl_hotel_tagline', false, 'THE ESSENCE OF FAMILY' );
$title        = vbl_field( 'vbl_hotel_title', false, 'HOTEL VILA BALEIRA<br>PORTO SANTO' );
$description  = vbl_field( 'vbl_hotel_desc', false, 'Faucibus nec pellentesque interdum mauris sed tellus, lorem ipsum dolor at sit amet consectetur, lectus elit at adipiscing euismod gravida libero duis.' );
$hero_bg      = vbl_field( 'vbl_hotel_hero_img', false, vbl_img( 'sobre-fotografia.jpg' ) );
if ( empty( $hero_bg ) ) {
    $hero_bg = vbl_img( 'sobre-fotografia.jpg' );
}
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
      <div class="w-10 h-px bg-[#bc945b] flex-shrink-0"></div>
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
  <div class="relative z-10 w-full max-w-[1920px] mx-auto px-6 xl:px-[8.33%] pb-12">
    <div class="w-full border border-white/40 grid grid-cols-1 md:grid-cols-4 items-center bg-transparent">
      
      <!-- ONDE -->
      <div class="flex flex-col gap-1 border-b md:border-b-0 md:border-r border-white/40 py-4 px-6 xl:px-8">
        <span class="font-body text-[9px] xl:text-[10px] tracking-[1px] uppercase text-white/70">ONDE</span>
        <div class="flex items-center justify-between">
          <span class="font-body text-[13px] xl:text-[14px] text-white font-medium uppercase tracking-[1px]"><?php echo esc_html( $hotel_name ); ?></span>
          <svg class="w-[10px] h-[10px] text-white" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
        </div>
      </div>
      
      <!-- QUANDO -->
      <div class="flex flex-col gap-1 border-b md:border-b-0 md:border-r border-white/40 py-4 px-6 xl:px-8">
        <span class="font-body text-[9px] xl:text-[10px] tracking-[1px] uppercase text-white/70">QUANDO</span>
        <div class="flex items-center justify-between">
          <span class="font-body text-[13px] xl:text-[14px] text-white font-medium uppercase tracking-[1px]">ENTRADA - SAÍDA</span>
          <svg class="w-[10px] h-[10px] text-white" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
        </div>
      </div>
      
      <!-- QUEM -->
      <div class="flex flex-col gap-1 border-b md:border-b-0 md:border-r border-white/40 py-4 px-6 xl:px-8">
        <span class="font-body text-[9px] xl:text-[10px] tracking-[1px] uppercase text-white/70">QUEM</span>
        <div class="flex items-center justify-between">
          <span class="font-body text-[13px] xl:text-[14px] text-white font-medium uppercase tracking-[1px]">2 ADULTOS · 1 QUARTO</span>
          <svg class="w-[10px] h-[10px] text-white" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
        </div>
      </div>
      
      <!-- PESQUISAR -->
      <a href="<?php echo esc_url( $booking_url ); ?>" class="flex items-center justify-between py-4 px-6 xl:px-8 hover:bg-white/10 cursor-pointer transition-colors group">
        <span class="font-body text-[13px] xl:text-[14px] text-white font-medium uppercase tracking-[1.4px]">PESQUISAR</span>
        <svg class="w-[10px] h-[10px] text-white transition-transform duration-300 group-hover:rotate-45" viewBox="0 0 11 11" fill="none">
          <path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
        </svg>
      </a>
      
    </div>
  </div>

</section>

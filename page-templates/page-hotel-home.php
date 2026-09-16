<?php
/**
 * Template Name: Microsite Home
 * Template para a página principal de cada Microsite de Hotel (ex: Porto Santo).
 *
 * @package Vila_Baleira
 */

// Inclui o Header Transparente com branding do Hotel
require_once VBL_DIR . '/header-hotel.php';
?>

<main class="w-full">
  <?php
  // 1. Hero Banner Principal do Hotel
  get_template_part( 'template-parts/hotel/hero-hotel' );

  // 2. Secções de Apresentação (O Hotel, Quartos, Restaurantes/Spa)
  get_template_part( 'template-parts/hotel/sections-overview' );

  // 3. Secção de Newsletter
  if ( file_exists( VBL_DIR . '/template-parts/section-newsletter.php' ) ) {
      get_template_part( 'template-parts/section-newsletter' );
  }
  ?>
</main>

<?php get_footer('hotel'); ?>

<?php
/**
 * Template Name: Hotel — Internal Page
 * Template para páginas genéricas internas do Hotel (ex: Restaurantes, Spa, etc.)
 *
 * @package Vila_Baleira
 */

// Inclui o Header do Microsite (com as cores e navegação específicas do hotel)
require_once VBL_DIR . '/header-hotel.php';
?>

<main id="primary" class="site-main hotel-internal-page pt-[120px] lg:pt-[150px]">
  
  <!-- Content Placeholder -->
  <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] py-12">
    <h1 class="text-3xl font-medium text-[#0da9a6] mb-8">
      <?php the_title(); ?>
    </h1>
    
    <div class="content-area">
      <?php
      while ( have_posts() ) :
        the_post();
        the_content();
      endwhile;
      ?>
    </div>
  </div>

</main><!-- #main -->

<?php
// Inclui o Footer independente do Hotel
get_footer('hotel');

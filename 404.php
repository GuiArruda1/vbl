<?php
/**
 * 404 Template
 *
 * @package Vila_Baleira
 */

get_header();
?>

<main class="max-w-[1920px] mx-auto px-6 md:px-10 xl:px-[160px] py-24 xl:py-[200px] min-h-[60vh] flex flex-col items-center justify-center text-center">
  <h1 class="font-serif text-[64px] leading-[68px] lg:text-[100px] lg:leading-[100px] text-verde uppercase mb-8">404</h1>
  <p class="font-light text-[16px] leading-[24px] mb-12 max-w-[480px]">A página que procura não foi encontrada. Talvez tenha sido movida ou já não exista.</p>
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center gap-16 px-4 py-4 border-t border-b border-dourado text-dourado text-[14px] tracking-[1.4px] uppercase">
    <span>Voltar ao início</span>
    <img src="<?php echo vbl_img( 'seta-dourada.svg' ); ?>" alt="" class="w-[10px] h-[10px]">
  </a>
</main>

<?php get_footer(); ?>

<?php
/**
 * Main Index Template (fallback)
 *
 * @package Vila_Baleira
 */

get_header();
?>

<main class="max-w-[1920px] mx-auto px-6 md:px-10 xl:px-[160px] py-16 xl:py-[100px]">
  <?php if ( have_posts() ) : ?>
    <div class="flex flex-col gap-12">
      <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class( 'flex flex-col gap-6' ); ?>>
          <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail( 'vbl-news', array( 'class' => 'w-full aspect-[720/400] object-cover' ) ); ?>
            </a>
          <?php endif; ?>
          <div class="flex flex-col gap-3">
            <h2 class="font-serif text-[24px] leading-[30px] lg:text-[36px] lg:leading-[42px] text-verde uppercase">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <div class="font-light text-[16px] leading-[24px]">
              <?php the_excerpt(); ?>
            </div>
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center gap-16 px-4 py-4 border-t border-b border-dourado text-dourado text-[14px] tracking-[1.4px] uppercase self-start">
              <span>Ler mais</span>
              <img src="<?php echo vbl_img( 'seta-dourada.svg' ); ?>" alt="" class="w-[10px] h-[10px]">
            </a>
          </div>
        </article>
      <?php endwhile; ?>
    </div>

    <div class="mt-12">
      <?php the_posts_pagination( array(
          'prev_text' => '&laquo;',
          'next_text' => '&raquo;',
      ) ); ?>
    </div>
  <?php else : ?>
    <p class="font-light text-[16px] leading-[24px]">Nenhum conteúdo encontrado.</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>

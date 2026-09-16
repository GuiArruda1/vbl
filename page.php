<?php
/**
 * Page Template
 *
 * @package Vila_Baleira
 */

get_header();
?>

<main class="max-w-[1920px] mx-auto px-6 md:px-10 xl:px-[160px] py-16 xl:py-[100px]">
  <?php while ( have_posts() ) : the_post(); ?>
    <article <?php post_class(); ?>>
      <div class="flex items-center gap-4 mb-6">
        <div class="w-10 h-px bg-verde flex-shrink-0"></div>
        <span class="text-[14px] tracking-[1.4px] uppercase text-verde"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
      </div>
      <h1 class="font-serif text-[48px] leading-[52px] xl:text-[clamp(64px,5.21vw,100px)] xl:leading-[clamp(64px,5.21vw,100px)] text-verde uppercase mb-12">
        <?php the_title(); ?>
      </h1>
      <div class="prose prose-lg max-w-none font-light text-[16px] leading-[24px]">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>

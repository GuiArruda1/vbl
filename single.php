<?php
/**
 * Single Post Template
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
        <span class="text-[14px] tracking-[1.4px] uppercase text-verde">
          <?php
          $post_type_obj = get_post_type_object( get_post_type() );
          echo esc_html( $post_type_obj->labels->singular_name ?? 'Artigo' );
          ?>
        </span>
      </div>

      <?php if ( has_post_thumbnail() ) : ?>
        <div class="mb-12">
          <?php the_post_thumbnail( 'vbl-banner', array( 'class' => 'w-full max-h-[600px] object-cover' ) ); ?>
        </div>
      <?php endif; ?>

      <h1 class="font-serif text-[36px] leading-[40px] lg:text-[64px] lg:leading-[68px] text-verde uppercase mb-12">
        <?php the_title(); ?>
      </h1>

      <div class="prose prose-lg max-w-[800px] font-light text-[16px] leading-[24px]">
        <?php the_content(); ?>
      </div>

      <div class="mt-16 pt-8 border-t border-dourado">
        <a href="<?php echo esc_url( get_post_type_archive_link( get_post_type() ) ?: home_url() ); ?>" class="inline-flex items-center gap-16 px-4 py-4 border-t border-b border-dourado text-dourado text-[14px] tracking-[1.4px] uppercase">
          <span>Voltar</span>
          <img src="<?php echo vbl_img( 'seta-dourada.svg' ); ?>" alt="" class="w-[10px] h-[10px]">
        </a>
      </div>
    </article>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>

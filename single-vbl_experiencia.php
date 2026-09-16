<?php
/**
 * Single template — Experiência Individual (CPT vbl_experiencia)
 *
 * @package Vila_Baleira
 */
get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();

$post_id    = get_the_ID();
$subtitle   = vbl_field( 'vbl_exp_subtitle', false, 'Experiência Exclusiva' );
$badge      = vbl_field( 'vbl_exp_badge', false, '' );
$hotel      = vbl_field( 'vbl_exp_hotel', false, 'Vila Baleira Porto Santo' );
$horario    = vbl_field( 'vbl_exp_horario', false, '' );
$preco      = vbl_field( 'vbl_exp_preco', false, '' );
$btn_label  = vbl_field( 'vbl_exp_btn_label', false, 'Reservar Experiência' );
$btn_url    = vbl_field( 'vbl_exp_btn_url', false, home_url( '/contactos' ) );
$hero_img   = get_the_post_thumbnail_url( $post_id, 'full' );
if ( empty( $hero_img ) ) $hero_img = vbl_img( 'experiencias/hero-foto.jpg' );

$terms      = get_the_terms( $post_id, 'vbl_cat_experiencia' );
$cat_name   = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0]->name : 'Geral';
?>

<!-- Spacer for fixed header -->
<div class="h-20"></div>

<!-- ====== HERO EXPERIÊNCIA (Responsive) ====== -->
<section class="w-full bg-bege/30 py-12 lg:py-20">
  <div class="vbl-container">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
      
      <!-- Imagem Esquerda -->
      <div class="lg:col-span-6 overflow-hidden rounded-sm relative">
        <img src="<?php echo esc_url( $hero_img ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full aspect-[4/3] object-cover">
        <?php if ( ! empty( $badge ) ) : ?>
        <span class="absolute top-4 left-4 bg-dourado text-white text-[11px] font-medium uppercase tracking-wider px-3 py-1 rounded-xs">
          <?php echo esc_html( $badge ); ?>
        </span>
        <?php endif; ?>
      </div>

      <!-- Informação Direita -->
      <div class="lg:col-span-6 flex flex-col justify-center font-body">
        <div class="vbl-subtitle mb-3">
          <span><?php echo esc_html( $subtitle ); ?> • <?php echo esc_html( $cat_name ); ?></span>
        </div>

        <h1 class="font-display text-[36px] sm:text-[48px] lg:text-[64px] leading-tight text-verde uppercase mb-6">
          <?php the_title(); ?>
        </h1>

        <!-- Detalhes do Cartão -->
        <div class="flex flex-wrap gap-6 text-[14px] text-preto/80 mb-8 pb-6 border-b border-dourado/20">
          <?php if ( ! empty( $hotel ) ) : ?>
          <div>
            <span class="text-[11px] tracking-widest uppercase text-verde/70 font-semibold block mb-0.5">LOCALIZAÇÃO</span>
            <span class="font-light"><?php echo esc_html( $hotel ); ?></span>
          </div>
          <?php endif; ?>

          <?php if ( ! empty( $horario ) ) : ?>
          <div>
            <span class="text-[11px] tracking-widest uppercase text-verde/70 font-semibold block mb-0.5">DURAÇÃO / HORÁRIO</span>
            <span class="font-light"><?php echo esc_html( $horario ); ?></span>
          </div>
          <?php endif; ?>

          <?php if ( ! empty( $preco ) ) : ?>
          <div>
            <span class="text-[11px] tracking-widest uppercase text-verde/70 font-semibold block mb-0.5">VALOR</span>
            <span class="font-light font-medium text-verde"><?php echo esc_html( $preco ); ?></span>
          </div>
          <?php endif; ?>
        </div>

        <!-- Descrição Completa -->
        <div class="font-light text-[15px] lg:text-[16px] leading-relaxed text-preto/90 mb-8 space-y-4">
          <?php the_content(); ?>
        </div>

        <!-- Botão de Reserva -->
        <a href="<?php echo esc_url( $btn_url ); ?>" class="vbl-btn-primary self-start">
          <span><?php echo esc_html( $btn_label ); ?></span>
          <svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45" viewBox="0 0 11 11" fill="none" aria-hidden="true">
            <path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
          </svg>
        </a>

      </div>

    </div>
  </div>
</section>

<!-- ====== OUTRAS EXPERIÊNCIAS RELACIONADAS ====== -->
<section class="w-full py-16 lg:py-24 bg-white">
  <div class="vbl-container">
    <div class="flex items-center justify-between mb-12">
      <div>
        <div class="vbl-subtitle mb-2"><span>DESCUBRA MAIS</span></div>
        <h2 class="font-display text-[28px] sm:text-[40px] text-verde uppercase">Outras Experiências</h2>
      </div>
      <a href="<?php echo esc_url( home_url( '/experiencias' ) ); ?>" class="hidden sm:inline-flex vbl-btn-white">
        <span>Ver Todas</span>
      </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php
      $related = new WP_Query( array(
        'post_type'      => 'vbl_experiencia',
        'posts_per_page' => 3,
        'post__not_in'   => array( $post_id ),
      ) );

      if ( $related->have_posts() ) : while ( $related->have_posts() ) : $related->the_post();
        $r_img   = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
        if ( empty( $r_img ) ) $r_img = vbl_img( 'experiencias/carrossel-520x480.jpg' );
        $r_hotel = vbl_field( 'vbl_exp_hotel', false, 'Vila Baleira' );
      ?>
      <a href="<?php the_permalink(); ?>" class="group flex flex-col bg-bege/20 rounded-sm overflow-hidden border border-dourado/20 hover:border-dourado transition-all">
        <div class="w-full aspect-[4/3] overflow-hidden">
          <img src="<?php echo esc_url( $r_img ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        </div>
        <div class="p-6 flex flex-col gap-3 font-body">
          <span class="text-[11px] tracking-wider uppercase text-verde/70 font-semibold"><?php echo esc_html( $r_hotel ); ?></span>
          <h3 class="font-display text-[22px] text-verde group-hover:text-dourado transition-colors uppercase leading-tight">
            <?php the_title(); ?>
          </h3>
          <p class="font-light text-[13px] text-preto/80 line-clamp-2">
            <?php echo esc_html( get_the_excerpt() ); ?>
          </p>
        </div>
      </a>
      <?php endwhile; wp_reset_postdata(); endif; ?>
    </div>
  </div>
</section>

<?php
endwhile; endif;
get_footer();
?>

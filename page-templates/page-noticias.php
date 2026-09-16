<?php
/* Template Name: Vila Baleira — Notícias */
get_header();
?>

<!-- Spacer for fixed header (this page has no immersive hero) -->
<div class="h-20"></div>

<?php
// ── HERO TÍTULO ──
$hero_subtitle   = vbl_field( 'vbl_noticias_hero_subtitle', false, 'sit amet consectetur Lorem ipsum dolor' );
$hero_title      = vbl_field( 'vbl_noticias_hero_title', false, 'Notícias' );
$hero_desc       = vbl_field( 'vbl_noticias_hero_desc', false, 'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur.' );
$hero_desc_desktop = vbl_field( 'vbl_noticias_hero_desc_desktop', false, 'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur. Convallis odio massa pellentesque elit non eu fusce auctor mattis.' );
$flor_img        = vbl_img( 'noticias/flor.svg' );
?>

<!-- ====== HERO TÍTULO — Mobile/Tablet ====== -->
<section class="lg:hidden px-6 md:px-10">
  <div class="relative py-10 md:py-14 flex flex-col items-center text-center">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-8 h-px bg-[#BC945B]"></div>
      <span class="font-body text-[11px] tracking-[1.1px] uppercase text-[#0d5257]"><?php echo esc_html( $hero_subtitle ); ?></span>
      <div class="w-8 h-px bg-[#BC945B]"></div>
    </div>
    <h1 class="font-display text-[clamp(48px,12.8vw,72px)] leading-[clamp(52px,13.3vw,76px)] text-[#0d5257] uppercase mb-5"><?php echo wp_kses_post( $hero_title ); ?></h1>
    <p class="font-body font-light text-[13px] leading-[19px] text-black/80 max-w-[400px]"><?php echo esc_html( $hero_desc ); ?></p>
  </div>
</section>

<!-- ====== HERO TÍTULO — Desktop ====== -->
<section class="hidden lg:block max-w-[1920px] mx-auto">
  <div class="relative" style="padding:clamp(56px,5.21vw,100px) 16.67% clamp(24px,2.08vw,40px)">
    <div class="flex items-center gap-4">
      <div class="w-10 h-px bg-[#BC945B]"></div>
      <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $hero_subtitle ); ?></span>
    </div>
    <h1 class="font-display text-[clamp(56px,5.21vw,100px)] leading-[clamp(56px,5.21vw,100px)] text-[#0d5257] uppercase mt-[24px]"><?php echo wp_kses_post( $hero_title ); ?></h1>
    <p class="font-body font-light text-[clamp(13px,0.83vw,16px)] leading-[clamp(19px,1.25vw,24px)] mt-[clamp(16px,1.56vw,30px)] ml-[clamp(40px,4.17vw,80px)] max-w-[clamp(400px,33.33vw,640px)]"><?php echo esc_html( $hero_desc_desktop ); ?></p>
    <img src="<?php echo esc_url( $flor_img ); ?>" alt="" id="heroFlor" class="absolute pointer-events-none transition-transform duration-500 ease-out" style="right:clamp(40px,5.21vw,100px);bottom:clamp(-20px,-1.04vw,-40px);width:clamp(140px,11.04vw,212px);z-index:-1">
  </div>
</section>

<?php
// ── GRID DE NOTÍCIAS ──
$pag_arrow_left  = vbl_img( 'noticias/pag-arrow-left.svg' );
$pag_arrow_right = vbl_img( 'noticias/pag-arrow-right.svg' );
$fallback_img    = vbl_img( 'noticias/noticia-1.jpg' );

// Fallback mock data (usado quando CPT tem menos de 6 posts)
$fallback_template = array(
  'title' => 'Exemplo de título para notícia ou um evento a acontecer em 2026',
  'desc'  => 'Lorem ipsum dolor sit amet consectetur. Penatibus posuere eget etiam viverra interdum ultricies enim. Egestas posuere lectus tempor in sed neque.',
);
$fallback_imgs = array(
  vbl_img( 'noticias/noticia-1.jpg' ),
  vbl_img( 'noticias/noticia-2.jpg' ),
  vbl_img( 'noticias/noticia-3.jpg' ),
  vbl_img( 'noticias/noticia-4.jpg' ),
  vbl_img( 'noticias/noticia-5.jpg' ),
  vbl_img( 'noticias/noticia-6.jpg' ),
);

// WP_Query for CPT vbl_noticia
$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$noticias_query = new WP_Query( array(
  'post_type'      => 'vbl_noticia',
  'posts_per_page' => 6,
  'paged'          => $paged,
  'orderby'        => 'date',
  'order'          => 'DESC',
) );

// Build unified items: reais primeiro (com permalink), depois fallback (design mock)
// Fallback link aponta para modelo-noticia (página de demonstração) para nunca ter "#"
$modelo_url = home_url( '/modelo-noticia' );
$noticias_items = array();
if ( $noticias_query->have_posts() ) {
  while ( $noticias_query->have_posts() ) {
    $noticias_query->the_post();
    $img_real = get_the_post_thumbnail_url( get_the_ID(), 'large' );
    $noticias_items[] = array(
      'img'      => $img_real ?: $fallback_img,
      'title'    => get_the_title(),
      'desc'     => get_the_excerpt() ?: $fallback_template['desc'],
      'link'     => get_permalink(),
      'fallback' => false,
    );
  }
  wp_reset_postdata();
}
// Completar até 6 com fallback mock
$fb_i = 0;
while ( count( $noticias_items ) < 6 ) {
  $noticias_items[] = array(
    'img'      => $fallback_imgs[ $fb_i % 6 ],
    'title'    => $fallback_template['title'],
    'desc'     => $fallback_template['desc'],
    'link'     => $modelo_url,
    'fallback' => true,
  );
  $fb_i++;
}

$total_pages = $noticias_query->have_posts() ? $noticias_query->max_num_pages : 1;
if ( $total_pages < 1 ) $total_pages = 1;
?>

<!-- ====== GRID DE NOTÍCIAS ====== -->
<section class="max-w-[1920px] mx-auto px-6 md:px-10 xl:px-0 pb-[clamp(32px,3.33vw,64px)]">
  <div class="lg:mx-auto" style="max-width:1280px">
    <div class="grid grid-cols-1 md:grid-cols-2" style="gap:clamp(32px,3.33vw,64px)">
      <?php foreach ( $noticias_items as $noticia ) : ?>
        <a href="<?php echo esc_url( $noticia['link'] ); ?>" class="group flex flex-col" style="gap:clamp(20px,2.08vw,40px)">
          <div class="overflow-hidden">
            <img src="<?php echo esc_url( $noticia['img'] ); ?>" alt="<?php echo esc_attr( $noticia['title'] ); ?>" class="w-full aspect-[720/400] object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
          </div>
          <div class="flex" style="gap:clamp(12px,1.25vw,24px)">
            <p class="font-display font-medium text-[clamp(13px,0.83vw,16px)] leading-[clamp(19px,1.25vw,24px)] text-[#0d5257] uppercase w-1/2"><?php echo esc_html( $noticia['title'] ); ?></p>
            <p class="font-body font-light text-[clamp(12px,0.73vw,14px)] leading-[clamp(18px,1.04vw,20px)] text-black/60 w-1/2"><?php echo esc_html( $noticia['desc'] ); ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Paginação -->
    <div class="flex items-center justify-center gap-4 mt-[clamp(32px,3.33vw,64px)]">
      <?php if ( $paged > 1 ) : ?>
        <a href="<?php echo esc_url( get_pagenum_link( $paged - 1 ) ); ?>" class="vbl-arrow w-10 h-10 lg:w-12 lg:h-12 border border-[#bc945b] flex items-center justify-center cursor-pointer active:scale-90 transition-all">
          <img src="<?php echo esc_url( $pag_arrow_left ); ?>" alt="" class="w-4 h-4">
        </a>
      <?php else : ?>
        <span class="vbl-arrow w-10 h-10 lg:w-12 lg:h-12 border border-[#bc945b]/30 flex items-center justify-center opacity-40">
          <img src="<?php echo esc_url( $pag_arrow_left ); ?>" alt="" class="w-4 h-4">
        </span>
      <?php endif; ?>

      <?php for ( $i = 1; $i <= $total_pages; $i++ ) : ?>
        <?php if ( $i === (int) $paged ) : ?>
          <span class="font-body text-[14px] tracking-[1.4px] text-[#0d5257] font-medium"><?php echo $i; ?></span>
        <?php else : ?>
          <a href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>" class="text-[14px] tracking-[1.4px] text-[#0d5257]/30 cursor-pointer hover:text-[#0d5257]/60 transition-colors"><?php echo $i; ?></a>
        <?php endif; ?>
      <?php endfor; ?>

      <?php if ( $paged < $total_pages ) : ?>
        <a href="<?php echo esc_url( get_pagenum_link( $paged + 1 ) ); ?>" class="vbl-arrow w-10 h-10 lg:w-12 lg:h-12 border border-[#bc945b] flex items-center justify-center cursor-pointer active:scale-90 transition-all">
          <img src="<?php echo esc_url( $pag_arrow_right ); ?>" alt="" class="w-4 h-4">
        </a>
      <?php else : ?>
        <span class="vbl-arrow w-10 h-10 lg:w-12 lg:h-12 border border-[#bc945b]/30 flex items-center justify-center opacity-40">
          <img src="<?php echo esc_url( $pag_arrow_right ); ?>" alt="" class="w-4 h-4">
        </span>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php
// ── NEWSLETTER ──
$nl_subtitle = vbl_field( 'vbl_noticias_nl_subtitle', false, 'Subscreva a nossa' );
$nl_title    = vbl_field( 'vbl_noticias_nl_title', false, 'Newsletter' );
$nl_desc     = vbl_field( 'vbl_noticias_nl_desc', false, 'Seja o primeiro a receber as novidades sobre os nossos hotéis.' );
$arvore_img  = vbl_img( 'arvore.svg' );
$seta_dourada = vbl_img( 'seta-dourada.svg' );
?>

<!-- ====== NEWSLETTER ====== -->
<?php get_template_part( 'template-parts/section', 'newsletter' ); ?>

<?php get_footer(); ?>

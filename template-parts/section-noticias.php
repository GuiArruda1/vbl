<?php
/**
 * Template Part: Notícias Section
 * Uses Custom Post Type 'vbl_noticia' or falls back to static content
 *
 * @package Vila_Baleira
 */

// Query latest news from CPT vbl_noticia
$news_query = new WP_Query( array(
    'post_type'      => 'vbl_noticia',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
) );

// Fallback URL aponta para a listagem geral (user clica → vai para /noticias/)
$noticias_archive_url = home_url( '/noticias' );

// Fallback data when CPT has fewer than 3 posts
$fallback_news = array(
    array(
        'image' => vbl_img( 'noticias-2.jpg' ),
        'title' => 'Exemplo de título para notícia ou um evento a acontecer em 20261',
        'desc'  => 'Lorem ipsum dolor sit amet consectetur. Penatibus posuere eget etiam viverra interdum ultricies enim.',
        'url'   => $noticias_archive_url,
    ),
    array(
        'image' => vbl_img( 'noticias-3.jpg' ),
        'title' => 'Exemplo de título para notícia ou um evento a acontecer em 20262',
        'desc'  => 'Lorem ipsum dolor sit amet consectetur. Penatibus posuere eget etiam viverra interdum ultricies enim.',
        'url'   => $noticias_archive_url,
    ),
    array(
        'image' => vbl_img( 'noticias-1.jpg' ),
        'title' => 'Exemplo de título para notícia ou um evento a acontecer em 20263',
        'desc'  => 'Lorem ipsum dolor sit amet consectetur. Penatibus posuere eget etiam viverra interdum ultricies enim.',
        'url'   => $noticias_archive_url,
    ),
);

// Build news data array: reais primeiro, depois completar com fallback até 3
$news_items = array();
if ( $news_query->have_posts() ) {
    while ( $news_query->have_posts() ) {
        $news_query->the_post();
        $news_items[] = array(
            'image' => get_the_post_thumbnail_url( get_the_ID(), 'vbl-news' ) ?: vbl_img( 'noticias-1.jpg' ),
            'title' => get_the_title(),
            'desc'  => get_the_excerpt(),
            'url'   => get_permalink(),
        );
    }
    wp_reset_postdata();
}

// Completar sempre até 3 (reais + fallback); fallback tem link para /noticias/
$fb_i = 0;
while ( count( $news_items ) < 3 ) {
    $news_items[] = $fallback_news[ $fb_i % 3 ];
    $fb_i++;
}

// ACF Text Fields
$noticias_subtitle = vbl_field( 'vbl_noticias_subtitle', false, 'Destaques' );
$noticias_title    = vbl_field( 'vbl_noticias_title', false, 'Notícias' );
$noticias_text     = vbl_field( 'vbl_noticias_text', false, 'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur. Convallis odio massa pellentesque elit non eu fusce auctor mattis.' );
?>

<section class="max-w-[1920px] mx-auto py-16 xl:py-[100px] overflow-hidden">
  <!-- Header -->
  <div class="px-6 md:px-10 xl:px-[16.7%]">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-8 xl:mb-[78px] [@media(min-width:1280px)_and_(max-width:1333px)]:gap-14">
      <div>
        <div class="flex items-center gap-4 mb-4 xl:mb-6 ">
          <div class="w-10 h-px bg-[#BC945B] flex-shrink-0"></div>
          <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $noticias_subtitle ); ?></span>
        </div>
        <h2 class="font-display text-[48px] leading-[52px] xl:text-[clamp(64px,5.21vw,100px)] xl:leading-[clamp(64px,5.21vw,100px)] text-[#0d5257] uppercase"><?php echo esc_html( $noticias_title ); ?></h2>
      </div>
      <div class="max-w-[488px] flex flex-col gap-6 ">
        <p class="font-body font-light text-[16px] leading-[24px]"><?php echo esc_html( $noticias_text ); ?></p>
        <a href="<?php echo esc_url( home_url( '/noticias' ) ); ?>" class="group vbl-btn font-body inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-start transition-all duration-500 ease-in-out">
			<span>Acompanhar</span>
			<svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
					  <path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
		</a>
      </div>
    </div>
  </div>

  <!-- MOBILE slider -->
  <div class="lg:hidden px-6 md:px-10">
    <div class="flex gap-6 overflow-x-auto snap-x snap-mandatory pb-4 -mx-6 px-6 scroll-pl-6 md:scroll-pl-10 scrollbar-hide">
      <?php foreach ( $news_items as $item ) : ?>
      <a href="<?php echo esc_url( $item['url'] ); ?>" class="flex-shrink-0 w-[75vw] md:w-[60vw] snap-start flex flex-col gap-6">
        <img src="<?php echo esc_url( $item['image'] ); ?>" alt="" class="w-full aspect-[720/400] object-cover">
        <div class="flex flex-col gap-3">
          <p class="font-display text-[16px] leading-[24px] text-[#0d5257] uppercase"><?php echo esc_html( $item['title'] ); ?></p>
          <p class="font-body text-[14px] leading-[24px]"><?php echo esc_html( $item['desc'] ); ?></p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <div class="flex gap-3 mt-6">
      <button id="newsPrevMobile" class="vbl-arrow-btn"><img src="<?php echo vbl_img( 'arrow-left.svg' ); ?>" alt=""></button>
      <button id="newsNextMobile" class="vbl-arrow-btn"><img src="<?php echo vbl_img( 'arrow-right.svg' ); ?>" alt=""></button>
    </div>
  </div>

  <!-- DESKTOP slider -->
  <div class="hidden lg:block relative" id="newsSlider">
    <div class="flex items-start justify-center gap-[2.5%]">
      <?php foreach ( $news_items as $idx => $item ) :
        $opacity = $idx === 1 ? '' : 'opacity-25';
        $margin = $idx === 0 ? '-ml-[8%]' : ( $idx === 2 ? '-mr-[8%]' : '' );
      ?>
      <a href="<?php echo esc_url( $item['url'] ); ?>" class="flex-shrink-0 w-[37.5%] flex flex-col gap-10 <?php echo $opacity . ' ' . $margin; ?>" data-news="<?php echo $idx; ?>">
        <img src="<?php echo esc_url( $item['image'] ); ?>" alt="" class="w-full aspect-[720/400] object-cover">
        <div class="flex flex-col gap-3 xl:flex-row xl:gap-[47px] items-start"> 
          <p class="font-body font-serif text-[18px] leading-[24px] text-[#0d5257] uppercase flex-shrink-0 w-full xl:w-[39%]"><?php echo esc_html( $item['title'] ); ?></p>
          <p class="font-body font-light text-[16px] leading-[24px] flex-1"><?php echo esc_html( $item['desc'] ); ?></p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <button class="vbl-arrow-btn absolute left-[28%] top-[30%] z-10" id="newsPrev"><img src="<?php echo vbl_img( 'arrow-left.svg' ); ?>" alt=""></button>
    <button class="vbl-arrow-btn absolute right-[28%] top-[30%] z-10" id="newsNext"><img src="<?php echo vbl_img( 'arrow-right.svg' ); ?>" alt=""></button>
  </div>
</section>

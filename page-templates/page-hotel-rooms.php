<?php
/**
 * Template Name: Hotel — Rooms & Suites
 * Template para a página de Quartos e Suites de um Hotel (ex: Porto Santo).
 *
 * @package Vila_Baleira
 */

// Inclui o Header Transparente com branding do Hotel
require_once VBL_DIR . '/header-hotel.php';

// ACF Fields: Hero Banner
$hero_bg = vbl_field( 'vbl_hrooms_hero_bg', false, vbl_img( 'hoteis/suites-680x400.jpg' ) );
if ( empty( $hero_bg ) ) {
    $hero_bg = vbl_img( 'hoteis/suites-680x400.jpg' );
}

// ACF Fields: Introdução
$intro_tagline = vbl_field( 'vbl_hrooms_intro_tagline', false, 'ROOMS & SUITES' );
$intro_title   = vbl_field( 'vbl_hrooms_intro_title', false, 'O QUARTO IDEAL PARA<br>A SUA ESTADIA' );
$intro_text    = vbl_field( 'vbl_hrooms_intro_text', false, 'Gravida turpis posuere in mauris. Eget placerat pretium tempus pellentesque amet venenatis enim est. Sed id condimentum eget amet augue pretium et leo integer. Neque eu ut vulputate nisi sed. Lorem ipsum dolor sit amet consectetur.' );

// 1. Tenta carregar os quartos cadastrados no CPT vbl_quarto associados a este Hotel Pai
$current_page_id = get_the_ID();
$target_hotel_ids = array_values( array_unique( array_filter( array( $current_page_id, $post->post_parent ) ) ) );

$cpt_rooms_query = new WP_Query( array(
    'post_type'      => 'vbl_quarto',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => 'vbl_quarto_hotel',
            'value'   => $target_hotel_ids,
            'compare' => 'IN',
        ),
    ),
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
) );

$rooms_list = array();
if ( $cpt_rooms_query->have_posts() ) {
    while ( $cpt_rooms_query->have_posts() ) {
        $cpt_rooms_query->the_post();
        $q_id = get_the_ID();
        $q_thumb = get_the_post_thumbnail_url( $q_id, 'medium_large' );
        if ( empty( $q_thumb ) ) $q_thumb = vbl_img( 'hoteis/porto-santo-520x400.jpg' );
        
        $rooms_list[] = array(
            'category'    => vbl_field( 'vbl_quarto_categoria', $q_id, 'QUARTO' ),
            'title'       => get_the_title( $q_id ),
            'description' => get_the_excerpt( $q_id ),
            'image'       => $q_thumb,
            'link'        => get_permalink( $q_id ),
        );
    }
    wp_reset_postdata();
}

// 2. Se ainda não houver quartos CPT cadastrados para este hotel, verifica o repeater ACF ou carrega fallbacks
if ( empty( $rooms_list ) ) {
    $acf_rooms = vbl_field( 'vbl_hrooms_list', false, array() );
    if ( ! empty( $acf_rooms ) && is_array( $acf_rooms ) ) {
        $rooms_list = $acf_rooms;
    } else {
        $rooms_list = array(
            array(
                'category'    => 'QUARTO',
                'title'       => 'TWIN DELUXE VISTA MAR',
                'description' => 'Quartos amplos, com varanda privada e uma decoração descontraída em cores vivas e muita luz.',
                'image'       => vbl_img( 'hoteis/porto-santo-520x400.jpg' ),
                'link'        => '#',
            ),
            array(
                'category'    => 'APARTAMENTO',
                'title'       => 'T2 VISTA PARCIAL DO MAR',
                'description' => 'Recentemente renovados, garantem o espaço e conforto ideal para famílias grandes. Dois quartos, kitchenette, sala de estar e varanda com vista parcial para o mar.',
                'image'       => vbl_img( 'hoteis/suites-680x400.jpg' ),
                'link'        => '#',
            ),
            array(
                'category'    => 'QUARTO',
                'title'       => 'TWIN CLÁSSICO',
                'description' => 'Ambiente acolhedor e funcional, ideal para momentos de descanso com todo o conforto que necessita.',
                'image'       => vbl_img( 'hoteis/funchal-680x400.jpg' ),
                'link'        => '#',
            ),
            array(
                'category'    => 'SUITE',
                'title'       => 'SUITE FAMILIAR VISTA MAR',
                'description' => 'Espaço privilegiado com vista panorâmica sobre o oceano, varanda privada e sala integrada.',
                'image'       => vbl_img( 'hoteis/residence-680x400.jpg' ),
                'link'        => '#',
            ),
        );
    }
}
?>

<main class="w-full bg-white text-black overflow-hidden">

  <!-- ==========================================
       1. HERO BANNER
  =========================================== -->
  <section class="relative w-full h-[280px] sm:h-[360px] max-h-[360px] overflow-hidden bg-black flex items-end vbl-hotel-subpage-hero">
    <div class="absolute inset-0 z-0">
      <img src="<?php echo esc_url( $hero_bg ); ?>" alt="Rooms & Suites" class="w-full h-full object-cover object-center filter brightness-[0.85]">
      <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/50"></div>
    </div>
  </section>

  <!-- ==========================================
       2. TÍTULO E INTRODUÇÃO
  =========================================== -->
  <section class="w-full py-20 lg:py-28 bg-white text-center">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] flex flex-col items-center">
      
      <!-- Tagline com Linhas Indicadoras Laterais -->
      <div class="flex items-center justify-center gap-4 mb-5">
        <div class="w-10 h-px bg-[#0da9a6]"></div>
        <span class="font-body text-[11px] xl:text-[12px] tracking-[2.5px] uppercase text-[#0da9a6] font-medium">
          <?php echo esc_html( $intro_tagline ); ?>
        </span>
        <div class="w-10 h-px bg-[#0da9a6]"></div>
      </div>

      <!-- Título Principal Display Serif -->
      <h1 class="font-display text-[46px] sm:text-[60px] md:text-[72px] lg:text-[84px] leading-[1.02] tracking-[1.5px] uppercase text-[#0d5257] mb-8 max-w-[1200px] font-normal">
        <?php echo wp_kses_post( $intro_title ); ?>
      </h1>

      <!-- Texto Introdutório Centralizado -->
      <?php if ( ! empty( $intro_text ) ) : ?>
      <p class="font-body font-light text-[14px] lg:text-[15px] xl:text-[16px] leading-relaxed text-[#333333] max-w-[680px]">
        <?php echo nl2br( esc_html( $intro_text ) ); ?>
      </p>
      <?php endif; ?>

    </div>
  </section>

  <!-- ==========================================
       3. CARROSSEL DE QUARTOS & SUITES (Fundo Suave)
  =========================================== -->
  <section class="w-full py-20 lg:py-32 bg-[#E8F5F5] relative overflow-hidden">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] relative">

      <!-- Botão Seta Esquerda (Anterior) -->
      <button type="button" id="vblRoomsPrev" class="absolute -left-2 lg:left-[2%] xl:left-[4%] top-[40%] -translate-y-1/2 z-20 w-12 h-12 border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer shadow-sm" aria-label="Quarto Anterior">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M28 15H1M1 15L14 2M1 15L14 28" />
        </svg>
      </button>

      <!-- Botão Seta Direita (Seguinte) -->
      <button type="button" id="vblRoomsNext" class="absolute -right-2 lg:right-[2%] xl:right-[4%] top-[40%] -translate-y-1/2 z-20 w-12 h-12 border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer shadow-sm" aria-label="Próximo Quarto">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M1 15H28M28 15L15 2M28 15L15 28" />
        </svg>
      </button>

      <!-- Track do Slider -->
      <div class="overflow-hidden px-4 lg:px-12">
        <div id="vblRoomsTrack" class="flex transition-transform duration-500 ease-out gap-8 lg:gap-12 xl:gap-16">
          
          <?php foreach ( $rooms_list as $index => $room ) : 
              $cat   = ! empty( $room['category'] ) ? $room['category'] : 'QUARTO';
              $title = ! empty( $room['title'] ) ? $room['title'] : '';
              $desc  = ! empty( $room['description'] ) ? $room['description'] : '';
              $img   = ! empty( $room['image'] ) ? $room['image'] : vbl_img( 'hoteis/porto-santo-520x400.jpg' );
              $link  = ! empty( $room['link'] ) ? $room['link'] : '#';
          ?>
          <div class="vbl-room-card w-full md:w-[calc(50%-16px)] lg:w-[calc(50%-24px)] xl:w-[calc(50%-32px)] flex-shrink-0 flex flex-col items-start bg-transparent group">
            
            <!-- Fotografia do Quarto Clicável para o Single -->
            <a href="<?php echo esc_url( $link ); ?>" class="block w-full aspect-[16/11] overflow-hidden bg-white shadow-sm mb-6 cursor-pointer" aria-label="<?php echo esc_attr( $title ); ?>">
              <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </a>

            <!-- Categoria com Traço Ciano -->
            <div class="flex items-center gap-3 mb-2">
              <div class="w-6 h-px bg-[#0da9a6]"></div>
              <span class="font-body text-[10px] xl:text-[11px] tracking-[2px] uppercase text-[#0da9a6] font-medium">
                <?php echo esc_html( $cat ); ?>
              </span>
            </div>

            <!-- Título do Quarto Clicável -->
            <h3 class="font-display text-[26px] sm:text-[30px] lg:text-[34px] leading-tight uppercase text-[#0d5257] mb-4 group-hover:text-[#0da9a6] transition-colors">
              <a href="<?php echo esc_url( $link ); ?>" class="hover:underline decoration-[#0da9a6] decoration-1 underline-offset-4">
                <?php echo esc_html( $title ); ?>
              </a>
            </h3>

            <!-- Descrição -->
            <p class="font-body font-light text-[13px] lg:text-[14px] leading-relaxed text-[#333333] mb-6 max-w-[480px]">
              <?php echo nl2br( esc_html( $desc ) ); ?>
            </p>

            <!-- Link Ver Quarto -->
            <a href="<?php echo esc_url( $link ); ?>" class="vbl-btn-microsite group/btn">
              <span>VER QUARTO</span>
              <svg viewBox="0 0 12 12" fill="none">
                <path d="M1 11L11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>

          </div>
          <?php endforeach; ?>

        </div>
      </div>

    </div>
  </section>

</main>

<!-- ==========================================
     SCRIPTS DO CARROSSEL DE QUARTOS
=========================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const track = document.getElementById('vblRoomsTrack');
  const prevBtn = document.getElementById('vblRoomsPrev');
  const nextBtn = document.getElementById('vblRoomsNext');
  const cards = document.querySelectorAll('.vbl-room-card');

  if (!track || !cards.length) return;

  let currentIndex = 0;

  function getCardsPerView() {
    return window.innerWidth >= 768 ? 2 : 1;
  }

  function getMaxIndex() {
    const perView = getCardsPerView();
    return Math.max(0, cards.length - perView);
  }

  function updateSlider() {
    const card = cards[0];
    const cardWidth = card.getBoundingClientRect().width;
    // Calcula o gap real baseado na janela
    const gap = window.innerWidth >= 1280 ? 64 : (window.innerWidth >= 1024 ? 48 : 32);
    const offset = currentIndex * (cardWidth + gap);
    track.style.transform = `translateX(-${offset}px)`;
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', function() {
      const max = getMaxIndex();
      if (currentIndex < max) {
        currentIndex++;
      } else {
        currentIndex = 0; // Volta ao início em loop
      }
      updateSlider();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function() {
      const max = getMaxIndex();
      if (currentIndex > 0) {
        currentIndex--;
      } else {
        currentIndex = max; // Vai para o final em loop
      }
      updateSlider();
    });
  }

  window.addEventListener('resize', function() {
    const max = getMaxIndex();
    if (currentIndex > max) {
      currentIndex = max;
    }
    updateSlider();
  });
});
</script>

<?php
// Inclui o Footer independente do Hotel
get_footer('hotel');

<?php
/**
 * Overview Sections — Hotel Microsite (Vila Baleira Porto Santo)
 * Secções de introdução, quartos, instalações e localização do hotel.
 *
 * @package Vila_Baleira
 */

$hotel_name = vbl_field( 'vbl_hotel_name', false, 'Porto Santo' );
$hotel_id   = get_the_ID();

// 1. Sobre o Hotel
$sobre_subtitle  = vbl_field( 'vbl_hhome_sobre_subtitle', false, 'SOBRE O HOTEL' );
$sobre_title     = vbl_field( 'vbl_hhome_sobre_title', false, 'SIT SED LOREM<br>IPSUM DOLOR' );
$sobre_desc      = vbl_field( 'vbl_hhome_sobre_desc', false, 'Mi lectus elit at adipiscing euismod gravida libero duis. Bibendum fusce imperdiet egestas amet nec. Sed ullamcorper eget euismod morbi ut egestas id proin posuere. In faucibus nec pellentesque interdum mauris sed tellus. Lorem ipsum dolor sit amet consectetur, lectus elit at adipiscing euismod gravida libero duis.' );
$sobre_btn_label = vbl_field( 'vbl_hhome_sobre_btn_label', false, 'CONHECER' );
$sobre_btn_url   = vbl_field( 'vbl_hhome_sobre_btn_url', false, '#quartos' );
$sobre_img       = vbl_field( 'vbl_hhome_sobre_img', false, vbl_img( 'hoteis/porto-santo-520x400.jpg' ) );
if ( empty( $sobre_img ) ) {
    $sobre_img = vbl_img( 'hoteis/porto-santo-520x400.jpg' );
}

// 2. Frase Panorâmica
$frase_line_1 = vbl_field( 'vbl_hhome_frase_line_1', false, 'LOREM IPSUM DOLOR' );
$frase_line_2 = vbl_field( 'vbl_hhome_frase_line_2', false, 'ENIM VITAE TURPIS' );
$frase_line_3 = vbl_field( 'vbl_hhome_frase_line_3', false, 'LACUS EGET UT SIT.' );
$frase_bg     = vbl_field( 'vbl_hhome_frase_bg', false, vbl_img( 'hoteis/frase-ilhas-bg.jpg' ) );
if ( empty( $frase_bg ) ) {
    $frase_bg = vbl_img( 'hoteis/frase-ilhas-bg.jpg' );
}

// 3. Quartos & Suites
$rooms_slides = array();
$hotel_default_img = function_exists( 'vbl_get_hotel_fallback_image' ) 
    ? vbl_get_hotel_fallback_image( $hotel_id ) 
    : ( function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/porto-santo-520x400.jpg' ) : '' );

// 1. Tenta carregar do Meta Box nativo de Quartos do Hotel (WordPress Meta Box API)
$saved_slider = get_post_meta( $hotel_id, '_vbl_hotel_rooms_slider', true );
if ( ! empty( $saved_slider ) && is_array( $saved_slider ) ) {
    foreach ( $saved_slider as $item ) {
        if ( ! empty( $item['title'] ) || ! empty( $item['image'] ) || ! empty( $item['room_id'] ) ) {
            $slide_img = ! empty( $item['image'] ) ? $item['image'] : '';
            if ( empty( $slide_img ) && ! empty( $item['room_id'] ) ) {
                $slide_img = get_the_post_thumbnail_url( (int) $item['room_id'], 'large' );
            }
            if ( empty( $slide_img ) ) {
                $slide_img = $hotel_default_img;
            }

            $rooms_slides[] = array(
                'title'       => ! empty( $item['title'] ) ? $item['title'] : ( ! empty( $item['room_id'] ) ? get_the_title( $item['room_id'] ) : 'ROOM' ),
                'subtitle'    => ! empty( $item['subtitle'] ) ? $item['subtitle'] : 'ROOMS & SUITES',
                'description' => ! empty( $item['description'] ) ? $item['description'] : '',
                'image'       => $slide_img,
                'url'         => ! empty( $item['url'] ) ? $item['url'] : ( ! empty( $item['room_id'] ) ? get_permalink( $item['room_id'] ) : '#' ),
            );
        }
    }
}

// 2. Se o repeater não estiver preenchido, carrega diretamente do CPT vbl_quarto deste hotel
if ( empty( $rooms_slides ) ) {
    $current_page_id = get_the_ID();
    $target_hotel_ids = array_values( array_unique( array_filter( array( $hotel_id, $current_page_id, ( isset( $post->post_parent ) ? $post->post_parent : 0 ) ) ) ) );

    $cpt_rooms = get_posts( array(
        'post_type'      => 'vbl_quarto',
        'posts_per_page' => 10,
        'meta_query'     => array(
            'relation' => 'OR',
            array(
                'key'     => 'vbl_quarto_hotel',
                'value'   => $target_hotel_ids,
                'compare' => 'IN',
            ),
            array(
                'key'     => 'vbl_quarto_hotel',
                'value'   => array_map( 'strval', $target_hotel_ids ),
                'compare' => 'IN',
            ),
        ),
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
    ) );

    if ( ! empty( $cpt_rooms ) ) {
        foreach ( $cpt_rooms as $r_post ) {
            $q_id    = $r_post->ID;
            $q_thumb = get_the_post_thumbnail_url( $q_id, 'large' );
            if ( empty( $q_thumb ) ) {
                $q_thumb = $hotel_default_img;
            }
            
            $desc = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_capacidade', $q_id ) : '';
            if ( empty( $desc ) ) {
                $desc = get_the_excerpt( $q_id );
            }
            if ( empty( $desc ) ) {
                $desc = 'Quartos amplos, com varanda privada e uma decoração descontraída em cores vivas e muita luz.';
            }

            $rooms_slides[] = array(
                'title'       => get_the_title( $q_id ),
                'subtitle'    => ( function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_categoria', $q_id, 'ROOMS & SUITES' ) : 'ROOMS & SUITES' ) ?: 'ROOMS & SUITES',
                'description' => wp_strip_all_tags( $desc ),
                'image'       => $q_thumb,
                'url'         => get_permalink( $q_id ),
            );
        }
    }
}

// Fallbacks se não houver quartos suficientes
if ( count( $rooms_slides ) < 2 ) {
    $fallback_rooms = array(
        array(
            'title'       => 'TWIN DELUXE<br>VISTA MAR',
            'subtitle'    => 'ROOMS & SUITES',
            'description' => 'Quartos amplos, com varanda privada e uma decoração descontraída em cores vivas e muita luz.',
            'image'       => $hotel_default_img,
            'url'         => '#',
        ),
        array(
            'title'       => 'T2 VISTA PARCIAL<br>DO MAR',
            'subtitle'    => 'ROOMS & SUITES',
            'description' => 'Recentemente renovados, garantem o espaço e conforto ideal para famílias grandes. Dois quartos, kitchenette e sala de estar com varanda.',
            'image'       => function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/suites-680x400.jpg' ) : $hotel_default_img,
            'url'         => '#',
        ),
        array(
            'title'       => 'TWIN CLÁSSICO<br>VISTA JARDIM',
            'subtitle'    => 'ROOMS & SUITES',
            'description' => 'Ambiente acolhedor e funcional, ideal para momentos de descanso com todo o conforto que necessita na sua estadia.',
            'image'       => function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/funchal-680x400.jpg' ) : $hotel_default_img,
            'url'         => '#',
        ),
    );
    if ( empty( $rooms_slides ) ) {
        $rooms_slides = $fallback_rooms;
    } else {
        foreach ( $fallback_rooms as $fb ) {
            $rooms_slides[] = $fb;
        }
    }
}

$rooms_btn_label = vbl_field( 'vbl_hhome_rooms_btn_label', false, 'DESCOBRIR' );
$initial_room     = $rooms_slides[0];
$next_room_idx    = count( $rooms_slides ) > 1 ? 1 : 0;
$initial_next_room = $rooms_slides[$next_room_idx];

// 4. Instalações & Experiências
$exp_subtitle   = vbl_field( 'vbl_hhome_exp_subtitle', false, 'INSTALAÇÕES / EXPERIÊNCIAS' );
$exp_title      = vbl_field( 'vbl_hhome_exp_title', false, 'INTERDUM MI LIBERO<br>LOREM IPSUM SIT UT' );
$exp_desc       = vbl_field( 'vbl_hhome_exp_desc', false, 'Lacus eget parturient non ut semper donec nunc eget. Quis netus diam ullamcorper purus. Lorem ipsum dolor sit amet consectetur. Lacus eget parturient non ut semper donec nunc eget.' );
$exp_btn_label  = vbl_field( 'vbl_hhome_exp_btn_label', false, 'CONHECER' );
$exp_btn_url    = vbl_field( 'vbl_hhome_exp_btn_url', false, '#' );
$exp_img_1      = vbl_field( 'vbl_hhome_exp_img_1', false, vbl_img( 'hoteis/porto-santo-520x400.jpg' ) );
$exp_img_2      = vbl_field( 'vbl_hhome_exp_img_2', false, vbl_img( 'hoteis/suites-680x400.jpg' ) );
$exp_img_center = vbl_field( 'vbl_hhome_exp_img_center', false, vbl_img( 'hoteis/porto-santo-760x760.jpg' ) );
$exp_img_right  = vbl_field( 'vbl_hhome_exp_img_right', false, vbl_img( 'hoteis/village-680x400.jpg' ) );

if ( empty( $exp_img_1 ) ) {
    $exp_img_1 = vbl_img( 'hoteis/porto-santo-520x400.jpg' );
}
if ( empty( $exp_img_2 ) ) {
    $exp_img_2 = vbl_img( 'hoteis/suites-680x400.jpg' );
}
if ( empty( $exp_img_center ) ) {
    $exp_img_center = vbl_img( 'hoteis/porto-santo-760x760.jpg' );
}
if ( empty( $exp_img_right ) ) {
    $exp_img_right = vbl_img( 'hoteis/village-680x400.jpg' );
}
?>

<!-- ====== 1. O HOTEL (Introdução) ====== -->
<section id="o-hotel" class="w-full py-24 lg:py-32 bg-white text-black overflow-hidden">
  <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24 items-center">
    
    <!-- Coluna Esquerda: Texto -->
    <div class="lg:col-span-5 flex flex-col items-start gap-8">
      <div class="flex flex-col gap-2">
        <div class="flex items-center gap-4">
          <div class="w-8 h-px bg-[#0da9a6]"></div>
          <span class="font-body text-[10px] xl:text-[11px] tracking-[1.5px] uppercase text-[#0da9a6] font-medium">
            <?php echo esc_html( $sobre_subtitle ); ?>
          </span>
        </div>
        <h2 class="font-display text-[48px] lg:text-[64px] xl:text-[76px] leading-[1] uppercase text-[#0d5257] mt-2">
          <?php echo wp_kses_post( $sobre_title ); ?>
        </h2>
      </div>
      
      <p class="font-body font-light text-[15px] xl:text-[16px] leading-relaxed text-[#333333] max-w-[480px]">
        <?php echo nl2br( esc_html( $sobre_desc ) ); ?>
      </p>
      
      <div class="pt-2">
        <a href="<?php echo esc_url( $sobre_btn_url ); ?>" class="vbl-btn-microsite">
          <span><?php echo esc_html( $sobre_btn_label ); ?></span>
          <svg viewBox="0 0 12 12" fill="none">
            <path d="M1 11L11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
      </div>
    </div>
    
    <!-- Coluna Direita: Imagem -->
    <div class="lg:col-span-7 relative flex justify-end">
      <div class="relative w-full aspect-[4/3] lg:aspect-auto lg:h-[720px] max-w-[800px]">
        <img src="<?php echo esc_url( $sobre_img ); ?>" alt="<?php echo esc_attr( strip_tags( $sobre_title ) ); ?>" class="w-full h-full object-cover">
        
        <!-- Decoration: Starfish SVG Overlay -->
        <div class="absolute -bottom-16 -left-16 lg:-bottom-24 lg:-left-24 z-10 w-48 h-48 lg:w-64 lg:h-64 pointer-events-none text-[#0da9a6]">
          <img src="<?php echo vbl_img( 'estrela.svg' ); ?>" alt="Estrela do Mar" class="w-full h-full object-contain drop-shadow-sm">
        </div>
      </div>
    </div>
    
  </div>
</section>

<!-- ====== 1.5. FRASE BANNER ====== -->
<section id="banner-frase" class="relative w-full py-32 lg:py-48 flex items-center justify-center overflow-hidden">
  <!-- Background Image -->
  <div class="absolute inset-0 w-full h-full">
    <img src="<?php echo esc_url( $frase_bg ); ?>" alt="Vila Baleira View" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/10"></div>
  </div>
  
  <!-- Text Content -->
  <div class="relative z-10 max-w-[1920px] mx-auto px-6 xl:px-[8.33%] text-center">
    <h2 class="font-display text-[40px] md:text-[56px] lg:text-[72px] xl:text-[80px] text-white uppercase leading-[1.1] shadow-black/20 drop-shadow-md">
      <?php if ( ! empty( $frase_line_1 ) ) : ?>
        <span class="block"><?php echo esc_html( $frase_line_1 ); ?></span>
      <?php endif; ?>
      <?php if ( ! empty( $frase_line_2 ) ) : ?>
        <span class="block italic"><?php echo esc_html( $frase_line_2 ); ?></span>
      <?php endif; ?>
      <?php if ( ! empty( $frase_line_3 ) ) : ?>
        <span class="block"><?php echo esc_html( $frase_line_3 ); ?></span>
      <?php endif; ?>
    </h2>
  </div>
</section>

<!-- ====== 2. ROOMS & SUITES PREVIEW ====== -->
<section id="quartos" class="w-full py-20 lg:py-32 bg-white text-black overflow-hidden">
  <div class="max-w-[1426px] mx-auto px-4 sm:px-6 lg:px-8 relative">
    
    <!-- Left Navigation Arrow (64x64, floating over left picture border, centered on horizontal midline) -->
    <button id="vblOverviewRoomsPrev" class="absolute left-4 sm:left-6 lg:left-8 -translate-x-1/2 top-1/2 -translate-y-1/2 z-20 w-16 h-16 border border-[#00B5B4] text-[#00B5B4] bg-transparent flex items-center justify-center hover:bg-white hover:text-[#00B5B4] hover:border-[#00B5B4] transition-all duration-300 cursor-pointer shadow-sm" aria-label="Quarto Anterior">
      <svg class="w-6 h-6 sm:w-7 sm:h-7" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M28 15H1M1 15L14 2M1 15L14 28" />
      </svg>
    </button>

    <!-- Right Navigation Arrow (64x64, floating over preview picture border, on exact same horizontal midline) -->
    <button id="vblOverviewRoomsNext" class="absolute right-4 sm:right-6 lg:right-8 translate-x-1/2 top-1/2 -translate-y-1/2 z-20 w-16 h-16 border border-[#00B5B4] text-[#00B5B4] bg-transparent flex items-center justify-center hover:bg-white hover:text-[#00B5B4] hover:border-[#00B5B4] transition-all duration-300 cursor-pointer shadow-sm" aria-label="Próximo Quarto">
      <svg class="w-6 h-6 sm:w-7 sm:h-7" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M1 15H28M28 15L15 2M28 15L15 28" />
      </svg>
    </button>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-14 items-center min-h-[560px] lg:min-h-[682px]">
      
      <!-- Left Side: Large Image -->
      <div class="relative w-full h-full flex items-center">
        <a id="vblOverviewRoomImgLink" href="<?php echo esc_url( $initial_room['url'] ); ?>" class="block w-full aspect-[4/4.2] lg:aspect-auto lg:h-[682px] bg-gray-100 shadow-xl shadow-black/5 overflow-hidden group cursor-pointer" aria-label="Ver quarto">
          <img id="vblOverviewRoomImgMain" src="<?php echo esc_url( $initial_room['image'] ); ?>" alt="<?php echo esc_attr( strip_tags( $initial_room['title'] ) ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
        </a>
      </div>

      <!-- Right Side: Content -->
      <div class="flex flex-col justify-between h-full py-4 lg:py-6 pl-0 lg:pl-6">
        <div>
          <!-- Top Tagline -->
          <div class="flex items-center gap-4 mb-4">
            <div class="w-8 h-px bg-[#0da9a6]"></div>
            <span id="vblOverviewRoomSubtitle" class="font-body text-[10px] tracking-[2px] uppercase text-[#0da9a6]">
              <?php echo esc_html( $initial_room['subtitle'] ); ?>
            </span>
          </div>
          <h2 id="vblOverviewRoomTitle" class="font-display text-[40px] md:text-[52px] lg:text-[64px] leading-[1.05] text-[#0d5257] uppercase mb-8 transition-opacity duration-300">
            <a id="vblOverviewRoomTitleLink" href="<?php echo esc_url( $initial_room['url'] ); ?>">
              <?php echo wp_kses_post( $initial_room['title'] ); ?>
            </a>
          </h2>
        </div>

        <!-- Indented Section: Text, Preview Image & Button all aligned to the exact same left guide -->
        <div class="pl-4 sm:pl-8 md:pl-16 flex flex-col items-start w-full">
          <!-- Description -->
          <p id="vblOverviewRoomDesc" class="font-body font-light text-[14px] leading-relaxed text-black/70 max-w-[340px] mb-8 transition-opacity duration-300">
            <?php echo esc_html( $initial_room['description'] ); ?>
          </p>

          <!-- Next Slide Preview (Extending to right border where the right arrow sits) -->
          <div class="relative w-full aspect-[16/10] max-h-[300px] mb-8 bg-gray-100 overflow-hidden opacity-40 hover:opacity-75 transition-opacity duration-300 cursor-pointer" title="Ver próximo quarto">
            <img id="vblOverviewRoomImgNext" src="<?php echo esc_url( $initial_next_room['image'] ); ?>" alt="<?php echo esc_attr( strip_tags( $initial_next_room['title'] ) ); ?>" class="w-full h-full object-cover grayscale transition-opacity duration-300">
          </div>

          <!-- Link -->
          <a id="vblOverviewRoomBtn" href="<?php echo esc_url( $initial_room['url'] ); ?>" class="vbl-btn-microsite">
            <span><?php echo esc_html( $rooms_btn_label ); ?></span>
            <svg viewBox="0 0 12 12" fill="none">
              <path d="M1 11L11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Rooms Data Payload -->
  <script type="application/json" id="vblOverviewRoomsData">
    <?php echo wp_json_encode( $rooms_slides ); ?>
  </script>

  <!-- Slider Controller -->
  <script>
  (function() {
    function initOverviewRoomsSlider() {
      var dataEl = document.getElementById('vblOverviewRoomsData');
      var prevBtn = document.getElementById('vblOverviewRoomsPrev');
      var nextBtn = document.getElementById('vblOverviewRoomsNext');
      var imgMain = document.getElementById('vblOverviewRoomImgMain');
      var imgNext = document.getElementById('vblOverviewRoomImgNext');
      var titleEl = document.getElementById('vblOverviewRoomTitle');
      var subtitleEl = document.getElementById('vblOverviewRoomSubtitle');
      var descEl = document.getElementById('vblOverviewRoomDesc');
      var btnEl = document.getElementById('vblOverviewRoomBtn');
      var imgLink = document.getElementById('vblOverviewRoomImgLink');
      var titleLink = document.getElementById('vblOverviewRoomTitleLink');

      if (!dataEl || !prevBtn || !nextBtn || !imgMain) return;

      var slides = [];
      try {
        slides = JSON.parse(dataEl.textContent);
      } catch(e) {
        return;
      }

      if (!slides || !slides.length) return;

      var currentIndex = 0;
      var isBusy = false;

      function renderSlide(index) {
        if (isBusy) return;
        isBusy = true;

        var currentSlide = slides[index];
        var nextIndex = (index + 1) % slides.length;
        var nextSlide = slides[nextIndex];

        // Fade out
        if (imgMain) imgMain.style.opacity = '0.2';
        if (titleEl) titleEl.style.opacity = '0';
        if (descEl) descEl.style.opacity = '0';
        if (imgNext) imgNext.style.opacity = '0';

        setTimeout(function() {
          if (imgMain) {
            imgMain.src = currentSlide.image;
            imgMain.alt = currentSlide.title.replace(/<[^>]*>?/gm, '');
            imgMain.style.opacity = '1';
          }
          if (titleEl) {
            titleEl.innerHTML = '<a id="vblOverviewRoomTitleLink" href="' + currentSlide.url + '">' + currentSlide.title + '</a>';
            titleEl.style.opacity = '1';
          }
          if (subtitleEl && currentSlide.subtitle) {
            subtitleEl.textContent = currentSlide.subtitle;
          }
          if (descEl) {
            descEl.textContent = currentSlide.description;
            descEl.style.opacity = '1';
          }
          if (btnEl) {
            btnEl.href = currentSlide.url;
          }
          if (imgLink) {
            imgLink.href = currentSlide.url;
          }
          if (imgNext) {
            imgNext.src = nextSlide.image;
            imgNext.alt = nextSlide.title.replace(/<[^>]*>?/gm, '');
            imgNext.style.opacity = '1';
          }

          setTimeout(function() {
            isBusy = false;
          }, 200);
        }, 200);
      }

      nextBtn.addEventListener('click', function(e) {
        e.preventDefault();
        currentIndex = (currentIndex + 1) % slides.length;
        renderSlide(currentIndex);
      });

      prevBtn.addEventListener('click', function(e) {
        e.preventDefault();
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        renderSlide(currentIndex);
      });

      // Clicking preview card advances to next room
      if (imgNext && imgNext.parentElement) {
        imgNext.parentElement.addEventListener('click', function(e) {
          e.preventDefault();
          currentIndex = (currentIndex + 1) % slides.length;
          renderSlide(currentIndex);
        });
      }
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initOverviewRoomsSlider);
    } else {
      initOverviewRoomsSlider();
    }
  })();
  </script>
</section>

<!-- ====== 3. ATIVIDADES & EXPERIÊNCIAS ====== -->
<section id="atividades" class="w-full py-16 md:py-24 xl:py-32 bg-white text-black overflow-hidden">
  
  <!-- Title (Aligned to standard container grid) -->
  <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] mb-8 md:mb-12 xl:mb-14">
    <h2 class="font-display text-[40px] md:text-[56px] lg:text-[72px] xl:text-[90px] leading-[1.05] text-[#0d5257] uppercase">
      <?php echo wp_kses_post( $exp_title ); ?>
    </h2>
  </div>

  <!-- Mobile / Tablet (< xl) -->
  <div class="xl:hidden px-6 md:px-10 flex flex-col gap-10">
    <div class="flex flex-col gap-6">
      <p class="font-body font-light text-[15px] leading-relaxed text-black/70 max-w-[480px]">
        <?php echo nl2br( esc_html( $exp_desc ) ); ?>
      </p>
      
      <a href="<?php echo esc_url( $exp_btn_url ); ?>" class="vbl-btn-microsite self-start">
        <span><?php echo esc_html( $exp_btn_label ); ?></span>
        <svg viewBox="0 0 12 12" fill="none">
          <path d="M1 11L11 1M11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
    </div>

    <!-- Center Big Image -->
    <div class="w-full aspect-[4/5] bg-gray-100 overflow-hidden shadow-sm">
      <img src="<?php echo esc_url( $exp_img_center ); ?>" class="w-full h-full object-cover" alt="Atividades">
    </div>

    <!-- Stacked Images + Right Image Grid -->
    <div class="grid grid-cols-2 gap-4 items-end relative">
      <div class="flex flex-col gap-4">
        <div class="w-full aspect-[4/3] bg-gray-100 overflow-hidden shadow-sm">
          <img src="<?php echo esc_url( $exp_img_1 ); ?>" class="w-full h-full object-cover" alt="Atividade">
        </div>
        <div class="w-full aspect-[4/3] bg-gray-100 overflow-hidden shadow-sm">
          <img src="<?php echo esc_url( $exp_img_2 ); ?>" class="w-full h-full object-cover" alt="Atividade">
        </div>
      </div>
      <div class="relative w-full aspect-[3/4] bg-gray-100 overflow-hidden shadow-sm">
        <div class="absolute -top-12 -left-8 z-10 w-28 pointer-events-none mix-blend-multiply">
          <img src="<?php echo vbl_img('concha-cyan.svg'); ?>" alt="Shell Decoration" class="w-full h-auto drop-shadow-md">
        </div>
        <img src="<?php echo esc_url( $exp_img_right ); ?>" class="w-full h-full object-cover" alt="Piscina interior">
      </div>
    </div>
  </div>

  <!-- Desktop (>= xl): Exato ao Design (como no Institucional) -->
  <div class="hidden xl:flex max-w-[1920px] mx-auto pl-[8.33%] pr-0 relative items-end justify-between min-h-[640px] xl:min-h-[720px] 2xl:min-h-[780px]">
    
    <!-- 1. Coluna Esquerda: Texto + Botão + 2 Imagens Ligeiramente Indentadas -->
    <div class="w-[28%] flex-shrink-0 flex flex-col justify-between self-stretch pr-4 2xl:pr-6 z-10">
      <!-- Topo: Texto e Botão alinhados à esquerda -->
      <div class="pt-2">
        <p class="font-body font-light text-[15px] leading-[24px] text-black/70 mb-8 max-w-[340px]">
          <?php echo nl2br( esc_html( $exp_desc ) ); ?>
        </p>
        
        <a href="<?php echo esc_url( $exp_btn_url ); ?>" class="vbl-btn-microsite mb-10">
          <span><?php echo esc_html( $exp_btn_label ); ?></span>
          <svg viewBox="0 0 12 12" fill="none">
            <path d="M1 11L11 1M11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
      </div>

      <!-- Fundo: 2 Imagens sobrepostas ligeiramente indentadas à direita -->
      <div class="flex flex-col gap-5 2xl:gap-6 ml-[18%] w-[82%]">
        <div class="w-full aspect-[4/3] bg-gray-100 overflow-hidden shadow-sm">
          <img src="<?php echo esc_url( $exp_img_1 ); ?>" class="w-full h-full object-cover" alt="Atividade">
        </div>
        <div class="w-full aspect-[4/3] bg-gray-100 overflow-hidden shadow-sm">
          <img src="<?php echo esc_url( $exp_img_2 ); ?>" class="w-full h-full object-cover" alt="Atividade">
        </div>
      </div>
    </div>

    <!-- 2. Coluna Central: Imagem Grande Ajustada (Mais Larga e Alta) -->
    <div class="w-[45%] flex-shrink-0 self-stretch flex items-end px-2 2xl:px-4">
      <div class="w-full h-full min-h-[640px] xl:min-h-[720px] 2xl:min-h-[780px] bg-gray-100 overflow-hidden shadow-sm">
        <img src="<?php echo esc_url( $exp_img_center ); ?>" class="w-full h-full object-cover" alt="Atividades">
      </div>
    </div>

    <!-- 3. Coluna Direita: Imagem até ao Limite da Página (pr-0) + Concha Decorativa -->
    <div class="w-[27%] flex-shrink-0 relative flex flex-col justify-end pl-2 2xl:pl-4">
      
      <!-- Concha Decorativa Sobreposta no canto superior esquerdo da foto -->
      <div class="absolute -top-20 xl:-top-28 -left-14 xl:-left-20 2xl:-left-24 z-20 w-44 xl:w-56 2xl:w-64 pointer-events-none mix-blend-multiply">
        <img src="<?php echo vbl_img('concha-cyan.svg'); ?>" alt="Shell Decoration" class="w-full h-auto drop-shadow-md">
      </div>
      
      <!-- Imagem Direita (vai até ao limite da página) -->
      <div class="w-full aspect-[3/4] 2xl:aspect-[416/480] bg-gray-100 overflow-hidden shadow-sm">
        <img src="<?php echo esc_url( $exp_img_right ); ?>" class="w-full h-full object-cover" alt="Piscina interior">
      </div>
    </div>

  </div>
</section>

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
$rooms_subtitle = vbl_field( 'vbl_hhome_rooms_subtitle', false, 'ROOMS & SUITES' );
$rooms_title    = vbl_field( 'vbl_hhome_rooms_title', false, 'TWIN DELUXE<br>VISTA MAR' );
$rooms_desc     = vbl_field( 'vbl_hhome_rooms_desc', false, 'Quartos amplos, com varanda privada e uma decoração descontraída em cores vivas e muita luz.' );
$rooms_btn_label = vbl_field( 'vbl_hhome_rooms_btn_label', false, 'DESCOBRIR' );
$rooms_btn_url   = vbl_field( 'vbl_hhome_rooms_btn_url', false, '#' );
$rooms_img_main  = vbl_field( 'vbl_hhome_rooms_img_main', false, vbl_img( 'hoteis/porto-santo-520x400.jpg' ) );
$rooms_img_next  = vbl_field( 'vbl_hhome_rooms_img_next', false, vbl_img( 'hoteis/porto-santo-520x400.jpg' ) );

// Procura quartos dinâmicos do CPT associados a este Hotel
$dynamic_rooms = get_posts( array(
    'post_type'      => 'vbl_quarto',
    'posts_per_page' => 5,
    'meta_query'     => array(
        array(
            'key'     => 'vbl_quarto_hotel',
            'value'   => $hotel_id,
            'compare' => '=',
        ),
    ),
) );

if ( ! empty( $dynamic_rooms ) ) {
    $first_room = $dynamic_rooms[0];
    $rooms_title = get_the_title( $first_room->ID );
    $rooms_btn_url = get_permalink( $first_room->ID );
    $room_desc_dyn = vbl_field( 'vbl_quarto_capacidade', $first_room->ID );
    if ( $room_desc_dyn ) {
        $rooms_desc = $room_desc_dyn;
    }
    if ( has_post_thumbnail( $first_room->ID ) ) {
        $rooms_img_main = get_the_post_thumbnail_url( $first_room->ID, 'large' );
    }
    if ( isset( $dynamic_rooms[1] ) && has_post_thumbnail( $dynamic_rooms[1]->ID ) ) {
        $rooms_img_next = get_the_post_thumbnail_url( $dynamic_rooms[1]->ID, 'medium' );
    }
}

if ( empty( $rooms_img_main ) ) {
    $rooms_img_main = vbl_img( 'hoteis/porto-santo-520x400.jpg' );
}
if ( empty( $rooms_img_next ) ) {
    $rooms_img_next = vbl_img( 'hoteis/porto-santo-520x400.jpg' );
}

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
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-14 items-center min-h-[560px] lg:min-h-[682px]">
      
      <!-- Left Side: Large Image -->
      <div class="relative w-full h-full flex items-center">
        <!-- Arrow Left -->
        <button class="absolute -left-3 sm:-left-5 lg:-left-6 top-1/2 -translate-y-1/2 z-10 w-11 h-11 lg:w-12 lg:h-12 border border-[#00B5B4] text-[#00B5B4] bg-white/80 lg:bg-white flex items-center justify-center hover:bg-[#00B5B4] hover:text-white transition-colors cursor-pointer shadow-sm" aria-label="Quarto Anterior">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M28 15H1M1 15L14 2M1 15L14 28" />
          </svg>
        </button>
        
        <div class="w-full aspect-[4/4.2] lg:aspect-auto lg:h-[682px] bg-gray-100 shadow-xl shadow-black/5 overflow-hidden">
          <img src="<?php echo esc_url( $rooms_img_main ); ?>" alt="<?php echo esc_attr( strip_tags( $rooms_title ) ); ?>" class="w-full h-full object-cover">
        </div>
      </div>

      <!-- Right Side: Content -->
      <div class="flex flex-col justify-between h-full py-4 lg:py-6 pl-0 lg:pl-6">
        <div>
          <!-- Top Tagline -->
          <div class="flex items-center gap-4 mb-4">
            <div class="w-8 h-px bg-[#0da9a6]"></div>
            <span class="font-body text-[10px] tracking-[2px] uppercase text-[#0da9a6]">
              <?php echo esc_html( $rooms_subtitle ); ?>
            </span>
          </div>
          <h2 class="font-display text-[40px] md:text-[52px] lg:text-[64px] leading-[1.05] text-[#0d5257] uppercase mb-8">
            <?php echo wp_kses_post( $rooms_title ); ?>
          </h2>
        </div>

        <!-- Indented Section: Text, Preview Image & Button all aligned to the exact same left guide -->
        <div class="pl-4 sm:pl-8 md:pl-16 flex flex-col items-start">
          <!-- Description -->
          <p class="font-body font-light text-[14px] leading-relaxed text-black/70 max-w-[340px] mb-8">
            <?php echo esc_html( $rooms_desc ); ?>
          </p>

          <!-- Next Slide Preview with Right Arrow -->
          <div class="relative w-full max-w-[480px] mb-8">
            <div class="aspect-[16/10] w-full opacity-35 bg-gray-100 overflow-hidden">
              <img src="<?php echo esc_url( $rooms_img_next ); ?>" alt="Preview next room" class="w-full h-full object-cover grayscale opacity-60">
            </div>
            <!-- Right Arrow -->
            <button class="absolute -right-3 sm:-right-5 lg:-right-6 top-1/2 -translate-y-1/2 z-10 w-11 h-11 lg:w-12 lg:h-12 border border-[#00B5B4] text-[#00B5B4] bg-white/80 lg:bg-white flex items-center justify-center hover:bg-[#00B5B4] hover:text-white transition-colors cursor-pointer shadow-sm" aria-label="Próximo Quarto">
              <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 15H28M28 15L15 2M28 15L15 28" />
              </svg>
            </button>
          </div>

          <!-- Link -->
          <a href="<?php echo esc_url( $rooms_btn_url ); ?>" class="vbl-btn-microsite">
            <span><?php echo esc_html( $rooms_btn_label ); ?></span>
            <svg viewBox="0 0 12 12" fill="none">
              <path d="M1 11L11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>
        </div>
      </div>
      
    </div>
  </div>
</section>

<!-- ====== 3. ATIVIDADES & EXPERIÊNCIAS ====== -->
<section id="atividades" class="w-full py-24 lg:py-40 bg-white text-black overflow-hidden">
  <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%]">
    
    <!-- Title -->
    <h2 class="font-display text-[44px] md:text-[56px] lg:text-[72px] xl:text-[90px] leading-[1.05] text-[#0d5257] uppercase mb-12 lg:mb-16">
      <?php echo wp_kses_post( $exp_title ); ?>
    </h2>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-10 xl:gap-12 relative">
      
      <!-- Left Column (Text + 2 small images) -->
      <div class="md:col-span-4 lg:col-span-3 flex flex-col justify-between">
        <div class="pt-2">
          <p class="font-body font-light text-[14px] leading-relaxed text-black/70 mb-10 max-w-[320px]">
            <?php echo nl2br( esc_html( $exp_desc ) ); ?>
          </p>
          
          <a href="<?php echo esc_url( $exp_btn_url ); ?>" class="vbl-btn-microsite mb-12">
            <span><?php echo esc_html( $exp_btn_label ); ?></span>
            <svg viewBox="0 0 12 12" fill="none">
               <path d="M1 11L11 1M11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>
        </div>

        <!-- Stacked small images -->
        <div class="flex flex-col gap-6 lg:gap-8 mt-12 md:mt-0">
          <div class="w-full aspect-[4/3] bg-gray-100">
            <img src="<?php echo esc_url( $exp_img_1 ); ?>" class="w-full h-full object-cover" alt="Atividade">
          </div>
          <div class="w-full aspect-[4/3] bg-gray-100">
            <img src="<?php echo esc_url( $exp_img_2 ); ?>" class="w-full h-full object-cover" alt="Atividade">
          </div>
        </div>
      </div>

      <!-- Center Column (Large tall image) -->
      <div class="md:col-span-5 lg:col-span-6">
        <div class="w-full h-full min-h-[600px] lg:min-h-[760px] bg-gray-100 relative shadow-sm">
          <img src="<?php echo esc_url( $exp_img_center ); ?>" class="w-full h-full object-cover" alt="Atividades">
        </div>
      </div>

      <!-- Right Column (Seashell + Medium image) -->
      <div class="md:col-span-3 lg:col-span-3 relative flex flex-col justify-end mt-16 md:mt-0">
        
        <!-- Seashell Illustration (Absolute) -->
        <div class="absolute -top-16 lg:-top-32 -left-12 lg:-left-24 z-20 w-48 lg:w-72 pointer-events-none mix-blend-multiply">
           <img src="<?php echo vbl_img('concha-cyan.svg'); ?>" alt="Shell Decoration" class="w-full h-auto drop-shadow-lg">
        </div>
        
        <div class="w-full aspect-[3/4] bg-gray-100 relative z-10 mt-24 md:mt-0">
          <img src="<?php echo esc_url( $exp_img_right ); ?>" class="w-full h-full object-cover" alt="Piscina interior">
        </div>
      </div>

    </div>
  </div>
</section>

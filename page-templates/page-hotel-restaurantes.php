<?php
/**
 * Template Name: Hotel — Restaurantes & Bares
 * Template para a página de Restaurantes, Gastronomia e Bares do Microsite de Hotel.
 *
 * @package Vila_Baleira
 */

// Inclui o Header Transparente com branding do Hotel
require_once VBL_DIR . '/header-hotel.php';

// ACF Fields: Hero Banner
$hero_bg = vbl_field( 'vbl_hrest_hero_bg', false, vbl_img( 'hoteis/porto-santo-760x760.jpg' ) );
if ( empty( $hero_bg ) ) {
    $hero_bg = vbl_img( 'hoteis/porto-santo-760x760.jpg' );
}

// ACF Fields: Apresentação da Gastronomia
$apres_subtitle = vbl_field( 'vbl_hrest_apres_subtitle', false, 'RESTAURANTES & BARES' );
$apres_title    = vbl_field( 'vbl_hrest_apres_title', false, 'MASSA EGET<br>DIAM ELIT UT' );
$apres_p1       = vbl_field( 'vbl_hrest_apres_p1', false, 'Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in. Elementum mauris dolor vitae at porttitor.' );
$apres_p2       = vbl_field( 'vbl_hrest_apres_p2', false, 'Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras. Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo.' );
$apres_img      = vbl_field( 'vbl_hrest_apres_img', false, vbl_img( 'sobre-fotografia.jpg' ) );
if ( empty( $apres_img ) ) {
    $apres_img = vbl_img( 'sobre-fotografia.jpg' );
}

// ACF Fields: Banner Frase
$frase_bg    = vbl_field( 'vbl_hrest_frase_bg', false, vbl_img( 'hoteis/frase-ilhas-bg.jpg' ) );
if ( empty( $frase_bg ) ) {
    $frase_bg = vbl_img( 'hoteis/frase-ilhas-bg.jpg' );
}
$frase_line1 = vbl_field( 'vbl_hrest_frase_line1', false, 'LOREM IPSUM DOLOR' );
$frase_line2 = vbl_field( 'vbl_hrest_frase_line2', false, 'ENIM VITAE TURPIS' );
$frase_line3 = vbl_field( 'vbl_hrest_frase_line3', false, 'LACUS EGET UT SIT.' );

// ACF Fields: Restaurantes & Bares (Slider)
$rest_subtitle = vbl_field( 'vbl_hrest_slider_subtitle', false, 'LACUS EGET UT SIT ENIM VITAE' );
$restaurantes_list = vbl_field( 'vbl_hrest_list', false, array() );
if ( empty( $restaurantes_list ) || ! is_array( $restaurantes_list ) ) {
    $restaurantes_list = array(
        array(
            'subtitle'    => 'RESTAURANTE PRINCIPAL · BUFFET',
            'title'       => 'RESTAURANTE<br>FERRUM',
            'description' => 'Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor.' . "\n\n" . 'Sabores autênticos da gastronomia regional e internacional preparados diariamente com ingredientes frescos selecionados.',
            'image'       => vbl_img( 'hoteis/suites-680x400.jpg' ),
            'thumb'       => vbl_img( 'hoteis/porto-santo-520x400.jpg' ),
            'menu_url'    => '#',
            'menu_label'  => 'CONSULTAR MENU',
        ),
        array(
            'subtitle'    => 'ESPLANADA · COCKTAILS & SNACKS',
            'title'       => 'BEACH CLUB<br>& POOL BAR',
            'description' => 'Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus.' . "\n\n" . 'Desfrute de bebidas refrescantes e refeições ligeiras com uma vista deslumbrante sobre a praia e o oceano.',
            'image'       => vbl_img( 'hoteis/funchal-680x400.jpg' ),
            'thumb'       => vbl_img( 'hoteis/village-680x400.jpg' ),
            'menu_url'    => '#',
            'menu_label'  => 'CONSULTAR CARTA',
        ),
    );
}
?>

<main class="w-full bg-white text-black overflow-hidden">

  <!-- ==========================================
       1. HERO BANNER
  =========================================== -->
  <section class="relative w-full h-[65vh] min-h-[500px] max-h-[750px] overflow-hidden bg-black flex items-end">
    <div class="absolute inset-0 z-0">
      <img src="<?php echo esc_url( $hero_bg ); ?>" alt="Restaurantes & Bares" class="w-full h-full object-cover object-center filter brightness-[0.85]">
      <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/50"></div>
    </div>
  </section>

  <!-- ==========================================
       2. APRESENTAÇÃO DA GASTRONOMIA
  =========================================== -->
  <section class="w-full py-20 lg:py-32 bg-white">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-center">
      
      <!-- Coluna Esquerda: Textos -->
      <div class="lg:col-span-7 flex flex-col items-start justify-center lg:pr-8 order-2 lg:order-1">
        
        <!-- Subtítulo com Traço Ciano -->
        <div class="flex items-center gap-4 mb-4">
          <div class="w-10 h-px bg-[#0da9a6] flex-shrink-0"></div>
          <span class="font-body text-[11px] xl:text-[12px] tracking-[2px] uppercase text-[#0da9a6] font-medium">
            <?php echo esc_html( $apres_subtitle ); ?>
          </span>
        </div>

        <!-- Título Display Serif -->
        <h1 class="font-display text-[46px] sm:text-[58px] lg:text-[72px] xl:text-[84px] leading-[1.02] tracking-[1.5px] uppercase text-[#0d5257] mb-8 lg:mb-10 font-normal">
          <?php echo wp_kses_post( $apres_title ); ?>
        </h1>

        <!-- Parágrafos -->
        <div class="flex flex-col gap-6 max-w-[560px] font-body font-light text-[14px] lg:text-[15px] xl:text-[16px] leading-relaxed text-[#333333]">
          <p>
            <?php echo nl2br( esc_html( $apres_p1 ) ); ?>
          </p>
          <?php if ( ! empty( $apres_p2 ) ) : ?>
            <p>
              <?php echo nl2br( esc_html( $apres_p2 ) ); ?>
            </p>
          <?php endif; ?>
        </div>

      </div>

      <!-- Coluna Direita: Fotografia Vertical do Restaurante -->
      <div class="lg:col-span-5 relative flex justify-center lg:justify-end order-1 lg:order-2">
        <div class="relative w-full max-w-[480px] aspect-[4/5] shadow-sm">
          <img src="<?php echo esc_url( $apres_img ); ?>" alt="<?php echo esc_attr( strip_tags( $apres_title ) ); ?>" class="w-full h-full object-cover">
        </div>
      </div>

    </div>
  </section>

  <!-- ==========================================
       3. BANNER DE FRASE GASTRONOMIA
  =========================================== -->
  <section class="relative w-full py-32 lg:py-44 flex items-center justify-center overflow-hidden">
    <!-- Imagem de Fundo com Gastronomia / Close-up -->
    <div class="absolute inset-0 w-full h-full">
      <img src="<?php echo esc_url( $frase_bg ); ?>" alt="Gastronomia Vila Baleira" class="w-full h-full object-cover filter brightness-[0.70]">
      <div class="absolute inset-0 bg-black/20"></div>
    </div>
    
    <!-- Texto Centralizado -->
    <div class="relative z-10 max-w-[1920px] mx-auto px-6 xl:px-[8.33%] text-center">
      <h2 class="font-display text-[40px] sm:text-[54px] md:text-[68px] lg:text-[80px] text-white uppercase leading-[1.1] drop-shadow-md font-normal">
        <span class="block"><?php echo esc_html( $frase_line1 ); ?></span>
        <span class="block italic"><?php echo esc_html( $frase_line2 ); ?></span>
        <span class="block"><?php echo esc_html( $frase_line3 ); ?></span>
      </h2>
    </div>
  </section>

  <!-- ==========================================
       4. CARROSSEL DE RESTAURANTES & BARES (ex: FERRUM)
  =========================================== -->
  <section class="w-full py-24 lg:py-36 bg-white overflow-hidden">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] relative">
      
      <div id="vblRestaurantesSlider" class="relative">
        <?php foreach ( $restaurantes_list as $index => $item ) : 
            $is_act     = ( $index === 0 );
            $sub_label  = ! empty( $item['subtitle'] ) ? $item['subtitle'] : $rest_subtitle;
            $btn_url    = ! empty( $item['menu_url'] ) ? $item['menu_url'] : '#';
            $btn_label  = ! empty( $item['menu_label'] ) ? $item['menu_label'] : 'CONSULTAR MENU';
        ?>
        <div class="vbl-rest-slide <?php echo $is_act ? 'flex' : 'hidden'; ?> flex-col lg:flex-row items-center gap-12 lg:gap-16 xl:gap-24 transition-opacity duration-500" data-slide="<?php echo esc_attr( $index ); ?>">
          
          <!-- Lado Esquerdo: Info + Miniatura + Botão Consultar Menu -->
          <div class="w-full lg:w-[48%] flex flex-col justify-between">
            
            <div>
              <!-- Subtítulo com Traço Ciano -->
              <div class="flex items-center gap-4 mb-3">
                <div class="w-8 h-px bg-[#0da9a6] flex-shrink-0"></div>
                <span class="font-body text-[10px] xl:text-[11px] tracking-[2px] uppercase text-[#0da9a6] font-medium">
                  <?php echo esc_html( $sub_label ); ?>
                </span>
              </div>

              <!-- Nome do Restaurante -->
              <h2 class="font-display text-[44px] sm:text-[56px] lg:text-[68px] xl:text-[78px] leading-[1.02] tracking-[1.5px] uppercase text-[#0d5257] mb-6 font-normal">
                <?php echo wp_kses_post( $item['title'] ); ?>
              </h2>

              <!-- Descrição -->
              <div class="font-body font-light text-[14px] xl:text-[15px] leading-relaxed text-[#333333] mb-8 max-w-[460px]">
                <?php echo nl2br( esc_html( $item['description'] ) ); ?>
              </div>
            </div>

            <!-- Miniatura com Seta Esquerda (Anterior) -->
            <div class="flex items-center gap-6 mb-8">
              <!-- Seta Esquerda -->
              <button type="button" class="vbl-rest-prev w-12 h-12 border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer flex-shrink-0" aria-label="Restaurante Anterior">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M28 15H1M1 15L14 2M1 15L14 28" />
                </svg>
              </button>

              <!-- Miniatura -->
              <?php if ( ! empty( $item['thumb'] ) ) : ?>
              <div class="w-48 sm:w-56 aspect-[16/10] bg-gray-100 overflow-hidden shadow-sm">
                <img src="<?php echo esc_url( $item['thumb'] ); ?>" alt="Área do Restaurante" class="w-full h-full object-cover opacity-85">
              </div>
              <?php endif; ?>
            </div>

            <!-- Botão Consultar Menu -->
            <?php if ( ! empty( $btn_url ) ) : ?>
            <a href="<?php echo esc_url( $btn_url ); ?>" target="_blank" rel="noopener" class="vbl-btn-microsite group/btn">
              <span><?php echo esc_html( $btn_label ); ?></span>
              <svg viewBox="0 0 12 12" fill="none">
                <path d="M1 11L11 1M11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>
            <?php endif; ?>

          </div>

          <!-- Lado Direito: Fotografia Principal com Seta Direita -->
          <div class="w-full lg:w-[52%] relative flex items-center justify-end">
            
            <div class="w-full aspect-[4/3] max-w-[760px] bg-gray-100 shadow-sm relative overflow-hidden">
              <img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( strip_tags( $item['title'] ) ); ?>" class="w-full h-full object-cover">
            </div>

            <!-- Seta Direita (Seguinte) -->
            <button type="button" class="vbl-rest-next absolute -right-3 sm:-right-5 top-1/2 -translate-y-1/2 z-10 w-12 h-12 border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer shadow-sm" aria-label="Restaurante Seguinte">
              <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 15H28M28 15L15 2M28 15L15 28" />
              </svg>
            </button>

          </div>

        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </section>

  <!-- ==========================================
       5. NEWSLETTER DO MICROSITE
  =========================================== -->
  <?php
  if ( file_exists( VBL_DIR . '/template-parts/section-newsletter.php' ) ) {
      get_template_part( 'template-parts/section-newsletter' );
  }
  ?>

</main>

<!-- ==========================================
     SCRIPTS DE INTERAÇÃO DO SLIDER
=========================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const slides = document.querySelectorAll('.vbl-rest-slide');
  const prevBtns = document.querySelectorAll('.vbl-rest-prev');
  const nextBtns = document.querySelectorAll('.vbl-rest-next');
  let currentSlide = 0;

  function showSlide(index) {
    if (!slides.length) return;
    if (index >= slides.length) currentSlide = 0;
    else if (index < 0) currentSlide = slides.length - 1;
    else currentSlide = index;

    slides.forEach((slide, i) => {
      if (i === currentSlide) {
        slide.classList.remove('hidden');
        slide.classList.add('flex');
      } else {
        slide.classList.add('hidden');
        slide.classList.remove('flex');
      }
    });
  }

  prevBtns.forEach(btn => {
    btn.addEventListener('click', () => showSlide(currentSlide - 1));
  });

  nextBtns.forEach(btn => {
    btn.addEventListener('click', () => showSlide(currentSlide + 1));
  });
});
</script>

<?php
// Inclui o Footer independente do Hotel
get_footer('hotel');

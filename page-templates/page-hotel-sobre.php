<?php
/**
 * Template Name: Hotel — O Hotel
 * Template para a página "O Hotel" do Microsite (Apresentação da Unidade, Vídeo e Instalações).
 *
 * @package Vila_Baleira
 */

// Inclui o Header Transparente com branding do Hotel
require_once VBL_DIR . '/header-hotel.php';

// ACF Fields: Hero
$hero_bg    = vbl_field( 'vbl_hhotel_hero_bg', false, vbl_img( 'hoteis/hero-foto.jpg' ) );
if ( empty( $hero_bg ) ) {
    $hero_bg = vbl_img( 'hoteis/hero-foto.jpg' );
}

// ACF Fields: Apresentação da Unidade ("Conheça o Hotel")
$conheca_subtitle = vbl_field( 'vbl_hhotel_conheca_subtitle', false, 'APRESENTAÇÃO DA UNIDADE' );
$conheca_title    = vbl_field( 'vbl_hhotel_conheca_title', false, 'CONHEÇA<br>O HOTEL' );
$conheca_p1       = vbl_field( 'vbl_hhotel_conheca_p1', false, 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras.' );
$conheca_p2       = vbl_field( 'vbl_hhotel_conheca_p2', false, 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.' );
$conheca_img      = vbl_field( 'vbl_hhotel_conheca_img', false, vbl_img( 'hoteis/porto-santo-520x400.jpg' ) );
if ( empty( $conheca_img ) ) {
    $conheca_img = vbl_img( 'hoteis/porto-santo-520x400.jpg' );
}

// ACF Fields: Vídeo
$video_tagline = vbl_field( 'vbl_hhotel_video_tagline', false, 'THE ESSENCE OF FAMILY' );
$video_title   = vbl_field( 'vbl_hhotel_video_title', false, 'VILA BALEIRA<br><em>PORTO SANTO</em>' );
$video_bg      = vbl_field( 'vbl_hhotel_video_bg', false, vbl_img( 'sobre-fotografia.jpg' ) );
if ( empty( $video_bg ) ) {
    $video_bg = vbl_img( 'sobre-fotografia.jpg' );
}
$video_url     = vbl_field( 'vbl_hhotel_video_url', false, 'https://www.youtube.com/watch?v=WN8c9XwUx9s' );
$video_embed   = vbl_get_youtube_embed_url( $video_url );

// ACF Fields: Instalações & Áreas Comuns
$inst_subtitle = vbl_field( 'vbl_hhotel_inst_subtitle', false, 'INSTALAÇÕES & ÁREAS COMUNS' );

// Repeater de Instalações ou Fallback padrão
$instalacoes = vbl_field( 'vbl_hhotel_instalacoes', false, array() );
if ( empty( $instalacoes ) || ! is_array( $instalacoes ) ) {
    $instalacoes = array(
        array(
            'title'       => 'RECEÇÃO',
            'description' => 'Quam consectetur sodales mattis id commodo urna. In non vitae amet enim. Ipsum vitae felis at purus nam nibh tincidunt. Lorem ipsum dolor sit amet consectetur. Habitasse elementum quam ullamcorper id euismod amet. Nisl ac ultricies augue ante tortor consequat.',
            'image'       => vbl_img( 'sobre-fotografia.jpg' ),
            'thumb'       => vbl_img( 'hoteis/village-680x400.jpg' ),
        ),
        array(
            'title'       => 'PISCINAS & EXTERIORES',
            'description' => 'Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras.',
            'image'       => vbl_img( 'hoteis/porto-santo-760x760.jpg' ),
            'thumb'       => vbl_img( 'hoteis/porto-santo-520x400.jpg' ),
        ),
        array(
            'title'       => 'SPA & BEM-ESTAR',
            'description' => 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor.',
            'image'       => vbl_img( 'hoteis/suites-680x400.jpg' ),
            'thumb'       => vbl_img( 'hoteis/funchal-680x400.jpg' ),
        ),
    );
}
?>

<main class="w-full bg-white text-black overflow-hidden">

  <!-- ==========================================
       1. HERO HEADER BANNER
  =========================================== -->
  <section class="relative w-full h-[280px] sm:h-[360px] max-h-[360px] overflow-hidden bg-black flex items-end vbl-hotel-subpage-hero">
    <div class="absolute inset-0 z-0">
      <img src="<?php echo esc_url( $hero_bg ); ?>" alt="Hotel Vila Baleira" class="w-full h-full object-cover object-center filter brightness-[0.85]">
      <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/50"></div>
    </div>
  </section>

  <!-- ==========================================
       2. CONHEÇA O HOTEL (Apresentação da Unidade)
  =========================================== -->
  <section id="conheca-o-hotel" class="w-full py-20 lg:py-28 xl:py-32 bg-white">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 xl:gap-24 items-start">
      
      <!-- Coluna Esquerda: Imagem com Concha Sobreposta -->
      <div class="lg:col-span-5 relative flex justify-start">
        <div class="relative w-full max-w-[480px] aspect-[500/550] shadow-sm">
          <img src="<?php echo esc_url( $conheca_img ); ?>" alt="<?php echo esc_attr( strip_tags( $conheca_title ) ); ?>" class="w-full h-full object-cover">
          
          <!-- Concha Decorativa em Outline Ciano Sobreposta (Canto inferior direito) -->
          <div class="absolute bottom-2 lg:bottom-4 -right-10 sm:-right-14 lg:-right-16 xl:-right-20 z-10 w-36 sm:w-44 lg:w-48 xl:w-56 pointer-events-none">
            <img src="<?php echo vbl_img( 'concha-cyan.svg' ); ?>" alt="" class="w-full h-auto object-contain">
          </div>
        </div>
      </div>

      <!-- Coluna Direita: Subtítulo, Título e Parágrafos Indentados -->
      <div class="lg:col-span-7 flex flex-col items-start justify-start pt-2 lg:pt-4">
        
        <!-- Subtítulo -->
        <div class="flex items-center gap-3 mb-4">
          <div class="w-8 h-px bg-[#0da9a6] flex-shrink-0"></div>
          <span class="font-body text-[11px] xl:text-[12px] tracking-[2px] uppercase text-[#0da9a6] font-medium">
            <?php echo esc_html( $conheca_subtitle ); ?>
          </span>
        </div>

        <!-- Título Display -->
        <h2 class="font-display text-[38px] sm:text-[46px] lg:text-[54px] xl:text-[62px] leading-[1.04] tracking-[1.5px] uppercase text-[#0d5257] mb-8 lg:mb-10 font-normal">
          <?php echo wp_kses_post( $conheca_title ); ?>
        </h2>

        <!-- Parágrafos de Apresentação Indentados à Direita (como no design) -->
        <div class="flex flex-col gap-5 max-w-[460px] xl:max-w-[490px] lg:ml-12 xl:ml-16 font-body font-light text-[13px] sm:text-[14px] xl:text-[15px] leading-[22px] xl:leading-[24px] text-[#4a4a4a]">
          <p>
            <?php echo nl2br( esc_html( $conheca_p1 ) ); ?>
          </p>
          <?php if ( ! empty( $conheca_p2 ) ) : ?>
            <p>
              <?php echo nl2br( esc_html( $conheca_p2 ) ); ?>
            </p>
          <?php endif; ?>
        </div>

      </div>

    </div>
  </section>

  <!-- ==========================================
       3. BANNER DE VÍDEO (The Essence of Family)
  =========================================== -->
  <section class="relative w-full h-[520px] md:h-[620px] lg:h-[720px] min-h-[520px] lg:min-h-[720px] overflow-hidden bg-black flex items-center justify-center">
    <!-- Imagem de Fundo -->
    <div class="absolute inset-0 z-0">
      <img src="<?php echo esc_url( $video_bg ); ?>" alt="Vila Baleira Porto Santo" class="w-full h-full object-cover object-center filter brightness-[0.75]">
      <div class="absolute inset-0 bg-black/25"></div>
    </div>

    <!-- Conteúdo Centralizado / Layout com Play Button à Direita -->
    <div class="relative z-10 max-w-[1920px] w-full mx-auto px-6 xl:px-[8.33%] flex flex-col md:flex-row items-center justify-between gap-12">
      
      <!-- Lado Esquerdo: Tagline e Título (Alinhados ao Centro) -->
      <div class="flex flex-col items-center text-center gap-4">
        <div class="flex items-center justify-center gap-4">
          <div class="w-8 lg:w-10 h-px bg-[#0da9a6] flex-shrink-0"></div>
          <span class="font-body text-[11px] xl:text-[13px] tracking-[2px] uppercase text-white/90 font-light text-center">
            <?php echo esc_html( $video_tagline ); ?>
          </span>
          <div class="w-8 lg:w-10 h-px bg-[#0da9a6] flex-shrink-0"></div>
        </div>
        <h2 class="font-display text-[40px] sm:text-[54px] md:text-[64px] lg:text-[76px] xl:text-[88px] uppercase tracking-[2px] text-white leading-[1.05] drop-shadow-md text-center">
          <?php echo wp_kses_post( $video_title ); ?>
        </h2>
      </div>

      <!-- Lado Direito: Botão Play de Vídeo (Como no design e em O Grupo) -->
      <?php if ( ! empty( $video_url ) ) : ?>
      <div class="flex items-center justify-center md:pr-12 lg:pr-20 xl:pr-28">
        <button type="button" id="vblHotelVideoOpen" class="cursor-pointer opacity-80 hover:opacity-100 hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none w-[110px] sm:w-[130px] lg:w-[150px] xl:w-[170px]" aria-label="Reproduzir Vídeo">
          <img src="<?php echo vbl_img( 'ogrupo/play-icon.svg' ); ?>" alt="Reproduzir" class="w-full h-auto">
        </button>
      </div>
      <?php endif; ?>

    </div>
  </section>

  <!-- ==========================================
       4. INSTALAÇÕES & ÁREAS COMUNS (Carrossel / Receção)
  =========================================== -->
  <section id="instalacoes" class="w-full py-20 lg:py-28 xl:py-36 bg-white overflow-hidden">
    <div class="max-w-[1920px] mx-auto px-4 sm:px-8 xl:px-[5%] 2xl:px-[8.33%] relative">
      
      <div id="vblHotelInstalacoesSlider" class="relative px-8 sm:px-12 md:px-16 lg:px-20 xl:px-24">

        <!-- Seta Esquerda (Anterior) Centralizada Verticalmente -->
        <button type="button" class="vbl-slider-prev absolute left-0 sm:left-2 lg:left-4 xl:left-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-12 sm:h-12 bg-white border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer shadow-sm" aria-label="Instalação Anterior">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7" />
          </svg>
        </button>

        <!-- Slides Wrapper -->
        <div class="relative w-full">
          <?php foreach ( $instalacoes as $index => $item ) : 
              $is_active  = ( $index === 0 );
              $next_idx   = ( $index + 1 ) % count( $instalacoes );
              $thumb_img  = ! empty( $item['thumb'] ) ? $item['thumb'] : ( isset( $instalacoes[ $next_idx ]['image'] ) ? $instalacoes[ $next_idx ]['image'] : '' );
          ?>
          <div class="vbl-instalacao-slide <?php echo $is_active ? 'flex' : 'hidden'; ?> flex-col lg:flex-row items-center justify-between gap-8 lg:gap-12 xl:gap-16 transition-opacity duration-500" data-slide="<?php echo esc_attr( $index ); ?>">
            
            <!-- Lado Esquerdo: Info da Instalação + Miniatura Próxima -->
            <div class="w-full lg:w-[46%] xl:w-[45%] flex flex-col justify-between">
              
              <div>
                <!-- Subtítulo -->
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-8 h-px bg-[#0da9a6] flex-shrink-0"></div>
                  <span class="font-body text-[10px] xl:text-[11px] tracking-[2px] uppercase text-[#0da9a6] font-medium">
                    <?php echo esc_html( $inst_subtitle ); ?>
                  </span>
                </div>

                <!-- Título da Instalação -->
                <h2 class="font-display text-[34px] sm:text-[42px] lg:text-[48px] xl:text-[54px] leading-[1.05] tracking-[1.5px] uppercase text-[#0d5257] mb-4 lg:mb-5 font-normal">
                  <?php echo esc_html( $item['title'] ); ?>
                </h2>

                <!-- Descrição -->
                <p class="font-body font-light text-[13px] sm:text-[14px] xl:text-[15px] leading-relaxed text-[#555555] mb-6 sm:mb-8 max-w-[440px]">
                  <?php echo nl2br( esc_html( $item['description'] ) ); ?>
                </p>
              </div>

              <!-- Miniatura da Próxima Área / Instalação Alinhada à Esquerda -->
              <?php if ( ! empty( $thumb_img ) ) : ?>
              <div class="vbl-slider-next-thumb w-full max-w-[300px] sm:max-w-[340px] xl:max-w-[380px] aspect-[4/3] bg-gray-100 overflow-hidden shadow-xs cursor-pointer group relative" title="Ver próxima instalação">
                <img src="<?php echo esc_url( $thumb_img ); ?>" alt="Área adjacente" class="w-full h-full object-cover opacity-75 group-hover:opacity-100 transition-all duration-300">
                <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors"></div>
              </div>
              <?php endif; ?>

            </div>

            <!-- Lado Direito: Fotografia Principal com Seta Sobreposta na Borda -->
            <div class="w-full lg:w-[52%] xl:w-[53%] flex items-center justify-center lg:justify-end">
              <div class="relative w-full max-w-[480px] sm:max-w-[520px] lg:max-w-[560px] xl:max-w-[620px] aspect-[1.05/1]">
                
                <!-- Foto Principal com cantos nítidos -->
                <div class="w-full h-full overflow-hidden bg-gray-100 shadow-sm">
                  <img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" class="w-full h-full object-cover">
                </div>

                <!-- Seta Direita (Seguinte) Sobreposta à lateral direita da foto (como no Figma) -->
                <button type="button" class="vbl-slider-next absolute -right-4 sm:-right-6 lg:-right-7 xl:-right-8 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-12 sm:h-12 bg-white border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer shadow-sm" aria-label="Instalação Seguinte">
                  <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                  </svg>
                </button>

              </div>
            </div>

          </div>
          <?php endforeach; ?>
        </div>

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
     MODAL DE VÍDEO
=========================================== -->
<?php if ( ! empty( $video_embed ) ) : ?>
<div id="vblHotelVideoModal" class="fixed inset-0 z-[99999] bg-black/90 backdrop-blur-md hidden items-center justify-center p-4">
  <div class="relative w-full max-w-5xl aspect-video bg-black shadow-2xl">
    <button type="button" id="vblHotelVideoClose" class="absolute -top-12 right-0 text-white/80 hover:text-white text-sm uppercase tracking-widest flex items-center gap-2 transition-colors cursor-pointer">
      <span>Fechar</span>
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
    <iframe id="vblHotelVideoIframe" class="w-full h-full" src="" data-src="<?php echo esc_url( $video_embed ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
  </div>
</div>
<?php endif; ?>

<!-- ==========================================
     SCRIPTS ESPECÍFICOS DO TEMPLATE
=========================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // 1. Slider Instalações
  const slides = document.querySelectorAll('.vbl-instalacao-slide');
  const prevBtns = document.querySelectorAll('.vbl-slider-prev');
  const nextBtns = document.querySelectorAll('.vbl-slider-next');
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

  const thumbBtns = document.querySelectorAll('.vbl-slider-next-thumb');
  thumbBtns.forEach(thumb => {
    thumb.addEventListener('click', () => showSlide(currentSlide + 1));
  });

  // 2. Modal de Vídeo
  const videoModal = document.getElementById('vblHotelVideoModal');
  const videoOpenBtn = document.getElementById('vblHotelVideoOpen');
  const videoCloseBtn = document.getElementById('vblHotelVideoClose');
  const videoIframe = document.getElementById('vblHotelVideoIframe');

  if (videoModal && videoOpenBtn && videoIframe) {
    videoOpenBtn.addEventListener('click', function() {
      videoIframe.src = videoIframe.getAttribute('data-src');
      videoModal.classList.remove('hidden');
      videoModal.classList.add('flex');
      document.body.style.overflow = 'hidden';
    });

    const closeModal = function() {
      videoIframe.src = '';
      videoModal.classList.add('hidden');
      videoModal.classList.remove('flex');
      document.body.style.overflow = '';
    };

    if (videoCloseBtn) {
      videoCloseBtn.addEventListener('click', closeModal);
    }

    videoModal.addEventListener('click', function(e) {
      if (e.target === videoModal) {
        closeModal();
      }
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && !videoModal.classList.contains('hidden')) {
        closeModal();
      }
    });
  }
});
</script>

<?php 
// Inclui o Footer independente do Hotel
get_footer('hotel');

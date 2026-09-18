<?php
/**
 * Template Name: Hotel — Região
 * Template para a página de Região e Destino do Microsite de Hotel.
 *
 * @package Vila_Baleira
 */

// Inclui o Header Transparente com branding do Hotel
require_once VBL_DIR . '/header-hotel.php';

// ACF Fields: Hero Banner (Aéreo)
$hero_bg = vbl_field( 'vbl_hreg_hero_bg', false, vbl_img( 'hoteis/hero-foto.jpg' ) );
if ( empty( $hero_bg ) ) {
    $hero_bg = vbl_img( 'hoteis/hero-foto.jpg' );
}

// ACF Fields: Apresentação do Destino (Explore Porto Santo)
$apres_subtitle = vbl_field( 'vbl_hreg_apres_subtitle', false, 'TINCIDUNT TURPIS UT QUAM ID MORBI' );
$apres_title    = vbl_field( 'vbl_hreg_apres_title', false, 'EXPLORE<br>PORTO SANTO' );
$apres_p1       = vbl_field( 'vbl_hreg_apres_p1', false, 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt.' );
$apres_p2       = vbl_field( 'vbl_hreg_apres_p2', false, 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.' );
$apres_img      = vbl_field( 'vbl_hreg_apres_img', false, vbl_img( 'ilhas-416x480.jpg' ) );
if ( empty( $apres_img ) ) {
    $apres_img = vbl_img( 'ilhas-416x480.jpg' );
}

// ACF Fields: Banner Frase Aéreo
$frase_bg    = vbl_field( 'vbl_hreg_frase_bg', false, vbl_img( 'hoteis/frase-ilhas-bg.jpg' ) );
if ( empty( $frase_bg ) ) {
    $frase_bg = vbl_img( 'hoteis/frase-ilhas-bg.jpg' );
}
$frase_line1 = vbl_field( 'vbl_hreg_frase_line1', false, 'LOREM IPSUM DOLOR' );
$frase_line2 = vbl_field( 'vbl_hreg_frase_line2', false, 'ENIM VITAE TURPIS' );
$frase_line3 = vbl_field( 'vbl_hreg_frase_line3', false, 'LACUS EGET UT SIT.' );

// ACF Fields: Experiências da Região (Slider)
$exp_tagline = vbl_field( 'vbl_hreg_exp_tagline', false, 'ELIT FAUCIBUS ENIM ALIQUET' );
$exp_title   = vbl_field( 'vbl_hreg_exp_title', false, 'TÍTULO PARA AS<br>EXPERIÊNCIAS' );
$exp_desc    = vbl_field( 'vbl_hreg_exp_desc', false, 'Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus.' );

$experiencias_list = vbl_field( 'vbl_hreg_exp_list', false, array() );
if ( empty( $experiencias_list ) || ! is_array( $experiencias_list ) ) {
    $experiencias_list = array(
        array(
            'title'       => 'FAUCIBUS PURUS<br>SIT EU RHONCUS',
            'description' => 'Quis amet velit cursus etiam ipsum semper augue. Quam consectetur sodales mattis id commodo urna. In non vitae amet enim. Ipsum vitae felis at purus nam nibh tincidunt.' . "\n\n" . 'Lorem ipsum dolor sit amet consectetur. Habitasse elementum quam ullamcorper id euismod amet. Rhoncus faucibus eu purus quis vitae aliquam vitae. Nunc diam tempus accumsan nulla commodo sagittis.',
            'image'       => vbl_img( 'ilhas-432x240-2.jpg' ),
        ),
        array(
            'title'       => 'PASSEIOS DE BARCO<br>& MERGULHO',
            'description' => 'Explore as águas cristalinas do arquipélago, descubra recifes intactos e encantos marinhos únicos com instrutores certificados e equipamento completo incluído.' . "\n\n" . 'Uma experiência inesquecível para todas as idades, com saídas diárias a partir da marina.',
            'image'       => vbl_img( 'ilhas-432x240-1.jpg' ),
        ),
        array(
            'title'       => 'TRILHOS & MIRADOUROS<br>PANORÂMICOS',
            'description' => 'Caminhadas pelas cumeadas das montanhas vulcânicas com vistas deslumbrantes sobre toda a extensão de areal dourado e o infinito do Oceano Atlântico.' . "\n\n" . 'Desfrute da natureza em estado puro ao seu próprio ritmo.',
            'image'       => vbl_img( 'ilhas-800x720.jpg' ),
        ),
    );
}
?>

<main class="w-full bg-white text-black overflow-hidden">

  <!-- ==========================================
       1. HERO BANNER AÉREO
  =========================================== -->
  <section class="relative w-full h-[280px] sm:h-[360px] max-h-[360px] overflow-hidden bg-black flex items-end vbl-hotel-subpage-hero">
    <div class="absolute inset-0 z-0">
      <img src="<?php echo esc_url( $hero_bg ); ?>" alt="Região e Destino" class="w-full h-full object-cover object-center filter brightness-[0.85]">
      <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/50"></div>
    </div>
  </section>

  <!-- ==========================================
       2. APRESENTAÇÃO DO DESTINO (Explore Porto Santo)
  =========================================== -->
  <section class="w-full py-20 lg:py-32 bg-white">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-center">
      
      <!-- Coluna Esquerda: Fotografia Vertical com Estrela do Mar -->
      <div class="lg:col-span-5 relative flex justify-start">
        <div class="relative w-full max-w-[480px] aspect-[4/5] shadow-sm">
          <img src="<?php echo esc_url( $apres_img ); ?>" alt="<?php echo esc_attr( strip_tags( $apres_title ) ); ?>" class="w-full h-full object-cover">
          
          <!-- Estrela do mar decorativa em outline ciano sobreposta -->
          <div class="absolute -bottom-8 -right-8 lg:-bottom-12 lg:-right-12 z-10 w-44 h-44 lg:w-60 lg:h-60 pointer-events-none text-[#0da9a6]">
            <img src="<?php echo vbl_img( 'estrela.svg' ); ?>" alt="" class="w-full h-full object-contain">
          </div>
        </div>
      </div>

      <!-- Coluna Direita: Textos -->
      <div class="lg:col-span-7 flex flex-col items-start justify-center lg:pl-8">
        
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

    </div>
  </section>

  <!-- ==========================================
       3. BANNER DE FRASE AÉREO
  =========================================== -->
  <section class="relative w-full py-32 lg:py-48 flex items-center justify-center overflow-hidden">
    <!-- Imagem de Fundo Aérea -->
    <div class="absolute inset-0 w-full h-full">
      <img src="<?php echo esc_url( $frase_bg ); ?>" alt="Vista Aérea de Porto Santo" class="w-full h-full object-cover filter brightness-[0.70]">
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
       4. CARROSSEL DE EXPERIÊNCIAS DA REGIÃO
  =========================================== -->
  <section class="w-full py-20 lg:py-32 bg-white relative overflow-hidden">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%]">
      
      <!-- Cabeçalho Central -->
      <div class="text-center mb-16 flex flex-col items-center">
        <div class="flex items-center justify-center gap-4 mb-3">
          <div class="w-10 h-px bg-[#0da9a6]"></div>
          <span class="font-body text-[11px] xl:text-[12px] tracking-[2.5px] uppercase text-[#0da9a6] font-medium">
            <?php echo esc_html( $exp_tagline ); ?>
          </span>
          <div class="w-10 h-px bg-[#0da9a6]"></div>
        </div>

        <h2 class="font-display text-[42px] sm:text-[54px] lg:text-[68px] leading-[1.02] uppercase text-[#0d5257] font-normal mb-5">
          <?php echo wp_kses_post( $exp_title ); ?>
        </h2>

        <?php if ( ! empty( $exp_desc ) ) : ?>
        <p class="font-body font-light text-[14px] lg:text-[15px] text-[#333333] max-w-[620px]">
          <?php echo nl2br( esc_html( $exp_desc ) ); ?>
        </p>
        <?php endif; ?>
      </div>

      <!-- Slider de Experiências -->
      <div id="vblExperienciasRegiaoSlider" class="relative">
        
        <?php foreach ( $experiencias_list as $e_idx => $item ) : 
            $is_e_act = ( $e_idx === 0 );
        ?>
        <div class="vbl-reg-slide <?php echo $is_e_act ? 'flex' : 'hidden'; ?> flex-col lg:flex-row items-center gap-12 lg:gap-16 xl:gap-24 transition-opacity duration-500" data-reg="<?php echo esc_attr( $e_idx ); ?>">
          
          <!-- Lado Esquerdo: Fotografia + Seta Esquerda -->
          <div class="w-full lg:w-[48%] relative flex items-center justify-start">
            <!-- Seta Esquerda -->
            <button type="button" class="vbl-reg-prev absolute -left-3 sm:-left-5 top-1/2 -translate-y-1/2 z-10 w-12 h-12 border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer shadow-sm" aria-label="Experiência Anterior">
              <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M28 15H1M1 15L14 2M1 15L14 28" />
              </svg>
            </button>

            <div class="w-full aspect-[4/3] max-w-[680px] bg-gray-100 shadow-sm overflow-hidden">
              <img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( strip_tags( $item['title'] ) ); ?>" class="w-full h-full object-cover">
            </div>
          </div>

          <!-- Lado Direito: Textos + Seta Seguinte -->
          <div class="w-full lg:w-[52%] relative flex items-center justify-between">
            <div class="flex flex-col items-start pr-12 lg:pr-16">
              <h3 class="font-display text-[32px] sm:text-[40px] lg:text-[48px] leading-[1.05] uppercase text-[#0d5257] mb-6 font-normal">
                <?php echo wp_kses_post( $item['title'] ); ?>
              </h3>

              <div class="font-body font-light text-[14px] lg:text-[15px] leading-relaxed text-[#333333] max-w-[500px]">
                <?php echo nl2br( esc_html( $item['description'] ) ); ?>
              </div>
            </div>

            <!-- Seta Direita -->
            <button type="button" class="vbl-reg-next absolute -right-3 sm:-right-5 top-1/2 -translate-y-1/2 z-10 w-12 h-12 border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer shadow-sm" aria-label="Próxima Experiência">
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
     SCRIPTS DO SLIDER DE EXPERIÊNCIAS
=========================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const slides = document.querySelectorAll('.vbl-reg-slide');
  const prevBtns = document.querySelectorAll('.vbl-reg-prev');
  const nextBtns = document.querySelectorAll('.vbl-reg-next');
  let currentIdx = 0;

  function showSlide(idx) {
    if (!slides.length) return;
    if (idx >= slides.length) currentIdx = 0;
    else if (idx < 0) currentIdx = slides.length - 1;
    else currentIdx = idx;

    slides.forEach((s, i) => {
      if (i === currentIdx) {
        s.classList.remove('hidden');
        s.classList.add('flex');
      } else {
        s.classList.add('hidden');
        s.classList.remove('flex');
      }
    });
  }

  prevBtns.forEach(btn => btn.addEventListener('click', () => showSlide(currentIdx - 1)));
  nextBtns.forEach(btn => btn.addEventListener('click', () => showSlide(currentIdx + 1)));
});
</script>

<?php
// Inclui o Footer independente do Hotel
get_footer('hotel');

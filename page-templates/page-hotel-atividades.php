<?php
/**
 * Template Name: Hotel — Atividades
 * Template para a página de Atividades, Animação e Spa do Microsite de Hotel.
 *
 * @package Vila_Baleira
 */

// Inclui o Header Transparente com branding do Hotel
require_once VBL_DIR . '/header-hotel.php';

// ACF Fields: Hero Banner
$hero_bg = vbl_field( 'vbl_hativ_hero_bg', false, vbl_img( 'hoteis/hero-foto.jpg' ) );
if ( empty( $hero_bg ) ) {
    $hero_bg = vbl_img( 'hoteis/hero-foto.jpg' );
}

// ACF Fields: Apresentação das Atividades
$apres_subtitle = vbl_field( 'vbl_hativ_apres_subtitle', false, 'ATIVIDADES & ANIMAÇÃO' );
$apres_title    = vbl_field( 'vbl_hativ_apres_title', false, 'DOLOR RISUS<br>QUAM VITAE' );
$apres_p1       = vbl_field( 'vbl_hativ_apres_p1', false, 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt.' );
$apres_p2       = vbl_field( 'vbl_hativ_apres_p2', false, 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.' );
$apres_img      = vbl_field( 'vbl_hativ_apres_img', false, vbl_img( 'hoteis/porto-santo-520x400.jpg' ) );
if ( empty( $apres_img ) ) {
    $apres_img = vbl_img( 'hoteis/porto-santo-520x400.jpg' );
}

// ACF Fields: Programa Semanal
$prog_tagline   = vbl_field( 'vbl_hativ_prog_tagline', false, 'ANIMAÇÃO & ATIVIDADES' );
$prog_title     = vbl_field( 'vbl_hativ_prog_title', false, 'PROGRAMA SEMANAL' );
$prog_text      = vbl_field( 'vbl_hativ_prog_text', false, 'Gravida turpis posuere in mauris. Eget placerat pretium tempus pellentesque amet venenatis enim est. Sed id condimentum eget amet augue pretium et leo integer.' );

$programa_list  = vbl_field( 'vbl_hativ_programa_list', false, array() );
if ( empty( $programa_list ) || ! is_array( $programa_list ) ) {
    $programa_list = array(
        array(
            'title'    => 'PASSEIOS & NATUREZA',
            'schedule' => 'Segunda a Sexta · 10h00',
            'image'    => vbl_img( 'ilhas-432x240-1.jpg' ),
        ),
        array(
            'title'    => 'MÚSICA AO VIVO & CONVÍVIO',
            'schedule' => 'Todos os dias · 17h00',
            'image'    => vbl_img( 'sobre-fotografia.jpg' ),
        ),
        array(
            'title'    => 'ATIVIDADES EQUITATIVAS',
            'schedule' => 'Terças e Quintas · 11h00',
            'image'    => vbl_img( 'ilhas-432x240-2.jpg' ),
        ),
        array(
            'title'    => 'AQUAGYM & FITNESS',
            'schedule' => 'Segunda a Sábado · 09h30',
            'image'    => vbl_img( 'hoteis/porto-santo-760x760.jpg' ),
        ),
    );
}

// ACF Fields: Animação Noturna / Kids Club (Slider)
$anim_subtitle = vbl_field( 'vbl_hativ_anim_subtitle', false, 'CLUBE INFANTIL / ANIMAÇÃO' );
$anim_slides   = vbl_field( 'vbl_hativ_anim_slides', false, array() );
if ( empty( $anim_slides ) || ! is_array( $anim_slides ) ) {
    $anim_slides = array(
        array(
            'title'       => 'FELIS NAM',
            'description' => 'Quam consectetur sodales mattis id commodo urna. In non vitae amet enim. Ipsum vitae felis at purus nam nibh tincidunt. Lorem ipsum dolor sit amet consectetur. Habitasse elementum quam ullamcorper id euismod amet.' . "\n\n" . 'Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.',
            'image'       => vbl_img( 'noticias/noticias-1.jpg' ),
        ),
        array(
            'title'       => 'ESPETÁCULOS NOTURNOS',
            'description' => 'Momentos únicos de celebração com música, teatro e animação para toda a família num palco ao ar livre com vista para o mar.',
            'image'       => vbl_img( 'noticias/noticias-2.jpg' ),
        ),
    );
}

// ACF Fields: Banner Vídeo Spa
$spa_tagline = vbl_field( 'vbl_hativ_spa_tagline', false, 'BEM-ESTAR & SPA' );
$spa_title   = vbl_field( 'vbl_hativ_spa_title', false, 'UM TÍTULO<br><em>SOBRE O SPA</em>' );
$spa_bg      = vbl_field( 'vbl_hativ_spa_bg', false, vbl_img( 'hoteis/village-680x400.jpg' ) );
if ( empty( $spa_bg ) ) {
    $spa_bg = vbl_img( 'hoteis/village-680x400.jpg' );
}
$spa_video_url   = vbl_field( 'vbl_hativ_spa_video_url', false, 'https://www.youtube.com/watch?v=WN8c9XwUx9s' );
$spa_video_embed = vbl_get_youtube_embed_url( $spa_video_url );

// ACF Fields: Tratamentos & Programas
$trat_tagline = vbl_field( 'vbl_hativ_trat_tagline', false, 'TRATAMENTOS & SPA' );
$trat_title   = vbl_field( 'vbl_hativ_trat_title', false, 'TÍTULO PARA OS<br>PROGRAMAS' );
$trat_desc    = vbl_field( 'vbl_hativ_trat_desc', false, 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod.' );

$tratamentos_list = vbl_field( 'vbl_hativ_tratamentos_list', false, array() );
if ( empty( $tratamentos_list ) || ! is_array( $tratamentos_list ) ) {
    $tratamentos_list = array(
        array(
            'title'       => 'PURUS SIT EU<br>RHONCUS FAUCIBUS',
            'description' => 'Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus.' . "\n\n" . 'Gravida turpis posuere in mauris. Eget placerat pretium tempus pellentesque amet venenatis enim est. Sed id condimentum eget amet augue pretium et leo integer. Neque eu ut vulputate nisi sed.',
            'image'       => vbl_img( 'hoteis/porto-santo-520x400.jpg' ),
        ),
        array(
            'title'       => 'MASSAGEM DE ASSINATURA<br>VILA BALEIRA',
            'description' => 'Técnica exclusiva desenvolvida para libertar tensões musculares, aliando óleos essenciais naturais à envolvência do clima calmo das ilhas.',
            'image'       => vbl_img( 'hoteis/suites-680x400.jpg' ),
        ),
    );
}
?>

<main class="w-full bg-white text-black overflow-hidden">

  <!-- ==========================================
       1. HERO BANNER
  =========================================== -->
  <section class="relative w-full h-[280px] sm:h-[360px] max-h-[360px] overflow-hidden bg-black flex items-end vbl-hotel-subpage-hero">
    <div class="absolute inset-0 z-0">
      <img src="<?php echo esc_url( $hero_bg ); ?>" alt="Atividades" class="w-full h-full object-cover object-center filter brightness-[0.85]">
      <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/50"></div>
    </div>
  </section>

  <!-- ==========================================
       2. APRESENTAÇÃO DAS ATIVIDADES
  =========================================== -->
  <section class="w-full py-20 lg:py-32 bg-white">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-center">
      
      <!-- Coluna Esquerda: Fotografia Vertical (Crianças / Diversão) -->
      <div class="lg:col-span-5 relative flex justify-start">
        <div class="relative w-full max-w-[480px] aspect-[4/5] shadow-sm">
          <img src="<?php echo esc_url( $apres_img ); ?>" alt="<?php echo esc_attr( strip_tags( $apres_title ) ); ?>" class="w-full h-full object-cover">
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
       3. PROGRAMA SEMANAL (Carrossel 3 Colunas)
  =========================================== -->
  <section class="w-full py-20 lg:py-32 bg-[#E8F5F5] relative overflow-hidden">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] relative">
      
      <!-- Cabeçalho Central -->
      <div class="text-center mb-16 flex flex-col items-center">
        <div class="flex items-center justify-center gap-4 mb-3">
          <div class="w-10 h-px bg-[#0da9a6]"></div>
          <span class="font-body text-[11px] xl:text-[12px] tracking-[2.5px] uppercase text-[#0da9a6] font-medium">
            <?php echo esc_html( $prog_tagline ); ?>
          </span>
          <div class="w-10 h-px bg-[#0da9a6]"></div>
        </div>

        <h2 class="font-display text-[42px] sm:text-[56px] lg:text-[68px] leading-[1.02] uppercase text-[#0d5257] font-normal mb-5">
          <?php echo esc_html( $prog_title ); ?>
        </h2>

        <?php if ( ! empty( $prog_text ) ) : ?>
        <p class="font-body font-light text-[14px] lg:text-[15px] text-[#333333] max-w-[580px]">
          <?php echo nl2br( esc_html( $prog_text ) ); ?>
        </p>
        <?php endif; ?>
      </div>

      <!-- Slider Programa Semanal -->
      <div class="relative">
        
        <!-- Seta Esquerda -->
        <button type="button" id="vblProgPrev" class="absolute -left-2 lg:-left-5 top-1/2 -translate-y-1/2 z-20 w-12 h-12 border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer shadow-sm" aria-label="Atividade Anterior">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M28 15H1M1 15L14 2M1 15L14 28" />
          </svg>
        </button>

        <!-- Seta Direita -->
        <button type="button" id="vblProgNext" class="absolute -right-2 lg:-right-5 top-1/2 -translate-y-1/2 z-20 w-12 h-12 border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer shadow-sm" aria-label="Próxima Atividade">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 15H28M28 15L15 2M28 15L15 28" />
          </svg>
        </button>

        <div class="overflow-hidden px-2 sm:px-6">
          <div id="vblProgTrack" class="flex transition-transform duration-500 ease-out gap-6 lg:gap-8">
            <?php foreach ( $programa_list as $item ) : 
                $p_title    = ! empty( $item['title'] ) ? $item['title'] : '';
                $p_schedule = ! empty( $item['schedule'] ) ? $item['schedule'] : '';
                $p_img      = ! empty( $item['image'] ) ? $item['image'] : vbl_img( 'ilhas-432x240-1.jpg' );
            ?>
            <div class="vbl-prog-card w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-22px)] flex-shrink-0 flex flex-col items-start bg-transparent group">
              <div class="w-full aspect-[16/10] overflow-hidden bg-white shadow-sm mb-5">
                <img src="<?php echo esc_url( $p_img ); ?>" alt="<?php echo esc_attr( $p_title ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
              </div>
              <h3 class="font-display text-[18px] xl:text-[20px] leading-snug uppercase text-[#0d5257] mb-2 group-hover:text-[#0da9a6] transition-colors">
                <?php echo esc_html( $p_title ); ?>
              </h3>
              <p class="font-body font-light text-[12px] xl:text-[13px] tracking-wide text-[#333333]/80">
                <?php echo esc_html( $p_schedule ); ?>
              </p>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

    </div>
  </section>

  <!-- ==========================================
       4. ANIMAÇÃO NOTURNA / KIDS CLUB (Slider Bipartido)
  =========================================== -->
  <section class="w-full py-24 lg:py-36 bg-white overflow-hidden">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] relative">
      
      <div id="vblAnimSlider" class="relative">
        <?php foreach ( $anim_slides as $idx => $slide ) : 
            $is_act = ( $idx === 0 );
        ?>
        <div class="vbl-anim-slide <?php echo $is_act ? 'flex' : 'hidden'; ?> flex-col lg:flex-row items-center gap-12 lg:gap-16 xl:gap-24 transition-opacity duration-500" data-anim="<?php echo esc_attr( $idx ); ?>">
          
          <!-- Lado Esquerdo: Textos + Seta Anterior -->
          <div class="w-full lg:w-[48%] flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-4 mb-3">
                <div class="w-8 h-px bg-[#0da9a6] flex-shrink-0"></div>
                <span class="font-body text-[10px] xl:text-[11px] tracking-[2px] uppercase text-[#0da9a6] font-medium">
                  <?php echo esc_html( $anim_subtitle ); ?>
                </span>
              </div>

              <h2 class="font-display text-[44px] sm:text-[56px] lg:text-[68px] xl:text-[78px] leading-[1.02] tracking-[1.5px] uppercase text-[#0d5257] mb-6">
                <?php echo esc_html( $slide['title'] ); ?>
              </h2>

              <div class="font-body font-light text-[14px] xl:text-[15px] leading-relaxed text-[#333333] mb-10 max-w-[480px]">
                <?php echo nl2br( esc_html( $slide['description'] ) ); ?>
              </div>
            </div>

            <!-- Seta Esquerda -->
            <button type="button" class="vbl-anim-prev w-12 h-12 border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer" aria-label="Slide Anterior">
              <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M28 15H1M1 15L14 2M1 15L14 28" />
              </svg>
            </button>
          </div>

          <!-- Lado Direito: Fotografia + Seta Seguinte -->
          <div class="w-full lg:w-[52%] relative flex items-center justify-end">
            <div class="w-full aspect-[4/3] max-w-[760px] bg-gray-100 shadow-sm relative overflow-hidden">
              <img src="<?php echo esc_url( $slide['image'] ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>" class="w-full h-full object-cover">
            </div>

            <!-- Seta Direita -->
            <button type="button" class="vbl-anim-next absolute -right-3 sm:-right-5 top-1/2 -translate-y-1/2 z-10 w-12 h-12 border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer shadow-sm" aria-label="Slide Seguinte">
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
       5. BANNER DE VÍDEO SPA
  =========================================== -->
  <section class="relative w-full h-[520px] md:h-[620px] lg:h-[720px] min-h-[520px] lg:min-h-[720px] overflow-hidden bg-black flex items-center justify-center">
    <!-- Imagem de Fundo -->
    <div class="absolute inset-0 z-0">
      <img src="<?php echo esc_url( $spa_bg ); ?>" alt="Spa & Bem-Estar" class="w-full h-full object-cover object-center filter brightness-[0.75]">
      <div class="absolute inset-0 bg-black/25"></div>
    </div>

    <!-- Conteúdo Centralizado / Layout com Play Button à Direita -->
    <div class="relative z-10 max-w-[1920px] w-full mx-auto px-6 xl:px-[8.33%] flex flex-col md:flex-row items-center justify-between gap-12">
      
      <!-- Lado Esquerdo: Tagline e Título (Alinhados ao Centro) -->
      <div class="flex flex-col items-center text-center gap-4">
        <div class="flex items-center justify-center gap-4">
          <div class="w-8 lg:w-10 h-px bg-[#0da9a6] flex-shrink-0"></div>
          <span class="font-body text-[11px] xl:text-[13px] tracking-[2px] uppercase text-white/90 font-light text-center">
            <?php echo esc_html( $spa_tagline ); ?>
          </span>
          <div class="w-8 lg:w-10 h-px bg-[#0da9a6] flex-shrink-0"></div>
        </div>
        <h2 class="font-display text-[40px] sm:text-[54px] md:text-[64px] lg:text-[76px] xl:text-[88px] uppercase tracking-[2px] text-white leading-[1.05] drop-shadow-md text-center">
          <?php echo wp_kses_post( $spa_title ); ?>
        </h2>
      </div>

      <!-- Lado Direito: Botão Play de Vídeo -->
      <?php if ( ! empty( $spa_video_url ) ) : ?>
      <div class="flex items-center justify-center md:pr-12 lg:pr-20 xl:pr-28">
        <button type="button" id="vblSpaVideoOpen" class="cursor-pointer opacity-80 hover:opacity-100 hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none w-[110px] sm:w-[130px] lg:w-[150px] xl:w-[170px]" aria-label="Reproduzir Vídeo do Spa">
          <img src="<?php echo vbl_img( 'ogrupo/play-icon.svg' ); ?>" alt="Reproduzir" class="w-full h-auto">
        </button>
      </div>
      <?php endif; ?>

    </div>
  </section>

  <!-- ==========================================
       6. TRATAMENTOS & PROGRAMAS DE SPA (Slider)
  =========================================== -->
  <section class="w-full py-20 lg:py-32 bg-white relative overflow-hidden">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%]">
      
      <!-- Cabeçalho Central -->
      <div class="text-center mb-16 flex flex-col items-center">
        <div class="flex items-center justify-center gap-4 mb-3">
          <div class="w-10 h-px bg-[#0da9a6]"></div>
          <span class="font-body text-[11px] xl:text-[12px] tracking-[2.5px] uppercase text-[#0da9a6] font-medium">
            <?php echo esc_html( $trat_tagline ); ?>
          </span>
          <div class="w-10 h-px bg-[#0da9a6]"></div>
        </div>

        <h2 class="font-display text-[42px] sm:text-[54px] lg:text-[68px] leading-[1.02] uppercase text-[#0d5257] font-normal mb-5">
          <?php echo wp_kses_post( $trat_title ); ?>
        </h2>

        <?php if ( ! empty( $trat_desc ) ) : ?>
        <p class="font-body font-light text-[14px] lg:text-[15px] text-[#333333] max-w-[580px]">
          <?php echo nl2br( esc_html( $trat_desc ) ); ?>
        </p>
        <?php endif; ?>
      </div>

      <!-- Slider de Tratamentos -->
      <div id="vblTratamentosSlider" class="relative">
        
        <?php foreach ( $tratamentos_list as $t_idx => $trat ) : 
            $is_t_act = ( $t_idx === 0 );
        ?>
        <div class="vbl-trat-slide <?php echo $is_t_act ? 'flex' : 'hidden'; ?> flex-col lg:flex-row items-center gap-12 lg:gap-16 xl:gap-24 transition-opacity duration-500" data-trat="<?php echo esc_attr( $t_idx ); ?>">
          
          <!-- Lado Esquerdo: Fotografia + Seta Esquerda -->
          <div class="w-full lg:w-[48%] relative flex items-center justify-start">
            <!-- Seta Esquerda -->
            <button type="button" class="vbl-trat-prev absolute -left-3 sm:-left-5 top-1/2 -translate-y-1/2 z-10 w-12 h-12 border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer shadow-sm" aria-label="Tratamento Anterior">
              <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M28 15H1M1 15L14 2M1 15L14 28" />
              </svg>
            </button>

            <div class="w-full aspect-[4/3] max-w-[680px] bg-gray-100 shadow-sm overflow-hidden">
              <img src="<?php echo esc_url( $trat['image'] ); ?>" alt="<?php echo esc_attr( strip_tags( $trat['title'] ) ); ?>" class="w-full h-full object-cover">
            </div>
          </div>

          <!-- Lado Direito: Textos + Seta Seguinte -->
          <div class="w-full lg:w-[52%] relative flex items-center justify-between">
            <div class="flex flex-col items-start pr-12 lg:pr-16">
              <h3 class="font-display text-[32px] sm:text-[40px] lg:text-[48px] leading-[1.05] uppercase text-[#0d5257] mb-6">
                <?php echo wp_kses_post( $trat['title'] ); ?>
              </h3>

              <div class="font-body font-light text-[14px] lg:text-[15px] leading-relaxed text-[#333333] max-w-[500px]">
                <?php echo nl2br( esc_html( $trat['description'] ) ); ?>
              </div>
            </div>

            <!-- Seta Direita -->
            <button type="button" class="vbl-trat-next absolute -right-3 sm:-right-5 top-1/2 -translate-y-1/2 z-10 w-12 h-12 border border-[#00B5B4] text-[#00B5B4] hover:bg-[#00B5B4] hover:text-white flex items-center justify-center transition-colors cursor-pointer shadow-sm" aria-label="Próximo Tratamento">
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
       7. NEWSLETTER DO MICROSITE
  =========================================== -->
  <?php
  if ( file_exists( VBL_DIR . '/template-parts/section-newsletter.php' ) ) {
      get_template_part( 'template-parts/section-newsletter' );
  }
  ?>

</main>

<!-- ==========================================
     MODAL DE VÍDEO DO SPA
=========================================== -->
<?php if ( ! empty( $spa_video_embed ) ) : ?>
<div id="vblSpaVideoModal" class="fixed inset-0 z-[99999] bg-black/90 backdrop-blur-md hidden items-center justify-center p-4">
  <div class="relative w-full max-w-5xl aspect-video bg-black shadow-2xl">
    <button type="button" id="vblSpaVideoClose" class="absolute -top-12 right-0 text-white/80 hover:text-white text-sm uppercase tracking-widest flex items-center gap-2 transition-colors cursor-pointer">
      <span>Fechar</span>
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
    <iframe id="vblSpaVideoIframe" class="w-full h-full" src="" data-src="<?php echo esc_url( $spa_video_embed ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
  </div>
</div>
<?php endif; ?>

<!-- ==========================================
     SCRIPTS DE INTERAÇÃO DOS SLIDERS
=========================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // 1. Slider Programa Semanal
  const progTrack = document.getElementById('vblProgTrack');
  const progPrev = document.getElementById('vblProgPrev');
  const progNext = document.getElementById('vblProgNext');
  const progCards = document.querySelectorAll('.vbl-prog-card');

  if (progTrack && progCards.length) {
    let currentProgIdx = 0;

    function getProgPerView() {
      if (window.innerWidth >= 1024) return 3;
      if (window.innerWidth >= 640) return 2;
      return 1;
    }

    function getMaxProgIdx() {
      return Math.max(0, progCards.length - getProgPerView());
    }

    function updateProgSlider() {
      const cardWidth = progCards[0].getBoundingClientRect().width;
      const gap = window.innerWidth >= 1024 ? 32 : 24;
      progTrack.style.transform = `translateX(-${currentProgIdx * (cardWidth + gap)}px)`;
    }

    if (progNext) {
      progNext.addEventListener('click', function() {
        if (currentProgIdx < getMaxProgIdx()) {
          currentProgIdx++;
        } else {
          currentProgIdx = 0;
        }
        updateProgSlider();
      });
    }

    if (progPrev) {
      progPrev.addEventListener('click', function() {
        if (currentProgIdx > 0) {
          currentProgIdx--;
        } else {
          currentProgIdx = getMaxProgIdx();
        }
        updateProgSlider();
      });
    }

    window.addEventListener('resize', function() {
      if (currentProgIdx > getMaxProgIdx()) {
        currentProgIdx = getMaxProgIdx();
      }
      updateProgSlider();
    });
  }

  // 2. Slider Animação Noturna
  const animSlides = document.querySelectorAll('.vbl-anim-slide');
  const animPrevs = document.querySelectorAll('.vbl-anim-prev');
  const animNexts = document.querySelectorAll('.vbl-anim-next');
  let currentAnim = 0;

  function showAnimSlide(idx) {
    if (!animSlides.length) return;
    if (idx >= animSlides.length) currentAnim = 0;
    else if (idx < 0) currentAnim = animSlides.length - 1;
    else currentAnim = idx;

    animSlides.forEach((s, i) => {
      if (i === currentAnim) {
        s.classList.remove('hidden');
        s.classList.add('flex');
      } else {
        s.classList.add('hidden');
        s.classList.remove('flex');
      }
    });
  }

  animPrevs.forEach(btn => btn.addEventListener('click', () => showAnimSlide(currentAnim - 1)));
  animNexts.forEach(btn => btn.addEventListener('click', () => showAnimSlide(currentAnim + 1)));

  // 3. Slider Tratamentos de Spa
  const tratSlides = document.querySelectorAll('.vbl-trat-slide');
  const tratPrevs = document.querySelectorAll('.vbl-trat-prev');
  const tratNexts = document.querySelectorAll('.vbl-trat-next');
  let currentTrat = 0;

  function showTratSlide(idx) {
    if (!tratSlides.length) return;
    if (idx >= tratSlides.length) currentTrat = 0;
    else if (idx < 0) currentTrat = tratSlides.length - 1;
    else currentTrat = idx;

    tratSlides.forEach((s, i) => {
      if (i === currentTrat) {
        s.classList.remove('hidden');
        s.classList.add('flex');
      } else {
        s.classList.add('hidden');
        s.classList.remove('flex');
      }
    });
  }

  tratPrevs.forEach(btn => btn.addEventListener('click', () => showTratSlide(currentTrat - 1)));
  tratNexts.forEach(btn => btn.addEventListener('click', () => showTratSlide(currentTrat + 1)));

  // 4. Modal de Vídeo do Spa
  const spaModal = document.getElementById('vblSpaVideoModal');
  const spaOpenBtn = document.getElementById('vblSpaVideoOpen');
  const spaCloseBtn = document.getElementById('vblSpaVideoClose');
  const spaIframe = document.getElementById('vblSpaVideoIframe');

  if (spaModal && spaOpenBtn && spaIframe) {
    spaOpenBtn.addEventListener('click', function() {
      spaIframe.src = spaIframe.getAttribute('data-src');
      spaModal.classList.remove('hidden');
      spaModal.classList.add('flex');
      document.body.style.overflow = 'hidden';
    });

    const closeSpaModal = function() {
      spaIframe.src = '';
      spaModal.classList.add('hidden');
      spaModal.classList.remove('flex');
      document.body.style.overflow = '';
    };

    if (spaCloseBtn) spaCloseBtn.addEventListener('click', closeSpaModal);
    spaModal.addEventListener('click', function(e) {
      if (e.target === spaModal) closeSpaModal();
    });
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && !spaModal.classList.contains('hidden')) closeSpaModal();
    });
  }
});
</script>

<?php
// Inclui o Footer independente do Hotel
get_footer('hotel');

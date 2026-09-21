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
            'title'    => "UM TÍTULO DE EXEMPLO<br>PARA ATIVIDADE OU<br>ENTRETENIMENTO",
            'days'     => 'De terça-feira a domingo',
            'hours'    => 'Das 12h00 às 16h00',
            'location' => 'Porto Santo',
            'image'    => vbl_img( 'ilhas-432x240-1.jpg' ),
        ),
        array(
            'title'    => "UM TÍTULO DE EXEMPLO<br>PARA ATIVIDADE OU<br>ENTRETENIMENTO",
            'days'     => 'Todas as sextas-feiras',
            'hours'    => 'Das 15h00 às 19h00',
            'location' => 'Bar de Praia',
            'image'    => vbl_img( 'sobre-fotografia.jpg' ),
        ),
        array(
            'title'    => "UM TÍTULO DE EXEMPLO<br>PARA ATIVIDADE OU<br>ENTRETENIMENTO",
            'days'     => 'Todos os dias',
            'hours'    => 'Das 07h00 às 11h00',
            'location' => 'Porto Santo',
            'image'    => vbl_img( 'ilhas-432x240-2.jpg' ),
        ),
        array(
            'title'    => "AQUAGYM & FITNESS<br>AULA ABERTA",
            'days'     => 'Segunda a Sábado',
            'hours'    => 'Das 09h30 às 10h30',
            'location' => 'Piscina Exterior',
            'image'    => vbl_img( 'hoteis/porto-santo-760x760.jpg' ),
        ),
    );
}

// ACF Fields: Animação Noturna / Kids Club (Slider)
$anim_subtitle = vbl_field( 'vbl_hativ_anim_subtitle', false, 'CLUBES OU ESPAÇOS TEMÁTICOS' );
$anim_slides   = vbl_field( 'vbl_hativ_anim_slides', false, array() );
if ( empty( $anim_slides ) || ! is_array( $anim_slides ) ) {
    $anim_slides = array(
        array(
            'title'       => 'FELIS NAM',
            'description' => "Quis amet velit cursus etiam ipsum semper augue. Quam consectetur sodales mattis id commodo urna. In non vitae amet enim. Habitasse elementum quam ullamcorper id euismod amet. Ipsum vitae felis at purus nam nibh tincidunt. Lorem ipsum dolor sit amet consectetur.\n\nRhoncus faucibus eu purus quis vitae aliquam vitae. Nunc diam tempus accumsan nulla commodo sagittis.",
            'image'       => vbl_img( 'noticias/noticias-1.jpg' ),
        ),
        array(
            'title'       => 'ESPETÁCULOS NOTURNOS',
            'description' => "Momentos únicos de celebração com música, teatro e animação para toda a família num palco ao ar livre com vista para o mar.\n\nNisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.",
            'image'       => vbl_img( 'noticias/noticias-2.jpg' ),
        ),
    );
}

// ACF Fields: Banner Vídeo Spa
$spa_tagline = vbl_field( 'vbl_hativ_spa_tagline', false, 'BEM-ESTAR & SPA' );
$spa_title   = vbl_field( 'vbl_hativ_spa_title', false, 'UM TÍTULO<br><em>SOBRE O SPA</em>' );
if ( strpos( $spa_title, '<em' ) === false && strpos( $spa_title, '<br' ) !== false ) {
    $parts = preg_split( '/<br\s*\/?>/i', $spa_title, 2 );
    if ( count( $parts ) === 2 ) {
        $spa_title = $parts[0] . '<br><em>' . $parts[1] . '</em>';
    }
}
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
      
      <!-- Coluna Esquerda: Fotografia (Apresentação Atividades) -->
      <div class="lg:col-span-5 relative flex justify-start">
        <div class="relative w-full max-w-[560px] xl:max-w-[620px] aspect-square shadow-sm">
          <img src="<?php echo esc_url( $apres_img ); ?>" alt="<?php echo esc_attr( strip_tags( $apres_title ) ); ?>" class="w-full h-full object-cover">
        </div>
      </div>

      <!-- Coluna Direita: Textos -->
      <div class="lg:col-span-7 flex flex-col items-start justify-center lg:pl-6 xl:pl-8">
        
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

        <!-- Parágrafos de Apresentação Indentados como no Design -->
        <div class="flex flex-col gap-6 max-w-[480px] xl:max-w-[520px] lg:ml-12 xl:ml-16 font-body font-light text-[14px] lg:text-[15px] xl:text-[16px] leading-relaxed text-[#333333]">
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
       3. PROGRAMA SEMANAL (Carrossel Full-Width)
  =========================================== -->
  <section class="w-full py-20 lg:py-32 bg-[#E8F5F5] relative overflow-hidden">
    
    <!-- Cabeçalho Alinhado à Esquerda (Como no Design) -->
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%]">
      
      <!-- Subtítulo com Traço Ciano à Esquerda -->
      <div class="flex items-center gap-4 mb-4">
        <div class="w-8 lg:w-10 h-px bg-[#0da9a6] flex-shrink-0"></div>
        <span class="font-body text-[11px] xl:text-[12px] tracking-[2.5px] uppercase text-[#0da9a6] font-medium">
          <?php echo esc_html( $prog_tagline ); ?>
        </span>
      </div>

      <!-- Título Principal Display à Esquerda -->
      <h2 class="font-display text-[44px] sm:text-[58px] lg:text-[72px] xl:text-[84px] leading-[1.02] tracking-[1.5px] uppercase text-[#0d5257] font-normal mb-6">
        <?php echo esc_html( $prog_title ); ?>
      </h2>

      <!-- Parágrafo Introdutório Indentado à Direita -->
      <?php if ( ! empty( $prog_text ) ) : ?>
      <div class="lg:ml-12 xl:ml-16 max-w-[540px] xl:max-w-[580px] font-body font-light text-[14px] lg:text-[15px] xl:text-[16px] leading-relaxed text-[#333333]">
        <p><?php echo nl2br( esc_html( $prog_text ) ); ?></p>
      </div>
      <?php endif; ?>

    </div>

    <!-- Slider Programa Semanal (Full-Width / Ocupa Toda a Largura) -->
    <div id="vblProgCarouselWrap" class="relative w-full overflow-hidden mt-12 lg:mt-16">
      
      <!-- Track do Carrossel -->
      <div id="vblProgTrack" class="flex items-start transition-transform duration-500 ease-out will-change-transform gap-6 sm:gap-8 lg:gap-10">
        <?php foreach ( $programa_list as $p_idx => $item ) : 
            $p_title    = ! empty( $item['title'] ) ? $item['title'] : '';
            $p_days     = ! empty( $item['days'] ) ? $item['days'] : '';
            $p_hours    = ! empty( $item['hours'] ) ? $item['hours'] : '';
            $p_location = ! empty( $item['location'] ) ? $item['location'] : '';
            $p_img      = ! empty( $item['image'] ) ? $item['image'] : vbl_img( 'ilhas-432x240-1.jpg' );

            // Fallback para schedule legado
            if ( empty( $p_days ) && ! empty( $item['schedule'] ) ) {
                $parts = explode( '·', $item['schedule'] );
                $p_days = trim( $parts[0] );
                if ( isset( $parts[1] ) ) {
                    $p_hours = trim( $parts[1] );
                }
            }
            if ( empty( $p_location ) ) {
                $p_location = 'Porto Santo';
            }
        ?>
        <div class="vbl-prog-card w-[82vw] sm:w-[480px] md:w-[540px] lg:w-[600px] xl:w-[650px] flex-shrink-0 flex flex-col items-start bg-transparent transition-opacity duration-500 cursor-pointer select-none" data-prog-idx="<?php echo esc_attr( $p_idx ); ?>">
          
          <!-- Fotografia da Atividade -->
          <div class="vbl-prog-img-wrap w-full aspect-[16/10] overflow-hidden bg-white shadow-sm mb-5">
            <img src="<?php echo esc_url( $p_img ); ?>" alt="<?php echo esc_attr( strip_tags( $p_title ) ); ?>" class="w-full h-full object-cover">
          </div>

          <!-- Linha Inferior: Título à Esquerda e Ícones à Direita -->
          <div class="w-full grid grid-cols-1 sm:grid-cols-12 gap-4 lg:gap-6 items-start">
            
            <!-- Esquerda: Título -->
            <div class="sm:col-span-7">
              <h3 class="font-display text-[16px] sm:text-[18px] lg:text-[20px] xl:text-[21px] leading-[1.18] uppercase text-[#0d5257] font-normal">
                <?php echo wp_kses_post( $p_title ); ?>
              </h3>
            </div>

            <!-- Direita: Informações com Ícones Ciano -->
            <div class="sm:col-span-5 flex flex-col gap-2 font-body font-light text-[11px] sm:text-[12px] xl:text-[12.5px] leading-snug text-[#0d5257]">
              
              <?php if ( ! empty( $p_days ) ) : ?>
              <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-[#00B5B4] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span><?php echo esc_html( $p_days ); ?></span>
              </div>
              <?php endif; ?>

              <?php if ( ! empty( $p_hours ) ) : ?>
              <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-[#00B5B4] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span><?php echo esc_html( $p_hours ); ?></span>
              </div>
              <?php endif; ?>

              <?php if ( ! empty( $p_location ) ) : ?>
              <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-[#00B5B4] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span><?php echo esc_html( $p_location ); ?></span>
              </div>
              <?php endif; ?>

            </div>

          </div>

        </div>
        <?php endforeach; ?>
      </div>

      <!-- Setas Sobrepostas às Bordas da Foto Central (Posição como no Design) -->
      <div id="vblProgArrowsWrapper" class="pointer-events-none absolute left-1/2 -translate-x-1/2 z-20 flex items-center justify-between w-[82vw] sm:w-[480px] md:w-[540px] lg:w-[600px] xl:w-[650px]">
        <!-- Seta Esquerda -->
        <button type="button" id="vblProgPrev" class="pointer-events-auto -translate-x-1/2 w-11 h-11 sm:w-12 sm:h-12 bg-transparent border border-[#00B5B4] text-[#00B5B4] hover:bg-white hover:text-[#00B5B4] hover:border-[#00B5B4] flex items-center justify-center transition-all duration-300 cursor-pointer shadow-xs hover:shadow-sm" aria-label="Atividade Anterior">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7" />
          </svg>
        </button>

        <!-- Seta Direita -->
        <button type="button" id="vblProgNext" class="pointer-events-auto translate-x-1/2 w-11 h-11 sm:w-12 sm:h-12 bg-transparent border border-[#00B5B4] text-[#00B5B4] hover:bg-white hover:text-[#00B5B4] hover:border-[#00B5B4] flex items-center justify-center transition-all duration-300 cursor-pointer shadow-xs hover:shadow-sm" aria-label="Próxima Atividade">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7" />
          </svg>
        </button>
      </div>

    </div>
  </section>

  <!-- ==========================================
       4. CLUBES OU ESPAÇOS TEMÁTICOS (Slider Bipartido)
  =========================================== -->
  <section class="w-full py-24 lg:py-36 bg-white overflow-hidden">
    <div class="max-w-[1920px] mx-auto px-4 sm:px-8 xl:px-[5%] 2xl:px-[8.33%] relative">
      
      <div id="vblAnimSlider" class="relative px-6 sm:px-12 md:px-16 lg:px-20 xl:px-24">
        
        <!-- Seta Esquerda (Alinhada à margem esquerda, centrada verticalmente como no Figma) -->
        <button type="button" id="vblAnimPrev" class="vbl-anim-prev absolute left-0 sm:left-2 lg:left-4 xl:left-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-12 sm:h-12 bg-transparent border border-[#00B5B4] text-[#00B5B4] hover:bg-white hover:text-[#00B5B4] hover:border-[#00B5B4] flex items-center justify-center transition-all duration-300 cursor-pointer shadow-xs hover:shadow-sm" aria-label="Slide Anterior">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7" />
          </svg>
        </button>

        <!-- Seta Direita (Alinhada à margem direita, centrada verticalmente como no Figma) -->
        <button type="button" id="vblAnimNext" class="vbl-anim-next absolute right-0 sm:right-2 lg:right-4 xl:right-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-12 sm:h-12 bg-transparent border border-[#00B5B4] text-[#00B5B4] hover:bg-white hover:text-[#00B5B4] hover:border-[#00B5B4] flex items-center justify-center transition-all duration-300 cursor-pointer shadow-xs hover:shadow-sm" aria-label="Slide Seguinte">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7" />
          </svg>
        </button>

        <!-- Slides Wrapper -->
        <div class="relative w-full">
          <?php foreach ( $anim_slides as $idx => $slide ) : 
              $is_act = ( $idx === 0 );
          ?>
          <div class="vbl-anim-slide <?php echo $is_act ? 'flex' : 'hidden'; ?> flex-col lg:flex-row items-center justify-between gap-10 lg:gap-14 xl:gap-20 transition-opacity duration-500" data-anim="<?php echo esc_attr( $idx ); ?>">
            
            <!-- Lado Esquerdo: Subtítulo, Título e Parágrafos Indentados -->
            <div class="w-full lg:w-[48%] xl:w-[46%] flex flex-col items-start justify-center">
              
              <!-- Subtítulo com Traço Ciano -->
              <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-px bg-[#0da9a6] flex-shrink-0"></div>
                <span class="font-body text-[10px] sm:text-[11px] xl:text-[12px] tracking-[2px] uppercase text-[#0da9a6] font-medium">
                  <?php echo esc_html( $anim_subtitle ); ?>
                </span>
              </div>

              <!-- Título Serif Display -->
              <h2 class="font-display text-[44px] sm:text-[56px] lg:text-[68px] xl:text-[78px] leading-[1.02] tracking-[1.5px] uppercase text-[#0d5257] mb-8 lg:mb-10 font-normal">
                <?php echo esc_html( $slide['title'] ); ?>
              </h2>

              <!-- Parágrafos de Texto Indentados à Direita como no Figma -->
              <div class="lg:ml-12 xl:ml-16 max-w-[460px] xl:max-w-[500px] flex flex-col gap-5 font-body font-light text-[13px] sm:text-[14px] xl:text-[15px] leading-relaxed text-[#4a4a4a]">
                <?php echo nl2br( esc_html( $slide['description'] ) ); ?>
              </div>

            </div>

            <!-- Lado Direito: Fotografia com Proporção do Design -->
            <div class="w-full lg:w-[50%] xl:w-[52%] flex items-center justify-center lg:justify-end">
              <div class="relative w-full max-w-[560px] lg:max-w-[620px] xl:max-w-[680px] aspect-[1.12/1] bg-gray-100 shadow-sm overflow-hidden">
                <img src="<?php echo esc_url( $slide['image'] ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>" class="w-full h-full object-cover">
              </div>
            </div>

          </div>
          <?php endforeach; ?>
        </div>

      </div>

    </div>
  </section>

  <!-- ==========================================
       5. BANNER DE VÍDEO SPA
  =========================================== -->
  <section id="vblSpaBanner" class="relative w-full h-[560px] md:h-[640px] lg:h-[720px] min-h-[560px] md:min-h-[640px] lg:min-h-[720px] overflow-hidden bg-black flex items-center justify-center">
    <!-- Imagem de Fundo -->
    <div class="absolute inset-0 z-0">
      <img src="<?php echo esc_url( $spa_bg ); ?>" alt="Spa & Bem-Estar" class="w-full h-full object-cover object-center filter brightness-[0.88]">
      <div class="absolute inset-0 bg-black/15"></div>
    </div>

    <!-- Conteúdo Centralizado / Layout com Play Button à Direita -->
    <div class="relative z-10 max-w-[1920px] w-full mx-auto px-6 lg:px-12 xl:px-[8.33%] flex flex-col md:flex-row items-center justify-between gap-10 md:gap-0">
      
      <!-- Lado Esquerdo: Tagline e Título (Alinhados ao Centro) -->
      <div class="flex flex-col items-center text-center gap-2.5 sm:gap-3 w-full md:w-1/2">
        <div class="flex items-center justify-center gap-4">
          <div class="w-8 sm:w-10 h-px bg-[#0da9a6] flex-shrink-0"></div>
          <span class="font-body text-[11px] sm:text-[12px] xl:text-[13px] tracking-[2px] uppercase text-white/95 font-light text-center drop-shadow-sm">
            <?php echo esc_html( $spa_tagline ); ?>
          </span>
          <div class="w-8 sm:w-10 h-px bg-[#0da9a6] flex-shrink-0"></div>
        </div>
        <h2 class="font-display text-[44px] sm:text-[56px] md:text-[68px] lg:text-[80px] xl:text-[92px] uppercase tracking-[2px] text-white leading-[1.04] text-center [&_em]:italic [&_em]:font-normal" style="text-shadow: 0 2px 24px rgba(0,0,0,0.35);">
          <?php echo wp_kses_post( $spa_title ); ?>
        </h2>
      </div>

      <!-- Lado Direito: Botão Play de Vídeo (Mesmo do design e página O Grupo) -->
      <?php if ( ! empty( $spa_video_url ) ) : ?>
      <div class="flex items-center justify-center w-full md:w-1/2">
        <button type="button" id="vblSpaVideoOpen" class="cursor-pointer opacity-90 hover:opacity-100 hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none w-[130px] sm:w-[150px] md:w-[170px] lg:w-[190px] xl:w-[200px] drop-shadow-[0_2px_16px_rgba(0,0,0,0.3)]" aria-label="Reproduzir Vídeo do Spa">
          <svg class="w-full h-auto block" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="1.5" y="1.5" width="197" height="197" stroke="white" stroke-width="3"/>
            <path d="M69 60L131 100L69 140V60Z" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
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
            <button type="button" class="vbl-trat-prev absolute -left-3 sm:-left-5 lg:-left-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-12 sm:h-12 bg-transparent border border-[#00B5B4] text-[#00B5B4] hover:bg-white hover:text-[#00B5B4] hover:border-[#00B5B4] flex items-center justify-center transition-all duration-300 cursor-pointer shadow-xs hover:shadow-sm" aria-label="Tratamento Anterior">
              <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7" />
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

              <div class="flex flex-col gap-5 font-body font-light text-[14px] lg:text-[15px] xl:text-[16px] leading-relaxed text-[#333333] max-w-[480px] xl:max-w-[520px] lg:ml-12 xl:ml-16">
                <?php 
                $paragraphs = explode( "\n\n", str_replace( "\r", '', $trat['description'] ) );
                foreach ( $paragraphs as $p ) :
                    if ( trim( $p ) ) :
                ?>
                  <p><?php echo nl2br( esc_html( trim( $p ) ) ); ?></p>
                <?php 
                    endif;
                endforeach; 
                ?>
              </div>
            </div>

            <!-- Seta Direita -->
            <button type="button" class="vbl-trat-next absolute -right-3 sm:-right-5 lg:-right-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-12 sm:h-12 bg-transparent border border-[#00B5B4] text-[#00B5B4] hover:bg-white hover:text-[#00B5B4] hover:border-[#00B5B4] flex items-center justify-center transition-all duration-300 cursor-pointer shadow-xs hover:shadow-sm" aria-label="Próximo Tratamento">
              <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14M12 5l7 7-7 7" />
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
<div id="vblSpaVideoModal" class="fixed inset-0 z-[99999] hidden items-center justify-center p-4 transition-opacity duration-300" style="position: fixed; inset: 0; z-index: 99999;" role="dialog" aria-modal="true" aria-label="Vídeo do Spa">
  <!-- Backdrop Blur (clicar fora fecha) -->
  <div class="absolute inset-0 bg-black/85 backdrop-blur-md cursor-pointer" id="vblSpaVideoBackdrop"></div>

  <!-- Content Box (Vídeo 16:9 Centralizado com botão fechar ancorado no topo do vídeo como em O Grupo) -->
  <div class="relative z-20 w-[92%] max-w-[1000px] flex flex-col transition-all duration-300 ease-out" id="vblSpaVideoContent">
    <!-- Barra superior com botão fechar X como na página O Grupo -->
    <div class="flex justify-end w-full mb-3">
      <button type="button"
        id="vblSpaVideoClose"
        class="w-11 h-11 flex items-center justify-center bg-transparent border-0 cursor-pointer p-0 opacity-80 hover:opacity-100 hover:scale-110 active:scale-95 transition-all duration-200 focus:outline-none"
        aria-label="Fechar vídeo"
        title="Fechar (Esc)">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"/>
          <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>

    <!-- Iframe Container 16:9 universal com padding-bottom 56.25% -->
    <div class="relative w-full pb-[56.25%] h-0 overflow-hidden bg-black rounded shadow-2xl">
      <iframe id="vblSpaVideoIframe" src="" data-src="<?php echo esc_url( $spa_video_embed ); ?>" class="absolute top-0 left-0 w-full h-full border-0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- ==========================================
     SCRIPTS DE INTERAÇÃO DOS SLIDERS
=========================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // 1. Slider Programa Semanal (Full-Width Center Mode com Opacidade e Setas Alinhadas)
  const progCarouselWrap = document.getElementById('vblProgCarouselWrap');
  const progTrack = document.getElementById('vblProgTrack');
  const progPrev = document.getElementById('vblProgPrev');
  const progNext = document.getElementById('vblProgNext');
  const progArrowsWrapper = document.getElementById('vblProgArrowsWrapper');
  const progCards = document.querySelectorAll('.vbl-prog-card');

  if (progTrack && progCards.length) {
    let currentProgIdx = progCards.length > 1 ? 1 : 0; // Inicia no card 1 (centro como no Design)

    function updateProgSlider(animate = true) {
      if (!progCarouselWrap) return;
      const wrapWidth = progCarouselWrap.offsetWidth;
      const activeCard = progCards[currentProgIdx];
      if (!activeCard) return;

      const cardWidth = activeCard.offsetWidth;
      const cardCenter = activeCard.offsetLeft + cardWidth / 2;
      const centerOffset = (wrapWidth / 2) - cardCenter;

      progTrack.style.transition = animate ? 'transform 500ms cubic-bezier(0.25, 1, 0.5, 1)' : 'none';
      progTrack.style.transform = `translateX(${centerOffset}px)`;

      // Atualiza opacidade das atividades (centro = 100%, laterais = 35%)
      progCards.forEach((card, idx) => {
        if (idx === currentProgIdx) {
          card.classList.remove('opacity-35');
          card.classList.add('opacity-100');
        } else {
          card.classList.remove('opacity-100');
          card.classList.add('opacity-35');
        }
      });

      // Posiciona o wrapper de setas exatamente sobre a foto do card central
      if (progArrowsWrapper) {
        progArrowsWrapper.style.width = `${cardWidth}px`;
        const imgWrap = activeCard.querySelector('.vbl-prog-img-wrap');
        if (imgWrap) {
          progArrowsWrapper.style.top = `${imgWrap.offsetTop + imgWrap.offsetHeight / 2}px`;
          progArrowsWrapper.style.transform = 'translate(-50%, -50%)';
        }
      }
    }

    if (progNext) {
      progNext.addEventListener('click', function(e) {
        e.stopPropagation();
        currentProgIdx = (currentProgIdx + 1) % progCards.length;
        updateProgSlider(true);
      });
    }

    if (progPrev) {
      progPrev.addEventListener('click', function(e) {
        e.stopPropagation();
        currentProgIdx = (currentProgIdx - 1 + progCards.length) % progCards.length;
        updateProgSlider(true);
      });
    }

    // Clique direto em qualquer card para torná-lo ativo e centralizado
    progCards.forEach((card, idx) => {
      card.addEventListener('click', function() {
        if (currentProgIdx !== idx) {
          currentProgIdx = idx;
          updateProgSlider(true);
        }
      });
    });

    // Suporte a swipe no mobile / touch
    let touchStartX = 0;
    let touchEndX = 0;
    progCarouselWrap.addEventListener('touchstart', function(e) {
      touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    progCarouselWrap.addEventListener('touchend', function(e) {
      touchEndX = e.changedTouches[0].screenX;
      if (touchStartX - touchEndX > 40) {
        currentProgIdx = (currentProgIdx + 1) % progCards.length;
        updateProgSlider(true);
      } else if (touchEndX - touchStartX > 40) {
        currentProgIdx = (currentProgIdx - 1 + progCards.length) % progCards.length;
        updateProgSlider(true);
      }
    }, { passive: true });

    window.addEventListener('resize', function() {
      updateProgSlider(false);
    });

    // Executa no load e após o carregamento das imagens para alinhamento vertical preciso
    updateProgSlider(false);
    setTimeout(function() {
      updateProgSlider(false);
    }, 100);
  }

  // 2. Slider Clubes ou Espaços Temáticos
  const animSlides = document.querySelectorAll('.vbl-anim-slide');
  const animPrev = document.getElementById('vblAnimPrev');
  const animNext = document.getElementById('vblAnimNext');
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

  if (animPrev) animPrev.addEventListener('click', () => showAnimSlide(currentAnim - 1));
  if (animNext) animNext.addEventListener('click', () => showAnimSlide(currentAnim + 1));
  document.querySelectorAll('.vbl-anim-prev').forEach(btn => {
    if (btn !== animPrev) btn.addEventListener('click', () => showAnimSlide(currentAnim - 1));
  });
  document.querySelectorAll('.vbl-anim-next').forEach(btn => {
    if (btn !== animNext) btn.addEventListener('click', () => showAnimSlide(currentAnim + 1));
  });

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
  const spaBackdrop = document.getElementById('vblSpaVideoBackdrop');
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
    if (spaBackdrop) spaBackdrop.addEventListener('click', closeSpaModal);
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

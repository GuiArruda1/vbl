<?php
/* Template Name: Vila Baleira — O Grupo */
get_header();
?>


<?php
// ── HERO ──
$hero_subtitle = vbl_field( 'vbl_ogrupo_hero_subtitle', false, 'Consequat sapien Hotel Holding' );
$hero_title    = vbl_field( 'vbl_ogrupo_hero_title', false, 'O Grupo<br>Vila baleira' );
$hero_img      = vbl_field( 'vbl_ogrupo_hero_img', false, vbl_img( 'ogrupo/hero-foto.jpg' ) );
if ( empty( $hero_img ) ) $hero_img = vbl_img( 'ogrupo/hero-foto.jpg' );
$hero_text_1   = vbl_field( 'vbl_ogrupo_hero_text_1', false, 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras.' );
$hero_text_2   = vbl_field( 'vbl_ogrupo_hero_text_2', false, 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.' );
$estrela_img   = vbl_img( 'estrela-dourada.svg' );
?>

<!-- ====== HERO: "O GRUPO VILA BALEIRA" — Mobile/Tablet ====== -->
<section class="lg:hidden pt-20">
  <div class="relative min-h-[65vh] md:min-h-[55vh] flex flex-col justify-end">
    <img src="<?php echo esc_url( $hero_img ); ?>" alt="Vila Baleira" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0) 100%)"></div>
    <div class="relative z-10 px-6 md:px-10 pb-8 md:pb-12">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-8 h-px bg-[#BC945B]"></div>
        <span class="font-body text-[11px] tracking-[1.1px] uppercase text-[#eee8e5]"><?php echo esc_html( $hero_subtitle ); ?></span>
      </div>
      <h1 class="font-display text-[clamp(40px,10.6vw,72px)] leading-[clamp(44px,11.2vw,76px)] text-[#eee8e5] uppercase" style="text-shadow:0 2px 16px rgba(0,0,0,0.3)"><?php echo wp_kses_post( $hero_title ); ?></h1>
    </div>
  </div>
  <div class="relative px-6 md:px-10 py-8 md:py-10">
    <div class="md:grid md:grid-cols-2 md:gap-6">
      <p class="font-body font-light text-[13px] leading-[19px] text-black/80"><?php echo esc_html( $hero_text_1 ); ?></p>
      <p class="font-body font-light text-[13px] leading-[19px] text-black/80 mt-4 md:mt-0"><?php echo esc_html( $hero_text_2 ); ?></p>
    </div>
  </div>
</section>

<!-- ====== HERO: "O GRUPO VILA BALEIRA" — Desktop ====== -->
<section class="hidden lg:block max-w-[1920px] mx-auto lg:px-[8.33%] pt-[80px]">
  <div class="pt-[clamp(32px,3.13vw,60px)] pb-[clamp(80px,7.29vw,140px)]">
    <div class="relative flex gap-[5%]">
      <!-- Foto esquerda: 40.8% com aspect-ratio próprio -->
      <div class="w-[40.8%] flex-shrink-0 self-start">
        <img src="<?php echo esc_url( $hero_img ); ?>" alt="Vila Baleira" class="w-full aspect-[653/720] object-cover">
      </div>
      <!-- Coluna direita: título + texto -->
      <div class="flex-1 pt-[clamp(40px,4.17vw,80px)]">
        <!-- Label -->
        <div class="flex items-center gap-4">
          <div class="w-10 h-px bg-[#BC945B]"></div>
          <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $hero_subtitle ); ?></span>
        </div>
        <!-- Título -->
        <h1 class="font-display text-[clamp(56px,5.21vw,100px)] leading-[clamp(56px,5.21vw,100px)] text-[#0d5257] uppercase mt-[24px]"><?php echo wp_kses_post( $hero_title ); ?></h1>
        <!-- Texto: indentado 80px do Figma -->
        <div class="ml-[clamp(40px,4.17vw,80px)] mt-[clamp(20px,2.14vw,41px)] w-[clamp(400px,25vw,480px)]">
          <p class="font-body font-light text-[16px] leading-[24px]"><?php echo esc_html( $hero_text_1 ); ?></p>
          <p class="font-body font-light text-[16px] leading-[24px] mt-[clamp(12px,1.04vw,20px)]"><?php echo esc_html( $hero_text_2 ); ?></p>
        </div>
      </div>
      <!-- Estrela decorativa -->
      <img src="<?php echo esc_url( $estrela_img ); ?>" alt="" class="absolute pointer-events-none" style="left:34%;top:72.2%;width:clamp(140px,11.3vw,217px);transform:scaleX(-1)">
    </div>
  </div>
</section>

<?php
// ── FRASE VIDEO ──
$video_subtitle  = vbl_field( 'vbl_ogrupo_video_subtitle', false, 'A nossa história' );
$video_title_1   = vbl_field( 'vbl_ogrupo_video_title_1', false, 'Vila Baleira:' );
$video_title_2   = vbl_field( 'vbl_ogrupo_video_title_2', false, 'UMA HISTÓRIA<br>DE CURA.' );
$video_bg        = vbl_field( 'vbl_ogrupo_video_bg', false, vbl_img( 'ogrupo/frase-video-bg.jpg' ) );
if ( empty( $video_bg ) ) $video_bg = vbl_img( 'ogrupo/frase-video-bg.jpg' );
$video_url       = vbl_field( 'vbl_ogrupo_video_url', false, 'https://www.youtube.com/watch?v=WN8c9XwUx9s' );
if ( empty( $video_url ) ) {
    $video_url = 'https://www.youtube.com/watch?v=WN8c9XwUx9s';
}
$video_embed_url = function_exists( 'vbl_get_youtube_embed_url' ) ? vbl_get_youtube_embed_url( $video_url ) : 'https://www.youtube.com/embed/WN8c9XwUx9s?autoplay=1&rel=0&modestbranding=1';
if ( empty( $video_embed_url ) ) {
    $video_embed_url = 'https://www.youtube.com/embed/WN8c9XwUx9s?autoplay=1&rel=0&modestbranding=1';
}
$play_icon       = vbl_img( 'ogrupo/play-icon.svg' );
?>

<!-- ====== FRASE VIDEO — Mobile/Tablet ====== -->
<section class="lg:hidden relative w-full overflow-hidden">
  <div class="relative min-h-[60vh] md:min-h-[50vh] flex flex-col justify-center items-center">
    <img src="<?php echo esc_url( $video_bg ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,0.45) 0%, rgba(29,29,27,0.2) 40%, rgba(29,29,27,0.15) 100%)"></div>
    <div class="relative z-10 flex flex-col items-center gap-3 md:gap-4 px-6 text-center py-16">
      <div class="flex items-center gap-3">
        <div class="w-8 h-px bg-[#eee8e5]"></div>
        <span class="text-[11px] tracking-[1.1px] uppercase text-[#eee8e5]"><?php echo esc_html( $video_subtitle ); ?></span>
        <div class="w-8 h-px bg-[#eee8e5]"></div>
      </div>
      <div class="flex flex-col items-center gap-1">
        <p class="font-['Old_Standard_TT',serif] text-[clamp(32px,8.5vw,56px)] leading-[clamp(38px,9.6vw,62px)] text-white uppercase" style="text-shadow:0 2px 20px rgba(0,0,0,0.25)"><?php echo esc_html( $video_title_1 ); ?></p>
        <p class="font-['Old_Standard_TT',serif] italic text-[clamp(28px,7.5vw,50px)] leading-[clamp(34px,8.5vw,56px)] text-white uppercase" style="text-shadow:0 2px 20px rgba(0,0,0,0.25)"><?php echo wp_kses_post( $video_title_2 ); ?></p>
      </div>
      <button type="button" onclick="openVideoModal()" class="w-[clamp(64px,17vw,100px)] mt-6 cursor-pointer opacity-80 hover:opacity-100 hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none" aria-label="Reproduzir vídeo">
        <img src="<?php echo esc_url( $play_icon ); ?>" alt="Reproduzir" class="w-full h-auto">
      </button>
    </div>
  </div>
</section>

<!-- ====== FRASE VIDEO — Desktop ====== -->
<section class="hidden lg:block w-full relative overflow-hidden">
  <div class="relative min-h-[500px]" style="aspect-ratio:1920/787">
    <img src="<?php echo esc_url( $video_bg ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-[rgba(29,29,27,0.2)] mix-blend-multiply"></div>
    <!-- Título: left=8.33%, top=27.8%, w=40.7% (container Figma, texto quebra em 3 linhas) -->
    <div class="absolute flex flex-col items-center" style="left:8.33%;top:27.8%;width:40.7%;gap:clamp(16px,1.25vw,24px)">
      <div class="flex items-center gap-4">
        <div class="w-10 h-px bg-[#BC945B]"></div>
        <span class="font-body text-[14px] tracking-[1.4px] uppercase text-white text-center"><?php echo esc_html( $video_subtitle ); ?></span>
        <div class="w-10 h-px bg-[#BC945B]"></div>
      </div>
      <p class="font-display text-[clamp(64px,5.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-white uppercase text-center"><?php echo esc_html( $video_title_1 ); ?></p>
      <p class="font-display italic text-[clamp(64px,5.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-white uppercase text-center"><?php echo wp_kses_post( $video_title_2 ); ?></p>
    </div>
    <!-- Play icon: left=65.6%, top=calc(50%+7.7%), w=200 -->
    <div class="absolute -translate-y-1/2" style="left:65.6%;top:calc(50% + 60.5px)">
      <button type="button" onclick="openVideoModal()" class="cursor-pointer opacity-80 hover:opacity-100 hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none" style="width:clamp(130px,10.42vw,200px)" aria-label="Reproduzir vídeo">
        <img src="<?php echo esc_url( $play_icon ); ?>" alt="Reproduzir" class="w-full h-auto">
      </button>
    </div>
  </div>
</section>

<?php
// ── MISSÃO (carrossel) ──
$missao_slides = [];
for ($i = 1; $i <= 3; $i++) {
    $img = vbl_field( "vbl_ogrupo_missao_img_$i", false, '' );
    if ( empty($img) && $i === 1 ) $img = vbl_img( 'ogrupo/missao-520x480.jpg' );
    
    $missao_slides[] = [
        'subtitle' => vbl_field( "vbl_ogrupo_missao_subtitle_$i", false, 'A Nossa' ),
        'title'    => vbl_field( "vbl_ogrupo_missao_title_$i", false, $i === 1 ? 'Missão' : ($i === 2 ? 'Visão' : 'Valores') ),
        'text'     => vbl_field( "vbl_ogrupo_missao_text_$i", false, 'Conteúdo do slide ' . $i ),
        'img'      => $img
    ];
}
$arrow_left  = vbl_img( 'arrow-left.svg' );
$arrow_right = vbl_img( 'arrow-right.svg' );
?>

<!-- ====== MISSÃO (carrossel) — Mobile/Tablet ====== -->
<section class="xl:hidden py-12 md:py-16 px-6 md:px-10 overflow-hidden">
  <div class="flex flex-col relative" id="missao-mobile-slides">
    <?php foreach ( $missao_slides as $i => $slide ) : 
      $active_class = $i === 0 ? 'opacity-100 relative z-10' : 'opacity-0 absolute top-0 left-0 w-full z-0 pointer-events-none';
    ?>
    <div class="missao-slide-mob transition-opacity duration-500 ease-in-out <?php echo $active_class; ?>">
      <!-- Imagem com overlay e título -->
      <div class="relative">
        <?php if (!empty($slide['img'])): ?>
        <img src="<?php echo esc_url( $slide['img'] ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>" class="w-full aspect-[520/480] object-cover rounded-sm">
        <?php endif; ?>
        <!-- Gradient overlay -->
        <div class="absolute inset-0" style="background:linear-gradient(to top right, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.25) 40%, rgba(0,0,0,0) 70%)"></div>
        <!-- Título sobre a imagem -->
        <div class="absolute left-6 md:left-10 bottom-6 md:bottom-10">
          <div class="flex items-center gap-3 mb-2">
            <div class="w-8 h-px bg-[#BC945B]"></div>
            <span class="font-body text-[11px] tracking-[1.1px] uppercase text-white/90"><?php echo esc_html( $slide['subtitle'] ); ?></span>
          </div>
          <h2 class="font-display text-[clamp(48px,12.8vw,72px)] leading-[clamp(52px,13.3vw,76px)] text-white uppercase"><?php echo esc_html( $slide['title'] ); ?></h2>
        </div>
      </div>
      <!-- Texto -->
      <div class="pt-8 md:pt-10 pb-2">
        <p class="font-body font-light text-[13px] leading-[19px] md:max-w-[500px] min-h-[60px]"><?php echo esc_html( $slide['text'] ); ?></p>
      </div>
    </div>
    <?php endforeach; ?>
    
    <!-- Setas Mobile -->
    <div class="flex gap-3 self-end relative z-20 pt-4">
      <button class="vbl-arrow w-11 h-11 border-2 border-[#bc945b] flex items-center justify-center cursor-pointer hover:bg-[#bc945b] group transition-all" onclick="prevMissaoSlide()"><img src="<?php echo esc_url( $arrow_left ); ?>" alt="" class="w-5 h-5 transition-all group-hover:brightness-0 group-hover:invert"></button>
      <button class="vbl-arrow w-11 h-11 border-2 border-[#bc945b] flex items-center justify-center cursor-pointer hover:bg-[#bc945b] group transition-all" onclick="nextMissaoSlide()"><img src="<?php echo esc_url( $arrow_right ); ?>" alt="" class="w-5 h-5 transition-all group-hover:brightness-0 group-hover:invert"></button>
    </div>
  </div>
</section>

<!-- ====== MISSÃO (carrossel) — Desktop ====== -->
<section class="hidden xl:block max-w-[1920px] mx-auto lg:px-[10%]">
  <div class="py-[clamp(32px,3.33vw,64px)]">
    <div class="relative" style="min-height:clamp(320px,25vw,480px)">
      <!-- Seta esquerda -->
      <button class="vbl-arrow absolute left-0 w-16 h-16 border-2 border-[#bc945b] flex items-center justify-center z-20 cursor-pointer hover:bg-[#bc945b] group transition-all" onclick="prevMissaoSlide()" style="top:clamp(138px,10.83vw,208px)">
        <img src="<?php echo esc_url( $arrow_left ); ?>" alt="" class="w-[27px] h-[27px] transition-all group-hover:brightness-0 group-hover:invert">
      </button>
      
      <!-- Slides Container -->
      <div id="missao-desktop-slides" class="relative w-full h-full">
        <?php foreach ( $missao_slides as $i => $slide ) : 
          $active_class = $i === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none';
        ?>
        <div class="missao-slide absolute inset-0 transition-opacity duration-500 ease-in-out <?php echo $active_class; ?>">
          <!-- Imagem -->
          <?php if (!empty($slide['img'])): ?>
          <img src="<?php echo esc_url( $slide['img'] ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>" class="absolute top-0 right-[clamp(48px,6.67vw,128px)] object-cover" style="width:clamp(340px,27.08vw,520px);aspect-ratio:520/480">
          <?php endif; ?>
          <!-- Título -->
          <div class="absolute" style="left:clamp(48px,6.67vw,128px);top:clamp(40px,3.13vw,60px)">
            <div class="flex items-center gap-4">
              <div class="w-10 h-px bg-[#BC945B]"></div>
              <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $slide['subtitle'] ); ?></span>
            </div>
            <h2 class="font-display text-[clamp(56px,5.21vw,100px)] leading-[clamp(56px,5.21vw,100px)] text-[#0d5257] uppercase mt-[clamp(14px,1.15vw,22px)]" style="white-space:nowrap"><?php echo esc_html( $slide['title'] ); ?></h2>
          </div>
          <!-- Texto -->
          <p class="font-body absolute font-light text-[16px] leading-[24px]" style="left:clamp(100px,10.83vw,208px);top:clamp(162px,12.66vw,243px);width:clamp(360px,27.08vw,520px)"><?php echo esc_html( $slide['text'] ); ?></p>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Seta direita -->
      <button class="vbl-arrow absolute right-0 w-16 h-16 border-2 border-[#bc945b] flex items-center justify-center z-20 cursor-pointer hover:bg-[#bc945b] group transition-all" onclick="nextMissaoSlide()" style="top:clamp(138px,10.83vw,208px)">
        <img src="<?php echo esc_url( $arrow_right ); ?>" alt="" class="w-[27px] h-[27px] transition-all group-hover:brightness-0 group-hover:invert">
      </button>
    </div>
  </div>
</section>

<?php
// ── TIMELINE (Native Meta Box) ──
$timeline_slides = get_post_meta( get_the_ID(), '_vbl_timeline', true );

if ( empty($timeline_slides) || ! is_array($timeline_slides) ) {
    // Fallback if empty (before user populates it in the metabox)
    $timeline_slides = [
        ['year' => '2000', 'label' => 'Vila baleira', 'desc' => 'Fundação do Grupo Vila Baleira com a abertura do primeiro resort na ilha do Porto Santo, marcando o início de uma história dedicada ao bem-estar e à hospitalidade autêntica.'],
        ['year' => '2002', 'label' => 'Vila baleira Funchal', 'desc' => 'Quis amet velit cursus etiam ipsum semper augue. Quam consectetur sodales mattis id commodo urna. In non vitae amet enim. Habitasse elementum quam ullamcorper id euismod amet. Ipsum vitae felis at purus nam nibh tincidunt.'],
        ['year' => '2018', 'label' => 'Vila baleira Porto Santo', 'desc' => 'Expansão do grupo com a inauguração do Vila Baleira Thalassa, reforçando o compromisso com o turismo de saúde e bem-estar na ilha do Porto Santo.']
    ];
}
?>

<!-- ====== TIMELINE — Mobile/Tablet ====== -->
<section class="xl:hidden bg-[#eee8e5] py-14 md:py-16 overflow-hidden" id="timelineMobile">
  <div class="flex flex-col items-center gap-8 px-6 md:px-10">
    <!-- Linha de navegação: setas + ano activo centrado -->
    <div class="flex items-center justify-center gap-5 w-full">
      <button class="vbl-arrow w-10 h-10 border-2 border-[#bc945b] flex items-center justify-center cursor-pointer flex-shrink-0 active:scale-90 transition-transform" data-tl-btn="prev"><img src="<?php echo esc_url( $arrow_left ); ?>" alt="" class="w-4 h-4"></button>
      <!-- Ano activo: largura fixa para estabilidade -->
      <div class="w-[200px] md:w-[280px] flex flex-col items-center gap-2 overflow-hidden">
        <span class="font-body text-[10px] tracking-[1px] uppercase text-[#0d5257] text-center transition-opacity duration-300" data-tl-role="active-label"></span>
        <p class="font-display text-[clamp(52px,13.8vw,80px)] leading-[1] text-[#0d5257] uppercase text-center transition-opacity duration-300" data-tl-role="active-year"></p>
      </div>
      <button class="vbl-arrow w-10 h-10 border-2 border-[#bc945b] flex items-center justify-center cursor-pointer flex-shrink-0 active:scale-90 transition-transform" data-tl-btn="next"><img src="<?php echo esc_url( $arrow_right ); ?>" alt="" class="w-4 h-4"></button>
    </div>
    <!-- Indicadores: Dinâmicos -->
    <div class="flex gap-2 flex-wrap justify-center">
      <?php foreach ($timeline_slides as $i => $slide) : ?>
        <div class="w-2 h-2 rounded-full transition-colors duration-300" data-tl-dot="<?php echo $i; ?>"></div>
      <?php endforeach; ?>
    </div>
    <!-- Descrição: altura fixa -->
    <div class="min-h-[80px] w-full max-w-[420px]">
      <p class="font-body font-light text-[13px] leading-[19px] text-center text-black/80 transition-opacity duration-300" data-tl-role="desc"></p>
    </div>
  </div>
</section>

<!-- ====== TIMELINE — Desktop ====== -->
<section class="hidden xl:block w-full bg-[#eee8e5]" id="timelineDesktop">
  <div class="max-w-[1920px] mx-auto relative" style="height:clamp(280px,20.83vw,400px)">
    <!-- SLOT PREV -->
    <div class="absolute" style="left:4.2%;top:50%;transform:translateY(-50%)">
      <div class="opacity-25">
        <div class="flex items-center gap-4"><div class="w-10 h-px bg-[#BC945B]"></div><span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257] transition-opacity duration-300" data-tl-role="prev-label"></span></div>
        <p class="font-display text-[clamp(64px,5.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-[#0d5257] uppercase mt-[22px] transition-opacity duration-300" data-tl-role="prev-year"></p>
      </div>
    </div>
    <!-- SETA ESQUERDA -->
    <button class="vbl-arrow absolute w-16 h-16 border-2 border-[#bc945b] flex items-center justify-center cursor-pointer active:scale-90 transition-all duration-200" style="left:20.5%;top:50%;transform:translateY(-50%)" data-tl-btn="prev"><img src="<?php echo esc_url( $arrow_left ); ?>" alt="" class="w-[27px] h-[27px]"></button>
    <!-- SLOT ACTIVO -->
    <div class="absolute" style="left:28%;top:50%;transform:translateY(-50%)">
      <div class="flex items-end" style="gap:clamp(40px,4.17vw,80px)">
        <div class="flex flex-col gap-[22px] flex-shrink-0 w-[clamp(180px,15.63vw,300px)]">
          <div class="flex items-center gap-4"><div class="w-10 h-px bg-[#BC945B]"></div><span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257] transition-opacity duration-300" data-tl-role="active-label"></span></div>
          <p class="font-display text-[clamp(64px,5.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-[#0d5257] uppercase transition-opacity duration-300" data-tl-role="active-year"></p>
        </div>
        <p class="font-body font-light text-[16px] leading-[24px] w-[clamp(280px,22.92vw,440px)] pb-1 transition-opacity duration-300" data-tl-role="desc"></p>
      </div>
    </div>
    <!-- SETA DIREITA -->
    <button class="vbl-arrow absolute w-16 h-16 border-2 border-[#bc945b] flex items-center justify-center cursor-pointer active:scale-90 transition-all duration-200" style="left:76.1%;top:50%;transform:translateY(-50%)" data-tl-btn="next"><img src="<?php echo esc_url( $arrow_right ); ?>" alt="" class="w-[27px] h-[27px]"></button>
    <!-- SLOT NEXT -->
    <div class="absolute" style="left:83.6%;top:50%;transform:translateY(-50%)">
      <div class="opacity-25">
        <div class="flex items-center gap-4"><div class="w-10 h-px bg-[#BC945B]"></div><span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257] transition-opacity duration-300" data-tl-role="next-label"></span></div>
        <p class="font-display text-[clamp(64px,5.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-[#0d5257] uppercase mt-[22px] transition-opacity duration-300" data-tl-role="next-year"></p>
      </div>
    </div>
  </div>
</section>

<?php
// ── SUSTENTABILIDADE / VALORES ──
$sust_subtitle   = vbl_field( 'vbl_ogrupo_sust_subtitle', false, 'Sustentabilidade / Responsabilidade Social' );
$sust_title      = vbl_field( 'vbl_ogrupo_sust_title', false, 'vitae Elementum sit ut' );
$sust_title_br   = vbl_field( 'vbl_ogrupo_sust_title_mobile', false, 'vitae Elementum<br>sit ut' );
$sust_img        = vbl_field( 'vbl_ogrupo_sust_img', false, vbl_img( 'ogrupo/valores-800x400.jpg' ) );
if ( empty( $sust_img ) ) $sust_img = vbl_img( 'ogrupo/valores-800x400.jpg' );

$valor_1_icon    = vbl_field( 'vbl_ogrupo_valor_1_icon', false, vbl_img( 'ogrupo/icon-valor-1.svg' ) );
if ( empty( $valor_1_icon ) ) $valor_1_icon = vbl_img( 'ogrupo/icon-valor-1.svg' );
$valor_1_title   = vbl_field( 'vbl_ogrupo_valor_1_title', false, 'Fermentum turpis malesuada nec' );
$valor_1_text    = vbl_field( 'vbl_ogrupo_valor_1_text', false, 'Pulvinar sagittis malesuada velit dui curabitur egestas in fermentum. Tincidunt eget dis diam penatibus et mi pellentesque.' );
$valor_1_text_dk = vbl_field( 'vbl_ogrupo_valor_1_text_desktop', false, 'Pulvinar sagittis malesuada velit dui curabitur egestas in fermentum. Tincidunt eget dis diam penatibus et mi pellentesque. Lacus nunc fermentum convallis felis at integer consequat duis ut. Tortor rhoncus consectetur augue pellentesque proin at sit.' );

$valor_2_icon    = vbl_field( 'vbl_ogrupo_valor_2_icon', false, vbl_img( 'ogrupo/icon-valor-2.svg' ) );
if ( empty( $valor_2_icon ) ) $valor_2_icon = vbl_img( 'ogrupo/icon-valor-2.svg' );
$valor_2_title   = vbl_field( 'vbl_ogrupo_valor_2_title', false, 'Tincidunt adipiscing ac praesent' );
$valor_2_text    = vbl_field( 'vbl_ogrupo_valor_2_text', false, 'Lorem ipsum dolor sit amet consectetur. Consectetur ac venenatis eu id egestas ornare. At id suspendisse arcu auctor placerat.' );
$valor_2_text_dk = vbl_field( 'vbl_ogrupo_valor_2_text_desktop', false, 'Lorem ipsum dolor sit amet consectetur. Consectetur ac venenatis eu id egestas ornare. At id suspendisse arcu auctor placerat enim vitae diam. Cras nisl euismod ut vel maecenas sollicitudin eu risus. Nunc pulvinar vivamus posuere auctor aliquam quam et.' );

$valor_3_icon    = vbl_field( 'vbl_ogrupo_valor_3_icon', false, vbl_img( 'ogrupo/icon-valor-3.svg' ) );
if ( empty( $valor_3_icon ) ) $valor_3_icon = vbl_img( 'ogrupo/icon-valor-3.svg' );
$valor_3_title   = vbl_field( 'vbl_ogrupo_valor_3_title', false, 'Consectetur ac venenatis eu id egestas' );
$valor_3_text    = vbl_field( 'vbl_ogrupo_valor_3_text', false, 'At id suspendisse arcu auctor placerat enim vitae diam. Cras nisl euismod ut vel maecenas sollicitudin eu risus.' );
$valor_3_text_dk = vbl_field( 'vbl_ogrupo_valor_3_text_desktop', false, 'At id suspendisse arcu auctor placerat enim vitae diam. Cras nisl euismod ut vel maecenas sollicitudin eu risus. Nunc pulvinar vivamus posuere auctor aliquam quam et.' );
?>

<!-- ====== SUSTENTABILIDADE / VALORES — Mobile/Tablet ====== -->
<section class="xl:hidden py-12">
  <!-- Título -->
  <div class="px-6 md:px-10 mb-6">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-8 h-px bg-[#BC945B]"></div>
      <span class="font-body text-[11px] tracking-[1.1px] uppercase text-[#0d5257]"><?php echo esc_html( $sust_subtitle ); ?></span>
    </div>
    <h2 class="font-display text-[clamp(32px,8.5vw,48px)] leading-[clamp(36px,9vw,52px)] text-[#0d5257] uppercase"><?php echo wp_kses_post( $sust_title_br ); ?></h2>
  </div>
  <!-- Imagem full-width -->
  <img src="<?php echo esc_url( $sust_img ); ?>" alt="Valores" class="w-full aspect-[800/400] object-cover">
  <!-- Valores como cards -->
  <div class="px-6 md:px-10 pt-10 flex flex-col gap-8 md:grid md:grid-cols-3 md:gap-6">
    <div class="flex flex-col gap-3 items-center text-center md:items-start md:text-left">
      <img src="<?php echo esc_url( $valor_1_icon ); ?>" alt="" class="w-14 h-14">
      <p class="font-body text-[13px] tracking-[1.3px] uppercase text-[#0d5257] font-medium"><?php echo esc_html( $valor_1_title ); ?></p>
      <p class="font-body font-light text-[12px] leading-[18px] text-black/70"><?php echo esc_html( $valor_1_text ); ?></p>
    </div>
    <div class="flex flex-col gap-3 items-center text-center md:items-start md:text-left">
      <img src="<?php echo esc_url( $valor_2_icon ); ?>" alt="" class="w-14 h-14">
      <p class="font-body text-[13px] tracking-[1.3px] uppercase text-[#0d5257] font-medium"><?php echo esc_html( $valor_2_title ); ?></p>
      <p class="font-body font-light text-[12px] leading-[18px] text-black/70"><?php echo esc_html( $valor_2_text ); ?></p>
    </div>
    <div class="flex flex-col gap-3 items-center text-center md:items-start md:text-left">
      <img src="<?php echo esc_url( $valor_3_icon ); ?>" alt="" class="w-14 h-14">
      <p class="font-body text-[13px] tracking-[1.3px] uppercase text-[#0d5257] font-medium"><?php echo esc_html( $valor_3_title ); ?></p>
      <p class="font-body font-light text-[12px] leading-[18px] text-black/70"><?php echo esc_html( $valor_3_text ); ?></p>
    </div>
  </div>
</section>

<!-- ====== SUSTENTABILIDADE / VALORES — Desktop ====== -->
<section class="hidden xl:block max-w-[1920px] mx-auto lg:px-[8.33%]">
  <div class="py-[clamp(48px,5vw,96px)]">
    <!-- Título full width -->
    <div class="flex flex-col gap-[clamp(14px,1.15vw,22px)] mb-[clamp(30px,3.65vw,70px)]">
      <div class="flex items-center gap-4">
        <div class="w-10 h-px bg-[#BC945B]"></div>
        <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $sust_subtitle ); ?></span>
      </div>
      <h2 class="font-display text-[clamp(56px,5.21vw,100px)] leading-[clamp(56px,5.21vw,100px)] text-[#0d5257] uppercase" style="white-space:nowrap"><?php echo esc_html( $sust_title ); ?></h2>
    </div>
    <!-- Conteúdo: valores left + imagem right -->
    <div class="flex">
      <!-- Valores: left=80 (5%), w=560 (35%) -->
      <div class="w-[35%] flex-shrink-0 ml-[clamp(40px,4.17vw,80px)] flex flex-col" style="gap:clamp(24px,2.5vw,48px)">
        <div class="flex items-start" style="gap:clamp(10px,0.83vw,16px)">
          <img src="<?php echo esc_url( $valor_1_icon ); ?>" alt="" class="flex-shrink-0" style="width:clamp(48px,3.33vw,64px);height:clamp(48px,3.33vw,64px)">
          <div class="flex flex-col gap-[16px]">
            <p class="font-body text-[16px] tracking-[1.6px] uppercase text-[#0d5257]"><?php echo esc_html( $valor_1_title ); ?></p>
            <p class="font-body font-light text-[16px] leading-[24px]"><?php echo esc_html( $valor_1_text_dk ); ?></p>
          </div>
        </div>
        <div class="flex items-start" style="gap:clamp(10px,0.83vw,16px)">
          <img src="<?php echo esc_url( $valor_2_icon ); ?>" alt="" class="flex-shrink-0" style="width:clamp(48px,3.33vw,64px);height:clamp(48px,3.33vw,64px)">
          <div class="flex flex-col gap-[16px]">
            <p class="font-body text-[16px] tracking-[1.6px] uppercase text-[#0d5257]"><?php echo esc_html( $valor_2_title ); ?></p>
            <p class="font-body font-light text-[16px] leading-[24px]"><?php echo esc_html( $valor_2_text_dk ); ?></p>
          </div>
        </div>
        <div class="flex items-start" style="gap:clamp(10px,0.83vw,16px)">
          <img src="<?php echo esc_url( $valor_3_icon ); ?>" alt="" class="flex-shrink-0" style="width:clamp(48px,3.33vw,64px);height:clamp(48px,3.33vw,64px)">
          <div class="flex flex-col gap-[16px]">
            <p class="font-body text-[16px] tracking-[1.6px] uppercase text-[#0d5257]"><?php echo esc_html( $valor_3_title ); ?></p>
            <p class="font-body font-light text-[16px] leading-[24px]"><?php echo esc_html( $valor_3_text_dk ); ?></p>
          </div>
        </div>
      </div>
      <!-- Imagem: right, w=50% -->
      <div class="flex-1 self-start ml-[10%]">
        <img src="<?php echo esc_url( $sust_img ); ?>" alt="Valores" class="w-full aspect-[800/400] object-cover">
      </div>
    </div>
  </div>
</section>


<!-- ====== NEWSLETTER ====== -->
<?php get_template_part( 'template-parts/section', 'newsletter' ); ?>

<!-- ====== MODAL VÍDEO ====== -->
<div id="modalVideo" class="fixed inset-0 z-[70] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300" style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center;" role="dialog" aria-modal="true" aria-label="Vídeo institucional Vila Baleira">
  <!-- Backdrop Blur (clicar fora fecha) -->
  <div class="absolute inset-0 bg-black/80 backdrop-blur-md" style="position: absolute; inset: 0; background-color: rgba(0, 0, 0, 0.85); cursor: pointer;" onclick="closeVideoModal()"></div>

  <!-- Content Box (Vídeo 16:9 Centralizado com botão fechar ancorado no topo do vídeo) -->
  <div class="relative z-20 scale-95 translate-y-4 transition-all duration-400 ease-out" style="position: relative; z-index: 20; width: 90%; max-width: 1000px; display: flex; flex-direction: column;" id="modalVideoContent">
    <!-- Barra superior com botão fechar inspirado na identidade da marca (Quadrado com moldura dourada) -->
    <div style="display: flex; justify-content: flex-end; width: 100%; margin-bottom: 12px;">
      <button type="button"
        id="modalVideoCloseBtn"
        style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; background: transparent; border: none; cursor: pointer; padding: 0; transition: transform 0.3s ease, opacity 0.3s ease; opacity: 0.8;"
        onmouseover="this.style.transform='scale(1.1)'; this.style.opacity='1';"
        onmouseout="this.style.transform='scale(1)'; this.style.opacity='0.8';"
        onclick="closeVideoModal()"
        aria-label="Fechar vídeo"
        title="Fechar (Esc)">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"/>
          <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>

    <!-- Iframe Container 16:9 universal com padding-bottom 56.25% -->
    <div style="position: relative; width: 100%; padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000000; border-radius: 4px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);">
      <iframe id="videoIframe" src="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
  </div>
</div>

<script>
  // === TIMELINE — cross-fade only, zero layout shift ===
  (function() {
    const data = <?php echo wp_json_encode( $timeline_slides ); ?>;
    let current = 1;
    let animating = false;

    function idx(offset) { return (current + offset + data.length) % data.length; }

    function fadeSwap(container) {
      const els = container.querySelectorAll('[data-tl-role]');
      // Step 1: fade out ALL text elements
      els.forEach(el => { el.style.opacity = '0'; });

      // Step 2: after fade completes, swap content + fade in
      setTimeout(() => {
        container.querySelectorAll('[data-tl-role="prev-label"]').forEach(el => el.textContent = data[idx(-1)].label);
        container.querySelectorAll('[data-tl-role="prev-year"]').forEach(el => el.textContent = data[idx(-1)].year);
        container.querySelectorAll('[data-tl-role="active-label"]').forEach(el => el.textContent = data[current].label);
        container.querySelectorAll('[data-tl-role="active-year"]').forEach(el => el.textContent = data[current].year);
        container.querySelectorAll('[data-tl-role="next-label"]').forEach(el => el.textContent = data[idx(1)].label);
        container.querySelectorAll('[data-tl-role="next-year"]').forEach(el => el.textContent = data[idx(1)].year);
        container.querySelectorAll('[data-tl-role="desc"]').forEach(el => el.textContent = data[current].desc);
        // Update dots
        container.querySelectorAll('[data-tl-dot]').forEach(dot => {
          dot.style.backgroundColor = parseInt(dot.dataset.tlDot) === current ? '#0d5257' : '#0d525740';
        });
        // Fade in
        requestAnimationFrame(() => { els.forEach(el => { el.style.opacity = '1'; }); });
      }, 300);
    }

    function navigate(dir) {
      if (animating) return;
      animating = true;
      current = (current + dir + data.length) % data.length;
      document.querySelectorAll('#timelineMobile, #timelineDesktop').forEach(c => fadeSwap(c));
      setTimeout(() => { animating = false; }, 650);
    }

    // Init: render immediately (no fade)
    document.querySelectorAll('#timelineMobile, #timelineDesktop').forEach(c => {
      c.querySelectorAll('[data-tl-role="prev-label"]').forEach(el => el.textContent = data[idx(-1)].label);
      c.querySelectorAll('[data-tl-role="prev-year"]').forEach(el => el.textContent = data[idx(-1)].year);
      c.querySelectorAll('[data-tl-role="active-label"]').forEach(el => el.textContent = data[current].label);
      c.querySelectorAll('[data-tl-role="active-year"]').forEach(el => el.textContent = data[current].year);
      c.querySelectorAll('[data-tl-role="next-label"]').forEach(el => el.textContent = data[idx(1)].label);
      c.querySelectorAll('[data-tl-role="next-year"]').forEach(el => el.textContent = data[idx(1)].year);
      c.querySelectorAll('[data-tl-role="desc"]').forEach(el => el.textContent = data[current].desc);
      c.querySelectorAll('[data-tl-dot]').forEach(dot => {
        dot.style.backgroundColor = parseInt(dot.dataset.tlDot) === current ? '#0d5257' : '#0d525740';
      });
      c.querySelectorAll('[data-tl-btn="prev"]').forEach(btn => btn.addEventListener('click', () => navigate(-1)));
      c.querySelectorAll('[data-tl-btn="next"]').forEach(btn => btn.addEventListener('click', () => navigate(1)));
    });
  })();

  // === VIDEO MODAL (Motion Design idêntico aos Gift Cards) ===
  (function() {
    const videoModal = document.getElementById('modalVideo');
    const videoContent = document.getElementById('modalVideoContent');
    const videoIframe = document.getElementById('videoIframe');
    const defaultEmbedUrl = 'https://www.youtube.com/embed/WN8c9XwUx9s?autoplay=1&rel=0&modestbranding=1';
    let videoEmbedUrl = <?php echo wp_json_encode( esc_url( $video_embed_url ) ); ?> || defaultEmbedUrl;

    function parseYoutubeUrl(url) {
      if (!url) return defaultEmbedUrl;
      const match = url.match(/(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i);
      const id = (match && match[1]) ? match[1] : 'WN8c9XwUx9s';
      return 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0&modestbranding=1';
    }

    window.openVideoModal = function() {
      if (!videoModal) return;
      document.body.style.overflow = 'hidden';
      if (videoIframe) {
        videoIframe.src = parseYoutubeUrl(videoEmbedUrl);
      }
      videoModal.classList.remove('opacity-0', 'pointer-events-none');
      videoModal.classList.add('opacity-100');
      videoModal.style.opacity = '1';
      videoModal.style.pointerEvents = 'auto';
      requestAnimationFrame(() => {
        videoContent.style.transform = 'scale(1) translateY(0)';
        videoContent.style.opacity = '1';
      });
    };

    window.closeVideoModal = function() {
      if (!videoModal) return;
      videoContent.style.transform = 'scale(0.95) translateY(16px)';
      videoContent.style.opacity = '0.5';
      if (videoIframe) {
        videoIframe.src = '';
      }
      setTimeout(() => {
        videoModal.classList.add('opacity-0', 'pointer-events-none');
        videoModal.classList.remove('opacity-100');
        videoModal.style.opacity = '0';
        videoModal.style.pointerEvents = 'none';
        document.body.style.overflow = '';
        setTimeout(() => {
          videoContent.style.transform = '';
          videoContent.style.opacity = '';
        }, 300);
      }, 150);
    };

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && videoModal && !videoModal.classList.contains('opacity-0')) {
        closeVideoModal();
      }
    });
  })();

  // Back to top
  window.addEventListener('scroll', () => {
    const bt = document.getElementById('backTop');
    if (bt) {
      if (scrollY > 400) bt.classList.add('opacity-100');
      else bt.classList.remove('opacity-100');
    }
  });

  // Missão Slider
  let currentMissaoSlide = 0;
  const totalMissaoSlides = <?php echo count($missao_slides); ?>;

  function updateMissaoSlides() {
    // Desktop
    const desktopSlides = document.querySelectorAll('#missao-desktop-slides .missao-slide');
    desktopSlides.forEach((slide, index) => {
      if (index === currentMissaoSlide) {
        slide.classList.add('opacity-100', 'z-10');
        slide.classList.remove('opacity-0', 'z-0', 'pointer-events-none');
      } else {
        slide.classList.add('opacity-0', 'z-0', 'pointer-events-none');
        slide.classList.remove('opacity-100', 'z-10');
      }
    });

    // Mobile
    const mobileSlides = document.querySelectorAll('#missao-mobile-slides .missao-slide-mob');
    mobileSlides.forEach((slide, index) => {
      if (index === currentMissaoSlide) {
        slide.classList.add('opacity-100', 'relative', 'z-10');
        slide.classList.remove('opacity-0', 'absolute', 'top-0', 'left-0', 'w-full', 'z-0', 'pointer-events-none');
      } else {
        slide.classList.add('opacity-0', 'absolute', 'top-0', 'left-0', 'w-full', 'z-0', 'pointer-events-none');
        slide.classList.remove('opacity-100', 'relative', 'z-10');
      }
    });
  }

  window.nextMissaoSlide = function() {
    if (totalMissaoSlides === 0) return;
    currentMissaoSlide = (currentMissaoSlide + 1) % totalMissaoSlides;
    updateMissaoSlides();
  }

  window.prevMissaoSlide = function() {
    if (totalMissaoSlides === 0) return;
    currentMissaoSlide = (currentMissaoSlide - 1 + totalMissaoSlides) % totalMissaoSlides;
    updateMissaoSlides();
  }
</script>

<?php get_footer(); ?>
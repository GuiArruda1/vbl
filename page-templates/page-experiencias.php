<?php
/* Template Name: Vila Baleira — Experiências */
get_header();
?>

<?php
// ── HERO FIELDS ──
$hero_subtitle   = vbl_field( 'vbl_experiencias_hero_subtitle', false, 'QUAM ID MORBI TINCIDUNT TURPIS UT' );
$hero_title      = vbl_field( 'vbl_experiencias_hero_title', false, 'MADEIRA E<br>PORTO SANTO' );
$hero_img        = vbl_field( 'vbl_experiencias_hero_img', false, vbl_img( 'experiencias/hero-foto.jpg' ) );
if ( empty( $hero_img ) ) $hero_img = vbl_img( 'experiencias/hero-foto.jpg' );
$hero_text_1     = vbl_field( 'vbl_experiencias_hero_text_1', false, 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras.' );
$hero_text_2     = vbl_field( 'vbl_experiencias_hero_text_2', false, 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.' );
$flor_img        = vbl_field( 'vbl_experiencias_flor_img', false, vbl_img( 'experiencias/flor.svg' ) );
if ( empty( $flor_img ) ) $flor_img = vbl_img( 'experiencias/flor.svg' );

// ── FRASE BANNER FIELDS ──
$frase_bg        = vbl_field( 'vbl_experiencias_frase_bg', false, vbl_img( 'experiencias/frase-bg.jpg' ) );
if ( empty( $frase_bg ) ) $frase_bg = vbl_img( 'experiencias/frase-bg.jpg' );
$frase_line_1    = vbl_field( 'vbl_experiencias_frase_line_1', false, 'LOREM IPSUM DOLOR' );
$frase_line_2    = vbl_field( 'vbl_experiencias_frase_line_2', false, 'ENIM VITAE TURPIS' );
$frase_line_3    = vbl_field( 'vbl_experiencias_frase_line_3', false, 'LACUS EGET UT SIT.' );

// ── EXPERIÊNCIAS CARROSSEL (CPT vbl_experiencia) ──
$exp_subtitle    = vbl_field( 'vbl_experiencias_carousel_subtitle', false, 'ALIQUET DIGNISSIM DUI TORTOR DIAM ELIT' );
$exp_title       = vbl_field( 'vbl_experiencias_carousel_title', false, 'TÍTULO PARA AS EXPERIÊNCIAS' );
$exp_desc        = vbl_field( 'vbl_experiencias_carousel_desc', false, 'Lacus eget parturient non ut semper donec nunc eget. Quis netus diam ullamcorper purus. Lorem ipsum dolor sit amet consectetur. Lacus eget parturient non ut semper donec nunc eget.' );

$exp_posts = array();
$exp_query = new WP_Query( array(
    'post_type'      => 'vbl_experiencia',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
) );

if ( $exp_query->have_posts() ) {
    while ( $exp_query->have_posts() ) {
        $exp_query->the_post();
        $p_id = get_the_ID();
        $img  = get_the_post_thumbnail_url( $p_id, 'full' );
        if ( empty( $img ) ) $img = vbl_img( 'experiencias/carrossel-520x480.jpg' );
        
        $exp_posts[] = array(
            'id'       => $p_id,
            'title'    => get_the_title(),
            'subtitle' => vbl_field( 'vbl_exp_subtitle', $p_id, $exp_subtitle ),
            'text'     => get_the_excerpt(),
            'img'      => $img,
            'link'     => get_permalink( $p_id ),
        );
    }
    wp_reset_postdata();
}

if ( empty( $exp_posts ) ) {
    $exp_posts = array(
        array(
            'title'    => 'RHONCUS FAUCIBUS EU PURUS SIT EU',
            'subtitle' => $exp_subtitle,
            'text'     => 'Rhoncus faucibus eu purus quis vitae aliquam vitae. Nunc diam tempus accumsan nulla commodo sagittis. Quis amet velit cursus etiam ipsum semper augue. Quam consectetur sodales mattis id commodo urna. In non vitae amet enim. Ipsum vitae felis at purus nam nibh tincidunt.',
            'img'      => vbl_img( 'experiencias/carrossel-520x480.jpg' ),
            'link'     => '#',
        ),
        array(
            'title'    => 'EXPERIÊNCIA THALASSO & BEM-ESTAR',
            'subtitle' => 'BEM-ESTAR & SPA',
            'text'     => 'Desfrute de tratamentos revigorantes de talassoterapia com água do mar aquecida e areia dourada do Porto Santo, proporcionando relaxamento profundo e benefícios comprovados para a sua saúde.',
            'img'      => vbl_img( 'experiencias/carrossel-520x480.jpg' ),
            'link'     => '#',
        ),
        array(
            'title'    => 'PASSEIOS DE BARCO & ILHAS DESERTAS',
            'subtitle' => 'DESPORTO & NATUREZA',
            'text'     => 'Explore a deslumbrante costa da ilha e as águas cristalinas do arquipélago, com rotas exclusivas para observação de cetáceos e mergulho em locais únicos de biodiversidade marinha.',
            'img'      => vbl_img( 'experiencias/carrossel-520x480.jpg' ),
            'link'     => '#',
        ),
    );
}

$first_slide = $exp_posts[0];
$arrow_left  = vbl_img( 'arrow-left.svg' );
$arrow_right = vbl_img( 'arrow-right.svg' );
?>

<!-- ====== HERO: "MADEIRA E PORTO SANTO" — Mobile/Tablet ====== -->
<section class="lg:hidden pt-20">
  <div class="relative min-h-[65vh] md:min-h-[55vh] flex flex-col justify-end">
    <img src="<?php echo esc_url( $hero_img ); ?>" alt="Experiências Madeira" class="absolute inset-0 w-full h-full object-cover">
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
    <div class="<?php echo ! empty( $hero_text_2 ) ? 'md:grid md:grid-cols-2 md:gap-6' : 'md:max-w-[70%]'; ?>">
      <p class="font-body font-light text-[13px] leading-[19px] text-black/80"><?php echo esc_html( $hero_text_1 ); ?></p>
      <?php if ( ! empty( $hero_text_2 ) ) : ?>
        <p class="font-body font-light text-[13px] leading-[19px] text-black/80 mt-4 md:mt-0"><?php echo esc_html( $hero_text_2 ); ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ====== HERO: "MADEIRA E PORTO SANTO" — Desktop ====== -->
<section class="hidden lg:block max-w-[1920px] mx-auto lg:px-[8.33%] pt-[80px]">
  <div class="pt-[clamp(32px,3.13vw,60px)] pb-[clamp(80px,7.29vw,140px)]">
    <div class="relative flex">
      <!-- Coluna esquerda: título + texto -->
      <div class="flex-1 pt-[clamp(40px,4.17vw,80px)]">
        <div class="flex items-center gap-4">
          <div class="w-10 h-px bg-[#BC945B]"></div>
          <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $hero_subtitle ); ?></span>
        </div>
        <h1 class="font-display text-[clamp(56px,5.21vw,100px)] leading-[clamp(56px,5.21vw,100px)] text-[#0d5257] uppercase mt-[24px]"><?php echo wp_kses_post( $hero_title ); ?></h1>
        <div class="ml-[clamp(40px,4.17vw,80px)] mt-[clamp(20px,2.14vw,41px)] w-[clamp(400px,25vw,480px)]">
          <p class="font-body font-light text-[16px] leading-[24px]"><?php echo esc_html( $hero_text_1 ); ?></p>
          <?php if ( ! empty( $hero_text_2 ) ) : ?>
            <p class="font-body font-light text-[16px] leading-[24px] mt-[clamp(12px,1.04vw,20px)]"><?php echo esc_html( $hero_text_2 ); ?></p>
          <?php endif; ?>
        </div>
      </div>
      <!-- Coluna direita: foto -->
      <div class="w-[40.8%] flex-shrink-0 self-start">
        <img src="<?php echo esc_url( $hero_img ); ?>" alt="Experiências Madeira" class="w-full aspect-[653/720] object-cover">
      </div>
      <!-- Flor decorativa -->
      <img src="<?php echo esc_url( $flor_img ); ?>" alt="" class="absolute pointer-events-none" style="left:48.4%;top:66.7%;width:clamp(136px,11.04vw,212px)">
    </div>
  </div>
</section>

<!-- ====== FRASE BANNER — Mobile/Tablet ====== -->
<section class="lg:hidden relative w-full overflow-hidden">
  <div class="relative min-h-[55vh] md:min-h-[50vh] flex flex-col justify-center items-center">
    <img src="<?php echo esc_url( $frase_bg ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,0.4) 0%, rgba(29,29,27,0.15) 40%, rgba(29,29,27,0.1) 100%)"></div>
    <div class="relative z-10 flex flex-col items-center gap-1 md:gap-2 py-16 md:py-20 text-center">
      <p class="font-display text-[clamp(32px,8.5vw,56px)] leading-[clamp(38px,9.6vw,62px)] text-white uppercase" style="text-shadow:0 2px 20px rgba(0,0,0,0.25)"><?php echo esc_html( $frase_line_1 ); ?></p>
      <p class="font-display italic text-[clamp(32px,8.5vw,56px)] leading-[clamp(38px,9.6vw,62px)] text-white uppercase mr-[-5%] md:mr-[-3%]" style="text-shadow:0 2px 20px rgba(0,0,0,0.25)"><?php echo esc_html( $frase_line_2 ); ?></p>
      <p class="font-display text-[clamp(32px,8.5vw,56px)] leading-[clamp(38px,9.6vw,62px)] text-white uppercase ml-[-3%] md:ml-[-2%]" style="text-shadow:0 2px 20px rgba(0,0,0,0.25)"><?php echo esc_html( $frase_line_3 ); ?></p>
    </div>
  </div>
</section>

<!-- ====== FRASE BANNER — Desktop ====== -->
<section class="hidden lg:block w-full relative overflow-hidden">
  <div class="relative min-h-[500px]" style="aspect-ratio:1920/787">
    <div class="absolute inset-0 overflow-hidden">
      <img src="<?php echo esc_url( $frase_bg ); ?>" alt="" class="absolute left-0 top-0 w-full h-[183%] object-cover">
    </div>
    <div class="absolute inset-0 bg-[rgba(29,29,27,0.2)] mix-blend-multiply"></div>
    <p class="absolute font-display text-[clamp(64px,4.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-white uppercase" style="white-space:nowrap;left:calc(50% - clamp(427px,33.39vw,641px));top:calc(50% - clamp(101px,7.89vw,151.5px))"><?php echo esc_html( $frase_line_1 ); ?></p>
    <p class="absolute font-display italic text-[clamp(64px,4.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-white uppercase" style="white-space:nowrap;left:calc(50% - clamp(267px,20.89vw,401px));top:calc(50% - clamp(24px,1.85vw,35.5px))"><?php echo esc_html( $frase_line_2 ); ?></p>
    <p class="absolute font-display text-[clamp(64px,4.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-white uppercase" style="white-space:nowrap;left:calc(50% - clamp(397px,30.99vw,595px));top:calc(50% + clamp(54px,4.19vw,80.5px))"><?php echo esc_html( $frase_line_3 ); ?></p>
  </div>
</section>

<!-- ====== EXPERIÊNCIAS: TÍTULO + CARROSSEL (Figma Design Layout) — Mobile/Tablet ====== -->
<section class="lg:hidden px-6 md:px-10 py-12 md:py-16">
  <!-- Título centrado -->
  <div class="flex flex-col items-center text-center gap-4 mb-10">
    <div class="flex items-center gap-3">
      <div class="w-8 h-px bg-[#BC945B]"></div>
      <span id="vblExpSubtitleMob" class="font-body text-[11px] tracking-[1.1px] uppercase text-[#0d5257]"><?php echo esc_html( $exp_subtitle ); ?></span>
      <div class="w-8 h-px bg-[#BC945B]"></div>
    </div>
    <h2 class="font-display text-[clamp(32px,8.5vw,48px)] leading-[clamp(36px,9vw,52px)] text-[#0d5257] uppercase"><?php echo esc_html( $exp_title ); ?></h2>
    <p class="font-body font-light text-[13px] leading-[19px] text-black/80 max-w-[480px]"><?php echo esc_html( $exp_desc ); ?></p>
  </div>

  <!-- Carrossel card -->
  <div class="bg-[#f8f6f4] rounded-sm overflow-hidden">
    <div class="md:flex">
      <!-- Imagem -->
      <div class="md:w-[45%] md:flex-shrink-0">
        <img id="vblExpImgMob" src="<?php echo esc_url( $first_slide['img'] ); ?>" alt="Experiência" class="w-full aspect-[520/480] object-cover transition-opacity duration-300">
      </div>
      <!-- Conteúdo -->
      <div class="p-6 md:p-8 md:flex-1 flex flex-col justify-center gap-4">
        <h3 id="vblExpTitleMob" class="font-display text-[clamp(24px,6.4vw,36px)] leading-[clamp(28px,7.2vw,40px)] text-[#0d5257] uppercase transition-opacity duration-300">
          <?php echo esc_html( $first_slide['title'] ); ?>
        </h3>
        <p id="vblExpTextMob" class="font-body font-light text-[13px] leading-[19px] text-black/80 transition-opacity duration-300">
          <?php echo esc_html( $first_slide['text'] ); ?>
        </p>
      </div>
    </div>

    <!-- Navegação: setas -->
    <div class="flex items-center justify-end gap-3 px-6 md:px-8 pb-5">
      <button type="button" id="vblExpPrevBtnMob" aria-label="Experiência anterior" class="vbl-arrow w-10 h-10 border-2 border-[#bc945b] text-[#bc945b] hover:bg-[#bc945b] hover:text-white flex items-center justify-center cursor-pointer active:scale-90 transition-all">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M28 15H1M1 15L14 2M1 15L14 28" />
        </svg>
      </button>
      <button type="button" id="vblExpNextBtnMob" aria-label="Próxima experiência" class="vbl-arrow w-10 h-10 border-2 border-[#bc945b] text-[#bc945b] hover:bg-[#bc945b] hover:text-white flex items-center justify-center cursor-pointer active:scale-90 transition-all">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M1 15H28M28 15L15 2M28 15L15 28" />
        </svg>
      </button>
    </div>
  </div>
</section>

<!-- ====== EXPERIÊNCIAS: TÍTULO + CARROSSEL (Figma Design Layout) — Desktop ====== -->
<section class="hidden lg:block max-w-[1920px] mx-auto">
  <!-- Título centrado -->
  <div class="flex flex-col items-center text-center py-[clamp(40px,4.17vw,80px)]">
    <div class="flex items-center gap-4">
      <div class="w-10 h-px bg-[#BC945B]"></div>
      <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $exp_subtitle ); ?></span>
      <div class="w-10 h-px bg-[#BC945B]"></div>
    </div>
    <h2 class="font-display text-[clamp(64px,5.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-[#0d5257] uppercase mt-[clamp(24px,1.77vw,34px)] w-[clamp(620px,48.44vw,930px)]"><?php echo esc_html( $exp_title ); ?></h2>
    <p class="font-body font-light text-[16px] leading-[24px] mt-[clamp(16px,1.56vw,30px)] w-[clamp(420px,33.33vw,640px)]"><?php echo esc_html( $exp_desc ); ?></p>
  </div>

  <!-- Carrossel -->
  <div class="px-[10%] pb-[clamp(48px,5vw,96px)]">
    <div class="relative">
      <!-- Seta esquerda -->
      <button type="button" id="vblExpPrevBtn" aria-label="Experiência anterior" class="vbl-arrow absolute left-0 top-1/2 -translate-y-1/2 w-16 h-16 border-2 border-[#bc945b] text-[#bc945b] hover:bg-[#bc945b] hover:text-white flex items-center justify-center cursor-pointer z-10 active:scale-90 transition-all">
        <svg class="w-6 h-6 sm:w-8 sm:h-8" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M28 15H1M1 15L14 2M1 15L14 28" />
        </svg>
      </button>

      <!-- Conteúdo central -->
      <div class="flex items-start mx-[clamp(48px,5.21vw,100px)]" style="gap:clamp(40px,5.21vw,100px)">
        <!-- Imagem LEFT -->
        <div class="w-[clamp(340px,27.08vw,520px)] flex-shrink-0 overflow-hidden">
          <img id="vblExpImg" src="<?php echo esc_url( $first_slide['img'] ); ?>" alt="Experiência" class="w-full aspect-[520/480] object-cover transition-opacity duration-300">
        </div>
        <!-- Título + texto RIGHT -->
        <div class="flex-1 pt-[clamp(30px,3.13vw,60px)] font-body">
          <h3 id="vblExpTitle" class="font-display text-[clamp(42px,3.33vw,64px)] leading-[clamp(44px,3.54vw,68px)] text-[#0d5257] uppercase transition-opacity duration-300">
            <?php echo esc_html( $first_slide['title'] ); ?>
          </h3>
          <p id="vblExpText" class="font-body font-light text-[16px] leading-[24px] mt-[clamp(20px,2.08vw,40px)] ml-[clamp(40px,4.17vw,80px)] max-w-[520px] transition-opacity duration-300">
            <?php echo esc_html( $first_slide['text'] ); ?>
          </p>
        </div>
      </div>

      <!-- Seta direita -->
      <button type="button" id="vblExpNextBtn" aria-label="Próxima experiência" class="vbl-arrow absolute right-0 top-1/2 -translate-y-1/2 w-16 h-16 border-2 border-[#bc945b] text-[#bc945b] hover:bg-[#bc945b] hover:text-white flex items-center justify-center cursor-pointer z-10 active:scale-90 transition-all">
        <svg class="w-6 h-6 sm:w-8 sm:h-8" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M1 15H28M28 15L15 2M28 15L15 28" />
        </svg>
      </button>
    </div>
  </div>
</section>

<!-- Payload JSON de Experiências CPT -->
<script type="application/json" id="vbl-experiencias-data">
<?php echo json_encode( $exp_posts ); ?>
</script>

<!-- ====== NEWSLETTER ====== -->
<?php get_template_part( 'template-parts/section', 'newsletter' ); ?>

<?php get_footer(); ?>
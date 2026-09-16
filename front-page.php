<?php
/**
 * Template Name: Homepage
 * Front Page Template
 *
 * @package Vila_Baleira
 */

get_header();

// ACF fields with Customizer fallback
$banner_subtitle = vbl_field( 'vbl_banner_subtitle', false, 'The essence of hospitality' );
$banner_line1    = vbl_field( 'vbl_banner_line1', false, 'Lorem UT & Ipsum SIT' );
$banner_line2    = vbl_field( 'vbl_banner_line2', false, 'Hotel holding' );
$banner_img      = vbl_field( 'vbl_banner_image', false, vbl_img( 'banner-principal.jpg' ) );
$sobre_subtitle  = vbl_field( 'vbl_sobre_subtitle', false, 'Descubra o nosso grupo' );
$sobre_title     = vbl_field( 'vbl_sobre_title', false, 'Vila baleira<br>Hotel holding' );
$sobre_text      = vbl_field( 'vbl_sobre_text', false, 'No Grupo Vila Baleira, proporcionamos experiências autênticas que valorizam o bem-estar e a ligação à natureza. Entre a beleza natural da Madeira e a tranquilidade do Porto Santo, os nossos hotéis são verdadeiros refúgios onde criamos memórias únicas, num ambiente saudável e culturalmente rico.' );
$sobre_img       = vbl_field( 'vbl_sobre_image', false, vbl_img( 'sobre-fotografia.jpg' ) );
$sobre_link      = vbl_field( 'vbl_sobre_link', false, '/o-grupo' );

// Frase 1
$frase1_line1 = vbl_field( 'vbl_frase1_line1', false, 'Criamos momentos' );
$frase1_line2 = vbl_field( 'vbl_frase1_line2', false, '"sem tempo" para' );
$frase1_line3 = vbl_field( 'vbl_frase1_line3', false, 'memórias eternas.' );
$frase1_bg    = vbl_field( 'vbl_frase1_bg', false, vbl_img( 'frase1-bg.jpg' ) );

// Hotéis
$hoteis_subtitle  = vbl_field( 'vbl_hoteis_subtitle', false, 'Vila baleira' );
$hoteis_lista = array();
for ($i = 1; $i <= 5; $i++) {
    $t = vbl_field("vbl_h_{$i}_title", false, "");
    if ( $t ) {
        $img_b = vbl_field("vbl_h_{$i}_img_big", false, "");
        $img_s = vbl_field("vbl_h_{$i}_img_small", false, "");
        if ( empty($img_s) ) { $img_s = $img_b; }

        $hoteis_lista[] = array(
            'title'     => $t,
            'text'      => vbl_field("vbl_h_{$i}_text", false, ""),
            'img_big'   => $img_b,
            'img_small' => $img_s,
            'link'      => vbl_field("vbl_h_{$i}_link", false, "/hoteis")
        );
    }
}
if ( empty($hoteis_lista) ) {
    // Fallback Mock
    $hoteis_lista[] = array(
        'title'     => 'Funchal',
        'text'      => 'Localizado na zona do Lido, oferece um ambiente moderno e equilibrado entre tranquilidade e a energia da cidade, a poucos minutos do centro do Funchal e perto de várias atrações turísticas.',
        'img_big'   => vbl_img( 'hoteis-800x800.jpg' ),
        'img_small' => vbl_img( 'hoteis-520x480.jpg' ),
        'link'      => '/hoteis',
    );
    $hoteis_lista[] = array(
        'title'     => 'Porto Santo',
        'text'      => 'Descubra o paraíso no Vila Baleira Porto Santo. Um resort All Inclusive ideal para famílias, onde a praia dourada e o bem-estar se unem.',
        'img_big'   => vbl_img( 'sobre-fotografia.jpg' ),
        'img_small' => vbl_img( 'ilhas-416x480.jpg' ),
        'link'      => '/hoteis',
    );
}
$h_first = $hoteis_lista[0];

// Ilhas
$ilhas_subtitle   = vbl_field( 'vbl_ilhas_subtitle', false, 'Explore as ilhas da' );
$ilhas_title      = vbl_field( 'vbl_ilhas_title', false, "madeira e<br>porto santo" );
$ilhas_text       = vbl_field( 'vbl_ilhas_text', false, 'Lacus eget parturient non ut semper donec nunc eget. Quis netus diam ullamcorper purus. Lorem ipsum dolor sit amet consectetur. Lacus eget parturient non ut semper donec nunc eget.' );
$ilhas_img_main   = vbl_field( 'vbl_ilhas_img_main', false, vbl_img( 'ilhas-800x720.jpg' ) );
$ilhas_img_left   = vbl_field( 'vbl_ilhas_img_left', false, vbl_img( 'ilhas-416x480.jpg' ) );
$ilhas_img_top    = vbl_field( 'vbl_ilhas_img_top', false, vbl_img( 'ilhas-432x240-2.jpg' ) );
$ilhas_img_bottom = vbl_field( 'vbl_ilhas_img_bottom', false, vbl_img( 'ilhas-432x240-1.jpg' ) );
$ilhas_link       = vbl_field( 'vbl_ilhas_link', false, '#' );

// Frase 2
$frase2_line1 = vbl_field( 'vbl_frase2_line1', false, 'Descubra um' );
$frase2_line2 = vbl_field( 'vbl_frase2_line2', false, 'mundo de' );
$frase2_line3 = vbl_field( 'vbl_frase2_line3', false, 'paraísos.' );
$frase2_desc  = vbl_field( 'vbl_frase2_desc', false, 'Deixe-se envolver pela beleza natural das ilhas da Madeira e do Porto Santo e encontre o seu refúgio de bem-estar num dos nossos hotéis.' );
?>

  <!-- ====== BANNER ====== -->
  <section class="relative w-full h-[75vh] xl:h-screen xl:max-h-[990px] overflow-hidden">
    <!-- Header transparente sobre banner -->
    <div class="absolute top-0 left-0 w-full h-20 xl:h-[144px] z-20">
      <div class="max-w-[1920px] mx-auto h-full flex items-center justify-between px-6 xl:px-10">
        <nav class="font-body hidden xl:flex items-center gap-6 xl:gap-10">
          <?php
          wp_nav_menu( array(
              'theme_location' => 'primary',
              'container'      => false,
              'items_wrap'     => '%3$s',
              'fallback_cb'    => 'vbl_fallback_menu_transparent',
              'walker'         => new VBL_Nav_Walker( 'transparent' ),
          ) );
          ?>
        </nav>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center gap-2">
          <img src="<?php echo vbl_img( 'logo-top-white.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-7 xl:h-[48px] w-auto">
          <img src="<?php echo vbl_img( 'logo-bottom-white.svg' ); ?>" alt="" class="h-[10px] xl:h-[14px] w-auto">
        </a>
        <div class="font-body hidden xl:flex items-center gap-6 xl:gap-10">
          <a href="<?php echo esc_url( home_url( '/contactos' ) ); ?>" class="vbl-nav-link-white text-[14px] tracking-[1.4px] uppercase text-white">Contactos</a>
          <span class="vbl-nav-link-white text-[14px] tracking-[1.4px] uppercase text-white flex items-center gap-2 cursor-pointer">PT <img src="<?php echo vbl_img( 'vector-dropdown-white.svg' ); ?>" alt="" class="w-2 h-1"></span>
          	<a href="#" class="group vbl-btn-reservar flex items-center gap-16 px-4 py-2 border border-white text-white text-[14px] tracking-[1.4px] uppercase transition-all duration-500 ease-in-out">
				<span>Reservar</span>
				<img src="<?php echo vbl_img( 'seta-branca.svg' ); ?>" alt="" class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45">
			</a>			
        </div>
        <button class="xl:hidden w-8 h-8 flex flex-col items-center justify-center gap-1.5 menu-toggle" aria-label="Menu">
          <span class="w-6 h-px bg-white"></span><span class="w-6 h-px bg-white"></span><span class="w-4 h-px bg-white self-end"></span>
        </button>
		<!-- reservar semi mobile -->
		<a href="#" class="hidden [@media(min-width:426px)_and_(max-width:1279px)]:flex font-body group items-center gap-16 px-4 py-2 border border-white text-white text-[14px] tracking-[1.4px] uppercase transition-all duration-500 ease-in-out">
			<span>Reservar</span>
			<img src="<?php echo vbl_img( 'seta-branca.svg' ); ?>" alt="" class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45">
		</a>
		<span class="hidden max-[425px]:flex font-body vbl-nav-link-white text-[14px] tracking-[1.4px] uppercase text-white flex items-center gap-2 cursor-pointer">PT <img src="<?php echo vbl_img( 'vector-dropdown-white.svg' ); ?>" alt="" class="w-2 h-1">
		</span>
		  
      </div>
    </div>
    <!-- BG + overlays -->
    <img src="<?php echo esc_url( $banner_img ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 mix-blend-multiply" style="background:linear-gradient(to bottom,rgba(0,0,0,0) 25%,rgba(0,0,0,0.65) 100%)"></div>
    <div class="absolute inset-0 mix-blend-multiply" style="background:linear-gradient(to top,rgba(0,0,0,0) 50%,rgba(0,0,0,0.5) 100%)"></div>
    <div class="absolute inset-0 mix-blend-overlay" style="background:linear-gradient(to bottom,rgba(255,255,255,0),#bc945b)"></div>
    <!-- Título -->
    <div class="absolute inset-0 z-10 flex flex-col items-center justify-center px-6 -mt-[5%]">
      <div class="flex items-center gap-4 mb-8 xl:mb-10" style="text-shadow:0 2px 12px rgba(0,0,0,0.4)">
        <div class="w-10 h-px bg-[#eee8e5] hidden lg:block"></div>
        <span class="font-body text-[12px] xl:text-[14px] tracking-[1.4px] uppercase text-[#eee8e5] font-normal"><?php echo esc_html( $banner_subtitle ); ?></span>
        <div class="w-10 h-px bg-[#eee8e5] hidden lg:block"></div>
      </div>
		<h1 class="font-display text-white text-[28px] leading-[34px] md:text-[48px] md:leading-[54px] lg:text-[64px] lg:leading-[70px] xl:text-[clamp(72px,5.42vw,104px)] xl:leading-[clamp(72px,5.42vw,104px)] uppercase text-center" style="text-shadow:0 4px 20px rgba(0,0,0,0.45),0 1px 6px rgba(0,0,0,0.3)"><span class="text-[#EEE8E5]"><?php echo esc_html( $banner_line1 ); ?></span><br><span><?php echo esc_html( $banner_line2 ); ?></span></h1>
    </div>
    <!-- Barra pesquisa (mobile/tablet) -->
    <div class="font-body absolute bottom-4 left-4 right-4 z-10 flex flex-col border border-white xl:hidden">
      <div class="grid grid-cols-2">
        <div class="flex flex-col justify-center gap-1 px-4 py-3">
          <span class="text-[9px] tracking-[1px] uppercase text-white">Onde</span>
          <span class="text-[12px] tracking-[1.2px] uppercase text-white flex items-center gap-2">todos os hotéis <img src="<?php echo vbl_img( 'vector-dropdown-white.svg' ); ?>" alt="" class="w-2 h-1"></span>
        </div>
        <div class="flex flex-col justify-center gap-1 px-4 py-3 border-l border-white">
          <span class="text-[9px] tracking-[1px] uppercase text-white">Quando</span>
          <span class="text-[12px] tracking-[1.2px] uppercase text-white flex items-center gap-2">Entrada · Saída <img src="<?php echo vbl_img( 'vector-dropdown-white.svg' ); ?>" alt="" class="w-2 h-1"></span>
        </div>
      </div>
      <div class="h-px bg-white"></div>
      <div class="grid grid-cols-2">
        <div class="flex flex-col justify-center gap-1 px-4 py-3">
          <span class="text-[9px] tracking-[1px] uppercase text-white">Quem</span>
          <span class="text-[12px] tracking-[1.2px] uppercase text-white flex items-center gap-2">2 adultos · 1 quarto <img src="<?php echo vbl_img( 'vector-dropdown-white.svg' ); ?>" alt="" class="w-2 h-1"></span>
        </div>
        <div class="flex items-center justify-center gap-6 px-4 py-3 border-l border-white cursor-pointer">
          <span class="text-[12px] tracking-[1.2px] uppercase text-white">Pesquisar</span>
          <img src="<?php echo vbl_img( 'seta-branca.svg' ); ?>" alt="" class="w-[10px] h-[10px]">
        </div>
      </div>
    </div>
    <!-- Barra pesquisa (desktop) -->
    <div class="font-body  absolute bottom-[60px] xl:bottom-[80px] left-1/2 -translate-x-1/2 z-10 hidden xl:flex items-stretch border border-white h-[66px]">
      <div class="flex flex-col justify-center gap-[10px] px-10 xl:px-20 min-w-[240px] xl:min-w-[322px]">
        <span class="text-[10px] tracking-[1px] uppercase text-white">Onde</span>
        <span class="text-[14px] tracking-[1.4px] uppercase text-white flex items-center gap-2 whitespace-nowrap">todos os hotéis <img src="<?php echo vbl_img( 'vector-dropdown-white.svg' ); ?>" alt="" class="w-2 h-1"></span>
      </div>
      <div class="w-px h-8 bg-white self-center"></div>
      <div class="flex flex-col justify-center gap-[10px] px-10 xl:px-20 min-w-[240px] xl:min-w-[322px]">
        <span class="text-[10px] tracking-[1px] uppercase text-white">Quando</span>
        <span class="text-[14px] tracking-[1.4px] uppercase text-white flex items-center gap-2 whitespace-nowrap">Entrada · Saída <img src="<?php echo vbl_img( 'vector-dropdown-white.svg' ); ?>" alt="" class="w-2 h-1"></span>
      </div>
      <div class="w-px h-8 bg-white self-center"></div>
      <div class="flex flex-col justify-center gap-[10px] px-10 xl:px-20 min-w-[240px] xl:min-w-[322px]">
        <span class="text-[10px] tracking-[1px] uppercase text-white">Quem</span>
        <span class="text-[14px] tracking-[1.4px] uppercase text-white flex items-center gap-2 whitespace-nowrap">2 adultos · 1 quarto <img src="<?php echo vbl_img( 'vector-dropdown-white.svg' ); ?>" alt="" class="w-2 h-1"></span>
      </div>
      <div class="w-px h-8 bg-white self-center"></div>
      <div class="flex items-center gap-14 px-10 xl:px-20 min-w-[240px] xl:min-w-[322px] justify-center cursor-pointer">
        <span class="text-[14px] tracking-[1.4px] uppercase text-white whitespace-nowrap">Pesquisar</span>
        <img src="<?php echo vbl_img( 'seta-branca.svg' ); ?>" alt="" class="w-[10px] h-[10px]">
      </div>
    </div>
  </section>

  <!-- ====== SOBRE ====== -->
  <section class="max-w-[1920px] mx-auto px-6 md:px-10 xl:px-[160px] py-16 xl:py-[100px]">
    <!-- Mobile -->
    <div class="flex flex-col gap-10 xl:hidden">
      <img src="<?php echo esc_url( $sobre_img ); ?>" alt="Vila Baleira" class="w-full object-cover">
      <div class="flex flex-col gap-6">
        <div class="flex items-center gap-4">
          <div class="w-10 h-px bg-[#BC945B] flex-shrink-0"></div>
          <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $sobre_subtitle ); ?></span>
        </div>
        <h2 class="font-display text-[36px] leading-[40px] md:text-[64px] md:leading-[68px] text-[#0d5257] uppercase"><?php echo wp_kses_post( $sobre_title ); ?></h2>
        
		<div class="flex flex-col gap-6 md:gap-8 max-w-[480px] [@media(min-width:1024px)_and_(max-width:1279px)]:max-w-full w-full">
			<p class="font-body font-light text-[16px] leading-[24px] [@media(min-width:1024px)_and_(max-width:1279px)]:text-justify"><?php echo esc_html( $sobre_text ); ?></p>
			<a href="<?php echo esc_url( home_url( $sobre_link ) ); ?>" class="inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-start"><span class="font-body">Descobrir</span><img src="<?php echo vbl_img( 'seta-dourada.svg' ); ?>" alt="" class="w-[10px] h-[10px]"></a>
		</div>
		  
      </div>
    </div>
    <!-- Desktop -->
    <div class="hidden xl:block relative" style="aspect-ratio: 1600/720;">
      <img src="<?php echo esc_url( $sobre_img ); ?>" alt="Vila Baleira" class="absolute left-0 top-0 w-[40.8%] h-full object-cover">
      <div class="absolute left-[45.8%] top-[11.1%] flex flex-col gap-[24px]">
        <div class="flex items-center gap-4">
          <div class="w-10 h-px bg-[#BC945B] flex-shrink-0"></div>
          <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $sobre_subtitle ); ?></span>
        </div>
        <h2 class="font-display text-[clamp(64px,5.21vw,100px)] [@media(min-width:1337px)]:text-[clamp(64px,4.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-[#0d5257] uppercase whitespace-nowrap"><?php echo wp_kses_post( $sobre_title ); ?></h2>
      </div>
      <div class="absolute left-[50.8%] top-[50.7%] [@media(min-width:1280px)_and_(max-width:1333px)]:top-[54.7%] w-[40%] [@media(min-width:1367px)]:w-[30%] flex flex-col gap-[40px]">
        <p class="font-body font-light text-[16px] leading-[24px]"><?php echo esc_html( $sobre_text ); ?></p>
        <a href="<?php echo esc_url( home_url( $sobre_link ) ); ?>" class="group vbl-btn font-body inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-start transition-all duration-500 ease-in-out">
			<span>Descobrir</span>
			<svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
		</a>
      </div>
      <img src="<?php echo vbl_img( 'concha.svg' ); ?>" alt="" class="absolute left-[35.7%] top-[72.2%] w-[10.4%] h-auto pointer-events-none">
    </div>
  </section>

  <!-- ====== FRASE 1 ====== -->
  <!-- Mobile -->
  <section class="relative w-full h-[50vh] md:h-[65vh] overflow-hidden xl:hidden">
    <img src="<?php echo esc_url( $frase1_bg ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0" style="background:rgba(29,29,27,0.2);mix-blend-mode:multiply"></div>
    <div class="relative z-10 flex items-center justify-center h-full px-6">
      <div class="flex flex-col items-center text-center">
        <p class="font-display text-[28px] leading-[36px] md:text-[48px] md:leading-[58px] text-white uppercase"><?php echo esc_html( $frase1_line1 ); ?></p>
        <p class="font-display text-[28px] leading-[36px] md:text-[48px] md:leading-[58px] text-white uppercase italic"><?php echo esc_html( $frase1_line2 ); ?></p>
        <p class="font-display text-[28px] leading-[36px] md:text-[48px] md:leading-[58px] text-white uppercase"><?php echo esc_html( $frase1_line3 ); ?></p>
      </div>
    </div>
  </section>
  <!-- Desktop -->
  

	<section class="relative w-full overflow-hidden hidden xl:block" style="aspect-ratio: 1920/787;">
		<img src="<?php echo esc_url( $frase1_bg ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover">
		<div class="absolute inset-0" style="background:rgba(29,29,27,0.2);mix-blend-mode:multiply"></div>

		<!-- Contentor Centrado -->
		<div class="absolute inset-0 flex flex-col justify-center items-center text-center">
			<div class="flex flex-col gap-[1vw]">
				<p class="font-display text-[clamp(64px,5.21vw,100px)] [@media(min-width:1337px)]:text-[clamp(64px,4.21vw,100px)] leading-none text-white uppercase whitespace-nowrap  mr-[10%]">
					<?php echo esc_html( $frase1_line1 ); ?>
				</p>
				<p class="font-display self-end text-[clamp(64px,5.21vw,100px)] [@media(min-width:1337px)]:text-[clamp(64px,4.21vw,100px)] leading-none text-white uppercase italic whitespace-nowrap ml-[15%]">
					<?php echo esc_html( $frase1_line2 ); ?>
				</p>
				<p class="font-display self-end text-[clamp(64px,5.21vw,100px)] [@media(min-width:1337px)]:text-[clamp(64px,4.21vw,100px)] leading-none text-white uppercase whitespace-nowrap">
					<?php echo esc_html( $frase1_line3 ); ?>
				</p>
			</div>
		</div>
	</section>


  <!-- ====== HOTÉIS — DINÂMICO ====== -->
  <section id="hoteis-section" class="max-w-[1920px] mx-auto px-6 md:px-10 xl:px-[160px] py-16 xl:py-[100px]">
    <!-- Mobile/Tablet -->
    <div class="flex flex-col gap-6 xl:hidden">
      <div class="flex items-center gap-4">
        <div class="w-10 h-px bg-[#BC945B] flex-shrink-0"></div>
        <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $hoteis_subtitle ); ?></span>
      </div>
      <h2 class="font-display text-[48px] leading-[52px] md:text-[72px] md:leading-[76px] text-[#0d5257] uppercase transition-opacity duration-300" id="mobileHotelTitle"><?php echo esc_html( $h_first['title'] ); ?></h2>
      <p class="font-body font-light text-[16px] leading-[24px] transition-opacity duration-300" id="mobileHotelText"><?php echo esc_html( $h_first['text'] ); ?></p>
      <div class="relative overflow-hidden aspect-[4/3] w-full" id="hotelSliderMobile">
        <?php foreach ( $hoteis_lista as $index => $item ) : ?>
        <img src="<?php echo esc_url( $item['img_big'] ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover transition-all duration-500 ease-in-out" data-slide="<?php echo $index; ?>" style="transform:translateX(<?php echo $index === 0 ? '0' : '100%'; ?>)">
        <?php endforeach; ?>
        <button class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 border border-[#bc945b] bg-white/80 backdrop-blur-sm flex items-center justify-center z-10" data-dir="prev"><img src="<?php echo vbl_img( 'arrow-left.svg' ); ?>" alt="" class="w-5 h-5"></button>
        <button class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 border border-[#bc945b] bg-white/80 backdrop-blur-sm flex items-center justify-center z-10" data-dir="next"><img src="<?php echo vbl_img( 'arrow-right.svg' ); ?>" alt="" class="w-5 h-5"></button>
      </div>
      <a href="<?php echo esc_url( home_url( $h_first['link'] ) ); ?>" class="font-body inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-start transition-opacity duration-300" id="mobileHotelLink"><span>Conhecer</span><img src="<?php echo vbl_img( 'seta-dourada.svg' ); ?>" alt="" class="w-[10px] h-[10px]"></a>
    </div>
    <!-- Desktop -->
    <div class="hidden xl:flex xl:justify-between relative" id="hotelSliderDesktop">
      <div class="w-[40%] flex flex-col flex-shrink-0">
        <div class="flex items-center gap-4">
          <div class="w-10 h-px bg-[#BC945B] flex-shrink-0"></div>
          <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $hoteis_subtitle ); ?></span>
        </div>
        <h2 class="font-display text-[clamp(64px,5.21vw,100px)] [@media(min-width:1280px)_and_(max-width:1333px)]:text-[clamp(58px,4.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-[#0d5257] uppercase whitespace-nowrap mt-[22px] transition-opacity duration-300" id="desktopHotelTitle"><?php echo esc_html( $h_first['title'] ); ?></h2>
        <p class="font-body font-light text-[16px] leading-[24px] ml-[12%] mt-[clamp(16px,2vw,40px)] max-w-[520px] transition-opacity duration-300" id="desktopHotelText"><?php echo esc_html( $h_first['text'] ); ?></p>
        <div class="relative flex-1 min-h-0 mt-6 ml-[12%] w-[80%] will-change-[opacity]" id="hotelSmallSlot">
          <?php $small_start_idx = count($hoteis_lista) > 1 ? 1 : 0; ?>
          <?php foreach ( $hoteis_lista as $index => $item ) : ?>
          <img src="<?php echo esc_url( $item['img_small'] ); ?>" alt="" class="<?php echo $index === 0 ? '' : 'absolute inset-0 '; ?>w-full h-full object-cover transition-opacity duration-500 ease-in-out" style="opacity:<?php echo $index === $small_start_idx ? '0.25' : '0'; ?>;">
          <?php endforeach; ?>
        </div>
        <div class="ml-[12%] w-[80%] flex justify-end mt-6">
          <a href="<?php echo esc_url( home_url( $h_first['link'] ) ); ?>" class="group vbl-btn font-body inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase transition-all duration-500 ease-in-out" id="desktopHotelLink">
			  <span>Conhecer</span>
			  <svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
		  </a>
        </div>
      </div>
      <div class="w-[50%] flex-shrink-0 pt-[2%] relative will-change-[opacity]" id="hotelBigSlot">
        <?php foreach ( $hoteis_lista as $index => $item ) : ?>
        <img src="<?php echo esc_url( $item['img_big'] ); ?>" alt="" class="<?php echo $index === 0 ? '' : 'absolute inset-0 mt-[2%] '; ?>w-full aspect-square object-cover transition-opacity duration-500 ease-in-out" style="opacity:<?php echo $index === 0 ? '1' : '0'; ?>;">
        <?php endforeach; ?>
        <!-- Seta Direita: 16px dentro da imagem, 48px fora -->
        <button class="vbl-arrow-btn absolute top-1/2 z-10" style="right:-48px; transform:translateY(-50%);" onclick="hotelSwap('next')"><img src="<?php echo vbl_img( 'arrow-right.svg' ); ?>" alt=""></button>
      </div>
      <button class="vbl-arrow-btn absolute left-[2%] top-[48%] z-10" onclick="hotelSwap('prev')"><img src="<?php echo vbl_img( 'arrow-left.svg' ); ?>" alt=""></button>

      <!-- Hoteis Data Payload -->
      <script type="application/json" id="vbl-hoteis-data">
        <?php echo json_encode( $hoteis_lista ); ?>
      </script>
    </div>
  </section>

  <!-- ====== ILHAS ====== -->
  <!-- Mobile -->
  <section class="max-w-[1920px] mx-auto px-6 md:px-10 py-16 xl:hidden">
    <div class="flex flex-col gap-6">
      <div class="flex items-center gap-4">
        <div class="w-10 h-px bg-[#BC945B] flex-shrink-0"></div>
        <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $ilhas_subtitle ); ?></span>
      </div>
      <h2 class="font-display text-[40px] leading-[44px] md:text-[72px] md:leading-[76px] text-[#0d5257] uppercase"><?php echo wp_kses_post( $ilhas_title ); ?></h2>
      <img src="<?php echo esc_url( $ilhas_img_main ); ?>" alt="" class="w-full aspect-[800/720] object-cover">
      <div class="grid grid-cols-2 gap-4">
        <img src="<?php echo esc_url( $ilhas_img_left ); ?>" alt="" class="w-full aspect-[416/480] object-cover">
        <div class="flex flex-col gap-4">
          <img src="<?php echo esc_url( $ilhas_img_top ); ?>" alt="" class="w-full aspect-[432/240] object-cover">
          <img src="<?php echo esc_url( $ilhas_img_bottom ); ?>" alt="" class="w-full aspect-[432/240] object-cover">
        </div>
      </div>
      <p class="font-body font-light text-[16px] leading-[24px]"><?php echo esc_html( $ilhas_text ); ?></p>
      <a href="<?php echo esc_url( $ilhas_link ); ?>" class="font-body inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-start">
		  <span>Descobrir</span>
		  <img src="<?php echo vbl_img( 'seta-dourada.svg' ); ?>" alt="" class="w-[10px] h-[10px]">
	  </a>
    </div>
  </section>
  <!-- Desktop -->
  <section class="hidden xl:block max-w-[1920px] mx-auto relative overflow-hidden" style="aspect-ratio: 1920/1045; min-height: 700px;">
    <div class="absolute right-[8.3%] top-0 flex items-center gap-4 z-10">
      <div class="w-10 h-px bg-[#BC945B] flex-shrink-0"></div>
      <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $ilhas_subtitle ); ?></span>
    </div>
    <div class="absolute right-[8.3%] top-[3.3%] text-right z-10">
      <h2 class="font-display text-[clamp(64px,5.21vw,100px)] [@media(min-width:1280px)_and_(max-width:1333px)]:text-[clamp(64px,4.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-[#0d5257] uppercase"><?php echo wp_kses_post( $ilhas_title ); ?></h2>
    </div>
    <img src="<?php echo vbl_img( 'estrela.svg' ); ?>" alt="" class="absolute left-[10.4%] top-[19.6%] w-[13.2%] h-auto pointer-events-none z-10">
    <img src="<?php echo esc_url( $ilhas_img_left ); ?>" alt="" class="absolute left-0 top-[40.7%] w-[21.7%] aspect-[416/480] object-cover">
    <img src="<?php echo esc_url( $ilhas_img_main ); ?>" alt="" class="absolute left-[22.5%] top-[31.1%] w-[41.7%] aspect-[800/720] object-cover">
    <div class="absolute left-[65%] top-[26.6%] w-[27%] flex flex-col z-10">
      <div class="flex flex-col gap-10 ml-[20%] max-w-[368px]">
        <p class="font-body font-light text-[16px] leading-[24px]"><?php echo esc_html( $ilhas_text ); ?></p>
        
		  <div class="w-[80%] flex justify-start">
			  <a href="<?php echo esc_url( $ilhas_link ); ?>" class="group vbl-btn font-body inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase transition-all duration-500 ease-in-out">
				  <span>Descobrir</span>
				  <svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
					  <path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
				  </svg>
			  </a>
		  </div>
		
      </div>
      <img src="<?php echo esc_url( $ilhas_img_top ); ?>" alt="" class="w-[83%] aspect-[432/240] object-cover mt-8">
      <img src="<?php echo esc_url( $ilhas_img_bottom ); ?>" alt="" class="w-[83%] aspect-[432/240] object-cover mt-4">
    </div>
  </section>

  <!-- ====== NOTÍCIAS ====== -->
  <?php get_template_part( 'template-parts/section', 'noticias' ); ?>

  <!-- ====== FRASE 2 ====== -->
  <!-- Mobile -->
  <section class="relative w-full bg-[#eee8e5] overflow-hidden py-16 px-6 md:px-10 xl:hidden min-h-[450px] flex flex-col justify-center items-center">    
    <img src="<?php echo vbl_img( 'arvore.svg' ); ?>" alt="" class="hidden [@media(min-width:400px)]:block absolute left-[-15%] top-[-5%] w-[39%] [@media(min-width:768px)_and_(max-width:1023px)]:w-[52%] [@media(min-width:375px)_and_(max-width:765px)]:w-[88%] [@media(min-width:375px)_and_(max-width:765px)]:top-[-1%] h-auto pointer-events-none opacity-40">
		<div class="relative z-10 flex flex-col items-center text-center max-w-[700px] mx-auto gap-2">
		  <p class="font-display text-[32px] leading-[40px] md:text-[44px] md:leading-[52px] text-[#0d5257] uppercase"><?php echo esc_html( $frase2_line1 ); ?></p>
		  <p class="font-display text-[32px] leading-[40px] md:text-[44px] md:leading-[52px] text-[#0d5257] uppercase italic"><?php echo esc_html( $frase2_line2 ); ?></p>
		  <p class="font-display text-[32px] leading-[40px] md:text-[44px] md:leading-[52px] text-[#0d5257] uppercase italic"><?php echo esc_html( $frase2_line3 ); ?></p>
		  <p class="font-body text-[12px] tracking-[1.2px] uppercase leading-[18px] mt-6 max-w-[450px] text-center"><?php echo esc_html( $frase2_desc ); ?></p>
		</div>
  </section>
  <!-- Desktop -->
  <section class="relative w-full bg-[#eee8e5] overflow-hidden hidden xl:block min-h-[500px]" style="aspect-ratio: 1920/743;">
    <img src="<?php echo vbl_img( 'arvore.svg' ); ?>" alt="" class="absolute left-[-17.6%] top-[-4.3%] w-[35.2%] h-auto pointer-events-none">
    <p class="absolute left-[32.2%] top-[28.9%] font-display text-[clamp(48px,5.21vw,100px)] leading-[clamp(48px,5.21vw,100px)] text-[#0d5257] uppercase" style="white-space:nowrap"><?php echo esc_html( $frase2_line1 ); ?></p>
    <p class="absolute left-[25.8%] top-[44.5%] font-display text-[clamp(48px,5.21vw,100px)] leading-[clamp(48px,5.21vw,100px)] text-[#0d5257] uppercase italic" style="white-space:nowrap"><?php echo esc_html( $frase2_line2 ); ?></p>
    <p class="font-body absolute left-[66%] top-[46%] w-[22%] max-w-[304px] text-[12px] tracking-[1.2px] uppercase leading-[15px]"><?php echo esc_html( $frase2_desc ); ?></p>
    <p class="absolute left-[38.5%] top-[60.2%] font-display text-[clamp(48px,5.21vw,100px)] leading-[clamp(48px,5.21vw,100px)] text-[#0d5257] uppercase italic" style="white-space:nowrap"><?php echo esc_html( $frase2_line3 ); ?></p>
  </section>

  <!-- ====== NEWSLETTER ====== -->
  <?php get_template_part( 'template-parts/section', 'newsletter-home' ); ?>

<?php get_footer(); ?>

<?php
/**
 * Fallback menu for transparent header
 */
function vbl_fallback_menu_transparent() {
    $items = array(
        array( 'url' => '/hoteis', 'label' => 'Hotéis', 'dropdown' => true ),
        array( 'url' => '/o-grupo', 'label' => 'O Grupo' ),
        array( 'url' => '/experiencias', 'label' => 'Experiências' ),
        array( 'url' => '/gift-card', 'label' => 'Gift Card' ),
    );
    foreach ( $items as $item ) {
        $dropdown = ! empty( $item['dropdown'] ) ? ' <svg class="vbl-dropdown-arrow w-2 h-1" viewBox="0 0 8 4" fill="none"><path d="M1 0.5L4 3.5L7 0.5" stroke="white" stroke-width="1.2"/></svg>' : '';
        $extra = ! empty( $item['dropdown'] ) ? ' flex items-center gap-2' : '';
        $mega = ! empty( $item['dropdown'] ) ? ' data-mega-trigger data-mega-top="144"' : '';
        echo '<a href="' . esc_url( home_url( $item['url'] ) ) . '" class="vbl-nav-link-white text-[14px] tracking-[1.4px] uppercase text-white' . $extra . '"' . $mega . '>' . esc_html( $item['label'] ) . $dropdown . '</a>';
    }
}
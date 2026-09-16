<?php
/* Template Name: Vila Baleira — Hotéis */
get_header();
?>

<?php
// ── HERO ──
$hero_subtitle = vbl_field( 'vbl_hoteis_hero_subtitle', false, 'turpis ornare enim sem vitae' );
$hero_title    = vbl_field( 'vbl_hoteis_hero_title', false, 'Os Hotéis<br>Vila Baleira' );
$hero_img      = vbl_field( 'vbl_hoteis_hero_img', false, vbl_img( 'hoteis/hero-foto.jpg' ) );
if ( empty( $hero_img ) ) $hero_img = vbl_img( 'hoteis/hero-foto.jpg' );
$hero_text_1   = vbl_field( 'vbl_hoteis_hero_text_1', false, 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.' );
$concha_img    = vbl_field( 'vbl_hoteis_concha_img', false, vbl_img( 'concha.svg' ) );
if ( empty( $concha_img ) ) $concha_img = vbl_img( 'concha.svg' );

// ── FRASE "DESCUBRA UM MUNDO DE PARAÍSOS" ──
$arvore_img    = vbl_field( 'vbl_hoteis_arvore_img', false, vbl_img( 'arvore.svg' ) );
if ( empty( $arvore_img ) ) $arvore_img = vbl_img( 'arvore.svg' );
$frase_line_1  = vbl_field( 'vbl_hoteis_frase_line_1', false, 'Descubra um' );
$frase_line_2  = vbl_field( 'vbl_hoteis_frase_line_2', false, 'mundo de' );
$frase_line_3  = vbl_field( 'vbl_hoteis_frase_line_3', false, 'paraísos.' );
$frase_desc    = vbl_field( 'vbl_hoteis_frase_desc', false, 'Deixe-se envolver pela beleza natural das ilhas da Madeira e do Porto Santo e encontre o seu refúgio de bem-estar num dos nossos hotéis.' );

// ── PORTO SANTO ──
$ps_subtitle   = vbl_field( 'vbl_hoteis_ps_subtitle', false, 'Vila baleira' );
$ps_title      = vbl_field( 'vbl_hoteis_ps_title', false, 'Porto Santo' );
$ps_text       = vbl_field( 'vbl_hoteis_ps_text', false, 'Cercado pela maravilhosa areia dourada, com um invencível leque de atividades de desporto e lazer e um programa de animação diária, este resort promete férias inesquecíveis para momentos em família.' );
$ps_img_big    = vbl_field( 'vbl_hoteis_ps_img_big', false, vbl_img( 'hoteis/porto-santo-760x760.jpg' ) );
if ( empty( $ps_img_big ) ) $ps_img_big = vbl_img( 'hoteis/porto-santo-760x760.jpg' );
$ps_img_small  = vbl_field( 'vbl_hoteis_ps_img_small', false, vbl_img( 'hoteis/porto-santo-520x400.jpg' ) );
if ( empty( $ps_img_small ) ) $ps_img_small = vbl_img( 'hoteis/porto-santo-520x400.jpg' );
$ps_link       = vbl_field( 'vbl_hoteis_ps_link', false, '#' );

// ── SUITES ──
$suites_subtitle = vbl_field( 'vbl_hoteis_suites_subtitle', false, 'Vila baleira' );
$suites_title    = vbl_field( 'vbl_hoteis_suites_title', false, 'Suites' );
$suites_text     = vbl_field( 'vbl_hoteis_suites_text', false, 'Privilegiado pela sua localização sobre a praia do Porto Santo, o Vila Baleira Suites oferece o descanso que se deseja para momentos de tranquilidade.' );
$suites_img      = vbl_field( 'vbl_hoteis_suites_img', false, vbl_img( 'hoteis/suites-680x400.jpg' ) );
if ( empty( $suites_img ) ) $suites_img = vbl_img( 'hoteis/suites-680x400.jpg' );
$suites_link     = vbl_field( 'vbl_hoteis_suites_link', false, '#' );

// ── VILLAGE ──
$village_subtitle = vbl_field( 'vbl_hoteis_village_subtitle', false, 'Vila baleira' );
$village_title    = vbl_field( 'vbl_hoteis_village_title', false, 'Village' );
$village_text     = vbl_field( 'vbl_hoteis_village_text', false, 'De arquitetura minimalista e elegante, integrada na paisagem natural da ilha do Porto Santo, o Vila Baleira Village oferece refúgios de serenidade com um toque de encanto.' );
$village_img      = vbl_field( 'vbl_hoteis_village_img', false, vbl_img( 'hoteis/village-680x400.jpg' ) );
if ( empty( $village_img ) ) $village_img = vbl_img( 'hoteis/village-680x400.jpg' );
$village_link     = vbl_field( 'vbl_hoteis_village_link', false, '#' );

// ── FRASE ILHAS ──
$ilhas_bg      = vbl_field( 'vbl_hoteis_ilhas_bg', false, vbl_img( 'hoteis/frase-ilhas-bg.jpg' ) );
if ( empty( $ilhas_bg ) ) $ilhas_bg = vbl_img( 'hoteis/frase-ilhas-bg.jpg' );
$ilhas_line_1  = vbl_field( 'vbl_hoteis_ilhas_line_1', false, 'Explore as Ilhas' );
$ilhas_line_2  = vbl_field( 'vbl_hoteis_ilhas_line_2', false, 'Madeira e' );
$ilhas_line_3  = vbl_field( 'vbl_hoteis_ilhas_line_3', false, 'Porto santo.' );

// ── FUNCHAL ──
$funchal_subtitle = vbl_field( 'vbl_hoteis_funchal_subtitle', false, 'Vila baleira' );
$funchal_title    = vbl_field( 'vbl_hoteis_funchal_title', false, 'Funchal' );
$funchal_text     = vbl_field( 'vbl_hoteis_funchal_text', false, 'Localizado na zona do Lido, oferece um ambiente moderno e equilibrado entre tranquilidade e a energia da cidade, a poucos minutos do centro do Funchal e perto de várias atrações turísticas.' );
$funchal_img      = vbl_field( 'vbl_hoteis_funchal_img', false, vbl_img( 'hoteis/funchal-680x400.jpg' ) );
if ( empty( $funchal_img ) ) $funchal_img = vbl_img( 'hoteis/funchal-680x400.jpg' );
$funchal_link     = vbl_field( 'vbl_hoteis_funchal_link', false, '#' );

// ── RESIDENCE ──
$residence_subtitle = vbl_field( 'vbl_hoteis_residence_subtitle', false, 'Vila baleira' );
$residence_title    = vbl_field( 'vbl_hoteis_residence_title', false, 'Residence' );
$residence_text     = vbl_field( 'vbl_hoteis_residence_text', false, 'Composto por 24 estúdios amplos, todos equipados com kitchenette e varanda, o hotel oferece um ambiente íntimo e independente, ideal para quem valoriza conforto e autonomia durante a estadia.' );
$residence_img      = vbl_field( 'vbl_hoteis_residence_img', false, vbl_img( 'hoteis/residence-680x400.jpg' ) );
if ( empty( $residence_img ) ) $residence_img = vbl_img( 'hoteis/residence-680x400.jpg' );
$residence_link     = vbl_field( 'vbl_hoteis_residence_link', false, '#' );

// ── SHARED ASSETS ──
$seta_dourada  = vbl_img( 'seta-dourada.svg' );
?>

<!-- ====== HERO — Mobile/Tablet ====== -->
<section class="lg:hidden pt-20">
  <div class="relative min-h-[65vh] md:min-h-[55vh] flex flex-col justify-end">
    <img src="<?php echo esc_url( $hero_img ); ?>" alt="Vila Baleira Hotel" class="absolute inset-0 w-full h-full object-cover">
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
    <div class="md:grid md:grid-cols-[1fr_auto] md:gap-10 md:items-start">
      <div>
        <p class="font-body font-light text-[13px] leading-[19px] text-black/80"><?php echo esc_html( $hero_text_1 ); ?></p>
      </div>
      <img src="<?php echo esc_url( $concha_img ); ?>" alt="" class="hidden md:block w-[100px] opacity-20 pointer-events-none">
    </div>
  </div>
</section>

<!-- ====== HERO — Desktop ====== -->
<section class="hidden lg:block max-w-[1920px] mx-auto lg:px-[8.33%] pt-[80px]">
  <div class="pt-[clamp(32px,3.13vw,60px)] pb-[clamp(80px,7.29vw,140px)]">
    <div class="relative flex">
      <div class="flex-1 pt-[clamp(40px,4.17vw,80px)]">
        <div class="flex items-center gap-4">
          <div class="w-10 h-px bg-[#BC945B]"></div>
          <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $hero_subtitle ); ?></span>
        </div>
        <h1 class="font-display text-[clamp(56px,5.21vw,100px)] leading-[clamp(56px,5.21vw,100px)] text-[#0d5257] uppercase mt-[clamp(16px,1.25vw,24px)]"><?php echo wp_kses_post( $hero_title ); ?></h1>
        <div class="ml-[clamp(40px,4.17vw,80px)] mt-[clamp(20px,2.14vw,41px)] w-[clamp(400px,25vw,480px)]">
          <p class="font-body font-light text-[16px] leading-[24px]"><?php echo esc_html( $hero_text_1 ); ?></p>
        </div>
      </div>
      <div class="w-[40.8%] flex-shrink-0 self-start">
        <img src="<?php echo esc_url( $hero_img ); ?>" alt="Vila Baleira Hotel" class="w-full aspect-[653/720] object-cover">
      </div>
      <img src="<?php echo esc_url( $concha_img ); ?>" alt="" class="absolute pointer-events-none" style="left:48.4%;top:clamp(253px,22.92vw,440px);width:clamp(130px,12.92vw,248px)">
    </div>
  </div>
</section>

<!-- ====== FRASE "DESCUBRA UM MUNDO DE PARAÍSOS" — Mobile/Tablet ====== -->


<!-- ====== FRASE "DESCUBRA UM MUNDO DE PARAÍSOS" — Desktop ====== -->
<!-- <section class="relative w-full bg-[#eee8e5] overflow-hidden hidden lg:block min-h-[500px]" style="aspect-ratio: 1920/743;">-->
<section class="relative w-full bg-[#eee8e5] overflow-hidden hidden xl:block min-h-[500px]" style="aspect-ratio: 1920/743;">
	<img src="<?php echo esc_url( $arvore_img ); ?>" alt="" class="absolute left-[-17.6%] top-[-4.3%] w-[35.2%] h-auto pointer-events-none">
  	<p class="font-display absolute left-[32.2%] top-[28.9%] text-[clamp(48px,5.21vw,100px)] leading-[clamp(48px,5.21vw,100px)] text-[#0d5257] uppercase" style="white-space:nowrap"><?php echo esc_html( $frase_line_1 ); ?></p>
	<p class="font-display absolute left-[21.8%] top-[44.5%] text-[clamp(48px,5.21vw,100px)] leading-[clamp(48px,5.21vw,100px)] text-[#0d5257] uppercase italic" style="white-space:nowrap"><?php echo esc_html( $frase_line_2 ); ?></p>
	<p class="font-body absolute left-[66%] top-[46%] w-[22%] max-w-[304px] text-[12px] tracking-[1.2px] uppercase leading-[15px]"><?php echo esc_html( $frase_desc ); ?></p>
	<p class="font-display absolute left-[38.5%] top-[60.2%] text-[clamp(48px,5.21vw,100px)] leading-[clamp(48px,5.21vw,100px)] text-[#0d5257] uppercase italic" style="white-space:nowrap"><?php echo esc_html( $frase_line_3 ); ?></p>
</section>

<!-- Mobile -->
	<section class="relative w-full bg-[#eee8e5] overflow-hidden py-16 px-6 md:px-10 xl:hidden min-h-[450px] flex flex-col justify-center items-center">    
    	<img src="<?php echo vbl_img( 'arvore.svg' ); ?>" alt="" class="hidden [@media(min-width:400px)]:block absolute left-[-15%] top-[-5%] w-[39%] [@media(min-width:768px)_and_(max-width:1023px)]:w-[52%] [@media(min-width:375px)_and_(max-width:765px)]:w-[88%] [@media(min-width:375px)_and_(max-width:765px)]:top-[-1%] h-auto pointer-events-none opacity-40">
		<div class="relative z-10 flex flex-col items-center text-center max-w-[700px] mx-auto gap-2">
			<p class="font-display text-[32px] leading-[40px] md:text-[44px] md:leading-[52px] text-[#0d5257] uppercase"><?php echo esc_html( $frase_line_1 ); ?></p>
			<p class="font-display text-[32px] leading-[40px] md:text-[44px] md:leading-[52px] text-[#0d5257] uppercase italic"><?php echo esc_html( $frase_line_2 ); ?></p>
			<p class="font-display text-[32px] leading-[40px] md:text-[44px] md:leading-[52px] text-[#0d5257] uppercase italic"><?php echo esc_html( $frase_line_3 ); ?></p>
			<p class="font-body text-[12px] tracking-[1.2px] uppercase leading-[18px] mt-6 max-w-[450px] text-center"><?php echo esc_html( $frase_desc ); ?></p>
		</div>
	</section>



<!-- ====== PORTO SANTO — Mobile/Tablet ====== -->
<section class="lg:hidden px-6 md:px-10 py-12">
  <div class="flex flex-col">
    <div class="flex flex-col gap-4 mb-6">
      <div class="flex items-center gap-4">
        <div class="w-10 h-px bg-[#BC945B]"></div>
        <span class="font-body text-[12px] tracking-[1.2px] uppercase text-[#0d5257]"><?php echo esc_html( $ps_subtitle ); ?></span>
      </div>
      <h2 class="font-display text-[clamp(40px,10vw,56px)] leading-[clamp(44px,10.5vw,60px)] text-[#0d5257] uppercase"><?php echo esc_html( $ps_title ); ?></h2>
    </div>
    <p class="font-body font-light text-[13px] leading-[19px] mb-8 max-w-[400px]"><?php echo esc_html( $ps_text ); ?></p>
    <div class="relative mb-8">
      <img src="<?php echo esc_url( $ps_img_big ); ?>" alt="<?php echo esc_attr( $ps_title ); ?>" class="w-[85%] ml-auto aspect-square object-cover">
      <img src="<?php echo esc_url( $ps_img_small ); ?>" alt="<?php echo esc_attr( $ps_title ); ?>" class="absolute left-0 bottom-[-10%] w-[55%] aspect-[520/400] object-cover shadow-lg">
    </div>
    <a href="<?php echo esc_url( $ps_link ); ?>" class="group vbl-btn font-body inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-end mt-4">
		<span>Conhecer</span>
		<svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
					<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
		</svg>
	</a>
  </div>
</section>

<!-- ====== PORTO SANTO — Desktop ====== -->
<section class="hidden lg:block max-w-[1920px] mx-auto lg:px-[8.33%]">
  <div class="pt-24 pb-12">
    <div class="flex gap-[6.6%]">
      <div class="w-[45.9%] flex-shrink-0 relative">
        <div class="flex flex-col gap-[22px]">
          <div class="flex items-center gap-4">
            <div class="w-10 h-px bg-[#BC945B]"></div>
            <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $ps_subtitle ); ?></span>
          </div>
          <h2 class="font-display text-[clamp(56px,5.21vw,100px)] leading-[clamp(56px,5.21vw,100px)] text-[#0d5257] uppercase" style="white-space:nowrap"><?php echo esc_html( $ps_title ); ?></h2>
        </div>
        <p class="font-body font-light ml-[clamp(40px,4.17vw,80px)] mt-[clamp(20px,1.82vw,35px)] w-[clamp(360px,27.08vw,520px)] text-[16px] leading-[24px]"><?php echo esc_html( $ps_text ); ?></p>
        <div class="ml-[clamp(40px,4.17vw,80px)] mt-[clamp(40px,4.69vw,90px)]">
          <img src="<?php echo esc_url( $ps_img_small ); ?>" alt="<?php echo esc_attr( $ps_title ); ?>" class="w-[clamp(360px,27.08vw,520px)] aspect-[520/400] object-cover">
        </div>
        <div class="ml-[2%] w-[80%] flex justify-end mt-6">
        	<a href="<?php echo esc_url( $ps_link ); ?>" class="group vbl-btn font-body inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase">
			<span>Conhecer</span>
				<svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
					<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
				</svg>
			</a>
        </div>
      </div>
      <div class="w-[47.5%] flex-shrink-0">
        <img src="<?php echo esc_url( $ps_img_big ); ?>" alt="<?php echo esc_attr( $ps_title ); ?>" class="w-full aspect-square object-cover">
      </div>
    </div>
  </div>
</section>

<!-- ====== SUITES + VILLAGE — Mobile/Tablet ====== -->
<section class="lg:hidden px-6 md:px-10 py-10">
  <div class="flex flex-col gap-14 md:grid md:grid-cols-2 md:gap-8">
    <!-- Suites -->
    <div class="flex flex-col">
      <img src="<?php echo esc_url( $suites_img ); ?>" alt="<?php echo esc_attr( $suites_title ); ?>" class="w-full aspect-[680/400] object-cover">
      <div class="pt-5 flex flex-col gap-3">
        <div class="flex items-center gap-3">
          <div class="w-8 h-px bg-[#BC945B]"></div>
          <span class="font-body text-[11px] tracking-[1.1px] uppercase text-[#0d5257]"><?php echo esc_html( $suites_subtitle ); ?></span>
        </div>
        <h2 class="font-display text-[clamp(32px,8.5vw,48px)] leading-[clamp(36px,9vw,52px)] text-[#0d5257] uppercase"><?php echo esc_html( $suites_title ); ?></h2>
        <p class="font-body font-light text-[13px] leading-[19px]"><?php echo esc_html( $suites_text ); ?></p>
        <a href="<?php echo esc_url( $suites_link ); ?>" class="group vbl-btn font-body inline-flex items-center gap-12 px-3 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[13px] tracking-[1.3px] uppercase self-start mt-1">
			<span>Conhecer</span>
			<svg class="w-[9px] h-[9px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
					<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
				</svg>
		  </a>
      </div>
    </div>
    <!-- Village -->
    <div class="flex flex-col">
      <img src="<?php echo esc_url( $village_img ); ?>" alt="<?php echo esc_attr( $village_title ); ?>" class="w-full aspect-[680/400] object-cover">
      <div class="pt-5 flex flex-col gap-3">
        <div class="flex items-center gap-3">
          <div class="w-8 h-px bg-[#BC945B]"></div>
          <span class="font-body text-[11px] tracking-[1.1px] uppercase text-[#0d5257]"><?php echo esc_html( $village_subtitle ); ?></span>
        </div>
        <h2 class="font-display text-[clamp(32px,8.5vw,48px)] leading-[clamp(36px,9vw,52px)] text-[#0d5257] uppercase"><?php echo esc_html( $village_title ); ?></h2>
        <p class="font-body font-light text-[13px] leading-[19px]"><?php echo esc_html( $village_text ); ?></p>
        <a href="<?php echo esc_url( $village_link ); ?>" class="group vbl-btn font-body inline-flex items-center gap-12 px-3 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[13px] tracking-[1.3px] uppercase self-start mt-1">
			<span>Conhecer</span>
			<svg class="w-[9px] h-[9px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
					<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
				</svg>
		  </a>
      </div>
    </div>
  </div>
</section>

<!-- ====== SUITES + VILLAGE — Desktop ====== -->
<section class="hidden lg:block max-w-[1920px] mx-auto lg:px-[8.33%]">
  <div class="pt-12 pb-24">
    <div class="flex gap-[10%]">
      <!-- SUITES: imagem topo, título, texto, botão -->
      <div class="w-[42.5%] flex-shrink-0 flex flex-col">
        <img src="<?php echo esc_url( $suites_img ); ?>" alt="<?php echo esc_attr( $suites_title ); ?>" class="w-full aspect-[680/400] object-cover">
        <div class="flex flex-col gap-[clamp(14px,1.15vw,22px)] mt-[clamp(24px,2.08vw,40px)]">
          <div class="flex items-center gap-4">
            <div class="w-10 h-px bg-[#BC945B]"></div>
            <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $suites_subtitle ); ?></span>
          </div>
          <h2 class="font-display text-[clamp(56px,5.21vw,100px)] leading-[clamp(56px,5.21vw,100px)] text-[#0d5257] uppercase" style="white-space:nowrap"><?php echo esc_html( $suites_title ); ?></h2>
        </div>
        <p class="font-body font-light ml-[clamp(40px,4.17vw,80px)] mt-[clamp(16px,1.56vw,30px)] w-[clamp(380px,31.25vw,600px)] font-light text-[16px] leading-[24px]"><?php echo esc_html( $suites_text ); ?></p>
        <a href="<?php echo esc_url( $suites_link ); ?>" class="group font-body vbl-btn ml-[clamp(40px,4.17vw,80px)] mt-[clamp(16px,1.56vw,30px)] inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-start">
			<span>Conhecer</span>
			<svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
		</a>
      </div>
      <!-- VILLAGE: título, texto, botão, imagem baixo -->
      <div class="w-[47.5%] flex-shrink-0 flex flex-col">
        <div class="flex flex-col gap-[clamp(14px,1.15vw,22px)]">
          <div class="flex items-center gap-4">
            <div class="w-10 h-px bg-[#BC945B]"></div>
            <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $village_subtitle ); ?></span>
          </div>
          <h2 class="font-display text-[clamp(56px,5.21vw,100px)] leading-[clamp(56px,5.21vw,100px)] text-[#0d5257] uppercase" style="white-space:nowrap"><?php echo esc_html( $village_title ); ?></h2>
        </div>
        <p class="font-body font-light ml-[clamp(40px,4.17vw,80px)] mt-[clamp(16px,1.56vw,30px)] w-[clamp(380px,31.25vw,600px)] font-light text-[16px] leading-[24px]"><?php echo esc_html( $village_text ); ?></p>
        <a href="<?php echo esc_url( $village_link ); ?>" class="group font-body vbl-btn ml-[clamp(40px,4.17vw,80px)] mt-[clamp(16px,1.56vw,30px)] inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-start">
			<span>Conhecer</span>
			<svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
		</a>
        <img src="<?php echo esc_url( $village_img ); ?>" alt="<?php echo esc_attr( $village_title ); ?>" class="w-[89.5%] self-end aspect-[680/400] object-cover mt-[clamp(24px,3.13vw,60px)]">
      </div>
    </div>
  </div>
</section>

<!-- ====== FRASE ILHAS — Mobile/Tablet ====== -->
<section class="lg:hidden relative w-full overflow-hidden">
  <div class="relative min-h-[55vh] md:min-h-[50vh] flex flex-col justify-center">
    <img src="<?php echo esc_url( $ilhas_bg ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,0.4) 0%, rgba(29,29,27,0.15) 40%, rgba(29,29,27,0.1) 100%)"></div>
    <div class="relative z-10 flex flex-col items-center gap-1 md:gap-2 py-16 md:py-20 text-center">
      <p class="font-display text-[clamp(32px,8.5vw,56px)] leading-[clamp(38px,9.6vw,62px)] text-white uppercase" style="text-shadow:0 2px 20px rgba(0,0,0,0.25)"><?php echo esc_html( $ilhas_line_1 ); ?></p>
      <p class="font-display italic text-[clamp(32px,8.5vw,56px)] leading-[clamp(38px,9.6vw,62px)] text-white uppercase mr-[-8%] md:mr-[-5%]" style="text-shadow:0 2px 20px rgba(0,0,0,0.25)"><?php echo esc_html( $ilhas_line_2 ); ?></p>
      <p class="font-display text-[clamp(32px,8.5vw,56px)] leading-[clamp(38px,9.6vw,62px)] text-white uppercase ml-[-5%] md:ml-[-3%]" style="text-shadow:0 2px 20px rgba(0,0,0,0.25)"><?php echo esc_html( $ilhas_line_3 ); ?></p>
    </div>
  </div>
</section>

<!-- ====== FRASE ILHAS — Desktop ====== -->
<section class="hidden lg:block w-full relative overflow-hidden">
  <div class="relative min-h-[500px]" style="aspect-ratio:1920/787">
    <div class="absolute inset-0 overflow-hidden">
      <img src="<?php echo esc_url( $ilhas_bg ); ?>" alt="" class="absolute left-0 top-0 w-full h-[183%] object-cover">
    </div>
    <div class="absolute inset-0 bg-[rgba(29,29,27,0.2)] mix-blend-multiply"></div>
    <p class="absolute font-display text-[clamp(64px,5.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-white uppercase" style="white-space:nowrap;left:calc(50% - clamp(336px,26.25vw,504px));top:calc(50% - clamp(101px,7.89vw,151.5px))"><?php echo esc_html( $ilhas_line_1 ); ?></p>
    <p class="absolute font-display italic text-[clamp(64px,5.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-white uppercase" style="white-space:nowrap;left:calc(50% - clamp(213px,16.67vw,320px));top:calc(50% - clamp(24px,1.85vw,35.5px))"><?php echo esc_html( $ilhas_line_2 ); ?> </p>
    <p class="absolute font-display text-[clamp(64px,5.21vw,100px)] leading-[clamp(64px,5.21vw,100px)] text-white uppercase" style="white-space:nowrap;left:calc(50% - clamp(240px,18.75vw,360px));top:calc(50% + clamp(54px,4.19vw,80.5px))"><?php echo esc_html( $ilhas_line_3 ); ?></p>
  </div>
</section>

<!-- ====== FUNCHAL + RESIDENCE — Mobile/Tablet ====== -->
<section class="lg:hidden px-6 md:px-10 py-10">
  <div class="flex flex-col gap-14 md:grid md:grid-cols-2 md:gap-8">
    <!-- Funchal -->
    <div class="flex flex-col">
      <img src="<?php echo esc_url( $funchal_img ); ?>" alt="<?php echo esc_attr( $funchal_title ); ?>" class="w-full aspect-[680/400] object-cover">
      <div class="pt-5 flex flex-col gap-3">
        <div class="flex items-center gap-3">
          <div class="w-8 h-px bg-[#BC945B]"></div>
          <span class="font-body text-[11px] tracking-[1.1px] uppercase text-[#0d5257]"><?php echo esc_html( $funchal_subtitle ); ?></span>
        </div>
        <h2 class="font-display text-[clamp(32px,8.5vw,48px)] leading-[clamp(36px,9vw,52px)] text-[#0d5257] uppercase"><?php echo esc_html( $funchal_title ); ?></h2>
        <p class="font-body font-light text-[13px] leading-[19px]"><?php echo esc_html( $funchal_text ); ?></p>
        <a href="<?php echo esc_url( $funchal_link ); ?>" class="group vbl-btn font-body inline-flex items-center gap-12 px-3 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[13px] tracking-[1.3px] uppercase self-start mt-1">
			<span>Conhecer</span>
			<svg class="w-[9px] h-[9px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
		  </a>
      </div>
    </div>
    <!-- Residence -->
    <div class="flex flex-col">
      <img src="<?php echo esc_url( $residence_img ); ?>" alt="<?php echo esc_attr( $residence_title ); ?>" class="w-full aspect-[680/400] object-cover">
      <div class="pt-5 flex flex-col gap-3">
        <div class="flex items-center gap-3">
          <div class="w-8 h-px bg-[#BC945B]"></div>
          <span class="font-body text-[11px] tracking-[1.1px] uppercase text-[#0d5257]"><?php echo esc_html( $residence_subtitle ); ?></span>
        </div>
        <h2 class="font-display text-[clamp(32px,8.5vw,48px)] leading-[clamp(36px,9vw,52px)] text-[#0d5257] uppercase"><?php echo esc_html( $residence_title ); ?></h2>
        <p class="font-body font-light text-[13px] leading-[19px]"><?php echo esc_html( $residence_text ); ?></p>
        <a href="<?php echo esc_url( $residence_link ); ?>" class="group vbl-btn font-body inline-flex items-center gap-12 px-3 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[13px] tracking-[1.3px] uppercase self-start mt-1">
			<span>Conhecer</span>
			<svg class="w-[9px] h-[9px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
		  </a>
      </div>
    </div>
  </div>
</section>

<!-- ====== FUNCHAL + RESIDENCE — Desktop ====== -->
<section class="hidden lg:block max-w-[1920px] mx-auto lg:px-[8.33%]">
  <div class="py-24">
    <div class="flex gap-[10%]">
      <!-- FUNCHAL: título, texto, botão, imagem baixo -->
      <div class="w-[47.5%] flex-shrink-0 flex flex-col">
        <div class="flex flex-col gap-[clamp(14px,1.15vw,22px)]">
          <div class="flex items-center gap-4">
            <div class="w-10 h-px bg-[#BC945B]"></div>
            <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $funchal_subtitle ); ?></span>
          </div>
          <h2 class="font-display text-[clamp(56px,5.21vw,100px)] leading-[clamp(56px,5.21vw,100px)] text-[#0d5257] uppercase" style="white-space:nowrap"><?php echo esc_html( $funchal_title ); ?></h2>
        </div>
        <p class="font-body ml-[clamp(40px,4.17vw,80px)] mt-[clamp(16px,1.56vw,30px)] w-[clamp(380px,31.25vw,600px)] font-light text-[16px] leading-[24px]"><?php echo esc_html( $funchal_text ); ?></p>
        <a href="<?php echo esc_url( $funchal_link ); ?>" class="group vbl-btn font-body ml-[clamp(40px,4.17vw,80px)] mt-[clamp(16px,1.56vw,30px)] inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-start">
			<span>Conhecer</span>
			<svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
		  </a>
        <img src="<?php echo esc_url( $funchal_img ); ?>" alt="<?php echo esc_attr( $funchal_title ); ?>" class="w-[89.5%] self-end aspect-[680/400] object-cover mt-[clamp(24px,3.13vw,60px)]">
      </div>
      <!-- RESIDENCE: imagem topo, título, texto, botão -->
      <div class="w-[42.5%] flex-shrink-0 flex flex-col">
        <img src="<?php echo esc_url( $residence_img ); ?>" alt="<?php echo esc_attr( $residence_title ); ?>" class="w-full aspect-[680/400] object-cover">
        <div class="flex flex-col gap-[clamp(14px,1.15vw,22px)] mt-[clamp(24px,2.08vw,40px)]">
          <div class="flex items-center gap-4">
            <div class="w-10 h-px bg-[#BC945B]"></div>
            <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $residence_subtitle ); ?></span>
          </div>
          <h2 class="font-display text-[clamp(56px,5.21vw,100px)] leading-[clamp(56px,5.21vw,100px)] text-[#0d5257] uppercase" style="white-space:nowrap"><?php echo esc_html( $residence_title ); ?></h2>
        </div>
        <p class="font-body ml-[clamp(40px,4.17vw,80px)] mt-[clamp(16px,1.56vw,30px)] w-[clamp(340px,27.24vw,523px)] font-light text-[16px] leading-[24px]"><?php echo esc_html( $residence_text ); ?></p>
        <a href="<?php echo esc_url( $residence_link ); ?>" class="group vbl-btn font-body ml-[clamp(40px,4.17vw,80px)] mt-[clamp(16px,1.56vw,30px)] inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-start">
			<span>Conhecer</span>
			<svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
		  </a>
      </div>
    </div>
  </div>
</section>

<!-- ====== NEWSLETTER ====== -->
<?php get_template_part( 'template-parts/section', 'newsletter' ); ?>

<?php get_footer(); ?>

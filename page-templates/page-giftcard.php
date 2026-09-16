<?php
/* Template Name: Vila Baleira — Gift Card */
get_header();
?>

<?php
// ── BANNER ──
$banner_img      = vbl_field( 'vbl_giftcard_banner_img', false, vbl_img( 'giftcard/banner-giftcard.jpg' ) );
if ( empty( $banner_img ) ) $banner_img = vbl_img( 'giftcard/banner-giftcard.jpg' );
$banner_title    = vbl_field( 'vbl_giftcard_banner_title', false, 'Gift Card' );
$banner_text     = vbl_field( 'vbl_giftcard_banner_text', false, 'Quam id morbi tincidunt turpis ut eget amet metus, diam elit faucibus enim pellentesque nisi orci neque leo lorem ipsum dolor sit non.' );

// ── VOUCHERS ──
$voucher_label      = vbl_field( 'vbl_giftcard_voucher_label', false, 'Voucher Vila Baleira' );
$digital_title      = vbl_field( 'vbl_giftcard_digital_title', false, 'Gift Card<br>Digital' );
$digital_text       = vbl_field( 'vbl_giftcard_digital_text', false, 'Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras.' );
$digital_text_desk  = vbl_field( 'vbl_giftcard_digital_text_desk', false, 'Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras. Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor.' );
$digital_btn        = vbl_field( 'vbl_giftcard_digital_btn', false, 'Pedir Gift Card' );
$fisico_title       = vbl_field( 'vbl_giftcard_fisico_title', false, 'Gift Card<br>Físico' );
$fisico_text        = vbl_field( 'vbl_giftcard_fisico_text', false, 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt.' );
$fisico_text_desk   = vbl_field( 'vbl_giftcard_fisico_text_desk', false, 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras.' );
$fisico_btn         = vbl_field( 'vbl_giftcard_fisico_btn', false, 'Pedir Gift Card' );

// ── Decorativos ──
$concha_svg  = vbl_img( 'concha.svg' );
$estrela_svg = vbl_img( 'estrela-dourada.svg' );
$seta_dourada = vbl_img( 'seta-dourada.svg' );

// ── MODAL ──
$modal_digital_label = vbl_field( 'vbl_giftcard_modal_digital_label', false, 'Gift Card Digital' );
$modal_digital_title = vbl_field( 'vbl_giftcard_modal_digital_title', false, 'Pedido de<br>Gift Card' );
$modal_digital_text  = vbl_field( 'vbl_giftcard_modal_digital_text', false, 'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur. Convallis odio massa pellentesque elit non eu fusce auctor mattis.' );
$modal_fisico_label  = vbl_field( 'vbl_giftcard_modal_fisico_label', false, 'Gift Card Físico' );
$modal_fisico_title  = vbl_field( 'vbl_giftcard_modal_fisico_title', false, 'Pedido de<br>Gift Card' );
$modal_fisico_text   = vbl_field( 'vbl_giftcard_modal_fisico_text', false, 'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur. Convallis odio massa pellentesque elit non eu fusce auctor mattis.' );
$modal_btn           = vbl_field( 'vbl_giftcard_modal_btn', false, 'Enviar Pedido' );
?>

<!-- ====== BANNER "GIFT CARD" ====== -->
<section class="relative w-full h-[50vh] md:h-[45vh] xl:h-[440px] xl:max-h-[440px] overflow-hidden">
  <!-- Header transparente sobre banner (mesmo padrão da homepage) -->
  <div class="absolute font-body top-0 left-0 w-full h-20 xl:h-[80px] z-20">
    <div class="max-w-[1920px] mx-auto h-full flex items-center justify-between px-6 xl:px-10">
      <nav class="hidden lg:flex items-center gap-6 xl:gap-10">
        <a href="<?php echo esc_url( home_url( '/hoteis' ) ); ?>" class="vbl-nav-link-white text-[14px] tracking-[1.4px] uppercase text-white flex items-center gap-2" data-mega-trigger data-mega-top="100">Hotéis <svg class="vbl-dropdown-arrow w-2 h-1" viewBox="0 0 8 4" fill="none"><path d="M1 0.5L4 3.5L7 0.5" stroke="white" stroke-width="1.2"/></svg></a>
        <a href="<?php echo esc_url( home_url( '/o-grupo' ) ); ?>" class="vbl-nav-link-white text-[14px] tracking-[1.4px] uppercase text-white">O Grupo</a>
        <a href="<?php echo esc_url( home_url( '/experiencias' ) ); ?>" class="vbl-nav-link-white text-[14px] tracking-[1.4px] uppercase text-white">Experiências</a>
        <a href="<?php echo esc_url( home_url( '/gift-card' ) ); ?>" class="vbl-nav-link-white text-[14px] tracking-[1.4px] uppercase text-white">Gift Card</a>
      </nav>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center gap-1">
        <img src="<?php echo vbl_img( 'logo-top-white.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-7 xl:h-8 w-auto">
        <img src="<?php echo vbl_img( 'logo-bottom-white.svg' ); ?>" alt="" class="h-[8px] xl:h-[12px] w-auto">
      </a>
      <div class="hidden lg:flex items-center gap-6 xl:gap-10">
        <a href="<?php echo esc_url( home_url( '/contactos' ) ); ?>" class="vbl-nav-link-white text-[14px] tracking-[1.4px] uppercase text-white">Contactos</a>
        <span class="vbl-nav-link-white text-[14px] tracking-[1.4px] uppercase text-white flex items-center gap-2 cursor-pointer">PT <svg class="w-2 h-1" viewBox="0 0 8 4" fill="none"><path d="M1 0.5L4 3.5L7 0.5" stroke="white" stroke-width="1.2"/></svg></span>
        <a href="#" class="vbl-btn-reservar flex items-center gap-16 px-4 py-2 border border-white text-white text-[14px] tracking-[1.4px] uppercase"><span>Reservar</span><svg class="w-[10px] h-[10px]" viewBox="0 0 11 11" fill="none"><path d="M1 10L10 1M10 1H3M10 1V8" stroke="white" stroke-width="1.2"/></svg></a>
      </div>
      <button class="lg:hidden w-8 h-8 flex flex-col items-center justify-center gap-1.5 menu-toggle" aria-label="Menu">
        <span class="w-6 h-px bg-white"></span><span class="w-6 h-px bg-white"></span><span class="w-4 h-px bg-white self-end"></span>
      </button>
    </div>
  </div>
  <!-- BG + overlays (3 camadas do Figma) -->
  <img src="<?php echo esc_url( $banner_img ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover">
  <div class="absolute inset-0 mix-blend-multiply" style="background:linear-gradient(to bottom,rgba(0,0,0,0) 25%,rgba(0,0,0,0.65) 100%)"></div>
  <div class="absolute inset-0 mix-blend-multiply" style="background:linear-gradient(to top,rgba(0,0,0,0) 50%,rgba(0,0,0,0.5) 100%)"></div>
  <div class="absolute inset-0 mix-blend-overlay" style="background:linear-gradient(to bottom,rgba(255,255,255,0),#bc945b)"></div>
  <!-- Título centrado -->
  <div class="absolute inset-0 z-10 flex flex-col items-center justify-end pb-[clamp(40px,4.17vw,80px)] px-6">
    <h1 class="font-display text-[clamp(48px,5.21vw,100px)] leading-[clamp(48px,5.21vw,100px)] text-white uppercase text-center" style="text-shadow:0 4px 20px rgba(0,0,0,0.45),0 1px 6px rgba(0,0,0,0.3)"><?php echo esc_html( $banner_title ); ?></h1>
    <p class="font-body font-light text-[16px] leading-[24px] text-white text-center max-w-[440px] mt-[clamp(12px,1.56vw,30px)]" style="text-shadow:0 2px 12px rgba(0,0,0,0.4)"><?php echo esc_html( $banner_text ); ?></p>
  </div>
</section>

<!-- ====== VOUCHERS — Mobile/Tablet ====== -->
<section class="lg:hidden px-6 md:px-10 py-14 md:py-16">
  <div class="flex flex-col gap-14 md:grid md:grid-cols-2 md:gap-8">
    <!-- Card Digital -->
    <div class="relative bg-[#dce9ea] p-7 md:p-8 flex flex-col justify-between md:min-h-[380px] sm:min-h-[300px] overflow-hidden">
      <div class="flex flex-col gap-4">
        <div class="flex items-center gap-3">
          <div class="w-8 h-px bg-[#BC945B]"></div>
          <span class="font-body text-[11px] tracking-[1.1px] uppercase text-[#0d5257]"><?php echo esc_html( $voucher_label ); ?></span>
        </div>
        <h2 class="font-display text-[clamp(36px,9.6vw,52px)] leading-[clamp(40px,10.4vw,56px)] text-[#0d5257] uppercase"><?php echo wp_kses_post( $digital_title ); ?></h2>
      </div>
      <div class="flex flex-col gap-5 mt-6">
        <p class="font-body font-light text-[13px] leading-[19px]"><?php echo esc_html( $digital_text ); ?></p>
        <button class="group font-body vbl-btn inline-flex items-center gap-12 px-3 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[13px] tracking-[1.3px] uppercase self-start cursor-pointer" onclick="openModal('digital')">
			<span><?php echo esc_html( $digital_btn ); ?></span>
			<svg class="w-[9px] h-[9px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
		</button>
      </div>
      <img src="<?php echo esc_url( $concha_svg ); ?>" alt="" class="absolute right-[-20px] top-1/2 -translate-y-1/2 w-[100px] opacity-20 pointer-events-none">
    </div>
    <!-- Card Físico -->
    <div class="relative bg-[#eee8e5] p-7 md:p-8 flex flex-col justify-between md:min-h-[380px] sm:min-h-[300px] overflow-hidden">
      <div class="flex flex-col gap-4">
        <div class="flex items-center gap-3">
          <div class="w-8 h-px bg-[#BC945B]"></div>
          <span class="font-body text-[11px] tracking-[1.1px] uppercase text-[#0d5257]"><?php echo esc_html( $voucher_label ); ?></span>
        </div>
        <h2 class="font-display text-[clamp(36px,9.6vw,52px)] leading-[clamp(40px,10.4vw,56px)] text-[#0d5257] uppercase"><?php echo wp_kses_post( $fisico_title ); ?></h2>
      </div>
      <div class="flex flex-col gap-5 mt-6">
        <p class="font-body font-light text-[13px] leading-[19px]"><?php echo esc_html( $fisico_text ); ?></p>
        <button class="group font-body vbl-btn inline-flex items-center gap-12 px-3 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[13px] tracking-[1.3px] uppercase self-start cursor-pointer" onclick="openModal('fisico')">
			<span><?php echo esc_html( $fisico_btn ); ?></span>
			<svg class="w-[9px] h-[9px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
		  </button>
      </div>
      <img src="<?php echo esc_url( $estrela_svg ); ?>" alt="" class="absolute right-[-10px] top-[20px] w-[90px] pointer-events-none" style="transform:scaleX(-1)">
    </div>
  </div>
</section>

<!-- ====== VOUCHERS — Desktop ====== -->
<section class="hidden lg:block max-w-[1920px] mx-auto lg:px-[8.33%]">
  <div class="py-[clamp(56px,5.73vw,110px)]">
    <div class="flex" style="gap:clamp(20px,2.08vw,40px)">
      <!-- Card Digital: bg rgba(13,82,87,0.1) -->
      <div class="relative flex-1 bg-[#dce9ea] flex flex-col justify-between overflow-hidden" style="padding:clamp(40px,4.17vw,80px);min-height:clamp(360px,27.08vw,520px)">
        <!-- Título -->
        <div class="flex flex-col gap-[clamp(14px,1.15vw,22px)]">
          <div class="flex items-center gap-4">
            <div class="w-10 h-px bg-[#BC945B]"></div>
            <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $voucher_label ); ?></span>
          </div>
          <h2 class="font-display text-[clamp(42px,3.33vw,64px)] leading-[clamp(42px,3.33vw,64px)] text-[#0d5257] uppercase w-[clamp(260px,20.83vw,400px)]"><?php echo wp_kses_post( str_replace( '<br>', ' ', $digital_title ) ); ?></h2>
        </div>
        <!-- Corpo -->
        <div class="flex flex-col gap-[clamp(20px,2.08vw,40px)]">
          <p class="font-body font-light text-[16px] leading-[24px] mt-4"><?php echo esc_html( $digital_text_desk ); ?></p>
          <button class="group font-body vbl-btn inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-start cursor-pointer" onclick="openModal('digital')">
			  <span><?php echo esc_html( $digital_btn ); ?></span>
			  <svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
			</button>
        </div>
        <!-- Concha decorativa -->
        <img src="<?php echo esc_url( $concha_svg ); ?>" alt="" class="absolute pointer-events-none opacity-30" style="right:-5%;top:calc(50% - 15%);width:clamp(160px,12.92vw,248px)">
      </div>
      <!-- Card Físico: bg #eee8e5 -->
      <div class="relative flex-1 bg-[#eee8e5] flex flex-col justify-between overflow-hidden" style="padding:clamp(40px,4.17vw,80px);min-height:clamp(360px,27.08vw,520px)">
        <!-- Título -->
        <div class="flex flex-col gap-[clamp(14px,1.15vw,22px)]">
          <div class="flex items-center gap-4">
            <div class="w-10 h-px bg-[#BC945B]"></div>
            <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $voucher_label ); ?></span>
          </div>
          <h2 class="font-display text-[clamp(42px,3.33vw,64px)] leading-[clamp(42px,3.33vw,64px)] text-[#0d5257] uppercase w-[clamp(260px,20.83vw,400px)]"><?php echo wp_kses_post( str_replace( '<br>', ' ', $fisico_title ) ); ?></h2>
        </div>
        <!-- Corpo -->
        <div class="flex flex-col gap-[clamp(20px,2.08vw,40px)]">
          <p class="font-body font-light text-[16px] leading-[24px] mt-4"><?php echo esc_html( $fisico_text_desk ); ?></p>
          <button class="group font-body vbl-btn inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-start cursor-pointer" onclick="openModal('fisico')">
			  <span><?php echo esc_html( $fisico_btn ); ?></span>
			  <svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
			</button>
        </div>
        <!-- Estrela decorativa -->
        <img src="<?php echo esc_url( $estrela_svg ); ?>" alt="" class="absolute pointer-events-none" style="right:-2%;top:5%;width:clamp(140px,11.3vw,217px);transform:scaleX(-1)">
      </div>
    </div>
  </div>
</section>

<!-- ====== NEWSLETTER ====== -->
<?php get_template_part( 'template-parts/section', 'newsletter' ); ?>

<!-- ====== MODAIS GIFT CARD ====== -->
<!-- Modal Digital -->
<div id="modalDigital" class="fixed inset-0 z-[70] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
  <div class="relative w-[90%] max-w-[1200px] max-h-[90vh] overflow-y-auto bg-[#dce9ea] p-6 md:p-10 lg:p-16 scale-95 translate-y-4 transition-all duration-400 ease-out" id="modalDigitalContent">
    <!-- Fechar -->
    <button class="absolute top-4 right-4 md:top-6 md:right-6 w-8 h-8 flex items-center justify-center cursor-pointer z-10" onclick="closeModal()">
      <svg class="w-5 h-5" viewBox="0 0 20 20" fill="none"><line x1="2" y1="2" x2="18" y2="18" stroke="#0d5257" stroke-width="1.5"/><line x1="18" y1="2" x2="2" y2="18" stroke="#0d5257" stroke-width="1.5"/></svg>
    </button>
    <!-- Conteúdo: flex col em mobile, flex row em desktop -->
    <div class="flex flex-col lg:flex-row lg:gap-16">
      <!-- Info esquerda -->
      <div class="lg:w-[40%] flex-shrink-0 mb-8 lg:mb-0">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-8 h-px bg-[#BC945B]"></div>
          <span class="font-body text-[12px] tracking-[1.2px] uppercase text-[#0d5257]"><?php echo esc_html( $modal_digital_label ); ?></span>
        </div>
        <h2 class="font-display italic text-[clamp(36px,4.17vw,64px)] leading-[clamp(40px,4.58vw,70px)] text-[#0d5257] uppercase mb-6"><?php echo wp_kses_post( $modal_digital_title ); ?></h2>
        <p class="font-body font-light text-[clamp(13px,0.73vw,14px)] leading-[clamp(19px,1.04vw,20px)] text-black/80 max-w-[400px]"><?php echo esc_html( $modal_digital_text ); ?></p>
      </div>
      <!-- Formulário direita -->
      <form class="font-body flex-1 flex flex-col gap-5 vbl-contact-form">
        <input type="hidden" name="origem" value="Gift Card Digital">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input type="text" name="nome" placeholder="NOME" required class="px-4 py-4 border-b border-black/30 bg-transparent text-[14px] tracking-[1.4px] uppercase outline-none focus:border-[#0d5257] transition-colors">
          <input type="email" name="email" placeholder="EMAIL" required class="px-4 py-4 border-b border-black/30 bg-transparent text-[14px] tracking-[1.4px] uppercase outline-none focus:border-[#0d5257] transition-colors">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input type="tel" name="telefone" placeholder="CONTACTO TELEFÓNICO" class="px-4 py-4 border-b border-black/30 bg-transparent text-[14px] tracking-[1.4px] uppercase outline-none focus:border-[#0d5257] transition-colors">
          <input type="text" name="assunto" placeholder="ASSUNTO" class="px-4 py-4 border-b border-black/30 bg-transparent text-[14px] tracking-[1.4px] uppercase outline-none focus:border-[#0d5257] transition-colors">
        </div>
        <textarea name="mensagem" placeholder="MENSAGEM" rows="4" required class="px-4 py-4 border-b border-black/30 bg-transparent text-[14px] tracking-[1.4px] uppercase outline-none resize-none focus:border-[#0d5257] transition-colors"></textarea>
        <div class="flex items-center gap-2 mt-2">         
			<input type="checkbox" name="privacy" required class="w-4 h-4 bg-white rounded flex-shrink-0 accent-[#bc945b]">
          <label class="font-light text-[13px]">Li e aceito a <u class="underline-offset-2">Política de Privacidade.</u></label>
        </div>
        <button type="submit" class="group vbl-btn inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-start mt-2 cursor-pointer">
			<span><?php echo esc_html( $modal_btn ); ?></span>
			<svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
		  </button>
        <div class="vbl-form-feedback hidden text-[13px] font-medium py-1"></div>
      </form>
    </div>
    <!-- Concha decorativa -->
    <img src="<?php echo esc_url( $concha_svg ); ?>" alt="" class="absolute bottom-4 right-4 w-[clamp(80px,8.33vw,160px)] opacity-15 pointer-events-none hidden lg:block">
  </div>
</div>

<!-- Modal Físico -->
<div id="modalFisico" class="fixed inset-0 z-[70] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
  <div class="relative w-[90%] max-w-[1200px] max-h-[90vh] overflow-y-auto bg-[#eee8e5] p-6 md:p-10 lg:p-16 scale-95 translate-y-4 transition-all duration-400 ease-out" id="modalFisicoContent">
    <!-- Fechar -->
    <button class="absolute top-4 right-4 md:top-6 md:right-6 w-8 h-8 flex items-center justify-center cursor-pointer z-10" onclick="closeModal()">
      <svg class="w-5 h-5" viewBox="0 0 20 20" fill="none"><line x1="2" y1="2" x2="18" y2="18" stroke="#0d5257" stroke-width="1.5"/><line x1="18" y1="2" x2="2" y2="18" stroke="#0d5257" stroke-width="1.5"/></svg>
    </button>
    <!-- Conteúdo -->
    <div class="flex flex-col lg:flex-row lg:gap-16">
      <!-- Info esquerda -->
      <div class="lg:w-[40%] flex-shrink-0 mb-8 lg:mb-0">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-8 h-px bg-[#BC945B]"></div>
          <span class="font-body text-[12px] tracking-[1.2px] uppercase text-[#0d5257]"><?php echo esc_html( $modal_fisico_label ); ?></span>
        </div>
        <h2 class="font-display italic text-[clamp(36px,4.17vw,64px)] leading-[clamp(40px,4.58vw,70px)] text-[#0d5257] uppercase mb-6"><?php echo wp_kses_post( $modal_fisico_title ); ?></h2>
        <p class="font-body font-light text-[clamp(13px,0.73vw,14px)] leading-[clamp(19px,1.04vw,20px)] text-black/80 max-w-[400px]"><?php echo esc_html( $modal_fisico_text ); ?></p>
      </div>
      <!-- Formulário direita -->
      <form class="font-body flex-1 flex flex-col gap-5 vbl-contact-form">
        <input type="hidden" name="origem" value="Gift Card Físico">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input type="text" name="nome" placeholder="NOME" required class="px-4 py-4 border-b border-black/30 bg-transparent text-[14px] tracking-[1.4px] uppercase outline-none focus:border-[#0d5257] transition-colors">
          <input type="email" name="email" placeholder="EMAIL" required class="px-4 py-4 border-b border-black/30 bg-transparent text-[14px] tracking-[1.4px] uppercase outline-none focus:border-[#0d5257] transition-colors">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input type="tel" name="telefone" placeholder="CONTACTO TELEFÓNICO" class="px-4 py-4 border-b border-black/30 bg-transparent text-[14px] tracking-[1.4px] uppercase outline-none focus:border-[#0d5257] transition-colors">
          <input type="text" name="assunto" placeholder="ASSUNTO" class="px-4 py-4 border-b border-black/30 bg-transparent text-[14px] tracking-[1.4px] uppercase outline-none focus:border-[#0d5257] transition-colors">
        </div>
        <textarea name="mensagem" placeholder="MENSAGEM" rows="4" required class="px-4 py-4 border-b border-black/30 bg-transparent text-[14px] tracking-[1.4px] uppercase outline-none resize-none focus:border-[#0d5257] transition-colors"></textarea>
        <div class="flex items-center gap-2 mt-2">
			<input type="checkbox" name="privacy" required class="w-4 h-4 bg-white rounded flex-shrink-0 accent-[#bc945b]">
          	<label class="font-light text-[13px]">Li e aceito a <u class="underline-offset-2">Política de Privacidade.</u></label>
        </div>
        <button type="submit" class="vbl-btn inline-flex items-center gap-16 px-4 py-2 border-t border-b border-[#bc945b] text-[#bc945b] text-[14px] tracking-[1.4px] uppercase self-start mt-2 cursor-pointer">
			<span><?php echo esc_html( $modal_btn ); ?></span>
			<svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none">
				<path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
			</svg>
		  </button>
        <div class="vbl-form-feedback hidden text-[13px] font-medium py-1"></div>
      </form>
    </div>
    <!-- Estrela decorativa -->
    <img src="<?php echo esc_url( $estrela_svg ); ?>" alt="" class="absolute bottom-4 right-4 w-[clamp(80px,8.33vw,160px)] pointer-events-none hidden lg:block" style="transform:scaleX(-1)">
  </div>
</div>

<script>
  // === MODAIS — motion design: backdrop fade + content slide-up + scale ===
  let activeModal = null;
  window.openModal = function(type) {
    const modal = document.getElementById(type === 'digital' ? 'modalDigital' : 'modalFisico');
    const content = document.getElementById(type === 'digital' ? 'modalDigitalContent' : 'modalFisicoContent');
    activeModal = modal;
    document.body.style.overflow = 'hidden';
    // Step 1: show overlay (opacity)
    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.classList.add('opacity-100');
    // Step 2: animate content in (scale + translateY)
    requestAnimationFrame(() => {
      content.style.transform = 'scale(1) translateY(0)';
      content.style.opacity = '1';
    });
  };
  window.closeModal = function() {
    if (!activeModal) return;
    const content = activeModal.querySelector('[id$="Content"]');
    // Step 1: animate content out
    content.style.transform = 'scale(0.95) translateY(16px)';
    content.style.opacity = '0.5';
    // Step 2: fade overlay
    setTimeout(() => {
      activeModal.classList.add('opacity-0', 'pointer-events-none');
      activeModal.classList.remove('opacity-100');
      document.body.style.overflow = '';
      // Reset content for next open
      setTimeout(() => {
        content.style.transform = '';
        content.style.opacity = '';
        activeModal = null;
      }, 300);
    }, 150);
  };
  // Close on Escape
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
</script>

<?php get_footer(); ?>
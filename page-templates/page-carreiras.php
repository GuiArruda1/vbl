<?php
/**
 * Template Name: Vila Baleira — Carreiras
 *
 * @package Vila_Baleira
 */

get_header();

// ── BANNER HERO ──
$banner_img = vbl_field( 'vbl_carreiras_banner_img', false, vbl_img( 'carreiras_banner.png' ) );
if ( empty( $banner_img ) ) {
    $banner_img = vbl_img( 'carreiras_banner.png' );
}
$banner_title = vbl_field( 'vbl_carreiras_banner_title', false, 'CARREIRAS' );
$banner_desc  = vbl_field( 'vbl_carreiras_banner_desc', false, "No Vila Baleira, valorizamos a inovação, colaboração, compromisso e crescimento.\nJunta-te à equipa e aproveita as melhores oportunidades de carreira connosco!" );

// ── FORMULÁRIO CONFIGURAÇÃO ──
$positions_raw = vbl_field( 'vbl_carreiras_positions', false, "Receção / Front Office\nF&B / Restaurante & Bar\nCozinha & Pastelaria\nHousekeeping / Andares\nManutenção & Serviços Técnicos\nComercial, Vendas & Marketing\nAdministrativo / Recursos Humanos\nSpa & Bem-Estar\nCandidatura Espontânea" );
$positions_list = array_filter( array_map( 'trim', explode( "\n", str_replace( "\r", "", (string) $positions_raw ) ) ) );

$btn_text          = vbl_field( 'vbl_carreiras_btn_text', false, 'ENVIAR CANDIDATURA' );
$email_info_text   = vbl_field( 'vbl_carreiras_email_info_text', false, 'Podes também enviar o teu currículo para o seguinte endereço de e-mail:' );
$email_display     = vbl_field( 'vbl_carreiras_email_display', false, 'rh@vilabaleira.com' );
$privacy_text      = vbl_field( 'vbl_carreiras_privacy_text', false, 'Li e aceito a' );
$privacy_link_text = vbl_field( 'vbl_carreiras_privacy_link_text', false, 'Política de Privacidade.' );
$privacy_url       = vbl_field( 'vbl_carreiras_privacy_url', false, '/politica-de-privacidade' );

$lang_data = function_exists( 'vbl_get_languages' ) ? vbl_get_languages() : array( 'current' => 'PT', 'languages' => array() );
?>

<!-- ====== HERO BANNER "CARREIRAS" (1920x440) ====== -->
<section class="relative w-full h-[420px] sm:h-[440px] xl:h-[440px] xl:max-h-[440px] overflow-hidden">
  
  <!-- Header transparente sobre o banner -->
  <div class="absolute font-body top-0 left-0 w-full h-20 xl:h-[80px] z-20">
    <div class="max-w-[1920px] mx-auto h-full flex items-center justify-between px-6 xl:px-10">
      
      <!-- Nav Esquerda Desktop -->
      <nav class="hidden xl:flex items-center gap-6 xl:gap-10">
        <?php vbl_header_nav_items( 'transparent' ); ?>
      </nav>

      <!-- Logo Central (White) -->
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center gap-1" aria-label="<?php bloginfo( 'name' ); ?>">
        <img src="<?php echo esc_url( vbl_img( 'logo-top-white.svg' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-7 xl:h-8 w-auto">
        <img src="<?php echo esc_url( vbl_img( 'logo-bottom-white.svg' ) ); ?>" alt="" class="h-[8px] xl:h-[12px] w-auto">
      </a>

      <!-- Nav Direita Desktop -->
      <div class="hidden xl:flex items-center gap-6 xl:gap-10">
        <a href="<?php echo esc_url( home_url( '/contactos' ) ); ?>" class="vbl-nav-link-white text-[14px] tracking-[1.4px] uppercase text-white">Contactos</a>
        
        <!-- Language Switcher Desktop -->
        <div class="relative group">
          <button type="button" class="vbl-nav-link-white text-[14px] tracking-[1.4px] uppercase text-white flex items-center gap-2 cursor-pointer" aria-label="Alterar idioma">
            <span><?php echo esc_html( $lang_data['current'] ); ?></span>
            <svg class="w-2 h-1 group-hover:rotate-180 transition-transform duration-300" viewBox="0 0 8 4" fill="none" aria-hidden="true">
              <path d="M1 0.5L4 3.5L7 0.5" stroke="white" stroke-width="1.2"/>
            </svg>
          </button>
          <div class="absolute top-full right-0 hidden group-hover:flex flex-col bg-white border border-[#eee8e5] py-2 min-w-[90px] shadow-xl text-[#0d5257] z-50">
            <?php foreach ( $lang_data['languages'] as $lang_item ) : ?>
              <a href="<?php echo esc_url( $lang_item['url'] ); ?>" class="px-4 py-1.5 text-[12px] uppercase <?php echo ! empty( $lang_item['is_current'] ) ? 'font-bold text-[#0da9a6]' : 'text-[#0d5257] hover:text-[#0da9a6] hover:bg-[#f8f6f4]'; ?> transition-colors">
                <?php echo esc_html( $lang_item['code'] ); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Botão Reservar Desktop -->
        <a href="#" class="group vbl-btn-reservar flex items-center gap-16 px-4 py-2 border border-white text-white text-[14px] tracking-[1.4px] uppercase transition-all duration-500 ease-in-out">
          <span>Reservar</span>
          <svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45 fill-none" viewBox="0 0 11 11" fill="none" aria-hidden="true">
            <path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
          </svg>
        </a>
      </div>

      <!-- Mobile Hamburger Button -->
      <button class="xl:hidden w-8 h-8 flex flex-col items-center justify-center gap-1.5 menu-toggle cursor-pointer" aria-label="Abrir Menu" aria-expanded="false">
        <span class="w-6 h-px bg-white"></span>
        <span class="w-6 h-px bg-white"></span>
        <span class="w-4 h-px bg-white self-end"></span>
      </button>

      <!-- Mobile & Tablet RESERVAR Button -->
      <div class="xl:hidden flex items-center gap-3">
        <div class="relative group">
          <button type="button" class="text-[12px] sm:text-[14px] tracking-[1.4px] uppercase text-white flex items-center gap-1.5 cursor-pointer">
            <span><?php echo esc_html( $lang_data['current'] ); ?></span>
            <svg class="w-2 h-1 group-hover:rotate-180 transition-transform duration-300" viewBox="0 0 8 4" fill="none" aria-hidden="true">
              <path d="M1 0.5L4 3.5L7 0.5" stroke="currentColor" stroke-width="1.2"/>
            </svg>
          </button>
          <div class="absolute top-full right-0 hidden group-hover:flex flex-col bg-white border border-[#eee8e5] py-2 min-w-[80px] shadow-xl text-[#0d5257] z-50">
            <?php foreach ( $lang_data['languages'] as $lang_item ) : ?>
              <a href="<?php echo esc_url( $lang_item['url'] ); ?>" class="px-4 py-1.5 text-[12px] uppercase <?php echo ! empty( $lang_item['is_current'] ) ? 'font-bold text-[#0da9a6]' : 'text-[#0d5257] hover:text-[#0da9a6] hover:bg-[#f8f6f4]'; ?> transition-colors">
                <?php echo esc_html( $lang_item['code'] ); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
        <a href="#" class="vbl-btn-reservar group hidden [@media(min-width:426px)_and_(max-width:1279px)]:flex items-center gap-2 sm:gap-4 px-3 py-1.5 sm:px-4 sm:py-2 border border-white text-white text-[12px] sm:text-[14px] tracking-[1.2px] sm:tracking-[1.4px] uppercase transition-all duration-300">
          <span>Reservar</span>
          <svg class="w-[9px] h-[9px] sm:w-[10px] sm:h-[10px] transition-transform duration-300 group-hover:rotate-45" viewBox="0 0 11 11" fill="none" aria-hidden="true">
            <path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
          </svg>
        </a>
      </div>

    </div>
  </div>

  <!-- Imagem de Fundo do Banner -->
  <img src="<?php echo esc_url( $banner_img ); ?>" alt="<?php echo esc_attr( $banner_title ); ?>" class="absolute inset-0 w-full h-full object-cover">
  
  <!-- Gradiente de Sobreposição Elegante -->
  <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.30) 45%, rgba(0,0,0,0.60) 100%);"></div>

  <!-- Conteúdo Central do Banner -->
  <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-6 pt-20 pb-8">
    <h1 class="font-display text-[44px] sm:text-[58px] md:text-[72px] lg:text-[80px] tracking-[4px] text-white uppercase leading-none mb-3 sm:mb-4" style="text-shadow: 0 4px 20px rgba(0,0,0,0.5);">
      <?php echo esc_html( $banner_title ); ?>
    </h1>
    <p class="font-body font-light text-[13px] sm:text-[14px] md:text-[15px] lg:text-[16px] leading-relaxed text-white max-w-[640px] mx-auto opacity-95" style="text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
      <?php echo nl2br( esc_html( $banner_desc ) ); ?>
    </p>
  </div>

</section>

<!-- ====== FORMULÁRIO DE CANDIDATURA (Design Fiel ao Layout) ====== -->
<section class="w-full bg-white py-16 sm:py-20 md:py-24 lg:py-28">
  <div class="vbl-container">
    <div class="max-w-[760px] mx-auto">
      
      <form id="vblCarreirasForm" class="flex flex-col gap-8 md:gap-10 font-body" enctype="multipart/form-data">
        
        <?php wp_nonce_field( 'vbl_nonce', 'vbl_carreira_nonce' ); ?>
        <input type="hidden" name="action" value="vbl_carreira_submit">

        <!-- Linha 1: Nome Completo -->
        <div>
          <label for="carreira_nome" class="sr-only">Nome Completo</label>
          <input
            type="text"
            id="carreira_nome"
            name="nome"
            placeholder="NOME COMPLETO"
            required
            class="vbl-form-input-clean text-black">
        </div>

        <!-- Linha 2: Contacto Telefónico & Email -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10">
          <div>
            <label for="carreira_telefone" class="sr-only">Contacto Telefónico</label>
            <input
              type="tel"
              id="carreira_telefone"
              name="telefone"
              placeholder="CONTACTO TELEFÓNICO"
              class="vbl-form-input-clean text-black">
          </div>
          <div>
            <label for="carreira_email" class="sr-only">Email</label>
            <input
              type="email"
              id="carreira_email"
              name="email"
              placeholder="EMAIL"
              required
              class="vbl-form-input-clean text-black">
          </div>
        </div>

        <!-- Linha 3: Posição a que me candidato & Upload CV -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10 items-end">
          
          <!-- Posição a que me candidato -->
          <div class="relative">
            <label for="carreira_posicao" class="sr-only">Posição a que me candidato</label>
            <select
              id="carreira_posicao"
              name="posicao"
              required
              class="vbl-form-input-clean appearance-none bg-transparent cursor-pointer pr-8 text-black/60 focus:text-black valid:text-black">
              <option value="" disabled selected hidden>POSIÇÃO A QUE ME CANDIDATO</option>
              <?php foreach ( $positions_list as $pos ) : ?>
                <option value="<?php echo esc_attr( $pos ); ?>" class="text-black bg-white normal-case"><?php echo esc_html( $pos ); ?></option>
              <?php endforeach; ?>
            </select>
            <div class="pointer-events-none absolute right-1 top-1/2 -translate-y-1/2 text-black/60">
              <svg class="w-3.5 h-3.5" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.2">
                <path d="M1 1L5 5L9 1" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </div>

          <!-- Upload CV -->
          <div class="relative">
            <input
              type="file"
              id="carreira_cv"
              name="cv_file"
              accept=".pdf,.doc,.docx,.rtf,.odt"
              class="sr-only">
            <label
              for="carreira_cv"
              id="carreira_cv_label"
              class="vbl-form-input-clean flex items-center justify-between cursor-pointer select-none text-black/60 hover:text-black transition-colors"
              title="Carregar currículo (PDF, DOC, DOCX)">
              <span id="carreira_cv_filename" class="truncate pr-3">UPLOAD CV</span>
              <span id="carreira_cv_action" class="text-[20px] font-light leading-none flex-shrink-0 text-black/70 hover:text-black transition-colors" title="Selecionar ficheiro">+</span>
            </label>
          </div>

        </div>

        <!-- Linha 4: Checkbox de Privacidade -->
        <div class="flex items-center gap-3 pt-1">
          <input
            type="checkbox"
            id="carreira_privacy"
            name="privacy"
            required
            class="w-4 h-4 rounded border-black/30 text-dourado accent-dourado focus:ring-0 cursor-pointer">
          <label for="carreira_privacy" class="font-body text-[13px] md:text-[14px] font-light text-black/80 cursor-pointer">
            <?php echo esc_html( $privacy_text ); ?>
            <a href="<?php echo esc_url( function_exists( 'vbl_resolve_url' ) ? vbl_resolve_url( $privacy_url ) : $privacy_url ); ?>" target="_blank" rel="noopener noreferrer" class="underline underline-offset-4 decoration-black/40 hover:text-dourado hover:decoration-dourado transition-all">
              <?php echo esc_html( $privacy_link_text ); ?>
            </a>
          </label>
        </div>

        <!-- Linha 5: Botão Enviar Candidatura -->
        <div class="pt-2">
          <button
            type="submit"
            id="carreiraSubmitBtn"
            class="group vbl-btn-primary inline-flex items-center gap-4 px-6 py-2.5 border-t border-b border-dourado text-dourado text-[14px] tracking-[1.4px] uppercase transition-all duration-300 hover:bg-dourado/10 cursor-pointer">
            <span id="carreiraSubmitText"><?php echo esc_html( $btn_text ); ?></span>
            <svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45" viewBox="0 0 11 11" fill="none" aria-hidden="true">
              <path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
            </svg>
          </button>
        </div>

        <!-- Feedback de Envio -->
        <div id="carreiraFeedback" class="hidden text-[14px] font-medium py-3 px-4 rounded-sm transition-all" role="alert"></div>

        <!-- Linha 6: Envio alternativo por email -->
        <div class="pt-1 text-[13px] md:text-[14px] font-light text-black/80 leading-relaxed">
          <p>
            <?php echo esc_html( $email_info_text ); ?>
            <a href="mailto:<?php echo esc_attr( $email_display ); ?>" class="underline underline-offset-4 decoration-black/40 hover:text-dourado hover:decoration-dourado transition-colors font-normal">
              <?php echo esc_html( $email_display ); ?>
            </a>
          </p>
        </div>

      </form>

    </div>
  </div>
</section>

<!-- ====== SECÇÃO NEWSLETTER (Global) ====== -->
<?php get_template_part( 'template-parts/section-newsletter' ); ?>

<?php get_footer(); ?>

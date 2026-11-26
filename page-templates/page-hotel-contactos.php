<?php
/**
 * Template Name: Hotel — Contactos
 * Template para a página de Contactos do Microsite de Hotel.
 *
 * @package Vila_Baleira
 */

// Inclui o Header Transparente com branding do Hotel
require_once VBL_DIR . '/header-hotel.php';

// ACF Fields: Hero Banner
$hero_bg = vbl_field( 'vbl_hct_hero_bg', false, vbl_img( 'hoteis/porto-santo-760x760.jpg' ) );
if ( empty( $hero_bg ) ) {
    $hero_bg = vbl_img( 'hoteis/porto-santo-760x760.jpg' );
}

// ACF Fields: Informações & Mapa
$hotel_sub = vbl_field( 'vbl_hct_subtitle', false, 'VILA BALEIRA PORTO SANTO' );
$hotel_tit = vbl_field( 'vbl_hct_title', false, 'ENTRAR EM<br>CONTACTO' );

$morada     = vbl_field( 'vbl_hct_morada', false, "Sítio do Cabeço da Ponta, Apartado 243,\n9400-909 Porto Santo" );
$telefone   = vbl_field( 'vbl_hct_telefone', false, '+351 291 980 800' );
$email      = vbl_field( 'vbl_hct_email', false, 'portosanto@vilabaleira.com' );
$mapa_embed = vbl_field( 'vbl_hct_mapa_embed', false, '' );
$mapa_url   = vbl_field( 'vbl_hct_mapa_url', false, '' );

// Resolver URL do iframe do Google Maps
$final_map_src = '';

if ( ! empty( $mapa_embed ) ) {
    if ( preg_match( '/src=["\']([^"\']+)["\']/', $mapa_embed, $m ) ) {
        $final_map_src = $m[1];
    } else {
        $final_map_src = trim( $mapa_embed );
    }
}
// Só aceita URLs que realmente podem ser embebidos (o Google bloqueia links normais em iframe)
if ( ! empty( $final_map_src ) && false === strpos( $final_map_src, 'google.com/maps/embed' ) && false === strpos( $final_map_src, 'openstreetmap.org' ) ) {
    $final_map_src = '';
}

// Resolver hotel ativo e cor do marcador (conforme guia de cores dos microsites)
$hotel_slug = '';
if ( ! empty( $current_hotel_id ) ) {
    $hp = get_post( $current_hotel_id );
    if ( $hp ) {
        $hotel_slug = $hp->post_name;
    }
}
if ( empty( $hotel_slug ) ) {
    global $post;
    if ( ! empty( $post->post_parent ) ) {
        $pp = get_post( $post->post_parent );
        if ( $pp ) {
            $hotel_slug = $pp->post_name;
        }
    }
    if ( empty( $hotel_slug ) && ! empty( $post ) ) {
        $hotel_slug = $post->post_name;
    }
}

$hotel_pin_colors = array(
    'porto-santo' => '#00B5B4',
    'funchal'     => '#F0B85E',
    'madeira'     => '#F0B85E',
    'suites'      => '#D7A584',
    'village'     => '#658D72',
    'residence'   => '#BC945B',
    'residences'  => '#BC945B',
);
$pin_color = ! empty( $hotel_accent_color ) ? $hotel_accent_color : '#BC945B';
foreach ( $hotel_pin_colors as $key => $hex ) {
    if ( false !== stripos( $hotel_slug, $key ) || ( isset( $hotel_name ) && false !== stripos( (string) $hotel_name, $key ) ) || false !== stripos( (string) $hotel_sub, $key ) ) {
        $pin_color = $hex;
        break;
    }
}

// Se não houver mapa personalizado, resolver automaticamente com base no hotel atual
if ( empty( $final_map_src ) ) {
    $hotel_map_defaults = array(
        'porto-santo' => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d45000!2d-16.3626213!3d33.0374124!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-PT!2spt!4v1700000000000',
        'funchal'     => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d350000!2d-16.9307499!3d32.6383247!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-PT!2spt!4v1700000000000',
        'suites'      => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d45000!2d-16.3608198!3d33.0373331!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-PT!2spt!4v1700000000000',
        'village'     => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d45000!2d-16.3680087!3d33.0364243!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-PT!2spt!4v1700000000000',
        'residence'   => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d350000!2d-16.9424958!3d32.6374392!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-PT!2spt!4v1700000000000',
        'residences'  => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d350000!2d-16.9424958!3d32.6374392!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-PT!2spt!4v1700000000000',
    );

    foreach ( $hotel_map_defaults as $key => $url ) {
        if ( false !== strpos( $hotel_slug, $key ) || ( isset( $hotel_name ) && false !== stripos( (string) $hotel_name, $key ) ) || false !== stripos( (string) $hotel_sub, $key ) ) {
            $final_map_src = $url;
            break;
        }
    }

    if ( empty( $final_map_src ) ) {
        if ( ! empty( $morada ) ) {
            $clean_addr = trim( preg_replace( '/\s+/', ' ', str_replace( array( "\r", "\n" ), ' ', $morada ) ) );
            $final_map_src = 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d45000!2d-16.3626213!3d33.0374124!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-PT!2spt!4v1700000000000';
        } else {
            $final_map_src = 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d45000!2d-16.3626213!3d33.0374124!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-PT!2spt!4v1700000000000';
        }
    }
}
$final_map_src = str_replace( '!1d16000!', '!1d350000!', $final_map_src );


// ACF Fields: Formulário
$form_sub    = vbl_field( 'vbl_hct_form_sub', false, 'LOREM IPSUM DOLOR SIT AMET CONSECTETUR' );
$form_tit    = vbl_field( 'vbl_hct_form_tit', false, 'FAUCIBUS SIT<br>DIAM ELIT' );
$form_p1     = vbl_field( 'vbl_hct_form_p1', false, '' );
if ( false !== stripos( (string) $form_p1, 'Convallis odio massa' ) || false !== stripos( (string) $form_p1, 'Lorem ipsum dolor sit amet' ) ) {
    $form_p1 = '';
}
$form_p2     = vbl_field( 'vbl_hct_form_p2', false, '' );
if ( false !== stripos( (string) $form_p2, 'Convallis odio massa' ) || false !== stripos( (string) $form_p2, 'Lorem ipsum' ) ) {
    $form_p2 = '';
}
$privacy_url = function_exists( 'vbl_get_privacy_policy_url' ) ? vbl_get_privacy_policy_url() : ( function_exists( 'vbl_field' ) ? vbl_field( 'vbl_privacy_url', false, home_url( '/politica-de-privacidade' ) ) : home_url( '/politica-de-privacidade' ) );
?>

<main class="w-full bg-white text-black overflow-hidden">

  <!-- ==========================================
       1. HERO BANNER
  =========================================== -->
  <section class="relative w-full h-[280px] sm:h-[360px] max-h-[360px] overflow-hidden bg-black flex items-end vbl-hotel-subpage-hero">
    <div class="absolute inset-0 z-0">
      <img src="<?php echo esc_url( $hero_bg ); ?>" alt="Contactos Hotel" class="w-full h-full object-cover object-center filter brightness-[0.85]">
      <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/50"></div>
    </div>
  </section>

  <!-- ==========================================
       2. ENTRAR EM CONTACTO & MAPA
  =========================================== -->
  <section class="w-full py-20 lg:py-32 bg-white">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-center">
      
      <!-- Coluna Esquerda: Mapa Interativo (Google Maps) -->
      <div class="lg:col-span-6 relative flex justify-start w-full">
        <div class="relative w-full h-[380px] sm:h-[480px] lg:h-[600px] max-w-full rounded-sm overflow-hidden shadow-xs bg-[#eef2f5]">
          <iframe 
            src="<?php echo esc_url( $final_map_src ); ?>" 
            width="100%" 
            height="100%" 
            class="w-full h-full border-0 min-h-[380px] sm:min-h-[480px] lg:min-h-[600px]" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade" 
            title="Mapa de Localização do Hotel">
          </iframe>
          <svg width="33" height="40" viewBox="0 0 33 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="position:absolute;top:50%;left:50%;width:35.2px;height:auto;transform:translate(-50%,-100%);pointer-events:none;z-index:5;filter:drop-shadow(0 3px 4px rgba(0,0,0,.35));">
            <path d="M33 16.3636C33 29.0909 16.5 40 16.5 40C16.5 40 0 29.0909 0 16.3636C0 12.0237 1.73839 7.86158 4.83274 4.7928C7.92709 1.72402 12.1239 0 16.5 0C20.8761 0 25.0729 1.72402 28.1673 4.7928C31.2616 7.86158 33 12.0237 33 16.3636ZM22 16.3636C22 19.3761 19.5376 21.8182 16.5 21.8182C13.4624 21.8182 11 19.3761 11 16.3636C11 13.3512 13.4624 10.9091 16.5 10.9091C19.5376 10.9091 22 13.3512 22 16.3636Z" fill-rule="evenodd" clip-rule="evenodd" fill="<?php echo esc_attr( $pin_color ); ?>"/>
          </svg>
        </div>
      </div>

      <!-- Coluna Direita: Informações de Contacto -->
      <div class="lg:col-span-6 flex flex-col items-start justify-center lg:pl-6">
        
        <!-- Subtítulo com Traço Ciano -->
        <div class="flex items-center gap-4 mb-4">
          <div class="w-10 h-px bg-[var(--vbl-hotel-accent)] flex-shrink-0"></div>
          <span class="font-body text-[11px] xl:text-[12px] tracking-[2px] uppercase text-[var(--vbl-hotel-accent)] font-medium">
            <?php echo esc_html( $hotel_sub ); ?>
          </span>
        </div>

        <!-- Título Display Serif -->
        <h1 class="font-display text-[46px] sm:text-[58px] lg:text-[72px] xl:text-[84px] leading-[1.02] tracking-[1.5px] uppercase text-[#0d5257] mb-10 font-normal">
          <?php echo wp_kses_post( $hotel_tit ); ?>
        </h1>

        <!-- Caixas de Contacto -->
        <div class="flex flex-col gap-6 w-full max-w-[500px] font-body">
          
          <!-- Morada -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 border border-[var(--vbl-hotel-accent)] flex items-center justify-center text-[var(--vbl-hotel-accent)] flex-shrink-0 mt-0.5">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div class="flex flex-col">
              <span class="text-[10px] tracking-[1.5px] uppercase text-[var(--vbl-hotel-accent)] font-medium mb-1"><?php function_exists( 'vbl_e' ) ? vbl_e( 'MORADA' ) : _e( 'MORADA', 'vila-baleira' ); ?></span>
              <p class="font-light text-[13px] xl:text-[14px] leading-relaxed text-[#333333]">
                <?php echo nl2br( esc_html( $morada ) ); ?>
              </p>
            </div>
          </div>

          <!-- Telefone & Email em Grelha -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
            
            <!-- Telefone -->
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 border border-[var(--vbl-hotel-accent)] flex items-center justify-center text-[var(--vbl-hotel-accent)] flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
              </div>
              <div class="flex flex-col">
                <span class="text-[10px] tracking-[1.5px] uppercase text-[var(--vbl-hotel-accent)] font-medium mb-1"><?php function_exists( 'vbl_e' ) ? vbl_e( 'TELEFONE' ) : _e( 'TELEFONE', 'vila-baleira' ); ?></span>
                <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $telefone ) ); ?>" class="font-light text-[13px] xl:text-[14px] text-[#333333] hover:text-[var(--vbl-hotel-accent)] transition-colors">
                  <?php echo esc_html( $telefone ); ?>
                </a>
              </div>
            </div>

            <!-- Email -->
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 border border-[var(--vbl-hotel-accent)] flex items-center justify-center text-[var(--vbl-hotel-accent)] flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <div class="flex flex-col">
                <span class="text-[10px] tracking-[1.5px] uppercase text-[var(--vbl-hotel-accent)] font-medium mb-1"><?php function_exists( 'vbl_e' ) ? vbl_e( 'EMAIL' ) : _e( 'EMAIL', 'vila-baleira' ); ?></span>
                <a href="mailto:<?php echo esc_attr( $email ); ?>" class="font-light text-[13px] xl:text-[14px] text-[#333333] hover:text-[var(--vbl-hotel-accent)] transition-colors">
                  <?php echo esc_html( $email ); ?>
                </a>
              </div>
            </div>

          </div>

        </div>

      </div>

    </div>
  </section>

  <!-- ==========================================
       3. FORMULÁRIO DE MENSAGEM (Fundo Ciano com Concha)
  =========================================== -->
  <section class="w-full py-24 lg:py-36 bg-[var(--vbl-hotel-accent-light)] relative overflow-hidden">
    <!-- Concha decorativa em outline ciano sobreposta no canto inferior direito -->
    <div class="absolute -right-12 -bottom-16 w-80 lg:w-[480px] pointer-events-none opacity-20">
      <?php vbl_render_tinted_svg( vbl_img( 'concha-cyan.svg' ), 'block w-full', 'aspect-ratio: 166 / 160;' ); ?>
    </div>

    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-start relative z-10">
      
      <!-- Lado Esquerdo: Formulário com Linhas Sublinhadas -->
      <div class="lg:col-span-7 flex flex-col justify-center order-2 lg:order-1">
        <form id="vblContactosHotelForm" class="w-full flex flex-col gap-6">
          <input type="hidden" name="hotel" value="<?php echo esc_attr( $hotel_name ); ?>">
          <input type="hidden" name="origem" value="Microsite Hotel">
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-10">
            <!-- Nome -->
            <div>
              <input type="text" name="nome" placeholder="<?php function_exists( 'vbl_attr_e' ) ? vbl_attr_e( 'NOME' ) : _e( 'NOME', 'vila-baleira' ); ?>" required class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[var(--vbl-hotel-accent)] placeholder:text-black/60 transition-colors font-body">
            </div>

            <!-- Email -->
            <div>
              <input type="email" name="email" placeholder="<?php function_exists( 'vbl_attr_e' ) ? vbl_attr_e( 'EMAIL' ) : _e( 'EMAIL', 'vila-baleira' ); ?>" required class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[var(--vbl-hotel-accent)] placeholder:text-black/60 transition-colors font-body">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-10">
            <!-- Telefone -->
            <div>
              <input type="tel" name="telefone" placeholder="<?php function_exists( 'vbl_attr_e' ) ? vbl_attr_e( 'CONTACTO TELEFÓNICO' ) : _e( 'CONTACTO TELEFÓNICO', 'vila-baleira' ); ?>" required class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[var(--vbl-hotel-accent)] placeholder:text-black/60 transition-colors font-body">
            </div>

            <!-- Assunto -->
            <div>
              <input type="text" name="assunto" placeholder="<?php function_exists( 'vbl_attr_e' ) ? vbl_attr_e( 'ASSUNTO' ) : _e( 'ASSUNTO', 'vila-baleira' ); ?>" class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[var(--vbl-hotel-accent)] placeholder:text-black/60 transition-colors font-body">
            </div>
          </div>

          <!-- Mensagem -->
          <div>
            <textarea name="mensagem" rows="3" placeholder="<?php function_exists( 'vbl_attr_e' ) ? vbl_attr_e( 'MENSAGEM' ) : _e( 'MENSAGEM', 'vila-baleira' ); ?>" required class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[var(--vbl-hotel-accent)] placeholder:text-black/60 transition-colors font-body resize-none"></textarea>
          </div>

          <!-- Checkbox Privacidade -->
          <div class="flex items-center gap-3 pt-2">
            <input type="checkbox" name="privacy" id="contactPrivacy" required class="w-4 h-4 bg-white border border-transparent rounded-xs accent-[var(--vbl-hotel-accent)]">
            <label for="contactPrivacy" class="font-body font-light text-[12px] text-black/80">
              <?php function_exists( 'vbl_e' ) ? vbl_e( 'Li e aceito a' ) : _e( 'Li e aceito a', 'vila-baleira' ); ?> <a href="<?php echo esc_url( $privacy_url ); ?>" class="underline underline-offset-2 hover:text-[var(--vbl-hotel-accent)] transition-colors"><?php function_exists( 'vbl_e' ) ? vbl_e( 'Política de Privacidade' ) : _e( 'Política de Privacidade', 'vila-baleira' ); ?></a>.
            </label>
          </div>

          <!-- Botão Enviar Pedido -->
          <div class="pt-4">
            <button type="submit" class="vbl-btn-microsite">
              <span><?php function_exists( 'vbl_e' ) ? vbl_e( 'ENVIAR PEDIDO' ) : _e( 'ENVIAR PEDIDO', 'vila-baleira' ); ?></span>
              <svg viewBox="0 0 12 12" fill="none">
                <path d="M1 11L11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </div>

          <!-- Feedback -->
          <div id="vblContactFeedback" class="hidden font-body text-[13px] pt-2"></div>
        </form>
      </div>

      <!-- Lado Direito: Textos de Apoio -->
      <div class="lg:col-span-5 flex flex-col items-start justify-center order-1 lg:order-2">
        <!-- Subtítulo -->
        <div class="flex items-center gap-4 mb-3">
          <div class="w-10 h-px bg-[var(--vbl-hotel-accent)] flex-shrink-0"></div>
          <span class="font-body text-[10px] xl:text-[11px] tracking-[2px] uppercase text-[var(--vbl-hotel-accent)] font-medium">
            <?php echo esc_html( $form_sub ); ?>
          </span>
        </div>

        <!-- Título Display Serif -->
        <h2 class="font-display text-[44px] sm:text-[56px] lg:text-[70px] xl:text-[80px] leading-[1.02] tracking-[1.5px] uppercase text-[#0d5257] mb-8 font-normal">
          <?php echo wp_kses_post( $form_tit ); ?>
        </h2>

        <!-- Parágrafos -->
        <div class="flex flex-col gap-5 max-w-[440px] font-body font-light text-[13px] xl:text-[14px] leading-relaxed text-[#333333]">
          <?php if ( ! empty( $form_p1 ) ) : ?>
            <p><?php echo nl2br( esc_html( $form_p1 ) ); ?></p>
          <?php endif; ?>
          <?php if ( ! empty( $form_p2 ) ) : ?>
            <p><?php echo nl2br( esc_html( $form_p2 ) ); ?></p>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </section>

</main>

<?php
// Inclui o Footer independente do Hotel
get_footer('hotel');

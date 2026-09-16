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

$morada   = vbl_field( 'vbl_hct_morada', false, "Sítio do Cabeço da Ponta, Apartado 243,\n9400-909 Porto Santo" );
$telefone = vbl_field( 'vbl_hct_telefone', false, '+351 291 980 800' );
$email    = vbl_field( 'vbl_hct_email', false, 'portosanto@vilabaleira.com' );
$mapa_img = vbl_field( 'vbl_hct_mapa_img', false, vbl_img( 'contactos/mapa.webp' ) );
if ( empty( $mapa_img ) ) {
    $mapa_img = vbl_img( 'contactos/mapa.webp' );
}

// ACF Fields: Formulário
$form_sub    = vbl_field( 'vbl_hct_form_sub', false, 'LOREM IPSUM DOLOR SIT AMET CONSECTETUR' );
$form_tit    = vbl_field( 'vbl_hct_form_tit', false, 'FAUCIBUS SIT<br>DIAM ELIT' );
$form_p1     = vbl_field( 'vbl_hct_form_p1', false, 'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur.' );
$form_p2     = vbl_field( 'vbl_hct_form_p2', false, 'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur.' );
$privacy_url = vbl_field( 'vbl_privacy_url', false, home_url( '/politica-de-privacidade' ) );
?>

<main class="w-full bg-white text-black overflow-hidden">

  <!-- ==========================================
       1. HERO BANNER
  =========================================== -->
  <section class="relative w-full h-[65vh] min-h-[500px] max-h-[750px] overflow-hidden bg-black flex items-end">
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
      
      <!-- Coluna Esquerda: Mapa Ilustrado com Pin -->
      <div class="lg:col-span-6 relative flex justify-start">
        <div class="relative w-full aspect-[4/3] max-w-[620px] bg-[#93d4e8]/20 rounded-xs overflow-hidden flex items-center justify-center p-6 sm:p-10 shadow-xs">
          <!-- Imagem do Mapa -->
          <img src="<?php echo esc_url( $mapa_img ); ?>" alt="Localização no Porto Santo" class="w-full h-full object-contain">

          <!-- Pin Ciano Decorativo Indicando a Localização -->
          <div class="absolute top-[52%] left-[46%] -translate-x-1/2 -translate-y-1/2 flex flex-col items-center pointer-events-none">
            <div class="w-6 h-6 rounded-full bg-[#0da9a6] border-2 border-white shadow-md flex items-center justify-center animate-pulse">
              <div class="w-2 h-2 rounded-full bg-white"></div>
            </div>
            <span class="bg-white/95 backdrop-blur-xs text-[#0d5257] text-[9px] font-body uppercase tracking-wider px-2 py-0.5 rounded-xs shadow-xs mt-1 whitespace-nowrap font-medium">
              Vila Baleira
            </span>
          </div>
        </div>
      </div>

      <!-- Coluna Direita: Informações de Contacto -->
      <div class="lg:col-span-6 flex flex-col items-start justify-center lg:pl-6">
        
        <!-- Subtítulo com Traço Ciano -->
        <div class="flex items-center gap-4 mb-4">
          <div class="w-10 h-px bg-[#0da9a6] flex-shrink-0"></div>
          <span class="font-body text-[11px] xl:text-[12px] tracking-[2px] uppercase text-[#0da9a6] font-medium">
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
            <div class="w-10 h-10 border border-[#0da9a6] flex items-center justify-center text-[#0da9a6] flex-shrink-0 mt-0.5">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div class="flex flex-col">
              <span class="text-[10px] tracking-[1.5px] uppercase text-[#0da9a6] font-medium mb-1">MORADA</span>
              <p class="font-light text-[13px] xl:text-[14px] leading-relaxed text-[#333333]">
                <?php echo nl2br( esc_html( $morada ) ); ?>
              </p>
            </div>
          </div>

          <!-- Telefone & Email em Grelha -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
            
            <!-- Telefone -->
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 border border-[#0da9a6] flex items-center justify-center text-[#0da9a6] flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
              </div>
              <div class="flex flex-col">
                <span class="text-[10px] tracking-[1.5px] uppercase text-[#0da9a6] font-medium mb-1">TELEFONE</span>
                <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $telefone ) ); ?>" class="font-light text-[13px] xl:text-[14px] text-[#333333] hover:text-[#0da9a6] transition-colors">
                  <?php echo esc_html( $telefone ); ?>
                </a>
              </div>
            </div>

            <!-- Email -->
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 border border-[#0da9a6] flex items-center justify-center text-[#0da9a6] flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <div class="flex flex-col">
                <span class="text-[10px] tracking-[1.5px] uppercase text-[#0da9a6] font-medium mb-1">EMAIL</span>
                <a href="mailto:<?php echo esc_attr( $email ); ?>" class="font-light text-[13px] xl:text-[14px] text-[#333333] hover:text-[#0da9a6] transition-colors">
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
  <section class="w-full py-24 lg:py-36 bg-[#E8F5F5] relative overflow-hidden">
    <!-- Concha decorativa em outline ciano sobreposta no canto inferior direito -->
    <div class="absolute -right-12 -bottom-16 w-80 lg:w-[480px] pointer-events-none opacity-20">
      <img src="<?php echo vbl_img( 'concha-cyan.svg' ); ?>" alt="" class="w-full h-auto">
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
              <input type="text" name="nome" placeholder="NOME" required class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[#0da9a6] placeholder:text-black/60 transition-colors font-body">
            </div>

            <!-- Email -->
            <div>
              <input type="email" name="email" placeholder="EMAIL" required class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[#0da9a6] placeholder:text-black/60 transition-colors font-body">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-10">
            <!-- Telefone -->
            <div>
              <input type="tel" name="telefone" placeholder="CONTACTO TELEFÓNICO" required class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[#0da9a6] placeholder:text-black/60 transition-colors font-body">
            </div>

            <!-- Assunto -->
            <div>
              <input type="text" name="assunto" placeholder="ASSUNTO" class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[#0da9a6] placeholder:text-black/60 transition-colors font-body">
            </div>
          </div>

          <!-- Mensagem -->
          <div>
            <textarea name="mensagem" rows="3" placeholder="MENSAGEM" required class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[#0da9a6] placeholder:text-black/60 transition-colors font-body resize-none"></textarea>
          </div>

          <!-- Checkbox Privacidade -->
          <div class="flex items-center gap-3 pt-2">
            <input type="checkbox" name="privacy" id="contactPrivacy" required class="w-4 h-4 bg-white border border-transparent rounded-xs accent-[#0da9a6]">
            <label for="contactPrivacy" class="font-body font-light text-[12px] text-black/80">
              Li e aceito a <a href="<?php echo esc_url( $privacy_url ); ?>" class="underline underline-offset-2 hover:text-[#0da9a6] transition-colors">Política de Privacidade</a>.
            </label>
          </div>

          <!-- Botão Enviar Pedido -->
          <div class="pt-4">
            <button type="submit" class="vbl-btn-microsite">
              <span>ENVIAR PEDIDO</span>
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
          <div class="w-10 h-px bg-[#0da9a6] flex-shrink-0"></div>
          <span class="font-body text-[10px] xl:text-[11px] tracking-[2px] uppercase text-[#0da9a6] font-medium">
            <?php echo esc_html( $form_sub ); ?>
          </span>
        </div>

        <!-- Título Display Serif -->
        <h2 class="font-display text-[44px] sm:text-[56px] lg:text-[70px] xl:text-[80px] leading-[1.02] tracking-[1.5px] uppercase text-[#0d5257] mb-8 font-normal">
          <?php echo wp_kses_post( $form_tit ); ?>
        </h2>

        <!-- Parágrafos -->
        <div class="flex flex-col gap-5 max-w-[440px] font-body font-light text-[13px] xl:text-[14px] leading-relaxed text-[#333333]">
          <p><?php echo nl2br( esc_html( $form_p1 ) ); ?></p>
          <?php if ( ! empty( $form_p2 ) ) : ?>
            <p><?php echo nl2br( esc_html( $form_p2 ) ); ?></p>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('vblContactosHotelForm');
  const feedback = document.getElementById('vblContactFeedback');

  if (form && feedback) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      feedback.classList.remove('hidden', 'text-green-600', 'text-red-600');
      feedback.textContent = 'A enviar mensagem...';

      setTimeout(function() {
        feedback.classList.add('text-green-600');
        feedback.textContent = 'Obrigado! A sua mensagem foi enviada com sucesso. Responderemos com brevidade.';
        form.reset();
      }, 1000);
    });
  }
});
</script>

<?php
// Inclui o Footer independente do Hotel
get_footer('hotel');

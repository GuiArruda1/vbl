<?php
/**
 * Template Name: Hotel — Eventos & Salas
 * Template para a página de Eventos, Salas de Reunião e Conferências do Microsite de Hotel.
 *
 * @package Vila_Baleira
 */

// Inclui o Header Transparente com branding do Hotel
require_once VBL_DIR . '/header-hotel.php';

// ACF Fields: Hero Banner
$hero_bg = vbl_field( 'vbl_hevent_hero_bg', false, vbl_img( 'hoteis/suites-680x400.jpg' ) );
if ( empty( $hero_bg ) ) {
    $hero_bg = vbl_img( 'hoteis/suites-680x400.jpg' );
}

// ACF Fields: Apresentação (Diam Elit Ut Massa Eget)
$apres_subtitle = vbl_field( 'vbl_hevent_apres_subtitle', false, 'EVENTOS & SALAS' );
$apres_title    = vbl_field( 'vbl_hevent_apres_title', false, 'DIAM ELIT UT<br>MASSA EGET' );
$apres_p1       = vbl_field( 'vbl_hevent_apres_p1', false, 'Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in. Elementum mauris dolor vitae at porttitor.' );
$apres_p2       = vbl_field( 'vbl_hevent_apres_p2', false, 'Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras. Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor.' );
$apres_img      = vbl_field( 'vbl_hevent_apres_img', false, vbl_img( 'noticias/noticias-3.jpg' ) );
if ( empty( $apres_img ) ) {
    $apres_img = vbl_img( 'noticias/noticias-3.jpg' );
}

// ACF Fields: Banner Frase
$frase_bg    = vbl_field( 'vbl_hevent_frase_bg', false, vbl_img( 'hoteis/frase-ilhas-bg.jpg' ) );
if ( empty( $frase_bg ) ) {
    $frase_bg = vbl_img( 'hoteis/frase-ilhas-bg.jpg' );
}
$frase_line1 = vbl_field( 'vbl_hevent_frase_line1', false, 'LOREM IPSUM DOLOR' );
$frase_line2 = vbl_field( 'vbl_hevent_frase_line2', false, 'ENIM VITAE TURPIS' );
$frase_line3 = vbl_field( 'vbl_hevent_frase_line3', false, 'LACUS EGET UT SIT.' );

// ACF Fields: Planta & Equipamentos
$planta_subtitle = vbl_field( 'vbl_hevent_planta_subtitle', false, 'PLANTA & EQUIPAMENTOS' );
$planta_title    = vbl_field( 'vbl_hevent_planta_title', false, 'SIT LACUS EGET<br>LOREM IPSUM' );
$planta_desc     = vbl_field( 'vbl_hevent_planta_desc', false, 'Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in. Elementum mauris dolor vitae at porttitor.' );
$planta_url      = vbl_field( 'vbl_hevent_planta_url', false, '#' );
$planta_btn      = vbl_field( 'vbl_hevent_planta_btn', false, 'CONSULTAR PLANTA' );

$equipamentos_list = vbl_field( 'vbl_hevent_equip_list', false, array() );
if ( empty( $equipamentos_list ) || ! is_array( $equipamentos_list ) ) {
    $equipamentos_list = array(
        array(
            'title'       => 'CONSECTETUR AC VENENATIS EU EGESTAS',
            'description' => 'Sistemas integrados de projeção de alta definição e ecrãs motorizados de grandes dimensões.',
        ),
        array(
            'title'       => 'FERMENTUM TURPIS MALESUADA NEC',
            'description' => 'Soluções completas de sonorização profissional, microfones sem fios e controlo acústico.',
        ),
        array(
            'title'       => 'TINCIDUNT ADIPISCING AC PRAESENT',
            'description' => 'Ambientes climatizados individualmente, iluminação regulável e acessos diretos para cargas.',
        ),
        array(
            'title'       => 'PULVINAR SAGITTIS MALESUADA VELIT',
            'description' => 'Serviço personalizado de catering, coffee-breaks exclusivos e banquetes com gastronomia regional.',
        ),
    );
}

// ACF Fields: Pedido de Orçamento
$orc_subtitle = vbl_field( 'vbl_hevent_orc_subtitle', false, 'SOLICITAÇÃO DE DISPONIBILIDADE / CASAMENTOS' );
$orc_title    = vbl_field( 'vbl_hevent_orc_title', false, 'PEDIDO DE<br>ORÇAMENTO' );
$orc_p1       = vbl_field( 'vbl_hevent_orc_p1', false, 'Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor.' );
$orc_p2       = vbl_field( 'vbl_hevent_orc_p2', false, 'Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor.' );
$privacy_url  = vbl_field( 'vbl_privacy_url', false, home_url( '/politica-de-privacidade' ) );
?>

<main class="w-full bg-white text-black overflow-hidden">

  <!-- ==========================================
       1. HERO BANNER
  =========================================== -->
  <section class="relative w-full h-[280px] sm:h-[360px] max-h-[360px] overflow-hidden bg-black flex items-end vbl-hotel-subpage-hero">
    <div class="absolute inset-0 z-0">
      <img src="<?php echo esc_url( $hero_bg ); ?>" alt="Eventos & Salas" class="w-full h-full object-cover object-center filter brightness-[0.85]">
      <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/50"></div>
    </div>
  </section>

  <!-- ==========================================
       2. APRESENTAÇÃO DE EVENTOS & SALAS
  =========================================== -->
  <section class="w-full py-20 lg:py-32 bg-white">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-center">
      
      <!-- Coluna Esquerda: Textos -->
      <div class="lg:col-span-7 flex flex-col items-start justify-center lg:pr-8 order-2 lg:order-1">
        
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

      <!-- Coluna Direita: Fotografia Vertical (Atendimento / Sala) -->
      <div class="lg:col-span-5 relative flex justify-center lg:justify-end order-1 lg:order-2">
        <div class="relative w-full max-w-[480px] aspect-[4/5] shadow-sm">
          <img src="<?php echo esc_url( $apres_img ); ?>" alt="<?php echo esc_attr( strip_tags( $apres_title ) ); ?>" class="w-full h-full object-cover">
        </div>
      </div>

    </div>
  </section>

  <!-- ==========================================
       3. BANNER DE FRASE DECORATIVO
  =========================================== -->
  <section class="relative w-full py-32 lg:py-48 flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 w-full h-full">
      <img src="<?php echo esc_url( $frase_bg ); ?>" alt="Ambiente de Eventos" class="w-full h-full object-cover filter brightness-[0.70]">
      <div class="absolute inset-0 bg-black/20"></div>
    </div>
    
    <div class="relative z-10 max-w-[1920px] mx-auto px-6 xl:px-[8.33%] text-center">
      <h2 class="font-display text-[40px] sm:text-[54px] md:text-[68px] lg:text-[80px] text-white uppercase leading-[1.1] drop-shadow-md font-normal">
        <span class="block"><?php echo esc_html( $frase_line1 ); ?></span>
        <span class="block italic"><?php echo esc_html( $frase_line2 ); ?></span>
        <span class="block"><?php echo esc_html( $frase_line3 ); ?></span>
      </h2>
    </div>
  </section>

  <!-- ==========================================
       4. PLANTA & EQUIPAMENTOS
  =========================================== -->
  <section class="w-full py-24 lg:py-36 bg-white">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-start">
      
      <!-- Lado Esquerdo: Textos + Botão Consultar Planta -->
      <div class="lg:col-span-6 flex flex-col items-start justify-center">
        
        <!-- Subtítulo com Traço Ciano -->
        <div class="flex items-center gap-4 mb-4">
          <div class="w-10 h-px bg-[#0da9a6] flex-shrink-0"></div>
          <span class="font-body text-[11px] xl:text-[12px] tracking-[2px] uppercase text-[#0da9a6] font-medium">
            <?php echo esc_html( $planta_subtitle ); ?>
          </span>
        </div>

        <!-- Título Display Serif -->
        <h2 class="font-display text-[42px] sm:text-[54px] lg:text-[68px] leading-[1.02] tracking-[1.5px] uppercase text-[#0d5257] mb-8 font-normal">
          <?php echo wp_kses_post( $planta_title ); ?>
        </h2>

        <!-- Descrição -->
        <p class="font-body font-light text-[14px] lg:text-[15px] xl:text-[16px] leading-relaxed text-[#333333] mb-10 max-w-[500px]">
          <?php echo nl2br( esc_html( $planta_desc ) ); ?>
        </p>

        <!-- Botão Consultar Planta -->
        <?php if ( ! empty( $planta_url ) ) : ?>
        <a href="<?php echo esc_url( $planta_url ); ?>" target="_blank" rel="noopener" class="vbl-btn-microsite">
          <span><?php echo esc_html( $planta_btn ); ?></span>
          <svg viewBox="0 0 12 12" fill="none">
            <path d="M1 11L11 1M11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
        <?php endif; ?>

      </div>

      <!-- Lado Direito: Lista de 4 Equipamentos / Serviços com Ícone -->
      <div class="lg:col-span-6 flex flex-col gap-8 pt-4 lg:pt-8">
        <?php foreach ( $equipamentos_list as $eq_idx => $eq ) : 
            $eq_title = ! empty( $eq['title'] ) ? $eq['title'] : '';
            $eq_desc  = ! empty( $eq['description'] ) ? $eq['description'] : '';
            if ( empty( $eq_title ) ) continue;
        ?>
        <div class="flex items-start gap-5">
          <!-- Ícone Geométrico Elegante Ciano -->
          <div class="w-7 h-7 rounded-full border border-[#0da9a6] flex items-center justify-center flex-shrink-0 mt-1 text-[#0da9a6]">
            <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="currentColor">
              <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
            </svg>
          </div>

          <div class="flex flex-col gap-1.5">
            <h3 class="font-body font-medium text-[13px] xl:text-[14px] uppercase tracking-[1.5px] text-[#0d5257]">
              <?php echo esc_html( $eq_title ); ?>
            </h3>
            <?php if ( ! empty( $eq_desc ) ) : ?>
            <p class="font-body font-light text-[13px] leading-relaxed text-[#333333]/80 max-w-[480px]">
              <?php echo esc_html( $eq_desc ); ?>
            </p>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </section>

  <!-- ==========================================
       5. PEDIDO DE ORÇAMENTO (Bloco Ciano Suave)
  =========================================== -->
  <section class="w-full py-24 lg:py-36 bg-[#E8F5F5] relative overflow-hidden">
    <!-- Estrela do mar decorativa em outline ciano -->
    <div class="absolute right-0 bottom-0 w-72 lg:w-[420px] pointer-events-none opacity-20 text-[#0da9a6]">
      <img src="<?php echo vbl_img( 'estrela.svg' ); ?>" alt="" class="w-full h-auto">
    </div>

    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-start relative z-10">
      
      <!-- Lado Esquerdo: Textos -->
      <div class="lg:col-span-5 flex flex-col items-start justify-center">
        <!-- Subtítulo -->
        <div class="flex items-center gap-4 mb-3">
          <div class="w-10 h-px bg-[#0da9a6] flex-shrink-0"></div>
          <span class="font-body text-[10px] xl:text-[11px] tracking-[2px] uppercase text-[#0da9a6] font-medium">
            <?php echo esc_html( $orc_subtitle ); ?>
          </span>
        </div>

        <!-- Título Display Serif -->
        <h2 class="font-display text-[44px] sm:text-[56px] lg:text-[70px] xl:text-[80px] leading-[1.02] tracking-[1.5px] uppercase text-[#0d5257] mb-8 font-normal">
          <?php echo wp_kses_post( $orc_title ); ?>
        </h2>

        <!-- Parágrafos -->
        <div class="flex flex-col gap-5 max-w-[440px] font-body font-light text-[13px] xl:text-[14px] leading-relaxed text-[#333333]">
          <p><?php echo nl2br( esc_html( $orc_p1 ) ); ?></p>
          <?php if ( ! empty( $orc_p2 ) ) : ?>
            <p><?php echo nl2br( esc_html( $orc_p2 ) ); ?></p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Lado Direito: Formulário de Eventos -->
      <div class="lg:col-span-7 flex flex-col justify-center">
        <form id="vblEventosForm" class="w-full flex flex-col gap-6">
          <input type="hidden" name="hotel" value="<?php echo esc_attr( $hotel_name ); ?>">
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Nome -->
            <div>
              <input type="text" name="nome" placeholder="NOME" required class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[#0da9a6] placeholder:text-black/60 transition-colors font-body">
            </div>

            <!-- Email -->
            <div>
              <input type="email" name="email" placeholder="EMAIL" required class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[#0da9a6] placeholder:text-black/60 transition-colors font-body">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Telefone -->
            <div>
              <input type="tel" name="telefone" placeholder="CONTACTO TELEFÓNICO" required class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[#0da9a6] placeholder:text-black/60 transition-colors font-body">
            </div>

            <!-- Empresa / Tipo de Evento -->
            <div>
              <input type="text" name="empresa" placeholder="EMPRESA / TIPO DE EVENTO" class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[#0da9a6] placeholder:text-black/60 transition-colors font-body">
            </div>
          </div>

          <!-- Mensagem -->
          <div>
            <textarea name="mensagem" rows="3" placeholder="MENSAGEM / DETALHES DO EVENTO" required class="w-full px-1 py-3 border-b border-black/30 bg-transparent text-[13px] tracking-[1.5px] uppercase outline-none focus:border-[#0da9a6] placeholder:text-black/60 transition-colors font-body resize-none"></textarea>
          </div>

          <!-- Checkbox Privacidade -->
          <div class="flex items-center gap-3 pt-2">
            <input type="checkbox" name="privacy" id="eventPrivacy" required class="w-4 h-4 bg-white border border-transparent rounded-xs accent-[#0da9a6]">
            <label for="eventPrivacy" class="font-body font-light text-[12px] text-black/80">
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

          <!-- Mensagem Feedback -->
          <div id="vblEventosFeedback" class="hidden font-body text-[13px] pt-2"></div>
        </form>
      </div>

    </div>
  </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('vblEventosForm');
  const feedback = document.getElementById('vblEventosFeedback');

  if (form && feedback) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      feedback.classList.remove('hidden', 'text-green-600', 'text-red-600');
      feedback.textContent = 'A enviar pedido...';

      // Simulação ou chamada AJAX nativa
      setTimeout(function() {
        feedback.classList.add('text-green-600');
        feedback.textContent = 'Obrigado! O seu pedido de orçamento foi enviado com sucesso. Entraremos em contacto brevemente.';
        form.reset();
      }, 1000);
    });
  }
});
</script>

<?php
// Inclui o Footer independente do Hotel
get_footer('hotel');

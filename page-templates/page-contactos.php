<?php
/* Template Name: Vila Baleira — Contactos */
get_header();
?>

<!-- Spacer for fixed header -->
<div class="h-20"></div>

<?php
// ── HERO ──
$hero_subtitle = vbl_field('vbl_contactos_hero_subtitle', false, 'Lorem ipsum dolor sit amet consectetur');
$hero_title = vbl_field('vbl_contactos_hero_title', false, 'Entrar em<br>Contacto');
$hero_img = vbl_field('vbl_contactos_hero_img', false, vbl_img('contactos/geral-contacto.webp'));
if (empty($hero_img))
  $hero_img = vbl_img('contactos/geral-contacto.webp');

$morada_geral = vbl_field('vbl_contactos_morada', false, 'Sítio do Cabeço da Ponta, Apartado 243, 9401-909 Porto Santo');
$telefone_geral = vbl_field('vbl_contactos_telefone', false, '+351 291 980 800');
$email_geral = vbl_field('vbl_contactos_email', false, 'sales@vilabaleira.com');

$icon_morada = vbl_img('contactos/icon-morada.svg');
$icon_telefone = vbl_img('contactos/icon-telefone.svg');
$icon_email = vbl_img('contactos/icon-email.svg');
?>

<!-- ====== HERO CONTACTOS (Responsive) ====== -->
<section class="w-full">
  <div class="vbl-container py-12 lg:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
      <!-- Imagem esquerda -->
      <div class="lg:col-span-6 overflow-hidden rounded-sm">
        <img src="<?php echo esc_url($hero_img); ?>" alt="Vila Baleira Hotel"
          class="w-full aspect-[785/600] object-cover">
      </div>
      <!-- Info direita -->
      <div class="lg:col-span-6 flex flex-col justify-center">
        <div class="vbl-subtitle mb-4">
          <span><?php echo esc_html($hero_subtitle); ?></span>
        </div>
        <h1
          class="font-display text-[36px] md:text-[56px] lg:text-[72px] xl:text-[88px] leading-none text-verde uppercase mb-8">
          <?php echo wp_kses_post($hero_title); ?>
        </h1>
        <!-- Contactos lista -->
        <div class="font-body flex flex-col gap-6 text-preto">
          <div class="flex gap-4 items-start">
            <img src="<?php echo esc_url($icon_morada); ?>" alt="" aria-hidden="true" class="w-12 h-12 flex-shrink-0">
            <div>
              <p class="text-[12px] tracking-[1.2px] uppercase text-verde mb-1 font-medium">Morada</p>
              <p class="font-light text-[14px] lg:text-[16px] leading-relaxed"><?php echo esc_html($morada_geral); ?>
              </p>
            </div>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="flex gap-4 items-start">
              <img src="<?php echo esc_url($icon_telefone); ?>" alt="" aria-hidden="true" class="w-12 h-12 flex-shrink-0">
              <div>
                <p class="text-[12px] tracking-[1.2px] uppercase text-verde mb-1 font-medium">Telefone</p>
                <p class="font-light text-[14px] lg:text-[16px] leading-relaxed">
                  <?php echo esc_html($telefone_geral); ?></p>
              </div>
            </div>
            <div class="flex gap-4 items-start">
              <img src="<?php echo esc_url($icon_email); ?>" alt="" aria-hidden="true" class="w-12 h-12 flex-shrink-0">
              <div>
                <p class="text-[12px] tracking-[1.2px] uppercase text-verde mb-1 font-medium">Email</p>
                <p class="font-light text-[14px] lg:text-[16px] leading-relaxed"><?php echo esc_html($email_geral); ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
// ── FORMULÁRIO ──
$form_subtitle = vbl_field('vbl_contactos_form_subtitle', false, 'Lorem ipsum dolor sit amet consectetur');
$form_title = vbl_field('vbl_contactos_form_title', false, 'Faucibus sit<br>Diam elit');
$form_desc = vbl_field('vbl_contactos_form_desc', false, 'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur.');
$arvore_form = vbl_img('contactos/arvore-form.svg');
?>

<!-- ====== FORMULÁRIO DE CONTACTO (Responsive & Accessible) ====== -->
<section class="w-full bg-bege relative overflow-hidden py-16 lg:py-24">
  <!-- Árvore decorativa -->
  <img src="<?php echo esc_url($arvore_form); ?>" alt="" aria-hidden="true"
    class="absolute right-[-10%] top-1/2 -translate-y-1/2 h-[120%] w-auto opacity-20 pointer-events-none">

  <div class="vbl-container relative z-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">

      <!-- Coluna Esquerda: Título e Descrição -->
      <div class="lg:col-span-5 flex flex-col justify-start">
        <div class="vbl-subtitle mb-4">
          <span><?php echo esc_html($form_subtitle); ?></span>
        </div>
        <h2 class="font-display text-[32px] md:text-[48px] lg:text-[64px] leading-tight text-verde uppercase mb-6">
          <?php echo wp_kses_post($form_title); ?>
        </h2>
        <p class="font-body font-light text-[14px] lg:text-[16px] leading-relaxed text-preto/80 max-w-[480px]">
          <?php echo esc_html($form_desc); ?>
        </p>
      </div>

      <!-- Coluna Direita: Formulário Semântico -->
      <div class="lg:col-span-7 font-body">
        <form id="vblContactForm" class="flex flex-col gap-6">
          <?php wp_nonce_field('vbl_contact_form_nonce', 'vbl_contact_nonce'); ?>
          <input type="hidden" name="origem" value="Página de Contactos Geral">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label for="ct_nome" class="sr-only">Nome</label>
              <input type="text" id="ct_nome" name="nome" placeholder="NOME" required class="vbl-form-input">
            </div>
            <div>
              <label for="ct_email" class="sr-only">Email</label>
              <input type="email" id="ct_email" name="email" placeholder="EMAIL" required class="vbl-form-input">
            </div>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label for="ct_telefone" class="sr-only">Contacto Telefónico</label>
              <input type="tel" id="ct_telefone" name="telefone" placeholder="CONTACTO TELEFÓNICO"
                class="vbl-form-input">
            </div>
            <div>
              <label for="ct_assunto" class="sr-only">Assunto</label>
              <input type="text" id="ct_assunto" name="assunto" placeholder="ASSUNTO" required class="vbl-form-input">
            </div>
          </div>
          <div>
            <label for="ct_mensagem" class="sr-only">Mensagem</label>
            <textarea id="ct_mensagem" name="mensagem" placeholder="MENSAGEM" rows="4" required
              class="vbl-form-input resize-none"></textarea>
          </div>
          <div class="flex items-center gap-3 mt-2">
            <input type="checkbox" id="ct_privacy" name="privacy" required
              class="w-4 h-4 rounded accent-dourado cursor-pointer">
            <label for="ct_privacy" class="font-light text-[13px] text-preto/80 cursor-pointer">
              Li e aceito a <u class="underline-offset-2">Política de Privacidade.</u>
            </label>
          </div>
          <div class="flex flex-col gap-3">
            <button type="submit" class="vbl-btn-primary self-start mt-4 cursor-pointer">
              <span>Enviar Pedido</span>
              <svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45" viewBox="0 0 11 11"
                fill="none" aria-hidden="true">
                <path
                  d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z"
                  fill="currentColor" />
              </svg>
            </button>
            <div id="vblContactFeedback" class="hidden text-[14px] font-medium py-2"></div>
          </div>
        </form>
      </div>

    </div>
  </div>
</section>

<?php
$acf_hoteis = function_exists('get_field') ? get_field('vbl_contactos_hoteis_list') : false;
if (empty($acf_hoteis) || !is_array($acf_hoteis)) {
  $custom_hoteis = get_post_meta(get_the_ID(), '_vbl_contactos_hoteis', true);
  if (!empty($custom_hoteis) && is_array($custom_hoteis)) {
    $acf_hoteis = $custom_hoteis;
  }
}

if (!empty($acf_hoteis) && is_array($acf_hoteis)) {
  $hoteis_carousel = array();
  foreach ($acf_hoteis as $h) {
    $hoteis_carousel[] = array(
      'title' => !empty($h['title']) ? $h['title'] : 'PORTO SANTO',
      'subtitle' => !empty($h['subtitle']) ? $h['subtitle'] : 'VILA BALEIRA',
      'morada' => !empty($h['morada']) ? $h['morada'] : '',
      'telefone' => !empty($h['telefone']) ? $h['telefone'] : '',
      'email' => !empty($h['email']) ? $h['email'] : '',
      'foto' => !empty($h['foto']) ? $h['foto'] : vbl_img('hoteis/porto-santo-520x400.jpg'),
      'mapa' => !empty($h['mapa']) ? $h['mapa'] : vbl_img('contactos/mapa.jpg'),
    );
  }
} else {
  $hoteis_carousel = array(
    array(
      'title' => 'PORTO SANTO',
      'subtitle' => 'VILA BALEIRA',
      'morada' => 'Sítio do Cabeço da Ponta, Apartado 243, 9401-909 Porto Santo',
      'telefone' => '+351 291 980 800',
      'email' => 'sales@vilabaleira.com',
      'foto' => vbl_img('hoteis/porto-santo-520x400.jpg'),
      'mapa' => 'https://maps.google.com/maps?q=Vila+Baleira+Porto+Santo+Resort&t=&z=14&ie=UTF8&iwloc=&output=embed',
    ),
    array(
      'title' => 'FUNCHAL',
      'subtitle' => 'VILA BALEIRA',
      'morada' => 'Estrada Monumental 274, São Martinho, 9000-100 Funchal',
      'telefone' => '+351 291 000 274',
      'email' => 'funchal@vilabaleira.com',
      'foto' => vbl_img('contactos/funchal-hotel.jpg'),
      'mapa' => 'https://maps.google.com/maps?q=Vila+Baleira+Funchal&t=&z=15&ie=UTF8&iwloc=&output=embed',
    ),
    array(
      'title' => 'SUITES',
      'subtitle' => 'VILA BALEIRA',
      'morada' => 'Sítio do Cabeço da Ponta, 9401-909 Porto Santo',
      'telefone' => '+351 291 980 800',
      'email' => 'suites@vilabaleira.com',
      'foto' => vbl_img('hoteis/suites-680x400.jpg'),
      'mapa' => 'https://maps.google.com/maps?q=Vila+Baleira+Suites+Porto+Santo&t=&z=15&ie=UTF8&iwloc=&output=embed',
    ),
    array(
      'title' => 'VILLAGE',
      'subtitle' => 'VILA BALEIRA',
      'morada' => 'Sítio do Cabeço da Ponta, 9401-909 Porto Santo',
      'telefone' => '+351 291 980 800',
      'email' => 'village@vilabaleira.com',
      'foto' => vbl_img('hoteis/village-680x400.jpg'),
      'mapa' => 'https://maps.google.com/maps?q=Vila+Baleira+Village+Porto+Santo&t=&z=15&ie=UTF8&iwloc=&output=embed',
    ),
    array(
      'title' => 'RESIDENCE',
      'subtitle' => 'VILA BALEIRA',
      'morada' => 'Rua D. Francisco de Almeida, nº 11, 9000-754 Funchal',
      'telefone' => '+351 291 708 700',
      'email' => 'res.funchal@vilabaleira.com',
      'foto' => vbl_img('hoteis/residence-680x400.jpg'),
      'mapa' => 'https://maps.google.com/maps?q=Vila+Baleira+Residence+Funchal&t=&z=15&ie=UTF8&iwloc=&output=embed',
    ),
  );
}

$first_hotel = $hoteis_carousel[0];
?>

<!-- ====== CARROSSEL DE HOTÉIS E MAPA ====== -->
<section class="w-full py-16 lg:py-24 bg-white" id="hotelContactSection">
  <div class="vbl-container">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

      <!-- Coluna Esquerda: Informações e Carrosel -->
      <div class="lg:col-span-6 flex flex-col justify-center">
        <!-- Subtítulo -->
        <div class="vbl-subtitle mb-2">
          <span id="vblHotelSubtitle"><?php echo esc_html($first_hotel['subtitle']); ?></span>
        </div>

        <!-- Título do Hotel -->
        <h2 id="vblHotelTitle"
          class="font-display text-[44px] sm:text-[64px] lg:text-[80px] leading-tight text-verde uppercase mb-8 transition-opacity duration-300">
          <?php echo esc_html($first_hotel['title']); ?>
        </h2>

        <!-- Blocos de Contacto -->
        <div id="vblHotelInfoBlock"
          class="font-body flex flex-col gap-6 text-preto mb-10 transition-opacity duration-300">
          <!-- Morada -->
          <div class="flex items-start gap-4">
            <img src="<?php echo esc_url($icon_morada); ?>" alt="" aria-hidden="true" class="w-12 h-12 flex-shrink-0">
            <div>
              <p class="text-[11px] tracking-[1.1px] uppercase text-verde/70 font-semibold mb-1">MORADA</p>
              <p id="vblHotelMorada" class="font-light text-[14px] lg:text-[15px] leading-relaxed text-preto/90">
                <?php echo esc_html($first_hotel['morada']); ?>
              </p>
            </div>
          </div>

          <!-- Telefone e Email -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="flex items-start gap-4">
              <img src="<?php echo esc_url($icon_telefone); ?>" alt="" aria-hidden="true" class="w-12 h-12 flex-shrink-0">
              <div>
                <p class="text-[11px] tracking-[1.1px] uppercase text-verde/70 font-semibold mb-1">TELEFONE</p>
                <p id="vblHotelTelefone" class="font-light text-[14px] lg:text-[15px] text-preto/90">
                  <?php echo esc_html($first_hotel['telefone']); ?>
                </p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <img src="<?php echo esc_url($icon_email); ?>" alt="" aria-hidden="true" class="w-12 h-12 flex-shrink-0">
              <div>
                <p class="text-[11px] tracking-[1.1px] uppercase text-verde/70 font-semibold mb-1">EMAIL</p>
                <p id="vblHotelEmail" class="font-light text-[14px] lg:text-[15px] text-preto/90 truncate">
                  <?php echo esc_html($first_hotel['email']); ?>
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Carrossel da Foto + Setas -->
        <div class="relative flex items-center gap-4">
          <!-- Seta Esquerda -->
          <button type="button" id="vblHotelPrevBtn" aria-label="Hotel anterior"
            class="w-12 h-12 border border-dourado text-dourado hover:bg-dourado hover:text-white transition-colors flex items-center justify-center flex-shrink-0 cursor-pointer">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M28 15H1M1 15L14 2M1 15L14 28" />
            </svg>
          </button>

          <!-- Contentor da Foto -->
          <div class="flex-1 overflow-hidden h-[220px] sm:h-[260px] lg:h-[300px] rounded-sm relative">
            <img id="vblHotelFoto" src="<?php echo esc_url($first_hotel['foto']); ?>" alt="Hotel"
              class="w-full h-full object-cover transition-opacity duration-300">
          </div>

          <!-- Seta Direita -->
          <button type="button" id="vblHotelNextBtn" aria-label="Próximo hotel"
            class="w-12 h-12 border border-dourado text-dourado hover:bg-dourado hover:text-white transition-colors flex items-center justify-center flex-shrink-0 cursor-pointer">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 15H28M28 15L15 2M28 15L15 28" />
            </svg>
          </button>
        </div>

      </div>

      <!-- Coluna Direita: Mapa Interativo (Google Maps) -->
      <div
        class="lg:col-span-6 h-full min-h-[350px] sm:min-h-[450px] lg:min-h-[600px] overflow-hidden rounded-sm relative bg-bege flex items-center justify-center">
        <iframe id="vblHotelMapa" src="<?php echo esc_url($first_hotel['mapa']); ?>" width="100%" height="100%"
          class="w-full h-full border-0 min-h-[350px] sm:min-h-[450px] lg:min-h-[600px] transition-opacity duration-300"
          allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
          title="Mapa de Localização do Hotel"></iframe>
      </div>

    </div>
  </div>
</section>

<!-- Payload JSON de Hotéis -->
<script type="application/json" id="vbl-hotels-contact-data">
<?php echo json_encode($hoteis_carousel); ?>
</script>

<?php get_footer(); ?>
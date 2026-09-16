<?php
/**
 * Template Part: Newsletter Section
 * Layout: Condicional para Main Site (bege/gold/esquerda) e Microsite (teal/direita)
 * Usado em todas as páginas VBL EXCEPTO a homepage principal.
 *
 * @package Vila_Baleira
 */

// Descobre se estamos num microsite (templates de hotel)
$is_microsite = is_page_template( array(
    'page-templates/page-hotel-home.php',
    'page-templates/page-hotel-sobre.php',
    'page-templates/page-hotel-atividades.php',
    'page-templates/page-hotel-restaurantes.php',
    'page-templates/page-hotel-regiao.php',
    'page-templates/page-hotel-internal.php',
    'page-templates/page-hotel-rooms.php',
    'page-templates/page-hotel-contactos.php',
    'page-templates/page-hotel-eventos.php'
) ) || is_singular( 'vbl_quarto' );

// Tailwind Classes completas para o compilador não quebrar
$bg_section       = $is_microsite ? 'bg-[#E8F5F5]' : 'bg-[#EEE8E5]';
$line_class       = $is_microsite ? 'bg-[#0da9a6]' : 'bg-[#bc945b]';
$text_class       = $is_microsite ? 'text-[#0da9a6]' : 'text-[#bc945b]';
$focus_class      = $is_microsite ? 'focus:border-[#0da9a6]' : 'focus:border-[#bc945b]';
$accent_checkbox  = $is_microsite ? 'accent-[#0da9a6]' : 'accent-[#bc945b]';
$hover_link_class = $is_microsite ? 'hover:text-[#0da9a6]' : 'hover:text-[#bc945b]';
$btn_border_class = $is_microsite ? 'border-[#0da9a6]/60 hover:border-[#0da9a6]' : 'border-[#bc945b]/60 hover:border-[#bc945b]';

$arvore_svg = $is_microsite ? vbl_img('hoteis/arvore-newsletter-porto-santo.svg') : vbl_img('hoteis/arvore-newsletter.svg');

// Posicionamento e opacidade da árvore
$tree_mobile_class   = $is_microsite ? '-right-[20%]' : '-left-[20%]';
$tree_desktop_class  = $is_microsite ? 'right-0 xl:right-[2%]' : 'left-0 xl:left-[1%]';
$tree_mobile_opacity = $is_microsite ? '0.20' : '0.45';

// ACF Fields globais
$nl_subtitle    = vbl_field('vbl_newsletter_subtitle', false, 'Subscreva a nossa');
$nl_title       = vbl_field('vbl_newsletter_title', false, 'Newsletter');
$nl_text        = vbl_field('vbl_newsletter_text', false, 'Seja o primeiro a receber as novidades sobre os nossos hotéis.');
$nl_privacy     = vbl_field('vbl_newsletter_privacy', false, 'Política de Privacidade.');
$nl_privacy_url = vbl_field('vbl_newsletter_privacy_url', false, '#');
$nl_btn         = vbl_field('vbl_newsletter_btn', false, 'Subscrever');
?>

<!-- ====== NEWSLETTER COMPACTA ====== -->
<!-- Mobile/Tablet -->
<section class="lg:hidden <?php echo esc_attr($bg_section); ?> relative overflow-hidden">
  <div class="relative px-6 py-14 sm:py-16">
    <img src="<?php echo esc_url($arvore_svg); ?>" alt=""
      class="absolute <?php echo esc_attr($tree_mobile_class); ?> top-1/2 -translate-y-1/2 w-auto pointer-events-none select-none"
      style="height: 130%; opacity: <?php echo esc_attr($tree_mobile_opacity); ?>;">
    <div class="relative z-10 flex flex-col gap-8">
      <div class="flex flex-col gap-3">
        <div class="flex items-center gap-3">
          <div class="w-8 h-[1px] <?php echo esc_attr($line_class); ?> flex-shrink-0"></div>
          <span class="font-body text-[11px] font-medium tracking-[2.5px] uppercase <?php echo esc_attr($text_class); ?>">
            <?php echo esc_html($nl_subtitle); ?>
          </span>
        </div>
        <h2 class="font-display text-[38px] sm:text-[44px] leading-[1.05] text-[#0d5257] uppercase">
          <?php echo esc_html($nl_title); ?>
        </h2>
        <p class="font-body font-light text-[13px] sm:text-[14px] leading-relaxed text-black/80 max-w-[480px]">
          <?php echo esc_html($nl_text); ?>
        </p>
      </div>
      <form class="flex flex-col gap-6 w-full vbl-newsletter-form">
        <?php wp_nonce_field('vbl_nonce', 'vbl_newsletter_nonce'); ?>
        <div class="font-body flex flex-col gap-4 w-full">
          <input type="email" name="email" placeholder="EMAIL" required
            class="w-full pb-2 pt-1 border-b border-black/40 bg-transparent text-[13px] tracking-[1.6px] uppercase outline-none placeholder:text-black/60 <?php echo esc_attr($focus_class); ?> transition-colors">
          <div class="flex items-center gap-3">
            <input type="checkbox" name="privacy" id="nl_privacy_mob" required
              class="w-4 h-4 bg-white border border-black/30 rounded flex-shrink-0 cursor-pointer <?php echo esc_attr($accent_checkbox); ?>">
            <label for="nl_privacy_mob" class="font-body font-light text-[12px] text-black/80 cursor-pointer">
              Li e aceito a <a href="<?php echo esc_url($nl_privacy_url); ?>"
                class="underline underline-offset-2 <?php echo esc_attr($hover_link_class); ?> transition-colors"><?php echo esc_html($nl_privacy); ?></a>
            </label>
          </div>
        </div>
        <button type="submit"
          class="group vbl-btn font-body inline-flex items-center justify-between w-[220px] px-5 py-3.5 border-t border-b border-x-0 <?php echo esc_attr($btn_border_class); ?> <?php echo esc_attr($text_class); ?> hover:text-white <?php echo $is_microsite ? 'hover:bg-[#0da9a6]' : 'hover:bg-[#bc945b]'; ?> text-[11px] font-medium tracking-[2.5px] uppercase transition-all duration-300">
          <span><?php echo esc_html($nl_btn); ?></span>
          <svg class="w-3.5 h-3.5 <?php echo esc_attr($text_class); ?> group-hover:text-white transition-all duration-300 group-hover:rotate-45"
            viewBox="0 0 12 12" fill="none">
            <path d="M1 11L11 1M11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>
        </button>
        <div class="vbl-newsletter-message hidden text-[13px] mt-1"></div>
      </form>
    </div>
  </div>
</section>

<!-- Desktop: MAIN SITE -->
<?php if ( ! $is_microsite ) : ?>
<style>
  .vbl-nl-main {
    display: none;
    width: 100%;
    height: 317px;
    position: relative;
    overflow: hidden;
    background-color: #EEE8E5;
  }
  @media (min-width: 1024px) {
    .vbl-nl-main { display: block; }
  }
  .vbl-nl-main__inner {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 120px;
    height: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 48px;
    position: relative;
  }
  @media (min-width: 1280px) {
    .vbl-nl-main__inner { gap: 160px; }
  }
  .vbl-nl-main__tree {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    height: 150%;
    width: auto;
    opacity: 0.10;
    pointer-events: none;
    user-select: none;
    z-index: 0;
  }
  .vbl-nl-main__text {
    position: relative;
    z-index: 10;
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 480px;
    flex-shrink: 0;
  }
  .vbl-nl-main__pretitle {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
  }
  .vbl-nl-main__pretitle-line {
    width: 32px;
    height: 1px;
    background-color: #bc945b;
    flex-shrink: 0;
  }
  .vbl-nl-main__pretitle-text {
    font-family: 'Commissioner', sans-serif;
    font-size: 14px;
    font-weight: 400;
    letter-spacing: 0.1em;
    line-height: 1;
    text-transform: uppercase;
    color: #0d5257;
  }
  .vbl-nl-main__title {
    font-family: 'Old Standard TT', serif;
    font-weight: 400;
    font-size: 64px;
    line-height: 0.98;
    color: #0d5257;
    text-transform: uppercase;
    letter-spacing: normal;
    margin: 0;
  }
  @media (max-width: 1535px) {
    .vbl-nl-main__title { font-size: 62px; }
  }
  @media (max-width: 1279px) {
    .vbl-nl-main__title { font-size: 52px; }
  }
  .vbl-nl-main__subtitle {
    font-family: 'Commissioner', sans-serif;
    font-weight: 300;
    font-size: 14px;
    color: rgba(0,0,0,0.8);
    line-height: 1.6;
    margin-top: 14px;
    max-width: 460px;
    text-align: center;
  }
  .vbl-nl-main__form-col {
    position: relative;
    z-index: 10;
    width: 360px;
    flex-shrink: 0;
  }
  .vbl-nl-main__form {
    display: flex;
    flex-direction: column;
    gap: 24px;
    align-items: flex-start;
    width: 100%;
  }
  .vbl-nl-main__fields {
    display: flex;
    flex-direction: column;
    gap: 16px;
    width: 100%;
  }
  .vbl-nl-main__email {
    width: 100%;
    padding: 4px 0 10px;
    border: none;
    border-bottom: 1px solid rgba(0,0,0,0.4);
    background: transparent;
    font-family: 'Commissioner', sans-serif;
    font-size: 13px;
    letter-spacing: 1.8px;
    text-transform: uppercase;
    outline: none;
    color: #1a1a1a;
  }
  .vbl-nl-main__email::placeholder { color: rgba(0,0,0,0.6); }
  .vbl-nl-main__email:focus { border-bottom-color: #bc945b; }
  .vbl-nl-main__privacy {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-top: 4px;
  }
  .vbl-nl-main__privacy input[type="checkbox"] {
    width: 16px;
    height: 16px;
    border: 1px solid rgba(0,0,0,0.4);
    background: transparent;
    border-radius: 0;
    cursor: pointer;
    accent-color: #bc945b;
    flex-shrink: 0;
  }
  .vbl-nl-main__privacy label {
    font-family: 'Commissioner', sans-serif;
    font-weight: 300;
    font-size: 12px;
    color: rgba(0,0,0,0.8);
    cursor: pointer;
  }
  .vbl-nl-main__privacy a {
    text-decoration: underline;
    text-underline-offset: 2px;
    transition: color 0.2s;
  }
  .vbl-nl-main__privacy a:hover { color: #bc945b; }
  .vbl-nl-main__btn {
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    width: 220px;
    padding: 14px 20px;
    border-top: 1px solid rgba(188,148,91,0.6);
    border-bottom: 1px solid rgba(188,148,91,0.6);
    border-left: none !important;
    border-right: none !important;
    background: transparent;
    color: #bc945b;
    font-family: 'Commissioner', sans-serif;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s;
  }
  .vbl-nl-main__btn:hover {
    background-color: #bc945b;
    color: #ffffff;
    border-color: #bc945b;
  }
  .vbl-nl-main__btn svg {
    width: 14px;
    height: 14px;
    color: #bc945b;
    transition: transform 0.3s, color 0.3s;
  }
  .vbl-nl-main__btn:hover svg {
    color: #ffffff;
    transform: rotate(45deg);
  }
</style>

<section class="vbl-nl-main">
  <!-- Árvore de fundo -->
  <img src="<?php echo esc_url($arvore_svg); ?>" alt=""
    class="vbl-nl-main__tree"
    style="left: 0;">

  <div class="vbl-nl-main__inner">

    <!-- Left Column: Text -->
    <div class="vbl-nl-main__text">
      <div class="vbl-nl-main__pretitle">
        <div class="vbl-nl-main__pretitle-line"></div>
        <span class="vbl-nl-main__pretitle-text"><?php echo esc_html($nl_subtitle); ?></span>
      </div>
      <h2 class="vbl-nl-main__title"><?php echo esc_html($nl_title); ?></h2>
      <p class="vbl-nl-main__subtitle"><?php echo esc_html($nl_text); ?></p>
    </div>

    <!-- Right Column: Form -->
    <div class="vbl-nl-main__form-col">
      <form class="vbl-nl-main__form vbl-newsletter-form">
        <?php wp_nonce_field('vbl_nonce', 'vbl_newsletter_nonce_desktop'); ?>
        <div class="vbl-nl-main__fields">
          <input type="email" name="email" placeholder="EMAIL" required class="vbl-nl-main__email">
          <div class="vbl-nl-main__privacy">
            <input type="checkbox" name="privacy" id="nl_privacy_desktop" required>
            <label for="nl_privacy_desktop">
              Li e aceito a <a href="<?php echo esc_url($nl_privacy_url); ?>"><?php echo esc_html($nl_privacy); ?></a>
            </label>
          </div>
        </div>
        <button type="submit" class="vbl-nl-main__btn vbl-btn">
          <span><?php echo esc_html($nl_btn); ?></span>
          <svg viewBox="0 0 12 12" fill="none">
            <path d="M1 11L11 1M11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
        <div class="vbl-newsletter-message hidden" style="font-size:13px;margin-top:4px;"></div>
      </form>
    </div>
  </div>
</section>

<?php else : ?>
<!-- Desktop: MICROSITE (original layout) -->
<section class="hidden lg:block w-full <?php echo esc_attr($bg_section); ?> relative overflow-hidden">
  <div class="relative max-w-[1440px] 2xl:max-w-[1600px] mx-auto px-8 lg:px-12 xl:px-20 py-16 xl:py-20 flex items-center justify-between min-h-[320px] xl:min-h-[350px]">
    <!-- Árvore de fundo -->
    <img src="<?php echo esc_url($arvore_svg); ?>" alt=""
      class="absolute top-1/2 -translate-y-1/2 w-auto pointer-events-none select-none z-0 <?php echo esc_attr($tree_desktop_class); ?>"
      style="height: 150%; opacity: 0.20;">

    <!-- Left Column: Pretitle, Title, Subtitle -->
    <div class="relative z-10 flex flex-col justify-center max-w-[560px] xl:max-w-[620px]">
      <!-- Pretitle -->
      <div class="flex items-center gap-3 mb-2">
        <div class="w-8 xl:w-10 h-px <?php echo esc_attr($line_class); ?> flex-shrink-0"></div>
        <span class="font-body text-[11px] xl:text-[12px] font-medium tracking-[2.5px] uppercase <?php echo esc_attr($text_class); ?>">
          <?php echo esc_html($nl_subtitle); ?>
        </span>
      </div>

      <!-- Title -->
      <h2 class="font-display text-[48px] lg:text-[56px] xl:text-[66px] 2xl:text-[72px] leading-[0.98] text-[#0d5257] uppercase tracking-normal">
        <?php echo esc_html($nl_title); ?>
      </h2>

      <!-- Subtitle -->
      <p class="font-body font-light text-[13px] xl:text-[14px] text-black/80 leading-relaxed pl-8 xl:pl-10 mt-3 max-w-[460px]">
        <?php echo esc_html($nl_text); ?>
      </p>
    </div>

    <!-- Right Column: Form -->
    <div class="relative z-10 w-full max-w-[380px] xl:max-w-[420px]">
      <form class="flex flex-col gap-6 xl:gap-7 items-start w-full vbl-newsletter-form">
        <?php wp_nonce_field('vbl_nonce', 'vbl_newsletter_nonce_desktop'); ?>
        
        <div class="font-body flex flex-col gap-4 w-full">
          <input type="email" name="email" placeholder="EMAIL" required
            class="w-full pb-2.5 pt-1 border-b border-black/40 bg-transparent text-[13px] xl:text-[14px] tracking-[1.8px] uppercase outline-none placeholder:text-black/60 <?php echo esc_attr($focus_class); ?> transition-colors">
          
          <div class="flex items-center gap-3 pt-1">
            <input type="checkbox" name="privacy" id="nl_privacy_desktop_micro" required
              class="w-4 h-4 bg-white border border-black/30 rounded flex-shrink-0 cursor-pointer <?php echo esc_attr($accent_checkbox); ?>">
            <label for="nl_privacy_desktop_micro" class="font-body font-light text-[12px] xl:text-[12.5px] text-black/80 leading-normal cursor-pointer">
              Li e aceito a <a href="<?php echo esc_url($nl_privacy_url); ?>"
                class="underline underline-offset-2 <?php echo esc_attr($hover_link_class); ?> transition-colors"><?php echo esc_html($nl_privacy); ?></a>
            </label>
          </div>
        </div>

        <button type="submit"
          class="group vbl-btn font-body inline-flex items-center justify-between w-[220px] xl:w-[240px] px-5 py-3.5 border-t border-b border-x-0 <?php echo esc_attr($btn_border_class); ?> <?php echo esc_attr($text_class); ?> hover:text-white hover:bg-[#0da9a6] text-[11px] xl:text-[12px] font-medium tracking-[2.5px] uppercase transition-all duration-300">
          <span><?php echo esc_html($nl_btn); ?></span>
          <svg class="w-3.5 h-3.5 <?php echo esc_attr($text_class); ?> group-hover:text-white transition-all duration-300 group-hover:rotate-45"
            viewBox="0 0 12 12" fill="none">
            <path d="M1 11L11 1M11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>
        </button>

        <div class="vbl-newsletter-message hidden text-[13px] mt-1"></div>
      </form>
    </div>
  </div>
</section>
<?php endif; ?>
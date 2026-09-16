<?php
/**
 * Template Part: Newsletter HOMEPAGE (pixel-perfect com index.astro)
 * Layout: Foto grande à direita 49.1%, título + form à esquerda posicionados absolutos.
 * Usado EXCLUSIVAMENTE no front-page.php (homepage).
 *
 * @package Vila_Baleira
 */

// ACF Fields (mesmos campos globais — homepage tem imagem própria)
$nl_subtitle = vbl_field( 'vbl_newsletter_subtitle', false, 'Subscreva a nossa' );
$nl_title    = vbl_field( 'vbl_newsletter_title',    false, 'Newsletter' );
$nl_text     = vbl_field( 'vbl_newsletter_text',     false, 'Seja o primeiro a receber as novidades sobre os nossos hotéis.' );
$nl_image    = vbl_field( 'vbl_newsletter_image',    false, vbl_img( 'newsletter-foto.jpg' ) );
if ( empty( $nl_image ) ) { $nl_image = vbl_img( 'newsletter-foto.jpg' ); }
$nl_privacy  = vbl_field( 'vbl_newsletter_privacy',  false, 'Política de Privacidade.' );
$nl_privacy_url = vbl_field( 'vbl_newsletter_privacy_url', false, '#' );
$nl_btn      = vbl_field( 'vbl_newsletter_btn',      false, 'Subscrever' );

$seta_dourada = vbl_img( 'seta-dourada.svg' );
?>

<!-- ====== NEWSLETTER (Homepage) ====== -->
<!-- Mobile -->
<section class="max-w-[1920px] mx-auto px-6 md:px-10 py-16 lg:hidden">
  <div class="flex flex-col gap-6">
    <div class="flex items-center gap-4">
      <div class="w-10 h-px bg-[#BC945B] flex-shrink-0"></div>
      <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $nl_subtitle ); ?></span>
    </div>
    <h2 class="font-['Old_Standard_TT',serif] text-[48px] leading-[52px] text-[#0d5257] uppercase"><?php echo esc_html( $nl_title ); ?></h2>
    <div class="flex flex-col gap-6 max-w-[440px]">
      <p class="font-body text-[16px] leading-[24px]"><?php echo esc_html( $nl_text ); ?></p>
      <form class="flex flex-col gap-6 items-start w-full vbl-newsletter-form">
        <?php wp_nonce_field( 'vbl_nonce', 'vbl_newsletter_nonce' ); ?>
        <div class="flex flex-col gap-4 w-full">
          <input type="email" name="email" placeholder="EMAIL" required class="font-body w-full pb-2 pt-1 border-b border-black/40 bg-transparent text-[13px] tracking-[1.6px] uppercase outline-none placeholder:text-black/60 focus:border-[#bc945b] transition-colors">
          <div class="flex items-center gap-3">
            <input type="checkbox" name="privacy" id="nl_privacy_mob_home" required class="w-4 h-4 bg-white rounded border border-black/30 flex-shrink-0 cursor-pointer accent-[#bc945b]">
            <label for="nl_privacy_mob_home" class="font-body font-light text-[12px] text-black/80 cursor-pointer">Li e aceito a <a href="<?php echo esc_url( $nl_privacy_url ); ?>" class="underline underline-offset-2 hover:text-[#bc945b] transition-colors"><?php echo esc_html( $nl_privacy ); ?></a></label>
          </div>
        </div>
        <button type="submit" class="group font-body vbl-btn inline-flex items-center justify-between w-[220px] px-5 py-3.5 border-t border-b border-x-0 border-[#bc945b]/60 hover:border-[#bc945b] text-[#bc945b] hover:text-white hover:bg-[#bc945b] text-[11px] font-medium tracking-[2.5px] uppercase transition-all duration-300">
          <span><?php echo esc_html( $nl_btn ); ?></span>
          <svg class="w-3.5 h-3.5 text-[#bc945b] group-hover:text-white transition-all duration-300 group-hover:rotate-45" viewBox="0 0 12 12" fill="none">
            <path d="M1 11L11 1M11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
        <div class="vbl-newsletter-message hidden text-[13px] mt-1"></div>
      </form>
    </div>
    <img src="<?php echo esc_url( $nl_image ); ?>" alt="" class="w-full aspect-[785/600] object-cover mt-4">
  </div>
</section>

<!-- Desktop: aspect-ratio 1600/600, posições % -->
<section class="hidden lg:block max-w-[1920px] mx-auto px-6 md:px-10 xl:px-[160px] py-16 xl:py-[100px]">
  <div class="relative min-h-[480px] max-w-full [@media(min-width:1024px)_and_(max-width:1279px)]:aspect-[1330/600] [@media(min-width:1280px)_and_(max-width:1333px)]:aspect-[1500/600] aspect-[1600/600] overflow-hidden" >
    <!-- Foto: right 0%, top 0%, w 49.1%, h 100% -->
    <img src="<?php echo esc_url( $nl_image ); ?>" alt="" class="absolute right-0 top-0 w-[49.1%] h-full object-cover">
    <!-- Label + Título: left 0%, top 13.3% -->
    <div class="absolute left-0 top-[13.3%] flex flex-col gap-[24px]">
      <div class="flex items-center gap-4">
        <div class="w-10 h-px bg-[#BC945B] flex-shrink-0"></div>
        <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $nl_subtitle ); ?></span>
      </div>
      <h2 class="font-display text-[clamp(48px,5.21vw,100px)] leading-[clamp(48px,5.21vw,100px)] text-[#0d5257] uppercase" style="white-space:nowrap"><?php echo esc_html( $nl_title ); ?></h2>
    </div>
    <!-- Form: left 5%, top 44.2%, w 27.5% -->
    <div class="absolute left-[5%] top-[44.2%] w-[27.5%] max-w-[440px] flex flex-col gap-6 z-10">
      <p class="font-body font-light text-[16px] leading-[24px]"><?php echo esc_html( $nl_text ); ?></p>
      <form class="flex flex-col gap-7 items-start w-full vbl-newsletter-form">
        <?php wp_nonce_field( 'vbl_nonce', 'vbl_newsletter_nonce_home' ); ?>
        <div class="flex flex-col gap-4 w-full">
          <input type="email" name="email" placeholder="EMAIL" required class="font-body w-full pb-2 pt-1 border-b border-black/40 bg-transparent text-[13px] xl:text-[14px] tracking-[1.8px] uppercase outline-none placeholder:text-black/60 focus:border-[#bc945b] transition-colors">
          <div class="flex items-center gap-3">
            <input type="checkbox" name="privacy" id="nl_privacy_desk_home" required class="w-4 h-4 bg-white rounded border border-black/30 flex-shrink-0 cursor-pointer accent-[#bc945b]">
            <label for="nl_privacy_desk_home" class="font-body font-light text-[12px] xl:text-[13px] text-black/80 cursor-pointer">Li e aceito a <a href="<?php echo esc_url( $nl_privacy_url ); ?>" class="underline underline-offset-2 hover:text-[#bc945b] transition-colors"><?php echo esc_html( $nl_privacy ); ?></a></label>
          </div>
        </div>
        <button type="submit" class="group font-body vbl-btn inline-flex items-center justify-between w-[220px] xl:w-[240px] px-5 py-3.5 border-t border-b border-x-0 border-[#bc945b]/60 hover:border-[#bc945b] text-[#bc945b] hover:text-white hover:bg-[#bc945b] text-[11px] xl:text-[12px] font-medium tracking-[2.5px] uppercase transition-all duration-300">
          <span><?php echo esc_html( $nl_btn ); ?></span>
          <svg class="w-3.5 h-3.5 text-[#bc945b] group-hover:text-white transition-all duration-300 group-hover:rotate-45" viewBox="0 0 12 12" fill="none">
            <path d="M1 11L11 1M11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
        <div class="vbl-newsletter-message hidden text-[13px] mt-1"></div>
      </form>
    </div>
  </div>
</section>

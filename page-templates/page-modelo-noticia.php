<?php
/**
 * Template Name: Vila Baleira — Modelo Notícia
 *
 * Pixel-perfect com /Users/joaofelix/Desktop/VBL-V2/src/pages/modelo-noticia.astro.
 * Partilhado por:
 *   - page-template: page-templates/page-modelo-noticia.php (demo)
 *   - single-vbl_noticia.php (posts do CPT via ACF)
 *
 * @package Vila_Baleira
 */
get_header();

// ───────────────────────────────────────────────
// FALLBACKS — cópia literal do modelo-noticia.astro
// ───────────────────────────────────────────────
$default_date   = '16 de janeiro de 2026';
$default_title  = 'Exemplo de título para notícia ou um evento a acontecer em 2026';
$default_hero   = vbl_img( 'modelo-noticia/hero.jpg' );
$default_img2   = vbl_img( 'modelo-noticia/secundaria.jpg' );

$default_text_1 = '<p>Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras. Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus.</p><p>Convallis odio massa pellentesque elit non eu fusce auctor mattis. <strong class="font-semibold">Diam integer ultricies vitae.</strong> Lorem ipsum dolor sit amet consectetur. Convallis odio massa pellentesque elit non eu fusce auctor mattis.</p><p>Lacinia enim sem vitae turpis ornare convallis cras. Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras. Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus.</p>';

$default_text_2 = '<p>Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur. Convallis odio massa pellentesque elit non eu fusce auctor mattis.</p><p>Lacinia enim sem vitae turpis ornare convallis cras. Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. <a href="#" class="text-[#bc945b] underline underline-offset-2">Convallis odio massa</a></p><p>Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras. Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus.</p>';

// ACF com fallbacks
$article_date   = vbl_field( 'vbl_noticia_article_date',    false, $default_date );
$article_title  = vbl_field( 'vbl_noticia_article_title',   false, $default_title );
$article_hero   = vbl_field( 'vbl_noticia_article_hero_img', false, $default_hero );
if ( empty( $article_hero ) ) $article_hero = $default_hero;

$body_text_1    = vbl_field( 'vbl_noticia_body_text_1', false, $default_text_1 );
$body_img       = vbl_field( 'vbl_noticia_body_img',    false, $default_img2 );
if ( empty( $body_img ) ) $body_img = $default_img2;
$body_text_2    = vbl_field( 'vbl_noticia_body_text_2', false, $default_text_2 );

$nav_share_label = vbl_field( 'vbl_noticia_nav_share',       false, 'Partilhar' );
$nav_share_url   = vbl_field( 'vbl_noticia_nav_share_url',   false, '#' );
$nav_prev_label  = vbl_field( 'vbl_noticia_nav_prev_label',  false, 'Anterior' );
$nav_prev_url    = vbl_field( 'vbl_noticia_nav_prev_url',    false, '#' );
$nav_next_label  = vbl_field( 'vbl_noticia_nav_next_label',  false, 'Seguinte' );
$nav_next_url    = vbl_field( 'vbl_noticia_nav_next_url',    false, '#' );
?>

<!-- Spacer for fixed header (this page has no immersive hero) -->
<div class="h-20"></div>

<!-- ====== SECÇÃO 1: ARTIGO TOP — Mobile/Tablet ====== -->
<article class="xl:hidden px-6 md:px-10">
  <div class="pt-8 pb-6 md:pt-10 md:pb-8 flex flex-col gap-5">
    <div class="flex items-center gap-3">
      <div class="w-8 h-px bg-[#0d5257] flex-shrink-0"></div>
      <span class="font-body text-[12px] md:text-[13px] tracking-[1.2px] uppercase text-[#0d5257]"><?php echo esc_html( $article_date ); ?></span>
    </div>
    <h1 class="font-display text-[clamp(26px,6.5vw,40px)] leading-[clamp(28px,7vw,42px)] text-[#0d5257] uppercase">
      <?php echo esc_html( $article_title ); ?>
    </h1>
  </div>
  <div class="w-full overflow-hidden">
    <img src="<?php echo esc_url( $article_hero ); ?>" alt="<?php echo esc_attr( $article_title ); ?>" class="w-full aspect-[1280/600] object-cover">
  </div>
</article>

<!-- ====== SECÇÃO 1: ARTIGO TOP — Desktop ====== -->
<article class="hidden xl:block max-w-[1920px] mx-auto px-[160px]" style="padding-top:80px">
  <div class="mx-auto" style="max-width:1280px">
    <div class="flex flex-col gap-[24px]">
      <div class="flex items-center gap-4">
        <div class="w-10 h-px bg-[#0d5257] flex-shrink-0"></div>
        <span class="font-body text-[14px] tracking-[1.4px] uppercase text-[#0d5257]"><?php echo esc_html( $article_date ); ?></span>
      </div>
      <h1 class="font-display text-[40px] leading-[40px] text-[#0d5257] uppercase">
        <?php echo esc_html( $article_title ); ?>
      </h1>
    </div>
    <div class="w-full overflow-hidden mt-[40px]">
      <img src="<?php echo esc_url( $article_hero ); ?>" alt="<?php echo esc_attr( $article_title ); ?>" class="w-full object-cover" style="aspect-ratio:1280/600">
    </div>
  </div>
</article>

<!-- ====== SECÇÃO 2: CORPO DO ARTIGO — Mobile/Tablet ====== -->
<div class="xl:hidden px-6 md:px-10 mt-10 md:mt-12">
  <div class="flex flex-col gap-8">
    <div class="flex flex-col gap-5 font-light text-[15px] leading-[23px]">
      <?php echo wp_kses_post( $body_text_1 ); ?>
    </div>
    <div class="w-full overflow-hidden">
      <img src="<?php echo esc_url( $body_img ); ?>" alt="" class="w-full aspect-[800/375] object-cover">
    </div>
    <div class="flex flex-col gap-5 font-light text-[15px] leading-[23px]">
      <?php echo wp_kses_post( $body_text_2 ); ?>
    </div>
  </div>
</div>

<!-- ====== SECÇÃO 2: CORPO DO ARTIGO — Desktop ====== -->
<div class="hidden xl:block max-w-[1920px] mx-auto px-[160px]" style="margin-top:80px">
  <div class="mx-auto flex flex-col gap-[40px] items-start" style="max-width:800px">
    <div class="flex flex-col gap-6 font-light text-[16px] leading-[24px]">
      <?php echo wp_kses_post( $body_text_1 ); ?>
    </div>
    <div class="w-full overflow-hidden">
      <img src="<?php echo esc_url( $body_img ); ?>" alt="" class="w-full object-cover" style="aspect-ratio:800/375">
    </div>
    <div class="flex flex-col gap-6 font-light text-[16px] leading-[24px]">
      <?php echo wp_kses_post( $body_text_2 ); ?>
    </div>
  </div>
</div>

<!-- ====== SECÇÃO 3: NAVEGAÇÃO — Mobile/Tablet ====== -->
<div class="xl:hidden px-6 md:px-10 mt-10 md:mt-12">
  <div class="flex items-center gap-6">
    <a href="<?php echo esc_url( $nav_share_url ); ?>" class="text-[13px] tracking-[1.3px] uppercase text-[#0d5257] flex-shrink-0"><?php echo esc_html( $nav_share_label ); ?></a>
    <div class="flex-1 h-px bg-[#eee8e5]"></div>
    <div class="flex items-center gap-5 flex-shrink-0">
      <a href="<?php echo esc_url( $nav_prev_url ); ?>" class="flex items-center gap-3 text-[13px] tracking-[1.3px] uppercase text-[#0d5257]">
        <svg class="w-[6px] h-[10px]" viewBox="0 0 6 10" fill="none"><path d="M5 1L1 5L5 9" stroke="#0d5257" stroke-width="1.2"/></svg>
        <span><?php echo esc_html( $nav_prev_label ); ?></span>
      </a>
      <a href="<?php echo esc_url( $nav_next_url ); ?>" class="flex items-center gap-3 text-[13px] tracking-[1.3px] uppercase text-[#0d5257]">
        <span><?php echo esc_html( $nav_next_label ); ?></span>
        <svg class="w-[6px] h-[10px]" viewBox="0 0 6 10" fill="none"><path d="M1 1L5 5L1 9" stroke="#0d5257" stroke-width="1.2"/></svg>
      </a>
    </div>
  </div>
</div>

<!-- ====== SECÇÃO 3: NAVEGAÇÃO — Desktop ====== -->
<div class="hidden xl:block max-w-[1920px] mx-auto px-[160px]" style="margin-top:80px">
  <div class="mx-auto flex items-center gap-[40px]" style="max-width:1280px">
    <a href="<?php echo esc_url( $nav_share_url ); ?>" class="text-[14px] tracking-[1.4px] uppercase text-[#0d5257] flex-shrink-0 whitespace-nowrap"><?php echo esc_html( $nav_share_label ); ?></a>
    <div class="flex-1 h-px bg-[#eee8e5]"></div>
    <div class="flex items-center gap-[24px] flex-shrink-0">
      <a href="<?php echo esc_url( $nav_prev_url ); ?>" class="flex items-center gap-[16px] text-[14px] tracking-[1.4px] uppercase text-[#0d5257] whitespace-nowrap">
        <svg class="w-[6px] h-[10px]" viewBox="0 0 6 10" fill="none"><path d="M5 1L1 5L5 9" stroke="#0d5257" stroke-width="1.2"/></svg>
        <span><?php echo esc_html( $nav_prev_label ); ?></span>
      </a>
      <a href="<?php echo esc_url( $nav_next_url ); ?>" class="flex items-center gap-[16px] text-[14px] tracking-[1.4px] uppercase text-[#0d5257] whitespace-nowrap">
        <span><?php echo esc_html( $nav_next_label ); ?></span>
        <svg class="w-[6px] h-[10px]" viewBox="0 0 6 10" fill="none"><path d="M1 1L5 5L1 9" stroke="#0d5257" stroke-width="1.2"/></svg>
      </a>
    </div>
  </div>
</div>

<!-- ====== Margem antes da Newsletter — Astro: mt-10 md:mt-12 xl:mt-[80px] ====== -->
<div class="h-10 md:h-12 xl:h-[80px]"></div>

<!-- ====== SECÇÃO 4: NEWSLETTER ====== -->
<?php get_template_part( 'template-parts/section', 'newsletter' ); ?>

<?php get_footer(); ?>

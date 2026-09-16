<?php
/**
 * Footer Template
 *
 * @package Vila_Baleira
 */

// Identifica o Hotel Pai associado
$current_hotel_id = get_the_ID();
global $post;
if ( is_singular( 'vbl_quarto' ) ) {
    $parent_hotel = vbl_field( 'vbl_quarto_hotel', get_the_ID() );
    if ( $parent_hotel ) {
        $current_hotel_id = is_object( $parent_hotel ) ? $parent_hotel->ID : (int) $parent_hotel;
    }
} elseif ( ! empty( $post->post_parent ) ) {
    $current_hotel_id = $post->post_parent;
}

$hotel_name        = vbl_field( 'vbl_hotel_name', $current_hotel_id, 'Porto Santo' );
$hotel_footer_logo = vbl_field( 'vbl_hotel_footer_logo', $current_hotel_id );

$address   = vbl_opt( 'address', "Sítio do Cabeço da Ponta, Apartado 243,\n9401-909 Porto Santo" );
$phone     = vbl_opt( 'phone', '+351 291 980 800' );
$email     = vbl_opt( 'email', 'sales@vilabaleira.com' );
$facebook  = vbl_opt( 'facebook', 'https://www.facebook.com/HotelsVilaBaleira' );
$instagram = vbl_opt( 'instagram', 'https://www.instagram.com/vila.baleira/' );
$youtube   = vbl_opt( 'youtube', 'https://www.youtube.com/@VilaBaleiraHotels' );
?>

  <!-- ====== FOOTER ====== -->
  <footer class="w-full bg-white font-body pt-16 lg:pt-24 pb-8">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%]">
      
      <div class="flex flex-col lg:flex-row lg:justify-between items-start gap-12 lg:gap-6 mb-16 xl:mb-24">
        
        <!-- Morada -->
        <div class="flex flex-col gap-6">
          <p class="font-medium text-[14px] tracking-[1.4px] uppercase text-[#0da9a6]">MORADA</p>
          <p class="font-light text-[16px] leading-[24px] text-black/80 max-w-[200px]">
            <a href="https://maps.google.com/?q=<?php echo urlencode( strip_tags( $address ) ); ?>" target="_blank" rel="noopener" class="hover:text-[#bc945b] hover:underline hover:decoration-[#bc945b] hover:underline-offset-4 decoration-1 transition-all inline-block">
              <?php echo nl2br( esc_html( $address ) ); ?>
            </a>
          </p>
        </div>

        <!-- Contactos -->
        <div class="flex flex-col gap-6">
          <p class="font-medium text-[14px] tracking-[1.4px] uppercase text-[#0da9a6]">CONTACTOS</p>
          <div class="font-light text-[16px] leading-[24px] text-black/80 flex flex-col gap-1">
            <p><a href="tel:<?php echo esc_html( $phone ); ?>" class="hover:text-[#bc945b] hover:underline hover:decoration-[#bc945b] hover:underline-offset-4 decoration-1 transition-all whitespace-nowrap"><?php echo esc_html( $phone ); ?></a></p>
            <p><a href="mailto:<?php echo esc_html( $email ); ?>" class="hover:text-[#bc945b] hover:underline hover:decoration-[#bc945b] hover:underline-offset-4 decoration-1 transition-all"><?php echo esc_html( $email ); ?></a></p>
          </div>
        </div>

        <!-- Siga-nos -->
        <div class="flex flex-col gap-6">
          <p class="font-medium text-[14px] tracking-[1.4px] uppercase text-[#0da9a6]">SIGA-NOS</p>
          <div class="flex items-center gap-5 text-[#0d5257]">
            <a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener" class="hover:text-[#bc945b] transition-colors duration-300">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12z"/>
              </svg>
            </a>
            <a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener" class="hover:text-[#bc945b] transition-colors duration-300">
              <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
              </svg>
            </a>
            <a href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener" class="hover:text-[#bc945b] transition-colors duration-300">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M23.498 6.163a3.003 3.003 0 0 0-2.11-2.11C19.517 3.545 12 3.545 12 3.545s-7.517 0-9.388.508a3.003 3.003 0 0 0-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 0 0 2.11 2.11c1.871.508 9.388.508 9.388.508s7.517 0 9.388-.508a3.003 3.003 0 0 0 2.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
              </svg>
            </a>
          </div>
        </div>
        
        <!-- Logo -->
        <div class="flex flex-col gap-6">
          <?php 
          if ( ! empty( $hotel_footer_logo ) ) : ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="Vila Baleira Hotels" class="inline-block hover:opacity-80 transition-opacity">
              <img src="<?php echo esc_url( $hotel_footer_logo ); ?>" alt="<?php echo esc_attr( $hotel_name ); ?>" class="h-auto max-h-[70px] w-auto max-w-[220px] object-contain">
            </a>
          <?php elseif ( $footer_logo_micro = get_theme_mod( 'vbl_footer_logo_microsite' ) ) : ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="Vila Baleira Hotels" class="inline-block hover:opacity-80 transition-opacity">
              <img src="<?php echo esc_url( $footer_logo_micro ); ?>" alt="Um Hotel do Grupo Vila Baleira" class="h-auto w-[180px] lg:w-[220px]">
            </a>
          <?php else : ?>
            <p class="font-medium text-[14px] tracking-[1.4px] uppercase text-[#0da9a6]">UM HOTEL DO GRUPO</p>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="Vila Baleira Hotels" class="flex items-center gap-4 lg:gap-5 flex-shrink-0 hover:opacity-80 transition-opacity">
              <img src="<?php echo vbl_img('footer-logo-icon.svg'); ?>" alt="" class="w-10 lg:w-[48px] h-auto">
              <div class="flex flex-col gap-1 lg:gap-1.5">
                <img src="<?php echo vbl_img('footer-logo-text.svg'); ?>" alt="Vila Baleira" class="h-[9px] lg:h-[11px] w-auto">
                <img src="<?php echo vbl_img('footer-logo-subtitle.svg'); ?>" alt="The Essence of Hospitality" class="h-[3px] lg:h-[4px] w-auto">
              </div>
            </a>
          <?php endif; ?>
        </div>
        
      </div>

      <!-- Bottom bar -->
      <div class="flex flex-col xl:flex-row items-start xl:items-center justify-between border-t border-[#0da9a6]/30 pt-6 gap-6 relative">
        <div class="flex flex-wrap items-center gap-3 lg:gap-4 text-[9px] uppercase tracking-wider text-black/60 font-medium">
          <?php
          $legal_links = vbl_opt( 'legal_links', array(
              array( 'label' => 'Aviso Legal',                      'url' => '/aviso-legal',                     'external' => 0 ),
              array( 'label' => 'Política de privacidade e cookies','url' => '/politica-de-privacidade-e-cookies', 'external' => 0 ),
              array( 'label' => 'Livro de reclamações',             'url' => 'https://www.livroreclamacoes.pt/Inicio/', 'external' => 1 ),
              array( 'label' => 'Canal de Denuncia Grupo Ferpinta', 'url' => 'https://whistleblowersoftware.com/secure/abc3c3c1-245d-4721-95c2-b47cd32cce83', 'external' => 1 ),
          ) );
          $nipc  = vbl_opt( 'nipc', '511 085 133' );
          $rnavt = vbl_opt( 'rnavt', '9525' );

          foreach ( $legal_links as $llink ) :
              $target = ! empty( $llink['external'] ) ? ' target="_blank" rel="noopener"' : '';
              if ( ! empty( $llink['url'] ) && $llink['url'] !== '#' ) :
                  $resolved_url = function_exists( 'vbl_resolve_url' ) ? vbl_resolve_url( $llink['url'] ) : esc_url( $llink['url'] );
          ?>
              <a href="<?php echo esc_url( $resolved_url ); ?>"<?php echo $target; ?> class="hover:text-[#bc945b] hover:underline hover:decoration-[#bc945b] hover:underline-offset-4 decoration-1 transition-all"><span class="whitespace-nowrap"><?php echo esc_html( $llink['label'] ); ?></span></a><span class="text-[#0da9a6] hidden lg:inline">|</span>
          <?php else : ?>
              <span class="whitespace-nowrap"><?php echo esc_html( $llink['label'] ); ?></span><span class="text-[#0da9a6] hidden lg:inline">|</span>
          <?php
              endif;
          endforeach;

          if ( $nipc ) :
          ?>
              <span class="whitespace-nowrap">N.I.P.C. <?php echo esc_html( $nipc ); ?></span><span class="text-[#0da9a6] hidden sm:inline">|</span>
          <?php endif;
          if ( $rnavt ) :
          ?>
              <span class="whitespace-nowrap">RNAVT Nº <?php echo esc_html( $rnavt ); ?></span>
          <?php endif; ?>
        </div>
        <div class="text-[9px] uppercase tracking-wider text-black/60 font-medium whitespace-nowrap">
          VILA BALEIRA &copy; <?php echo date( 'Y' ); ?> <span class="mx-2 text-[#0da9a6]">|</span> <a href="https://www.sanzza.com" target="_blank" class="hover:text-[#bc945b] hover:underline hover:decoration-[#bc945b] hover:underline-offset-[3px] decoration-1 transition-all">DESIGNED BY SANZZA</a>
        </div>
      </div>
    </div>
  </footer>
	
	<!-- Reservar em mobile -->
	<div id="barraFixaMobile" class="fixed bottom-0 left-0 right-0 z-[99999] bg-white border-t border-[#eee8e5] px-6 py-3 flex items-center justify-between shadow-[0_-4px_20px_rgba(0,0,0,0.1)] [@media(min-width:426px)]:hidden">		
    	<a href="#" class="group font-body w-full flex items-center justify-between gap-4 px-6 py-4 border border-[#0d5257] text-[#0d5257] text-[14px] tracking-[1.4px] uppercase hover:bg-[#0da9a6] hover:border-[#0da9a6] hover:text-white transition-all duration-300">
        	<span>Reservar</span>
        	<svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45" viewBox="0 0 11 11" fill="none"><path d="M1 10L10 1M10 1H3M10 1V8" stroke="currentColor" stroke-width="1.2"/></svg>
    	</a>
	</div>

	<!-- Back to top -->
	<div id="backTopWrapper" class="fixed bottom-20 sm:bottom-6 xl:bottom-8 left-0 right-0 w-full pointer-events-none z-[999]">
	  <div class="max-w-[1920px] mx-auto px-6 xl:px-12 flex justify-end w-full">
	    <button id="backTop" type="button" aria-label="Voltar ao topo" class="group vbl-back-top w-10 h-10 border border-[#0da9a6] flex items-center justify-center bg-white/90 backdrop-blur-sm hover:bg-[#0da9a6] hover:border-[#0da9a6] cursor-pointer text-[#0da9a6] hover:text-white shadow-md pointer-events-auto" onclick="window.scrollTo({top:0,behavior:'smooth'})">
	      <svg class="w-3.5 h-3.5 pointer-events-none" viewBox="0 0 16 16" fill="none"><polyline points="2,10 8,4 14,10" stroke="currentColor" stroke-width="2"/></svg>
	    </button>
	  </div>
	</div>

<?php wp_footer(); ?>
</body>
</html>

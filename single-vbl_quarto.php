<?php
/**
 * Single Template: Quarto Individual (CPT vbl_quarto)
 *
 * @package Vila_Baleira
 */

// Inclui o Header Transparente com branding do Hotel Pai
require_once VBL_DIR . '/header-hotel.php';

$post_id = get_the_ID();

// Hotel Pai (Post Object ID)
$parent_hotel = vbl_field( 'vbl_quarto_hotel', $post_id );
$hotel_id = false;
if ( $parent_hotel ) {
    $hotel_id = is_object( $parent_hotel ) ? $parent_hotel->ID : (int) $parent_hotel;
}

$hotel_name = $hotel_id ? vbl_field( 'vbl_hotel_name', $hotel_id, 'Porto Santo' ) : 'Porto Santo';
$hotel_booking_url = vbl_field( 'vbl_quarto_booking_url', $post_id );
if ( empty( $hotel_booking_url ) ) {
    $hotel_booking_url = vbl_field( 'vbl_hotel_booking_url', $hotel_id, '#' );
}

// Categoria & Detalhes
$categoria = vbl_field( 'vbl_quarto_categoria', $post_id, 'SUITE' );
$intro_desc = get_the_excerpt( $post_id );
if ( empty( $intro_desc ) ) {
    $intro_desc = 'Quartos amplos, com varanda privada e uma decoração descontraída em cores vivas e muita luz. Ideal para estadias relaxantes e revigorantes.';
}

// Imagem Hero
$hero_bg = get_the_post_thumbnail_url( $post_id, 'full' );
if ( empty( $hero_bg ) ) {
    $hero_bg = vbl_img( 'hoteis/suites-680x400.jpg' );
}

// Galeria (Slider de Fotos)
$galeria = vbl_field( 'vbl_quarto_galeria', $post_id, array() );
if ( empty( $galeria ) || ! is_array( $galeria ) ) {
    $galeria = array(
        array( 'image' => vbl_img( 'hoteis/porto-santo-520x400.jpg' ) ),
        array( 'image' => vbl_img( 'hoteis/suites-680x400.jpg' ) ),
        array( 'image' => vbl_img( 'hoteis/porto-santo-760x760.jpg' ) ),
    );
}

// Comodidades
$comod_subtitle = vbl_field( 'vbl_quarto_comod_subtitle', $post_id, 'SERVIÇOS / COMODIDADES' );
$comod_title    = vbl_field( 'vbl_quarto_comod_title', $post_id, 'NISI ORCI LEO SED IN' );
$comod_text     = vbl_field( 'vbl_quarto_comod_text', $post_id, 'Diam elit faucibus enim pellentesque nisi orci neque leo, aliquet dignissim dui tortor massa. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus.' );

if ( ! function_exists( 'vbl_get_quarto_icon_url' ) ) {
    function vbl_get_quarto_icon_url( $icone_key = '', $nome = '' ) {
        $base_uri = VBL_URI . '/assets/images/icons-quarto/';
        $map = array(
            'varanda'             => 'varanda.svg',
            'wifi'                => 'wifi.svg',
            'banheira_com_duche'  => 'banheira_com_duche.svg',
            'cofre'               => 'cofre.svg',
            'toucador'            => 'toucador.svg',
            'ar_condicionado'     => 'ar_condicionado.svg',
            'secador_de_cabelo'   => 'secador_de_cabelo.svg',
            'phone'               => 'phone.svg',
            'telefone'            => 'phone.svg',
            'amenidades_de_banho' => 'amenidades_de_banho.svg',
            'televisao'           => 'televisao.svg',
            'chao_de_mosaico'     => 'chao_de_mosaico.svg',
            'bide'                => 'bide.svg',
        );

        if ( ! empty( $icone_key ) && $icone_key !== 'auto' && isset( $map[ $icone_key ] ) ) {
            return $base_uri . $map[ $icone_key ];
        }

        $n = mb_strtolower( trim( $nome ) );
        if ( strpos( $n, 'varanda' ) !== false || strpos( $n, 'terraço' ) !== false || strpos( $n, 'balcony' ) !== false ) {
            return $base_uri . 'varanda.svg';
        }
        if ( strpos( $n, 'wifi' ) !== false || strpos( $n, 'wi-fi' ) !== false || strpos( $n, 'internet' ) !== false ) {
            return $base_uri . 'wifi.svg';
        }
        if ( strpos( $n, 'banheira' ) !== false || strpos( $n, 'duche' ) !== false || strpos( $n, 'chuveiro' ) !== false ) {
            return $base_uri . 'banheira_com_duche.svg';
        }
        if ( strpos( $n, 'cofre' ) !== false || strpos( $n, 'safe' ) !== false ) {
            return $base_uri . 'cofre.svg';
        }
        if ( strpos( $n, 'toucador' ) !== false || strpos( $n, 'espelho' ) !== false || strpos( $n, 'secretária' ) !== false || strpos( $n, 'mesa' ) !== false ) {
            return $base_uri . 'toucador.svg';
        }
        if ( strpos( $n, 'ar condicionado' ) !== false || strpos( $n, 'climatiz' ) !== false || strpos( $n, 'ac' ) !== false || strpos( $n, 'temperatura' ) !== false ) {
            return $base_uri . 'ar_condicionado.svg';
        }
        if ( strpos( $n, 'secador' ) !== false || strpos( $n, 'cabelo' ) !== false ) {
            return $base_uri . 'secador_de_cabelo.svg';
        }
        if ( strpos( $n, 'telefone' ) !== false || strpos( $n, 'fone' ) !== false || strpos( $n, 'phone' ) !== false ) {
            return $base_uri . 'phone.svg';
        }
        if ( strpos( $n, 'amenit' ) !== false || strpos( $n, 'higiene' ) !== false || strpos( $n, 'champô' ) !== false || strpos( $n, 'sabonete' ) !== false ) {
            return $base_uri . 'amenidades_de_banho.svg';
        }
        if ( strpos( $n, 'tv' ) !== false || strpos( $n, 'televis' ) !== false || strpos( $n, 'ecrã' ) !== false ) {
            return $base_uri . 'televisao.svg';
        }
        if ( strpos( $n, 'mosaico' ) !== false || strpos( $n, 'chão' ) !== false || strpos( $n, 'piso' ) !== false || strpos( $n, 'parquê' ) !== false ) {
            return $base_uri . 'chao_de_mosaico.svg';
        }
        if ( strpos( $n, 'bidé' ) !== false || strpos( $n, 'bide' ) !== false ) {
            return $base_uri . 'bide.svg';
        }

        return $base_uri . 'amenidades_de_banho.svg';
    }
}

$comodidades_list = vbl_field( 'vbl_quarto_comodidades_list', $post_id, array() );
if ( empty( $comodidades_list ) || ! is_array( $comodidades_list ) ) {
    $comodidades_list = array(
        array( 'nome' => 'VARANDA',                  'icone' => 'varanda' ),
        array( 'nome' => 'COFRE (GRÁTIS)',           'icone' => 'cofre' ),
        array( 'nome' => 'SECADOR DE CABELO',        'icone' => 'secador_de_cabelo' ),
        array( 'nome' => 'TELEVISÃO',                'icone' => 'televisao' ),
        array( 'nome' => 'INTERNET WIFI (GRÁTIS)',   'icone' => 'wifi' ),
        array( 'nome' => 'TOUCADOR',                 'icone' => 'toucador' ),
        array( 'nome' => 'TELEFONE',                 'icone' => 'phone' ),
        array( 'nome' => 'CHÃO DE MOSAICO',          'icone' => 'chao_de_mosaico' ),
        array( 'nome' => 'BANHEIRA COM DUCHE',       'icone' => 'banheira_com_duche' ),
        array( 'nome' => 'AR CONDICIONADO',          'icone' => 'ar_condicionado' ),
        array( 'nome' => 'AMENIDADES DE BANHO',      'icone' => 'amenidades_de_banho' ),
        array( 'nome' => 'BIDÉ',                     'icone' => 'bide' ),
    );
}

// Banner Frase Final
$frase_bg    = vbl_field( 'vbl_quarto_frase_bg', $post_id, vbl_img( 'hoteis/frase-ilhas-bg.jpg' ) );
if ( empty( $frase_bg ) ) {
    $frase_bg = vbl_img( 'hoteis/frase-ilhas-bg.jpg' );
}
$frase_line1 = vbl_field( 'vbl_quarto_frase_line1', $post_id, 'LOREM IPSUM DOLOR' );
$frase_line2 = vbl_field( 'vbl_quarto_frase_line2', $post_id, 'ENIM VITAE TURPIS' );
$frase_line3 = vbl_field( 'vbl_quarto_frase_line3', $post_id, 'LACUS EGET UT SIT.' );
?>

<main class="w-full bg-white text-black overflow-hidden">

  <!-- ==========================================
       1. HERO BANNER
  =========================================== -->
  <section class="relative w-full h-[280px] sm:h-[360px] max-h-[360px] overflow-hidden bg-black flex items-end vbl-hotel-subpage-hero">
    <div class="absolute inset-0 z-0">
      <img src="<?php echo esc_url( $hero_bg ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover object-center filter brightness-[0.85]">
      <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/50"></div>
    </div>
  </section>

  <!-- ==========================================
       2. TÍTULO, DESCRIÇÃO E BOTÃO RESERVAR
  =========================================== -->
  <section class="w-full py-16 lg:py-24 bg-white text-center">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] flex flex-col items-center">
      
      <!-- Categoria / Tagline Superior -->
      <div class="flex items-center justify-center gap-3 mb-4">
        <span class="font-body text-[11px] xl:text-[12px] tracking-[2.5px] uppercase text-[#0da9a6] font-medium">
          <?php echo esc_html( $categoria ); ?>
        </span>
      </div>

      <!-- Título do Quarto (Display Serif) -->
      <h1 class="font-display text-[44px] sm:text-[56px] md:text-[68px] lg:text-[80px] leading-[1.02] tracking-[1.5px] uppercase text-[#0d5257] mb-6 max-w-[1100px] font-normal">
        <?php the_title(); ?>
      </h1>

      <!-- Descrição Curta -->
      <p class="font-body font-light text-[14px] lg:text-[15px] xl:text-[16px] leading-relaxed text-[#333333] max-w-[620px] mb-8">
        <?php echo nl2br( esc_html( $intro_desc ) ); ?>
      </p>

      <!-- Botão Reservar -->
      <a href="<?php echo esc_url( $hotel_booking_url ); ?>" class="vbl-btn-microsite w-[180px]">
        <span>RESERVAR</span>
        <svg viewBox="0 0 12 12" fill="none">
          <path d="M1 11L11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>

    </div>
  </section>

  <!-- ==========================================
       3. SLIDER DE FOTOGRAFIAS DO QUARTO (Largura Total / Full-width)
  =========================================== -->
  <section class="w-full relative overflow-hidden bg-black">
    <div class="relative w-full h-[460px] sm:h-[580px] md:h-[680px] lg:h-[780px] xl:h-[840px] overflow-hidden group/slider">
      
      <div id="vblQuartoGaleriaTrack" class="flex h-full transition-transform duration-500 ease-out">
        <?php foreach ( $galeria as $item ) : 
            $gal_img = ! empty( $item['image'] ) ? $item['image'] : '';
            if ( empty( $gal_img ) ) continue;
        ?>
        <div class="w-full h-full flex-shrink-0">
          <img src="<?php echo esc_url( $gal_img ); ?>" alt="Fotografia do Quarto" class="w-full h-full object-cover object-center">
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Seta Esquerda -->
      <button type="button" id="vblGaleriaPrev" class="absolute left-4 sm:left-8 lg:left-12 xl:left-16 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-12 sm:h-12 border border-white/70 text-white bg-black/15 hover:bg-white hover:text-[#0d5257] hover:border-white flex items-center justify-center transition-all duration-300 cursor-pointer backdrop-blur-[2px] shadow-sm" aria-label="Foto Anterior">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 12H5M12 19l-7-7 7-7" />
        </svg>
      </button>

      <!-- Seta Direita -->
      <button type="button" id="vblGaleriaNext" class="absolute right-4 sm:right-8 lg:right-12 xl:right-16 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-12 sm:h-12 border border-white/70 text-white bg-black/15 hover:bg-white hover:text-[#0d5257] hover:border-white flex items-center justify-center transition-all duration-300 cursor-pointer backdrop-blur-[2px] shadow-sm" aria-label="Próxima Foto">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14M12 5l7 7-7 7" />
        </svg>
      </button>

    </div>
  </section>

  <!-- ==========================================
       4. COMODIDADES DO QUARTO
  =========================================== -->
  <section class="w-full py-20 lg:py-28 xl:py-32 bg-white relative overflow-hidden">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] relative z-10">
      
      <!-- Subtítulo com Traço Ciano -->
      <div class="flex items-center gap-4 mb-3">
        <div class="w-8 h-px bg-[#0da9a6] flex-shrink-0"></div>
        <span class="font-body text-[11px] xl:text-[12px] tracking-[2px] uppercase text-[#0da9a6] font-medium">
          <?php echo esc_html( $comod_subtitle ); ?>
        </span>
      </div>

      <!-- Título Display Serif -->
      <h2 class="font-display text-[40px] sm:text-[52px] lg:text-[64px] leading-[1.02] tracking-[1.5px] uppercase text-[#0d5257] mb-8 lg:mb-10 font-normal">
        <?php echo esc_html( $comod_title ); ?>
      </h2>

      <!-- Conteúdo Indentado à Direita: Descrição e Grelha de Comodidades (Alinhado à 2ª Coluna como no Figma) -->
      <div class="pl-0 sm:pl-6 lg:pl-[8.33%]">
        
        <!-- Texto Introdutório Indentado -->
        <?php if ( ! empty( $comod_text ) ) : ?>
        <p class="font-body font-light text-[13px] sm:text-[14px] lg:text-[15px] leading-relaxed text-[#4a4a4a] max-w-[540px] mb-12 lg:mb-14">
          <?php echo nl2br( esc_html( $comod_text ) ); ?>
        </p>
        <?php endif; ?>

        <!-- Grid de Comodidades Indentado (4 colunas no desktop) -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-y-7 gap-x-6 lg:gap-x-8 xl:gap-x-12 pt-2">
          <?php foreach ( $comodidades_list as $comod ) : 
              $c_nome  = ! empty( $comod['nome'] ) ? $comod['nome'] : '';
              $c_icone = ! empty( $comod['icone'] ) ? $comod['icone'] : 'auto';
              if ( empty( $c_nome ) ) continue;
              $c_icon_url = vbl_get_quarto_icon_url( $c_icone, $c_nome );
          ?>
          <div class="flex items-center gap-3.5">
            <img src="<?php echo esc_url( $c_icon_url ); ?>" alt="<?php echo esc_attr( $c_nome ); ?>" class="w-7 h-7 sm:w-8 sm:h-8 flex-shrink-0 object-contain">
            <span class="font-body text-[11px] xl:text-[12px] tracking-[1.2px] uppercase text-[#0d5257] font-medium leading-tight">
              <?php echo esc_html( $c_nome ); ?>
            </span>
          </div>
          <?php endforeach; ?>
        </div>

      </div>

    </div>

    <!-- Concha Decorativa em Outline Ciano (Canto inferior direito como no design) -->
    <div class="absolute -bottom-8 sm:-bottom-12 lg:-bottom-16 right-4 sm:right-8 lg:right-16 z-0 w-36 sm:w-48 lg:w-60 pointer-events-none opacity-25">
      <img src="<?php echo vbl_img( 'concha-cyan.svg' ); ?>" alt="" class="w-full h-auto object-contain">
    </div>
  </section>

  <!-- ==========================================
       5. WIDGET DE RESERVA (Faça já a sua reserva)
  =========================================== -->
  <section class="w-full py-16 lg:py-20 bg-[#E8F5F5] relative overflow-hidden">
    <!-- Estrela do mar decorativa em outline -->
    <div class="absolute right-0 top-1/2 -translate-y-1/2 w-64 lg:w-96 pointer-events-none opacity-20 text-[#0da9a6]">
      <img src="<?php echo vbl_img( 'estrela.svg' ); ?>" alt="" class="w-full h-auto">
    </div>

    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] relative z-10">
      
      <!-- Cabeçalho do Widget -->
      <div class="mb-10">
        <div class="flex items-center gap-4 mb-2">
          <div class="w-8 h-px bg-[#0da9a6] flex-shrink-0"></div>
          <span class="font-body text-[10px] xl:text-[11px] tracking-[2px] uppercase text-[#0da9a6] font-medium">RESERVAR ESTADIA</span>
        </div>
        <h2 class="font-display text-[36px] sm:text-[46px] lg:text-[56px] leading-tight uppercase text-[#0d5257] font-normal mb-3">
          FAÇA JÁ A SUA RESERVA
        </h2>
        <p class="font-body font-light text-[13px] lg:text-[14px] text-black/70 max-w-[480px]">
          Garanta a sua estadia com as melhores condições e tarifas exclusivas no site oficial.
        </p>
      </div>

      <!-- Barra de Pesquisa de Quartos -->
      <div class="w-full border border-[#0da9a6]/40 grid grid-cols-1 md:grid-cols-4 items-center bg-white/70 backdrop-blur-xs">
        
        <!-- ONDE -->
        <div class="flex flex-col gap-1 border-b md:border-b-0 md:border-r border-[#0da9a6]/40 py-4 px-6 xl:px-8">
          <span class="font-body text-[9px] xl:text-[10px] tracking-[1px] uppercase text-[#0d5257]/70">ONDE</span>
          <div class="flex items-center justify-between">
            <span class="font-body text-[13px] xl:text-[14px] text-[#0d5257] font-medium uppercase tracking-[1px]">
              <?php echo esc_html( $hotel_name ); ?>
            </span>
            <svg class="w-[10px] h-[10px] text-[#0d5257]" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
          </div>
        </div>
        
        <!-- QUANDO -->
        <div class="flex flex-col gap-1 border-b md:border-b-0 md:border-r border-[#0da9a6]/40 py-4 px-6 xl:px-8">
          <span class="font-body text-[9px] xl:text-[10px] tracking-[1px] uppercase text-[#0d5257]/70">QUANDO</span>
          <div class="flex items-center justify-between">
            <span class="font-body text-[13px] xl:text-[14px] text-[#0d5257] font-medium uppercase tracking-[1px]">ENTRADA - SAÍDA</span>
            <svg class="w-[10px] h-[10px] text-[#0d5257]" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
          </div>
        </div>
        
        <!-- QUEM -->
        <div class="flex flex-col gap-1 border-b md:border-b-0 md:border-r border-[#0da9a6]/40 py-4 px-6 xl:px-8">
          <span class="font-body text-[9px] xl:text-[10px] tracking-[1px] uppercase text-[#0d5257]/70">QUEM</span>
          <div class="flex items-center justify-between">
            <span class="font-body text-[13px] xl:text-[14px] text-[#0d5257] font-medium uppercase tracking-[1px]">2 ADULTOS · 1 QUARTO</span>
            <svg class="w-[10px] h-[10px] text-[#0d5257]" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
          </div>
        </div>
        
        <!-- PESQUISAR -->
        <a href="<?php echo esc_url( $hotel_booking_url ); ?>" class="flex items-center justify-between py-4 px-6 xl:px-8 bg-white hover:bg-[#0da9a6] hover:text-white text-[#0d5257] cursor-pointer transition-colors group">
          <span class="font-body text-[13px] xl:text-[14px] font-medium uppercase tracking-[1.4px]">PESQUISAR</span>
          <svg class="w-[10px] h-[10px] transition-transform duration-300 group-hover:rotate-45" viewBox="0 0 11 11" fill="none">
            <path d="M10.8535 10.5H9.85352V1.70703L0.707031 10.8535L0 10.1465L9.14648 1H0.353516V0H10.3535C10.6297 0 10.8535 0.223858 10.8535 0.5V10.5Z" fill="currentColor"/>
          </svg>
        </a>
        
      </div>

    </div>
  </section>

  <!-- ==========================================
       6. OUTROS QUARTOS RELACIONADOS (Carrossel)
  =========================================== -->
  <?php
  // Query aos restantes quartos do mesmo hotel pai
  $args_other = array(
      'post_type'      => 'vbl_quarto',
      'posts_per_page' => 8,
      'post__not_in'   => array( $post_id ),
      'orderby'        => 'menu_order title',
      'order'          => 'ASC',
  );

  if ( $hotel_id ) {
      $args_other['meta_query'] = array(
          array(
              'key'     => 'vbl_quarto_hotel',
              'value'   => $hotel_id,
              'compare' => '=',
          ),
      );
  }

  $other_rooms_query = new WP_Query( $args_other );

  // Se não houver pelo menos 2 quartos deste hotel, busca quartos gerais
  if ( ! $other_rooms_query->have_posts() || $other_rooms_query->found_posts < 2 ) {
      $fallback_args = array(
          'post_type'      => 'vbl_quarto',
          'posts_per_page' => 8,
          'post__not_in'   => array( $post_id ),
          'orderby'        => 'menu_order title',
          'order'          => 'ASC',
      );
      $fallback_query = new WP_Query( $fallback_args );
      if ( $fallback_query->have_posts() && $fallback_query->found_posts >= 2 ) {
          $other_rooms_query = $fallback_query;
      }
  }

  $other_rooms_list = array();
  if ( $other_rooms_query->have_posts() ) {
      while ( $other_rooms_query->have_posts() ) {
          $other_rooms_query->the_post();
          $o_id    = get_the_ID();
          $o_cat   = vbl_field( 'vbl_quarto_categoria', $o_id, 'QUARTO' );
          $o_thumb = get_the_post_thumbnail_url( $o_id, 'medium_large' );
          if ( empty( $o_thumb ) ) $o_thumb = vbl_img( 'hoteis/porto-santo-520x400.jpg' );

          $other_rooms_list[] = array(
              'cat'   => $o_cat,
              'title' => get_the_title( $o_id ),
              'desc'  => wp_trim_words( get_the_excerpt( $o_id ), 18, '...' ),
              'thumb' => $o_thumb,
              'link'  => get_permalink( $o_id ),
          );
      }
      wp_reset_postdata();
  }

  // Fallback garantido se não houver dados no banco
  if ( count( $other_rooms_list ) < 2 ) {
      $other_rooms_list = array(
          array(
              'cat'   => 'APARTAMENTO',
              'title' => 'APARTAMENTO VISTA MAR',
              'desc'  => 'Espaços que combinam independência e vistas panorâmicas, perfeitos para quem procura autonomia junto ao mar.',
              'thumb' => vbl_img( 'hoteis/porto-santo-520x400.jpg' ),
              'link'  => '#',
          ),
          array(
              'cat'   => 'QUARTO SUPERIOR',
              'title' => 'DELUXE VISTA MAR',
              'desc'  => 'Acomodações superiores desenhadas para proporcionar uma estadia sofisticada com o oceano como pano de fundo constante.',
              'thumb' => vbl_img( 'hoteis/suites-680x400.jpg' ),
              'link'  => '#',
          ),
          array(
              'cat'   => 'QUARTO',
              'title' => 'CLÁSSICO TWIN',
              'desc'  => 'Quartos confortáveis, ideais para relaxar após explorar as dunas e praias da ilha.',
              'thumb' => vbl_img( 'hoteis/funchal-680x400.jpg' ),
              'link'  => '#',
          ),
          array(
              'cat'   => 'SUITE',
              'title' => 'SUITE FAMILIAR VISTA MAR',
              'desc'  => 'Espaço privilegiado com vista panorâmica sobre o oceano, varanda privada e sala integrada.',
              'thumb' => vbl_img( 'hoteis/residence-680x400.jpg' ),
              'link'  => '#',
          ),
      );
  }
  ?>
  <section class="w-full py-20 lg:py-32 bg-white relative overflow-hidden">
    <div class="max-w-[1920px] mx-auto px-6 xl:px-[8.33%] relative">
      
      <!-- Cabeçalho Outros Quartos -->
      <div class="text-center mb-14 lg:mb-16 flex flex-col items-center">
        <span class="font-body text-[11px] xl:text-[12px] tracking-[2.5px] uppercase text-[#0da9a6] font-medium mb-3">
          ROOMS & SUITES
        </span>
        <h2 class="font-display text-[40px] sm:text-[52px] lg:text-[64px] leading-[1.05] uppercase text-[#0d5257] font-normal mb-4">
          AMET ET LEO NEQUE<br>LOREM IPSUM SIT
        </h2>
        <p class="font-body font-light text-[14px] text-black/70 max-w-[560px]">
          Descubra as restantes opções de alojamento e escolha o refúgio perfeito para a sua estadia.
        </p>
      </div>

      <!-- Carrossel de Quartos Relacionados -->
      <div class="relative">

        <!-- Botão Seta Esquerda (Anterior) -->
        <button type="button" id="vblOtherRoomsPrev" class="absolute -left-2 lg:left-[2%] xl:left-[4%] top-[40%] -translate-y-1/2 z-20 w-11 h-11 sm:w-12 sm:h-12 border border-[#00B5B4] text-[#00B5B4] bg-transparent hover:bg-white hover:text-[#00B5B4] hover:border-[#00B5B4] flex items-center justify-center transition-all duration-300 cursor-pointer shadow-xs hover:shadow-sm" aria-label="Quarto Anterior">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7" />
          </svg>
        </button>

        <!-- Botão Seta Direita (Seguinte) -->
        <button type="button" id="vblOtherRoomsNext" class="absolute -right-2 lg:right-[2%] xl:right-[4%] top-[40%] -translate-y-1/2 z-20 w-11 h-11 sm:w-12 sm:h-12 border border-[#00B5B4] text-[#00B5B4] bg-transparent hover:bg-white hover:text-[#00B5B4] hover:border-[#00B5B4] flex items-center justify-center transition-all duration-300 cursor-pointer shadow-xs hover:shadow-sm" aria-label="Próximo Quarto">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7" />
          </svg>
        </button>

        <!-- Track Container com overflow-hidden -->
        <div class="overflow-hidden px-4 lg:px-12">
          <div id="vblOtherRoomsTrack" class="flex transition-transform duration-500 ease-out gap-8 lg:gap-12 xl:gap-16">
            
            <?php foreach ( $other_rooms_list as $o_room ) : ?>
            <div class="vbl-other-room-card w-full md:w-[calc(50%-16px)] lg:w-[calc(50%-24px)] xl:w-[calc(50%-32px)] flex-shrink-0 flex flex-col items-start bg-transparent group">
              
              <!-- Fotografia Clicável -->
              <a href="<?php echo esc_url( $o_room['link'] ); ?>" class="block w-full aspect-[16/11] overflow-hidden bg-gray-100 shadow-xs mb-6 cursor-pointer" aria-label="<?php echo esc_attr( $o_room['title'] ); ?>">
                <img src="<?php echo esc_url( $o_room['thumb'] ); ?>" alt="<?php echo esc_attr( $o_room['title'] ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
              </a>

              <!-- Categoria -->
              <div class="flex items-center gap-3 mb-2">
                <div class="w-6 h-px bg-[#0da9a6]"></div>
                <span class="font-body text-[10px] xl:text-[11px] tracking-[2px] uppercase text-[#0da9a6] font-medium">
                  <?php echo esc_html( $o_room['cat'] ); ?>
                </span>
              </div>

              <!-- Título Clicável -->
              <h3 class="font-display text-[26px] sm:text-[30px] leading-tight uppercase text-[#0d5257] mb-3 group-hover:text-[#0da9a6] transition-colors">
                <a href="<?php echo esc_url( $o_room['link'] ); ?>" class="hover:underline decoration-[#0da9a6] decoration-1 underline-offset-4">
                  <?php echo esc_html( $o_room['title'] ); ?>
                </a>
              </h3>

              <!-- Descrição -->
              <p class="font-body font-light text-[13px] leading-relaxed text-[#333333] mb-6 max-w-[460px]">
                <?php echo esc_html( $o_room['desc'] ); ?>
              </p>

              <!-- Botão Ver Quarto -->
              <a href="<?php echo esc_url( $o_room['link'] ); ?>" class="vbl-btn-microsite group/btn">
                <span>VER QUARTO</span>
                <svg viewBox="0 0 12 12" fill="none">
                  <path d="M1 11L11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </a>

            </div>
            <?php endforeach; ?>

          </div>
        </div>

      </div>

    </div>
  </section>

  <!-- ==========================================
       7. BANNER DE FRASE FINAL
  =========================================== -->
  <section class="relative w-full py-28 lg:py-40 flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 w-full h-full">
      <img src="<?php echo esc_url( $frase_bg ); ?>" alt="Vista Quarto" class="w-full h-full object-cover filter brightness-[0.75]">
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

</main>

<!-- ==========================================
     SCRIPTS DO TEMPLATE
=========================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // 1. Slider da Galeria Principal
  const track = document.getElementById('vblQuartoGaleriaTrack');
  const prevBtn = document.getElementById('vblGaleriaPrev');
  const nextBtn = document.getElementById('vblGaleriaNext');

  if (track && prevBtn && nextBtn) {
    const slides = track.children;
    let currentIdx = 0;

    function updateGaleria() {
      track.style.transform = `translateX(-${currentIdx * 100}%)`;
    }

    nextBtn.addEventListener('click', function() {
      if (currentIdx < slides.length - 1) {
        currentIdx++;
      } else {
        currentIdx = 0;
      }
      updateGaleria();
    });

    prevBtn.addEventListener('click', function() {
      if (currentIdx > 0) {
        currentIdx--;
      } else {
        currentIdx = slides.length - 1;
      }
      updateGaleria();
    });
  }

  // 2. Slider de Outros Quartos Relacionados
  const otherTrack = document.getElementById('vblOtherRoomsTrack');
  const otherPrevBtn = document.getElementById('vblOtherRoomsPrev');
  const otherNextBtn = document.getElementById('vblOtherRoomsNext');
  const otherCards = document.querySelectorAll('.vbl-other-room-card');

  if (otherTrack && otherCards.length) {
    let otherIndex = 0;

    function getOtherCardsPerView() {
      return window.innerWidth >= 768 ? 2 : 1;
    }

    function getOtherMaxIndex() {
      const perView = getOtherCardsPerView();
      return Math.max(0, otherCards.length - perView);
    }

    function updateOtherSlider() {
      const card = otherCards[0];
      const cardWidth = card.getBoundingClientRect().width;
      const gap = window.innerWidth >= 1280 ? 64 : (window.innerWidth >= 1024 ? 48 : 32);
      const offset = otherIndex * (cardWidth + gap);
      otherTrack.style.transform = `translateX(-${offset}px)`;
    }

    if (otherNextBtn) {
      otherNextBtn.addEventListener('click', function() {
        const max = getOtherMaxIndex();
        if (otherIndex < max) {
          otherIndex++;
        } else {
          otherIndex = 0;
        }
        updateOtherSlider();
      });
    }

    if (otherPrevBtn) {
      otherPrevBtn.addEventListener('click', function() {
        const max = getOtherMaxIndex();
        if (otherIndex > 0) {
          otherIndex--;
        } else {
          otherIndex = max;
        }
        updateOtherSlider();
      });
    }

    window.addEventListener('resize', function() {
      const max = getOtherMaxIndex();
      if (otherIndex > max) {
        otherIndex = max;
      }
      updateOtherSlider();
    });
  }
});
</script>

<?php
// Inclui o Footer independente do Hotel
get_footer('hotel');

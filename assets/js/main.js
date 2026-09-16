/**
 * Vila Baleira — Main JavaScript
 * Mobile menu, sticky header, carousels, newsletter AJAX
 *
 * @package Vila_Baleira
 */

(function () {
  'use strict';

  /* ========================================
     Mobile Menu Toggle
     ======================================== */
  const menu = document.getElementById('mobileMenu');
  const closeBtn = document.getElementById('menuClose');
  const toggleBtns = document.querySelectorAll('.menu-toggle');

  function openMenu() {
    if (!menu) return;
    menu.classList.remove('translate-x-full');
    menu.classList.add('translate-x-0');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    if (!menu) return;
    menu.classList.add('translate-x-full');
    menu.classList.remove('translate-x-0');
    document.body.style.overflow = '';
  }

  toggleBtns.forEach(function (btn) {
    btn.addEventListener('click', openMenu);
  });

  if (closeBtn) {
    closeBtn.addEventListener('click', closeMenu);
  }

  if (menu) {
    menu.querySelectorAll('#mobileNav > a').forEach(function (a) {
      a.addEventListener('click', closeMenu);
    });
    // Also close on hotel card links
    menu.querySelectorAll('#mobileHotelCards a').forEach(function (a) {
      a.addEventListener('click', closeMenu);
    });
    // Also close on room card links
    menu.querySelectorAll('#mobileRoomsCards a').forEach(function (a) {
      a.addEventListener('click', closeMenu);
    });
  }

  /* ========================================
     Mobile Accordions (Hotéis & Rooms)
     ======================================== */
  function setupAccordion(toggleId, cardsId, arrowId) {
    var toggle = document.getElementById(toggleId);
    var cards = document.getElementById(cardsId);
    var arrow = document.getElementById(arrowId);
    if (!toggle || !cards) return;

    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      cards.classList.toggle('is-open');
      if (arrow) arrow.classList.toggle('is-open');
    });
  }

  setupAccordion('mobileHotelToggle', 'mobileHotelCards', 'mobileHotelArrow');
  setupAccordion('mobileRoomsToggle', 'mobileRoomsCards', 'mobileRoomsArrow');

  /* ========================================
     Sticky Header + Back to Top
     ======================================== */
  var hf = document.getElementById('headerFixo');
  var hh = document.getElementById('headerHotel');
  var bt = document.getElementById('backTop');

  var isBannerPage = hf && hf.classList.contains('pointer-events-none');

  if (bt) {
    bt.addEventListener('click', function (e) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  function handleScroll() {
    var sy = window.scrollY || window.pageYOffset || document.documentElement.scrollTop || 0;

    /* Only toggle header visibility on banner pages */
    if (hf && isBannerPage) {
      if (sy > 760) {
        hf.classList.remove('opacity-0', 'pointer-events-none');
        hf.classList.add('opacity-100');
      } else {
        hf.classList.add('opacity-0', 'pointer-events-none');
        hf.classList.remove('opacity-100');
      }
    }

    /* Hotel Microsite Header on Scroll */
    if (hh) {
      if (sy > 40) {
        hh.classList.add('is-scrolled');
      } else {
        hh.classList.remove('is-scrolled');
      }
    }

    if (bt) {
      if (sy > 250) {
        bt.classList.add('is-visible');
      } else {
        bt.classList.remove('is-visible');
      }
    }
  }

  window.addEventListener('scroll', handleScroll, { passive: true });
  handleScroll();

  /* ========================================
     Hotel Carousel — Desktop (cross-fade)
     ======================================== */
  (function () {
    var bigSlot = document.getElementById('hotelBigSlot');
    var smallSlot = document.getElementById('hotelSmallSlot');
    if (!bigSlot || !smallSlot) return;

    var bigImgs  = bigSlot.querySelectorAll('img:not(button img)');
    var smallImgs = smallSlot.querySelectorAll('img:not(button img)');
    var titleEl = document.getElementById('desktopHotelTitle');
    var textEl = document.getElementById('desktopHotelText');
    var linkEl = document.getElementById('desktopHotelLink');
    var dataEl = document.getElementById('vbl-hoteis-data');
    var payload = [];
    if (dataEl) {
      try { payload = JSON.parse(dataEl.textContent); } catch(e) {}
    }

    var current = 0;

    window.hotelSwap = function (dir) {
      var total = bigImgs.length;
      if (total === 0) return;

      current = dir === 'next' ? (current + 1) : (current - 1);
      current = (current + total) % total;
      var next = (current + 1) % total;

      bigImgs.forEach(function(img, i) {
        img.style.opacity = (i === current) ? '1' : '0';
      });
      smallImgs.forEach(function(img, i) {
        img.style.opacity = (i === next) ? '0.25' : '0';
      });

      if (payload.length > 0 && payload[current]) {
        if(titleEl) { titleEl.style.opacity = 0; setTimeout(function(){ titleEl.innerHTML = payload[current].title; titleEl.style.opacity = 1; }, 150); }
        if(textEl) { textEl.style.opacity = 0; setTimeout(function(){ textEl.innerHTML = payload[current].text; textEl.style.opacity = 1; }, 150); }
        if(linkEl) { linkEl.style.opacity = 0; setTimeout(function(){ linkEl.href = payload[current].link; linkEl.style.opacity = 1; }, 150); }
      }
    };
  })();

  /* ========================================
     Hotel Carousel — Mobile (slide + swipe)
     ======================================== */
  (function () {
    var slider = document.getElementById('hotelSliderMobile');
    if (!slider) return;

    var slides = slider.querySelectorAll('img[data-slide]');
    var titleEl = document.getElementById('mobileHotelTitle');
    var textEl = document.getElementById('mobileHotelText');
    var linkEl = document.getElementById('mobileHotelLink');
    var dataEl = document.getElementById('vbl-hoteis-data');
    var payload = [];
    if (dataEl) {
      try { payload = JSON.parse(dataEl.textContent); } catch(e) {}
    }
    var current = 0;

    function goTo(idx) {
      var total = slides.length;
      if (total === 0) return;
      current = (idx + total) % total;

      slides.forEach(function (s, i) {
        s.style.transform =
          i === current
            ? 'translateX(0)'
            : i < current
            ? 'translateX(-100%)'
            : 'translateX(100%)';
      });

      if (payload.length > 0 && payload[current]) {
        if(titleEl) { titleEl.style.opacity = 0; setTimeout(function(){ titleEl.innerHTML = payload[current].title; titleEl.style.opacity = 1; }, 150); }
        if(textEl) { textEl.style.opacity = 0; setTimeout(function(){ textEl.innerHTML = payload[current].text; textEl.style.opacity = 1; }, 150); }
        if(linkEl) { linkEl.style.opacity = 0; setTimeout(function(){ linkEl.href = payload[current].link; linkEl.style.opacity = 1; }, 150); }
      }
    }

    slider.querySelectorAll('button[data-dir]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        goTo(btn.dataset.dir === 'next' ? current + 1 : current - 1);
      });
    });

    var startX = 0;
    slider.addEventListener(
      'touchstart',
      function (e) {
        startX = e.touches[0].clientX;
      },
      { passive: true }
    );
    slider.addEventListener(
      'touchend',
      function (e) {
        var diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) goTo(diff > 0 ? current + 1 : current - 1);
      },
      { passive: true }
    );
  })();

  /* ========================================
     News Carousel — Desktop (cross-fade)
     ======================================== */
  (function () {
    var slider = document.getElementById('newsSlider');
    if (!slider) return;

    var container = slider.querySelector('.flex');
    var cards = Array.from(slider.querySelectorAll('[data-news]'));
    var prev = document.getElementById('newsPrev');
    var next = document.getElementById('newsNext');
    if (!container || cards.length < 3) return;

    var data = cards.map(function (c) {
      return {
        img: c.querySelector('img').src,
        title: c.querySelectorAll('p')[0].textContent,
        desc: c.querySelectorAll('p')[1].textContent,
        url: c.href,
      };
    });

    var order = [0, 1, 2];
    var animating = false;

    function render(animate) {
      cards.forEach(function (card, slot) {
        var d = data[order[slot]];
        var img = card.querySelector('img');
        var ps = card.querySelectorAll('p');

        card.style.transition = animate ? 'opacity 300ms ease-out' : 'none';
        card.style.opacity = slot === 1 ? '1' : '0.25';

        img.src = d.img;
        ps[0].textContent = d.title;
        ps[1].textContent = d.desc;
        card.href = d.url;
      });
    }

    function shift(dir) {
      if (animating) return;
      animating = true;

      if (dir === 'next') {
        order = [order[1], order[2], order[0]];
      } else {
        order = [order[2], order[0], order[1]];
      }

      render(true);
      setTimeout(function () {
        animating = false;
      }, 350);
    }

    render(false);

    if (prev) prev.addEventListener('click', function () { shift('prev'); });
    if (next) next.addEventListener('click', function () { shift('next'); });
  })();

  /* ========================================
     News Carousel — Mobile (button scroll)
     ======================================== */
  (function () {
    var mobileSlider = document.querySelector('.lg\\:hidden .snap-x');
    var prevBtn = document.getElementById('newsPrevMobile');
    var nextBtn = document.getElementById('newsNextMobile');
    if (!mobileSlider || !prevBtn || !nextBtn) return;

    function getCardWidth() {
      var firstCard = mobileSlider.querySelector('a');
      if (!firstCard) return mobileSlider.offsetWidth * 0.75;
      return firstCard.offsetWidth + 24; /* width + gap-6 (24px) */
    }

    prevBtn.addEventListener('click', function () {
      mobileSlider.scrollBy({ left: -getCardWidth(), behavior: 'smooth' });
    });

    nextBtn.addEventListener('click', function () {
      mobileSlider.scrollBy({ left: getCardWidth(), behavior: 'smooth' });
    });
  })();

  /* ========================================
     Newsletter Form AJAX
     ======================================== */
  document.querySelectorAll('.vbl-newsletter-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var emailInput = form.querySelector('input[type="email"]');
      var messageEl = form.querySelector('.vbl-newsletter-message');
      var submitBtn = form.querySelector('button[type="submit"]');

      if (!emailInput || !emailInput.value) return;

      // Check if vblData exists (WordPress AJAX)
      if (typeof vblData === 'undefined') {
        if (messageEl) {
          messageEl.textContent = 'Obrigado pela subscrição!';
          messageEl.classList.remove('hidden');
          messageEl.classList.add('text-verde');
        }
        return;
      }

      submitBtn.disabled = true;

      var formData = new FormData();
      formData.append('action', 'vbl_newsletter');
      formData.append('email', emailInput.value);
      formData.append('nonce', vblData.nonce);

      fetch(vblData.ajaxUrl, {
        method: 'POST',
        body: formData,
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (messageEl) {
            messageEl.textContent = data.data.message || 'Obrigado!';
            messageEl.classList.remove('hidden');
            messageEl.classList.add(data.success ? 'text-verde' : 'text-red-600');
          }
          if (data.success) {
            emailInput.value = '';
          }
        })
        .catch(function () {
          if (messageEl) {
            messageEl.textContent = 'Erro ao processar. Tente novamente.';
            messageEl.classList.remove('hidden');
            messageEl.classList.add('text-red-600');
          }
        })
        .finally(function () {
          submitBtn.disabled = false;
        });
    });
  });

  /* ========================================
     Contact Forms AJAX (General, Hotel & Gift Card)
     ======================================== */
  var contactForms = document.querySelectorAll('#vblContactForm, #vblContactosHotelForm, .vbl-contact-form');
  contactForms.forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var submitBtn = form.querySelector('button[type="submit"]');
      var btnSpan = submitBtn ? submitBtn.querySelector('span') : null;
      var originalBtnText = btnSpan ? btnSpan.textContent : '';
      var feedbackEl = form.querySelector('#vblContactFeedback, .vbl-form-feedback') || form.querySelector('.vbl-feedback');

      if (!feedbackEl) {
        feedbackEl = document.createElement('div');
        feedbackEl.className = 'text-[14px] font-medium py-2';
        form.appendChild(feedbackEl);
      }

      if (typeof vblData === 'undefined') {
        feedbackEl.textContent = 'Mensagem enviada com sucesso!';
        feedbackEl.className = 'text-[14px] font-medium py-2 text-[#0da9a6]';
        feedbackEl.classList.remove('hidden');
        form.reset();
        return;
      }

      if (submitBtn) {
        submitBtn.disabled = true;
        if (btnSpan) btnSpan.textContent = 'A ENVIAR...';
      }

      feedbackEl.className = 'text-[14px] font-medium py-2 text-gray-500';
      feedbackEl.textContent = 'A enviar a sua mensagem...';
      feedbackEl.classList.remove('hidden');

      var formData = new FormData(form);
      formData.append('action', 'vbl_contact_submit');
      formData.append('nonce', vblData.nonce);

      fetch(vblData.ajaxUrl, {
        method: 'POST',
        body: formData,
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (data && data.success) {
            feedbackEl.className = 'text-[14px] font-medium py-2 text-green-600';
            feedbackEl.textContent = (data.data && data.data.message) ? data.data.message : 'Obrigado! A sua mensagem foi enviada com sucesso.';
            form.reset();
          } else {
            feedbackEl.className = 'text-[14px] font-medium py-2 text-red-600';
            feedbackEl.textContent = (data && data.data && data.data.message) ? data.data.message : 'Ocorreu um erro ao enviar. Por favor tente novamente.';
          }
        })
        .catch(function () {
          feedbackEl.className = 'text-[14px] font-medium py-2 text-red-600';
          feedbackEl.textContent = 'Ocorreu um erro de ligação. Por favor tente novamente.';
        })
        .finally(function () {
          if (submitBtn) {
            submitBtn.disabled = false;
            if (btnSpan) btnSpan.textContent = originalBtnText;
          }
        });
    });
  });

  /* ========================================
     Eventos & Salas Form AJAX
     ======================================== */
  var eventosForm = document.getElementById('vblEventosForm');
  if (eventosForm) {
    eventosForm.addEventListener('submit', function (e) {
      e.preventDefault();

      var submitBtn = eventosForm.querySelector('button[type="submit"]');
      var btnSpan = submitBtn ? submitBtn.querySelector('span') : null;
      var originalBtnText = btnSpan ? btnSpan.textContent : '';
      var feedbackEl = document.getElementById('vblEventosFeedback') || eventosForm.querySelector('#vblEventosFeedback');

      if (!feedbackEl) {
        feedbackEl = document.createElement('div');
        feedbackEl.className = 'text-[14px] font-medium py-2';
        eventosForm.appendChild(feedbackEl);
      }

      if (typeof vblData === 'undefined') {
        feedbackEl.textContent = 'Pedido de orçamento enviado com sucesso!';
        feedbackEl.className = 'text-[14px] font-medium py-2 text-[#0da9a6]';
        feedbackEl.classList.remove('hidden');
        eventosForm.reset();
        return;
      }

      if (submitBtn) {
        submitBtn.disabled = true;
        if (btnSpan) btnSpan.textContent = 'A ENVIAR...';
      }

      feedbackEl.className = 'text-[14px] font-medium py-2 text-gray-500';
      feedbackEl.textContent = 'A enviar pedido de orçamento...';
      feedbackEl.classList.remove('hidden');

      var formData = new FormData(eventosForm);
      formData.append('action', 'vbl_eventos_submit');
      formData.append('nonce', vblData.nonce);

      fetch(vblData.ajaxUrl, {
        method: 'POST',
        body: formData,
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (data && data.success) {
            feedbackEl.className = 'text-[14px] font-medium py-2 text-green-600';
            feedbackEl.textContent = (data.data && data.data.message) ? data.data.message : 'Obrigado! O seu pedido de orçamento foi enviado com sucesso.';
            eventosForm.reset();
          } else {
            feedbackEl.className = 'text-[14px] font-medium py-2 text-red-600';
            feedbackEl.textContent = (data && data.data && data.data.message) ? data.data.message : 'Ocorreu um erro ao enviar. Por favor tente novamente.';
          }
        })
        .catch(function () {
          feedbackEl.className = 'text-[14px] font-medium py-2 text-red-600';
          feedbackEl.textContent = 'Ocorreu um erro de ligação. Por favor tente novamente.';
        })
        .finally(function () {
          if (submitBtn) {
            submitBtn.disabled = false;
            if (btnSpan) btnSpan.textContent = originalBtnText;
          }
        });
    });
  }

  /* ========================================
     Mega Menu — Hover on Hotéis
     ======================================== */
  (function () {
    var megaMenu = document.getElementById('megaMenu');
    if (!megaMenu) return;
    var triggers = document.querySelectorAll('[data-mega-trigger]');
    var arrows = document.querySelectorAll('[data-mega-trigger] .vbl-dropdown-arrow');
    var megaTimeout;

    function showMega(e) {
      clearTimeout(megaTimeout);
      var customTop = e.currentTarget.getAttribute('data-mega-top');
      var variant = e.currentTarget.getAttribute('data-mega-variant');
      var base = customTop ? parseInt(customTop, 10) : 81;
      var adminBar = document.getElementById('wpadminbar');
      if (adminBar && getComputedStyle(adminBar).display !== 'none') {
        base += adminBar.offsetHeight;
      }
      megaMenu.style.top = base + 'px';
      if (variant === 'dark') {
        megaMenu.classList.add('mega-dark');
      } else {
        megaMenu.classList.remove('mega-dark');
      }
      megaMenu.style.maxHeight = '500px';
      megaMenu.style.opacity = '1';
      megaMenu.style.pointerEvents = 'auto';
      arrows.forEach(function (a) { a.classList.add('is-rotated'); });
    }

    function hideMega() {
      megaTimeout = setTimeout(function () {
        megaMenu.style.maxHeight = '0';
        megaMenu.style.opacity = '0';
        megaMenu.style.pointerEvents = 'none';
        megaMenu.classList.remove('mega-dark');
        arrows.forEach(function (a) { a.classList.remove('is-rotated'); });
      }, 150);
    }

    triggers.forEach(function (t) {
      t.addEventListener('mouseenter', showMega);
      t.addEventListener('mouseleave', hideMega);
    });
    megaMenu.addEventListener('mouseenter', function () { clearTimeout(megaTimeout); });
    megaMenu.addEventListener('mouseleave', hideMega);
  })();

  /* ========================================
     Contact Page — Interactive Hotel Carousel
     ======================================== */
  (function () {
    var dataEl = document.getElementById('vbl-hotels-contact-data');
    var prevBtn = document.getElementById('vblHotelPrevBtn');
    var nextBtn = document.getElementById('vblHotelNextBtn');
    if (!dataEl || !prevBtn || !nextBtn) return;

    var hotels = [];
    try {
      hotels = JSON.parse(dataEl.textContent);
    } catch (e) {
      return;
    }
    if (!hotels.length) return;

    var currentIndex = 0;
    var titleEl = document.getElementById('vblHotelTitle');
    var subtitleEl = document.getElementById('vblHotelSubtitle');
    var moradaEl = document.getElementById('vblHotelMorada');
    var telefoneEl = document.getElementById('vblHotelTelefone');
    var emailEl = document.getElementById('vblHotelEmail');
    var fotoEl = document.getElementById('vblHotelFoto');
    var mapaEl = document.getElementById('vblHotelMapa');
    var infoBlock = document.getElementById('vblHotelInfoBlock');

    function updateHotel(index) {
      currentIndex = (index + hotels.length) % hotels.length;
      var item = hotels[currentIndex];

      if (titleEl) titleEl.style.opacity = '0';
      if (infoBlock) infoBlock.style.opacity = '0';
      if (fotoEl) fotoEl.style.opacity = '0';
      if (mapaEl) mapaEl.style.opacity = '0';

      setTimeout(function () {
        if (titleEl && item.title) titleEl.textContent = item.title;
        if (subtitleEl && item.subtitle) subtitleEl.textContent = item.subtitle;
        if (moradaEl && item.morada) moradaEl.textContent = item.morada;
        if (telefoneEl && item.telefone) telefoneEl.textContent = item.telefone;
        if (emailEl && item.email) emailEl.textContent = item.email;
        if (fotoEl && item.foto) fotoEl.src = item.foto;
        if (mapaEl && item.mapa) mapaEl.src = item.mapa;

        if (titleEl) titleEl.style.opacity = '1';
        if (infoBlock) infoBlock.style.opacity = '1';
        if (fotoEl) fotoEl.style.opacity = '1';
        if (mapaEl) mapaEl.style.opacity = '1';
      }, 150);
    }

    prevBtn.addEventListener('click', function () {
      updateHotel(currentIndex - 1);
    });

    nextBtn.addEventListener('click', function () {
      updateHotel(currentIndex + 1);
    });
  })();

  /* ========================================
     Experiências Page — Dynamic Carousel Handler (Figma Design)
     ======================================== */
  (function () {
    var dataEl = document.getElementById('vbl-experiencias-data');
    if (!dataEl) return;

    var items = [];
    try {
      items = JSON.parse(dataEl.textContent);
    } catch (e) {
      console.error('Experiências Carousel: failed to parse JSON', e);
      return;
    }
    if (!items || !items.length) return;

    var currentIndex = 0;

    // Desktop Elements
    var titleEl = document.getElementById('vblExpTitle');
    var linkTitleEl = document.getElementById('vblExpLinkTitle');
    var textEl = document.getElementById('vblExpText');
    var imgEl = document.getElementById('vblExpImg');
    var linkImgEl = document.getElementById('vblExpLinkImg');
    var prevBtn = document.getElementById('vblExpPrevBtn');
    var nextBtn = document.getElementById('vblExpNextBtn');

    // Mobile Elements
    var titleElMob = document.getElementById('vblExpTitleMob');
    var linkTitleElMob = document.getElementById('vblExpLinkMobTitle');
    var textElMob = document.getElementById('vblExpTextMob');
    var imgElMob = document.getElementById('vblExpImgMob');
    var linkImgElMob = document.getElementById('vblExpLinkMobImg');
    var prevBtnMob = document.getElementById('vblExpPrevBtnMob');
    var nextBtnMob = document.getElementById('vblExpNextBtnMob');

    function updateExp(index) {
      currentIndex = (index + items.length) % items.length;
      var item = items[currentIndex];

      // Desktop Fade Out
      if (titleEl) titleEl.style.opacity = '0';
      if (textEl) textEl.style.opacity = '0';
      if (imgEl) imgEl.style.opacity = '0';

      // Mobile Fade Out
      if (titleElMob) titleElMob.style.opacity = '0';
      if (textElMob) textElMob.style.opacity = '0';
      if (imgElMob) imgElMob.style.opacity = '0';

      setTimeout(function () {
        // Desktop Update
        if (titleEl) titleEl.innerHTML = item.title || '';
        if (textEl) textEl.textContent = item.text || '';
        if (imgEl && item.img) imgEl.src = item.img;

        // Mobile Update
        if (titleElMob) titleElMob.innerHTML = item.title || '';
        if (textElMob) textElMob.textContent = item.text || '';
        if (imgElMob && item.img) imgElMob.src = item.img;

        // Desktop Fade In
        if (titleEl) titleEl.style.opacity = '1';
        if (textEl) textEl.style.opacity = '1';
        if (imgEl) imgEl.style.opacity = '1';

        // Mobile Fade In
        if (titleElMob) titleElMob.style.opacity = '1';
        if (textElMob) textElMob.style.opacity = '1';
        if (imgElMob) imgElMob.style.opacity = '1';
      }, 150);
    }

    if (prevBtn) prevBtn.addEventListener('click', function (e) { e.preventDefault(); updateExp(currentIndex - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function (e) { e.preventDefault(); updateExp(currentIndex + 1); });
    if (prevBtnMob) prevBtnMob.addEventListener('click', function (e) { e.preventDefault(); updateExp(currentIndex - 1); });
    if (nextBtnMob) nextBtnMob.addEventListener('click', function (e) { e.preventDefault(); updateExp(currentIndex + 1); });
  })();

})();

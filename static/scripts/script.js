'use strict';

import './modules/_lightbox.js';

document.addEventListener(
  'DOMContentLoaded',
  () => {
    // グローバルナビゲーション開閉
    const gnavToggle = document.getElementById('gnav-toggle');
    const gnav = document.getElementById('gnav');
    if (gnavToggle && gnav) {
      const mediaQueryList = window.matchMedia(
        '(min-width: calc(1000px + 1.25rem * 2)'
      );
      const setHeaderVisibility = (mq) => {
        gnav.setAttribute('aria-hidden', mq.matches ? 'false' : 'true');
      };
      mediaQueryList.addEventListener('change', setHeaderVisibility);
      setHeaderVisibility(mediaQueryList);
      if (!mediaQueryList.matches) {
        // リンクをクリックしたらナビゲーションを閉じる
        const gnavItems = gnav.querySelectorAll('a[href^="#"]');
        gnavItems.forEach((item) => {
          item.addEventListener('click', () => {
            gnavToggle.setAttribute('aria-expanded', false);
            gnav.setAttribute('aria-hidden', true);
          });
        });
      }

      gnavToggle.addEventListener('click', () => {
        gnavToggle.setAttribute(
          'aria-expanded',
          !(gnavToggle.getAttribute('aria-expanded') === 'true')
        );
        gnav.setAttribute(
          'aria-hidden',
          !(gnav.getAttribute('aria-hidden') === 'true')
        );
      });
    }
  },
  false
);

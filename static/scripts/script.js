'use strict';

import './modules/_lightbox.js';

document.addEventListener(
  'DOMContentLoaded',
  () => {
    // グローバルナビゲーション開閉
    const gnavToggle = document.getElementById('gnav-toggle');
    const gnav = document.getElementById('gnav');
    if (gnavToggle && gnav) {
      if (window.matchMedia('(min-width: calc(1000px + 1.25rem * 2)').matches) {
        gnav.setAttribute('aria-hidden', false);
      } else {
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

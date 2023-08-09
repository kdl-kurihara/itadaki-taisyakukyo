'use strict';

import './modules/_lightbox.js';

document.addEventListener(
  'DOMContentLoaded',
  () => {
    // グローバルナビゲーション開閉
    const gnavToggle = document.getElementById('gnav-toggle');
    const gnav = document.getElementById('gnav');
    if (gnavToggle && gnav) {
      // トグルボタン
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

      // ページ内リンクをクリックしたらナビゲーションを閉じる
      const gnavItems = gnav.querySelectorAll('a');
      gnavItems.forEach((item) => {
        item.addEventListener('click', () => {
          gnavToggle.setAttribute('aria-expanded', false);
          gnav.setAttribute('aria-hidden', true);
        });
      });
    }
  },
  false
);

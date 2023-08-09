'use strict';

import { Luminous, LuminousGallery } from 'luminous-lightbox';

document.addEventListener(
  'DOMContentLoaded',
  () => {
    // 画像
    const images = document.querySelectorAll('[data-lightbox]');
    images.forEach((image) => {
      new Luminous(image);
    });

    if (window.matchMedia('(min-width: 560px)').matches) {
      const images = document.querySelectorAll('.wp-block-image > img');
      if (images.length > 0) {
        images.forEach((img) => {
          const a = document.createElement('a');
          a.setAttribute('href', img.getAttribute('src'));
          img.parentNode.insertBefore(a, img);
          a.appendChild(img);
          new Luminous(a);
        });
      }
    }

    // ギャラリー
    const galleries = document.querySelectorAll(
      '.wp-block-gallery, [data-lightbox-gallery]'
    );
    if (galleries.length > 0) {
      galleries.forEach((gallery) => {
        const galleryItems = gallery.querySelectorAll('a');
        new LuminousGallery(
          galleryItems,
          {},
          {
            caption: (trigger) => {
              // キャプションが入力されていたら表示
              const caption = trigger.nextSibling;
              if (!caption) return '';

              return caption.innerText || '';
            },
          }
        );
      });
    }
  },
  false
);

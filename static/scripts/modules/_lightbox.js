'use strict';

import PhotoSwipeLightbox from 'photoswipe/lightbox';
import PhotoSwipe from 'photoswipe';

document.addEventListener(
  'DOMContentLoaded',
  () => {
    const lightbox = new PhotoSwipeLightbox({
      gallery: '#gallery',
      children: 'a',
      pswpModule: PhotoSwipe,
    });
    lightbox.init();
  },
  false
);

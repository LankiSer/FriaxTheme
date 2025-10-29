jQuery(document).ready(function ($) {
  const swiperThumbs = new Swiper(".gallery-thumbs", {
    el: ".gallery-thumbs",
    direction: "horizontal",
    slidesPerView: "auto",
    lazy: false,
    speed: 700,

    breakpoints: {
      319: {
        direction: "horizontal",
      },
      700: {
        direction: "horizontal",
      },
      701: {
        direction: "horizontal",
      },
      1920: {
        direction: "horizontal",
      },
    },
  });

  const swiperProduct = new Swiper(".gallery-top", {
    direction: "horizontal",
    slidesPerView: 1,
    observer: true,
    observeParents: true,
    spaceBetween: 10,
    navigation: {
      nextEl: ".swiper-button-next-thumbs",
      prevEl: ".swiper-button-prev-thumbs",
    },
    pagination: {
      el: ".swiper-pagination-gallery-top",
      clickable: "true",
    },
    speed: 700,
    autoplay: {
      delay: 3000,
    },
    breakpoints: {
      319: {
        direction: "horizontal",
      },
      700: {
        direction: "horizontal",
      },
      701: {
        direction: "horizontal",
      },
      1920: {
        direction: "horizontal",
      },
    },
    thumbs: {
      swiper: swiperThumbs,
    },
  });
});

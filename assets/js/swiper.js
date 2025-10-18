jQuery(function($){
	const swiperVars = new Swiper('.single-prices .vars', {
		direction: 'horizontal',
		navigation: {
			nextEl: '.single-prices .vars-holder .swiper-button-next',
			prevEl: '.single-prices .vars-holder .swiper-button-prev',
		},
		spaceBetween: 30,
		speed: 500,
		pagination: {
			el: '.single-prices .vars-holder .swiper-pagination',
			type: 'bullets',
		  	clickable:true,
		},
		breakpoints: {
			0: {
				slidesPerView: 1,
				spaceBetween: 10,
			},
			600: {
				slidesPerView: 2,
				spaceBetween: 20,
			},
			996: {
				slidesPerView: 3,
				spaceBetween: 20,
			},
			1200: {
				slidesPerView: 4,
				spaceBetween: 30,
			},
		},
	});

})




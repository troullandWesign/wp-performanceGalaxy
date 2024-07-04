import Swiper, { Autoplay, Navigation, Pagination, EffectCoverflow } from 'swiper';
Swiper.use([Navigation, Pagination, Autoplay, EffectCoverflow]);

class home {
	namespace = 'home';

	afterEnter = data => {
		console.log('Home.js');
		var swiper = new Swiper(".mySwiper", {
			effect: "coverflow",
			grabCursor: true,
			centeredSlides: true,
			initialSlide: 1,
			slidesPerView: "auto",
			coverflowEffect: {
			  rotate: 0,
			  stretch: 0,
			  depth: 150,
			  modifier: 2.5,
			  slideShadows: true,
			},		  
		  });
		
	};
}

export default new home();
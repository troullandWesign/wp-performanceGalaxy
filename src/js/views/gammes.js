import Swiper, { Autoplay, Navigation, Pagination, EffectCoverflow } from 'swiper';
Swiper.use([Navigation, Pagination, Autoplay, EffectCoverflow]);

class gammes {
	namespace = 'gammes';

	afterEnter = data => {
		console.log('Gamme.js');
		var swiper = new Swiper(".mySwiper", {
			effect: "coverflow",
			grabCursor: true,
			centeredSlides: true,
			
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

export default new gammes();
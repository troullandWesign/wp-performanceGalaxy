import Swiper, { Navigation, Pagination } from 'swiper'
Swiper.use([Navigation, Pagination])

class home {
	namespace = 'home';

	afterEnter = data => {
		console.log('Home.js');
		
	};
}

export default new home();
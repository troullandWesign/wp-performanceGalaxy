import barba from '@barba/core'


/*Modules*/
import prototype from '../prototype'
import { gsap } from 'gsap'
import { ScrollTrigger } from "gsap/ScrollTrigger"
import Swiper, { Navigation, Pagination } from 'swiper'
gsap.registerPlugin(ScrollTrigger)
Swiper.use([Navigation, Pagination])
import LocomotiveScroll from 'locomotive-scroll';
//const locomotiveScroll = new LocomotiveScroll();

/*Components*/


/*Views*/
import home from '../views/home'
import offres from '../views/offres'


class Spa {
    constructor () {
        this.loco = null;
        barba.init({
            prefetchIgnore: true,
            preventRunning: true,
            sync: false,
            timeout: 0,
            debug: false,
            logLevel: 'error',
            views: [
                home,
                offres
            ],
            transitions: [
                {
                    name: 'opacity-transition',
                    leave(data) {
                        return new Promise(resolve => {
                            gsap.to(data.current.container, 
                                {
                                    duration: 1,
                                    opacity: 0,
                                    '-webkit-filter':'blur(2px)',
                                    filter: 'blur(2px)',
                                    ease: 'Power2.easeIn',
                                    onComplete: resolve
                                }
                            )
                        });
                    },
                    enter: (data) => {
                        data.next.container.style.opacity = "0";
                        data.next.container.style.filter = 'blur(2px)';
                        window.scrollTo(0, 0);
                        gsap.to(data.next.container, 
                            {
                                '-webkit-filter':'blur(0px)',
                                filter: 'blur(0px)',
                                duration: 1,
                                opacity: 1,
                                ease: 'Power2.easeOut',
                                onComplete: () => {
                                    data.next.container.style.filter = null
                                }
                            }
                        )
                    },
                }
            ],
            requestError: (trigger, action, url, response) => {
                console.log('*Request Error*')
                console.log(url)
                console.log(response)
                console.log('***************')
            
                // prevent Barba from redirecting the user to the requested URL
                // this is equivalent to e.preventDefault() in this context
                return false;
            },
        });

        //Function calls on barba hooks
        //On browser load
        barba.hooks.once(
            this.loadingApp()
        )

        //On page load (includes first load)
        barba.hooks.afterEnter((datas) => {
            this.pageLoad()
            this.loco = new LocomotiveScroll();
        });

        //On page load (except first load)
        barba.hooks.after((datas) => {
            this.newPageLoad(datas)
        });

        //On page leave (transition before next page)
        barba.hooks.beforeLeave((datas) => {
            this.loco.destroy()
        });

    }


    /**
     * Hook functions
     */
    loadingApp(){
        //console.log('*APP first load*')

        //Init polyfill

        //Add serialize method to HTMLFormElement
        HTMLFormElement.prototype.serialize = prototype.FormElement.serialize;

        //Bind listeners
        
        window.onresize = this.handleResize.bind(this);

        //Define currentDevice to watch resolution/device change
        if (window.innerWidth < 1200) {
            this.currentDevice = 'mobile';
        }
        else {
            this.currentDevice = 'desktop';
        }

        //Init global components
        //Animations.global()
        //etc.

        //Call functions
        
    }

    pageLoad(){
        /**
        * Functions called
        * after every page load
        */

        //Functions
        function pageInit() {
            //Functions calls
      

            //mobile only
            if(window.innerWidth < 1200){

            }
            //desktop only
            if(window.innerWidth >= 1200){
                
            }
        }
        
        pageInit()

    }

    newPageLoad(datas){
        /**
        * Functions called
        * after every page load
        * except first load
        */

        //Components refresh
        //Animations.globalSwipers()

        //DOM refresh
        let parser = new DOMParser();
		let nextDom = parser.parseFromString(datas.next.html, 'text/html')
        let currentDom = parser.parseFromString(datas.current.html, 'text/html')

        //Current elements
        /*let currentMain = document.querySelector('nav.nav-main ul')
        let currentCat = document.querySelector('nav.nav-categories ul')
        let currentOther = document.querySelector('nav.nav-others ul')
        let currentInfos = document.querySelector('nav.infos ul')*/
        let scripts = document.querySelector('.load-scripts')

        //Replace with new content
        /*currentMain.innerHTML = nextDom.querySelector('nav.nav-main ul').innerHTML
        currentCat.innerHTML = nextDom.querySelector('nav.nav-categories ul').innerHTML
        currentOther.innerHTML = nextDom.querySelector('nav.nav-others ul').innerHTML
        currentInfos.innerHTML = nextDom.querySelector('nav.infos ul').innerHTML*/
        scripts.innerHTML = nextDom.querySelector('.load-scripts').innerHTML
    }



    /**
     * Other functions
     */
    
    handleResize() {
        this.updateCssVars();

        //Device switch
        if (window.innerWidth < 1200) {
            var newDevice = 'mobile';
        }
        else{
            var newDevice = 'desktop';
        }
        
        if(newDevice != this.currentDevice){
            this.currentDevice = newDevice;

            if (window.innerWidth < 1200) {
                console.log('switching to mobile')
            }
            else {
                console.log('switching to desktop')
            }
        }
    }

    updateCssVars(){
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }
}

export default Spa
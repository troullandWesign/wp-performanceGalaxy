//Config
import { gsap } from 'gsap'
import Spa from './barbaSpa'

class Preloader {
  constructor () {
    this.body = document.body
    //this.el = document.querySelector('#preloader')
  }

  init() {
    this.appLoad()
    //this.animateIn()
    this.done()
  }

  
  appLoad = () => { //Functions to call on app load, before barba
    /**
     * Can write some code here
     * to be executed before almost everything
     * barba app is not working yet
    */

    //Dynamic viewport unit for mobile
    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
    var viewportHeight = window.innerHeight;
    document.body.style.setProperty('--viewport-height', `${viewportHeight}px`);
  }

  animateIn = () => { //Preloader animations
    gsap.to(this.el.querySelector('img'), {
      duration: 0.7,
      opacity: 1,
      scale: 1,
      onComplete: this.animateOut
    })
  }

  animateOut = () => {
    gsap.to(this.el, {
      duration: 0.6,
      opacity: 0,
      delay: 0.2,
      onComplete: this.done
    })
  }

  done = () => {
    //this.el.parentNode.removeChild(this.el)
    this.body.classList.remove('is-loading')
    const barbaSpa = new Spa()
  }
}

export default new Preloader()
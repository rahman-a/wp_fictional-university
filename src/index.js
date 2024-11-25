import '../css/style.scss'

// Our modules / classes
import MobileMenu from './modules/MobileMenu'
import HeroSlider from './modules/HeroSlider'
import GoogleAPI from './modules/GoogleMap'
import Search from './modules/Search'
import SearchV2 from './modules/search-v2'

// Instantiate a new object using our modules/classes
document.addEventListener('DOMContentLoaded', () => {
  const mobileMenu = new MobileMenu()
  const heroSlider = new HeroSlider()
  const googleApi = new GoogleAPI()
  // const search = new Search()
  // search.trigger()
  const searchv2 = new SearchV2()
  searchv2.trigger()
})

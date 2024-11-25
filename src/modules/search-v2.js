// @ts-nocheck
class Search {
  constructor() {
    this.bodyElement = document.querySelector('body')
    this.seachContainer = document.getElementById('search-container')
    this.searchTrigger = document.querySelectorAll('.js-search-trigger')
    this.searchClose = document.getElementById('search-close')
    this.inputField = document.getElementById('search-term')
    this.searchOutput = document.getElementById('search-output')
    this.typingTimeout
    this.previousValue
    this.isTyping = false
    this.isOverlayOpen = false
  }

  // events

  trigger() {
    this.searchTrigger?.forEach((e) => {
      e.addEventListener('click', this.openSearchOverlay.bind(this))
    })
    this.searchClose?.addEventListener(
      'click',
      this.closeSearchOverlay.bind(this)
    )
    document.addEventListener('keydown', this.keypressDispatcher.bind(this))
    this.inputField?.addEventListener('keydown', this.keyTyping.bind(this))
  }

  // methods

  keyTyping(e) {
    if (this.inputField.value !== this.previousValue) {
      clearTimeout(this.typingTimeout)
      if (!this.isTyping && this.inputField.value) {
        this.searchOutput.innerHTML = "<div class='spinner-loader'></div>"
        this.isTyping = true
      }
      this.typingTimeout = setTimeout(() => {
        this.getResult()
      }, 1000)
    }

    this.previousValue = this.inputField.value

    const clearSpinner = setTimeout(() => {
      if (this.inputField.value === '') {
        this.searchOutput.innerHTML = ''
        this.isTyping = false
      }
      clearTimeout(clearSpinner)
    }, 500)
  }

  async getResult() {
    const value = this.inputField.value
    if (!value) return
    const output = await this.fetchData(value)
    this.searchOutput.innerHTML = this.renderHtml(output)
    this.isTyping = false
  }

  keypressDispatcher(event) {
    if (event.key === 's' && !this.isOverlayOpen && !this.isInputFocused()) {
      this.openSearchOverlay()
    }
    if (event.key === 'Escape' && this.isOverlayOpen) {
      this.closeSearchOverlay()
    }
  }

  openSearchOverlay(e) {
    e.preventDefault()
    this.seachContainer?.classList.add('search-overlay--active')
    this.bodyElement?.classList.add('body-no-scroll')
    this.isOverlayOpen = true

    const focusTimout = setTimeout(() => {
      this.inputField?.focus()
      clearTimeout(focusTimout)
    }, 350)
  }

  closeSearchOverlay() {
    this.seachContainer?.classList.remove('search-overlay--active')
    this.bodyElement?.classList.remove('body-no-scroll')
    this.inputField.value = ''
    this.searchOutput.innerHTML = ''
    this.isOverlayOpen = false
  }

  isInputFocused() {
    const focusedElement = document.activeElement
    return (
      focusedElement.tagName === 'INPUT' ||
      focusedElement.tagName === 'TEXTAREA' ||
      focusedElement.tagName === 'SELECT'
    )
  }

  renderHtml(data) {
    /////////// RENDER GENERAL INFORAMTION ////////////////////
    const generalInfoList = this.renderListItems({
      data: data['generalInfo'],
      notfound_msg: 'No general information found',
      header: 'General Information',
      type: 'generalInfo',
    })
    /////////// RENDER PROGRAMS ////////////////////
    const programsList = this.renderListItems({
      data: data['programs'],
      notfound_msg:
        'No programs found <a href="/programs">list all programs</a>',
      header: 'Programs',
      type: 'programs',
    })
    /////////// RENDER PROFESSORS ////////////////////
    const professorsList = this.renderListItems({
      data: data['professors'],
      notfound_msg: 'No professors found',
      header: 'Professors',
      type: 'professors',
    })
    /////////// RENDER CAMPUSES ////////////////////
    const campusesList = this.renderListItems({
      data: data['campuses'],
      notfound_msg:
        'No campuses found <a href="/campuses">list all campuses</a>',
      header: 'Campuses',
      type: 'campuses',
    })
    /////////// RENDER EVENTS ////////////////////
    const eventsList = this.renderListItems({
      data: data['events'],
      notfound_msg: 'No events found <a href="/events">list all events</a>',
      header: 'Events',
      type: 'events',
    })
    /////////// RETURN FINAL HTML ////////////////////
    return /*html*/ `
      <div class="row">
        <div class="one-third">${generalInfoList}</div>
        <div class="one-third">${programsList} ${professorsList}</div>
        <div class="one-third">${campusesList} ${eventsList}</div>
      </div>
    `
  }

  renderListItems({ data, notfound_msg, header, type }) {
    const listItems = data
      .map((d) => {
        if (type === 'events') return this.renderEventsList(d)
        if (type === 'professors') return this.renderProfessorsList(d)
        const author = d.type === 'post' ? ` by: ${d.author_name}` : ''
        return /*html*/ ` 
        <li style="display:flex; flex-direction:column; gap:0.15rem">
          <a href="${d.permalink}">${d.title}</a>
          <span style="font-size:0.85rem;">${author}</span>
        </li>`
      })
      .join(' ')

    const listClasses =
      type === 'professors'
        ? 'flex items-center flex-wrap link-list min-list'
        : 'link-list min-list'

    const list =
      type === 'events'
        ? listItems
        : `<ul class="${listClasses}">${listItems}</ul>`

    const notFound = `<p>${notfound_msg}</p>`

    return `<h2 class="search-overlay__section-title">${header}</h2> ${
      listItems.length ? list : notFound
    }`
  }

  renderEventsList(d) {
    return /*html*/ ` 
    <div class="event-summary">
      <a
        class="event-summary__date event-summary__date--beige t-center"
        href="${d.permalink}"
      >
        <span class="event-summary__month">${d.month}</span>
        <span class="event-summary__day">${d.day}</span>
      </a>
      <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny">
          <a href="${d.permalink}">${d.title}</a>
        </h5>
        <p>
          ${d.description}
          <a href="${d.permalink}" class="nu gray">Read more</a>
        </p>
      </div>
    </div>`
  }

  renderProfessorsList(d) {
    return /*html*/ ` 
    <li class="professor-card__list-item">
      <a href="${d.permalink}" class="professor-card">
        <img
          src="${d.image.url}"
          alt="${d.title}"
          class="professor-card__image"
        />
        <span class="professor-card__name">${d.title}</span>
      </a>
    </li>`
  }

  async fetchData(value) {
    const res = await fetch(
      `${universityData.root_url}/wp-json/university/v1/search?keyword=${value}`
    )
    return await res.json()
  }
}

export default Search

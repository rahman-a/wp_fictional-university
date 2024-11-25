// @ts-nocheck
class Search {
  constructor() {
    this.bodyElement = document.querySelector('body')
    this.seachContainer = document.getElementById('search-container')
    this.searchTrigger = document.getElementById('search-trigger')
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
    this.searchTrigger?.addEventListener(
      'click',
      this.openSearchOverlay.bind(this)
    )
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
    Promise.allSettled([
      this.fetchData('posts', value),
      this.fetchData('pages', value),
    ]).then((data) => {
      const output = data
        .map(
          (t) => t.status === 'fulfilled' && Array.isArray(t.value) && t.value
        )
        .flat()
        .filter((d) => d)
      this.searchOutput.innerHTML = this.renderHtml(output)
    })

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

  openSearchOverlay() {
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
    const listItems = data
      .map((d) => {
        const author = d.type === 'post' ? ` by: ${d.author_name}` : ''
        return `<li style="display:flex; flex-direction:column; gap:0.15rem">
          <a href="${d.link}">${d.title.rendered}</a>
          <span style="font-size:0.85rem;">${author}</span>
        </li>`
      })
      .join(' ')
    const list = `<ul class="link-list min-list">${listItems}</ul>`
    const notFound = '<p>No general information found</p>'
    return `<h2 class="search-overlay__section-title">General Information</h2>
            ${listItems.length ? list : notFound}`
  }

  async fetchData(postType, value) {
    const res = await fetch(
      `${universityData.root_url}/wp-json/wp/v2/${postType}?search=${value}`
    )
    return await res.json()
  }
}

export default Search

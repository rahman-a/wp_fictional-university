// @ts-nocheck
import { slideUp, slideDown } from './animation'
class Note {
  constructor() {
    this.$notesListEl = document.getElementById('notes-list')
    this.$titleInput = document.querySelector('.new-note-title')
    this.$contentTextarea = document.querySelector('.new-note-body')
    this.$createBtn = document.querySelector('.submit-note')
    this.$limitMessageError = document.querySelector('.note-limit-message')
  }

  trigger() {
    if (!this.$notesListEl) return
    this.$notesListEl.addEventListener('click', async (e) => {
      const el = e.target
      if (
        el.classList.contains('fa-trash-o') ||
        el.classList.contains('delete-note')
      ) {
        this.deleteNoteAction(e)
      }
      if (
        el.classList.contains('fa-pencil') ||
        el.classList.contains('fa-close') ||
        el.classList.contains('edit-note')
      ) {
        this.editNoteAction(e)
      }

      if (
        el.classList.contains('fa-arrow-right') ||
        el.classList.contains('update-note')
      ) {
        const res = await this.updateRequest(e)
        if (res.ok) {
          this.makeFormReadonly(e)
        }
      }
    })
    this.$createBtn.addEventListener('click', this.createNoteAction.bind(this))
  }

  async createNoteAction(e) {
    const data = {
      title: this.$titleInput.value,
      content: this.$contentTextarea.value,
      status: 'private',
    }
    const res = await this.createRequest(data)
    if (res.ok) {
      this.$titleInput.value = ''
      this.$contentTextarea.value = ''
      return this.appendNewNoteToDOM(res)
    }
    this.$limitMessageError.classList.add('active')
    console.log('Failed 🟥', res)
  }

  async editNoteAction(e) {
    const $listItemEl = e.target.closest('li')
    if ($listItemEl.dataset.state === 'editable') {
      this.makeFormReadonly(e)
    } else {
      this.makeFormEditable(e)
    }
  }

  makeFormEditable(e) {
    const $listItemEl = e.target.closest('li')
    ;[...$listItemEl.children].forEach((node) => {
      if (
        node.getAttribute('name') === 'title' ||
        node.getAttribute('name') === 'content'
      ) {
        node.removeAttribute('readonly')
        node.removeAttribute('readonly')
        node.classList.add('note-active-field')
        node.classList.add('note-active-field')
        $listItemEl.dataset.state = 'editable'
      }
      if (node.getAttribute('name') === 'save') {
        node.classList.add('update-note--visible')
      }
      if (node.getAttribute('name') === 'edit') {
        node.innerHTML = '<i class="fa fa-close"></i>'
      }
    })
  }

  makeFormReadonly(e) {
    const $listItemEl = e.target.closest('li')
    ;[...$listItemEl.children].forEach((node) => {
      if (
        node.getAttribute('name') === 'title' ||
        node.getAttribute('name') === 'content'
      ) {
        node.setAttribute('readonly', 'readonly')
        node.setAttribute('readonly', 'readonly')
        node.classList.remove('note-active-field')
        node.classList.remove('note-active-field')
        $listItemEl.dataset.state = 'cancel'
      }
      if (node.getAttribute('name') === 'save') {
        node.classList.remove('update-note--visible')
      }
      if (node.getAttribute('name') === 'edit') {
        node.innerHTML = '<i class="fa fa-pencil"></i>'
      }
    })
  }

  async deleteNoteAction(e) {
    const $listItemEl = e.target.closest('li')
    const noteId = $listItemEl.dataset['id']
    const response = await this.deleteRequest(noteId)
    if (!response.ok) {
      return console.log('Failed Deleted 🟥', response.message)
    }
    if (parseInt(response.notes_count) < 5) {
      this.$limitMessageError.classList.remove('active')
    }
    this.removeElementFromDOM($listItemEl)
  }

  async createRequest(data) {
    const response = await fetch(
      `${universityData.root_url}/wp-json/wp/v2/note`,
      {
        method: 'POST',
        headers: {
          'X-WP-Nonce': universityData.nonce,
          'content-type': 'application/json',
        },
        body: JSON.stringify(data),
      }
    )
    const output = await response.json()
    return { ok: response.ok, ...output }
  }

  async updateRequest(e) {
    const $listItem = e.target.closest('li')
    const noteId = $listItem.dataset['id']
    const data = {
      title: $listItem.children.title.value,
      content: $listItem.children.content.value,
    }
    const response = await fetch(
      `${universityData.root_url}/wp-json/wp/v2/note/${noteId}`,
      {
        method: 'PUT',
        headers: {
          'X-WP-Nonce': universityData.nonce,
          'content-type': 'application/json',
        },
        body: JSON.stringify(data),
      }
    )
    const output = await response.json()
    return { ok: response.ok, ...output }
  }

  async deleteRequest(id) {
    const res = await fetch(
      `${universityData.root_url}/wp-json/wp/v2/note/${id}`,
      {
        method: 'DELETE',
        headers: {
          'X-WP-Nonce': universityData.nonce,
        },
      }
    )
    const data = await res.json()
    if (res.ok) {
      return { ...data, ok: res.ok }
    }
    return { ok: false, message: data.message, status: data.data.status }
  }

  appendNewNoteToDOM(data) {
    const note = /*html*/ `
        <input readonly type="text" name="title" class="note-title-field" id="note-title" value="${data.title.raw}">
        <span class="edit-note" aria-hidden="true" name="edit"><i class="fa fa-pencil"></i></span>
        <span class="delete-note" aria-hidden="true" name="delete"> <i class="fa fa-trash-o"></i></span>
        <textarea readonly class="note-body-field" name="content">${data.content.raw}</textarea>
        <span class="update-note btn btn--blue btn--small" aria-hidden="true" name="save"> <i class="fa fa-arrow-right"></i></span>
    `
    const noteItem = document.createElement('li')
    noteItem.style.listStyle = 'none'
    noteItem.style.display = 'none'
    noteItem.dataset.id = data.id
    noteItem.insertAdjacentHTML('afterbegin', note)
    this.$notesListEl.insertAdjacentElement('afterbegin', noteItem)
    slideDown(noteItem)
  }

  removeElementFromDOM(el, duration = 500) {
    slideUp(el, duration)
    const removeTimout = setTimeout(() => {
      el.remove()
      clearTimeout(el)
    }, duration + 50)
  }
}

export default Note

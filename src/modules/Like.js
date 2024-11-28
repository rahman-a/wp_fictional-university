// @ts-nocheck
class Like {
  constructor() {
    this.$likeBox = document.querySelector('.like-box')
    this.$likesCount = document.querySelector('.like-count')
  }

  trigger() {
    this.$likeBox?.addEventListener('click', this.clickDispatcher.bind(this))
  }

  clickDispatcher() {
    if (this.$likeBox.dataset.liked === 'true') {
      this.deleteLikeAction()
    } else {
      this.createLikeAction()
    }
  }

  async createLikeAction() {
    const professor = this.$likeBox.dataset.professor
    const res = await this.createRequest(professor)
    if (res.ok) {
      this.renderLikeBox(true, { id: res.id })
    }
  }
  async deleteLikeAction() {
    const like = this.$likeBox.dataset.like
    const res = await this.deleteRequest(like)
    if (res.ok) {
      this.renderLikeBox(false)
    }
  }

  renderLikeBox(isLiked, data) {
    if (isLiked) {
      this.$likeBox.dataset.liked = 'true'
      this.$likeBox.dataset.like = data.id
      let likesCount = parseInt(this.$likesCount.innerText)
      likesCount++
      this.$likesCount.innerText = likesCount
      return
    }

    this.$likeBox.dataset.liked = 'false'
    this.$likeBox.dataset.like = ''
    let likesCount = parseInt(this.$likesCount.innerText)
    likesCount--
    this.$likesCount.innerText = likesCount
  }

  async createRequest(professor) {
    const data = new URLSearchParams()
    data.append('professor', professor)
    const res = await fetch(
      `${universityData.root_url}/wp-json/university/v1/likes`,
      {
        method: 'POST',
        headers: {
          'X-WP-Nonce': universityData.nonce,
        },
        body: data,
      }
    )
    return await res.json()
  }
  async deleteRequest(like) {
    const data = new URLSearchParams()
    data.append('like', like)
    const res = await fetch(
      `${universityData.root_url}/wp-json/university/v1/likes`,
      {
        method: 'DELETE',
        headers: {
          'X-WP-Nonce': universityData.nonce,
        },
        body: data,
      }
    )
    return await res.json()
  }
}

export default Like

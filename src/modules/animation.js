export function slideUp(element, duration = 500) {
  element.style.height = element.offsetHeight + 'px'
  element.style.transitionProperty = 'height, margin, padding'
  element.style.transitionDuration = duration + 'ms'
  element.style.boxSizing = 'border-box'
  element.style.overflow = 'hidden'

  // Trigger reflow to apply the initial height
  element.offsetHeight

  // Set the target height to zero
  element.style.height = 0
  element.style.paddingTop = 0
  element.style.paddingBottom = 0
  element.style.marginTop = 0
  element.style.marginBottom = 0

  // Remove element from the layout after the transition
  const slideUpTimout = window.setTimeout(() => {
    element.style.display = 'none'
    element.style.removeProperty('height')
    element.style.removeProperty('padding-top')
    element.style.removeProperty('padding-bottom')
    element.style.removeProperty('margin-top')
    element.style.removeProperty('margin-bottom')
    element.style.removeProperty('overflow')
    element.style.removeProperty('transition-duration')
    element.style.removeProperty('transition-property')
    clearTimeout(slideUpTimout)
  }, duration)
}

export function slideDown(element, duration = 500) {
  element.style.removeProperty('display')
  let display = window.getComputedStyle(element).display
  if (display === 'none') display = 'block'
  element.style.display = display

  let height = element.offsetHeight
  element.style.overflow = 'hidden'
  element.style.height = 0
  element.style.paddingTop = 0
  element.style.paddingBottom = 0
  element.style.marginTop = 0
  element.style.marginBottom = 0
  element.offsetHeight // Trigger reflow

  element.style.boxSizing = 'border-box'
  element.style.transitionProperty = 'height, margin, padding'
  element.style.transitionDuration = duration + 'ms'
  element.style.height = height + 'px'
  element.style.removeProperty('padding-top')
  element.style.removeProperty('padding-bottom')
  element.style.removeProperty('margin-top')
  element.style.removeProperty('margin-bottom')

  window.setTimeout(() => {
    element.style.removeProperty('height')
    element.style.removeProperty('overflow')
    element.style.removeProperty('transition-duration')
    element.style.removeProperty('transition-property')
  }, duration)
}

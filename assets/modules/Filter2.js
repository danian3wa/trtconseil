/**
 * Class responsible for handling filtering functionality and loading content based on filter selections.
 * @property {HTMLElement} pagination
 * @property {HTMLElement} content
 * @property {HTMLFormElement} form
 */

export default class Filter {

  /**
   * @param {HTMLElement|null} element - The root element for the filter component.
   */
  constructor(element) {
    if (element === null) {
      return
    }
    // Cache references to relevant DOM elements:
    this.pagination = element.querySelector('.js-filter-pagination')
    this.content = element.querySelector('.js-filter-content')
    this.form = element.querySelector('.js-filter-form')

    // Attach event listeners to handle user interactions:
    this.bindEvents()
  }

  /**
  * Ajoute les comportements aux différents éléments
  * Binds event listeners to the pagination element and form elements to handle user interactions with the filter.
  */
  bindEvents() {
    // Handle clicks on pagination links to load new content based on selected page:
    const aClickListener = e => {
      if (e.target.tagName === 'A') {
        e.preventDefault()
        this.loadUrl(e.target.getAttribute('href'))
      }
    }

    this.pagination.addEventListener('click', aClickListener)
    // Handle changes and key presses on form inputs to submit the form and update content:
    // Add keyup listener for qInput
    const qInput = document.querySelector('.js-filter-form input[name="q"]')
    qInput.addEventListener('keyup', this.loadForm.bind(this))
    /*
    this.form.querySelector('input[name="q"]').forEach(input => {
      input.addEventListener('keyup', this.loadForm.bind(this))
    })*/
    // console.log(this.form);
    this.form.querySelectorAll('input').forEach(input => {
      input.addEventListener('change', this.loadForm.bind(this))
      // input.addEventListener('keyup', this.loadForm.bind(this))
    })
    this.form.querySelectorAll('select').forEach(select => {
      select.addEventListener('change', this.loadForm.bind(this))
    })
  }

  /**
   * Gathers form data, constructs a URL with query parameters, and triggers content loading based on the updated form data.
   *
   * @returns {Promise<void>} - A promise that resolves when the content loading is complete.
   */
  async loadForm() {
    //console.log(this.form);
    const data = new FormData(this.form)
    const url = new URL(this.form.getAttribute('action') || window.location.href)
    const params = new URLSearchParams()

    data.forEach((value, key) => {
      params.append(key, value)
    })
    //debugger;
    return this.loadUrl(url.pathname + '?' + params.toString())
  }

  /**
   * Fetches content from the specified URL using AJAX and updates the content and pagination areas with the received data.
   *
   * @param {string} url - The URL to fetch content from.
   * @returns {Promise<void>} - A promise that resolves when the content update is complete.
   */
  async loadUrl(url) {
    const ajaxUrl = url + '&ajax=1'
    const response = await fetch(ajaxUrl, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    if (response.status >= 200 && response.status < 300) {
      const data = await response.json()
      //console.log(data)
      this.content.innerHTML = data.content
      this.pagination.innerHTML = data.pagination

      // Update browser history to reflect the loaded content:
      history.replaceState({}, '', url)  // pushState pour l'historique
    } else {
      console.error(response)
    }
  }
}
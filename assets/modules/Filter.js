/**
 * Class responsible for handling filtering functionality and loading content based on filter selections.
 */
export default class Filter {
  /**
   * Constructs a new Filter instance, associating it with the provided element containing filter controls and content area.
   *
   * @param {HTMLElement|null} element - The root element for the filter component.
   */
  constructor(element) {
    if (!element) {
      return;
    }

    // Cache references to relevant DOM elements:
    this.pagination = element.querySelector('.js-filter-pagination');
    this.content = element.querySelector('.js-filter-content');
    this.form = element.querySelector('.js-filter-form');

    // Attach event listeners to handle user interactions:
    this.bindEvents();
  }

  /**
   * Binds event listeners to the pagination element and form elements to handle user interactions with the filter.
   */
  bindEvents() {
    // Handle clicks on pagination links to load new content based on selected page:
    const handlePaginationClick = (event) => {
      if (event.target.tagName === 'A') {
        event.preventDefault();
        this.loadUrl(event.target.getAttribute('href'));
      }
    };
    this.pagination.addEventListener('click', handlePaginationClick);

    // Handle changes and key presses on form inputs to submit the form and update content:
    this.form.querySelectorAll('input, select').forEach((input) => {
      input.addEventListener('change', this.loadForm.bind(this));
      input.addEventListener('keyup', this.loadForm.bind(this));
    });
  }

  /**
   * Gathers form data, constructs a URL with query parameters, and triggers content loading based on the updated form data.
   *
   * @returns {Promise<void>} - A promise that resolves when the content loading is complete.
   */
  async loadForm() {
    const data = new FormData(this.form);
    const url = new URL(this.form.getAttribute('action') || window.location.href);
    const params = new URLSearchParams();

    data.forEach((value, key) => {
      params.append(key, value);
    });

    return this.loadUrl(url.pathname + '?' + params.toString());
  }

  /**
   * Fetches content from the specified URL using AJAX and updates the content and pagination areas with the received data.
   *
   * @param {string} url - The URL to fetch content from.
   * @returns {Promise<void>} - A promise that resolves when the content update is complete.
   */
  async loadUrl(url) {
    const ajaxUrl = url + '&ajax=1'; // Add flag indicating AJAX request
    const response = await fetch(ajaxUrl, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      },
    });

    if (response.ok) {
      const data = await response.json();
      this.content.innerHTML = data.content;
      this.pagination.innerHTML = data.pagination;

      // Update browser history to reflect the loaded content:
      history.replaceState({}, '', url);
    } else {
      console.error('Failed to load content:', response);
    }
  }
}

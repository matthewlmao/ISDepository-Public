class listingCard extends HTMLElement {
    
    constructor() {
      super();
      this.attachShadow({ mode: 'open' });
      this.loadTemplate();
    }
  
    async loadTemplate() {
      const response = await fetch('/components/cards/listing-card.html');  // Fetch the template >~< 
      const html = await response.text(); 
      this.shadowRoot.innerHTML = html; 

      this.updateCard();  // For sake of extensibility. Modify card with this. 
    } 

    updateCard() { 
      const image = this.getAttribute('image'); 
      const title = this.getAttribute('title'); 
      const text = this.getAttribute('text'); 
      const price = this.getAttribute('price'); 
      const condition = this.getAttribute('condition'); 

      this.shadowRoot.querySelector('card-image').src = image;
      this.shadowRoot.querySelector('card-title').innerText = title;
      this.shadowRoot.querySelector('card-text').innerText = text;
      this.shadowRoot.querySelector('card-price').innerText = price;
      this.shadowRoot.querySelector('card-condition').innerText = condition;

    }
  }
  
  // Registering dat element :p
  customElements.define('listing-card', listingCard);
const searchInput = document.getElementById('searchInput');
const suggestionsContainer = document.getElementById('suggestionsContainer');

searchInput.addEventListener('input', debounce(handleInput, 300));

function handleInput(e) {
    const query = e.target.value.trim();
    
    if (!query) {
        suggestionsContainer.innerHTML = '';
        return;
    }
    
    const results = searchIndex.search({
        query: query,
        limit: 5,
        suggest: true // Enable fuzzy matched suggestions 
    });
    
    displaySuggestions(results);
}

function displaySuggestions(results) {
    suggestionsContainer.innerHTML = results
        .flatMap(r => r.result)
        .map(id => {
            const item = searchData.find(d => d.listing_id === id);
            return `<div class="suggestion">${item.title}</div>`;
        })
        .join('');
}

function debounce(func, wait) {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}
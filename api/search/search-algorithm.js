let searchIndex = null;
let searchData = [];

async function initializeSearch() {
    // Fetch from database 
    const response = await fetch('search-algorithm.php');
    searchData = await response.json();
    
    // Create index for FlexSearch 
    searchIndex = new FlexSearch.Document({
        document: {
            id: "listing_id",
            index: "search_text",
            store: ["title", "price", "created_at", "condition_name", "tags"]
        },
        tokenize: "forward",
        suggest: true,
        cache: 100,
        depth: 3 // Adjust fuzzy search depth
    });
    
    // Add data to index
    searchData.forEach(item => searchIndex.add(item));
}

initializeSearch();
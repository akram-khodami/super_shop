const inputs = document.querySelectorAll('.general-search');
let debounceTimer;
let currentSearchId = 0;

inputs.forEach(input => {
    input.addEventListener('input', searchProductsDebounced);
});

function searchProductsDebounced() {
    clearTimeout(debounceTimer);
    const searchText = this.value.trim();

    // Do not search if less than 2 characters
    if (searchText.length < 2) {
        clearResults();
        return;
    }

    // Show loading state
    showLoadingState();

    debounceTimer = setTimeout(() => {
        const searchId = ++currentSearchId;
        searchProducts(searchText, searchId);
    }, 800); // Reduced to 800ms for better responsiveness
}

function searchProducts(searchText, searchId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch(`/assistant/search`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            message: searchText
        })
    })
        .then(async response => {
            const data = await response.json();

            // If a new search has started, ignore this result
            if (searchId !== currentSearchId) return;

            if (!response.ok) {
                throw new Error(data.error || 'Error in search');
            }

            return data;
        })
        .then(data => {
            if (searchId !== currentSearchId) return;

            if (data && data.success !== false) {
                displayResults(data);
            } else {
                showError(data?.error || 'Error in search');
            }
        })
        .catch(error => {
            if (searchId !== currentSearchId) return;
            console.error('Error:', error);
            showError('A problem occurred while connecting to the server.');
        });
}

function showLoadingState() {
    const resultsContainer = document.getElementById('search-results');
    if (resultsContainer) {
        resultsContainer.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Searching...</span>
                </div>
                <p class="mt-2 text-gray-600">Searching...</p>
            </div>
        `;
        resultsContainer.style.display = 'block';
    }
}

function displayResults(data) {
    const resultsContainer = document.getElementById('search-results');
    if (!resultsContainer) return;

    if (data.products && data.products.length > 0) {
        let html = `<div class="grid grid-cols-1 gap-4 p-4">`;

        data.products.forEach(product => {
            const price = product.variants[0]?.final_price || 'Unknown';
            html += `
                <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg cursor-pointer"
                     onclick="window.location.href='/product/${product.slug}'">
                    <img src="${product.thumbnail_url}" alt="${product.name}" class="w-16 h-16 object-cover rounded">
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm">${product.name}</h4>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-blue-600 font-bold">${formatPrice(price)} Tomans</span>
                            <span class="text-xs text-gray-500">${product.brand?.name || ''}</span>
                        </div>
                    </div>
                </div>
            `;
        });

        html += `
            <div class="text-center text-sm text-gray-500 p-2 border-t">
                ${data.total} results found
            </div>
        </div>`;

        resultsContainer.innerHTML = html;
        resultsContainer.style.display = 'block';
    } else {
        resultsContainer.innerHTML = `
            <div class="text-center py-4 text-gray-500">
                <p>No results found for your search</p>
                <p class="text-xs mt-1">Try searching with different keywords</p>
            </div>
        `;
        resultsContainer.style.display = 'block';
    }
}

function clearResults() {
    const resultsContainer = document.getElementById('search-results');
    if (resultsContainer) {
        resultsContainer.innerHTML = '';
        resultsContainer.style.display = 'none';
    }
}

function showError(message) {
    const resultsContainer = document.getElementById('search-results');
    if (resultsContainer) {
        resultsContainer.innerHTML = `
            <div class="text-center py-4 text-red-500">
                <p>⚠️ ${message}</p>
            </div>
        `;
        resultsContainer.style.display = 'block';
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('fa-IR').format(price);
}

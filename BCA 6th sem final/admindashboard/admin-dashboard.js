function loadContent(page) {
    const content = document.getElementById("dynamic-content");
    content.innerHTML = `
        <div class="loader-container">
            <div class="loader"></div>
            <p>Loading...</p>
        </div>
    `;
    fetch(page)
        .then(response => response.text())
        .then(data => content.innerHTML = data)
        .catch(() => {
            content.innerHTML = `
                <div class="error-message">
                    <p>Failed to load content.</p>
                    <button class="btn-retry" onclick="loadContent('${page}')">Retry</button>
                </div>
            `;
        });
}

function submitForm(form, actionUrl) {
    const formData = new FormData(form);
    fetch(actionUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('dynamic-content').innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}


        function confirmUpdate() {
            return confirm('Are you sure you want to update this vehicle?');
        }

        function submitForm(form, actionUrl) {
            const formData = new FormData(form);
            
            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                // Update the content area with the response
                window.parent.document.getElementById('dynamic-content').innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const licensePlate = document.getElementById('license_plate');
            
            // Client-side validation for license plate
            licensePlate.addEventListener('input', function() {
                this.setCustomValidity('');
                this.checkValidity();
            });
            
            licensePlate.addEventListener('invalid', function() {
                if (this.validity.patternMismatch) {
                    this.setCustomValidity('Please use format: ba 01 cha 1234');
                }
            });
        });

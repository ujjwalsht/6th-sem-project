/**
 * User Dashboard - Main JavaScript File
 * Handles dynamic content loading, form submissions, and navigation
 */

// Global variables
let currentPage = '';

// Main content loader
async function loadContent(page) {
    // Skip processing for logout links
    if (page.includes('logout.php')) {
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = page;
        }
        return;
    }

    try {
        currentPage = page;
        const response = await fetch(page);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const html = await response.text();
        const contentArea = document.getElementById('dynamic-content');
        
        if (contentArea) {
            contentArea.innerHTML = html;
            initPageScripts(page);
        } else {
            console.error('Content area not found');
        }

    } catch (error) {
        console.error('Content loading failed:', error);
        showError('Failed to load content. Please try again.');
    }
}

// Initialize page-specific functionality
function initPageScripts(page) {
    if (page.includes('add-vehicle') || page.includes('update_vehicle')) {
        initVehicleForm();
    } 
    else if (page.includes('vehicle-list')) {
        // Vehicle list scripts are self-contained in vehicle-list.js
    } 
    else if (page.includes('appointment_form')) {
        initAppointmentForm();
    }
    else if (page.includes('appointment-list')) {
        loadAppointments();
    }
    else if (page.includes('profile')) {
        initProfilePage();
    }
}

// Vehicle Form Handling
function initVehicleForm() {
    const form = document.getElementById('vehicleForm');
    if (!form) return;

    // License plate validation
    const licensePlate = document.getElementById('license_plate');
    const errorMsg = document.getElementById('error-message');

    if (licensePlate && errorMsg) {
        licensePlate.addEventListener('input', () => {
            const isValid = /^[a-z]{2}\s\d{2}\s[a-z]{3}\s\d{4}$/i.test(licensePlate.value.trim());
            errorMsg.textContent = isValid ? '' : 'Invalid format (Example: ba 01 cha 1234)';
        });
    }

    // Form submission
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        await submitForm(form, form.action);
    });
}

// Appointment Form Handling
function initAppointmentForm() {
    const form = document.getElementById('appointmentForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        await submitForm(form, 'process_appointment.php');
    });
}

// Appointment List Handling
function loadAppointments() {
    const container = document.getElementById("appointment-container") || 
                     document.getElementById("dynamic-content");

    fetch('appointment-list.php')
        .then(response => {
            if (!response.ok) throw new Error("Failed to load appointments");
            return response.text();
        })
        .then(html => {
            container.innerHTML = html;
            initAppointmentActions();
        })
        .catch(error => {
            console.error(error);
            showError('Could not load appointments.');
        });
}

function initAppointmentActions() {
    document.querySelectorAll('.cancel-appointment').forEach(btn => {
        btn.addEventListener('click', async function() {
            const appointmentId = this.dataset.id;
            if (confirm('Are you sure you want to cancel this appointment?')) {
                await cancelAppointment(appointmentId);
            }
        });
    });
}

// Profile Page Handling
function initProfilePage() {
    const form = document.getElementById('profileForm');
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            await submitForm(form, 'update-profile.php');
        });
    }
}

// Helper Functions
async function submitForm(form, actionUrl) {
    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
    let responseDiv = document.getElementById('response-message');
    
    if (!responseDiv) {
        responseDiv = document.createElement('div');
        responseDiv.id = 'response-message';
        form.appendChild(responseDiv);
    }

    // Store original text/value
    const originalText = submitBtn.value || submitBtn.textContent;
    submitBtn.disabled = true;
    
    // Set processing text
    if (submitBtn.tagName === 'INPUT') {
        submitBtn.value = 'Processing...';
    } else {
        submitBtn.textContent = 'Processing...';
    }

    try {
        const formData = new FormData(form);
        const response = await fetch(actionUrl, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) throw new Error(`Server error: ${response.status}`);
        const data = await response.json();

        responseDiv.innerHTML = data.success
            ? `<div class="success">${data.message}</div>`
            : `<div class="error">${data.message}</div>`;

        if (data.success && data.redirect) {
            setTimeout(() => loadContent(data.redirect), 1500);
        } else if (data.success) {
            form.reset();
        }
    } catch (error) {
        console.error('Submission error:', error);
        responseDiv.innerHTML = `<div class="error">Error: ${error.message}</div>`;
    } finally {
        submitBtn.disabled = false;
        // Restore original text
        if (submitBtn.tagName === 'INPUT') {
            submitBtn.value = originalText;
        } else {
            submitBtn.textContent = originalText;
        }
    }
}

async function cancelAppointment(appointmentId) {
    try {
        const response = await fetch(`cancel-appointment.php?id=${appointmentId}`);
        const data = await response.json();
        
        if (data.success) {
            showSuccess(data.message);
            loadContent('appointment-list.php');
        } else {
            showError(data.message);
        }
    } catch (error) {
        console.error('Cancel error:', error);
        showError('Failed to cancel appointment');
    }
}

function showError(message) {
    const container = document.getElementById('dynamic-content');
    if (container) {
        container.innerHTML = `<div class="error">${message}</div>`;
    }
}

function showSuccess(message) {
    const container = document.getElementById('dynamic-content');
    if (container) {
        container.innerHTML = `<div class="success">${message}</div>`;
    }
}

// Initialize the dashboard
document.addEventListener('DOMContentLoaded', () => {
    // Set default page
    loadContent('vehicle-list.php');

    // Navigation event listeners
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function(e) {
            const page = this.dataset.page || this.getAttribute('onclick')?.match(/'([^']+)'/)?.[1];
            if (page) {
                e.preventDefault();
                loadContent(page);
            }
        });
    });
});
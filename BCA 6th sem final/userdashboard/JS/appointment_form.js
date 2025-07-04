function validateAppointmentForm() {
    const form = document.getElementById('appointment-form');
    const formData = new FormData(form);
    
    // Make sure a slot is selected
    const slotNumber = document.getElementById('selected_slot').value;
    if (!slotNumber) {
        alert('Please select an available slot');
        return;
    }
    
    fetch('appointment_form.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            if (window.parent && window.parent.loadContent) {
                window.parent.loadContent('appointment-list.php');
            } else {
                window.location.href = 'appointment-list.php';
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while booking the appointment.');
    });
}

function checkAvailableSlots() {
    const date = document.getElementById('appointment_date').value;
    const time = document.getElementById('appointment_time').value;
    
    if (!date || !time) return;
    
    fetch('appointment_form.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `check_slots=true&date=${date}&time=${time}`
    })
    .then(response => response.json())
    .then(data => {
        const slotContainer = document.getElementById('slot-options');
        const slotSelection = document.getElementById('slot-selection');
        slotContainer.innerHTML = '';
        
        if (data.available_slots.length === 0) {
            slotContainer.innerHTML = '<div class="slot-error">No slots available at this time. Please choose another time.</div>';
            slotSelection.style.display = 'block';
            document.getElementById('selected_slot').value = '';
        } else {
            data.available_slots.forEach(slot => {
                const slotBtn = document.createElement('button');
                slotBtn.type = 'button';
                slotBtn.className = 'slot-btn';
                slotBtn.textContent = `Slot ${slot}`;
                slotBtn.onclick = function() {
                    // Remove active class from all buttons
                    document.querySelectorAll('.slot-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    // Add active class to clicked button
                    this.classList.add('active');
                    // Set the selected slot value
                    document.getElementById('selected_slot').value = slot;
                };
                slotContainer.appendChild(slotBtn);
            });
            slotSelection.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error checking slots:', error);
    });
}

// Set minimum time to current time if today is selected
document.getElementById('appointment_date').addEventListener('change', function() {
    const today = new Date().toISOString().split('T')[0];
    const timeInput = document.getElementById('appointment_time');
    
    if (this.value === today) {
        const now = new Date();
        const currentHour = now.getHours().toString().padStart(2, '0');
        const currentMinute = Math.ceil(now.getMinutes() / 30) * 30; // Round to nearest 30 minutes
        const currentTime = `${currentHour}:${currentMinute === 60 ? '00' : currentMinute.toString().padStart(2, '0')}`;
        
        timeInput.min = currentTime > '07:30' ? currentTime : '07:30';
    } else {
        timeInput.min = '07:30';
    }
    
    // Hide slot selection when date changes
    document.getElementById('slot-selection').style.display = 'none';
    document.getElementById('selected_slot').value = '';
});
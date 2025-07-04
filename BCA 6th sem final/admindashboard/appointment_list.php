<?php
require_once 'admin_auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment List</title>
    <link rel="stylesheet" href="admin-style.css">
    <link rel="stylesheet" href="ap_list.css">

</head>
<body>
    <div id="container">
        <div id="dashboard-header">
            <h1>Appointment List</h1>
        </div>
        
        <div id="nav" class="sidenav">
            <a href="../homepage/hp.php" class="btn-home">Click here to Goto Homepage</a>
            <a href="javascript:void(0)" onclick="loadContent('manage_users.php')" class="nav-item">Manage Users</a>
            <a href="javascript:void(0)" onclick="loadContent('appointment_list.php')" class="nav-item active">Appointment List</a>
            <a href="javascript:void(0)" onclick="loadContent('admin_dashboard.php')" class="nav-item">← Back to Dashboard</a>
            <a href="../logout.php" class="nav-item">Logout</a>
        </div>
        
        <div id="main-content">
            <div id="dynamic-content">
                <h2>Manage Appointments</h2>
                <div id="alert-container"></div>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                
                <div class="sort-options">
                    <h3>Sort By:</h3>
                    <button onclick="loadAppointments('priority')" class="btn btn-sort active" id="sort-priority">Priority</button>
                    <button onclick="loadAppointments('user')" class="btn btn-sort" id="sort-user">User ID</button>
                    <button onclick="loadAppointments('time')" class="btn btn-sort" id="sort-time">Appointment Time</button>
                </div>
                
                <div class="priority-legend">
                    <h3>Priority Legend:</h3>
                    <div>
                        <span class="priority-badge priority-high">High</span> - Critical safety and drivability issues
                    </div>
                    <div>
                        <span class="priority-badge priority-medium">Medium</span> - Important but not immediately critical
                    </div>
                    <div>
                        <span class="priority-badge priority-low">Low</span> - Routine maintenance
                    </div>
                </div>
                
                <div id="appointment-container">
                    <div class="loading">Loading appointments...</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        &copy; <?php echo date('Y'); ?> Sangam Auto Workshop. ALL RIGHTS RESERVED.
    </div>
    
    <script>
        // Load appointments when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadAppointments('priority');
        });
        
        function loadAppointments(sortMethod) {
            // Show loading state
            document.getElementById('appointment-container').innerHTML = '<div class="loading">Loading appointments...</div>';
            
            // Update active sort button
            document.querySelectorAll('.btn-sort').forEach(btn => {
                btn.classList.remove('active');
            });
            document.getElementById('sort-' + sortMethod).classList.add('active');
            
            // Fetch data from server with improved error handling
            fetch(`appointment_list_ajax.php?sort=${sortMethod}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Server returned ${response.status}: ${response.statusText}`);
                    }
                    return response.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.error) {
                            throw new Error(data.error);
                        }
                        renderAppointments(data);
                    } catch (e) {
                        console.error('Failed to parse JSON:', text);
                        throw new Error('Invalid data received from server');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('appointment-container').innerHTML = `
                        <div class="error-message">
                            <strong>Error loading appointments:</strong><br>
                            ${error.message}<br><br>
                            Please try again or contact support if the problem persists.
                        </div>
                    `;
                });
        }
        
        function renderAppointments(appointments) {
            const container = document.getElementById('appointment-container');
            
            if (!appointments || appointments.length === 0) {
                container.innerHTML = '<div class="error-message">No appointments found</div>';
                return;
            }
            
            // Create table HTML
            let html = `
                <table class="appointment-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Vehicle</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Service Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            appointments.forEach(appointment => {
                // Parse the date from database
                const dbDate = new Date(appointment.appointment_date);
                
                // Format date as YYYY-MM-DD
                const formattedDate = dbDate.toISOString().split('T')[0];
                
                // Get time from the database field (assuming it's stored in appointment_time)
                const time = appointment.appointment_time || '00:00';
                
                html += `
                    <tr>
                        <td>${escapeHtml(appointment.id)}</td>
                        <td>
                            <div class="user-info">
                                <span>${escapeHtml(appointment.user_name + ' ' + (appointment.user_last_name || ''))}</span>
                                <span class="user-id">ID: ${escapeHtml(appointment.user_id)}</span>
                            </div>
                        </td>
                        <td>
                            ${escapeHtml(appointment.vehicle_brand + ' ' + (appointment.vehicle_model || ''))}
                            ${appointment.vehicle_plate ? '<br><small>' + escapeHtml(appointment.vehicle_plate) + '</small>' : ''}
                        </td>
                        <td>${formattedDate}</td>
                        <td>${time}</td>
                        <td>
                            <span class="priority-badge priority-${appointment.priority_label.toLowerCase()}">
                                ${appointment.priority_label}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-${appointment.status.toLowerCase()}" data-appointment-id="${appointment.id}">
                                ${appointment.status}
                                <div class="status-options">
                                    <div class="status-option" data-status="Pending">Pending</div>
                                    <div class="status-option" data-status="Confirmed">Confirmed</div>
                                    <div class="status-option" data-status="Cancelled">Cancelled</div>
                                </div>
                            </span>
                        </td>
                        <td>${escapeHtml(appointment.service_type || 'N/A')}</td>
                        <td class="actions">
                            <a href="delete_appointment.php?id=${appointment.id}" class="btn" onclick="return confirmDelete(event, ${appointment.id})">Delete</a>
                        </td>
                    </tr>
                `;
            });
            
            html += `
                    </tbody>
                </table>
            `;
            
            container.innerHTML = html;
            
            // Add event listeners for status changes
            document.querySelectorAll('.status-option').forEach(option => {
                option.addEventListener('click', function() {
                    const newStatus = this.getAttribute('data-status');
                    const statusBadge = this.closest('.status-badge');
                    const appointmentId = statusBadge.getAttribute('data-appointment-id');
                    
                    updateAppointmentStatus(appointmentId, newStatus);
                });
            });
        }
        
        function updateAppointmentStatus(appointmentId, newStatus) {
            fetch('update_appointment_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${appointmentId}&status=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the status badge visually
                    const statusBadge = document.querySelector(`.status-badge[data-appointment-id="${appointmentId}"]`);
                    statusBadge.className = `status-badge status-${newStatus.toLowerCase()}`;
                    statusBadge.innerHTML = `
                        ${newStatus}
                        <div class="status-options">
                            <div class="status-option" data-status="Pending">Pending</div>
                            <div class="status-option" data-status="Confirmed">Confirmed</div>
                            <div class="status-option" data-status="Cancelled">Cancelled</div>
                        </div>
                    `;
                    
                    // Reattach event listeners
                    statusBadge.querySelectorAll('.status-option').forEach(option => {
                        option.addEventListener('click', function() {
                            updateAppointmentStatus(appointmentId, this.getAttribute('data-status'));
                        });
                    });
                } else {
                    alert('Failed to update status: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update status. Please try again.');
            });
        }
        
        function confirmDelete(event, id) {
            event.preventDefault();
            
            if (confirm('Are you sure you want to delete this appointment?')) {
                fetch(`delete_appointment.php?id=${id}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Server returned non-JSON response');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showAlert('Appointment deleted successfully', 'success');
                        const activeSort = document.querySelector('.btn-sort.active');
                        loadAppointments(activeSort ? activeSort.id.replace('sort-', '') : 'priority');
                    } else {
                        throw new Error(data.error || 'Failed to delete appointment');
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    showAlert(error.message || 'Failed to delete appointment', 'error');
                });
            }
        }
        
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.textContent = message;
            alertDiv.style.padding = '10px';
            alertDiv.style.margin = '10px 0';
            alertDiv.style.borderRadius = '4px';
            alertDiv.style.color = type === 'error' ? '#721c24' : '#155724';
            alertDiv.style.backgroundColor = type === 'error' ? '#f8d7da' : '#d4edda';
            alertDiv.style.border = type === 'error' ? '1px solid #f5c6cb' : '1px solid #c3e6cb';
            
            const container = document.getElementById('alert-container');
            container.innerHTML = '';
            container.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
        
        function escapeHtml(unsafe) {
            if (unsafe === null || unsafe === undefined) return '';
            return unsafe.toString()
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
        
        function loadContent(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
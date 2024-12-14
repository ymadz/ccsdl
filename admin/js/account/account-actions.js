document.addEventListener('DOMContentLoaded', function() {
    // Initialize event listeners
    initializeAccountActions();
});

function initializeAccountActions() {
    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', handleDelete);
    });
}

function handleDelete(event) {
    const accountId = this.getAttribute('data-id');
    const row = this.closest('tr');

    if (confirm('Are you sure you want to delete this account?')) {
        fetch('delete.account.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                account_id: accountId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row from the table
                row.remove();
                showAlert('Account deleted successfully', 'success');
            } else {
                showAlert(data.message || 'Error deleting account', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while deleting the account', 'danger');
        });
    }
}

function showAlert(message, type) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    // Insert alert at the top of the main content area
    const mainContent = document.querySelector('.main');
    mainContent.insertBefore(alertDiv, mainContent.firstChild);

    // Auto-dismiss after 3 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
} 
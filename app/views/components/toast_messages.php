<?php
/**
 * Toast messages component
 * Include this in all views that need to display notifications
 */
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to show toast messages
        function showPhpToast(message, type = 'primary') {
            if (typeof showToast === 'function') {
                // If produtos.js is loaded, use its showToast function
                showToast(message, type === 'primary' ? 'info' : type);
            } else {
                // Otherwise create our own toast
                // Get or create toast container
                let toastContainer = document.querySelector('.toast-container');
                if (!toastContainer) {
                    toastContainer = document.createElement('div');
                    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                    document.body.appendChild(toastContainer);
                }
                
                // Create toast element
                const toastId = 'toast-' + Date.now();
                const wrapper = document.createElement('div');
                
                // Map alert types to Bootstrap color classes
                const bgColorClass = type === 'danger' ? 'bg-danger' :
                                   type === 'warning' ? 'bg-warning' :
                                   type === 'primary' ? 'bg-primary' : 'bg-success';
                                   
                // Use white text for dark backgrounds
                const textColorClass = (type === 'danger' || type === 'primary' || type === 'success') ? 'text-white' : '';
                
                wrapper.innerHTML = `
                    <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header ${bgColorClass} ${textColorClass}">
                            <strong class="me-auto">Notificação</strong>
                            <button type="button" class="btn-close ${textColorClass ? 'btn-close-white' : ''}" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            ${message}
                        </div>
                    </div>
                `;
                
                toastContainer.appendChild(wrapper);
                
                // Initialize and show the toast
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 5000 });
                toast.show();
                
                // Remove the toast element after it's hidden to prevent build-up
                toastElement.addEventListener('hidden.bs.toast', function() {
                    this.remove();
                });
            }
        }

        <?php if (isset($erro) && $erro): ?>
            showPhpToast('<?= addslashes(htmlspecialchars($erro)) ?>', 'danger');
        <?php endif; ?>

        <?php if (isset($mensagem) && $mensagem): ?>
            showPhpToast('<?= addslashes(htmlspecialchars($mensagem)) ?>', 'primary');
        <?php endif; ?>
    });
</script>
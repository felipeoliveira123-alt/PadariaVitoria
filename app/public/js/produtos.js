document.addEventListener('DOMContentLoaded', function() {
    // Check for success message in sessionStorage
    const successMessage = sessionStorage.getItem('productUpdateMessage');
    if (successMessage) {
        showToast(successMessage);
        sessionStorage.removeItem('productUpdateMessage');
    }

    function showAlert(message, type) {
        let alertPlaceholder = document.getElementById('alertPlaceholder');
        if (!alertPlaceholder) {
            // Create the alert placeholder if it doesn't exist
            alertPlaceholder = document.createElement('div');
            alertPlaceholder.id = 'alertPlaceholder';
            // Insert it at the top of the main content area
            const mainContent = document.querySelector('main') || document.body;
            mainContent.insertBefore(alertPlaceholder, mainContent.firstChild);
        }

        const wrapper = document.createElement('div');
        wrapper.innerHTML = `
            <div class="alert alert-${type} alert-dismissible" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        alertPlaceholder.innerHTML = '';
        alertPlaceholder.append(wrapper);
    }

    function showToast(message) {
        let toastElement = document.getElementById('productToast');
        if (!toastElement) {
            // Create toast container if it doesn't exist
            let container = document.querySelector('.toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'toast-container position-fixed top-0 end-0 p-3';
                document.body.appendChild(container);
            }
            
            // Create toast element
            const wrapper = document.createElement('div');
            wrapper.innerHTML = `
                <div id="productToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-success text-white">
                        <strong class="me-auto">Sucesso</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body"></div>
                </div>
            `;
            container.appendChild(wrapper);
            toastElement = document.getElementById('productToast');
        }

        const toastBody = toastElement.querySelector('.toast-body');
        if (toastBody) {
            toastBody.textContent = message;
        }
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
    }

    // Handle product editing
    document.querySelectorAll('.edit-product').forEach(button => {
        button.addEventListener('click', async function() {
            const row = this.closest('tr');
            const productId = row.dataset.id;
            let currentModal = null;

            try {
                const response = await fetch(`produtos.php?id=${productId}`);
                if (!response.ok) throw new Error('Erro ao carregar produto');
                const produto = await response.json();

                document.getElementById('productName').value = produto.nome;
                document.getElementById('productDescription').value = produto.descricao || '';
                document.getElementById('productPrice').value = produto.preco;
                document.getElementById('productBarcode').value = produto.codigo_barras;
                document.getElementById('productCategory').value = produto.categoria || '';

                // Esconder campos de lote inicial na edição
                document.querySelectorAll('#productForm hr, #productForm h6, #productForm div.mb-3:nth-last-child(-n+3)').forEach(el => {
                    el.style.display = 'none';
                });

                document.getElementById('productModalTitle').textContent = 'Editar Produto';
                document.getElementById('saveProduct').textContent = 'Confirmar';
                
                currentModal = new bootstrap.Modal(document.getElementById('productModal'));
                currentModal.show();

                const form = document.getElementById('productForm');
                const submitHandler = async function(e) {
                    e.preventDefault();
                    
                    if (!this.checkValidity()) {
                        e.stopPropagation();
                        this.classList.add('was-validated');
                        return;
                    }

                    if (confirm('Tem certeza que deseja salvar as alterações?')) {
                        const productData = {
                            nome: document.getElementById('productName').value,
                            descricao: document.getElementById('productDescription').value,
                            preco: parseFloat(document.getElementById('productPrice').value),
                            codigo_barras: document.getElementById('productBarcode').value,
                            categoria: document.getElementById('productCategory').value
                        };

                        try {
                            const response = await fetch(`produtos.php?id=${productId}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify(productData)
                            });

                            if (!response.ok) throw new Error('Erro ao atualizar produto');

                            currentModal.hide();
                            sessionStorage.setItem('productUpdateMessage', 'Produto atualizado com sucesso!');
                            location.reload();
                        } catch (error) {
                            showAlert('Erro ao atualizar produto: ' + error.message, 'danger');
                        }
                    }
                };
                
                form.addEventListener('submit', submitHandler, { once: true });
            } catch (error) {
                showAlert('Erro ao carregar produto: ' + error.message, 'danger');
            }
        });
    });

    // Handle new product creation
    document.getElementById('productForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const isEditing = document.getElementById('productModalTitle').textContent === 'Editar Produto';
        if (isEditing) return;

        if (!this.checkValidity()) {
            e.stopPropagation();
            this.classList.add('was-validated');
            return;
        }

        const productData = {
            nome: document.getElementById('productName').value,
            descricao: document.getElementById('productDescription').value,
            preco: parseFloat(document.getElementById('productPrice').value),
            codigo_barras: document.getElementById('productBarcode').value,
            categoria: document.getElementById('productCategory').value
        };

        const batchNumber = document.getElementById('batchNumber').value;
        const batchQuantity = document.getElementById('batchQuantity').value;
        const batchValidity = document.getElementById('batchValidity').value;

        if (batchNumber && batchQuantity && batchValidity) {
            productData.lote_inicial = {
                numero_lote: batchNumber,
                quantidade: parseInt(batchQuantity),
                validade: batchValidity
            };
        }

        try {
            const response = await fetch('produtos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(productData)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Erro ao criar produto');
            }

            sessionStorage.setItem('productUpdateMessage', 'Produto criado com sucesso!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
            modal.hide();
            location.reload();
        } catch (error) {
            showAlert('Erro ao criar produto: ' + error.message, 'danger');
        }
    });

    // Move o evento de click do saveProduct para submeter o formulário
    document.getElementById('saveProduct').addEventListener('click', function() {
        const form = document.getElementById('productForm');
        if (form) {
            form.requestSubmit();
        }
    });

    // Reset modal when closed
    document.getElementById('productModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('productForm').reset();
        document.getElementById('productModalTitle').textContent = 'Novo Produto';
        document.getElementById('saveProduct').textContent = 'Salvar';
        
        document.querySelectorAll('#productForm hr, #productForm h6, #productForm div.mb-3:nth-last-child(-n+3)').forEach(el => {
            el.style.display = 'block';
        });
    });

    // Handle batch management
    document.querySelectorAll('.manage-batches').forEach(button => {
        button.addEventListener('click', async function() {
            const row = this.closest('tr');
            const productId = row.dataset.id;
            
            // Function to load batches and update UI
            async function loadBatches(productId, batchModal) {
                try {
                    const response = await fetch(`produtos.php?id=${productId}&lotes=true`);
                    if (!response.ok) throw new Error('Erro ao carregar lotes');
                    const data = await response.json();
                    
                    const batchTableBody = document.getElementById('batchTableBody');
                    batchTableBody.innerHTML = '';
                    
                    if (data.response && data.response.length > 0) {
                        data.response.forEach(batch => {
                            const tr = document.createElement('tr');
                            tr.dataset.id = batch.id;
                            // Use date string without timezone conversion for display
                            // The format YYYY-MM-DD works correctly with the Date constructor
                            const dateStr = batch.validade.split('T')[0];
                            const validityDate = new Date(dateStr + 'T12:00:00'); // Adding noon time to avoid timezone issues
                            tr.innerHTML = `
                                <td>${batch.numero_lote}</td>
                                <td>${batch.quantidade}</td>
                                <td>${validityDate.toLocaleDateString('pt-BR')}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger delete-batch" data-batch-id="${batch.id}">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            `;
                            batchTableBody.appendChild(tr);
                        });
                        
                        // Reattach delete handlers to new buttons
                        setupBatchDeletionHandlers(productId, batchModal);
                    } else {
                        // Show a message when there are no batches
                        const tr = document.createElement('tr');
                        tr.innerHTML = '<td colspan="4" class="text-center">Nenhum lote encontrado para este produto.</td>';
                        batchTableBody.appendChild(tr);
                    }
                    
                    return data.response || [];
                } catch (error) {
                    showToast('Erro ao carregar lotes: ' + error.message, 'danger');
                    return [];
                }
            }
            
            // Function to set up batch deletion handlers
            function setupBatchDeletionHandlers(productId, batchModal) {
                document.querySelectorAll('.delete-batch').forEach(deleteBtn => {
                    deleteBtn.addEventListener('click', async function(e) {
                        e.preventDefault();
                        const batchId = this.dataset.batchId;
                        const row = this.closest('tr');
                        
                        if (confirm('Tem certeza que deseja excluir este lote?')) {
                            try {
                                const response = await fetch(`produtos.php?id=${productId}&lote_id=${batchId}`, {
                                    method: 'DELETE'
                                });
                                
                                if (!response.ok) throw new Error('Erro ao excluir lote');
                                
                                // Remove the row directly without reloading the page
                                row.remove();
                                
                                // Show success message
                                showToast('Lote excluído com sucesso!', 'success');
                                
                                // If table is now empty, refresh the entire modal content
                                if (document.querySelectorAll('#batchTableBody tr').length === 0) {
                                    await loadBatches(productId, batchModal);
                                }
                            } catch (error) {
                                showToast('Erro ao excluir lote: ' + error.message, 'danger');
                            }
                        }
                    });
                });
            }
              const batchModal = new bootstrap.Modal(document.getElementById('batchModal'));
            batchModal.show();
            
            // Load batches first time
            await loadBatches(productId, batchModal);
            
            // Set up batch form submission
            const batchForm = document.getElementById('batchForm');
            if (batchForm) {
                // Remove any existing event listeners to prevent duplicates
                const newBatchForm = batchForm.cloneNode(true);
                batchForm.parentNode.replaceChild(newBatchForm, batchForm);
                
                newBatchForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                      // Create a reference to form elements that won't change during async operations
                    const numeroLote = document.getElementById('newBatchNumber').value;
                    const quantidade = parseInt(document.getElementById('newBatchQuantity').value);
                    const validadeInput = document.getElementById('newBatchValidity').value;
                    
                    // Validate form
                    if (!numeroLote || !quantidade || !validadeInput) {
                        showToast('Por favor, preencha todos os campos do lote', 'warning');
                        return;
                    }
                    
                    // Ensure the date is sent in YYYY-MM-DD format without timezone adjustment
                    const batchData = {
                        tipo: 'lote',
                        produto_id: productId,
                        numero_lote: numeroLote,
                        quantidade: quantidade,
                        validade: validade, // Now correctly formatted as yyyy-mm-dd
                        preserve_date: true // Flag to tell server not to adjust for timezone
                    };
                    
                    try {
                        const response = await fetch('produtos.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(batchData)
                        });
                        
                        if (!response.ok) throw new Error('Erro ao criar lote');
                        
                        // Reset form
                        this.reset();
                        
                        // Reload batches to show the new one
                        await loadBatches(productId, batchModal);
                        
                        showToast('Lote adicionado com sucesso!', 'success');
                    } catch (error) {
                        showToast('Erro ao adicionar lote: ' + error.message, 'danger');
                    }
                });
            }
        });
    });

    // Handle product deletion
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', async function() {
            if (confirm('Tem certeza que deseja excluir este produto e todos os seus lotes?')) {
                const row = this.closest('tr');
                const productId = row.dataset.id;

                try {
                    const response = await fetch(`produtos.php?id=${productId}`, {
                        method: 'DELETE'
                    });

                    if (!response.ok) throw new Error('Erro ao excluir produto');

                    // Remove the row directly without refreshing the page
                    row.remove();
                    
                    // Show success message
                    showToast('Produto excluído com sucesso!', 'success');
                    
                    // If table is now empty, show a message
                    const tbody = document.querySelector('.table tbody');
                    if (tbody && tbody.children.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="8" class="text-center">Nenhum produto encontrado.</td></tr>';
                    }
                } catch (error) {
                    showToast('Erro ao excluir produto: ' + error.message, 'danger');
                }
            }
        });
    });
});
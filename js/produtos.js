document.addEventListener('DOMContentLoaded', function() {
    function showAlert(message, type) {
        const alertPlaceholder = document.getElementById('alertPlaceholder');
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
        const toastElement = document.getElementById('productToast');
        const toastBody = toastElement.querySelector('.toast-body');
        toastBody.textContent = message;
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
    }

    // Handle product editing
    document.querySelectorAll('.edit-product').forEach(button => {
        button.addEventListener('click', async function() {
            const row = this.closest('tr');
            const productId = row.dataset.id;

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
                const saveButton = document.getElementById('saveProduct');
                saveButton.textContent = 'Confirmar';
                
                const modal = new bootstrap.Modal(document.getElementById('productModal'));
                modal.show();

                saveButton.onclick = async function() {
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

                            showToast('Produto atualizado com sucesso!');
                            modal.hide();
                            location.reload();
                        } catch (error) {
                            showAlert('Erro ao atualizar produto: ' + error.message, 'danger');
                        }
                    }
                };
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

            showAlert('Produto criado com sucesso!', 'success');
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
            
            try {
                const response = await fetch(`produtos.php?id=${productId}&lotes=true`);
                if (!response.ok) throw new Error('Erro ao carregar lotes');
                const data = await response.json();
                
                const batchTableBody = document.getElementById('batchTableBody');
                batchTableBody.innerHTML = '';
                
                data.response.forEach(batch => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${batch.numero_lote}</td>
                        <td>${batch.quantidade}</td>
                        <td>${new Date(batch.validade).toLocaleDateString('pt-BR')}</td>
                        <td>
                            <button class="btn btn-sm btn-danger delete-batch" data-batch-id="${batch.id}">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </td>
                    `;
                    batchTableBody.appendChild(tr);
                });
                
                const batchModal = new bootstrap.Modal(document.getElementById('batchModal'));
                batchModal.show();
                
                // Set up batch form submission
                document.getElementById('batchForm').onsubmit = async function(e) {
                    e.preventDefault();
                    
                    const batchData = {
                        tipo: 'lote',
                        produto_id: productId,
                        numero_lote: document.getElementById('newBatchNumber').value,
                        quantidade: parseInt(document.getElementById('newBatchQuantity').value),
                        validade: document.getElementById('newBatchValidity').value
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
                        
                        showAlert('Lote adicionado com sucesso!', 'success');
                        location.reload();
                    } catch (error) {
                        showAlert('Erro ao adicionar lote: ' + error.message, 'danger');
                    }
                };
                
                // Handle batch deletion
                document.querySelectorAll('.delete-batch').forEach(deleteBtn => {
                    deleteBtn.addEventListener('click', async function() {
                        if (confirm('Tem certeza que deseja excluir este lote?')) {
                            const batchId = this.dataset.batchId;
                            try {
                                const response = await fetch(`produtos.php?id=${productId}&lote_id=${batchId}`, {
                                    method: 'DELETE'
                                });
                                
                                if (!response.ok) throw new Error('Erro ao excluir lote');
                                
                                showAlert('Lote excluído com sucesso!', 'success');
                                this.closest('tr').remove();
                                location.reload();
                            } catch (error) {
                                showAlert('Erro ao excluir lote: ' + error.message, 'danger');
                            }
                        }
                    });
                });
                
            } catch (error) {
                showAlert('Erro ao carregar lotes: ' + error.message, 'danger');
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

                    showAlert('Produto excluído com sucesso!', 'success');
                    row.remove();
                } catch (error) {
                    showAlert('Erro ao excluir produto: ' + error.message, 'danger');
                }
            }
        });
    });
});
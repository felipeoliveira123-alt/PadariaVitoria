<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalTitle">Novo Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productForm" class="needs-validation" novalidate>
                    <input type="hidden" id="isEditingProduct" name="isEditingProduct" value="0">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="productName" name="nome" required>
                        <div class="invalid-feedback">
                            Por favor, informe o nome do produto.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Descrição</label>
                        <textarea class="form-control" id="productDescription" name="descricao" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Preço</label>
                        <input type="number" class="form-control" id="productPrice" name="preco" step="0.01" min="0" required>
                        <div class="invalid-feedback">
                            Por favor, informe um preço válido.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="productBarcode" class="form-label">Código de Barras</label>
                        <input type="text" class="form-control" id="productBarcode" name="codigo_barras" required>
                        <div class="invalid-feedback">
                            Por favor, informe o código de barras.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="productCategory" class="form-label">Categoria</label>
                        <input type="text" class="form-control" id="productCategory" name="categoria">
                    </div>
                    <hr>
                    <h6>Lote Inicial</h6>
                    <div class="mb-3">
                        <label for="batchNumber" class="form-label">Número do Lote</label>
                        <input type="text" class="form-control" id="batchNumber" name="numero_lote">
                    </div>
                    <div class="mb-3">
                        <label for="batchQuantity" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="batchQuantity" name="quantidade" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="batchValidity" class="form-label">Validade</label>
                        <input type="date" class="form-control" id="batchValidity" name="validade">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveProduct">Salvar</button>
            </div>
        </div>
    </div>
</div>
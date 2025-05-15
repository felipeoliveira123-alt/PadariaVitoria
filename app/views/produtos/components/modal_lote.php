<div class="modal fade" id="batchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gerenciar Lotes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="batchForm" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="newBatchNumber" class="form-label">Número do Lote</label>
                                <input type="text" class="form-control" id="newBatchNumber" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="newBatchQuantity" class="form-label">Quantidade</label>
                                <input type="number" class="form-control" id="newBatchQuantity" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="newBatchValidity" class="form-label">Validade</label>
                                <input type="text" class="form-control date-mask" id="newBatchValidity" 
                                       placeholder="DD/MM/AAAA" data-inputmask="'mask': '99/99/9999'" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Adicionar Lote</button>
                </form>
                <div id="batchList">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Lote</th>
                                <th>Quantidade</th>
                                <th>Validade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="batchTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
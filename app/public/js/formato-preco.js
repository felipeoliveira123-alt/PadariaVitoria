/**
 * formato-preco.js
 * 
 * Este script formata campos de entrada de preço para usar vírgula como separador decimal.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Função para aplicar máscara de preço
    function aplicarMascaraPreco(input) {
        input.addEventListener('input', function(e) {
            // Obtém apenas os números do input
            let valor = this.value.replace(/\D/g, '');
            
            // Se não tiver valor, retorna vazio
            if (valor === '') {
                this.value = '';
                return;
            }
            
            // Converte para número com 2 casas decimais
            valor = (parseInt(valor) / 100).toFixed(2);
            
            // Substitui ponto por vírgula
            valor = valor.replace('.', ',');
            
            // Formata com separadores de milhar se necessário
            if (valor.length > 6) {
                // Adiciona separador de milhar (ponto)
                const partes = valor.split(',');
                partes[0] = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                valor = partes.join(',');
            }
            
            // Atualiza o valor do input
            this.value = valor;
            
            // Armazena o valor original (com ponto) como atributo data para uso no envio do formulário
            this.dataset.valorNumerico = valor.replace('.', '').replace(',', '.');
        });
    }
    
    // Função para preparar o formulário antes do envio
    function prepararFormularioParaEnvio(form) {
        form.addEventListener('submit', function(e) {
            // Encontra todos os inputs com classe preco-input
            const inputs = form.querySelectorAll('.preco-input');
            
            inputs.forEach(function(input) {
                // Converte o valor formatado para o formato que o servidor espera (com ponto)
                if (input.dataset.valorNumerico) {
                    input.value = input.dataset.valorNumerico;
                } else {
                    // Fallback se o dataset não existir
                    input.value = input.value.replace('.', '').replace(',', '.');
                }
            });
        });
    }
    
    // Aplica a máscara a todos os campos de entrada de preço existentes
    document.querySelectorAll('.preco-input').forEach(function(input) {
        aplicarMascaraPreco(input);
    });
    
    // Prepara todos os formulários com campos de preço
    document.querySelectorAll('form').forEach(function(form) {
        if (form.querySelector('.preco-input')) {
            prepararFormularioParaEnvio(form);
        }
    });
    
    // Configura um MutationObserver para monitorar novos campos que possam ser adicionados dinamicamente
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Elemento
                    // Verifica novos campos .preco-input
                    const newInputs = node.querySelectorAll ? node.querySelectorAll('.preco-input') : [];
                    newInputs.forEach(function(input) {
                        aplicarMascaraPreco(input);
                    });
                    
                    // Verifica novos formulários
                    const newForms = node.querySelectorAll ? node.querySelectorAll('form') : [];
                    newForms.forEach(function(form) {
                        if (form.querySelector('.preco-input')) {
                            prepararFormularioParaEnvio(form);
                        }
                    });
                }
            });
        });
    });
    
    // Iniciar observação do DOM
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});
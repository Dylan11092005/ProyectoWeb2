<style>
.modal-bg {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(33,147,176,0.25);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}
.modal-bg.active {
    display: flex;
}
.modal {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(33, 147, 176, 0.18);
    padding: 32px 28px 24px 28px;
    min-width: 320px;
    max-width: 95vw;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
}
.modal .close-modal {
    position: absolute;
    top: 12px;
    right: 18px;
    background: none;
    border: none;
    font-size: 22px;
    color: #2193b0;
    cursor: pointer;
}
.modal label {
    display: block;
    margin-bottom: 6px;
    color: #2193b0;
    font-size: 15px;
    text-align: left;
}
.modal input, .modal select, .modal textarea {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 16px;
    border: 1px solid #b2ebf2;
    border-radius: 8px;
    font-size: 15px;
    background: #f7fafd;
    transition: border-color 0.2s;
    box-sizing: border-box;
}
.modal input:focus, .modal select:focus, .modal textarea:focus {
    border-color: #2193b0;
    outline: none;
}
.modal button[type="submit"] {
    width: 100%;
    padding: 12px;
    background: linear-gradient(90deg, #2193b0 0%, #6dd5ed 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(33, 147, 176, 0.10);
    transition: background 0.2s;
}
.modal button[type="submit"]:hover {
    background: linear-gradient(90deg, #6dd5ed 0%, #2193b0 100%);
}
</style>
<div class="modal-bg" id="modal">
    <div class="modal">
        <button class="close-modal" onclick="cerrarModal()">&times;</button>
        <h2 id="modalTitulo">Modal</h2>
        <form id="modalForm" method="POST" action="">
            <div id="modalCampos">
            </div>
            <button type="submit" id="modalBoton">Guardar</button>
        </form>
    </div>
</div>
<script>
function abrirModal(config) {
    document.getElementById('modal').classList.add('active');
    document.getElementById('modalTitulo').textContent = config.titulo || 'Modal';
    document.getElementById('modalBoton').textContent = config.boton || 'Guardar';
    const camposDiv = document.getElementById('modalCampos');
    camposDiv.innerHTML = '';
    (config.campos || []).forEach(function(campo) {
        let campoHtml = '';
        if (campo.type === 'select') {
            campoHtml += `<label for="${campo.name}">${campo.label}</label>`;
            campoHtml += `<select name="${campo.name}" id="${campo.name}"${campo.required ? ' required' : ''}>`;
            (campo.options || []).forEach(function(opt) {
                campoHtml += `<option value="${opt.value}"${campo.value == opt.value ? ' selected' : ''}>${opt.label}</option>`;
            });
            campoHtml += `</select>`;
        } else if (campo.type === 'textarea') {
            campoHtml += `<label for="${campo.name}">${campo.label}</label>`;
            campoHtml += `<textarea name="${campo.name}" id="${campo.name}"${campo.required ? ' required' : ''}>${campo.value || ''}</textarea>`;
        } else {
            campoHtml += `<label for="${campo.name}">${campo.label}</label>`;
            campoHtml += `<input type="${campo.type || 'text'}" name="${campo.name}" id="${campo.name}" value="${campo.value || ''}"${campo.required ? ' required' : ''}${campo.maxlength ? ' maxlength="'+campo.maxlength+'"' : ''}${campo.pattern ? ' pattern="'+campo.pattern+'"' : ''}>`;
        }
        camposDiv.innerHTML += campoHtml;
    });
    const form = document.getElementById('modalForm');
    form.onsubmit = function(e) {
        if (typeof config.onsubmit === 'function') {
            e.preventDefault();
            config.onsubmit(e, form);
        }
    };
}
function cerrarModal() {
    document.getElementById('modal').classList.remove('active');
    document.getElementById('modalCampos').innerHTML = '';
    document.getElementById('modalForm').onsubmit = null;
}
</script>

document.addEventListener('DOMContentLoaded', () => {

    const newSessionBtn = document.querySelector('.new-session-btn');
    const sessionsList = document.querySelector('.sessions-list');

    const sessionNumberInput = document.getElementById('session-number');
    const sessionDateInput = document.getElementById('session-date');
    const sessionNotes = document.getElementById('session-notes');

    const sessionKeywordsInput = document.getElementById('session-keywords');
    const keywordsContainer = document.querySelector('.keywords-container');

    const saveBtn = document.querySelector('.save-btn');

    const backBtn = document.getElementById('backBtn');

    const btnExcluirPaciente = document.getElementById('btnExcluirPaciente');

    const statusBtn = document.getElementById('btnStatus');

    const statusMenu = document.querySelector('.status-menu');

    let sessions = window.sessoesData || [];
    let currentSessionId = null;

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');

    // =========================================
    // 🔙 VOLTAR
    // =========================================

    if (backBtn) {

    backBtn.addEventListener('click', (e) => {

        e.preventDefault();

        window.location.href = '/dashboard';

    });

    }

    // =========================================
    // 🗑️ EXCLUIR PACIENTE
    // =========================================

    if (btnExcluirPaciente) {

    btnExcluirPaciente.addEventListener('click', () => {

        const pacienteId =
            btnExcluirPaciente.dataset.pacienteId;


        // pega datas válidas
        const datas = sessions.map(sessao => {

            return new Date(
                sessao.data_sessao.split('T')[0]
            );

        });

        // pega a data mais recente
        const dataUltimaSessao = new Date(
            Math.max(...datas)
        );

        // hoje
        const hoje = new Date();

        // diferença em anos
        let diferencaAnos =
            hoje.getFullYear() -
            dataUltimaSessao.getFullYear();

        // ajusta mês/dia
        const mesAtual = hoje.getMonth();
        const mesSessao = dataUltimaSessao.getMonth();

        if (
            mesAtual < mesSessao ||
            (
                mesAtual === mesSessao &&
                hoje.getDate() <
                dataUltimaSessao.getDate()
            )
        ) {

            diferencaAnos--;

        }

        console.log('Última sessão:', dataUltimaSessao);
        console.log('Diferença anos:', diferencaAnos);

        // bloqueia exclusão
        if (diferencaAnos < 5) {

           showToast(
            'Paciente não pode ser excluído porque a última sessão possui menos de 5 anos.',
            'warning'
        );

            return;

        }

        showConfirmToast(
    'Deseja excluir este paciente?',
    () => {

        fetch(`/pacientes/${pacienteId}`, {

            method: 'DELETE',

            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }

        })
        .then(async res => {

            const data = await res.json();

            if (!res.ok) {

                throw new Error(
                    data.message ||
                    'Erro ao excluir paciente'
                );

            }

            return data;

        })
        .then(data => {

            showToast(
                data.message,
                'success'
            );

            window.location.href = '/dashboard';

        })
        .catch(err => {

            showToast(
                err.message,
                'error'
            );

        });

    }
);

        if (!confirmar) return;

        fetch(`/pacientes/${pacienteId}`, {

            method: 'DELETE',

            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }

        })
        .then(async res => {

            const data = await res.json();

            if (!res.ok) {

                throw new Error(
                    data.message ||
                    'Erro ao excluir paciente'
                );

            }

            return data;

        })
        .then(data => {

            showToast(data.message);

            window.location.href = '/dashboard';

        })
        .catch(err => {

            console.error(err);

            showToasts(err.message);

        });

    });

}    // =========================================
    // 🔄 STATUS
    // =========================================

    if (statusBtn && statusMenu) {

        statusBtn.addEventListener('click', e => {

            e.stopPropagation();

            statusMenu.style.display =
                statusMenu.style.display === 'block'
                    ? 'none'
                    : 'block';

        });

        document.addEventListener('click', () => {

            statusMenu.style.display = 'none';

        });

        statusMenu.querySelectorAll('div')
            .forEach(option => {

                option.addEventListener('click', () => {

                    const novoStatus =
                        option.dataset.value;

                    const pacienteId =
                        statusBtn.dataset.pacienteId;

                    atualizarStatus(
                        pacienteId,
                        novoStatus,
                        null
                    );

                });

            });

    }

    function atualizarStatus(
        pacienteId,
        status,
        dataInativacao
    ) {

        fetch(`/pacientes/${pacienteId}/status`, {

            method: 'PATCH',

            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },

            body: JSON.stringify({

                status: status,

                data_inativacao: dataInativacao

            })

        })
        .then(res => {

            if (!res.ok) {

                throw new Error(
                    'Erro ao atualizar status'
                );

            }

            return res.json();

        })
        .then(data => {

            statusBtn.textContent =
                data.novo_status.charAt(0).toUpperCase() +
                data.novo_status.slice(1) +
                ' ▼';

            statusBtn.dataset.status =
                data.novo_status;

            statusBtn.style.backgroundColor =
                data.novo_status === 'ativo'
                    ? '#2ecc71'
                    : data.novo_status === 'pausa'
                        ? '#f39c12'
                        : '#e74c3c';

        })
        .catch(err => {

            console.error(err);

            showToast('Erro ao atualizar status');

        });

    }

    // =========================================
    // 🏷️ PALAVRAS-CHAVE
    // =========================================

    function getKeywords() {

        return Array.from(
            keywordsContainer.querySelectorAll('.keyword-tag')
        ).map(tag => tag.dataset.keyword);

    }

    function renderKeywords(keywords) {

        keywordsContainer.innerHTML = '';

        keywords.forEach(keyword => {

            const tag = document.createElement('div');

            tag.classList.add('keyword-tag');

            tag.dataset.keyword = keyword;

            tag.innerHTML = `
                <span>${keyword}</span>
                <span class="remove-keyword">✕</span>
            `;

            tag.querySelector('.remove-keyword')
                .addEventListener('click', () => {

                    tag.remove();

                });

            keywordsContainer.appendChild(tag);

        });

    }

    // =========================================
    // ➕ ADICIONAR TAGS
    // =========================================

    sessionKeywordsInput.addEventListener('keydown', e => {

        if (e.key !== 'Enter' && e.key !== ' ') return;

        e.preventDefault();

        const value = sessionKeywordsInput.value.trim();

        if (!value) return;

        const novasTags = value
            .split(' ')
            .map(t => t.trim())
            .filter(t => t.length);

        const existentes = getKeywords();

        novasTags.forEach(tag => {

            if (!existentes.includes(tag)) {

                existentes.push(tag);

            }

        });

        renderKeywords(existentes);

        sessionKeywordsInput.value = '';

    });

    // =========================================
    // 📋 RENDER SESSÕES
    // =========================================

    function renderSessions() {

        sessions.sort(
            (a, b) =>
                b.numero_sessao - a.numero_sessao
        );

        sessionsList.innerHTML = '';

        sessions.forEach(sessao => {

            const div = document.createElement('div');

            div.classList.add('session-item');

            if (sessao.id == currentSessionId) {

                div.classList.add('active');

            }

            const dateParts = sessao.data_sessao
                .split('T')[0]
                .split('-');

            const formattedDate =
                `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;

            div.textContent = formattedDate;

            div.dataset.id = sessao.id;

            div.addEventListener('click', () => {

                selectSession(sessao.id);

            });

            sessionsList.appendChild(div);

        });

    }

    // =========================================
    // 📂 SELECIONAR SESSÃO
    // =========================================

    function selectSession(id) {

        const sessao = sessions.find(
            s => s.id == id
        );

        if (!sessao) return;

        currentSessionId = sessao.id;

        document
            .querySelectorAll('.session-item')
            .forEach(item =>
                item.classList.remove('active')
            );

        document
            .querySelector(
                `.session-item[data-id="${id}"]`
            )
            ?.classList.add('active');

        sessionNumberInput.value =
            sessao.numero_sessao;

        sessionDateInput.value =
            sessao.data_sessao.split('T')[0];

        sessionNotes.value =
            sessao.registro;

        let keywords = [];

        try {

            keywords = sessao.palavras_chave
                ? JSON.parse(sessao.palavras_chave)
                : [];

        } catch {

            keywords = [];

        }

        renderKeywords(keywords);

    }


    function showToast(message, type = 'success') {

    const container =
        document.getElementById('toast-container');

    const toast =
        document.createElement('div');

    toast.className = `toast ${type}`;

    toast.textContent = message;

    container.appendChild(toast);

    setTimeout(() => {

        toast.remove();

    }, 3000);

    }
    
    function showConfirmToast(message, onConfirm) {

    const container =
        document.getElementById('toast-container');

    const toast =
        document.createElement('div');

    toast.className =
        'toast confirm-toast';

    toast.innerHTML = `

        <div class="toast-message">
            ${message}
        </div>

        <div class="toast-actions">

            <button class="toast-cancel">
                Cancelar
            </button>

            <button class="toast-confirm">
                Excluir
            </button>

        </div>

    `;

    container.appendChild(toast);

    toast
        .querySelector('.toast-cancel')
        .addEventListener('click', () => {

            toast.remove();

        });

    toast
        .querySelector('.toast-confirm')
        .addEventListener('click', () => {

            onConfirm();

            toast.remove();

        });

}

    // =========================================
    // ➕ NOVA SESSÃO
    // =========================================

    newSessionBtn.addEventListener('click', () => {

        currentSessionId = undefined;

        sessionNumberInput.value =
            sessions.length
                ? Math.max(
                    ...sessions.map(
                        s => s.numero_sessao
                    )
                ) + 1
                : 1;

        sessionDateInput.value =
            new Date()
                .toISOString()
                .split('T')[0];

        sessionNotes.value = '';

        sessionKeywordsInput.value = '';

        keywordsContainer.innerHTML = '';

        document
            .querySelectorAll('.session-item')
            .forEach(item =>
                item.classList.remove('active')
            );

    });

    // =========================================
    // 💾 SALVAR SESSÃO
    // =========================================

    saveBtn.addEventListener('click', () => {

        const payload = {

            numero_sessao:
                sessionNumberInput.value,

            data_sessao:
                sessionDateInput.value,

            registro:
                sessionNotes.value,

            palavras_chave: JSON.stringify(
                getKeywords()
            )

        };

        const url = currentSessionId
            ? `/pacientes/${window.pacienteId}/sessoes/${currentSessionId}`
            : `/pacientes/${window.pacienteId}/sessoes`;

        fetch(url, {

            method:
                currentSessionId
                    ? 'PUT'
                    : 'POST',

            headers: {
                'Content-Type':
                    'application/json',

                'X-CSRF-TOKEN':
                    csrfToken
            },

            body: JSON.stringify(payload)

        })
        .then(res => {

            if (!res.ok) {

                throw new Error(
                    'Erro ao salvar'
                );

            }

            return res.json();

        })
        .then(data => {

            // editar
            if (currentSessionId) {

                const index =
                    sessions.findIndex(
                        s =>
                            s.id ==
                            currentSessionId
                    );

                if (index !== -1) {

                    sessions[index] = data;

                }

            }

            // nova
            else {

                sessions.unshift(data);

                currentSessionId =
                    data.id;

            }

            renderSessions();

            selectSession(
                currentSessionId
            );

            showToast(
                'Sessão salva com sucesso'
            );

        })
        .catch(err => {

            console.error(err);

            showToast(
                'Erro ao salvar sessão',
                'error'
            );

        });

    });

    // =========================================
    // 🚀 INIT
    // =========================================

    renderSessions();

    if (sessions.length) {

        selectSession(
            sessions[0].id
        );

    }

});

document.addEventListener('DOMContentLoaded', () => {
    const newSessionBtn = document.querySelector('.new-session-btn');
    const sessionsList = document.querySelector('.sessions-list');
    const sessionNumberInput = document.getElementById('session-number');
    const sessionDateInput = document.getElementById('session-date');
    const sessionNotes = document.getElementById('session-notes');
    const sessionKeywordsInput = document.getElementById('session-keywords');
    const keywordsContainer = document.querySelector('.keywords-container');
    const saveBtn = document.querySelector('.save-btn');
    const btnExcluirPaciente = document.getElementById('btnExcluirPaciente');

    let sessions = window.sessoesData || [];
    let currentSessionId = null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ===== FUNÇÃO PARA EXCLUIR PACIENTE =====
    if (btnExcluirPaciente) {
        btnExcluirPaciente.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const pacienteId = this.getAttribute('data-paciente-id');
            
            if (confirm('Tem certeza que deseja excluir este paciente? Esta ação não pode ser desfeita.')) {
                excluirPaciente(pacienteId);
            }
        });
    }

    function excluirPaciente(pacienteId) {
        fetch(`/pacientes/${pacienteId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Redireciona para a dashboard após exclusão bem-sucedida
                window.location.href = '/dashboard';
            } else {
                alert(data.message || 'Erro ao excluir paciente.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            if (error.message) {
                alert(error.message);
            } else {
                alert('Erro ao excluir paciente. Verifique se o paciente não possui sessões cadastradas.');
            }
        });
    }

    // ===== FUNÇÕES PARA GERENCIAR SESSÕES =====
    function renderSessions() {
        // Ordena por numero_sessao crescente
        sessions.sort((a, b) => a.numero_sessao - b.numero_sessao);

        sessionsList.innerHTML = '';
        sessions.forEach(sessao => {
            const div = document.createElement('div');
            div.classList.add('session-item');
            if (sessao.id == currentSessionId) div.classList.add('active');

            const dateParts = sessao.data_sessao.split('T')[0].split('-'); // ["2025","10","01"]
            const formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
            div.textContent = formattedDate;

            div.dataset.id = sessao.id;
            div.addEventListener('click', () => selectSession(sessao.id));
            sessionsList.appendChild(div);
        });
    }

    function selectSession(id) {
        const sessao = sessions.find(s => s.id == id);
        if (!sessao) return;

        currentSessionId = sessao.id;
        document.querySelectorAll('.session-item').forEach(item => item.classList.remove('active'));
        document.querySelector(`.session-item[data-id="${id}"]`)?.classList.add('active');

        sessionNumberInput.value = sessao.numero_sessao;
        sessionDateInput.value = sessao.data_sessao.split('T')[0];
        sessionNotes.value = sessao.registro;

        const keywords = sessao.palavras_chave
            ? JSON.parse(sessao.palavras_chave)
            : [];
        renderKeywords(keywords);
    }

    function renderKeywords(keywords) {
        keywordsContainer.innerHTML = '';
        keywords.forEach(k => {
            const span = document.createElement('span');
            span.classList.add('keyword-tag');
            span.textContent = k;
            keywordsContainer.appendChild(span);
        });
    }

    newSessionBtn.addEventListener('click', () => {
        currentSessionId = null;
        sessionNumberInput.value = sessions.length ? Math.max(...sessions.map(s => s.numero_sessao)) + 1 : 1;
        sessionDateInput.value = new Date().toISOString().split('T')[0];
        sessionNotes.value = '';
        sessionKeywordsInput.value = '';
        keywordsContainer.innerHTML = '';
        document.querySelectorAll('.session-item').forEach(item => item.classList.remove('active'));
    });

    sessionKeywordsInput.addEventListener('keypress', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const value = sessionKeywordsInput.value.trim();
            if (!value) return;

            const tags = value.split(',').map(k => k.trim());
            const existingTags = Array.from(keywordsContainer.querySelectorAll('.keyword-tag')).map(k => k.textContent);
            const merged = [...existingTags, ...tags].filter((v, i, a) => a.indexOf(v) === i);

            renderKeywords(merged);
            sessionKeywordsInput.value = '';
        }
    });

    saveBtn.addEventListener('click', () => {
        const payload = {
            numero_sessao: sessionNumberInput.value,
            data_sessao: sessionDateInput.value,
            registro: sessionNotes.value,
            palavras_chave: JSON.stringify(
                Array.from(keywordsContainer.querySelectorAll('.keyword-tag')).map(k => k.textContent)
            )
        };

        const url = currentSessionId 
            ? `/pacientes/${window.pacienteId}/sessoes/${currentSessionId}`
            : `/pacientes/${window.pacienteId}/sessoes`;

        fetch(url, {
            method: currentSessionId ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        })
        .then(res => {
            if (!res.ok) throw new Error('Erro ao salvar a sessão');
            return res.json();
        })
        .then(data => {
            if (currentSessionId) {
                const index = sessions.findIndex(s => s.id == currentSessionId);
                sessions[index] = data;
            } else {
                sessions.push(data);
                currentSessionId = data.id;
            }
            renderSessions();
            selectSession(currentSessionId);
            alert('Sessão salva com sucesso!');
        })
        .catch(err => {
            console.error(err);
            alert('Erro ao salvar a sessão! Veja o console para detalhes.');
        });
    });
    
    
   // Controle do menu de status
const statusBtn = document.getElementById('btnStatus');
const statusMenu = document.querySelector('.status-menu');

if (statusBtn && statusMenu) {
    // Exibe/esconde o menu
    statusBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        statusMenu.style.display = statusMenu.style.display === 'block' ? 'none' : 'block';
    });

    // Fecha o menu ao clicar fora
    document.addEventListener('click', () => {
        statusMenu.style.display = 'none';
    });

    // Clique em uma opção do menu
    statusMenu.querySelectorAll('div').forEach(option => {
        option.addEventListener('click', () => {
            const novoStatus = option.dataset.value;
            const pacienteId = statusBtn.dataset.pacienteId;

            fetch(`/pacientes/${pacienteId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ status: novoStatus })
            })
            .then(res => {
                if (!res.ok) throw new Error('Erro ao atualizar status');
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    statusBtn.textContent = data.novo_status.charAt(0).toUpperCase() + data.novo_status.slice(1) + ' ▼';
                    statusBtn.dataset.status = data.novo_status;

                    // Atualiza cor do botão conforme status
                    statusBtn.style.backgroundColor =
                        data.novo_status === 'ativo' ? '#2ecc71' :
                        data.novo_status === 'pausa' ? '#f39c12' : '#e74c3c';

                    alert(`Status atualizado para: ${data.novo_status}`);
                }
                statusMenu.style.display = 'none';
            })
            .catch(err => {
                console.error(err);
                alert('Erro ao atualizar o status!');
            });
        });
    });
}

    // Inicializa renderização
    renderSessions();
    if(sessions.length) selectSession(sessions[0].id);
});

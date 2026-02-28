document.addEventListener('DOMContentLoaded', () => {
    const newPatientBtn = document.getElementById('newPatientBtn');
    const patientsContainer = document.getElementById("patientsContainer");
    const searchInput = document.getElementById("searchInput");
    const searchClear = document.getElementById("searchClear");
    const paginationContainer = document.getElementById("paginationContainer");
    const prevPageBtn = document.getElementById("prevPageBtn");
    const nextPageBtn = document.getElementById("nextPageBtn");

    let currentPage = 1;
    let lastPage = 1;

    if (newPatientBtn) {
        newPatientBtn.addEventListener("click", () => {
            window.location.href = "cadastrar"; 
        });
    }

    if (searchClear) {
        searchClear.addEventListener('click', function() {
            searchInput.value = '';
            searchClear.classList.remove('show');
            loadPacientes(1, '');
            searchInput.focus();
        });

        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                searchClear.classList.add('show');
            } else {
                searchClear.classList.remove('show');
            }
        });
    }

    function loadPacientes(page = 1, query = "") {
        let url = `/pacientes?page=${page}`;
        if (query) url += `&search=${query}`;

        patientsContainer.innerHTML = '<div class="loading">Carregando pacientes...</div>';

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta da API');
                }
                return response.json();
            })
            .then(result => {
                const pacientes = result.data; 
                currentPage = result.current_page;
                lastPage = result.last_page;

                renderPacientes(pacientes);
                renderPagination();
            })
            .catch(error => {
                console.error("Erro ao carregar pacientes:", error);
                patientsContainer.innerHTML = `<p class="no-results">Erro ao carregar pacientes.</p>`;
            });
    }

    function renderPacientes(pacientes) {
        patientsContainer.innerHTML = "";

        if (!pacientes || pacientes.length === 0) {
            patientsContainer.innerHTML = `<p class="no-results">Nenhum paciente encontrado.</p>`;
            return;
        }

        pacientes.forEach(paciente => {
            const card = document.createElement("div");
            card.classList.add("patient-card");
            card.innerHTML = `
                <div class="patient-name">${paciente.nome}</div>
                <div class="patient-cpf">CPF: ${formatCPF(paciente.cpf)}</div>
                <div class="patient-info">
                    <div class="status-badge status-${paciente.status}">
                        ${getStatusText(paciente.status)}
                    </div>
                </div>
            `;

            card.addEventListener("click", () => {
                window.location.href = `/pacientes/${paciente.id}`;
            });

            patientsContainer.appendChild(card);
        });
    }

    function renderPagination() {
        paginationContainer.innerHTML = "";
        
        const startPage = Math.max(1, currentPage - 1);
        const endPage = Math.min(lastPage, currentPage + 1);
        
        // Primeira página
        if (startPage > 1) {
            const firstPage = document.createElement('span');
            firstPage.className = 'page-number';
            firstPage.textContent = '1';
            firstPage.addEventListener('click', () => loadPacientes(1, searchInput.value));
            paginationContainer.appendChild(firstPage);
            
            if (startPage > 2) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'page-number';
                ellipsis.textContent = '...';
                ellipsis.style.cursor = 'default';
                ellipsis.style.background = 'none';
                ellipsis.style.border = 'none';
                paginationContainer.appendChild(ellipsis);
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement("span");
            pageBtn.classList.add("page-number");
            if (i === currentPage) pageBtn.classList.add("active");
            pageBtn.textContent = i;
            pageBtn.addEventListener("click", () => loadPacientes(i, searchInput.value));
            paginationContainer.appendChild(pageBtn);
        }

        if (endPage < lastPage) {
            if (endPage < lastPage - 1) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'page-number';
                ellipsis.textContent = '...';
                ellipsis.style.cursor = 'default';
                ellipsis.style.background = 'none';
                ellipsis.style.border = 'none';
                paginationContainer.appendChild(ellipsis);
            }
            
            const lastPageBtn = document.createElement('span');
            lastPageBtn.className = 'page-number';
            lastPageBtn.textContent = lastPage;
            lastPageBtn.addEventListener('click', () => loadPacientes(lastPage, searchInput.value));
            paginationContainer.appendChild(lastPageBtn);
        }

        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === lastPage;
    }

    function formatCPF(cpf) {
        if (!cpf) return 'Não informado';
        cpf = cpf.toString().padStart(11, '0');
        return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    }

    function getStatusText(status) {
        const statusMap = {
            'ativo': 'Ativo',
            'pausa': 'Pausa',
            'inativo': 'Inativo'
        };
        return statusMap[status] || status;
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    prevPageBtn.addEventListener("click", () => {
        if (currentPage > 1) loadPacientes(currentPage - 1, searchInput.value);
    });

    nextPageBtn.addEventListener("click", () => {
        if (currentPage < lastPage) loadPacientes(currentPage + 1, searchInput.value);
    });

    if (searchInput) {
        searchInput.addEventListener("input", debounce(() => {
            loadPacientes(1, searchInput.value);
        }, 500));
    }

    loadPacientes();
});

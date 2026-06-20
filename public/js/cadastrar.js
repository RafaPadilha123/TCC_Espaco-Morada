document.addEventListener('DOMContentLoaded', () => {

    const select = document.getElementById('statusSelect');
    const selected = select.querySelector('.select-selected');
    const items = select.querySelector('.select-items');
    const hiddenInput = document.getElementById('status');
    const backBtn = document.getElementById('backBtn');

    if (backBtn) {

        backBtn.addEventListener('click', (e) => {

            e.preventDefault();
            e.stopPropagation();

            window.location.href = '/dashboard';

        });

    }

    selected.addEventListener('click', (e) => {

        e.stopPropagation();

        items.style.display =
            items.style.display === 'block'
                ? 'none'
                : 'block';

    });

    items.querySelectorAll('div').forEach(item => {

        item.addEventListener('click', () => {

            selected.textContent = item.textContent;

            hiddenInput.value =
                item.getAttribute('data-value');

            items.style.display = 'none';

        });

    });

    document.addEventListener('click', (e) => {

        if (!select.contains(e.target)) {

            items.style.display = 'none';

        }

    });

    const form = document.getElementById('cadastroForm');

    form.addEventListener('submit', function(e) {

        e.preventDefault();

        if (!hiddenInput.value) {

            showToast('Selecione um status antes de enviar.', 'warning');

            return;

        }

        const formData = new FormData(this);

     fetch(this.action, {

    method: form.method,

    body: formData,

    headers: {

        'X-Requested-With': 'XMLHttpRequest',

        'X-CSRF-TOKEN':
            document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content')

    }

})

.then(async res => {

    const data = await res.json();

    if (!res.ok) {

        if (data.errors?.cpf) {

            showToast(data.errors.cpf[0], 'error');

            return;

        }

        showToast('Erro ao salvar paciente.', 'error');

        return;

    }

    if (data.success) {

        window.location.href = data.redirect;

    }

})

.catch(err => {

    console.error(err);

    showToast('Erro ao salvar paciente.', 'error');

});

    });

});

document.addEventListener('DOMContentLoaded', () => {
 
    const select = document.getElementById('statusSelect');
    const selected = select.querySelector('.select-selected');
    const items = select.querySelector('.select-items');
    const hiddenInput = document.getElementById('status');

    selected.addEventListener('click', () => {
        items.style.display = items.style.display === 'block' ? 'none' : 'block';
    });

    items.querySelectorAll('div').forEach(item => {
        item.addEventListener('click', () => {
            selected.textContent = item.textContent;
            hiddenInput.value = item.getAttribute('data-value');
            items.style.display = 'none';
        });
    });

    document.addEventListener('click', (e) => {
        if (!select.contains(e.target)) items.style.display = 'none';
    });

    const form = document.getElementById('cadastroForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!hiddenInput.value) {
            alert('Selecione um status antes de enviar.');
            return;
        }

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert('Erro ao cadastrar paciente.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Erro ao cadastrar paciente.');
        });
    });
});


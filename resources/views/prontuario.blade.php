<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Sessões</title>
    <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
        display: flex;
        background-color: #f3f6fa;
        color: #2d3436;
        height: 100vh;
    }

    /* --- SIDEBAR --- */
    .sidebar {
        width: 320px;
        background: linear-gradient(180deg, #42b893 0%, #3fad8c 100%);
        color: #fff;
        padding: 25px;
        display: flex;
        flex-direction: column;
        height: 100vh;
        box-shadow: 3px 0 15px rgba(0,0,0,0.1);
    }

    .patient-info { margin-bottom: 30px; }
    .patient-name { font-size: 22px; font-weight: 600; margin-bottom: 6px; }
    .patient-cpf { font-size: 15px; color: #e0f0ea; }

    .patient-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* --- STATUS BUTTON --- */
    .status-dropdown {
        position: relative;
        display: inline-block;
        font-size: 15px;
    }

    .status-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        background-color: #2ecc71;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        transition: all 0.25s ease;
    }

    .status-btn[data-status="pausa"] { background-color: #f39c12; }
    .status-btn[data-status="inativo"] { background-color: #e74c3c; }

    .status-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
    }

    /* --- MENU STATUS --- */
    .status-menu {
        display: none;
        position: absolute;
        top: 110%;
        right: 0;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 22px rgba(0,0,0,0.18);
        z-index: 20;
        overflow: hidden;
        width: 160px;
        animation: fadeIn 0.2s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .status-menu div {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        cursor: pointer;
        font-weight: 500;
        color: #333;
        background: #fff;
        transition: background-color 0.25s, transform 0.1s;
    }

    .status-menu div:hover {
        background-color: #f2f5f9;
        transform: translateX(3px);
    }

    .status-circle {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .status-menu div[data-value="ativo"] .status-circle { background-color: #27ae60; }
    .status-menu div[data-value="pausa"] .status-circle { background-color: #f39c12; }
    .status-menu div[data-value="inativo"] .status-circle { background-color: #e74c3c; }

    /* --- SESSÕES --- */
    .section-title {
        font-size: 18px;
        margin: 25px 0 15px 0;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255,255,255,0.4);
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .new-session-btn {
        background: linear-gradient(90deg, #3498db 0%, #2980b9 100%);
        color: white;
        border: none;
        padding: 12px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        margin-bottom: 60px;
        width: 100%;
        box-shadow: 0 3px 8px rgba(0,0,0,0.2);
        transition: all 0.3s;
    }

    .new-session-btn:hover {
        transform: translateY(-1px);
        background: linear-gradient(90deg, #3ca0e0 0%, #2278b0 100%);
    }

    .sessions-list {
        flex-grow: 1;
        overflow-y: auto;
        padding-right: 5px;
    }

    .session-item {
        padding: 12px 10px;
        margin-bottom: 10px;
        background-color: rgba(255,255,255,0.1);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .session-item:hover {
        background-color: rgba(255,255,255,0.25);
        transform: translateX(3px);
    }

    .session-item.active {
        background-color: rgba(255,255,255,0.35);
        border-left: 4px solid #fff;
    }

    /* --- EXCLUIR --- */
    .delete-container {
        margin-top: auto;
        display: flex;
        justify-content: center;
        padding: 25px 0 80px;
        border-top: 1px solid rgba(255,255,255,0.25);
    }

    .delete-btn {
        background: none;
        border: none;
        cursor: pointer;
    }

   .delete-btn img {
    opacity: 0.9;
    transition: transform 0.2s, opacity 0.2s;
}
    .delete-btn:hover img {
        transform: scale(1.15);
        opacity: 1;
    }

    /* --- MAIN --- */
    .main-content {
        flex-grow: 1;
        padding: 40px;
        overflow-y: auto;
        background: #ffffff;
    }

    .session-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .session-title {
        font-size: 26px;
        font-weight: 700;
        color: #2c3e50;
    }

    .session-form {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.07);
    }

    .form-group { margin-bottom: 22px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; color: #444; }

    input[type="text"], input[type="date"], textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #dcdde1;
        border-radius: 8px;
        font-family: inherit;
        font-size: 15px;
        transition: border 0.2s, box-shadow 0.2s;
    }

    input:focus, textarea:focus {
        border-color: #3fad8c;
        outline: none;
        box-shadow: 0 0 0 2px rgba(63,173,140,0.2);
    }

    textarea {
        min-height: 150px;
        resize: vertical;
    }

    .save-btn {
        background: linear-gradient(90deg, #27ae60 0%, #219653 100%);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        font-size: 16px;
        transition: all 0.3s;
    }

    .save-btn:hover {
        background: linear-gradient(90deg, #2ecc71 0%, #27ae60 100%);
        transform: translateY(-1px);
    }

    .keywords-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .keyword-tag {
        background-color: #eafaf1;
        color: #2ecc71;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 13px;
        font-weight: 500;
    }
</style>

</head>
<body>

    <div class="sidebar">
        <div class="patient-info">
            <div class="patient-header">
                <div class="patient-name">{{ $paciente->nome }}</div>
                <div class="status-dropdown">
                    <button 
                        id="btnStatus" 
                        class="status-btn" 
                        data-paciente-id="{{ $paciente->id }}" 
                        data-status="{{ $paciente->status }}"
                    >
                        {{ ucfirst($paciente->status) }} ▼
                    </button>

                    <div class="status-menu">
                        <div data-value="ativo">🟢 Ativo</div>
                        <div data-value="pausa">🟠 Em Pausa</div>
                        <div data-value="inativo">🔴 Inativo</div>
                    </div>
                </div>
            </div>
            <div class="patient-cpf">CPF: {{ $paciente->cpf }}</div>
        </div>

        <div class="section-title">Sessões</div>
        <button class="new-session-btn">Nova Sessão</button>
        
        <div class="sessions-list">
            @foreach($sessoes as $sessao)
                <div class="session-item {{ $loop->first ? 'active' : '' }}" data-id="{{ $sessao->id }}">
                    Sessão #{{ $sessao->numero_sessao }} - {{ $sessao->data_sessao->format('d/m/Y') }}
                </div>
            @endforeach
        </div>

        <div class="delete-container">
    <button 
        id="btnExcluirPaciente" 
        data-paciente-id="{{ $paciente->id }}" 
        title="Excluir paciente"
        class="delete-btn"
    >
        <img src="{{ asset('images/lixeira.svg') }}" alt="Excluir" style="width: 40px; height: 40px;">
    </button>
</div>
    </div>

    <div class="main-content">
        <div class="session-header">
            <div class="session-title">Registro de Sessão</div>
        </div>
        
        <div class="session-form">
            <div class="form-group">
                <label for="session-number">Número da Sessão</label>
                <input type="text" id="session-number" readonly>
            </div>
            
            <div class="form-group">
                <label for="session-date">Data</label>
                <input type="date" id="session-date">
            </div>
            
            <div class="form-group">
                <label for="session-notes">Registro</label>
                <textarea id="session-notes" placeholder="Descreva os detalhes desta sessão..."></textarea>
            </div>
            
            <div class="form-group">
                <label for="session-keywords">Palavras-chave</label>
                <input type="text" id="session-keywords" placeholder="Adicione palavras-chave separadas por vírgula">
                <div class="keywords-container"></div>
            </div>
            
            <button class="save-btn">Salvar Sessão</button>
        </div>
    </div>

    <script>
        window.pacienteId = @json($paciente->id);
        window.sessoesData = @json($sessoes);
    </script>
    <script src="{{ asset('js/prontuario.js') }}"></script>
</body>
</html>


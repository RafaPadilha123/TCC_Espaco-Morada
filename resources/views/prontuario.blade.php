<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Registro de Sessões</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/prontuario.css') }}"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

</head>

<body>

    <!-- ========================================= -->
    <!-- TOPO -->
    <!-- ========================================= -->

    <div class="topbar">

        <div class="logo">

            <img
                src="{{ asset('images/logo-espaco-morada.png') }}"
                alt="Logo"
                class="login-logo"
            >

            <h1>Espaço Morada Psicologia</h1>

        </div>

        <button
            id="backBtn"
            class="back-btn"
        >
            Voltar
        </button>

    </div>

    <!-- ========================================= -->
    <!-- SIDEBAR -->
    <!-- ========================================= -->

    <div class="sidebar">

        <!-- PACIENTE -->
        <div class="patient-info">

    <div class="patient-header">

        <!-- DADOS CLICÁVEIS -->
        <div
            class="patient-details clickable-patient"
            onclick="window.location.href='/pacientes/{{ $paciente->id }}/editar'"
        >

            <div class="patient-name">
                {{ $paciente->nome }}
            </div>

            <div class="patient-cpf">
                CPF: {{ $paciente->cpf }}
            </div>

        </div>

        <!-- STATUS -->
        <div class="status-dropdown">

            <button
                id="btnStatus"
                class="status-btn"
                data-paciente-id="{{ $paciente->id }}"
                data-status="{{ $paciente->status }}"
            >

                {{ ucfirst($paciente->status) }} ▼

            </button>

            <!-- MENU STATUS -->
            <div class="status-menu">

                <div data-value="ativo">
                    🟢 Ativo
                </div>

                <div data-value="pausa">
                    🟠 Em Pausa
                </div>

                <div data-value="inativo">
                    🔴 Inativo
                </div>

            </div>

        </div>

    </div>

</div>

        <!-- SESSÕES -->
        <div class="section-title">
            Sessões
        </div>

        <button class="new-session-btn">
            Nova Sessão
        </button>

        <div class="sessions-list">

            @foreach($sessoes as $sessao)

                <div
                    class="session-item {{ $loop->first ? 'active' : '' }}"
                    data-id="{{ $sessao->id }}"
                >

                    <div class="session-icon">

                        <i class="fa-regular fa-calendar"></i>

                    </div>

                    <div class="session-info">

                        <div class="session-date">

                            {{ $sessao->data_sessao->format('d/m/Y') }}

                        </div>

                        <div class="session-number">

                            Sessão {{ $sessao->numero_sessao }}

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

        <!-- EXCLUIR -->
        <div class="delete-container">

            <button
                id="btnExcluirPaciente"
                data-paciente-id="{{ $paciente->id }}"
                class="delete-btn"
            >

                <div class="delete-icon">

                   <img
                      src="{{ asset('images/picotador.png') }}"
                      alt="Excluir"
                      class="shredder-icon"
                   >

                </div>

                <span>Deletar</span>

            </button>

        </div>

    </div>
    

    <div class="main-content">

        <div class="session-header">

            <div class="session-title">
                Registro de Sessão
            </div>

        </div>

        <div class="session-form">

            <!-- NÚMERO -->
            <div class="form-group">

                <label for="session-number">
                    Número da Sessão
                </label>

                <input
                    type="text"
                    id="session-number"
                    readonly
                >

            </div>

            <!-- DATA -->
            <div class="form-group">

                <label for="session-date">
                    Data
                </label>

                <input
                    type="date"
                    id="session-date"
                >

            </div>

            <!-- REGISTRO -->
            <div class="form-group">

                <label for="session-notes">
                    Registro
                </label>

                <textarea
                    id="session-notes"
                    placeholder="Descreva os detalhes desta sessão..."
                ></textarea>

            </div>

            <!-- PALAVRAS -->
            <div class="form-group">

                <label for="session-keywords">
                    Indicadores clínicos
                </label>

                <input
                    type="text"
                    id="session-keywords"
                    placeholder="Aspectos observados"
                >

                <div class="keywords-container"></div>

            </div>

            <!-- SALVAR -->
            <button class="save-btn">

                Salvar Sessão

            </button>

        </div>

    </div>

    <script>

        window.pacienteId =
            @json($paciente->id);

        window.sessoesData =
            @json($sessoes);

        window.pacienteData =
            @json($paciente);

    </script>

    <script src="{{ asset('js/prontuario.js') }}"></script>
    
    <div id="toast-container"></div>

</body>

</html>

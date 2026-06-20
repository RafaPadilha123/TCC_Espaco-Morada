<!DOCTYPE html>

<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Espaço Morada - Pacientes</title>

<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>

<div class="topbar">

    <div class="logo">
        <img src="{{ asset('images/logo-espaco-morada.png') }}"
             alt="Logo"
             class="login-logo">

        <h1>Espaço Morada Psicologia</h1>
    </div>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="logout-btn">
            Sair
        </button>
    </form>

</div>


<div class="container">


    <div class="header">

        <div>
            <h2>Pacientes</h2>

            <p class="header-description">
            </p>
        </div>

        <button id="btnGraficoKeywords" class="grafico-btn">

        <i class="fa-solid fa-chart-column"></i>

        </button>

<div id="graficoModal" class="grafico-modal">
    <div class="grafico-content">

        <button id="fecharGrafico" class="fechar-grafico">
            ✕
        </button>

        <canvas id="keywordsChart"></canvas>

    </div>
</div>

        <button class="new-patient-btn" id="newPatientBtn">
            + Novo Paciente
        </button>

    </div>


    <div class="search-container">

        <div class="search-wrapper">

            <input type="text"
                   class="search-input"
                   id="searchInput"
                   placeholder="Buscar pacientes">

            <button class="search-clear" id="searchClear">
                ×
            </button>

        </div>

    </div>


    <div class="patients-container" id="patientsContainer">

        <div class="loading">
            Carregando pacientes...
        </div>

    </div>


    <div class="pagination-controls">

        <button class="pagination-btn"
                id="prevPageBtn"
                disabled>
            Anterior
        </button>

        <div class="pagination"
             id="paginationContainer">
        </div>

        <button class="pagination-btn"
                id="nextPageBtn">
            Próxima
        </button>

    </div>

</div>


<script>
    window.sessoesData = @json($sessoes);
</script>
<script src="{{ asset('js/pacientes.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="{{ asset('js/grafico.js') }}"></script>

<div id="toast-container"></div>
</body>
</html>

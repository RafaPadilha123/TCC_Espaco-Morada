<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espaço Morada - Pacientes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #2c3e50; 
            --secondary-color: hsl(210, 29%, 29%); 
            --accent-color: #3fad8c; 
            --text-light: #ecf0f1; 
            --text-dark: #2d3436;
            --highlight: #3fad8c;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        .container {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h2 {
            color: var(--primary-color);
            font-size: 26px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #3fad8c;
            padding: 1.5rem 2rem;
            color: white;
        }

        .topbar .logo {
            display: flex;
            align-items: center;
        }

        .topbar .logo img {
            height: 85px;
            margin-right: 1rem;
        }

        .topbar .logo h1 {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .logout-btn {
            background-color: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: rgba(255,255,255,0.3);
        }

        .new-patient-btn {
            background-color: var(--accent-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .new-patient-btn:hover {
            background-color: var(--highlight);
        }

        .search-container {
            margin: 0 auto 50px auto;
            position: relative;
            max-width: 1300px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .search-input {
            width: 100%;
            padding: 18px 55px 18px 25px;
            border: 2px solid #e1e8ed;
            border-radius: 30px;
            font-size: 17px;
            background: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 6px 20px rgba(63, 173, 140, 0.25);
            transform: translateY(-2px);
        }

        .search-input::placeholder {
            color: #a0a0a0;
            font-size: 16px;
        }

        .search-icon {
            position: absolute;
            right: 25px;
            color: var(--accent-color);
            font-size: 20px;
            pointer-events: none;
        }

        .search-clear {
            position: absolute;
            right: 50px;
            background: none;
            border: none;
            color: #ccc;
            cursor: pointer;
            padding: 5px;
            font-size: 18px;
            transition: color 0.3s;
            display: none;
        }

        .search-clear:hover {
            color: #ff6b6b;
        }

        .search-clear.show {
            display: block;
        }

        .patients-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .patient-card {
            background: white;
            border-radius: 12px;
            margin-top: 20px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            min-height: 150px;
            border-left: 4px solid var(--accent-color);
            cursor: pointer;
        }

        .patient-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .patient-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .patient-cpf {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .patient-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-ativo {
            background-color: #e8f6ef;
            color: #27ae60;
        }

        .status-pausa {
            background-color: #fef9e7;
            color: #f39c12;
        }

        .status-inativo {
            background-color: #fdedec;
            color: #e74c3c;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .page-number {
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            color: var(--primary-color);
            border: 1px solid #ddd;
        }

        .page-number.active {
            background-color: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
        }

        .page-number:hover:not(.active) {
            background-color: #f0f0f0;
        }

        .no-results {
            text-align: center;
            padding: 30px;
            color: #777;
            font-style: italic;
            grid-column: 1 / -1;
        }

        .pagination-controls {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            z-index: 100;
            background: none;
            padding: 0;
        }

        .pagination-btn, 
        .page-number {
            height: 38px;
            min-width: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            border: 1px solid #ddd;
            color: var(--primary-color);
            background: #fff;
        }

        .page-number.active {
            background-color: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
        }

        .pagination-btn:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
            color: #ccc;
        }

        .loading {
            text-align: center;
            padding: 30px;
            color: #7f8c8d;
            grid-column: 1 / -1;
        }

        @media (max-width: 1200px) {
            .patients-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .patients-container {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 20px;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .pagination-controls {
                position: static;
                transform: none;
                margin-top: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="logo">
            <img src="{{ asset('images/logo-espaco-morada.png') }}" alt="Logo" class="login-logo">
            <h1>Espaço Morada Psicologia</h1>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">Sair</button>
        </form>
    </div>

    <div class="container">
        <div class="header">
            <h2>Pacientes</h2>
            <button class="new-patient-btn" id="newPatientBtn">+ Novo Paciente</button>
        </div>

        <div class="search-container">
            <div class="search-wrapper">
                <input type="text" class="search-input" id="searchInput" placeholder="Buscar pacientes">
                <button class="search-clear" id="searchClear">×</button>
            </div>
        </div>

        <div class="patients-container" id="patientsContainer">
            <div class="loading">Carregando pacientes...</div>
        </div>

        <div class="pagination-controls">
            <button class="pagination-btn" id="prevPageBtn" disabled>Anterior</button>
            <div class="pagination" id="paginationContainer"></div>
            <button class="pagination-btn" id="nextPageBtn">Próxima</button>
        </div>
    </div>

    <script src="{{ asset('js/pacientes.js') }}"></script>
</body>
</html>

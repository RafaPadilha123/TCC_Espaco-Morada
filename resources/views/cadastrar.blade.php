<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Paciente - Centralizado</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    /* ===== Reset e Body ===== */
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    body {
      background-color: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    /* ===== Container (Card) ===== */
    .container {
      background-color: #ffffff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
      width: 100%;
      max-width: 450px;
      transition: transform 0.3s ease;
    }

    /* ===== Título ===== */
    h2 {
      text-align: center;
      color: #1a2c42;
      font-weight: 600;
      margin-bottom: 30px;
      letter-spacing: 0.5px;
    }

    /* ===== Form Groups ===== */
    .form-group {
      margin-bottom: 20px;
      position: relative;
    }

    label {
      display: block;
      font-weight: 500;
      color: #495057;
      margin-bottom: 6px;
      font-size: 14px;
    }

    input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ced4da;
      border-radius: 8px;
      font-size: 16px;
      background-color: #fff;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    input:focus {
      border-color: #3498db;
      box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.25);
      outline: none;
    }

    /* ===== Custom Select ===== */
    .custom-select {
      position: relative;
      user-select: none;
    }

    .select-selected {
      background-color: #fff;
      border: 1px solid #ced4da;
      padding: 12px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: border-color 0.3s;
    }

    .select-selected:hover {
      border-color: #a0a0a0;
    }

    .select-selected::after {
      content: '\25BC';
      font-size: 12px;
      color: #555;
      margin-left: 10px;
      transition: transform 0.3s;
    }

    .select-selected.select-arrow-active::after {
      transform: rotate(180deg);
    }

    .select-items {
      position: absolute;
      background-color: #fff;
      border: 1px solid #ced4da;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      top: calc(100% + 5px);
      left: 0;
      right: 0;
      z-index: 1000;
      display: none;
      max-height: 180px;
      overflow-y: auto;
    }

    .select-items div {
      padding: 10px 12px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.2s;
    }

    .select-items div:hover,
    .same-as-selected {
      background-color: #e9f0f6;
      color: #1a2c42;
    }

    /* ===== Botão ===== */
    button {
      width: 100%;
      background-color: #007bff;
      color: #fff;
      padding: 14px;
      border: none;
      border-radius: 8px;
      font-size: 17px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.1s, box-shadow 0.3s;
      margin-top: 10px;
      box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
    }

    button:hover {
      background-color: #0056b3;
      box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
    }

    button:active {
      transform: scale(0.99);
    }

    /* ===== Mensagens ===== */
    .message-success {
      color: #155724;
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
    }

    .message-error {
      color: #721c24;
      background-color: #f8d7da;
      border: 1px solid #f5c6cb;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
    }

    .message-error ul {
      margin: 0;
      padding-left: 20px;
    }

    /* ===== Responsividade ===== */
    @media (max-width: 500px) {
      .container {
        padding: 30px 20px;
      }

      input, .select-selected {
        font-size: 15px;
        padding: 10px;
      }

      button {
        font-size: 16px;
        padding: 12px;
      }
    }

  </style>
</head>
<body>
  <div class="container">
    <h2>Cadastro de Paciente</h2>

    {{-- Mensagem de sucesso --}}
    @if(session('success'))
      <div class="message-success">
        {{ session('success') }}
      </div>
    @endif

    {{-- Mensagem de erro --}}
    @if($errors->any())
      <div class="message-error">
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form id="cadastroForm" action="{{ route('pacientes.store') }}" method="POST">
      @csrf
      <div class="form-group">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required>
      </div>
      <div class="form-group">
        <label for="cpf">CPF</label>
        <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}" required>
      </div>
      <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
      </div>
      <div class="form-group">
        <label for="telefone">Telefone</label>
        <input type="text" id="telefone" name="telefone" value="{{ old('telefone') }}" required>
      </div>
      <div class="form-group">
        <label for="status">Status</label>
        <div class="custom-select" id="statusSelect">
          <div class="select-selected">{{ old('status') ?? 'Selecione um status' }}</div>
          <div class="select-items">
            <div data-value="ativo">Ativo</div>
            <div data-value="pausa">Em Pausa</div>
            <div data-value="inativo">Inativo</div>
          </div>
        </div>
        <input type="hidden" name="status" id="status" value="{{ old('status') }}" required>
      </div>
      <button type="submit">Cadastrar</button>
    </form>
  </div>

  <script src="{{ asset('js/cadastrar.js') }}"></script>
</body>
</html>


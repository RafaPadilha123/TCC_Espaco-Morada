<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>

        {{
            isset($paciente)
                ? 'Editar Paciente'
                : 'Cadastro de Paciente'
        }}

    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/cadastrar.css') }}"
    >
    
    <link
    rel="stylesheet"
    href="{{ asset('css/toast.css') }}"
    >

</head>

<body>

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
    type="button"
    id="backBtn"
    class="back-btn"
>
    Voltar
</button>

    </div>

    <div class="container">

        <h2>

            {{
                isset($paciente)
                    ? 'Editar Paciente'
                    : 'Cadastro de Paciente'
            }}

        </h2>

        {{-- SUCESSO --}}
        @if(session('success'))

            <div class="message-success">

                {{ session('success') }}

            </div>

        @endif

        {{-- ERROS --}}
        @if($errors->any())

            <div class="message-error">

                <ul>

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form
            id="cadastroForm"

            action="{{
                isset($paciente)
                    ? route('pacientes.update', $paciente->id)
                    : route('pacientes.store')
            }}"

            method="POST"
        >

            @csrf

            @if(isset($paciente))

                @method('PUT')

            @endif

            <!-- NOME -->
            <div class="form-group">

                <label for="nome">
                    Nome
                </label>

                <input
                    type="text"
                    id="nome"
                    name="nome"

                    value="{{
                        old(
                            'nome',
                            $paciente->nome ?? ''
                        )
                    }}"

                    required
                >

            </div>

            <!-- CPF -->
            <div class="form-group">

                <label for="cpf">
                    CPF
                </label>

                <input
                    type="text"
                    id="cpf"
                    name="cpf"

                    value="{{
                        old(
                            'cpf',
                            $paciente->cpf ?? ''
                        )
                    }}"

                    required
                >

            </div>

            <!-- EMAIL -->
            <div class="form-group">

                <label for="email">
                    E-mail
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"

                    value="{{
                        old(
                            'email',
                            $paciente->email ?? ''
                        )
                    }}"

                    required
                >

            </div>

            <!-- TELEFONE -->
            <div class="form-group">

                <label for="telefone">
                    Telefone
                </label>

                <input
                    type="text"
                    id="telefone"
                    name="telefone"

                    value="{{
                        old(
                            'telefone',
                            $paciente->telefone ?? ''
                        )
                    }}"

                    required
                >

            </div>

            <!-- STATUS -->
            <div class="form-group">

                <label for="status">
                    Status
                </label>

                <div
                    class="custom-select"
                    id="statusSelect"
                >

                    <div class="select-selected">

                        {{
                            old(
                                'status',
                                isset($paciente)
                                    ? ucfirst($paciente->status)
                                    : 'Selecione um status'
                            )
                        }}

                    </div>

                    <div class="select-items">

                        <div data-value="ativo">
                            Ativo
                        </div>

                        <div data-value="pausa">
                            Em Pausa
                        </div>

                        <div data-value="inativo">
                            Inativo
                        </div>

                    </div>

                </div>

                <input
                    type="hidden"
                    name="status"
                    id="status"

                    value="{{
                        old(
                            'status',
                            $paciente->status ?? ''
                        )
                    }}"

                    required
                >

            </div>

            <!-- BOTÃO -->
            <button type="submit">

                {{
                    isset($paciente)
                        ? 'Salvar Alterações'
                        : 'Cadastrar'
                }}

            </button>

        </form>

    </div>

    <script src="{{ asset('js/cadastrar.js') }}"></script>
    <script src="{{ asset('js/toast.js') }}"></script>
    
    <div id="toast-container"></div>

</body>
</html>

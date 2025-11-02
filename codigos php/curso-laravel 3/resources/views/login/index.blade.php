<x-layout title="Login">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
            <div class="card-body">
                <h2 class="card-title text-center mb-4 fw-bold">Entrar</h2>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                    </button>
                    <a href="{{ route('users.create') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-person-plus"></i> Registrar
                    </a>
                </form>
            </div>
        </div>
    </div>
</x-layout>

<x-layouts.main-layout pageTitle="Criar categoria">
    <div class="container mt-4">
        <h2>Criar Categoria</h2>
        <form action="{{ route('insertCategoria') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nome" class="form-label">Nome da Categoria</label>
                <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome') }}">
            </div>
            @error('nome')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
        @if (session('success'))
            <p class="mt-3 alert alert-success text-center p-2">Categoria criada com sucesso!</p>
        @endif
    </div>
</x-layouts.main-layout>

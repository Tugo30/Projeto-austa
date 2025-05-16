<x-layouts.main-layout pageTitle="Atendimento">
    <div class="container">
        <h2 class="mt-3">Editar Atendimento</h2>
        <form action="{{ route('atualizarAtendimento', ['id' => $atendimento->id]) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" name="titulo" class="form-control" value="{{ $atendimento->titulo }}">
                @error('titulo')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control" rows="5">{{ $atendimento->descricao }}</textarea>
                @error('descricao')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="prioridade" class="form-label">Prioridade</label>
                <select name="prioridade" class="form-select">
                    <option disabled>Selecionar opção</option>
                    @foreach ($prioridade as $id => $nome)
                        <option value="{{ $nome }}" {{ $atendimento->prioridade === $nome ? 'selected' : '' }}>
                            {{ $nome }}
                        </option>
                    @endforeach
                </select>
                @error('prioridade')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="categoria_id" class="form-label">Categoria</label>
                <select name="categoria_id" class="form-select">
                    <option disabled>Selecionar opção</option>
                    @foreach ($categorias as $id => $nome)
                        <option value="{{ $id }}" {{ $atendimento->categoria_id == $id ? 'selected' : '' }}>
                            {{ $nome }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option selected disabled>Selecionar opção</option>
                    @foreach ($status as $id => $nome)
                        <option value="{{ $nome }}" {{ $atendimento->status === $nome ? 'selected' : '' }}>
                            {{ $nome }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
        @if (session('success'))
            <p class="mt-3 alert alert-success text-center p-2">Atendimento atualizado com sucesso!</p>
        @endif
    </div>
</x-layouts.main-layout>

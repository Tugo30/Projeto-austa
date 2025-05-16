<x-layouts.main-layout pageTitle="Home">
    <div class="container">
        <h2 class="mt-3">Dashboard</h2>
        <div class="row my-4">
            <div class="col">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5>Abertos</h5>
                        <p class="display-4">
                            {{ $atendimentos->where('status', 'Aberto')->count() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5>Em andamento</h5>
                        <p class="display-4">
                            {{ $atendimentos->where('status', 'Em andamento')->count() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5>Finalizados</h5>
                        <p class="display-4">
                            {{ $atendimentos->where('status', 'Finalizado')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('selectAtendimento') }}" class="btn btn-primary">Novo Atendimento</a>
        @if ($user->role === 'admin')
            <a href="{{ route('categoria') }}" class="btn btn-primary">Nova Categoria</a>
            <a href="#" class="btn btn-primary">Listar Categorias</a>
        @endif
    </div>
    <div class="container">
        <table class="table table-striped mt-5">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Prioridade</th>
                    <th>Status</th>
                    <th>Categoria</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($atendimentos as $a)
                    <tr>
                        <td>{{ $a->titulo }}</td>
                        <td>{{ $a->descricao }}</td>
                        <td>{{ $a->prioridade }}</td>
                        <td>{{ $a->status }}</td>
                        <td>{{ $a->categoria->nome }}</td>
                        <td class="text-center"><a href="{{ route('editarAtendimento', ['id' => $a->id]) }}"><i class="fas fa-edit"></i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.main-layout>

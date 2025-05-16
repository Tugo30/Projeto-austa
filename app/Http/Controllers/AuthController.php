<?php

namespace App\Http\Controllers;

use App\Mail\NewUserConfirmation;
use App\Mail\ResetPassword;
use App\Models\Atendimento;
use App\Models\Categorias;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        // validação do formulário
        $credentials = $request->validate(
            [
                'username' => 'required|min:3|max:30',
                'password' => 'required|min:8|max:32|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            [
                'username.required' => 'O usuário é obrigatório',
                'username.min' => 'O usuário deve ter no minimo :min caracteres',
                'username.max' => 'O usuário deve ter no máximo :min caracteres',
                'password.required' => 'A senha é obrigatória',
                'password.min' => 'A senha deve ter no mínimo :min caracteres',
                'password.max' => 'A senha deve ter no máximo :max caracteres',
                'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula e um número'
            ]
        );

        // verificar se o user existe
        $user = User::where('username', $credentials['username'])
            ->where('active', true)
            ->where(function ($query) {
                $query->whereNull('blocked_until')
                    ->orWhere('blocked_until', '<=', now());
            })
            ->whereNotNull('email_verified_at')
            ->whereNull('deleted_at')
            ->first();

        //verifica se o user existe
        if (!$user) {
            return back()->withInput()->with([
                'invalid_login' => 'Login inválido'
            ]);
        }

        // verificar se a password e valida
        if (!password_verify($credentials['password'], $user->password)) {
            return back()->withInput()->with([
                'invalid_login' => 'Login inválido'
            ]);
        }

        // atualizar o ultimo login (last_login_at)
        $user->last_login_at = now();
        $user->blocked_until = null;
        $user->save();

        // login 
        $request->session()->regenerate();
        Auth::login($user);

        // redirecionar
        return redirect()->intended(route('home'));
    }

    public function logout(): RedirectResponse
    {
        // logout
        Auth::logout();
        return redirect()->route('login');
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function store_user(Request $request): RedirectResponse | View
    {
        // form validation
        $request->validate(
            [
                'username' => 'required|min:3|max:30|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|max:32|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'password_confirmation' => 'required|same:password'
            ],
            [
                'username.required' => 'O usuário é obrigatório.',
                'username.min' => 'O usuário deve conter no mínimo :min caracteres.',
                'username.max' => 'O usuário deve conter no máximo :max caracteres.',
                'username.unique' => 'Este nome não pode ser usado.',
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'O email deve ser um endereço de email válido.',
                'email.unique' => 'Este email não pode ser usado.',
                'password.required' => 'A senha é obrigatória.',
                'password.min' => 'A senha deve conter no mínimo :min caracteres.',
                'password.max' => 'A senha deve conter no máximo :max caracteres.',
                'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula e um número.',
                'password_confirmation.required' => 'A confirmação de senha é obrigatória.',
                'password_confirmation.same' => 'A confirmação da senha deve ser igual à senha.'
            ]
        );

        // crirar um novo usuário definindo um token de verificação de email
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->token = Str::random(64); //criando hash para token

        // gerar link para confimação do token
        $confirmation_link = route('new_user_confirmation',  ['token' => $user->token]);

        // enviar email
        $result = Mail::to($user->email)->send(new NewUserConfirmation($user->username, $confirmation_link));

        //verificar se o email foi enviado com sucesso
        if (!$result) {
            return back()->withInput()->with([
                'server_error' => 'Ocorreu um erro ao enviar o email de confirmação.'
            ]);
        }

        // criar usuario na base de dados
        $user->save();

        // apresentar view de sucesso
        return view('auth.email_sent', ['email' => $user->email]);
    }

    public function new_user_confirmation($token)
    {
        // verificar se o token é valido
        $user = User::where('token', $token)->first();
        if (!$user) {
            return redirect()->route('login');
        }

        // confirmar o registro do usuario
        $user->email_verified_at = Carbon::now();
        $user->token = null;
        $user->active = true;
        $user->save();

        //autenticação automatica (login) do usuario confirmado
        Auth::login($user);

        // apresenta uma mensagem de sucesso
        return view('auth.new_user_confirmation');
    }

    public function profile(): View
    {
        return view('auth.profile');
    }

    public function change_password(Request $request)
    {
        // form validate
        $request->validate(
            [
                'current_password' => 'required|min:8|max:32|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'new_password' => 'required|min:8|max:32|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|different:current_password',
                'new_password_confirmation' => 'required|same:new_password'
            ],
            [
                'current_password.required' => 'A senha atual é obrigatória.',
                'current_password.min' => 'A senha atual deve conter no mínimo :min caracteres.',
                'current_password.max' => 'A senha atual deve conter no máximo :max caracteres.',
                'current_password.regex' => 'A senha atual deve conter pelo menos uma letra maiúscula, uma minúscula e um número',
                'new_password.required' => 'A senha atual é obrigatória.',
                'new_password.min' => 'A senha atual deve conter no mínimo :min caracteres.',
                'new_password.max' => 'A senha atual deve conter no máximo :max caracteres.',
                'new_password.different' => 'A nova senha deve ser diferente da atual.',
                'new_password_confirmation.required' => 'A confirmação da nova senha é obrigatória',
                'new_password_confirmation.same' => 'A confirmação da nova senha deve ser igual á nova.'
            ]
        );

        // verificar se a password atual (current_password) esta correta
        if (!password_verify($request->current_password, Auth::user()->password)) {
            return back()->with([
                'server_error' => 'A senha atual está incorreta'
            ]);
        }

        // atualizar a senha na base de dados
        $user = Auth::user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        // atualizar a password na sessão
        Auth::user()->password = $request->new_password;

        // apresentar uma mensagem de sucesso
        return redirect()->route('profile')->with([
            'success' => 'A senha foi atualizada com sucesso.'
        ]);
    }

    public function forgot_password(): View
    {
        return view('auth.forgot_password');
    }

    public function send_reset_password_link(Request $request)
    {
        // form validation
        $request->validate(
            [
                'email' => 'required|email',
            ],
            [
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'O email deve ser um endereço de email válido.'
            ]
        );

        $generic_message = "Verifique a sua caixa de correio para prosseguir com a recuperação de senha.";

        // verificar se email existe
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with([
                'server_message' => $generic_message
            ]);
        }

        // criar o link com token para envial o email
        $user->token = Str::random(64);

        $token_link = route('reset_password', ['token' => $user->token]);

        // envio de email com link para recuperar a senha
        $result = Mail::to($user->email)->send(new ResetPassword($user->username, $token_link));

        // verificar se o email foi verificado
        if (!$result) {
            return back()->with([
                'server_message' => $generic_message
            ]);
        }

        //guarda o token na base de dados
        $user->save();

        return back()->with([
            'server_message' => $generic_message
        ]);
    }

    public function reset_password($token): View | RedirectResponse
    {
        // verificar se o token é valido
        $user = User::where('token', $token)->first();
        if (!$user) {
            return redirect()->route('login');
        }

        return view('auth.reset_password', ['token' => $token]);
    }

    public function reset_password_update(Request $request): RedirectResponse
    {
        // form validation 
        $request->validate(
            [
                'token' => 'required',
                'new_password' => 'required|min:8|max:32|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'new_password_confirmation' => 'required|same:new_password',
            ],
            [
                'new_password.required' => 'A nova senha é obrigatória.',
                'new_password.min' => 'A nova senha deve conter no mínimo :min caracteres.',
                'new_password.max' => 'A nova senha deve conter no máximo :max caracteres.',
                'new_password.regex' => 'A nova senha deve conter pelo menos uma letra maiuscula, uma letra minuscula e um número.',
                'new_password_confirmation.required' => 'A confirmação da nova senha é obrigatória.',
                'new_password_confirmation.same' => 'A confirmação da nova senha deve ser igual à nova senha.'
            ]
        );

        // verifica se o token é válido
        $user = User::where('token', $request->token)->first();
        if (!$user) {
            return redirect()->route('login');
        }

        // atualizar a senha na base de dados
        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('login')->with([
            'success' => true
        ]);
    }

    public function selectAtendimento(): View
    {
        $user = Auth::user();
        $prioridade = [
            1 => 'Alta',
            2 => 'Média',
            3 => 'Baixa'
        ];
        $categorias = Categorias::all();
        $status = [
            1 => 'Aberto',
            2 => 'Em andamento',
            3 => 'Finalizado',
        ];
        return view('auth.atendimento')->with([
            'user' => $user,
            'prioridade' => $prioridade,
            'categorias' => $categorias,
            'status' => $status
        ]);
    }

    public function editarAtendimento($id): View
    {
        $atendimento = Atendimento::findOrFail($id);

        $status = [
            1 => 'Aberto',
            2 => 'Em andamento',
            3 => 'Finalizado',
        ];

        $prioridade = [
            1 => 'Alta',
            2 => 'Média',
            3 => 'Baixa',
        ];

        $categorias = Categorias::pluck('nome', 'id');

        return view('auth.editar-atendimento')->with([
            'atendimento' => $atendimento,
            'prioridade' => $prioridade,
            'categorias' => $categorias,
            'status' => $status
        ]);
    }

    public function atualizarAtendimento(Request $request, $id) : Redirectresponse
    {
        $request->validate(
            [
                'titulo' => 'required|string|min:3|max:255',
                'descricao' => 'required|min:3|max:700|string',
                'prioridade' => 'required|in:Alta,Média,Baixa',
                'status' => 'required|in:Aberto,Em andamento, Finalizado',
                'categoria_id' => 'required|integer|exists:categorias,id',
            ],
            [
                'titulo.required' => 'O campo titulo deve ser preenchido!',
                'titulo.min' => 'O campo titulo deve conter no mínimo :min caracterers!',
                'titulo.max' => 'O campo titulo deve conter no máximo :max caracteres!',
                'descricao.required' => 'O campo descrição deve ser preenchido!',
                'descricao.min' => 'O campo descrição deve conter no mínimo :min caracterers!',
                'descricao.max' => 'O campo descrição deve conter no máximo :max caracterers!',
                'prioridade.required' => 'Você deve selecionar pelo menos uma das opções!',
                'status.required' => 'Você deve selecionar pelo menos uma das opções!',
                'categoria_id.required' => 'Você deve selecionar pelo menos uma das opções!'
            ]
        );

        $atendimento = Atendimento::findOrFail($id);

        $atendimento->update([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'prioridade' => $request->prioridade,
            'status' => $request->status,
            'categoria_id' => $request->categoria_id,
            'updated_at' => now(),
        ]);

        return redirect()->route('editarAtendimento', ['id' => $id])->with('success', 'Atendimento criado com sucesso!');
    }

    public function insertAtendimento(Request $request): Redirectresponse
    {
        $request->validate(
            [
                'titulo' => 'required|string|min:3|max:255',
                'descricao' => 'required|min:3|max:700|string',
                'prioridade' => 'required|in:1,2,3',
                'status' => 'required|in:1,2,3',
                'categoria_id' => 'required|integer|exists:categorias,id',
            ],
            [
                'titulo.required' => 'O campo titulo deve ser preenchido!',
                'titulo.min' => 'O campo titulo deve conter no mínimo :min caracterers!',
                'titulo.max' => 'O campo titulo deve conter no máximo :max caracteres!',
                'descricao.required' => 'O campo descrição deve ser preenchido!',
                'descricao.min' => 'O campo descrição deve conter no mínimo :min caracterers!',
                'descricao.max' => 'O campo descrição deve conter no máximo :max caracterers!',
                'prioridade.required' => 'Você deve selecionar pelo menos uma das opções!',
                'status.required' => 'Você deve selecionar pelo menos uma das opções!',
                'categoria_id.required' => 'Você deve selecionar pelo menos uma das opções!'
            ]
        );

        $user = Auth::user();

        Atendimento::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'prioridade' => $request->prioridade,
            'status' => $request->status,
            'categoria_id' => $request->categoria_id,
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('atendimento')->with('success', 'Atendimento criado com sucesso!');
    }

    public function categoria(): View
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acesso não autorizado.');
        }
        return view('auth.categoria');
    }

    public function insertCategoria(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'nome' => 'required|string|min:2|max:255|unique:categorias,nome',
            ],
            [
                'nome.required' => 'O campo deve estar preenchido!',
                'nome.min' => 'O campo deve conter no mínimo :min caracteres!',
                'nome.max' => 'O campo deve conter no máximo :max caracteres!',
                'nome.unique' => 'Não é possível adicionar essa categoria!'
            ]
        );

        Categorias::create([
            'nome' => $request->nome
        ]);

        return redirect()->route('categoria')->with('success', 'Categoria criada com sucesso!');
    }

    public function deleted_account(Request $request): RedirectResponse
    {
        // validação do formulário
        $request->validate(
            [
                'deleted_confirmation' => 'required|in:ELIMINAR'
            ],
            [
                'deleted_confirmation.required' => 'A confirmação é obrigatória',
                'deleted_confirmation.in' => 'É obrigatório escrever a palavra ELIMINAR'
            ]
        );

        //remover a conta do usuário soft delete
        $user = Auth::user();
        $user->delete();

        // logout
        Auth::logout();

        // redirect
        return redirect()->route('login')->with(['account_deleted' => true]);
    }
}

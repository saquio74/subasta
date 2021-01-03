<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class users extends Controller
{
    public function signup(Request $request){
        $request->validate([
            'name'=>'required',
            'surname'=>'required',
            'pais'=>'required',
            'tipo_persona'=>'required',
            'nacimiento'=>'required',
            'genero'=>'required',
            'como_conocio'=>'required',
            'telefono'=>'required',
            'celular'=>'required',
            'provincia'=>'required',
            'ciudad'=>'required',
            'direccion'=>'required',
            'email'=>'required|confirmed|unique:users',
            'password'=>'required|confirmed',
        ]);
        $user = new User;
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->pais = $request->pais;
        $user->tipo_persona = $request->tipo_persona;
        $user->nacimiento = $request->nacimiento;
        $user->genero = $request->genero;
        $user->como_conocio = $request->como_conocio;
        $user->telefono = $request->telefono;
        $user->celular = $request->celular;
        $user->provincia = $request->provincia;
        $user->ciudad = $request->ciudad;
        $user->direccion = $request->direccion;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $user->whereEmail($user->email);

        $email = $user->email;

        $messageData = [
            'id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email, 
            'code'=>base64_encode($email)
        ];
        
        Mail::send('emails.emailConfirm',$messageData, function($message)use($email){
            $message->to($email)->subject('Confirma tu cuenta por favor');
        });
        $user->save();
        return response()->json([
            'message' => 'Por favor confirma tu e-mail!'
        ], 201);    
    }
    public function login(Request $request){
        $request->validate([
            'email'      => 'required|string',
            'password'   => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        if(!Auth::attempt($credentials)){
            return response()->json([
                'message'=>'invalid mail or password'
            ],401);
        }
        $user = $request->user();
        $token = $user->createToken('Access Token');
        $user->access_token = $token->accessToken;
        return response()->json($user,200);
    }
    public function logout(Request $request){
        $request->user()->token()->revoke();

        return response()->json(['message'=>'Ha cerrado sesion correctamente'],201);
    }
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    public function resend(Request $request){
        $user = User::whereId($request->id)->first();
        $email = $user->email;
        $messageData = [
            'id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email, 
            'code'=>base64_encode($email)
        ];
        Mail::send('emails.emailConfirm',$messageData, function($message)use($email){
            $message->to($email)->subject('Reenvio de confirmacion de email');
        });
        return response()->json(['message'=>'Mensaje reenviado'],201);
    }
    public function verify($id,$code){
        $user = User::whereId($id)->first();
        if($user->email == \base64_decode($code)){
            if($user->email_verified_at == null){

                $user->email_verified_at = Carbon::now()->toDateTimeString();
                $user->save();
            }
        }
        $response = $user->email_verified_at != null ? 'El email ya se encuentra confirmado'
                                                    :($user->email != \base64_decode($code)
                                                    ?'El codigo ingresado no es valido o ha caducado'
                                                    :'usuario verificado correctamente');
        
        return response()->json(['response'=>$response],201);
    }
    public function userDataModify(Request $request){
        $request->validate([
            'name'=>'required',
            'surname'=>'required',
            'pais'=>'required',
            'tipo_persona'=>'required',
            'nacimiento'=>'required',
            'genero'=>'required',
            'como_conocio'=>'required',
            'telefono'=>'required',
            'celular'=>'required',
            'provincia'=>'required',
            'ciudad'=>'required',
            'direccion'=>'required',
            'password'=>'required|confirmed',
        ]);
        $user = User::whereEmail($request->email);
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->pais = $request->pais;
        $user->tipo_persona = $request->tipo_persona;
        $user->nacimiento = $request->nacimiento;
        $user->genero = $request->genero;
        $user->como_conocio = $request->como_conocio;
        $user->telefono = $request->telefono;
        $user->celular = $request->celular;
        $user->provincia = $request->provincia;
        $user->ciudad = $request->ciudad;
        $user->direccion = $request->direccion;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['message'=>'Usuario modificado correctamente'],201);
    }
}

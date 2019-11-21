<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller;


class UserController extends Controller
{
    /**
     * @param Request $request
     * @return mixed|string
     */
    public function login(Request $request)
    {

        $user = User::where('email', $request['username'])->first();
        if ($user !== null) {
            $request->merge(["scope" => $user->status]);


            $newR = app()->dispatch($request->create("/oauth/token", "post", $request->all())
            );


            $string = substr($newR->getContent(), 0, -1);

            $string = $string . ',"lastname" :"' . $user->lastName . '"';
            $string = $string . ',"firstname" :"' . $user->firstName . '"';
            $string = $string . ',"mail" :"' . $user->email . '"';
            $string = $string . ',"id" :"' . $user->id . '"';
            $string = $string . ',"status" :"' . $user->status . '"}';

            return json_decode($string, true);
        }else {
            return 'Error : user not found';
        }

    }


//    public function showAllUsers()
//    {
//        return response()->json(User::all());
//    }
//
//
//
//    public function showOneUser($id)
//    {
//        return response()->json(User::find($id));
//    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
//        $this->validate($request, [
//            'firstName' => 'required',
//            'lastName' => 'required',
//            'email' => 'required|email',
//            'password' => 'required',
//        ]);

        $user = new User;
        $user->firstName = $request['firstName'];
        $user->lastName = $request->lastName;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->status = $request->status;
        $user->save();


        return response()->json($user, 201);

    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateMail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'

        ]);

        $user = $request->user();

        $user = User::findOrFail($user->id);
        $user->update($request->only('email'));
        return response()->json($user, 200);
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'oldpassword' => 'required',
            'password' => 'required'

        ]);

        $user = $request->user();

        $request['password'] = Hash::make($request['password']);
        $user = User::findOrFail($user->id);
        if (password_verify($request['oldpassword'], $user->password)) {
            $user->update($request->only('password'));
            return response()->json($user, 200);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

//    public function delete($id)
//    {
//        User::findOrFail($id)->delete();
//        return response('Deleted Successfully', 200);
//    }
}

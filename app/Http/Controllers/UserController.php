<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Регистрирует пользователя.
     *
     * @param  StoreUserRequest  $request
     * @return JsonResponse
     */
    public function signup(StoreUserRequest $request): JsonResponse
    {
        $user = User::create([
            'first_name'  => $request->get('first_name'),
            'second_name' => $request->get('second_name'),
            'email'       => $request->get('email'),
            'password'    => Hash::make($request->get('password'))
        ]);

        return response()->json([
            'data' => $user
        ]);
    }

    /**
     * Редактирование данных пользователя
     *
     * @param  UpdateUserRequest  $request
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $params = $request->validated();

        /** @var User $user */
        $user = auth()->user();

        foreach ($params as $key => $item) {
            switch ($key) {
                case 'first_name':
                    $user->setFirstName($params['first_name']);
                    break;
                case 'second_name':
                    $user->setSecondName($params['second_name']);
                    break;
                case 'email':
                    $user->setEmail($params['email']);
                    break;
                case 'password':
                    $user->setPassword(Hash::make($params['password']));
                    break;
            }
        }

        $user->save();

        return response()->json([
            'data' => $user
        ]);
    }

    /**
     * Удалить пользователя
     *
     * @return JsonResponse
     */
    public function delete(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->delete();

        return response()->json([
            'data' => 'Пользователь успешно удален'
        ]);
    }

    /**
     * Получить размер всех файлов на диске пользователя
     *
     * @return JsonResponse
     */
    public function getCurrentFilesSize(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        return response()->json([
            'data' => ['size' => $user->getCurrentSize()]
        ]);
    }
}

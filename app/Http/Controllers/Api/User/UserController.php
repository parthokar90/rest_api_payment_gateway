<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $query = $request->input('query');

        $limit = $request->input('limit', 10);

        $page = $request->input('page', 1);

        $usersQuery = User::query();

        if ($query) {
            $usersQuery->where('name', 'like', '%' . $query . '%');
        }

        $users = $usersQuery->paginate($limit, ['*'], 'page', $page);

        $dataTotal = $users->total();

        $currentPage = (string) $users->currentPage();

        $payload = [
            'status' => Response::HTTP_OK,
            'total' => $dataTotal,
            'page' => $currentPage,
            'perPage' => (string) $limit,
            'data' => UserResource::collection($users),
        ];

        return response()->json($payload, Response::HTTP_OK);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'date_of_birth' => 'required|date',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response(
                [
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'app_message' => 'Validation errors',
                    'user_message' => 'Validation errors',
                    'errors' => $validator->errors(),
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $data = [
            'name'=>$request->name,
            'email'=>$request->email,
            'date_of_birth'=>$request->date_of_birth,
            'password'=> Hash::make($request->password),
        ];

        User::create($data);

        $payload = [
            'status' => Response::HTTP_CREATED,
            'app_message' => 'User has been save successfully',
            'user_message' => 'User has been save successfully',
        ];

        return response()->json($payload, Response::HTTP_CREATED);

    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            $payload = [
                'status' => Response::HTTP_NOT_FOUND,
                'app_message' => 'User not found',
                'user_message' => 'User not found',
            ];

            return response()->json($payload, Response::HTTP_NOT_FOUND);
        }

        $payload = [
            'status' => Response::HTTP_OK,
            'data' => new UserResource($user)
        ];

        return response()->json($payload, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {

        $user = User::find($id);

        if (!$user) {
            $payload = [
                'status' => Response::HTTP_NOT_FOUND,
                'app_message' => 'User not found',
                'user_message' => 'User not found',
            ];

            return response()->json($payload, Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'date_of_birth' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response(
                [
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'app_message' => 'Validation errors',
                    'user_message' => 'Validation errors',
                    'errors' => $validator->errors(),
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user->update($request->all());

        $payload = [
            'status' => Response::HTTP_OK,
            'app_message' => 'User has been updated',
            'user_message' => 'Success',
            'data' => new UserResource($user)
        ];

        return response()->json($payload, Response::HTTP_OK);
    }


    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            $payload = [
                'status' => Response::HTTP_NOT_FOUND,
                'app_message' => 'User not found',
                'user_message' => 'User not found',
            ];

            return response()->json($payload, Response::HTTP_NOT_FOUND);
        }

        $user->delete();

        $payload = [
            'status' => Response::HTTP_OK,
            'app_message' => 'User has been deleted',
            'user_message' => 'User has been deleted',
        ];

        return response()->json($payload, Response::HTTP_OK);
    }
}

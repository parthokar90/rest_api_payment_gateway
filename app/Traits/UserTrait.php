<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait UserTrait{

    public function notFound($user){

        if (!$user) {
            $payload = [
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'User not found',
            ];

            return response()->json($payload, Response::HTTP_NOT_FOUND);
        }

    }
}

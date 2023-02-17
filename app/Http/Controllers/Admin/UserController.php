<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserCollection;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    /**
     * List of Users.
     * @OA\Get(
     *    tags={"Users"},
     *    path="/admin/users",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Page number",
     *      @OA\Schema(type="integer"),
     *      allowEmptyValue="true",
     *    ),
     *    @OA\Parameter(
     *      name="name",
     *      in="query",
     *      description="Filter by name",
     *      @OA\Schema(type="string"),
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a listing of users.",
     *        @OA\JsonContent(ref="#/components/schemas/User"),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="No users.",
     *        @OA\JsonContent(),
     *    )
     * )
     *
     * @param Request $request
     * @return UserCollection
     */
    public function index(Request $request): UserCollection
    {
        return new UserCollection(
            User::query()
                ->when(
                    $request->has('name'),
                    fn (Builder $query) => $query->where('firstname', 'like', '%' . $request->get('name') . '%')
                        ->orWhere('lastname', 'like', '%' . $request->get('name') . '%')
                        ->orWhere('username', 'like', '%' . $request->get('name') . '%')
                )
                ->orderByDesc('updated_at')
                ->paginate($request->get('size', 20))
        );
    }

    /**
     * Show a selected User.
     * @OA\Get(
     *    tags={"Users"},
     *    path="/admin/users/{user}",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(
     *        name="user",
     *        in="path",
     *        required=true,
     *        description="User ID",
     *        @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a selected User.",
     *        @OA\JsonContent(ref="#/components/schemas/User"),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="User not found.",
     *        @OA\JsonContent(),
     *    )
     * )
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }
}

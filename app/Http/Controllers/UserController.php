<?php

namespace App\Http\Controllers;

use App\Http\Resources\Admin\UserCollection;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{
    /**
     * List of Users.
     * @OA\Get(
     *    tags={"Users"},
     *    path="/admin/users",
     *    @OA\Response(
     *      response="200",
     *      description="Display a list of users."
     *    ),
     *    @OA\MediaType(
     *      mediaType="application/json"
     *    ),
     *    @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Page number",
     *      @OA\Schema(
     *          type="integer"
     *      )
     *    ),
     *    @OA\Parameter(
     *      name="name",
     *      in="query",
     *      description="name",
     *      @OA\Schema(
     *          type="string"
     *      )
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
     *    @OA\Response(
     *      response="200",
     *      description="Display a selected user."
     *    ),
     *    @OA\Response(
     *      response="404",
     *      description="User not found."
     *    ),
     *    @OA\MediaType(
     *      mediaType="application/json"
     *    ),
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     * )
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }
}

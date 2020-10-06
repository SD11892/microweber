<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Post\Http\Controllers\Admin;

use MicroweberPackages\App\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Crud\Traits\HasCrudActions;
use MicroweberPackages\Post\Http\Requests\PostRequest;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Post\Repositories\PostRepository;

class PostsController extends AdminDefaultController
{
    use HasCrudActions;

    public $repository;
    public $model = Post::class;
    public $validator = PostRequest::class;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *      path="/admin/posts",
     *      operationId="listPosts",
     *      tags={"Posts"},

     *      summary="Get list of posts",
     *      description="Returns list of posts",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */

    /**
     * @OA\Post(
     * path="/admin/posts/store",
     * summary="Store post in database.",
     * description="Title and descriptions.",
     * operationId="storePost",
     * tags={"Posts"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Set the title, description, image.",
     *    @OA\JsonContent(
     *       required={"title"},
     *       @OA\Property(property="title", type="string", format="text", example="My cool post"),
     *       @OA\Property(property="description", type="string", format="text", example="My cool description")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success response",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="string", example="1234")
     *        )
     *     )
     * )
     */


    /**
     * @OA\Delete(
     *      path="/admin/posts/{id}",
     *      operationId="deletePost",
     *      tags={"Posts"},
     *      summary="Delete existing post",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Post id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
}
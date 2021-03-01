<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace MicroweberPackages\Order\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\App\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Order\Http\Requests\OrderRequest;
use MicroweberPackages\Order\Http\Requests\OrderCreateRequest;
use MicroweberPackages\Order\Http\Requests\OrderUpdateRequest;
use MicroweberPackages\Order\Repositories\OrderRepository;

class OrderApiController extends AdminDefaultController
{
    public $order;

    public function __construct(OrderRepository $order)
    {
        $this->order = $order;

    }

    /**
    /**
     * Display a listing of the order.
     *
     * @param orderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return (new JsonResource(
            $this->order
                ->filter($request->all())
                ->paginate($request->get('limit', 30))
                ->appends($request->except('page'))

        ))->response();

    }

    /**
     * Store order in database
     *
     * @param orderCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(orderCreateRequest $request)
    {
        $result = $this->order->create($request->all());
        return (new JsonResource($result))->response();
    }

    /**
     * Display the specified resource.show
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = $this->order->show($id);

        return (new JsonResource($result))->response();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  orderRequest $request
     * @param  string $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(orderUpdateRequest $request, $order)
    {

        $result = $this->order->update($request->all(), $order);
        return (new JsonResource($result))->response();
    }

    /**
     * Destroy resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function delete($id)
    {
        return $this->order->delete($id);
    }

    /**
     * Delete resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function destroy($ids)
    {
        return $this->order->destroy($ids);
    }
}
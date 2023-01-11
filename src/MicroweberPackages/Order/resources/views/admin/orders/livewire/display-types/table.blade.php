<div class="table-responsive">

    <table class="table orders-table">
    <thead>
    <tr>
        <th scope="col"> <input type="checkbox" wire:model="selectAll" class=""> </th>
        @if($showColumns['id'])
            @include('order::admin.orders.livewire.table-includes.table-th',['name'=>'ID', 'key'=>'id', 'filters'=>$filters])
        @endif

        @if($showColumns['image'])
            <th scope="col">{{ _e('Image') }}</th>
        @endif

        @if($showColumns['products'])
            <th scope="col">{{ _e('Products') }}</th>
        @endif

        @if($showColumns['customer'])
            <th scope="col">{{ _e('Customer') }}</th>
        @endif

        @if($showColumns['shipping_method'])
            <th scope="col">{{ _e('Shipping Method') }}</th>
        @endif

        @if($showColumns['payment_method'])
            <th scope="col">{{ _e('Payment Method') }}</th>
        @endif

        @if($showColumns['payment_status'])
            <th scope="col">{{ _e('Payment Status') }}</th>
        @endif


        @if($showColumns['total_amount'])
            <th scope="col">{{ _e('Total Amount') }}</th>
        @endif

        @if($showColumns['status'])
            <th scope="col">{{ _e('Status') }}</th>
        @endif

        @if($showColumns['created_at'])
            <th scope="col">{{ _e('Created At') }}</th>
        @endif
        @if($showColumns['updated_at'])
            <th scope="col">{{ _e('Updated At') }}</th>
        @endif

        @if($showColumns['actions'])
            <th scope="col">{{ _e('Actions') }}</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach ($orders as $order)

        @php
            $carts = [];
            if (!empty($order->cart)) {
               $carts = $order->cart;
            }
            $cart = $order->cart->first();

            $cartProduct = [];
            if (isset($cart->products)) {
                $cartProduct = $cart->products->first();
            }
        @endphp

        <tr class="manage-post-item">
            <td>
                <input type="checkbox" value="{{ $order->id }}" wire:model="checked">
            </td>
            @if($showColumns['id'])
                <td>
                    {{ $order->id }}
                </td>
            @endif

            @if($showColumns['image'])
                <td>
                    @if (isset($cartProduct) && $cartProduct != null)
                        <a href="{{route('admin.order.show', $order->id)}}">
                            <div class="img-circle-holder">
                                <img src="{{$cartProduct->thumbnail()}}" />
                            </div>
                        </a>
                    @endif
                </td>
            @endif

            @if($showColumns['products'])
                <td>
                    @foreach ($carts as $cart)
                        @php
                            $cartProduct = $cart->products->first();
                            if ($cartProduct == null) {
                                continue;
                            }
                        @endphp
                        <a href="{{route('admin.order.show', $order->id)}}">{{$cartProduct->title}}</a> <span class="text-muted">x{{$cart->qty}}</span> <br />
                    @endforeach
                </td>
            @endif

            @if($showColumns['customer'])
                <td>
                    {{$order->customerName()}}
                </td>
            @endif

            @if($showColumns['shipping_method'])
                <td>
                    {{$order->shippingMethodName()}}
                </td>
            @endif
            @if($showColumns['payment_method'])
                <td style="text-align: center">
                    {{$order->paymentMethodName()}}
                </td>
            @endif
            @if($showColumns['payment_status'])
                <td style="text-align: center">
                    @if($order->is_paid == 1)
                        <span class="badge badge-success">{{ _e('Paid') }}</span>
                    @else
                        <span class="badge badge-danger">{{ _e('Unpaid') }}</span>
                    @endif
                </td>
            @endif


            @if($showColumns['total_amount'])
                <td>
                    <span>{{$order->payment_amount}} {{$order->payment_currency}}</span>
                </td>
            @endif

            @if($showColumns['status'])
                <td style="text-align: center">
                    @if($order->order_status == 'pending')
                        <span class="badge badge-warning text-white">{{ _e('Pending') }}</span>
                    @elseif($order->order_status == 'new')
                        <span class="badge badge-primary">{{ _e('New') }}</span>
                    @elseif($order->order_status == 'completed')
                        <span class="badge badge-success">{{ _e('Completed') }}</span>
                    @else
                        <span class="badge badge-primary">{{$order->order_status}}</span>
                    @endif
                </td>
            @endif

            @if($showColumns['created_at'])
                <td style="text-align: center">
                    {{$order->created_at}}<br />  {{mw()->format->ago($order->created_at)}}
                </td>
            @endif
            @if($showColumns['updated_at'])
                <td style="text-align: center">
                    {{$order->updated_at}}<br />  {{mw()->format->ago($order->updated_at)}}
                </td>
            @endif

            @if($showColumns['actions'])
                <td style="text-align: center">
                    <a href="{{route('admin.order.show', $order->id)}}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-eye"></i>    View
                    </a>
                </td>
            @endif

        </tr>
    @endforeach
    </tbody>

</table>
</div>

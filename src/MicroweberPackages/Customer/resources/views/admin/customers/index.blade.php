@extends('invoice::admin.layout')

@section('icon')
    <i class="mdi mdi-account-search module-icon-svg-fill"></i>
@endsection

@section('title', _e('Clients', true))

@section('content')

    <script type="text/javascript">
        $(document).ready(function () {
            $(".js-select-all").click(function () {
                $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
                //$('.js-delete-all').toggle();
            });

            $('.js-delete-selected-form').submit(function (e) {
                e.preventDefault();

                var id = [];
                $("input[name='id']:checked").each(function () {
                    id.push($(this).val());
                });

                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: {id: id},
                    success: function (data) {
                        window.location = window.location;
                    }
                });
            });

        });
    </script>

    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('customers.create') }}" class="btn btn-outline-primary pull-right mb-3 icon-left">
                <i class="mdi mdi-account-plus"></i> New customer
            </a>
        </div>
    </div>

    <form method="get">
        <input type="hidden" value="true" name="filter">
        <div class="bg-info pl-3 pr-3 pt-3 pb-3">
            <div class="row">
                <div class="col-md-4">
                    <label><?php _e('Search'); ?></label>
                    <input type="text" class="form-control"
                           value="@if(request()->get('search')){{request()->get('search')}}@endif"
                           name="search">
                </div>
                <div class="col-md-3">
                    <label><?php _e('Name'); ?></label>
                    <input type="text" class="form-control"
                           value="@if(request()->get('name')){{request()->get('name')}}@endif" name="name">
                </div>

                <div class="col-md-3">
                    <label><?php _e('Phone'); ?></label>
                    <input type="text" class="form-control"
                           value="@if(request()->get('phone')){{request()->get('phone')}}@endif" name="phone">
                </div>
                <div class="col-md-2">
                    <div class="pt-4">
                    @if(request()->get('filter') == 'true')
                        <a href="{{route('customers.index')}}" class="btn btn-outline-primary icon-right">Filter <i class="mdi mdi-close"></i></a>
                    @else
                        <button type="submit" class="btn btn-outline-primary icon-right">Filter <i class="mdi mdi-filter"></i></button>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <br/>

    <div class="actions">
        <form method="POST" class="js-delete-selected-form" action="{{ route('customers.delete') }}">
            {{csrf_field()}}
            <button class="btn btn-danger js-delete-all"><i class="fa fa-times"></i> <?php _e('Delete all'); ?></button>
        </form>
    </div>

    <table class="table mt-3">
        <thead>
        <tr>
            <th><input type="checkbox" class="js-select-all"></th>
            <th><?php _e('Name'); ?></th>
            <th><?php _e('Email'); ?></th>
            <th><?php _e('Phone'); ?></th>
            <th><?php _e('Amount Due'); ?></th>
            <th><?php _e('Added on'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($customers as $customer)
            <tr>
                <th><input type="checkbox" name="id" class="js-selected-customer" value="{{$customer->id}}"></th>
                <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ number_format($customer->due_amount, 2) }}</td>
                <td>{{ $customer->created_at }}</td>
                <td>
                    <div class="btn-group">
                        <a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            Action
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('customers.edit', $customer->id) }}"><i
                                        class="fa fa-pen"></i> &nbsp; <?php _e('Edit'); ?></a>
                            <a class="dropdown-item" href="{{ route('customers.show', $customer->id) }}"><i
                                        class="fa fa-eye"></i> &nbsp; <?php _e('View'); ?></a>
                            <a class="dropdown-item" href="{{ route('customers.edit', $customer->id) }}"><i
                                        class="fa fa-times"></i> &nbsp; <?php _e('Delete'); ?></a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
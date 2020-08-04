@extends('invoice::admin.layout')

@if (isset($payment) && $payment)
    @section('title', 'Edit payment')
@else
    @section('title', 'Add new payment')
@endif

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif

    <a href="{{route('payments.index')}}" class="btn btn-outline-primary"><i class="fa fa-list"></i> Back to payments</a>
    <br />
    <br />

    @if(count($invoices)==0)
        <div class="alert alert-success"><i class="fa fa-check"></i> All invoices are payed. Nothing for pay.</div>
    @endif

    @if($payment)
        <form method="post" action="{{ route('payments.update', $payment->id) }}">
            @method('PUT')
            @else
                <form method="post" action="{{ route('payments.store') }}">
                    @endif
                    @csrf
                    <div class="row">


                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date:</label>
                                <input type="datetime-local" class="form-control"
                                       value="@if($payment){{$payment->payment_date}}@else{{date('Y-m-d')}}@endif"
                                       name="payment_date"/>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payment Number:</label>
                                <input type="text" class="form-control"
                                       value="@if(isset($payment) && $payment){{ $payment->payment_number }}@else{{ $nextPaymentNumber }} @endif"
                                       name="payment_number"/>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Customer:</label>
                                <select class="form-control selectpicker" data-live-search="true" name="customer_id"
                                        placeholder="Start typing something to search customers...">
                                    <option disabled="disabled">Select customer..</option>
                                    @foreach($customers as $customer)
                                        <option @if($payment && $customer->id == $payment->customer_id)selected="selected"
                                                @endif value="{{$customer->id}}">{{$customer->first_name}} {{$customer->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Invoice:</label>
                                <select class="form-control selectpicker" data-live-search="true" name="invoice_id"
                                        placeholder="Start typing something to search invoices...">
                                    @if(count($invoices)==0)
                                        <option>No invoices for paying.</option>
                                    @else
                                    <option>Select invoice..</option>
                                    @endif
                                    @foreach($invoices as $invoice)
                                        <option
                                        @if(request()->get('invoice_id') == $invoice->id)selected="selected"@endif
                                        @if($payment && $invoice->id == $payment->invoice_id)selected="selected"@endif
                                        value="{{$invoice->id}}">
                                            {{$invoice->invoice_number}}
                                        </option>
                                    @endforeach
                                </select>
                                <br/>
                                <br/>
                                @if(count($invoices)> 0)
                                    <div class="alert alert-warning"><i class="fa fa-list"></i> Select invoices wich are not payed.</div>
                               @endif


                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Amount:</label>
                                <div class="form-group">
                                    <div class="input-group mb-3 prepend-transparent append-transparent">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-muted">{{ mw()->shop_manager->currency_symbol() }}</span>
                                        </div>
                                        <input type="text" class="form-control" name="amount"
                                               value="@if(isset($payment) && $payment){{ number_format($payment->amount, 2) }}@elseif(request()->get('amount') !== ''){{request()->get('amount')}}@endif"/>
                                        <div class="input-group-append">
                                            <span class="input-group-text" data-toggle="tooltip"
                                                  title="To put a product on sale, makeCompare at price the original price and enter the lower amount into Price."><i
                                                        class="mdi mdi-help-circle"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payment Method:</label>
                                <select class="form-control selectpicker" data-live-search="true" name="payment_method_id"
                                        placeholder="Start typing something to search customers...">
                                    <option>Select payment method..</option>
                                    @foreach($paymentMethods as $paymentMethod)
                                        <option @if($payment && $paymentMethod->id == $payment->payment_method_id)selected="selected"
                                                @endif value="{{$paymentMethod->id}}">{{$paymentMethod->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Note:</label>
                                <textarea class="form-control"
                                          name="note">@if(isset($payment) && $payment){{ $payment->note }}@endif</textarea>
                            </div>
                        </div>


                        <div class="col-md-12" style="margin-top:15px;">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Payment
                            </button>
                        </div>


                    </div>
                </form>

@endsection
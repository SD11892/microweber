@extends('invoice::admin.layout')

@section('title', 'Create Invoice')


@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif

    <script>
        /**
         * bojkata bojkata bojkata
         */
        class Invoice {
            constructor() {
                this.items = [];
                this.total = 0.00;
                this.subTotal = 0.00;
            }

            addItem(price, quantity) {
                var item = {price: price, quantity: quantity};
                this.items.push(item);
                return (this.items.length - 1);
            }

            addNewItem() {
                var itemId = this.addItem(0.00, 1);
                $('.js-invoice-items').append(this.invoiceItemTemplate(itemId, 0.00, 1));
                this.calculate();
            }

            calculate() {

                var i = 0;
                var itemsTotal = 0;
                for (i = 0; i < this.items.length; i++) {
                    itemsTotal = itemsTotal + (this.items[i].price * this.items[i].quantity);
                }
                this.total = itemsTotal;
                this.subTotal = itemsTotal;

                $('.js-invoice-total').val(this.total);
                $('.js-invoice-sub-total').val(this.subTotal);
            }

            inputsItemsChange(item) {

                var itemId = parseInt(item.attr('data-item-id'));

                if (item.attr('data-item-type') == 'quantity') {
                    this.items[itemId].quantity = item.val();
                }

                if (item.attr('data-item-type') == 'price') {
                    this.items[itemId].price = item.val();
                }

                this.calculate();
            }

            invoiceItemTemplate(itemId, price, quantity) {
                return '<tr class="js-invoice-item">' +
                    '<td>' +
                    '    <input type="text" class="form-control js-invoice-item-input" name="item[' + itemId + '][name]" placeholder="Type or click to select an item">' +
                    '    <textarea style="margin-top:5px;border:0px;background: none" name="item['+itemId+'][description]"  placeholder="Type item Description (optional)" class="form-control js-invoice-item-input"></textarea>' +
                    '</td>' +
                    '<td>' +
                    '    <input type="text" class="form-control js-invoice-item-input" data-item-id="' + itemId + '" data-item-type="quantity" name="item[' + itemId + '][quantity]" value="'+quantity+'">' +
                    '</td>' +
                    '<td>' +
                    '    <input type="text" class="form-control js-invoice-item-input" data-item-id="' + itemId + '" data-item-type="price" name="item[' + itemId + '][price]" value="'+price+'">' +
                    '</td>' +
                    '<td>' +
                    '    0.00' +
                    '</td>' +
                    '<td style="text-align: center;width: 10px">' +
                    '    <button class="btn btn-danger" type="button" onclick="invoice.removeItem(' + itemId + ')"><i class="fa fa-times"></i></button>' +
                    '</td>' +
                    '</tr>';
            }
        }

        $(document).ready(function () {
            invoice = new Invoice();
            invoice.addNewItem();
            invoice.calculate();
            $('body').on('change', '.js-invoice-item-input', function() {
                invoice.inputsItemsChange($(this));
            });
        });
    </script>

    <form method="post" action="{{ route('invoices.store') }}">
        @csrf

        <div class="row">

            <div class="col-md-12">
                <div class="form-group">
                    <label>Customer:</label>
                    <select class="form-control" name="user_id">
                        @foreach($users as $user):
                        <option value="{{$user->id}}">{{$user->email}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Invoice Number:</label>
                    <input type="text" class="form-control" value="{{ $nextInvoiceNumber }}" name="nextInvoiceNumber"/>
                </div>
            </div>


            <div class="col-md-4">
                <div class="form-group">
                    <label>Invoice Date:</label>
                    <input type="date" class="form-control" value="{{ date('Y-m-d') }}" name="invoice_date"/>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Invoice Due Date:</label>
                    <input type="date" class="form-control"
                           value="{{ date('Y-m-d', strtotime('+6 days', strtotime(date('Y-m-d')))) }}" name="due_date"/>
                </div>
            </div>

            <div class="col-md-12" style="margin-top:15px;">
                <div class="row">
                    <div class="col-md-9">

                        <table class="js-invoice-table table table-bordered">
                            <thead>
                            <tr>
                                <th>Items</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="js-invoice-items"></tbody>
                        </table>

                        <button class="btn btn-success" type="button" onclick="invoice.addNewItem();"><i
                                    class="fa fa-shopping-basket"></i> Add new item
                        </button>


                    </div>
                    <div class="col-md-3">
                        <div style="width:100%;background:#fff;border-radius: 3px;padding-top: 15px;padding-bottom: 15px;">

                            <div class="form-group col-md-12">
                                <label>Sub total:</label>
                                <input type="text" disabled="disabled" class="form-control js-invoice-sub-total"
                                       value="0.00"/>
                            </div>

                            <div class="container">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Discount:</label>
                                        <input type="text" class="form-control" value="0.00" name="discount_val"/>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Discount Type:</label>
                                        <select class="form-control" name="discount">
                                            <option value="fixed">Fixed</option>
                                            <option value="precentage">Precentage</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12" style="text-align: right">
                                <div class="form-group">
                                    <label>Tax:</label>
                                    <br/>
                                    @foreach($taxTypes as $taxType)
                                        <b>{{$taxType->name}} - {{$taxType->percent }} % </b><br/>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Total:</label>
                                <input type="text" disabled="disabled" class="form-control js-invoice-total"
                                       value="0.00"/>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12" style="margin-top:35px;">
                <label>Invoice Template:</label>
                <select class="form-control" name="invoice_template_id">
                    <option value="0">Simple</option>
                </select>
            </div>

            <input type="hidden" value="{{$taxType->id}}" name="tax"/>
            <input type="hidden" value="0.00" class="js-invoice-total" name="total"/>
            <input type="hidden" value="0.00" class="js-invoice-sub-total" name="sub_total"/>

            <div class="col-md-12" style="margin-top:15px;">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Invoice</button>
            </div>

        </div>
    </form>

    </div>
@endsection
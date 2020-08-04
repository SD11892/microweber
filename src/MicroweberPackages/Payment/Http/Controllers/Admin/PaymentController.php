<?php
namespace MicroweberPackages\Payment\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\AdminController;
use MicroweberPackages\Customer\Customer;
use MicroweberPackages\Invoice\Company;
use MicroweberPackages\Payment\Http\Requests\PaymentRequest;
use MicroweberPackages\Invoice\Invoice;
use MicroweberPackages\Payment\Payment;
use MicroweberPackages\Payment\PaymentMethod;
use Carbon\Carbon;
use function MongoDB\BSON\toJSON;
use MicroweberPackages\Invoice\User;
use Validator;
use MicroweberPackages\Invoice\Mail\PaymentPdf;

class PaymentController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 10;

        $payments = Payment::with(['customer', 'invoice', 'paymentMethod'])
            ->join('customers', 'customers.id', '=', 'payments.customer_id')
            ->leftJoin('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'payments.payment_method_id')
            ->applyFilters($request->only([
                'search',
                'payment_number',
                'payment_method_id',
                'customer_id',
                'orderByField',
                'orderBy'
            ]))
           //  ->whereCompany($request->header('company'))
            ->select('payments.*', 'customers.name', 'invoices.invoice_number', 'payment_methods.name as payment_mode')
            ->latest()
            ->paginate($limit);

        return $this->view('payment::admin.payments.index', ['customers'=> Customer::all(), 'paymentMethods'=>PaymentMethod::all(), 'payments'=>$payments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Update Payment Methods
        $paymentOptions = payment_options();
        if (!empty($paymentOptions)) {
            foreach ($paymentOptions as $paymentOption) {
                $findPayment = PaymentMethod::where('name', $paymentOption['name'])->first();
                if (!$findPayment) {
                    PaymentMethod::create([
                        'name'=>$paymentOption['name']
                    ]);
                }
            }
        }
        
        $payment_prefix = 'PAY';//CompanySetting::getSetting('payment_prefix', $request->header('company'));
        $payment_num_auto_generate =1;// CompanySetting::getSetting('payment_auto_generate', $request->header('company'));


        $nextPaymentNumberAttribute = null;
        $nextPaymentNumber = Payment::getNextPaymentNumber($payment_prefix);

        if ($payment_num_auto_generate == "YES") {
            $nextPaymentNumberAttribute = $nextPaymentNumber;
        }

        return $this->view('payment::admin.payments.edit', [
            'payment'=>false,
            'invoices'=> Invoice::all(),
            'customers' => Customer::
            //whereCompany($request->header('company'))
                get(),
            'paymentMethods' => PaymentMethod::
            //whereCompany($request->header('company'))
                latest()
                ->get(),
            'nextPaymentNumberAttribute' => $nextPaymentNumberAttribute,
            'nextPaymentNumber' => $payment_prefix.'-'.$nextPaymentNumber,
            'payment_prefix' => $payment_prefix
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentRequest $request)
    {
        $payment_number = explode("-",$request->payment_number);
        $number_attributes['payment_number'] = $payment_number[0].'-'.sprintf('%06d', intval($payment_number[1]));

        Validator::make($number_attributes, [
            'payment_number' => 'required|unique:payments,payment_number'
        ])->validate();

        $payment_date = Carbon::createFromFormat('Y-m-d', $request->payment_date);

        if ($request->has('invoice_id') && $request->invoice_id != null) {
            $invoice = Invoice::find($request->invoice_id);
            if ($invoice && $invoice->due_amount == $request->amount) {
                $invoice->status = Invoice::STATUS_ORIGINAL;
                $invoice->paid_status = Invoice::STATUS_PAID;
                $invoice->due_amount = 0;
            } elseif ($invoice && $invoice->due_amount != $request->amount) {
                $invoice->due_amount = (int)$invoice->due_amount - (int)$request->amount;
                if ($invoice->due_amount < 0) {
                    return response()->json([
                        'error' => 'invalid_amount'
                    ]);
                }
                $invoice->paid_status = Invoice::STATUS_PARTIALLY_PAID;
            }
            $invoice->save();
        }

        $payment = Payment::create([
            'payment_date' => $payment_date,
            'payment_number' => $number_attributes['payment_number'],
            'customer_id' => $request->customer_id,
            'company_id' => $request->header('company'),
            'invoice_id' => $request->invoice_id,
            'payment_method_id' => $request->payment_method_id,
            'amount' => $request->amount,
            'notes' => $request->notes,
            'unique_hash' => str_random(60)
        ]);

        return redirect(route('payments.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::with(['customer', 'invoice', 'paymentMethod'])->find($id);

        return response()->json([
            'payment' => $payment,
            'shareable_link' => url('/payments/pdf/'.$payment->unique_hash)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $payment = Payment::with(['customer', 'invoice', 'paymentMethod'])->find($id);

        $invoices = Invoice::where('paid_status', '<>', Invoice::STATUS_PAID)
            ->where('customer_id', $payment->customer_id)->where('due_amount', '>', 0)
           // ->whereCompany($request->header('company'))
            ->get();

        return $this->view('payment::admin.payments.edit', [
            'invoice_id'=>$id,
            'customers' => Customer::
               // ->whereCompany($request->header('company'))
                get(),
            'paymentMethods' => PaymentMethod::
            //whereCompany($request->header('company'))
                latest()
                ->get(),
            'nextPaymentNumber' => $payment->getPaymentNumAttribute(),
            'payment_prefix' => $payment->getPaymentPrefixAttribute(),
            'shareable_link' => url('/payments/pdf/'.$payment->unique_hash),
            'payment' => $payment,
            'invoices' => $invoices
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentRequest $request, $id)
    {
        $payment_number = explode("-",$request->payment_number);
        $number_attributes['payment_number'] = $payment_number[0].'-'.sprintf('%06d', intval($payment_number[1]));

        Validator::make($number_attributes, [
            'payment_number' => 'required|unique:payments,payment_number'.','.$id
        ])->validate();

        $payment_date = Carbon::createFromFormat('Y-m-d', $request->payment_date);

        $payment = Payment::find($id);
        $oldAmount = $payment->amount;

        if ($request->has('invoice_id') && $request->invoice_id && ($oldAmount != $request->amount)) {
            $amount = (int)$request->amount - (int)$oldAmount;
            $invoice = Invoice::find($request->invoice_id);
            $invoice->due_amount = (int)$invoice->due_amount - (int)$amount;
            if ($invoice->due_amount < 0) {
                return response()->json([
                    'error' => 'invalid_amount'
                ]);
            }

            if ($invoice->due_amount == 0) {
                $invoice->status = Invoice::STATUS_ORIGINAL;
                $invoice->paid_status = Invoice::STATUS_PAID;
            } else {
                $invoice->status = $invoice->getPreviousStatus();
                $invoice->paid_status = Invoice::STATUS_PARTIALLY_PAID;
            }

            $invoice->save();
        }

        $payment->payment_date = $payment_date;
        $payment->payment_number = $number_attributes['payment_number'];
        $payment->customer_id = $request->customer_id;
        $payment->invoice_id = $request->invoice_id;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->amount = $request->amount;
        $payment->notes = $request->notes;
        $payment->save();

        return redirect(route('payments.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);

        if ($payment->invoice_id != null) {
            $invoice = Invoice::find($payment->invoice_id);
            $invoice->due_amount = ((int)$invoice->due_amount + (int)$payment->amount);

            if ($invoice->due_amount == $invoice->total) {
                $invoice->paid_status = Invoice::STATUS_UNPAID;
            } else {
                $invoice->paid_status = Invoice::STATUS_PARTIALLY_PAID;
            }

            $invoice->status = $invoice->getPreviousStatus();
            $invoice->save();
        }

        $payment->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function delete(Request $request)
    {
        foreach ($request->id as $id) {
            $payment = Payment::find($id);
            if (!$payment) {
                continue;
            }

            if ($payment->invoice_id != null) {
                $invoice = Invoice::find($payment->invoice_id);
                $invoice->due_amount = ((int)$invoice->due_amount + (int)$payment->amount);

                if ($invoice->due_amount == $invoice->total) {
                    $invoice->paid_status = Invoice::STATUS_UNPAID;
                } else {
                    $invoice->paid_status = Invoice::STATUS_PARTIALLY_PAID;
                }

                $invoice->status = $invoice->getPreviousStatus();
                $invoice->save();
            }

            $payment->delete();
        }

        return [
            'status' => 'success'
        ];
    }

    public function sendPayment(Request $request)
    {
        $payment = Payment::findOrFail($request->id);

        $data['payment'] = $payment->toArray();
        $customerId = $data['payment']['customer_id'];
        $data['customer'] = Customer::find($customerId)->toArray();
        $data['company'] = Company::find($payment->company_id);
        $email = $data['customer']['email'];

        if (!$email) {
            return response()->json([
                'error' => 'user_email_does_not_exist'
            ]);
        }

        \Mail::to($email)->send(new PaymentPdf($data));

        return response()->json([
            'success' => true
        ]);
    }
}

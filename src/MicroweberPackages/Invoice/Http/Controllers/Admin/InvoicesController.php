<?php
namespace MicroweberPackages\Invoice\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Invoice\CompanySetting;
use MicroweberPackages\Invoice\Company;
use MicroweberPackages\Currency\Currency;
use MicroweberPackages\Customer\Customer;
use MicroweberPackages\Invoice\InvoiceTemplate;
use MicroweberPackages\Invoice\Http\Requests;
use MicroweberPackages\Invoice\Invoice;
use MicroweberPackages\Invoice\InvoiceItem;
use Carbon\Carbon;
use MicroweberPackages\Invoice\Item;
use MicroweberPackages\Invoice\Mail\InvoicePdf;
use MicroweberPackages\App\Http\Controllers\AdminController;
use function MongoDB\BSON\toJSON;
use Illuminate\Support\Facades\Log;
use Mailgun\Mailgun;
use PDF;
use Validator;
use MicroweberPackages\Invoice\TaxType;
use MicroweberPackages\Invoice\Tax;
use MicroweberPackages\Page\Http\Requests\PageRequest;

class InvoicesController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Invoice Templates
        $invoiceTemplate = InvoiceTemplate::first();
        if (!$invoiceTemplate) {
            InvoiceTemplate::create([
                'name' => 'Template 1',
                'view' => 'invoice1',
                'path' => '/assets/img/PDF/Template1.png'
            ]);

            InvoiceTemplate::create([
                'name' => ' Template 2',
                'view' => 'invoice2',
                'path' => '/assets/img/PDF/Template2.png'
            ]);

            InvoiceTemplate::create([
                'name' => 'Template 3',
                'view' => 'invoice3',
                'path' => '/assets/img/PDF/Template3.png'
            ]);
        }

        // Curencies
        $currencies = Currency::first();
        if (!$currencies) {
            Currency::create([
               'name'=>'USD',
               'code'=>'USD',
               'symbol'=>'$',
               'precision'=>'2',
               'thousand_separator'=>',',
               'decimal_separator'=>'.',
               'swap_currency_symbol'=>0,
            ]);
        }

        $limit = $request->has('limit') ? $request->limit : 10;

        $invoices = Invoice::with(['items', 'customer', 'invoiceTemplate', 'taxes'])
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->applyFilters($request->only([
                'status',
                'paid_status',
                'customer_id',
                'invoice_number',
                'from_date',
                'to_date',
                'orderByField',
                'orderBy',
                'search',
            ]))
            // ->whereCompany($request->header('company'))
            ->select('invoices.*', 'customers.first_name')
            ->latest()
            ->paginate($limit);

        return $this->view('invoice::admin.invoices.index', [
            'customers'=> Customer::all(),
            'invoices' => $invoices,
            'invoiceTotalCount' => Invoice::count()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $tax_per_item = CompanySetting::getSetting('tax_per_item', $request->header('company'));
        $discount_per_item = CompanySetting::getSetting('discount_per_item', $request->header('company'));
        $invoice_num_auto_generate = CompanySetting::getSetting('invoice_auto_generate', $request->header('company'));

        $invoice_prefix = get_option('invoice_prefix', 'shop');
        if (empty($invoice_prefix)) {
            $invoice_prefix = 'INV';
        }

        $nextInvoiceNumberAttribute = null;
        $nextInvoiceNumber = Invoice::getNextInvoiceNumber($invoice_prefix);

        if ($invoice_num_auto_generate == "YES") {
            $nextInvoiceNumberAttribute = $nextInvoiceNumber;
        }

        return $this->view('invoice::admin.invoices.edit', [
            'taxTypes'=>\MicroweberPackages\Tax\TaxType::all(),
            'customers' => Customer::all(),
            'nextInvoiceNumberAttribute' => $nextInvoiceNumberAttribute,
            'nextInvoiceNumber' => $invoice_prefix.'-'.$nextInvoiceNumber,
            'items' => Item::with('taxes')->get(), // ->whereCompany($request->header('company')
            'invoiceTemplates' => InvoiceTemplate::all(),
            'tax_per_item' => $tax_per_item,
            'discount_per_item' => $discount_per_item,
            'invoice_prefix' => $invoice_prefix
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PageRequest $request)
    {
        $invoice_number = explode("-",$request->invoice_number);
        $number_attributes['invoice_number'] = $invoice_number[0].'-'.sprintf('%06d', intval($invoice_number[1]));

        Validator::make($number_attributes, [
            'invoice_number' => 'required|unique:invoices,invoice_number'
        ])->validate();

        $invoice_date = Carbon::createFromFormat('Y-m-d', $request->invoice_date);
        $due_date = Carbon::createFromFormat('Y-m-d', $request->due_date);
        $status = Invoice::STATUS_PROFORMA;

        $tax_per_item = CompanySetting::getSetting('tax_per_item', $request->header('company')) ?? 'NO';
        $discount_per_item = CompanySetting::getSetting('discount_per_item', $request->header('company')) ?? 'NO';

        if ($request->has('invoiceSend')) {
            $status = Invoice::STATUS_SENT;
        }

        $invoice = Invoice::create([
            'invoice_date' => $invoice_date,
            'due_date' => $due_date,
            'invoice_number' => $number_attributes['invoice_number'],
            'reference_number' => $request->reference_number,
            'customer_id' => $request->customer_id,
            'company_id' => $request->header('company'),
            'invoice_template_id' => $request->invoice_template_id,
            'status' => $status,
            'paid_status' => Invoice::STATUS_UNPAID,
            'sub_total' => $request->sub_total,
            'discount' => $request->discount,
            'discount_type' => $request->discount_type,
            'discount_val' => $request->discount_val,
            'total' => $request->total,
            'due_amount' => $request->total,
            'tax_per_item' => $tax_per_item,
            'discount_per_item' => $discount_per_item,
            'tax' => $request->tax,
            'notes' => $request->notes,
            'unique_hash' => str_random(60)
        ]);

        $invoiceItems = $request->items;

        foreach ($invoiceItems as $invoiceItem) {
            $invoiceItem['company_id'] = $request->header('company');
            $item = $invoice->items()->create($invoiceItem);

            if (array_key_exists('taxes', $invoiceItem) && $invoiceItem['taxes']) {
                foreach ($invoiceItem['taxes'] as $tax) {
                    $tax['company_id'] = $request->header('company');
                    if (gettype($tax['amount']) !== "NULL") {
                        $item->taxes()->create($tax);
                    }
                }
            }
        }

        if ($request->has('taxes')) {
            foreach ($request->taxes as $tax) {
                $tax['company_id'] = $request->header('company');

                if (gettype($tax['amount']) !== "NULL") {
                    $invoice->taxes()->create($tax);
                }
            }
        }

        if ($request->has('invoiceSend')) {
            $data['invoice'] = Invoice::findOrFail($invoice->id)->toArray();
            $data['customer'] = Customer::find($request->customer_id)->toArray();
            $data['company'] = Company::find($invoice->company_id);

            $email = $data['user']['email'];

            if (!$email) {
                return response()->json([
                    'error' => 'user_email_does_not_exist'
                ]);
            }

            \Mail::to($email)->send(new InvoicePdf($data));
        }

        return redirect(route('invoices.index'))->with('status', 'Invoice is created success.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $invoice = Invoice::with([
            'items',
            'items.taxes',
            'customer',
            'invoiceTemplate',
            'taxes.taxType'
        ])->find($id);

        if (!$invoice) {
            return redirect(route('invoices.index'))->with('status_danger', 'Invoice not found.');
        }

        $siteData = [
            'invoice' => $invoice,
            'shareable_link' => url('/invoices/pdf/' . $invoice->unique_hash)
        ];

        return $this->view('invoice::admin.invoices.show', $siteData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request,$id)
    {
        $invoice = Invoice::with([
            'items',
            'items.taxes',
            'customer',
            'invoiceTemplate',
            'taxes.taxType'
        ])->find($id);

        return $this->view('invoice::admin.invoices.edit', [
            'customers' => Customer::all(),
            'taxTypes'=>\MicroweberPackages\Tax\TaxType::all(),
            'nextInvoiceNumber' => $invoice->getInvoiceNumAttribute(),
            'invoice' => $invoice,
            'invoiceTemplates' => InvoiceTemplate::all(),
            'tax_per_item' => $invoice->tax_per_item,
            'discount_per_item' => $invoice->discount_per_item,
            'shareable_link' => url('/invoices/pdf/'.$invoice->unique_hash),
            'invoice_prefix' => $invoice->getInvoicePrefixAttribute()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Requests\PageRequest $request, $id)
    {
        $invoice_number = explode("-",$request->invoice_number);
        $number_attributes['invoice_number'] = $invoice_number[0].'-'.sprintf('%06d', intval($invoice_number[1]));
        $number_attributes['invoice_number'] = trim($number_attributes['invoice_number']);

            Validator::make($number_attributes, [
            'invoice_number' => 'required|unique:invoices,invoice_number'.','.$id
        ])->validate();

        $invoice_date = Carbon::createFromFormat('Y-m-d', $request->invoice_date);
        $due_date = Carbon::createFromFormat('Y-m-d', $request->due_date);

        $invoice = Invoice::find($id);
        $oldAmount = $invoice->total;

        if ($oldAmount != $request->total) {
            $oldAmount = (int)round($request->total) - (int)$oldAmount;
        } else {
            $oldAmount = 0;
        }

        $invoice->due_amount = ($invoice->due_amount + $oldAmount);

        if ($invoice->due_amount == 0 && $invoice->paid_status != Invoice::STATUS_PAID) {
            $invoice->status = Invoice::STATUS_ORIGINAL;
            $invoice->paid_status = Invoice::STATUS_PAID;
        } elseif ($invoice->due_amount < 0 && $invoice->paid_status != Invoice::STATUS_UNPAID) {

            return redirect(route('invoices.index'))->with('status', 'Invalid invoice due amount.');

        } elseif ($invoice->due_amount != 0 && $invoice->paid_status == Invoice::STATUS_PAID) {
            $invoice->status = $invoice->getPreviousStatus();
            $invoice->paid_status = Invoice::STATUS_PARTIALLY_PAID;
        }

        $invoice->invoice_date = $invoice_date;
        $invoice->due_date = $due_date;
        $invoice->invoice_number =  $number_attributes['invoice_number'];
        $invoice->reference_number = $request->reference_number;
        $invoice->customer_id = $request->customer_id;
        $invoice->invoice_template_id = $request->invoice_template_id;
        $invoice->sub_total = $request->sub_total;
        $invoice->total = $request->total;
        $invoice->discount = $request->discount;
        $invoice->discount_type = $request->discount_type;
        $invoice->discount_val = $request->discount_val;
        $invoice->tax = $request->tax;
        $invoice->notes = $request->notes;
        $invoice->save();

        $oldItems = $invoice->items->toArray();
        $oldTaxes = $invoice->taxes->toArray();
        $invoiceItems = $request->items;

        foreach ($oldItems as $oldItem) {
            InvoiceItem::destroy($oldItem['id']);
        }

        foreach ($oldTaxes as $oldTax) {
            Tax::destroy($oldTax['id']);
        }
        foreach ($invoiceItems as $invoiceItem) {
            $invoiceItem['company_id'] = $request->header('company');
            $item = $invoice->items()->create($invoiceItem);

            if (array_key_exists('taxes', $invoiceItem) && $invoiceItem['taxes']) {
                foreach ($invoiceItem['taxes'] as $tax) {
                    $tax['company_id'] = $request->header('company');
                    if (gettype($tax['amount']) !== "NULL") {
                        $item->taxes()->create($tax);
                    }
                }
            }
        }

        if ($request->has('taxes')) {
            foreach ($request->taxes as $tax) {
                $tax['company_id'] = $request->header('company');

                if (gettype($tax['amount']) !== "NULL") {
                    $invoice->taxes()->create($tax);
                }
            }
        }

        return redirect(route('invoices.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $invoice = Invoice::find($id);

        if ($invoice && $invoice->payments()->exists() && $invoice->payments()->count() > 0) {
            // Payment attached
            return redirect(route('invoices.index'))->with('status', 'Invoice has attached payments.');
        }

        $invoice = Invoice::destroy($id);

        return redirect(route('invoices.index'))->with('status', 'Invoice is deleted.');
    }

    public function delete(Request $request)
    {
        $cantBeDeleted = [];
        foreach ($request->id as $id) {
            $invoice = Invoice::find($id);

            if ($invoice && $invoice->payments()->exists() && $invoice->payments()->count() > 0) {
                $cantBeDeleted[] = 'Invoice has attached payments.';
                continue;
            }

            $invoice->delete();
        }

        if (!empty($cantBeDeleted)) {
            return ['status'=>'danger', 'message'=> count($cantBeDeleted) . ' invoices has attached with payments and can\'t be deleted.'];
        }

        return ['status'=>'success', 'message'=> 'Invoice is deleted.'];
    }



     /**
     * Mail a specific invoice to the correponding cusitomer's email address.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendInvoice(Request $request)
    {
        $invoice = Invoice::findOrFail($request->id);

        $data['invoice'] = $invoice->toArray();
        $customerId = $data['invoice']['customer_id'];
        $data['customer'] = Customer::find($customerId)->toArray();
        $data['company'] = Company::find($invoice->company_id);
        $email = $data['customer']['email'];

        if (!$email) {
            return redirect(route('invoices.edit', $request->id))->with('status', 'User email does not exist.');
        }

        \Mail::to($email)->send(new InvoicePdf($data));

        if ($invoice->status == Invoice::STATUS_PROFORMA) {
            $invoice->status = Invoice::STATUS_SENT;
            $invoice->sent = true;
            $invoice->save();
        }


        return redirect(route('invoices'))->with('status', 'Invoice is sent.');
    }


     /**
     * Mark a specific invoice as sent.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsSent(Request $request)
    {
        $invoice = Invoice::findOrFail($request->id);
        $invoice->status = Invoice::STATUS_SENT;
        $invoice->sent = true;
        $invoice->save();

        return redirect(route('invoices.index'))->with('status', 'Invoice is marked as sent.');
    }


     /**
     * Mark a specific invoice as paid.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsPaid(Request $request)
    {
        $invoice = Invoice::findOrFail($request->id);
        $invoice->status = Invoice::STATUS_ORIGINAL;
        $invoice->paid_status = Invoice::STATUS_PAID;
        $invoice->due_amount = 0;
        $invoice->save();

        return redirect(route('invoices.index'))->with('status', 'Invoice is marked as paid.');
    }


     /**
     * Retrive a specified user's unpaid invoices from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomersUnpaidInvoices(Request $request, $id)
    {
        $invoices = Invoice::where('paid_status', '<>', Invoice::STATUS_PAID)
            ->where('customer_id', $id)->where('due_amount', '>', 0)
            ->whereCompany($request->header('company'))
            ->get();

        return response()->json([
            'invoices' => $invoices
        ]);
    }

    public function cloneInvoice(Request $request)
    {
        $oldInvoice = Invoice::with([
            'items.taxes',
            'customer',
            'invoiceTemplate',
            'taxes.taxType'
        ])
        ->find($request->id);

        $date = Carbon::now();
        $invoice_prefix = get_option('invoice_prefix', 'shop');
        if (empty($invoice_prefix)) {
            $invoice_prefix = 'INV';
        }


        $invoice = Invoice::create([
            'invoice_date' => $date,
            'due_date' => $date,
            'invoice_number' => $invoice_prefix."-".Invoice::getNextInvoiceNumber($invoice_prefix),
            'reference_number' => $oldInvoice->reference_number,
            'customer_id' => $oldInvoice->customer_id,
            'company_id' => $request->header('company'),
            'invoice_template_id' => 1,
            'status' => Invoice::STATUS_PROFORMA,
            'paid_status' => Invoice::STATUS_UNPAID,
            'sub_total' => $oldInvoice->sub_total,
            'discount' => $oldInvoice->discount,
            'discount_type' => $oldInvoice->discount_type,
            'discount_val' => $oldInvoice->discount_val,
            'total' => $oldInvoice->total,
            'due_amount' => $oldInvoice->total,
            'tax_per_item' => $oldInvoice->tax_per_item,
            'discount_per_item' => $oldInvoice->discount_per_item,
            'tax' => $oldInvoice->tax,
            'notes' => $oldInvoice->notes,
            'unique_hash' => str_random(60)
        ]);

        $invoiceItems = $oldInvoice->items->toArray();

        foreach ($invoiceItems as $invoiceItem) {
            $invoiceItem['company_id'] = $request->header('company');
            $invoiceItem['name'] = $invoiceItem['name'];
            $item = $invoice->items()->create($invoiceItem);

            if (array_key_exists('taxes', $invoiceItem) && $invoiceItem['taxes']) {
                foreach ($invoiceItem['taxes'] as $tax) {
                    $tax['company_id'] = $request->header('company');

                    if ($tax['amount']) {
                        $item->taxes()->create($tax);
                    }
                }
            }
        }

        if ($oldInvoice->taxes) {
            foreach ($oldInvoice->taxes->toArray() as $tax) {
                $tax['company_id'] = $request->header('company');
                $invoice->taxes()->create($tax);
            }
        }

        return redirect(route('invoices.edit', $invoice->id))->with('status', 'Invoice is cloned.');
    }
}

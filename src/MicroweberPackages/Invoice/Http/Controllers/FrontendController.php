<?php
namespace MicroweberPackages\Invoice\Http\Controllers;

use MicroweberPackages\App\Http\Controllers\Controller;
use MicroweberPackages\Currency\Currency;
use MicroweberPackages\Invoice\Invoice;
use PDF;
use MicroweberPackages\Invoice\CompanySetting;
use MicroweberPackages\Invoice\Estimate;
use MicroweberPackages\Invoice\Payment;
use MicroweberPackages\User\User;
use MicroweberPackages\Invoice\Company;
use MicroweberPackages\Invoice\InvoiceTemplate;
use MicroweberPackages\Invoice\EstimateTemplate;
use MicroweberPackages\Invoice\Mail\EstimateViewed;
use MicroweberPackages\Invoice\Mail\InvoiceViewed;

class FrontendController extends Controller
{
    public function getCustomerEstimatePdf($id)
    {
        $estimate = Estimate::with(
                'user',
                'items',
                'user.billingAddress',
                'user.shippingAddress'
            )
            ->where('unique_hash', $id)
            ->first();

        $taxTypes = [];
        $taxes = [];
        $labels = [];

        if ($estimate->tax_per_item === 'YES') {
            foreach ($estimate->items as $item) {
                foreach ($item->taxes as $tax) {
                    if (!in_array($tax->name, $taxTypes)) {
                        array_push($taxTypes, $tax->name);
                        array_push($labels, $tax->name.' ('.$tax->percent.'%)');
                    }
                }
            }

            foreach ($taxTypes as $taxType) {
                $total = 0;

                foreach ($estimate->items as $item) {
                    foreach ($item->taxes as $tax) {
                        if($tax->name == $taxType) {
                            $total += $tax->amount;
                        }
                    }
                }

                array_push($taxes, $total);
            }
        }

        $estimateTemplate = EstimateTemplate::find($estimate->estimate_template_id);

        $company = Company::find($estimate->company_id);
        if ($company) {
            $logo = $company->getMedia('logo')->first();
            if ($logo) {
                $logo = $logo->getFullUrl();
            }
        }

        if ($estimate && ($estimate->status == Estimate::STATUS_SENT || $estimate->status == Estimate::STATUS_DRAFT)) {
            $estimate->status = Estimate::STATUS_VIEWED;
            $estimate->save();
            $notifyEstimateViewed = CompanySetting::getSetting(
                'notify_estimate_viewed',
                $estimate->company_id
            );

            if ($notifyEstimateViewed == 'YES') {
                $data['estimate'] = Estimate::findOrFail($estimate->id)->toArray();
                $data['user'] = User::find($estimate->user_id)->toArray();
                $notificationEmail = CompanySetting::getSetting(
                    'notification_email',
                    $estimate->company_id
                );

                \Mail::to($notificationEmail)->send(new EstimateViewed($data));
            }
        }

        $companyAddress = User::with(['addresses', 'addresses.country'])->find(1);

        $colors = [
            'invoice_primary_color',
            'invoice_column_heading',
            'invoice_field_label',
            'invoice_field_value',
            'invoice_body_text',
            'invoice_description_text',
            'invoice_border_color'
        ];
        $colorSettings = CompanySetting::whereIn('option', $colors)
            ->whereCompany($estimate->company_id)
            ->get();

        view()->share([
            'estimate' => $estimate,
            'logo' => $logo ?? null,
            'company_address' => $companyAddress,
            'colors' => $colorSettings,
            'labels' => $labels,
            'taxes' => $taxes
        ]);
        $pdf = PDF::loadView('invoice::app.pdf.estimate.'.$estimateTemplate->view);

        return $pdf->stream();
    }


    /**
    *
     * @return \Illuminate\Http\Response
     */
    public function getCustomerInvoicePdf($id)
    {
        $invoice = Invoice::with([
                'items',
                'items.taxes',
                'user',
                'invoiceTemplate',
                'taxes'
            ])
            ->where('unique_hash', $id)
            ->first();

        $taxTypes = [];
        $taxes = [];
        $labels = [];

        if ($invoice->tax_per_item === 'YES') {
            foreach ($invoice->items as $item) {
                foreach ($item->taxes as $tax) {
                    if (!in_array($tax->name, $labels)) {
                        array_push($taxTypes, $tax->name);
                        array_push($labels, $tax->name.' ('.$tax->percent.'%)');
                    }
                }
            }

            foreach ($taxTypes as $taxType) {
                $total = 0;

                foreach ($invoice->items as $item) {
                    foreach ($item->taxes as $tax) {
                        if($tax->name == $taxType) {
                            $total += $tax->amount;
                        }
                    }
                }

                array_push($taxes, $total);
            }
        }

        $invoiceTemplate = InvoiceTemplate::find($invoice->invoice_template_id);

        $company = Company::find($invoice->company_id);
        $logo = $company->getMedia('logo')->first();

        if($logo) {
            $logo = $logo->getFullUrl();
        }

        if ($invoice && ($invoice->status == Invoice::STATUS_SENT || $invoice->status == Invoice::STATUS_DRAFT)) {
            $invoice->status = Invoice::STATUS_VIEWED;
            $invoice->viewed = true;
            $invoice->save();
            $notifyInvoiceViewed = CompanySetting::getSetting(
                'notify_invoice_viewed',
                $invoice->company_id
            );

            if ($notifyInvoiceViewed == 'YES') {
                $data['invoice'] = Invoice::findOrFail($invoice->id)->toArray();
                $data['user'] = User::find($invoice->user_id)->toArray();
                $notificationEmail = CompanySetting::getSetting(
                    'notification_email',
                    $invoice->company_id
                );

                \Mail::to($notificationEmail)->send(new InvoiceViewed($data));
            }
        }

        $companyAddress = User::with(['addresses', 'addresses.country'])->find(1);

        $colors = [
            'invoice_primary_color',
            'invoice_column_heading',
            'invoice_field_label',
            'invoice_field_value',
            'invoice_body_text',
            'invoice_description_text',
            'invoice_border_color'
        ];
        $colorSettings = CompanySetting::whereIn('option', $colors)
            ->whereCompany($invoice->company_id)
            ->get();

        view()->share([
            'invoice' => $invoice,
            'colors' => $colorSettings,
            'company_address' => $companyAddress,
            'logo' => $logo ?? null,
            'labels' => $labels,
            'taxes' => $taxes
        ]);
        $pdf = PDF::loadView('invoice::app.pdf.invoice.'.$invoiceTemplate->view);

        return $pdf->stream();
    }

    public function getEstimatePdf($id)
    {
        $estimate = Estimate::with([
                'items',
                'items.taxes',
                'user',
                'estimateTemplate',
                'taxes',
                'taxes.taxType'
            ])
            ->where('unique_hash', $id)
            ->first();

        $taxTypes = [];
        $taxes = [];
        $labels = [];

        if ($estimate->tax_per_item === 'YES') {
            foreach ($estimate->items as $item) {
                foreach ($item->taxes as $tax) {
                    if (!in_array($tax->name, $taxTypes)) {
                        array_push($taxTypes, $tax->name);
                        array_push($labels, $tax->name.' ('.$tax->percent.'%)');
                    }
                }
            }

            foreach ($taxTypes as $taxType) {
                $total = 0;

                foreach ($estimate->items as $item) {
                    foreach ($item->taxes as $tax) {
                        if($tax->name == $taxType) {
                            $total += $tax->amount;
                        }
                    }
                }

                array_push($taxes, $total);
            }
        }

        $estimateTemplate = EstimateTemplate::find($estimate->estimate_template_id);

        $company = Company::find($estimate->company_id);
        $companyAddress = User::with(['addresses', 'addresses.country'])->find(1);
        $logo = $company->getMedia('logo')->first();

        if($logo) {
            $logo = $logo->getFullUrl();
        }

        $colors = [
            'invoice_primary_color',
            'invoice_column_heading',
            'invoice_field_label',
            'invoice_field_value',
            'invoice_body_text',
            'invoice_description_text',
            'invoice_border_color'
        ];
        $colorSettings = CompanySetting::whereIn('option', $colors)
            ->whereCompany($estimate->company_id)
            ->get();

        view()->share([
            'estimate' => $estimate,
            'logo' => $logo ?? null,
            'company_address' => $companyAddress,
            'colors' => $colorSettings,
            'labels' => $labels,
            'taxes' => $taxes
        ]);
        $pdf = PDF::loadView('invoice::app.pdf.estimate.'.$estimateTemplate->view);

        return $pdf->stream();
    }

    public function getInvoicePdf($id)
    {
        $invoice = Invoice::with([
                'items',
                'items.taxes',
                'customer',
                'invoiceTemplate',
                'taxes'
            ])
            ->where('unique_hash', $id)
            ->first();

        $taxTypes = [];
        $taxes = [];
        $labels = [];

        if ($invoice->tax_per_item === 'YES') {
            foreach ($invoice->items as $item) {
                foreach ($item->taxes as $tax) {
                    if (!in_array($tax->name, $taxTypes)) {
                        array_push($taxTypes, $tax->name);
                        array_push($labels, $tax->name.' ('.$tax->percent.'%)');
                    }
                }
            }

            foreach ($taxTypes as $taxType) {
                $total = 0;

                foreach ($invoice->items as $item) {
                    foreach ($item->taxes as $tax) {
                        if($tax->name == $taxType) {
                            $total += $tax->amount;
                        }
                    }
                }

                array_push($taxes, $total);
            }
        }

        $invoiceTemplate = InvoiceTemplate::find($invoice->invoice_template_id);
        if (!$invoiceTemplate) {
            throw new \Exception('No invoice template');
        }

        $companyAddress = [
            'addresses'=>[
                [
                    'address_street_1'=>get_option('invoice_company_address', 'shop'),
                    'address_street_2'=>get_option('invoice_company_address2', 'shop'),
                    'city'=>get_option('invoice_company_city', 'shop'),
                    'state'=>get_option('invoice_company_state', 'shop'),
                    'zip'=>get_option('invoice_company_zip', 'shop'),
                    'country'=>get_option('invoice_company_country', 'shop'),
                    'phone'=>get_option('invoice_company_phone', 'shop'),
                ]
            ]
        ];

        $logo = get_option('invoice_company_logo', 'shop');
        $name = get_option('invoice_company_name', 'shop');

        $colors = [
            'invoice_primary_color',
            'invoice_column_heading',
            'invoice_field_label',
            'invoice_field_value',
            'invoice_body_text',
            'invoice_description_text',
            'invoice_border_color'
        ];
        $colorSettings = CompanySetting::whereIn('option', $colors)
            ->whereCompany($invoice->company_id)
            ->get();

        view()->share([
            'currency'=>Currency::first(),
            'invoice' => $invoice,
            'company_address' => $companyAddress,
            'owner_company_name' => $name ?? null,
            'owner_company_logo' => $logo ?? null,
            'colors' => $colorSettings,
            'labels' => $labels,
            'taxes' => $taxes
        ]);

        $pdf = PDF::loadView('invoice::app.pdf.invoice.'.$invoiceTemplate->view);

        return $pdf->stream();
    }

    public function getPaymentPdf($id)
    {
        $payment = Payment::with([
                'user',
                'invoice',
                'paymentMethod'
            ])
            ->where('unique_hash', $id)
            ->first();

        $company = Company::find($payment->company_id);
        $companyAddress = User::with(['addresses', 'addresses.country'])->find(1);

        $logo = $company->getMedia('logo')->first();

        if($logo) {
            $logo = $logo->getFullUrl();
        }

        view()->share([
            'payment' => $payment,
            'company_address' => $companyAddress,
            'logo' => $logo ?? null
        ]);

        $pdf = PDF::loadView('invoice::app.pdf.payment.payment');

        return $pdf->stream();
    }
}

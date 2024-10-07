<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\invoices;
use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;
use App\Models\invoices_details;
use Illuminate\Support\Facades\DB;
use App\Models\invoice_attachments;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Controllers\InvoiceAttachmentsController;
use Illuminate\Support\Facades\File;


class InvoicesController extends Controller
{

    public function index()
    {


        $invoices = invoices::all();
        return view('invoices.invoices', compact('invoices'));

    }

    public function create()
    {
        $sections = sections::all();
        return view('invoices.add_invoice', compact('sections'));
    }


    public function getProducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }
    public function store(Request $request)
    {

        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = invoices::latest()->first()->id;
        invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {

            $invoice_id = Invoices::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }
        $user = User::get();
        $invoices = invoices::latest()->first();

        $notifications = [
            'title' => 'فاتورة جديدة',
            'message' => 'تم إضافة فاتورة جديدة',
            'alert-type' => 'success',
        ];

        return redirect()->route('invoices.index')->with($notifications);


    }

    public function edit($id)
    {
        $invoices = invoices::find($id);
        $sections = sections::all();
        return view('invoices.edit_invoice', compact('invoices', 'sections'));
    }

    public function update(Request $request, $id)
    {
        $invoices = invoices::findOrFail($request->invoice_id);

        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);

        $notifications = [
            'title' => 'تعديل فاتورة',
            'message' => 'تم تعديل فاتورة',
            'alert-type' => 'success',
        ];

        return redirect()->route('invoices.index')->with($notifications);
    }



    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoice = invoices::where('id', $id)->first();
        $Details = invoice_attachments::where('invoice_id', $id)->first();

        $id_page = $request->id_page;

        if ($id_page != 2) {
            if (!empty($Details->invoice_number)) {
                $directoryPath = public_path('Attachments/' . $Details->invoice_number);

                if (File::exists($directoryPath)) {
                    File::deleteDirectory($directoryPath);
                }
            }

            $invoice->forceDelete();
            $notifications = [
                'title' => 'حذف فاتورة',
                'message' => 'تم حذف فاتورة',
                'alert-type' => 'success',
            ];
            return redirect()->route('invoices.index')->with($notifications);
        } else {
            $invoice->delete();
            $notifications = [
                'title' => 'أرشفة فاتورة',
                'message' => 'تم أرشفة فاتورة',
                'alert-type' => 'success',
            ];
            return redirect()->route('invoices.index')->with($notifications);
        }
    }

    public function showstatus($id)
    {
        $invoices = invoices::where('id', $id)->first();
        return view('invoices.status_update', compact('invoices'));
    }

    public function editstatus($id, Request $request)
    {
        $invoices = invoices::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        } else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        $notifications = [
            'title' => 'تحديث حالة الفاتورة',
            'message' => 'تم تحديث حالة الفاتورة بنجاح',
            'alert-type' => 'success',
        ];
        return redirect()->route('invoices.index')->with($notifications);
    }

    public function Invoice_Paid()
    {
        $invoices = Invoices::where('Value_Status', 1)->get();
        return view('invoices.invoices_paid',compact('invoices'));
    }

    public function Invoice_unPaid()
    {
        $invoices = Invoices::where('Value_Status',2)->get();
        return view('invoices.invoices_unpaid',compact('invoices'));
    }

    public function Invoice_Partial()
    {
        $invoices = Invoices::where('Value_Status',3)->get();
        return view('invoices.invoices_Partial',compact('invoices'));
    }




}

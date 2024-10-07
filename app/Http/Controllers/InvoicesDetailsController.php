<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;
use App\Models\invoices_details;
use Illuminate\Routing\Controller;
use App\Models\invoice_attachments;
use Illuminate\Support\Facades\File;

class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices = invoices::where('id', $id)->first();
        $details = invoices_Details::where('id_Invoice', $id)->get();
        $attachments = invoice_attachments::where('invoice_id', $id)->get();

        return view('invoices.details_invoice', compact('invoices', 'details', 'attachments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $attachment = invoice_attachments::findOrFail($request->id_file);

        $filePath = public_path('Attachments/' . $request->invoice_number . '/' . $attachment->file_name);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $directoryPath = public_path('Attachments/' . $request->invoice_number);

        if (File::isDirectory($directoryPath) && count(File::files($directoryPath)) === 0) {
            File::deleteDirectory($directoryPath);
        }

        $attachment->delete();

        $notifications = [
            'message' => 'تم حذف الملف بنجاح',
            'alert-type' => 'success'
        ];

        return back()->with($notifications);
    }

    public function viewFile($invoice_number, $file_name)
    {
        $path = public_path('Attachments/' . $invoice_number . '/' . $file_name);

        if (file_exists($path)) {
            return response()->file($path);
        }

        return response()->json(['error' => 'File not found'], 404);
    }



}

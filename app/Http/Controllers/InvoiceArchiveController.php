<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;

class InvoiceArchiveController extends Controller
{
    public function index()
    {
        $invoices = invoices::onlyTrashed()->get();
        return view('invoices.Archive_Invoices', compact('invoices'));
    }


  public function update(Request $request)
  {
       $id = $request->invoice_id;
       $flight = Invoices::withTrashed()->where('id', $id)->restore();
       session()->flash('restore_invoice');
       $notifications = [
        'title' => 'Invoice Restored',
        'message' => 'تم أرشفة الفاتورة بنجاح',
        'type-alert' => 'success'
       ];
       return redirect()->route('invoices.index')->with($notifications);
  }

  public function destroy(Request $request)
    {
         $invoices = invoices::withTrashed()->where('id',$request->invoice_id)->first();
         $invoices->forceDelete();
         session()->flash('delete_invoice');
         $notifications = [
            'title' => 'Invoice Deleted',
            'message' => 'تم حذف الفاتورة بنجاح',
            'type-alert' => 'success'
         ];
         return redirect()->route('archive.index')->with($notifications);

    }

}

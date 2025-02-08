<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CsvController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $filePath = $request->file('csv_file')->store('uploads');

        //check if file exists and readable
        if (!file_exists(storage_path('app/' . $filePath)) || !is_readable(storage_path('app/' . $filePath))) {
            return response()->json(['message' => 'Invalid file path'], 400);
        }

        $header = null;
        $data = array();
        //read csv file
        if (($handle = fopen(storage_path('app/' . $filePath), 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        //inset data into database
        foreach ($data as $row) {
            $customer = Customer::firstOrCreate(
                ['email' => $row['customer_email']],
                ['name' => $row['customer_name']]
            );

            $order = Order::firstOrCreate(
                ['id' => $row['order_id']],
                ['customer_id' => $customer->id, 'order_date' => $row['order_date']]
            );

            $product = Product::firstOrCreate(
                ['name' => $row['product_name']],
                ['price' => $row['product_price']]
            );

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $row['quantity'],
            ]);
        }


        return response()->json([
            'message' => 'CSV Uploaded successfully',
        ]);
    }

    public function generatePdf()
    {
        //get orders with customer and order items
        $orders = Order::with('customer', 'orderItems.product')->get();

        //genrate view file
        $pdf = Pdf::loadView('orders_pdf', compact('orders'))->setPaper('a4', 'landscape');

        $fileName = 'orders_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        Storage::put('public/reports/' . $fileName, $pdf->output());

        //return pdf url
        $publicPath = Storage::url('public/reports/' . $fileName);
        $fullUrl = url($publicPath);
        return response()->json([
            'message' => 'PDF generated successfully',
            'pdf_url' => $fullUrl,
        ]);
    }
}

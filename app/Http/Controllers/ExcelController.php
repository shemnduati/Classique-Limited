<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Spatie\SimpleExcel\SimpleExcelReader;
//use App\Sorter\ProductsSorting;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;


class ExcelController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
 
        try {
            $file = $request->file('file');

            $file = $request->file('file');

            $import = new ProductsImport();
            Excel::import($import, $file);
    
            $assignedProducts = $import->getAssignedProducts();

            return view('assigned-products', compact('assignedProducts'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred during file processing. Please check your file and try again.']);
        }
    }
}

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
 

            $file = $request->file('file');

            $import = new ProductsImport();
            Excel::import($import, $file);
    
            //$assignedProducts = $import->getAssignedProducts();
            return redirect()->route('products')->with('success', 'Products uploaded and assigned successfully.');



    }
}

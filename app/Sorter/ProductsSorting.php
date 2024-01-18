<?php

namespace App\Sorter;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ProductsImport implements ToCollection
{
    protected $assignedProducts = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $productName = $row['Product Name'];
            $quantity = $row['Quantity'];

            // Assign products based on rules
            if (stripos($productName, 'Apples') !== false) {
                $this->assignedProducts['User A'][] = ['name' => $productName, 'quantity' => $quantity];
            } elseif (stripos($productName, 'Oranges') !== false) {
                $this->assignedProducts['User B'][] = ['name' => $productName, 'quantity' => $quantity];
            } elseif (stripos($productName, 'Mangoes') !== false) {
                $this->assignedProducts['User C'][] = ['name' => $productName, 'quantity' => $quantity];
            }
        }
    }

    public function getAssignedProducts()
    {
        return $this->assignedProducts;
    }
}
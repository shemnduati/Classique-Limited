<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ProductsImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $assignedProducts = [
        'User A' => [],
        'User B' => [],
        'User C' => [],
    ];

    public function collection(Collection $rows)
    {
        // Skip the first row (headers row)
        $rows = $rows->slice(1);

        foreach ($rows as $row) {
            // Check if the keys exist before accessing them
            $productName = isset($row[0]) ? $row[0] : null;
            $quantity = isset($row[1]) ? $row[1] : null;

            // Your existing code for product assignment
            $user = $this->assignUser($productName);
            if ($user !== null) {
                $this->assignedProducts[$user][] = [
                    'name' => $productName,
                    'quantity' => $quantity,
                ];
            }
        }
    }

    public function getAssignedProducts()
    {
        return $this->assignedProducts;
    }

    protected function assignUser($productName)
    {
        if (stripos($productName, 'Apples') !== false) {
            return 'User A';
        } elseif (stripos($productName, 'Oranges') !== false) {
            return 'User B';
        } elseif (stripos($productName, 'Mangoes') !== false) {
            return 'User C';
        }

        // Product does not match any rule
        return null;
    }
}

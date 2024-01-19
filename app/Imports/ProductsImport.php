<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Product;

class ProductsImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   

    public function collection(Collection $rows)
    {
        // Get all users from the database
        $users = User::all();

        // Skip the first row (headers row)
        $rows = $rows->slice(1);

        // Create an associative array to keep track of assigned product codes and their total quantities
        $assignedProducts = [];

        foreach ($rows as $row) {
            // Get the product code and quantity from the current row
            $code = $row[0];
            $quantity = $row[1];

            // Check if the product code has already been assigned
            if (array_key_exists($code, $assignedProducts)) {
                // If assigned, update the total quantity for the first assigned user
                $user = User::find($assignedProducts[$code]['user_id']);
                if ($user) {
                    // Update the total quantity for the existing product
                    $user->products()->where('code', $code)->update([
                        'quantity' => $assignedProducts[$code]['total_quantity'] + $quantity,
                    ]);

                    // Update the total quantity in the assignedProducts array
                    $assignedProducts[$code]['total_quantity'] += $quantity;
                }
            } else {
                // If not assigned, find a user for assignment
                $user = $this->getUserForProductCode($code, $users, $assignedProducts);

                // Assign products based on rules
                if ($user) {
                    // Assign products based on rules
                    $product = $user->products()->create([
                        'code' => $code,
                        'quantity' => $quantity,
                    ]);

                    // Update the assignedProducts array with the total quantity
                    $assignedProducts[$code] = [
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'total_quantity' => $quantity,
                    ];

                    // Remove the user from the available users array to ensure they get only one code
                    $users = $users->reject(function ($availableUser) use ($user) {
                        return $availableUser->id == $user->id;
                    });
                } else {
                    // If no user is found for the current code, skip processing this row or handle the error as needed
                    continue;
                }
            }
        }
    }

    private function getUserForProductCode($code, $users, $lastAssignedCode)
    {
        
        // Iterate through users to find the one whose last assigned code is different from the current code
        foreach ($users as $user) {
            
            if (!isset($lastAssignedCode[$user->id]) || $lastAssignedCode[$user->id] !== $code) {
              
                return $user;
            }
        }

        // If no user is found, return null
        return null;
    }
}

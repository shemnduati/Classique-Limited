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
    
        // Create an associative array to keep track of assigned product codes and their quantities
        $assignedProducts = [];
    
        foreach ($rows as $row) {
            // Get the product code and quantity from the current row
            $code = $row[0];
            $quantity = $row[1];
    
            // Check if the product code has already been assigned
            if (array_key_exists($code, $assignedProducts)) {
                // If assigned, update the quantity for the first assigned user
                $user = User::find($assignedProducts[$code]['user_id']);
                if ($user) {
                    // Update the quantity for the existing product
                    $user->products()->where('code', $code)->update(['quantity' => $quantity]);
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
    
                    // Update the assigned products array
                    $assignedProducts[$code] = [
                        'user_id' => $user->id,
                        'product_id' => $product->id,
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
private function getUserWithCode($users, $code, $usersWithCode)
{
    // Check if any user already has the given code
    foreach ($users as $user) {
        if ($this->userHasCode($usersWithCode, $user->id)) {
            continue;
        }

        // Check if the user has the given code assigned
        if ($user->products->where('code', $code)->isEmpty()) {
            return $user;
        }
    }

    return null;
}


    private function isCodeAssigned($code, $lastAssignedCode)
    {
        // Check if the code is already assigned to any user
        return in_array($code, $lastAssignedCode);
    }

    private function userHasCode($usersWithCode, $userId)
    {
        // Check if the user already has a code assigned
        return in_array($userId, $usersWithCode);
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

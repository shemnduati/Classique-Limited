@extends('main')


@section('content')
<h1>Assigned Products</h1>

<table border="1">
    <thead>
        <tr>
            <th>User</th>
            <th>Product Name</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($assignedProducts as $user => $products)
            @foreach ($products as $product)
                <tr>
                    <td>{{ $user }}</td>
                    <td>{{ $product['name'] }}</td>
                    <td>{{ $product['quantity'] }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endsection
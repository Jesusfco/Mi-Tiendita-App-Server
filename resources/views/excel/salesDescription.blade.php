<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Vendedor</th>
        <th>Total</th>        
        <th>Fecha/Hora</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sales as $sale)
        <tr>
            <td>Venta #{{ $sale->id }}</td>
            <td>{{ $sale->user_name }}</td>
            <td>{{ $sale->total }}</td>
            <td>{{ $sale->created_at }}</td>
        </tr>

        <tr>
            <td></td>
            <td><h4>Descripcion</h4></td>
        </tr>

        <tr>
            <td></td>
            <td>Producto</td>
            <td>Precio</td>
            <td>Cantidad</td>
            <td>Subtotal</td>
        </tr>

        

        @foreach($sale->description as $d)

        <tr>
            <td></td>
            <td>{{ $d->product_name }}</td>
            <td>{{ $d->price }}</td>
            <td>{{ $d->quantity }}</td>
            <td>{{ $d->subtotal }}</td>
        </tr>


        @endforeach

    @endforeach
    </tbody>
</table
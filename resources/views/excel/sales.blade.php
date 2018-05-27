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
            <td>{{ $sale->id }}</td>
            <td>{{ $sale->user_id }}</td>
            <td>{{ $sale->total }}</td>
            <td>{{ $sale->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table
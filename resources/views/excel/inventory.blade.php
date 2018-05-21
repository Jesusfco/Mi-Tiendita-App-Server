<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Producto</th>
        <th>Codigo de Barras</th>        
        <th>Precio</th>
        <th>Precio/C</th>
        <th>Punto de reorden</th>
        <th>Stock</th>
        <th>Departamento</th>
        <th>Alta</th>
        <th>Actualizado</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $pro)
        <tr>
            <td>{{ $pro->id }}</td>
            <td>{{ $pro->name }}</td>
            <td>{{ $pro->code }}</td>
            <td>{{ $pro->price }}</td>
            <td>{{ $pro->cost_price }}</td>
            <td>{{ $pro->reorder }}</td>
            <td>{{ $pro->stock }}</td>
            <td>{{ $pro->department }}</td>
            <td>{{ $pro->created_at }}</td>
            <td>{{ $pro->updated_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table
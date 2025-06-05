@props(['columns'])

<thead>
    <tr>
        @foreach($columns as $column)
            <th scope="col">
                {{ $column }}
            </th>
        @endforeach
    </tr>
</thead>

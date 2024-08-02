<table> 
    <tbody>   
        <tr>
            <th>Autor</th>
            <th>Titulo</th>
        </tr>
        @foreach($data as $object)    
            <tr>
                <th>{{$object->user->name}}</th> 
                <th>{{$object->title}}</th> 
            </tr>
        @endforeach
    </tbody>
</table>
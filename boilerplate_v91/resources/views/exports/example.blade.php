<table> 
    <tbody>   
        <tr>
            <th>Autor</th>
            <th>Titulo</th>
            <th>Conteudo</th>
        </tr>
        <tr>
            @foreach($data as $object)    
                <th>{{$object->user->name}}</th> 
                <th>{{$object->title}}</th> 
                <th>{{$object->content}}</th> 
            @endforeach
        </tr>
    </tbody>
</table>
<table>
    <thead> 
        <tr>
            <th style="background-color: #ff9029;" colspan="9"><img src="assets/logo.png"/></th> 
        </tr>
    </thead>  
    <tbody>    
        <tr>
            <th style="background-color: #ef7b26; color: #9d0d34; width: 30px;" colspan="9">Example</th> 
        </tr>
        <tr>
            <td colspan="9">{{ $obj->nome }}</td> 
        </tr>  
        <tr>
            <th colspan="2" style="text-align: left; background-color: #ef7b26; color: #9d0d34;">Nome</th>
            <th colspan="2" style="text-align: right; background-color: #ef7b26; color: #9d0d34;">Data 1</th>
            <th colspan="2" style="text-align: right; background-color: #ef7b26; color: #9d0d34;">Data 2</th> 
            <th colspan="3" style="text-align: right; background-color: #ef7b26; color: #9d0d34;">Data 3</th> 
        </tr> 
        @foreach($dados as $item)
            <tr>
                <td colspan="2" style="text-align: left;">{{ $item->nome }}</td> 
                <td colspan="2" style="text-align: right;">{{ $item->nome }}</td>
                <td colspan="2" style="text-align: right;">{{ $item->nome }}</td> 
                <td colspan="3" style="text-align: right;">{{ $item->nome }}</td> 
            </tr>
        @endforeach
    </tbody>
</table>
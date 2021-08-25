<?php
  //recuperar arquivo e gerar destino
  //getcwd - exibe path relativo do server
  
  $arquivo = getcwd().'/zipname.zip';
  $destino = getcwd().'/';
  
  $zip = new ZipArchive;
  $zip->open($arquivo);
  if($zip->extractTo($destino) == TRUE){
    echo 'Arquivo descompactado com sucesso.';
  }else{
    echo 'O Arquivo não pode ser descompactado.';
  }
  $zip->close();
?>

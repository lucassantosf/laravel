<?php

$streamList  = [
    fopen('file1.txt','r'),
    fopen('file2.txt','r'),
];

foreach($streamList as $stream)
{
    stream_set_blocking($stream,false);
}

echo fget($streamList[0]);
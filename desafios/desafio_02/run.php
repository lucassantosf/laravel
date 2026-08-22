<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = 'Desafio02\\';
    $baseDir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use Desafio02\Exceptions\VeiculoIndisponivelException;
use Desafio02\Locadora;
use Desafio02\Carro;
use Desafio02\Moto;

$locadora = new Locadora();
$carro1 = new Carro("Gol","ABC-1234",100,4);
$moto1 = new Moto("CB 500","XYZ-9999",80,500);

$locadora->adicionarVeiculo($carro1);
$locadora->adicionarVeiculo($moto1);

$busca = $locadora->buscarPorPlaca("ABC-1234");
$aluguel = $carro1->calcularValorAluguel(3);

if(!empty($busca))
    echo "A busca retornou o veiculo de modelo {$busca->getModelo()}";

echo PHP_EOL."O total do aluguel para 3 dias seria R$ : ".$aluguel;

$carro1->alugar();

echo PHP_EOL."Carro foi alugado com sucesso";

echo PHP_EOL."Listando os carros disponiveis";

$disponiveis = $locadora->listarDisponiveis();

foreach($disponiveis as $disponivel){
    echo PHP_EOL." Modelo: {$disponivel->getModelo()} , Diaria : R$ {$disponivel->getPrecoDiaria()}";

}

try {
    
    $carro1->alugar();

} catch (VeiculoIndisponivelException $e) {
    echo PHP_EOL."Erro capturado com sucesso !!! {$e->getMessage()}";
}


echo PHP_EOL."Script finalizado !!! ";
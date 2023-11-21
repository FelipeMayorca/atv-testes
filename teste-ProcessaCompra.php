<?php

use Loja\WebIII\Model\Carrinho;
use Loja\WebIII\Model\Produto;
use Loja\WebIII\Model\Usuario;
use Loja\WebIII\Service\ProcessaCompra;

require 'vendor/autoload.php';

// Arrange - Given
$maria = new Usuario('Maria');

$carrinho = new Carrinho($maria);

$carrinho->adicionaProduto(new Produto('Geladeira', 1500));
$carrinho->adicionaProduto(new Produto('Cooktop', 600));
$carrinho->adicionaProduto(new Produto('Forno Eletrico', 4500));

$compra = new ProcessaCompra();

// Act - When
$compra->finalizaCompra($carrinho);

$totalDaCompra = $compra->getTotalDaCompra();

// Assert - Then
$totalEsperado = 6600;

echo PHP_EOL;
if($totalDaCompra == $totalEsperado){
    echo "TESTE PASSOU".PHP_EOL;
}
else{
    echo "TESTE FALHOU".PHP_EOL;
}
<?php

namespace Loja\WebIII\Service;

use Loja\WebIII\Model\Carrinho;
use Loja\WebIII\Model\Produto;

class ProcessaCompra 
{
    /** @var Carrinho */
    private $carrinho;
    /** @var int */
    private $totalDeProdutos;
    /** @var float */
    private $totalDaCompra;
    

    public function __construct()
    {
        $this->totalDaCompra = 0;
        $this->totalDeProdutos = 0;
    }

    public function finalizaCompra(Carrinho $carrinho)
    {
    $this->carrinho = $carrinho;
    $produtos = $this->carrinho->getProdutos();

    if(empty($produtos)) {
       throw new \Exception('Sem produtos no carrinho.');
    }

    // Inicializa $menorValor e $maiorValor com o valor do primeiro produto
    else if (!empty($produtos)) {
        $primeiroProduto = reset($produtos);
        $this->menorValor = $this->maiorValor = $primeiroProduto->getValor();
    }

    foreach ($produtos as $produto) {
        $this->totalDaCompra += $produto->getValor();
        $this->totalDeProdutos++;

        if ($produto->getValor() > $this->maiorValor) {
            $this->maiorValor = $produto->getValor();
        } elseif ($produto->getValor() < $this->menorValor) {
            $this->menorValor = $produto->getValor();
        }

        }

        if(count($produtos) === 1) {
            throw new \Exception('O carrinho não pode ser finalizado com apenas um produto.');
        }

        else if(count($produtos) >= 11) {
            throw new \Exception('O carrinho não pode ter mais de 10 produtos.');
        } 

        if($this->totalDaCompra > 50000) {
            throw new \Exception('Suportamos compras de até 50.000');
        }
       
    }

    public function getTotalDaCompra(): float
    {
        return $this->totalDaCompra;
    }

    public function getTotalDeProdutos(): int
    {
            return $this->totalDeProdutos;
    }

    public function getProdutoDeMaiorValor(): float
    {
            return $this->maiorValor;
    }

    public function getProdutoDeMenorValor(): float
    {
            return $this->menorValor;
    }

}
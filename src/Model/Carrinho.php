<?php

namespace Loja\WebIII\Model;

class Carrinho
{
    /** @var Produto[] */
    private $produtos;
    /** @var Usuario */
    private $usuario;
    /** @var int */
    private $qtdDeProdutos;
    /** @var float */
    private $valorTotalProdutos;

    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
        $this->produtos = [];
        $this->qtdDeProdutos = 0;
        $this->valorTotalProdutos = 0;
    }

    public function adicionaProduto(Produto $produto)
    {
        $this->produtos[] = $produto;
        $this->qtdDeProdutos = $this->qtdDeProdutos + 1;
        $this->valorTotalProdutos = $this->valorTotalProdutos + $produto->getValor();
    }

    public function removeProduto(Produto $produto)
    {
        $index = array_search($produto, $this->produtos, true);

        if($index !== false || $index === 0) {
            unset($this->produtos[$index]);
            $this->atualizaValores();
        }
    }

    private function atualizaValores()
    {
        $this->qtdDeProdutos = count($this->produtos);
        $this->valorTotalProdutos = 0;

        foreach ($this->produtos as $produto) {
            $this->valorTotalProdutos += $produto->getValor();
        }
    }

    /**
     * @param string $order Direção da ordenação ('asc' ou 'desc').
     * @return Produto[]
     */
    private function ordenarProdutos(string $order): array
    {
        $produtos = $this->produtos;
    
        usort($produtos, function ($produtoA, $produtoB) use ($order) {
            $valorA = $produtoA->getValor();
            $valorB = $produtoB->getValor();
    
            return ($order === 'asc') ? $valorA <=> $valorB : $valorB <=> $valorA;
        });
    
        return $produtos;
    }

    /**
     * @return Produto[]
     */
    public function getProdutos(): array
    {
        return $this->produtos;
    }

    /**
     * @return Produto[]
     */
    public function getProdutosMaisCaros(): array
    {
        $produtos = $this->ordenarProdutos('desc');
        return array_slice($produtos, 0, 3);
    }

    /**
     * @return Produto[]
     */
    public function getProdutosMaisBaratos(): array
    {
        $produtos = $this->ordenarProdutos('asc');
        return array_slice($produtos, 0, 3);
    }

    public function getTotalDeProdutos(): int
    {
        return $this->qtdDeProdutos;
        //return count($this->produtos);
    }

    public function getValorTotalProdutos(): float
    {
        return $this->valorTotalProdutos;
    }   
}

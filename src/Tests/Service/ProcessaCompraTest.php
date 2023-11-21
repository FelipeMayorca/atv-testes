<?php

namespace Loja\WebIII\Tests\Service;

use Loja\WebIII\Model\Carrinho;
use Loja\WebIII\Model\Produto;
use Loja\WebIII\Model\Usuario;
use Loja\WebIII\Service\ProcessaCompra;
use PHPUnit\Framework\TestCase;

class ProcessaCompraTest extends TestCase
{
    private $compra;

    public function setUp(): void
    {
            $this->compra = new ProcessaCompra();
    }

    public function carrinhoComProdutos()
    {
    // Arrange - Given - Prepara o cenario de teste
    $maria = new Usuario('Maria');
    $joao = new Usuario('Joao');
    $pedro = new Usuario('Pedro');
    
    $carrinhoOrdemCrescente = new Carrinho($maria);
    $carrinhoOrdemCrescente->adicionaProduto(new Produto('Cooktop', 600));
    $carrinhoOrdemCrescente->adicionaProduto(new Produto('Geladeira', 1000));
    $carrinhoOrdemCrescente->adicionaProduto(new Produto('Forno Eletrico', 2500));
    $carrinhoOrdemCrescente->adicionaProduto(new Produto('Fogao', 3000));
    $carrinhoOrdemCrescente->adicionaProduto(new Produto('Pia', 4500));
    
    $carrinhoOrdemDecrescente = new Carrinho($pedro);
    $carrinhoOrdemDecrescente->adicionaProduto(new Produto('Pia', 4500));
    $carrinhoOrdemDecrescente->adicionaProduto(new Produto('Fogao', 3000));
    $carrinhoOrdemDecrescente->adicionaProduto(new Produto('Forno Eletrico', 2500));
    $carrinhoOrdemDecrescente->adicionaProduto(new Produto('Geladeira', 1000));
    $carrinhoOrdemDecrescente->adicionaProduto(new Produto('Cooktop',600));

    $carrinhoOrdemAleatoria = new Carrinho($joao);
    $carrinhoOrdemAleatoria->adicionaProduto(new Produto('Forno Eletrico', 2500));
    $carrinhoOrdemAleatoria->adicionaProduto(new Produto('Geladeira',1000));
    $carrinhoOrdemAleatoria->adicionaProduto(new Produto('Pia',4500));
    $carrinhoOrdemAleatoria->adicionaProduto(new Produto('Cooktop',600));
    $carrinhoOrdemAleatoria->adicionaProduto(new Produto('Fogao',3000));
    
    return [
    'carrinho Aleatorio' => [$carrinhoOrdemAleatoria],
    'carrinho Crescente' => [$carrinhoOrdemCrescente],
    'carrinho Decrescente' => [$carrinhoOrdemDecrescente],
    ];
}

    /**
    * @dataProvider carrinhoComProdutos
    */
    public function testVerificaSe_OValorTotalDaCompraEASomaDosProdutosDoCarrinho_SaoIguais($carrinho)
    {
    
    // Act - When
    $this->compra->finalizaCompra($carrinho);

    $totalDaCompra = $this->compra->getTotalDaCompra();
    
    // Assert - Then
    $totalEsperado = $carrinho->getValorTotalProdutos();
    
    self::assertEquals($totalEsperado, $totalDaCompra);
    }

    /**
    * @dataProvider carrinhoComProdutos
    */
    public function
    testVerificaSe_AQuantidadeDeProdutosEmCompraECarrinho_SaoIguais($carrinho)
    {

        // Act - When - Executa o teste
        $this->compra->finalizaCompra($carrinho);

        $totalDeProdutosDaCompra = $this->compra->getTotalDeProdutos();

        // Assert - Then - Verifica-se a saida e a esperada
        $totalEsperado = $carrinho->getTotalDeProdutos();
        
        self::assertEquals($totalEsperado, $totalDeProdutosDaCompra);
    }

    public function
    testVerificaSe_OProdutoDeMaiorValorNoCarrinho_EstaCorreto()
    {
       
        $ana = new Usuario('Ana');
        $carrinho = new Carrinho($ana);
        $totalEsperado = 0;

        for ($i = 1; $i <= 3; $i++) {
            $valor = mt_rand(500, 5000);
            $produto = new Produto("Produto", $valor);
            $carrinho->adicionaProduto($produto);

            if($valor > $totalEsperado) {
                $totalEsperado = $valor;
            }
        }

        // Act - When - Executa o teste
        $this->compra->finalizaCompra($carrinho);

        $produtoDeMaiorValor = $this->compra->getProdutoDeMaiorValor();

        // Assert - Then - Verifica-se a saída e a esperada

        self::assertEquals($totalEsperado, $produtoDeMaiorValor);
    }

    public function
    testVerificaSe_OProdutoDeMenorValorNoCarrinho_EstaCorreto()
    {

        $ana = new Usuario('Ana');
        $carrinho = new Carrinho($ana);
        $totalEsperado = 5001;

        for ($i = 1; $i <= 3; $i++) {
            $valor = mt_rand(500, 5000);
            $produto = new Produto("Produto", $valor);
            $carrinho->adicionaProduto($produto);

            if($valor < $totalEsperado) {
                $totalEsperado = $valor;
            }
        }

        // Act - When - Executa o teste
        $this->compra->finalizaCompra($carrinho);

        $produtoDeMenorValor = $this->compra->getProdutoDeMenorValor();

        // Assert - Then - Verifica-se a saída e a esperada

        self::assertEquals($totalEsperado, $produtoDeMenorValor);
    }

    public function
    testFinalizaCompraComUmProduto()
    {

        $ana = new Usuario('ana');
        $carrinho = new Carrinho($ana);

        $produto = new Produto('Geladeira', 1500);
        $carrinho->adicionaProduto($produto);

        $compra = new ProcessaCompra();

        try{
        $compra->finalizaCompra($carrinho);

        } catch (\Exception $exception) {

        $mensagemEsperada = 'O carrinho não pode ser finalizado com apenas um produto.';
        self::assertEquals($mensagemEsperada, $exception->getMessage());

        }
    }

    public function
    testSe_TemMaisDeDezProdutos()
    {
        $ana = new Usuario('Ana');
        $carrinho = new Carrinho($ana);

        for ($i = 1; $i <= 11; $i++) {
            $produto = new Produto("Produto", 1000);
            $carrinho->adicionaProduto($produto);
        }

        $compra = new ProcessaCompra();

        try{
            $compra->finalizaCompra($carrinho);
        } catch (\Exception $exception) {

        $mensagemEsperada = 'O carrinho não pode ter mais de 10 produtos.';
        self::assertEquals($mensagemEsperada, $exception->getMessage());
        
        }
    }

    public function
    testSe_TemMaisDeCinquentaMil()
    {
        $ana = new Usuario('Ana');
        $carrinho = new Carrinho($ana);

        $carrinho->adicionaProduto(new Produto('Produto1', 50000));
        $carrinho->adicionaProduto(new Produto('Produto2', 1));

        $compra = new ProcessaCompra();

        try{
            $compra->finalizaCompra($carrinho);
        } catch (\Exception $exception) {

            $mensagemEsperada = 'Suportamos compras de até 50.000';
            self::assertEquals($mensagemEsperada, $exception->getMessage());

        }
    }

    public function testRemoveProdutoDoCarrinho()
{
    $usuario = new Usuario('Ana');
    $carrinho = new Carrinho($usuario);

    $produto1 = new Produto('Produto 1', 100);
    $produto2 = new Produto('Produto 2', 100);

    $carrinho->adicionaProduto($produto1);
    $carrinho->adicionaProduto($produto2);

    $carrinho->removeProduto($produto1);

    $produtosNoCarrinho = $carrinho->getProdutos();

    self::assertNotEmpty($produtosNoCarrinho);
    self::assertCount(1, $produtosNoCarrinho);
    
    self::assertNotContains($produto1, $produtosNoCarrinho);

    self::assertEquals(100, $carrinho->getValorTotalProdutos());
    }
}
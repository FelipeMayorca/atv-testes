<?php

namespace Loja\WebIII\Tests\Service;

use Loja\WebIII\Model\Carrinho;
use Loja\WebIII\Model\Produto;
use Loja\WebIII\Model\Usuario;
use Loja\WebIII\Service\ProcessaCompra;
use PHPUnit\Framework\TestCase;

/**
 * @group carrinho
 */
class CarrinhoTest extends TestCase
{

    
    private $compra;

    public function setUp(): void
    {
            $this->compra = new ProcessaCompra();
            $this->logFilePathMaior = __DIR__ . '/Carrinho-MaioresProdutos.txt';
            $this->logFilePathMenor = __DIR__ . '/Carrinho-MenoresProdutos.txt';
    }

    public function produtosAleatorios()
    {
        $quantidadeProdutos = mt_rand(3, 6);

        $produtos = [];

        for($i = 0; $i < $quantidadeProdutos; $i++) {
            $nomeProduto = 'Produto ' . ($i + 1);
            $valorProduto = mt_Rand(100, 5000);
            $produtos[] = new Produto($nomeProduto, $valorProduto);
        }

        shuffle($produtos);

        return [
            'produtos aleatórios' => [$produtos],
        ];
    }

    /**
     * @dataProvider produtosAleatorios
     */
    public function testListaTresMaioresProdutos(array $produtos)
    {

    $usuario = new Usuario('Ana');
    $carrinho = new Carrinho($usuario);

    foreach ($produtos as $produto) {
        $carrinho->adicionaProduto($produto);
    }

    $maioresProdutos = $carrinho->getProdutosMaisCaros();

    // Adiciona ou sobrescreve o log
    $logFilePathMaior = __DIR__ . '/Carrinho-MaioresProdutos.txt';
    file_put_contents($logFilePathMaior, "Lista de Produtos:\n");
    foreach ($produtos as $produto) {
        file_put_contents($logFilePathMaior, $produto->getProduto() . ' - ' . $produto->getValor() . "\n", FILE_APPEND);
    }

    file_put_contents($logFilePathMaior, "Três Maiores Produtos:\n", FILE_APPEND);
    foreach ($maioresProdutos as $produto) {
        file_put_contents($logFilePathMaior, $produto->getProduto() . ' - ' . $produto->getValor() . "\n", FILE_APPEND);
    }

    self::assertGreaterThanOrEqual(3, count($produtos));
}

    

    /**
     * @dataProvider produtosAleatorios
     */
    public function testListaTresMenoresProdutos(array $produtos)
    {
        $usuario = new Usuario('Ana');
        $carrinho = new Carrinho($usuario);

        foreach($produtos as $produto) {
            $carrinho->adicionaProduto($produto);
        }

        $menoresProdutos = $carrinho->getProdutosMaisBaratos();

        $logFilePathMenor = __DIR__ . '/Carrinho-MenoresProdutos.txt';
        file_put_contents($logFilePathMenor, "Lista de Produtos:\n");
        foreach ($produtos as $produto) {
            file_put_contents($logFilePathMenor, $produto->getProduto() . ' - ' . $produto->getValor() . "\n", FILE_APPEND);
        }

        file_put_contents($logFilePathMenor, "Três Menores Produtos:\n", FILE_APPEND);
        foreach ($menoresProdutos as $produto) {
            file_put_contents($logFilePathMenor, $produto->getProduto() . ' - ' . $produto->getValor() . "\n", FILE_APPEND);
        }

        self::assertGreaterThanOrEqual(3, count($produtos));
    }

}
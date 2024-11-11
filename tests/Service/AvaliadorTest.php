<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

class AvaliadorTest extends TestCase
{

    /** @var Avaliador */
    private $leiloeiro;

    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorDeveEncontrarOMaiorValorDeLances(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        assertEquals(150000, $maiorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorDeveEncontrarOMenorValorDeLances(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        assertEquals(100000, $menorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorDeveBuscarOs3MaioresValores(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maioresLances = $this->leiloeiro->getMaioresLances();

        assertCount(3, $maioresLances);
        assertEquals(150000, $maioresLances[0]->getValor());
        assertEquals(120000, $maioresLances[1]->getValor());
        assertEquals(100000, $maioresLances[2]->getValor());

    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Não é possível avaliar leilão vazio");
        $leilao = new Leilao('Fusca');
        $this->leiloeiro->avalia($leilao);
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado');

        $leilao = new Leilao('Fusca');
        $leilao->recebeLance(new Lance(new Usuario('Halan'), 2000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }

    public function leilaoEmOrdemCrescente()
    {
        $leilao = new Leilao('Honda City 2022');

        $halan = new Usuario('Halan');
        $daniel = new Usuario('Daniel');
        $guilherme = new Usuario('Guilherme');

        $leilao->recebeLance(new Lance($halan, 100000));
        $leilao->recebeLance(new Lance($daniel, 120000));
        $leilao->recebeLance(new Lance($guilherme, 150000));

        return [
            'ordem-crescente' => [$leilao]
        ];

    }

    public function leilaoEmOrdemDecrescente()
    {
        $leilao = new Leilao('Honda City 2022');

        $halan = new Usuario('Halan');
        $daniel = new Usuario('Daniel');
        $guilherme = new Usuario('Guilherme');

        $leilao->recebeLance(new Lance($guilherme, 150000));
        $leilao->recebeLance(new Lance($daniel, 120000));
        $leilao->recebeLance(new Lance($halan, 100000));

        return [
            'ordem-decrescente' => [$leilao]
        ];

    }

    public function leilaoEmOrdemAleatoria()
    {
        $leilao = new Leilao('Honda City 2022');

        $halan = new Usuario('Halan');
        $daniel = new Usuario('Daniel');
        $guilherme = new Usuario('Guilherme');

        $leilao->recebeLance(new Lance($daniel, 120000));
        $leilao->recebeLance(new Lance($guilherme, 150000));
        $leilao->recebeLance(new Lance($halan, 100000));

        return [
            'ordem-aleatoria' => [$leilao]
        ];

    }

    public function entregaLeiloes()
    {
        return [
            [$this->leilaoEmOrdemCrescente()],
            [$this->leilaoEmOrdemDecrescente()],
            [$this->leilaoEmOrdemAleatoria()]
        ];
    }
}
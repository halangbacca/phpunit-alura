<?php

namespace Alura\Leilao\Tests\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

class LeilaoTest extends TestCase
{
    /**
     * @dataProvider geraLances
     */
    public function testLeilaoDeveReceberLances(int $qtdLances, Leilao $leilao, array $valores)
    {
        self::assertCount($qtdLances, $leilao->getLances());

        foreach ($valores as $i => $valorEsperado) {
            self::assertEquals($valorEsperado, $leilao->getLances()[$i]->getValor());
        }
    }

    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        $leilao = new Leilao('Variante');
        $guilherme = new Usuario('Guilherme');

        $leilao->recebeLance(new Lance($guilherme, 1000));
        $leilao->recebeLance(new Lance($guilherme, 1500));

        assertCount(1, $leilao->getLances());
        assertEquals(1000, $leilao->getLances()[0]->getValor());
    }

    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
        $leilao = new Leilao('Impala 67');
        $daniel = new Usuario('Daniel');
        $halan = new Usuario('Halan');

        $leilao->recebeLance(new Lance($daniel, 1000));
        $leilao->recebeLance(new Lance($halan, 1500));
        $leilao->recebeLance(new Lance($daniel, 2000));
        $leilao->recebeLance(new Lance($halan, 2500));
        $leilao->recebeLance(new Lance($daniel, 3000));
        $leilao->recebeLance(new Lance($halan, 3500));
        $leilao->recebeLance(new Lance($daniel, 4000));
        $leilao->recebeLance(new Lance($halan, 4500));
        $leilao->recebeLance(new Lance($daniel, 5000));
        $leilao->recebeLance(new Lance($halan, 5500));

        $leilao->recebeLance(new Lance($halan, 6500));

        assertCount(10, $leilao->getLances());
        assertEquals(5500, $leilao->getLances()[array_key_last($leilao->getLances())]->getValor());
    }

    public function geraLances()
    {
        $halan = new Usuario('Halan');
        $daniel = new Usuario('Daniel');

        $leilaoCom2Lances = new Leilao('Honda City 2022');
        $leilaoCom2Lances->recebeLance(new Lance($halan, 100000));
        $leilaoCom2Lances->recebeLance(new Lance($daniel, 150000));

        $leilaoCom1Lance = new Leilao('Honda Civic 2024');
        $leilaoCom1Lance->recebeLance(new Lance($halan, 200000));

        return [
            'leilao-com-2-lances' => [2, $leilaoCom2Lances, [100000, 150000]],
            'leilao-com-1-lance' => [1, $leilaoCom1Lance, [200000]],
        ];
    }

}
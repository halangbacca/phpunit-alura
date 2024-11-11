<?php

namespace Alura\Leilao\Tests\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

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

    public function testLeilaoNaoDeveReceberLancesConsecutivos()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor 2 lances consecutivos');

        $leilao = new Leilao('Variante');
        $guilherme = new Usuario('Guilherme');

        $leilao->recebeLance(new Lance($guilherme, 1000));
        $leilao->recebeLance(new Lance($guilherme, 1500));
    }

    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propror mais de 5 lances por leilão');

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

        $leilao->recebeLance(new Lance($daniel, 6000));
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
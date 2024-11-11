<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

require 'vendor/autoload.php';

$leilao = new Leilao('Honda City 2022');

$halan = new Usuario('Halan');
$daniel = new Usuario('Daniel');

$leilao->recebeLance(new Lance($halan, 100000));
$leilao->recebeLance(new Lance($daniel, 120000));

$leiloeiro = new Avaliador();
$leiloeiro->avalia($leilao);

$maiorValor = $leiloeiro->getMaiorValor();

echo $maiorValor;
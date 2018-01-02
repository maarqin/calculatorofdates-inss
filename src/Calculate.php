<?php
/**
 * Created by PhpStorm.
 * User: thomaz
 * Date: 01/11/17
 * Time: 12:04
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

include_once 'Interval.php';

use src\Interval;

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

    $empresa = $_POST['empresa'];
    $inicios = $_POST['inicio'];
    $fims = $_POST['fim'];

    $adicionals = [];
    for ($i = 0; $i < count($inicios); $i++) {
        $adicionals[$i] = in_array($i, $_POST['adicional']) ? true : false;
    }

    $intervals = [];
    for($i = 0; $i < count($inicios); $i++) {
        $interval = new Interval();

        $interval->setMsg($empresa[$i]);
        $interval->setInit($inicios[$i]);
        $interval->setEnd($fims[$i]);
        $interval->setAdicional($adicionals[$i]);

        $intervals[] = $interval;

    }

    usort($intervals, array("src\Interval", "compare"));

    // Diff para idade
    $diffNascimento = new Interval();
    $diffNascimento->setInit($_POST['nascimento']);
    $diffNascimento->setEnd((new DateTime(date('Y-m-d')))->format('Y-m-d'));

    ?>

    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Calculadora de datas</title>

        <link rel="stylesheet" href="../css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
        <style>
            * {font-size: 12px;}
        </style>
    </head>
    <body>
    <div class="container">
    <div class="row">
    <div class="col-md-12" style="margin-top: 20px">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>Data: <?= date('d/m/Y') ?></td>
                <td rowspan="2" style="vertical-align: middle !important;">Data de nascimento: <?= $diffNascimento->getInitFormated() ?> - <?= $diffNascimento->getDiff()->y ?> anos <?= $diffNascimento->getDiff()->m ?> meses <?= $diffNascimento->getDiff()->d ?> dias</td>
                <td>Sexo</td>
            </tr>
            <tr>
                <td>Nome: <?= $_POST['nome'] ?></td>
                <td><?=  $_POST['sexo'] ?></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th rowspan="2" style="vertical-align: middle !important;">Empresa</th>
                <th rowspan="2" style="vertical-align: middle !important;">Especial</th>
                <th rowspan="2" style="vertical-align: middle !important;">Admissão</th>
                <th rowspan="2" style="vertical-align: middle !important;">Demissão</th>
                <th rowspan="2" style="vertical-align: middle !important;">Ano</th>
                <th rowspan="2" style="vertical-align: middle !important;">Mês</th>
                <th rowspan="2" style="vertical-align: middle !important;">Dia</th>
                <th colspan="3" style="vertical-align: middle !important;">Adicional de atividade especial</th>
                <th colspan="5" style="vertical-align: middle !important;">Intervalos parados</th>
            </tr>
            <tr>
                <th>Ano</th>
                <th>Mês</th>
                <th>Dia</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Ano</th>
                <th>Mês</th>
                <th>Dia</th>
            </tr>
        </thead>
        <tbody>
        <?php

        $daysWorked = 0;
        $daysSpecial = 0;
        $daysOff = 0;

        $daysWorkedD = 0;
        $daysSpecialD = 0;
        $daysOffD = 0;

        $line = '';
        for($i = 0; $i < count($intervals); $i++) {

            $c = $intervals[$i];

            $line .= '<tr>';

            $adc = ( $c->getAdicional() ) ? "Sim" : "Não";

            $line .= '<td>' . $c->getMsg() . '</td>'; // Company name
            $line .= '<td>' . $adc . '</td>'; // 40% ?
            $line .= '<td>' . $c->getInitFormated() . '</td>'; // In
            $line .= '<td>' . $c->getEndFormated() . '</td>'; // Out
            $line .= '<td>' . $c->getDiff()->y . '</td>'; // Year
            $line .= '<td>' . $c->getDiff()->m . '</td>'; // Month
            $line .= '<td>' . $c->getDiff()->d . '</td>'; // Day

            $daysWorked += $c->getDiff()->days;
            $daysWorkedD += $c->getDiff()->d;

            // 40%
            if( $c->getAdicional() ) {
                $sp = specialLine($c, $_POST['sexo']);

                $line .= '<td>' . $sp->y . '</td>'; // Year
                $line .= '<td>' . $sp->m . '</td>'; // Month
                $line .= '<td>' . $sp->d . '</td>'; // Day

                $daysSpecial += $sp->days;
                $daysSpecialD += $sp->d;
            } else {
                $line .= '<td colspan="3"></td>';
            }

            if( $i + 1 < count($intervals) ) {

                $cNext = $intervals[$i + 1];

                $nC = new Interval();

                $nC->setInit(modifyDate($c->getEnd(), 1)->format('Y-m-d'));
                $nC->setEnd(modifyDate($cNext->getInit(), 1, '-')->format('Y-m-d'));

                $line .= '<td>' . $nC->getInitFormated() . '</td>';
                $line .= '<td>' . $nC->getEndFormated() . '</td>';

                $dfOff = $nC->getDiff();

                $line .= '<td>' . $dfOff->y . '</td>'; // Year
                $line .= '<td>' . $dfOff->m . '</td>'; // Month
                $line .= '<td>' . $dfOff->d . '</td>'; // Day

                $daysOff += $dfOff->days;
                $daysOffD += $dfOff->d;

            } else {
                $line .= '<td colspan="5"></td>';
            }

            $line .= '</tr>';
        }
        echo $line;

        // Prepare to foot
        $today = new DateTime(date('Y/m/d'));

        $worked = date_diff($today, modifyDate(date('Y/m/d'), $daysWorked));
        $special = date_diff($today, modifyDate(date('Y/m/d'), $daysSpecial));
        $off = date_diff($today, modifyDate(date('Y/m/d'), $daysOff));

        $sum = date_diff($today, modifyDate(date('Y/m/d'), ($daysWorked + $daysSpecial)));


        ?>
        </tbody>
        <tfoot>

            <tr>
                <td rowspan="7" colspan="4"><!--Observação: --><?/*= $_POST['obs'] */?></td>
            </tr>

            <tr>
                <td colspan="3">Total trabalhado normal</td>
                <td colspan="3">Total trabalhado especial</td>
                <td colspan="2" rowspan="2">Total tempo parado</td>
                <td>Ano</td>
                <td>Mês</td>
                <td>Dia</td>
            </tr>

            <tr>
                <td>Ano</td>
                <td>Mês</td>
                <td>Dia</td>
                <td>Ano</td>
                <td>Mês</td>
                <td>Dia</td>
                <td><?= $off->y ?></td>
                <td><?= $off->m ?></td>
                <td><?= ($daysOffD % 30) ?></td>
            </tr>

            <tr>
                <td><?= $worked->y ?></td>
                <td><?= $worked->m ?></td>
                <td><?= ($daysWorkedD % 30) ?></td>
                <td><?= $special->y ?></td>
                <td><?= $special->m ?></td>
                <td><?= ($daysSpecialD % 30) ?></td>
            </tr>

            <tr>
                <td colspan="6">Soma total normal + especial</td>
            </tr>
            <tr>
                <td colspan="2">Ano</td>
                <td colspan="2">Mês</td>
                <td colspan="2">Dia</td>
            </tr>
            <tr>
                <td colspan="2"><?= $sum->y ?></td>
                <td colspan="2"><?= $sum->m ?></td>
                <td colspan="2"><?= (( $daysWorkedD + $daysSpecialD ) % 30) ?></td>
            </tr>

        </tfoot>

    </table>
    </div>
    </div>
    </div>
    </body>
    </html>

    <?php
}

function specialLine(Interval $i, $sexo) {
    $p = ( $sexo == 'Masculino' ) ? 0.4 : 0.2;

    $days = (int) ($i->getDiff()->days * $p);

    $start_date = new DateTime($i->getEnd());
    $end_date = modifyDate($i->getEnd(), $days + 1);
    return date_diff($start_date,$end_date);
}

function modifyDate($d, $days, $add = '+') {
    return (new DateTime($d))->modify("$add$days days");
}





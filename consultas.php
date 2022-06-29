<?php

//heroku prod
$DB_HEROKU_CHEC = 'heroku_qqkvqh3x';

date_default_timezone_set('America/Bogota');
function saveTextUser($con, $text)
{

    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->insert(
        [
            'TEXTO' => $text
        ]
    );
    $result = $con->executeBulkWrite($GLOBALS['DB_HEROKU_CHEC'] . '.log_text_pqrs', $bulk);
    return $result;
}

function updateTextUser($con, $id)
{
    $bulk = new MongoDB\Driver\BulkWrite();
    $a = $bulk->update(
        ['_id' => $id],
        ['$set' => ['ESTADO' => 'enviado']],
        ['multi' => false, 'upsert' => false]
    );
    $result = $con->executeBulkWrite($GLOBALS['DB_HEROKU_CHEC'] . '.log_text_pqrs', $bulk);
    return $result;
}

function getTextoPqr($con)
{
    $Command = new MongoDB\Driver\Command(
        [
            'aggregate' => 'log_text_pqrs',
            'pipeline' => [
                [
                    '$match' => [
                        'ESTADO' => 'sin enviar'
                    ]
                ]
            ],
            'cursor' => new stdClass(),
        ]
    );
    $result = $con->executeCommand($GLOBALS['DB_HEROKU_CHEC'], $Command);
    $respuesta = $result->toArray();

    return $respuesta;
}

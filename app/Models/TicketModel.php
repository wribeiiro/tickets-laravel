<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketModel extends Model {

    protected $table = 'chamados';
    protected $user_id;

    public function getMyTickets() {
        $sql = "SELECT DISTINCT chamado.*, DATE_FORMAT(chamado.inicio, '%d/%m/%Y') as inicio,
        tipo.nome AS nome_tipo,
        cliente.nome AS nome_cliente,
        modulo.nome AS nome_modulo,
        atendente.nome AS nome_atendente,
        chamado.andamento AS andamento,
        chamado.prioridade AS prioridade
        FROM chamado
        LEFT JOIN evento ON chamado.cod = evento.chamado_cod
        LEFT JOIN AREA ON evento.area_cod = area.cod
        LEFT JOIN tipo ON chamado.tipo_cod = tipo.cod
        LEFT JOIN cliente ON chamado.cliente_cod = cliente.cod
        LEFT JOIN modulo ON chamado.modulo_cod = modulo.cod
        LEFT JOIN atendente ON evento.atendente_cod = atendente.cod
        LEFT JOIN grupo ON cliente.grupo_cod = grupo.cod
        LEFT JOIN orcamento ON chamado.orcamento_cod = orcamento.cod
        WHERE evento.atendente_cod = 8
        GROUP BY chamado.cod
        ORDER BY chamado.inicio DESC";

        $result = DB::select($sql, [$this->user_id]);

        return $result;
    }

    public function getTickets() {

        /*return DB::table('chamados')
            ->select(DB::raw(
                "DATE_FORMAT(chamado.inicio, '%d/%m/%Y') as inicio,
                tipo.nome AS nome_tipo,
                cliente.nome AS nome_cliente,
                modulo.nome AS nome_modulo,
                atendente.nome AS nome_atendente,
                chamado.andamento AS andamento,
                chamado.prioridade AS prioridade
            "))
            ->distinct()
            ->leftJoin('evento', 'chamado.cod', '=', 'evento.chamado_cod')
            ->leftJoin('area', 'evento.area_cod', '=', 'area.cod')
            ->leftJoin('tipo', 'chamado.tipo_cod', '=', 'cliente.cod')
            ->leftJoin('cliente', 'chamado.cliente_cod', '=', 'cliente.cod')
            ->leftJoin('modulo', 'chamado.modulo_cod', '=', 'modulo.cod')
            ->leftJoin('atendente', 'evento.atendente_cod', '=', 'atendente.cod')
            ->leftJoin('grupo', 'cliente.grupo_cod', '=', 'grupo.cod')
            ->leftJoin('orcamento', 'chamado.orcamento_cod', '=', 'orcamento.cod')
            ->groupBy('chamado.cod')
            ->orderBy('chamado.inicio', 'desc')
            ->get();*/

        $sql = "SELECT DISTINCT chamado.*, DATE_FORMAT(chamado.inicio, '%d/%m/%Y') as inicio,
        tipo.nome AS nome_tipo,
        cliente.nome AS nome_cliente,
        modulo.nome AS nome_modulo,
        atendente.nome AS nome_atendente,
        chamado.andamento AS andamento,
        chamado.prioridade AS prioridade
        FROM chamado
        LEFT JOIN evento ON chamado.cod = evento.chamado_cod
        LEFT JOIN AREA ON evento.area_cod = area.cod
        LEFT JOIN tipo ON chamado.tipo_cod = tipo.cod
        LEFT JOIN cliente ON chamado.cliente_cod = cliente.cod
        LEFT JOIN modulo ON chamado.modulo_cod = modulo.cod
        LEFT JOIN atendente ON evento.atendente_cod = atendente.cod
        LEFT JOIN grupo ON cliente.grupo_cod = grupo.cod
        LEFT JOIN orcamento ON chamado.orcamento_cod = orcamento.cod
        GROUP BY chamado.cod
        ORDER BY chamado.inicio DESC";

        $result = DB::select($sql, []);

        return $result;
    }

    public function getTicketsMonth(string $monthYear = null) {

        $filter = "DATE_FORMAT(chamado.inicio, '%m%Y') = DATE_FORMAT(CURDATE(), '%m%Y')";

        if ($monthYear) $filter = "DATE_FORMAT(chamado.inicio, '%m%Y') = ?";

        $sql = "SELECT COUNT(DISTINCT(chamado.cod)) AS total, DATE_FORMAT(chamado.inicio, '%d') as day, DATE_FORMAT(chamado.inicio, '%d/%m/%Y') AS date
        FROM chamado
        LEFT JOIN evento ON chamado.cod = evento.chamado_cod
        LEFT JOIN AREA ON evento.area_cod = area.cod
        LEFT JOIN tipo ON chamado.tipo_cod = tipo.cod
        LEFT JOIN cliente ON chamado.cliente_cod = cliente.cod
        LEFT JOIN modulo ON chamado.modulo_cod = modulo.cod
        LEFT JOIN atendente ON evento.atendente_cod = atendente.cod
        LEFT JOIN grupo ON cliente.grupo_cod = grupo.cod
        LEFT JOIN orcamento ON chamado.orcamento_cod = orcamento.cod
        WHERE $filter
        GROUP BY DAY(chamado.inicio)
        ORDER BY chamado.inicio ASC";

        $result = DB::select($sql, [$monthYear]);

        return $result;
    }

    public function getCardsTickets(string $monthYear = null) {

        $filter = "DATE_FORMAT(chamado.inicio, '%m%Y') = DATE_FORMAT(CURDATE(), '%m%Y')";

        if ($monthYear)
            $filter = "DATE_FORMAT(chamado.inicio, '%m%Y') = ?";

        $sql1 = "SELECT cod FROM chamado";

        $sql2 = "SELECT
            DISTINCT chamado.cod
        FROM
            chamado
        INNER JOIN
            evento ON chamado.cod = evento.chamado_cod
        WHERE
            evento.atendente_cod = ?";

        $sql3 = "SELECT
            DISTINCT chamado.cod
        FROM
            chamado
        INNER JOIN
            evento ON chamado.cod = evento.chamado_cod
        WHERE
            evento.atendente_cod = ? AND
            chamado.situacao = 0 AND
            evento.concluido = 0";

        $sql4 = "SELECT
            DISTINCT chamado.cod
        FROM
            chamado
        INNER JOIN
            evento ON chamado.cod = evento.chamado_cod
        WHERE
            $filter";

        $sql5 = "SELECT COUNT(chamado.cod) / MONTH(CURDATE()) AS avgMonth FROM chamado WHERE YEAR(chamado.inicio) = YEAR(CURDATE())";

        return [
            'totalTickets' => count(DB::select($sql1)),
            'myTickets' => count(DB::select($sql2, [session('sessionUser')['id']])),
            'myOpenTickets' => count(DB::select($sql3, [session('sessionUser')['id']])),
            'totalTicketsMonth' => count(DB::select($sql4, [$monthYear])),
            "avgMonth" => floatVal(DB::select($sql5)[0]->avgMonth)
        ];
    }

}

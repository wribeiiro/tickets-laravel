<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketModel;
use CodeIgniter\HTTP\Response;

class TicketController extends Controller {

    private $ticket_model;
    private $categories = [];

    public function __construct() {
        $this->ticket_model = new TicketModel();
    }

    public function index() {
        return view('tickets.index');
    }

    public function getTickets(Request $request) {
        $result     = $this->ticket_model->getTickets();

        if ($result) {
            $this->data['status'] = 1;
            $this->data['data']   = $result;
        } else {
            $this->data['status'] = 0;
            $this->data['data']   = [];
        }

        return response()
            ->json($this->data, 200);
    }

    public function getChartTickets(Request $request) {

        $monthYear  = $request->monthYear;
        $result     = $this->ticket_model->getTicketsMonth($monthYear);

        foreach ($this->returnDaysOfMonth() as $day) {
            $this->categories[$day] = array('day' => $day, 'date' => 0);
        }

        foreach ($result as $row) {
            $this->categories[$row->day] = array('day' => $row->day, 'date' => $row->total);
        }

        foreach($this->categories as $cat) {
			$categoriesFinal[] = $cat['day'];
			$dataFinal[]       = floatval($cat['date']);
        }

        $this->data['status'] = 1;
        $this->data['data']   = array(
            'categories' => $categoriesFinal,
            'data'       => $dataFinal
        );

        return response()
            ->json($this->data, 200);
    }

    public function getCardTicket(Request $request) {

        $monthYear = $request->monthYear;
        $result    = $this->ticket_model->getCardsTickets($monthYear);

        if ($result) {
            $this->data['status'] = 1;
            $this->data['data']   = $result;
        } else {
            $this->data['status'] = 0;
            $this->data['data']   = [];
        }

        return response()
            ->json($this->data, 200);
    }

    public function returnDaysOfMonth() {

        $lastDay = date("Y-m-t", strtotime('today'));

        for ($data = strtotime(date('Y-m-01')); $data <= strtotime($lastDay); $data = strtotime("+1 day", $data)):
            $arrayData[] = date("d", $data);
        endfor;

        return $arrayData;
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketModel;

class TicketController extends Controller {

    private $objTicket;
    private $categories = [];

    public function __construct() {
        $this->objTicket = new TicketModel();
    }

    public function getTicket(Request $request) { 

        $monthYear  = $request->monthYear;
        $result     = $this->objTicket->getTicketsMonth($monthYear);

        foreach ($this->returnDaysOfMonth() as $day) {
            $this->categories[$day] = [
                'day'  => $day, 
                'data' => 0
            ];
        }

        foreach ($result as $row) {
            $this->categories[$row->day] = array(
                'day'  => $row->day, 
                'data' => $row->total_chamados
            );
        }

        foreach($this->categories as $cat) {
			$categoriesFinal[] = $cat['day'];
			$dataFinal[]       = floatval($cat['data']);   
        }

        $this->data['status'] = 1;
        $this->data['data']   = [
            'categories' => $categoriesFinal,
            'data'       => $dataFinal
        ];

        echo json_encode($this->data);
    }

    public function getCardTicket(Request $request) { 

        $monthYear = $request->monthYear;
        $result    = $this->objTicket->getCardsTickets($monthYear);

        if ($result) {
            $this->data['status'] = 1;
            $this->data['data']   = $result;
        } else {
            $this->data['status'] = 0;
            $this->data['data']   = [];
        }

        echo json_encode($this->data);
    }

    public function returnDaysOfMonth() {

        $last_day = date("Y-m-t", strtotime('today'));
    
        for ($data = strtotime(date('Y-m-01')); $data <= strtotime($last_day); $data = strtotime("+1 day", $data)):
            $datas[] = date("d", $data);
        endfor;
    
        return $datas;
    }
}


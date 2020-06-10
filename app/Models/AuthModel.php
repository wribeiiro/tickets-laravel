<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuthModel extends Model {
    protected $table = 'atendente';

    public function getAllUsers() {
		$sql = "SELECT * FROM atendente";

		return DB::select($sql);
    }

    public function getUser(string $login, string $pass) {
        $sql   = "SELECT * FROM atendente WHERE (cod = '$login' OR nome = '$login') AND senha = '$pass' LIMIT 1";

		return DB::select($sql);
    }
}

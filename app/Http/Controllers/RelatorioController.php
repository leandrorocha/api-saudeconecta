<?php

namespace App\Http\Controllers;

use App\Relatorio;
use App\User;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function create(Request $request)
    {
        $this->validateForm($request);
        $ids = $this->insertRelatorio(
            $request->user(),
            $request->input('relatorio')
        );
        return response($ids);
    }

    private function validateForm(Request $request)
    {
        $request->validate([
            'relatorio.*.teve_febre' => 'required',
            'relatorio.*.teve_tosse' => 'required',
            'relatorio.*.teve_dificuldade_respirar' => 'required',
            'relatorio.*.teve_contato' => 'required',
            'relatorio.*.teve_contato' => 'required',
        ]);
    }

    private function insertRelatorio(User $user, array $relatorios): array
    {
        $ids = [];

        foreach ($relatorios as $relatorio) {
            $relatorioModel = new Relatorio($relatorio);
            $relatorioModel->user_id = $user->id;
            $relatorioModel->save();
            $ids[] = (int) $relatorio['row'];
        }

        return $ids;
    }
}

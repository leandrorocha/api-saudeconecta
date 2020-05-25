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
        return response([
            '_ids' => $ids,
            '_total' => $this->getTotal()
        ]);
    }

    private function validateForm(Request $request)
    {
        $request->validate([
            'relatorio.*.teve_febre' => 'required',
            'relatorio.*.teve_tosse' => 'required',
            'relatorio.*.teve_dificuldade_respirar' => 'required',
            'relatorio.*.teve_dor_garganta' => 'required',
            'relatorio.*.teve_contato' => 'required',
        ]);
    }

    private function insertRelatorio(User $user, array $relatorios): array
    {
        $ids = [];

        foreach ($relatorios as $relatorio) {
            $relatorio['teve_febre'] = $relatorio['teve_febre'] === 'true' ? 1 : 0;
            $relatorio['teve_tosse'] = $relatorio['teve_tosse'] === 'true' ? 1 : 0;
            $relatorio['teve_contato'] = $relatorio['teve_contato'] === 'true' ? 1 : 0;
            $relatorio['teve_dor_garganta'] = $relatorio['teve_dor_garganta'] === 'true' ? 1 : 0;
            $relatorio['teve_dificuldade_respirar'] = $relatorio['teve_dificuldade_respirar'] === 'true' ? 1 : 0;

            $relatorioModel = new Relatorio($relatorio);
            $relatorioModel->user_id = $user->id;
            $relatorioModel->save();
            $ids[] = (int) $relatorio['row'];
        }

        return $ids;
    }

    private function getTotal()
    {
        return Relatorio::count();
    }
}

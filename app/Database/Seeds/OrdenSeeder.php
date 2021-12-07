<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\Centro;
use App\Models\User;
use App\Models\Ubicacion;
use App\Models\Maquina;
use App\Models\Instalacion;

class OrdenSeeder extends Seeder
{
    public function run()
    {
        $model = model('Orden');

        $model->truncate();

        $centro = new Centro();
        $centros = count($centro->findAll());        

        $usuarios = new User();
        $usuarios = count($usuarios->where('type',2)->findAll());

        $ubicacion = new Ubicacion();
        $ubicaciones = count($ubicacion->findAll());

        $maquina = new Maquina();
        $maquinas = count($maquina->findAll());

        $instalacion = new Instalacion();
        $instalaciones = count($instalacion->findAll());

        $ini=static::faker()->dateTimeThisYear('+ 2 month')->format('Y-m-d');
        for($i=0; $i<100; $i++){
            $days=static::faker()->numberBetween(0,30);            
            $dateIni = date('Y-m-d', strtotime($ini. ' + '.$i.' days'));
            $dateFin = date('Y-m-d', strtotime($dateIni. ' + '.$days.' days'));
            $model->insert([
                'tipo' => static::faker()->numberBetween(1,4),
                'solicitante_id' => static::faker()->numberBetween(1,$usuarios),
                'centro_id' => static::faker()->numberBetween(1,$centros),
                'ubicacion_id' => static::faker()->numberBetween(1,$ubicaciones),                
                'maq_inst' => ($i%3==0)? 1 : 0,
                'maquina_id' => ($i%3==0)? null : static::faker()->numberBetween(1,$maquinas),
                'instalacion_id' => ($i%3==0) ? static::faker()->numberBetween(1,$instalaciones) : null,
                'averia' => static::faker()->paragraph(5, false),
                'trabajo' => static::faker()->paragraph(8, false),
                'fecha_inicio' => $dateIni,
                'fecha_fin' => $dateFin,
                'parada' => static::faker()->numberBetween(0,480),
                'estado' => static::faker()->numberBetween(0,5),
            ]);
        }

    }
}

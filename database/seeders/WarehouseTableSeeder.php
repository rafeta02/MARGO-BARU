<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'code'  => 'GUDANG01',
                'name'  => 'Gudang Utama Margo Mitro Joyo',
                'address' => 'Ceplukan, RT 1/16, Wonorejo, Gondangrejo, Karanganyar, Jawa Tengah'
            ],
            [
                'code'  => 'GUDANG02',
                'name'  => 'Gudang Bahan Setengah Jadi Margo Mitro Joyo',
                'address' => 'Ceplukan, RT 1/16, Wonorejo, Gondangrejo, Karanganyar, Jawa Tengah'
            ],
            [
                'code'  => 'GUDANG03',
                'name'  => 'Gudang Plate Matang Margo Mitro Joyo',
                'address' => 'Ceplukan, RT 1/16, Wonorejo, Gondangrejo, Karanganyar, Jawa Tengah'
            ],
        ];
        Warehouse::insert($warehouses);
    }
}

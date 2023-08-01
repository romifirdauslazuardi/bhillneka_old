<?php

namespace App\Imports;

use App\Enums\CostAccountingEnum;
use App\Models\CostAccounting;
use App\Notifications\Failed\FailedCostAccountingNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use DB;
use Error;

class CostAccountingImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading, ShouldQueue, WithCustomCsvSettings
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @param  Collection  $collection
     * @return void
     */
    public function collection(Collection $collection)
    {
        DB::beginTransaction();
        try {
            if (count($collection) <= 0) {
                throw new Error('Data pada excel todal boleh kosong');
            }

            foreach ($collection as $index => $value) {
                $type = $value["type"] ?? null;
                $date = $value["date"] ?? null;
                $name = $value["name"] ?? null;
                $description = $value["description"] ?? null;
                $nominal = $value["nominal"] ?? null;

                if (empty($type)) {
                    throw new Error('Column type pada excel tidak boleh kosong');
                }

                if (empty($date)) {
                    throw new Error('Column date pada excel tidak boleh kosong');
                }

                if (empty($name)) {
                    throw new Error('Column name pada excel tidak boleh kosong');
                }

                if (empty($description)) {
                    throw new Error('Column description pada excel tidak boleh kosong');
                }

                if(!in_array($type,[CostAccountingEnum::TYPE_PEMASUKAN,CostAccountingEnum::TYPE_PENGELUARAN])){
                    throw new Error('Nilai type pada excel hanya diperbolehkan 1 (pemasukan) / 2 (pengeluaran ');
                }

                $create = new CostAccounting();
                $create = $create->create([
                    'type' => $type,
                    'date' => (!empty($date)) ? Date::excelToDateTimeObject($date)->format('Y-m-d') : null,
                    'name' => $name,
                    'description' => $description,
                    'nominal' => $nominal,
                    'user_id' => $this->user->id ?? null,
                    'business_id' => $this->user->business_id ?? null,
                    'author_id' => $this->user->id ?? null,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            $message = $th->getMessage();
            $this->user->notify(new FailedCostAccountingNotification($message));
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => "\t",
        ];
    }
}

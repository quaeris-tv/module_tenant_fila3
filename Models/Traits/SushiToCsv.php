<?php

/**
 * @see https://dev.to/hasanmn/automatically-update-createdby-and-updatedby-in-laravel-using-bootable-traits-28g9.
 */

declare(strict_types=1);

namespace Modules\Tenant\Models\Traits;

use Illuminate\Support\Facades\File;
use Modules\Tenant\Services\TenantService;
use League\Csv\Reader;
use League\Csv\Writer;


use function Safe\json_encode;
use function Safe\unlink;

use Webmozart\Assert\Assert;

trait SushiToCsv
{
    use \Sushi\Sushi;

    public function getSushiRows(): array
    {
        // return CSV::fromFile(__DIR__.'/roles.csv')->toArray();
        // load the CSV document from a file path
        $csv = Reader::createFromPath($this->getCsvPath(), 'r');
        // $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);
        // returns all the records as
        $records = $csv->getRecords(); // an Iterator object containing arrays
        // $records = $csv->getRecordsAsObject(MyDTO::class); // an Iterator object containing MyDTO objects
        $rows = iterator_to_array($records);
        $rows= array_values($rows);
        return $rows;
    }

    public function getCsvPath(): string
    {
        Assert::string($tbl = $this->getTable());
        $file=$tbl.'.csv';
        $path = TenantService::filePath($file);

        return $path;
    }

    /**
     * bootUpdater function.
     */
    protected static function bootSushiToCsv(): void
    {
        /*
         * During a model create Eloquent will also update the updated_at field so
         * need to have the updated_by field here as well.
         */
        static::creating(
            function ($model): void {
                
                $model->id = intval($model->max('id')) + 1;
                $model->updated_at = now();
                $model->updated_by = authId();
                $model->created_at = now();
                $model->created_by = authId();
                
                $data = $model->toArray();
                $writer = Writer::createFromPath($model->getCsvPath(), 'a+'); // 'a+' per appendere al file
                $reader = Reader::createFromPath($model->getCsvPath(), 'r');
                $reader->setHeaderOffset(0);
                
                $item = [];
                foreach ($reader->getHeader() as $name ) {
                    $value = $data[$name] ?? null;
                    $item[$name] = $value;
                }
                
                $res=$writer->insertOne($item);
                //dddx($res);// 4 ??
                /*
                
                $content = json_encode($item, JSON_PRETTY_PRINT);
                $file = $model->getJsonFile();
                if (! File::exists(\dirname($file))) {
                    File::makeDirectory(\dirname($file), 0755, true, true);
                }
                File::put($file, $content);
                */
            }
        );
        /*
         * updating.
         */
        static::updating(
            function ($model): void {
                $file = $model->getJsonFile();
                $model->updated_at = now();
                $model->updated_by = authId();
                $content = $model->toJson(JSON_PRETTY_PRINT);
                File::put($file, $content);
            }
        );
        // -------------------------------------------------------------------------------------
        /*
         * Deleting a model is slightly different than creating or deleting.
         * For deletes we need to save the model first with the deleted_by field
        */

        static::deleting(
            function ($model): void {
                unlink($model->getJsonFile());
            }
        );

        // ----------------------
    }
}
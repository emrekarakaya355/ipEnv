<?php

namespace App\Imports;

use App\Exports\FailuresExport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
abstract class BaseImport implements ToCollection, WithHeadingRow, SkipsOnError, SkipsOnFailure, WithSkipDuplicates, WithEvents
{

    use Importable, RegistersEventListeners, SkipsErrors, SkipsFailures;
    protected $failures = [];
    protected $rowNumber = 0;


    /**
     * Abstract method to handle each row after validation
     * @param array $row
     */
    abstract protected function processRow(array $row);

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->setRowNumber();

            try {
                // İşlem yapılacak satırı `processRow` metodunda yönetin
                $this->processRow($row->toArray());
            } catch (\Exception $e) {
                // Eğer bir hata varsa, bu hatayı Failure olarak kaydedelim
                $this->fail($row->toArray(), [$e->getMessage()]);
            }
        }
    }
    protected function fail(array $row, array $messages)
    {
        $rowNumber = $this->getRowNumber();
        // Başarısızlığı kaydedin
        $this->failures[] = new Failure($rowNumber, implode(",", $row), $messages, $row);
    }

    protected function getRowNumber()
    {
        return $this->rowNumber;
    }

    protected function setRowNumber()
    {
        $this->rowNumber++;
    }
    public function onFailure(Failure ...$failures){
        // Başarısız olan satırları topla
        foreach ($failures as $failure) {
            $this->failures[] = $failure;
        }
    }


    /**
     * AfterImport event handler
     */
    public static function afterImport(AfterImport $event)
    {
        /*
        // Burada hatalı verileri export edeceğiz
        $importInstance = $event->getConcernable();
        if (!empty($importInstance->failures)) {
            $importInstance->exportFailures();
        }*/
    }
    /**
     * Başarısız satırları export et
    */
    public function exportFailures()
    {
        // Export için yeni bir Excel export sınıfı oluşturulabilir
        return \Maatwebsite\Excel\Facades\Excel::download(new FailuresExport($this->failures), 'failed_imports.xlsx');
    }

    public function getFailures()
    {
        return $this->failures;
    }

    // This method can be used to register the failure
    protected function isUniqueConstraintViolation(\Exception $e)
    {
        return $e->getCode() === '23000'; // Hata kodunu kontrol et
    }




    protected function getDeviceType(){

    }

    protected function getLocation(){

    }


}

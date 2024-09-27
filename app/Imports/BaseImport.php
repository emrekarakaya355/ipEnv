<?php

namespace App\Imports;

use App\Exports\FailuresExport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
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
abstract class BaseImport implements ToModel, WithHeadingRow , SkipsOnError, SkipsOnFailure, WithSkipDuplicates, WithEvents
{

    use Importable, RegistersEventListeners,SkipsErrors, SkipsFailures;
    protected $failures = [];
    protected $rowNumber = 0;


    /**
     * @param array $row
     */
    abstract public function model(array $row);

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    /*
    public static function afterSheet(AfterSheet $event)
    {
        $importInstance = $event->getConcernable();

        // Hatalı satırları işleyip renklendirme ve mesaj ekleme
        if (!empty($importInstance->failures)) {
            $sheet = $event->sheet->getDelegate();
            $lastColumn = $sheet->getHighestColumn();
            $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($lastColumn);
            $errorColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColumnIndex + 1);

            // Yeni bir sütun ekleyip hata mesajlarını buraya yazalım
            $sheet->setCellValue("{$errorColumn}1", 'Error Message');

            foreach ($importInstance->failures as $failure) {

                $rowNumber = $failure->row();
                $errorMessages = implode(", ", $failure->errors());
                // Hatalı satırların rengini değiştirme
                $sheet->getStyle("A{$rowNumber}:{$lastColumn}{$rowNumber}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FFCCCC'],
                    ],
                ]);

                // Hata mesajını yeni bir sütuna yazalım
                $sheet->setCellValue("{$errorColumn}{$rowNumber}", $errorMessages);
            }

            // Dosyayı kaydetme
            $writer = new Xlsx($event->sheet->getDelegate()->getParent());
            $filePath = storage_path('C:/Users/NEVU/Desktop/test/failed_imports.xlsx'); // Kaydedilecek dosya yolu
            $writer->save($filePath);
            return response()->download($filePath)->deleteFileAfterSend(true); // Dosyayı indir ve ardından sil
        }

        return null ;
    }
*/
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

    public  function getRowNumber()
    {
        return $this->rowNumber;
    }
    public  function setRowNumber()
    {
        $this->rowNumber++;
    }

    protected function fail(array $row, array $messages)
    {
        $rowNumber = $this->getRowNumber();
        // Add the Failure instance to the import failures
        $this->failures[] = new Failure($rowNumber, implode(",", $row), $messages, $row);
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

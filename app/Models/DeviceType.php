<?php

namespace App\Models;

use App\Exceptions\ConflictException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NotFoundException;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use function PHPUnit\Framework\assertIsInt;


class DeviceType extends Model  implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'device_types';
    protected $fillable = ['type', 'brand','model','port_number','deleted_at'];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {        // Silinmiş bir kaydı kontrol et
            if ($model->type == 'switch' && !$model->port_number) {
                throw new \Exception('porta sayısını girmeniz gerekiyor');
            }
            if ($model->type == 'access_point' && $model->port_number) {
                $model->port_number = null;
            }
            // Var olan bir kaydı kontrol et (unique validation)
            $existingModel = DeviceType::where([
                ['type', '=', $model->type],
                ['brand', '=', $model->brand],
                ['model', '=', $model->model],
                ['port_number', '=', $model->port_number]
            ])->first();

            if ($existingModel && $existingModel->id !== $model->id) {
                throw new ConflictException("Cihaz Tipi Bilgisi Zaten Var!");
            }

        });
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
    public function scripts()
    {
        return $this->belongsToMany(Script::class, 'script_device_type', 'device_type_id', 'script_id');
    }



    public static function getBrandsByType($type)
    {

        return static::where('type', $type)->distinct('brand')->pluck('brand');
    }
    public static function getModelsByBrand($type, $brand)
    {
        return static::where('type', $type)->where('brand', $brand)
                ->select('model', 'port_number')
                ->distinct()
                ->get();
    }
    public function scopeSorted($query)
    {
        return $query->orderBy('type')
            ->orderBy('brand')
            ->orderBy('model')
            ->orderBy('port_number');
    }
    public static function getDeviceType($type, $model, $brand)
    {


        $modelParts = explode('(', $model, 2); // 2, yalnızca ilk bulduğu paranteze göre ayırmasını sağlar
        $modelName = trim($modelParts[0]); // İlk parçayı al ve boşlukları temizle

        // Eğer ikinci parça varsa (port numarası) onu al
        $port = isset($modelParts[1]) ? rtrim(trim($modelParts[1]), ')') : null; // Sonundaki ')' karakterini de kaldır

        if(is_numeric($port)){
            $port = (int)$port;
        }else{
            $port = null;
        }

        // DeviceType bulma
        $deviceType = DeviceType::where('type', $type)
            ->where('brand', $brand)
            ->where('model', $modelName)
            ->where('port_number', $port)
            ->first();



        if($deviceType === null){
            throw New ModelNotFoundException('Cihaz Tipi Bulunamadı!');
        }
        return $deviceType;
    }
    public static function getColumnMapping()
    {
        return [
            'Cihaz Tipi' => 'type',
            'Marka' => 'brand',
            'Model' => 'model',
            'Port Adet' => 'port_number',
        ];
    }
    /**
     * Update the record if it exists, or create it if it doesn't.
     *
     * @param array $attributes
     * @param array $values
     * @return bool|int
     * @throws Exception
     */
    /*
            public static function updateOrCreate(array $attributes, array $values = []): bool|int
            {
                // Silinmiş bir kaydı kontrol et
                $existingModel = static::withTrashed()->where($attributes)->first();

                if ($existingModel ) {
                    // Kayıt silinmişse, restore et
                    if( $existingModel->trashed()){

                        try {
                            $existingModel->restore();
                        } catch (Exception $e) {
                            // Hatanın ne olduğunu görmek için loglama veya hata mesajı
                            throw new Exception('Kayıt geri yüklenemedi, lütfen tekrar deneyiniz.');
                        }
                    }
                     return $existingModel->update($values);
                } else {
                    // Silinmemiş veya mevcut kayıt yoksa, yeni bir kayıt oluştur
                    return DeviceType::create($attributes);

                }
            }
    */
}

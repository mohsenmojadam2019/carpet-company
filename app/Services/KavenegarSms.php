<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class KavenegarSms
{
    public function send(string $receptor, string $message): array
    {
        if ($this->sandbox()) {
            Log::info('Kavenegar sandbox: SMS suppressed.', ['receptor'=>Str::mask($receptor,'*',4)]);
            return [['sandbox'=>true,'status'=>'suppressed']];
        }
        $apiKey=$this->apiKey();
        if(!$apiKey)return [];
        $response=Http::asForm()->timeout(8)->retry(2,200)->post("https://api.kavenegar.com/v1/{$apiKey}/sms/send.json",[
            'receptor'=>$receptor,'sender'=>Setting::valueOf('kavenegar_sender',config('services.kavenegar.sender')),'message'=>$message,
        ])->throw()->json();
        if((int)data_get($response,'return.status')!==200)throw new RuntimeException((string)data_get($response,'return.message','Kavenegar error'));
        return (array)data_get($response,'entries',[]);
    }

    public function verifyLookup(string $receptor,string $token,?string $template=null):array
    {
        if($this->sandbox())return [['sandbox'=>true,'status'=>'suppressed']];
        $apiKey=$this->apiKey();
        if(!$apiKey)return [];
        $response=Http::asForm()->timeout(8)->retry(2,200)->post("https://api.kavenegar.com/v1/{$apiKey}/verify/lookup.json",[
            'receptor'=>$receptor,'token'=>$token,'template'=>$template?:Setting::valueOf('kavenegar_verify_template',config('services.kavenegar.verify_template')),
        ])->throw()->json();
        if((int)data_get($response,'return.status')!==200)throw new RuntimeException((string)data_get($response,'return.message','Kavenegar error'));
        return (array)data_get($response,'entries',[]);
    }

    private function sandbox():bool{return (bool)Setting::valueOf('kavenegar_sandbox',config('services.kavenegar.sandbox',true));}
    private function apiKey():string{return (string)Setting::valueOf('kavenegar_api_key',config('services.kavenegar.api_key'));}
}

<?php

namespace App\Exports;

use App\Models\Block;
use App\Models\City;
use App\Models\Shop;
use App\Models\SubDistrict;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SellersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $districts = City::pluck('name', 'id');
        $blocks = Block::pluck('name', 'id');
        $subDistricts = SubDistrict::pluck('name', 'id');

        return Shop::with('user')->get()->map(function ($shop) use ($districts, $blocks, $subDistricts) {
            $user = $shop->user;

            return [
                'name' => optional($user)->name ?? '',
                'email' => optional($user)->email ?? '',
                'gender' => optional($user)->gender ?? '',
                'father_husband_name' => optional($user)->father_husband_name ?? '',
                'dob' => optional($user)->dob ?? '',
                'age' => optional($user)->age ?? '',
                'aadhaar' => optional($user)->aadhaar ?? '',
                'pan' => optional($user)->pan ?? '',
                'address' => optional($user)->address ?? '',
                'state' => optional($user)->state ?? '',
                'district' => $districts[optional($user)->district] ?? '',
                'block' => $blocks[optional($user)->block] ?? '',
                'sub_district' => $subDistricts[optional($user)->sub_district] ?? '',
                'city' => optional($user)->city ?? '',
                'postal_code' => optional($user)->postal_code ?? '',
                'phone' => optional($user)->phone ?? '',
                'alternate_phone' => optional($user)->alternate_phone ?? '',
                'whatsapp_number' => optional($user)->whatsapp_number ?? '',
                'qualification' => optional($user)->qualification ?? '',
                'experience' => optional($user)->experience ?? '',
                'shop_address' => $shop->address,
                'shop_size' => $shop->shop_size,
                'rent_type' => $shop->rent_type,
                'monthly_rent' => $shop->monthly_rent,
                'bank_acc_no' => $shop->bank_acc_no,
                'bank_name' => $shop->bank_name,
                'bank_acc_name' => $shop->bank_acc_name,
                'bank_routing_no' => $shop->bank_routing_no,
                'security_deposit' => $shop->security_deposit,
                'payment_status' => $shop->payment_status,
                'payment_mode' => $shop->payment_mode,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'name', 'email', 'gender', 'father_husband_name', 'dob', 'age',
            'aadhaar', 'pan', 'address', 'state', 'district', 'block',
            'sub_district', 'city', 'postal_code', 'phone', 'alternate_phone',
            'whatsapp_number', 'qualification', 'experience', 'shop_address',
            'shop_size', 'rent_type', 'monthly_rent', 'bank_acc_no', 'bank_name',
            'bank_acc_name', 'bank_routing_no', 'security_deposit',
            'payment_status', 'payment_mode'
        ];
    }
}

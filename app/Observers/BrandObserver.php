<?php

namespace App\Observers;

use App\Models\Brand;
use App\Models\BrandChangeRequest;

class BrandObserver
{
    /**
     * Handle the Brand "deleting" event.
     *
     * @param Brand $brand
     * @return void
     */
    public function deleting(Brand $brand)
    {
        $brand->image()->delete();
        $brand->tags()->detach();

        BrandChangeRequest::where('brand_id', '=', $brand->id)->delete();
    }

    /*TODO:: IMPORTOT IS RAKD RENDBE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     *
     *created_at: első elfogadás dátuma (amikor a brand táblában létrejön a rekord). Ha admin hozza létre, értelemszerűen a létrehozás dátuma.

updated_at: utolsó modsítás dátuma. Change request elfogadásakor az elfogadás dátuma.

created_by: a beküldő user ID-ja, ha admin töltötte fel, az admin user ID-ja.
     *
     *
     *
     *
     */
}

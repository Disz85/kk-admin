# Krémmánia admin

## Servers and metadata

 - Site URLs:
   - Development: http://admin.kremmania.local
   - Test: https://admin.kremmania.p24.hu
   - Production: https://admin.kremmania.hu
 - Laravel version: `9.41`
 - Node version: `19`
 - PHP version: `8.1`

## Tips for backend development

### Makefile targets

For more target, run `make` or `make --help`.

__Check code format__

`make dry-format` (PHP-CS-Fixer)

`make analyze` (Larastan - level: 6)

__Fix code format__

`make format` (PHP-CS-Fixer)

__Run `PhpUnit` tests__

`make test`

### API fejlesztés: kapcsolt modellek védelme törlés ellen

1) Implementáld az `App\Interfaces\HasDependencies` interfészt a védeni kívánt modellen! A `hasDependencies()` függvénynek `true` értéket kell visszaadnia, ha legalább egy kapcsolat fennáll más modellekkel. A validáció során ez a metódus hívódik meg a kapcsolatok ellenőrzése céljából. Az alábbi példában a `Brand` modellt védjük majd a törlés ellen, ha már egy termék hozzá van kapcsolva.

```php
class Brand extends Model implements HasDependencies {

    //...

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function hasDependencies(): bool
    {
        return $this->products()->exists();
    }

}
```

3) Származtassunk le a `FormRequest` osztályból egy törlési request osztályt, amit a kontroller `destroy()` metódusának adjunk át! Ezen az osztályon keresztül fogja a Laravel meghívni a validációt. A `prepareForValidation()` metódussal vegyünk fel egy új mezőt ellenőrzésre, majd a `rules()` függvényből visszaadott tömbbe vegyünk fel a definiált mezőre egy `CheckDependencies()` validációs szabályt, aminek átadjuk a requestben kapott modell objektumot (esetünkben ez most a `brand`).

```php
<?php

namespace App\Http\Requests;

use App\Rules\CheckDependencies;
use Illuminate\Foundation\Http\FormRequest;

class DeleteBrandRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'brand' => new CheckDependencies($this->brand),
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'brand' => $this->brand,
        ]);
    }

}

```
A kontroller `destroy()` metódusának adjuk át a fentebb implementált törlési requestet, hogy a Laravel meghívja rajta a validációt.

```php
    public function destroy(DeleteBrandRequest $request, Brand $brand): JsonResponse
    {
        $brand->image()->delete();
        $brand->deleteOrFail();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
```

Ha legalább egy kapcsolat létezik, akkor a request 422-es HTTP kóddal és a `Resource cannot be deleted due to existence of related resources` hibaüzenettel tér majd vissza ahelyett, hogy lefutna a `destroy()` metódus.

### Elastic

Az elastichoz ne felejts el futtatni queue workert.

`php artisan scout:import App\\Models\\Product` commanddal tudod felindexelni a Product modellt.

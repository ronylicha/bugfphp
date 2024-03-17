<?php
/**
 * Rony Licha
 * CreateProduct.php
 *
 *
 * @category Production
 * @package  Default
 * @date     15.03.2024 15:34
 */

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}

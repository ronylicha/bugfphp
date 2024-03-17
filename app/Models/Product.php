<?php
/**
 * Rony Licha
 * Product.php
 *
 *
 * @category Production
 * @package  Default
 * @date     15.03.2024 15:34
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * This class represents a product in the system.
 * It extends the Model class and uses the HasFactory trait.
 *
 * @package App\Models
 *
 * @property string $name
 * @property int $qte
 * @property float $value
 * @property float $com_to_pay
 * @property boolean $com_payed
 * @property Carbon $date_com_payed
 * @property float com_to_cancel
 * @property boolean $com_cancel
 * @property Carbon $date_com_cancel
 * @property int $user_id
 * @property Collection $user
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "value",
        "user_id",
        "com_payed",
        "com_cancel",
        "date_com_cancel",
        "date_com_payed",
        "qte",
        "com_to_pay",
        "com_to_cancel",
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

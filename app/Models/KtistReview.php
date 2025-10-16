namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KtistReview extends Model
{
    protected $fillable = ['ktist_id', 'user_id', 'rating', 'review'];

    public function ktist()
    {
        return $this->belongsTo(User::class, 'ktist_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

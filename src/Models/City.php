<?php
namespace Coolsam\World\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Coolsam\World\WorldTrait;

/**
 * City
 */
class City extends Model
{
    use WorldTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'world_cities';
    protected $collection = 'world_cities';

    /**
     * append names
     *
     * @var array
     */
    protected $appends = ['local_name','local_full_name','local_alias', 'local_abbr'];
    
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
    
    public function division()
    {
        return $this->belongsTo(Division::class,'division_id', 'id');
    }

    public function children()
    {
        return null;
    }

    public function parent()
    {
        if ($this->division_id === null) {
            return $this->country;
        } else {
            return $this->division;
        }
    }

    public function locales()
    {
        return $this->hasMany(CityLocale::class, 'city_id','id');
    }

    /**
     * Get City by name
     *
     * @param string $name
     * @return collection
     */
    public static function getByName($name)
    {
        $localed = CityLocale::where('name', $name)->first();
        if (is_null($localed)) {
            return $localed;
        } else {
            return $localed->city;
        }
    }

    /**
     * Search City by name
     *
     * @param string $name
     * @return collection
     */
    public static function searchByName($name)
    {
        return CityLocale::where('name', 'like', "%".$name."%")
            ->get()->map(function ($item) {
                return $item->city;
            });
    }
}

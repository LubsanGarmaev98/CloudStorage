<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'second_name',
        'email',
        'password',
        'max_size_disk'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function files()
    {
        return $this->hasMany(File::class, 'user_id', 'id');
    }

    public function folders()
    {
        return $this->hasMany(Folder::class, 'user_id', 'id');
    }

    public function setMaxSizeDisk(int $size)
    {
        $this->max_size_disk = $size;
    }
    public function getMaxSize(): int
    {
        return $this->max_size_disk;
    }

    public function getId(): int
    {
       return $this->id;
    }

    public function getCurrentSize()
    {
        $user = $this::with('files')->first();
        $sizeFiles = 0;

        foreach ($user->getRelation('files') as $file) {
            $sizeFiles += $file->getSize();
        }

        return $sizeFiles;
    }

    public function setFirstName(string $firstName)
    {
        $this->first_name = $firstName;
    }

    public function setSecondName(string $secondName)
    {
        $this->second_name = $secondName;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }
}

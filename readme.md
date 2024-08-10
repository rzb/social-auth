# SocialAuth

Socialite wrapper that handles all the remaining social authentication boilerplate, exposing endpoints ready for use that enable multiple providers at once for any model(s) you need.

## Installation

1) Require via Composer

``` bash
$ composer require rzb/socialauth
```

2) Run auto-discovered migrations

``` bash
$ php artisan migrate
```

3) Add the contracts and traits to the User model, or to whatever model(s) you need

``` php
<?php

namespace App\Models\User;

use Rzb\SocialAuth\Contracts\Resourceable as ResourceableContract;
use Rzb\SocialAuth\Contracts\Sociable as SociableContract;
use Rzb\SocialAuth\Traits\Resourceable;
use Rzb\SocialAuth\Traits\Sociable;
// ...

class User extends AuthUser implements SociableContract, ResourceableContract
{
    use Resourceable;
    use Sociable;
    // ...
}
```

4) *(optional)* If you need to authenticate any model(s) other than the User, add/remove providers or tweak the exposed Routes, publish the config file

``` bash
$ php artisan vendor:publish --provider="Rzb\SocialAuth\SocialAuthServiceProvider" --tag="config"
```

## Usage

For most projects, you won't have to do anything at all after installation. The package automatically exposes the endpoints and enables Google, Facebook and Twitter providers for the User model.

If however you have different needs, you're free to tweak the configuration, use your own controller or even implement your own DB logic.

### Customize supported models and providers

You can support authentication for any model you want.

1) Add an entry to the `sociables` key in the config file, indicating the model class and the providers you want to allow specifically for that model.


``` php
    'sociables' => [
    
        // default model. You can remove it or tweak it.
        'user' => [
            'model' => User::class,
            'providers' => [
                'google',
                'facebook',
                'twitter',
            ],
        ],
        
        // Customer model example
        // 'customer' => [
        //     'model' => Customer::class,
        //     'providers' => [
        //         'google',
        //         'github',
        //     ],
        // ],
    ],
``` 

2) Next, make sure your model implements and uses the following contracts and traits.

``` php

// Transforms User profile info from the social account to your sociable model.
use Rzb\SocialAuth\Contracts\Sociable as SociableContract;
use Rzb\SocialAuth\Traits\Sociable;

// Converts your sociable model to its respective JsonResource class. 
// Only needed if you're using the package's default controller.
use Rzb\SocialAuth\Contracts\Resourceable as ResourceableContract;
use Rzb\SocialAuth\Traits\Resourceable;
```

### Customize routes and controller

By default, the package exposes 2 stateless routes that expect the same 2 segments:

#### Routes
- Returns the auth URL for the given provider, so your frontend can load the provider's auth page.
    ``` md
    GET  /auth/social/{provider}/{sociable}
    ```
- Callback route that finds or creates the given sociable model and returns its JsonResource.
    ``` php
    POST /auth/social/{provider}/{sociable}
    [
        'access_token' => 'required|string' // incoming token from provider
    ]
    ```

#### Segments
- `provider` - the provider name. e.g. google, facebook, etc.

- `sociable` - the name of the sociable model as per your config file. e.g.user, customer, etc.
    

You can apply middlewares and route prefix to both routes. Or even replace its controller with your own.
``` php
    'routes' => [
        'controller' => SocialAuthController::class,
        'middleware' => null,
        'prefix' => 'auth/social',
    ],
```

### Customize DB logic
The `Sociable` trait covers a basic User model with username and password. If your model has a different structure you may want to define your own `createFromSocialUser` method.
``` php
use Laravel\Socialite\Contracts\User as SocialUser;

public static function createFromSocialUser(SocialUser $socialUser): self
{
    return self::firstOrCreate([
        'email' => $socialUser->getEmail(),
    ], [
        // map your model attributes here
    ]);
}
```


## Credits

- [rzb][link-author]
- [All Contributors][link-contributors]

[ico-version]: https://img.shields.io/packagist/v/rzb/socialauth.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rzb/socialauth.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rzb/socialauth/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/rzb/socialauth
[link-downloads]: https://packagist.org/packages/rzb/socialauth
[link-travis]: https://travis-ci.org/rzb/socialauth
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/rzb
[link-contributors]: ../../contributors

# Authenticated links

Authenticated expiring links

Generate links that are valid for certain amount of time

Generator and Authenticator share a secret and configuration to
allow restricted access behind authenticator.

Generator can generate links that are valid for certain amount of time
and authenticator will check validity of link before allowing access.


## Usage

### Generate link

```php
<?php
$authlink = new Mama\Authlink\Authlink();

$lifetime = 60; //seconds
$authlink = $authlink->generate($lifetime);
```

### Validate link

```php
<?php
$authlink = new Mama\Authlink\Authlink();

if ($authlink->validate($authlink)) {
  // VALID
} else {
  // INVALID
}
```

## Testing

Run tests with command

```bash
vendor/bin/phpunit -c test/
```

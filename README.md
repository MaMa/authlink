# Authenticated links

Authenticated expiring links

Generate links that are valid for certain amount of time

Generator and Authenticator share a secret and configuration to
allow restricted access behind authenticator.

Generator can generate links that are valid for certain amount of time
and authenticator will check validity of link before allowing access.


## Usage

Constructor takes parameters as either secret string:
```php
<?php
$Authlink = new Authlink('SharedSecretString')
```

### Generate link

```php
<?php
$settings = array(
  'secret' => 'MySuperSecretString',
  'lifetime' => 60 // 60 seconds = 1 minute
);

$Authlink = new Mama\Authlink\Authlink($settings);

$link = $Authlink->generate();
```

### Validate link

```php
<?php
$secret = 'MySuperSecretString';
$Authlink = new Mama\Authlink\Authlink($secret);

if ($Authlink->validate($link)) {
  // Link is valid
} else {
  // Link is invalid or expired
}
```

## Testing

Run tests with command

```bash
vendor/bin/phpunit -c test/
```

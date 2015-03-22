# Authenticated links

Authenticated expiring links

Generate links that are valid for certain amount of time

Generator and Authenticator share a secret and configuration to
allow restricted access behind authenticator.

Generator can generate links that are valid for certain amount of time
and authenticator will check validity of link before allowing access.


## Usage

### Generator

```
$generator = new mama\authlink\Generator();

$lifetime = 60; //seconds
$authlink = $generator->generate($lifetime);
```

### Validator

```
$validator = new mama\authlink\Validator();

if ($validator->validate($authlink)) {
  // VALID
} else {
  // INVALID
}
```

## Project Init

#### Install packages
```
composer install
```

#### Run Migrations
```
php yii migrate
php yii migrate --migrationPath=@yii/rbac/migrations
```

#### Init RBAC
```
php yii rbac/init
```

## Admin User

The user with id=1 will have an admin role (the first created user)